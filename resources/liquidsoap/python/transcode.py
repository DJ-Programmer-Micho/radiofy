from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json
import os
import signal

app = Flask(__name__)

# In‑memory dictionaries for radio configurations and FFmpeg processes.
# Format: { radio_id: { 'source_url': ..., 'mount': ..., 'bitrate': ..., 'password': ..., 'host': ..., 'port': ... } }
radio_config = {}
# Format: { radio_id: {'process': FFmpeg process, 'start_time': timestamp, 'restart_count': int} }
radio_processes = {}

# Create logs directory if it doesn't exist
os.makedirs('logs', exist_ok=True)

def start_ffmpeg_process(radio_id, config):
    log_file = open(f"logs/radio_{radio_id}.log", "a")
    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"\n\n[{timestamp}] Starting new FFmpeg process for radio {radio_id}\n")
    
    # More robust FFmpeg command with better reconnect settings
    ffmpeg_command = [
        "ffmpeg",
        "-reconnect", "1",
        "-reconnect_streamed", "1",
        "-reconnect_delay_max", "5",
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
    
    # Start FFmpeg process with proper error handling
    process = subprocess.Popen(
        ffmpeg_command, 
        stdout=subprocess.PIPE, 
        stderr=subprocess.PIPE,
        bufsize=1,
        universal_newlines=True
    )
    
    # Create a thread to monitor FFmpeg output
    def monitor_output():
        for line in process.stderr:
            timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
            log_entry = f"[{timestamp}] {line.strip()}"
            print(f"FFmpeg (radio {radio_id}): {line.strip()}")
            log_file.write(log_entry + "\n")
            log_file.flush()
            
            # Look for critical errors that might indicate why the stream stops
            if "Error" in line or "error" in line or "Failed" in line or "failed" in line:
                print(f"FFmpeg ERROR detected for radio {radio_id}: {line.strip()}")
    
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
    # If there is an existing process, terminate it gracefully.
    if radio_id in radio_processes:
        print(f"Restarting stream for radio {radio_id}")
        try:
            # Try to terminate gracefully
            if radio_processes[radio_id]['process'].poll() is None:
                radio_processes[radio_id]['process'].terminate()
                # Give it some time to terminate gracefully
                for _ in range(10):
                    if radio_processes[radio_id]['process'].poll() is not None:
                        break
                    time.sleep(0.1)
                # If still running, force kill
                if radio_processes[radio_id]['process'].poll() is None:
                    radio_processes[radio_id]['process'].kill()
            
            # Close the log file
            if 'log_file' in radio_processes[radio_id]:
                radio_processes[radio_id]['log_file'].close()
        except Exception as e:
            print(f"Error shutting down FFmpeg for radio {radio_id}: {e}")
    
    # Start a new process with the updated configuration.
    radio_processes[radio_id] = start_ffmpeg_process(radio_id, config)
    # Reset restart count if this was a manual restart
    radio_processes[radio_id]['restart_count'] = 0

def update_radio(radio_id, new_config):
    global radio_config, radio_processes
    # Check if this radio exists and whether the configuration has changed.
    if radio_id in radio_config:
        if radio_config[radio_id] != new_config:
            radio_config[radio_id] = new_config
            restart_ffmpeg_process(radio_id, new_config)
            return True
        else:
            print(f"No changes detected for radio {radio_id}.")
            return False
    else:
        # Add new radio config and start its FFmpeg process.
        radio_config[radio_id] = new_config
        radio_processes[radio_id] = start_ffmpeg_process(radio_id, new_config)
        return True

@app.route("/update_radio_config", methods=["POST"])
def update_radio_config():
    data = request.json
    radio_id = data.get("radio_id")
    if not radio_id:
        return jsonify({"error": "Missing radio_id"}), 400

    new_config = data.get("config")
    if not new_config:
        return jsonify({"error": "Missing config data"}), 400

    success = update_radio(radio_id, new_config)
    return jsonify({"status": "success", "restarted": success}), 200

@app.route("/health", methods=["GET"])
def health():
    # Return health information including uptime for each radio
    health_info = {
        "status": "ok",
        "radios": {}
    }
    
    for radio_id, process_info in radio_processes.items():
        running = process_info['process'].poll() is None
        uptime = time.time() - process_info['start_time'] if running else 0
        health_info["radios"][radio_id] = {
            "running": running,
            "uptime_seconds": int(uptime),
            "uptime_formatted": f"{int(uptime // 3600)}h {int((uptime % 3600) // 60)}m {int(uptime % 60)}s",
            "restart_count": process_info['restart_count']
        }
    
    return jsonify(health_info), 200

@app.route("/debug", methods=["GET"])
def debug_info():
    # Endpoint to get detailed debug info about all streams
    debug_data = {
        "radio_count": len(radio_config),
        "radios": {}
    }
    
    for radio_id, config in radio_config.items():
        # Don't include password in debug output
        safe_config = config.copy()
        safe_config['password'] = '*****'
        
        process_info = radio_processes.get(radio_id, {})
        process_status = "running" if process_info.get('process') and process_info['process'].poll() is None else "stopped"
        
        debug_data["radios"][radio_id] = {
            "config": safe_config,
            "status": process_status,
            "pid": process_info.get('process').pid if process_info.get('process') else None,
            "start_time": process_info.get('start_time'),
            "restart_count": process_info.get('restart_count', 0)
        }
    
    return jsonify(debug_data), 200

def flask_thread_func():
    # Run the Flask app on all network interfaces (0.0.0.0) on port 5000.
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)

