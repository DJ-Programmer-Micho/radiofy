from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json

app = Flask(__name__)

# In‑memory dictionaries for radio configurations and FFmpeg processes.
# Format: { radio_id: { 'source_url': ..., 'mount': ..., 'bitrate': ..., 'password': ..., 'host': ..., 'port': ... } }
radio_config = {}
# Format: { radio_id: FFmpeg process }
radio_processes = {}

def start_ffmpeg_process(radio_id, config):
    ffmpeg_command = [
        "ffmpeg",
        "-reconnect", "1",
        "-reconnect_streamed", "1",
        "-reconnect_delay_max", "5",  # Increased from 2 to 5
        "-reconnect_attempts", "10",  # Add this parameter
        "-timeout", "60000000",       # Add a long timeout
        "-bufsize", "256k",           # Increase from 64k to 256k
        "-i", config['source_url'],
        "-c:a", "libmp3lame",
        "-b:a", f"{config['bitrate']}k",
        "-ar", "44100",
        "-f", "mp3",
        f"icecast://source:{config['password']}@{config['host']}:{config['port']}{config['mount']}"
    ]
    command_str = ' '.join(ffmpeg_command)
    print(f"Starting stream for radio {radio_id} with command: {command_str}")
    log_file = open(f"radio_{radio_id}_log.txt", "a")
    process = subprocess.Popen(ffmpeg_command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    
    # Read a few lines from stderr for debugging
    def log_stderr():
        while True:
            line = process.stderr.readline()
            if not line and process.poll() is not None:
                break
            if line:
                log_entry = f"[{time.strftime('%Y-%m-%d %H:%M:%S')}] {line.decode('utf-8').strip()}"
                print(log_entry)
                log_file.write(log_entry + "\n")
                log_file.flush()
    
    stderr_thread = threading.Thread(target=log_stderr, daemon=True)
    stderr_thread.start()
    
    return process


def restart_ffmpeg_process(radio_id, config):
    # If there is an existing process, terminate it gracefully.
    if radio_processes.get(radio_id):
        print(f"Restarting stream for radio {radio_id}")
        radio_processes[radio_id].terminate()
        radio_processes[radio_id].wait()
    # Start a new process with the updated configuration.
    radio_processes[radio_id] = start_ffmpeg_process(radio_id, config)

def update_radio(radio_id, new_config):
    global radio_config, radio_processes
    # Check if this radio exists and whether the configuration has changed.
    if radio_id in radio_config:
        if radio_config[radio_id] != new_config:
            radio_config[radio_id] = new_config
            restart_ffmpeg_process(radio_id, new_config)
        else:
            print(f"No changes detected for radio {radio_id}.")
    else:
        # Add new radio config and start its FFmpeg process.
        radio_config[radio_id] = new_config
        radio_processes[radio_id] = start_ffmpeg_process(radio_id, new_config)

@app.route("/update_radio_config", methods=["POST"])
def update_radio_config():
    data = request.json
    radio_id = data.get("radio_id")
    if not radio_id:
        return jsonify({"error": "Missing radio_id"}), 400

    new_config = data.get("config")
    if not new_config:
        return jsonify({"error": "Missing config data"}), 400

    update_radio(radio_id, new_config)
    return jsonify({"status": "success"}), 200

@app.route("/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"}), 200

def flask_thread_func():
    # Run the Flask app on all network interfaces (0.0.0.0) on port 5000.
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)

def monitor_processes():
    # Load initial configurations from a JSON file (if available).
    try:
        with open('radio_config.json', 'r') as f:
            initial_config = json.load(f)
        for radio_id, conf in initial_config.items():
            radio_config[radio_id] = conf
            radio_processes[radio_id] = start_ffmpeg_process(radio_id, conf)
    except FileNotFoundError:
        print("No initial configuration file found. Waiting for updates...")

    # Main loop for monitoring FFmpeg processes.
    try:
        consecutive_failures = {}  # Track failures per radio
        while True:
            for radio_id, proc in list(radio_processes.items()):
                if proc.poll() is not None:
                    if radio_id not in consecutive_failures:
                        consecutive_failures[radio_id] = 0
                    consecutive_failures[radio_id] += 1
                    
                    print(f"Stream for radio {radio_id} terminated unexpectedly. Attempt {consecutive_failures[radio_id]}. Restarting...")
                    
                    # Add exponential backoff for repeated failures
                    if consecutive_failures[radio_id] > 1:
                        backoff_time = min(30, 2 ** consecutive_failures[radio_id])
                        print(f"Waiting {backoff_time} seconds before restarting radio {radio_id}...")
                        time.sleep(backoff_time)
                    
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, radio_config[radio_id])
                else:
                    # Reset consecutive failures counter if process is running
                    consecutive_failures[radio_id] = 0
            time.sleep(3)
    except KeyboardInterrupt:
        print("Shutting down streams...")
        for proc in radio_processes.values():
            proc.terminate()

if __name__ == "__main__":
    # Start the Flask server in its own thread.
    flask_thread = threading.Thread(target=flask_thread_func, daemon=True)
    flask_thread.start()

    # Start the FFmpeg process monitor in the main thread.
    monitor_processes()
