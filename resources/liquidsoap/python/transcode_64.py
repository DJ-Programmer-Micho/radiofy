from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json

app = Flask(__name__)

# In‑memory dictionaries for radio configurations and FFmpeg processes.
radio_config = {}     # Format: { radio_id: { 'source_url': ..., 'mount': ..., 'bitrate': ..., 'password': ..., 'host': ..., 'port': ... } }
radio_processes = {}  # Format: { radio_id: FFmpeg process }

def start_ffmpeg_process(radio_id, config):
    ffmpeg_command = [
        "ffmpeg",
        "-i", config['source_url'],
        "-c:a", "libmp3lame",
        "-b:a", f"{config['bitrate']}k",
        "-ar", "44100",
        "-f", "mp3",
        f"icecast://source:{config['password']}@{config['host']}:{config['port']}{config['mount']}"
    ]
    print(f"Starting stream for radio {radio_id} with command: {' '.join(ffmpeg_command)}")
    process = subprocess.Popen(ffmpeg_command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
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

def run_flask():
    # Run Flask on a specific port; adjust as needed.
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)

# Run the Flask server in a separate thread alongside your transcoding processes.
if __name__ == "__main__":
    flask_thread = threading.Thread(target=run_flask, daemon=True)
    flask_thread.start()

    # Example: Load initial configurations from file or database.
    # For demonstration, we're loading a JSON file.
    try:
        with open('radio_config.json', 'r') as f:
            initial_config = json.load(f)
        for radio_id, conf in initial_config.items():
            radio_config[radio_id] = conf
            radio_processes[radio_id] = start_ffmpeg_process(radio_id, conf)
    except FileNotFoundError:
        print("No initial configuration file found. Waiting for updates...")

    # Main loop for monitoring processes.
    try:
        while True:
            for radio_id, proc in list(radio_processes.items()):
                if proc.poll() is not None:
                    print(f"Stream for radio {radio_id} terminated unexpectedly. Restarting...")
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, radio_config[radio_id])
            time.sleep(5)
    except KeyboardInterrupt:
        print("Shutting down streams...")
        for proc in radio_processes.values():
            proc.terminate()


@app.route("/health", methods=["GET"])
def health():
    return {"status": "ok"}, 200