def check_stream_availability(url):
    # Simple function to check if a stream is available before trying to connect
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
        while True:
            for radio_id, process_info in list(radio_processes.items()):
                # Skip if radio is not in config
                if radio_id not in radio_config:
                    continue
                    
                process = process_info['process']
                # Check if process has terminated
                if process.poll() is not None:
                    # Process has terminated
                    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
                    print(f"[{timestamp}] Stream for radio {radio_id} terminated unexpectedly. Exit code: {process.poll()}")
                    
                    # Increment restart count
                    process_info['restart_count'] += 1
                    restart_count = process_info['restart_count']
                    
                    # Use exponential backoff for repeated failures
                    if restart_count > 1:
                        backoff_time = min(30, 2 ** restart_count)
                        print(f"Waiting {backoff_time} seconds before restarting radio {radio_id} (attempt {restart_count})...")
                        time.sleep(backoff_time)
                    
                    # Check if source stream is available before restarting
                    source_url = radio_config[radio_id]['source_url']
                    if not check_stream_availability(source_url):
                        print(f"Source stream {source_url} not available. Retrying in 10 seconds...")
                        time.sleep(10)
                    
                    # Start a new process
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, radio_config[radio_id])
                    # Preserve the restart count
                    radio_processes[radio_id]['restart_count'] = restart_count
                
                # Check long-running processes (over 6 hours) and restart them to prevent potential memory issues
                elif time.time() - process_info['start_time'] > 21600:  # 6 hours
                    print(f"Performing routine restart of long-running stream for radio {radio_id}")
                    restart_ffmpeg_process(radio_id, radio_config[radio_id])
            
            # Save current configuration to disk
            try:
                with open('radio_config.json', 'w') as f:
                    json.dump(radio_config, f)
            except Exception as e:
                print(f"Error saving radio configuration: {e}")
                
            time.sleep(5)  # Check every 5 seconds
            
    except KeyboardInterrupt:
        print("Shutting down streams...")
        for process_info in radio_processes.values():
            if process_info['process'].poll() is None:
                process_info['process'].terminate()
                
        print("Saving configuration...")
        try:
            with open('radio_config.json', 'w') as f:
                json.dump(radio_config, f)
        except Exception as e:
            print(f"Error saving radio configuration: {e}")

if __name__ == "__main__":
    # Start the Flask server in its own thread.
    flask_thread = threading.Thread(target=flask_thread_func, daemon=True)
    flask_thread.start()

    # Start the FFmpeg process monitor in the main thread.
    monitor_processes()