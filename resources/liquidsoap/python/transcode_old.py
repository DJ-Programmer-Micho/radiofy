from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json
import os

app = Flask(__name__)

# Use an absolute path for radio_config.json based on this script's directory.
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
CONFIG_FILE = os.path.join(BASE_DIR, "radio_config.json")

# In‑memory dictionaries for radio configurations and FFmpeg processes.
# Keys are strings.
radio_config = {}
# Format: { radio_id: { 'process': FFmpeg process, 'start_time': timestamp, 'restart_count': int, 'log_file': file, 'monitor_thread': Thread } }
radio_processes = {}

# Create logs directory if it doesn't exist
LOGS_DIR = os.path.join(BASE_DIR, "logs")
os.makedirs(LOGS_DIR, exist_ok=True)


def start_ffmpeg_process(radio_id, config):
    log_path = os.path.join(LOGS_DIR, f"radio_{radio_id}.log")
    try:
        log_file = open(log_path, "a")
    except Exception as e:
        print(f"Error opening log file {log_path}: {e}")
        raise

    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"\n\n[{timestamp}] Starting new FFmpeg process for radio {radio_id}\n")
    
    # Build FFmpeg command with fallback using anullsrc.
    ffmpeg_command = [
        "ffmpeg",
        "-reconnect", "1",
        "-reconnect_streamed", "1",
        "-reconnect_delay_max", "5",
        "-timeout", "60000000",  # 60 seconds
        "-i", config['source_url'],
        "-f", "lavfi", "-i", "anullsrc=r=44100:cl=mono",
        "-filter_complex", "[0:a][1:a]amix=inputs=2:duration=longest:dropout_transition=3",
        "-c:a", "libmp3lame",
        "-b:a", f"{config['bitrate']}k",
        "-ar", "44100",
        "-f", "mp3",
        f"icecast://{config['source']}:{config['password']}@{config['host']}:{config['port']}{config['mount']}"
    ]
    
    command_str = ' '.join(ffmpeg_command)
    print(f"Starting stream for radio {radio_id} with command: {command_str}")
    log_file.write(f"Command: {command_str}\n")
    log_file.flush()
    
    process = subprocess.Popen(
        ffmpeg_command,
        stdout=subprocess.DEVNULL,
        stderr=subprocess.PIPE,
        bufsize=1,
        universal_newlines=True
    )
    
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
    old_restart_count = 0
    if radio_id in radio_processes:
        old_restart_count = radio_processes[radio_id].get('restart_count', 0)
        print(f"Restarting stream for radio {radio_id}")
        try:
            proc = radio_processes[radio_id]['process']
            if proc.poll() is None:
                proc.terminate()
                for _ in range(10):
                    if proc.poll() is not None:
                        break
                    time.sleep(0.1)
                if proc.poll() is None:
                    proc.kill()
            if 'log_file' in radio_processes[radio_id]:
                radio_processes[radio_id]['log_file'].close()
        except Exception as e:
            print(f"Error shutting down FFmpeg for radio {radio_id}: {e}")
    
    new_proc_info = start_ffmpeg_process(radio_id, config)
    new_proc_info['restart_count'] = old_restart_count + 1
    radio_processes[radio_id] = new_proc_info


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
        print("Received data:", data)
        if not data:
            raise ValueError("No JSON payload provided.")
        radio_id = str(data.get("radio_id"))
        if not radio_id:
            raise ValueError("Missing radio_id")
        new_config = data.get("config")
        if not new_config:
            raise ValueError("Missing config data")
        
        update_radio(radio_id, new_config)
        return jsonify({"status": "success"}), 200
    except Exception as e:
        print(f"Exception in update_radio_config: {e}")
        return jsonify({"error": str(e)}), 500


@app.route("/health", methods=["GET"])
def health():
    health_info = {"status": "ok", "radios": {}}
    for radio_id, info in radio_processes.items():
        try:
            process = info.get("process")
            if process is None:
                raise ValueError("Missing process info")
            running = process.poll() is None
            start_time = info.get("start_time")
            if start_time is None:
                raise ValueError("Missing start_time")
            uptime = time.time() - start_time if running else 0
            restart_count = info.get("restart_count", 0)
            health_info["radios"][radio_id] = {
                "running": running,
                "uptime_seconds": int(uptime),
                "restart_count": restart_count
            }
        except Exception as e:
            health_info["radios"][radio_id] = {"error": str(e)}
    return jsonify(health_info), 200


def flask_thread_func():
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)


def check_stream_availability(url):
    try:
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
    # Attempt to load configuration from CONFIG_FILE if it exists and is non-empty.
    if os.path.exists(CONFIG_FILE) and os.path.getsize(CONFIG_FILE) > 0:
        try:
            with open(CONFIG_FILE, 'r') as f:
                initial_config = json.load(f)
            for radio_id, conf in initial_config.items():
                radio_config[radio_id] = conf
                radio_processes[radio_id] = start_ffmpeg_process(radio_id, conf)
        except Exception as e:
            print(f"Error loading configuration from {CONFIG_FILE}: {e}")
    else:
        print("No initial configuration file found or file is empty. Waiting for updates...")
    
    try:
        while True:
            # Remove any stale radio processes that are not in the current configuration.
            for radio_id in list(radio_processes.keys()):
                if radio_id not in radio_config:
                    print(f"Radio {radio_id} removed from configuration. Terminating its process.")
                    try:
                        proc = radio_processes[radio_id]['process']
                        if proc.poll() is None:
                            proc.terminate()
                    except Exception as e:
                        print(f"Error terminating radio {radio_id}: {e}")
                    del radio_processes[radio_id]
            
            # Monitor existing processes.
            for radio_id, info in list(radio_processes.items()):
                proc = info['process']
                if proc.poll() is not None:
                    ts = time.strftime("%Y-%m-%d %H:%M:%S")
                    print(f"[{ts}] Stream for radio {radio_id} terminated unexpectedly. Exit code: {proc.poll()}")
                    info['restart_count'] += 1
                    if info['restart_count'] > 1:
                        backoff = min(30, 2 ** info['restart_count'])
                        print(f"Waiting {backoff} seconds before restarting radio {radio_id}...")
                        time.sleep(backoff)
                    source_url = radio_config[radio_id].get('source_url')
                    if not source_url or not check_stream_availability(source_url):
                        print(f"Source stream {source_url} not available. Retrying in 10 seconds...")
                        time.sleep(10)
                    current_restart_count = info['restart_count']
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, radio_config[radio_id])
                    radio_processes[radio_id]['restart_count'] = current_restart_count
                elif time.time() - info['start_time'] > 21600:  # 6 hours
                    print(f"Routine restart for radio {radio_id} (uptime over 6 hours)")
                    restart_ffmpeg_process(radio_id, radio_config[radio_id])
            
            # Save the current configuration to disk.
            try:
                with open(CONFIG_FILE, 'w') as f:
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
            with open(CONFIG_FILE, 'w') as f:
                json.dump(radio_config, f)
        except Exception as e:
            print(f"Error saving radio configuration: {e}")


if __name__ == "__main__":
    flask_thread = threading.Thread(target=flask_thread_func, daemon=True)
    flask_thread.start()
    monitor_processes()
