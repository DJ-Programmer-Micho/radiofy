from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json
import os

app = Flask(__name__)

# In‑memory dictionaries for radio configurations and FFmpeg processes.
# Format: { radio_id: { 'source_url': ..., 'mount': ..., 'bitrate': ..., 'password': ..., 'host': ..., 'port': ... } }
radio_config = {}
# Format: { radio_id: { 'process': FFmpeg process, 'start_time': timestamp, 'restart_count': int } }
radio_processes = {}

# Create logs directory if it doesn't exist
os.makedirs('logs', exist_ok=True)

def start_ffmpeg_process(radio_id, config):
    log_path = f"logs/radio_{radio_id}.log"
    log_file = open(log_path, "a")
    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"\n\n[{timestamp}] Starting new FFmpeg process for radio {radio_id}\n")
    
    # Updated FFmpeg command with fallback using anullsrc and duration=longest.
    ffmpeg_command = [
        "ffmpeg",
        "-reconnect", "1",
        "-reconnect_streamed", "1",
        "-reconnect_delay_max", "5",
        "-timeout", "60000000",  # 60 seconds
        "-bufsize", "256k",
        "-i", config['source_url'],
        "-f", "lavfi", "-i", "anullsrc=r=44100:cl=mono",
        "-filter_complex", "[0:a][1:a]amix=inputs=2:duration=longest:dropout_transition=3",
        "-c:a", "libmp3lame",
        "-b:a", f"{config['bitrate']}k",
        "-ar", "44100",
        "-f", "mp3",
        f"icecast://source:{config['password']}@{config['host']}:{config['port']}{config['mount']}"
    ]
    
    command_str = ' '.join(ffmpeg_command)
    print(f"Starting stream for radio {radio_id} with command: {command_str}")
    log_file.write(f"Command: {command_str}\n")
    log_file.flush()
    
    process = subprocess.Popen(
        ffmpeg_command,
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
        bufsize=1,
        universal_newlines=True
    )
    
    # Thread to log FFmpeg stderr output
    def monitor_output():
        for line in process.stderr:
            ts = time.strftime("%Y-%m-%d %H:%M:%S")
            log_entry = f"[{ts}] {line.strip()}"
            print(f"FFmpeg (radio {radio_id}): {line.strip()}")
            log_file.write(log_entry + "\n")
            log_file.flush()
    
    monitor_thread = threading.Thread(target=monitor_output, daemon=True)
    monitor_thread.start()
    
    return {
        'process': process,
        'start_time': time.time(),
        'restart_count': 0,
        'log_file': log_file,
        'monitor_thread': monitor_thread
    }

def restart_ffmpeg_process(radio_id, config):
    if radio_id in radio_processes:
        print(f"Restarting stream for radio {radio_id}")
        try:
            if radio_processes[radio_id]['process'].poll() is None:
                radio_processes[radio_id]['process'].terminate()
                for _ in range(10):
                    if radio_processes[radio_id]['process'].poll() is not None:
                        break
                    time.sleep(0.1)
                if radio_processes[radio_id]['process'].poll() is None:
                    radio_processes[radio_id]['process'].kill()
            if 'log_file' in radio_processes[radio_id]:
                radio_processes[radio_id]['log_file'].close()
        except Exception as e:
            print(f"Error shutting down FFmpeg for radio {radio_id}: {e}")
    
    radio_processes[radio_id] = start_ffmpeg_process(radio_id, config)
    radio_processes[radio_id]['restart_count'] = radio_processes.get(radio_id, {}).get('restart_count', 0)

def update_radio(radio_id, new_config):
    global radio_config, radio_processes
    if radio_id in radio_config:
        if radio_config[radio_id] != new_config:
            radio_config[radio_id] = new_config
            restart_ffmpeg_process(radio_id, new_config)
        else:
            print(f"No changes detected for radio {radio_id}.")
    else:
        radio_config[radio_id] = new_config
        radio_processes[radio_id] = start_ffmpeg_process(radio_id, new_config)

@app.route("/update_radio_config", methods=["POST"])
def update_radio_config():
    try:
        data = request.json
        radio_id = int(data.get("radio_id"))
        if not radio_id:
            return jsonify({"error": "Missing radio_id"}), 400

        new_config = data.get("config")
        if not new_config:
            return jsonify({"error": "Missing config data"}), 400

        update_radio(radio_id, new_config)
        return jsonify({"status": "success"}), 200
    except Exception as e:
        print(f"Exception in update_radio_config: {e}")
        return jsonify({"error": str(e)}), 500

@app.route("/health", methods=["GET"])
def health():
    health_info = {"status": "ok", "radios": {}}
    for radio_id, info in radio_processes.items():
        running = info['process'].poll() is None
        uptime = time.time() - info['start_time'] if running else 0
        health_info["radios"][radio_id] = {
            "running": running,
            "uptime_seconds": int(uptime),
            "restart_count": info['restart_count']
        }
    return jsonify(health_info), 200

def flask_thread_func():
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)

def check_stream_availability(url):
    try:
        import subprocess
        result = subprocess.run(
            ["curl", "-s", "-I", url],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            timeout=5
        )
        return b"200 OK" in result.stdout or b"ICY 200 OK" in result.stdout
    except Exception as e:
        print(f"Error checking stream availability: {e}")
        return False

def monitor_processes():
    try:
        with open('radio_config.json', 'r') as f:
            initial_config = json.load(f)
        for radio_id, conf in initial_config.items():
            radio_config[radio_id] = conf
            radio_processes[radio_id] = start_ffmpeg_process(radio_id, conf)
    except FileNotFoundError:
        print("No initial configuration file found. Waiting for updates...")
    
    try:
        while True:
            for radio_id, info in list(radio_processes.items()):
                if radio_id not in radio_config:
                    continue
                proc = info['process']
                if proc.poll() is not None:
                    ts = time.strftime("%Y-%m-%d %H:%M:%S")
                    print(f"[{ts}] Stream for radio {radio_id} terminated unexpectedly. Exit code: {proc.poll()}")
                    info['restart_count'] += 1
                    if info['restart_count'] > 1:
                        backoff = min(30, 2 ** info['restart_count'])
                        print(f"Waiting {backoff} seconds before restarting radio {radio_id}...")
                        time.sleep(backoff)
                    source_url = radio_config[radio_id]['source_url']
                    if not check_stream_availability(source_url):
                        print(f"Source stream {source_url} not available. Retrying in 10 seconds...")
                        time.sleep(10)
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, radio_config[radio_id])
                    radio_processes[radio_id]['restart_count'] = info['restart_count']
                elif time.time() - info['start_time'] > 21600:  # 6 hours
                    print(f"Routine restart for radio {radio_id} (uptime over 6 hours)")
                    restart_ffmpeg_process(radio_id, radio_config[radio_id])
            try:
                with open('radio_config.json', 'w') as f:
                    json.dump(radio_config, f)
            except Exception as e:
                print(f"Error saving radio configuration: {e}")
            time.sleep(5)
    except KeyboardInterrupt:
        print("Shutting down streams...")
        for info in radio_processes.values():
            if info['process'].poll() is None:
                info['process'].terminate()
        try:
            with open('radio_config.json', 'w') as f:
                json.dump(radio_config, f)
        except Exception as e:
            print(f"Error saving radio configuration: {e}")

if __name__ == "__main__":
    flask_thread = threading.Thread(target=flask_thread_func, daemon=True)
    flask_thread.start()
    monitor_processes()
