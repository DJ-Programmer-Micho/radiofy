from flask import Flask, request, jsonify
import threading
import subprocess
import time
import json
import os
import logging
import sys

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/var/log/radio_transcoder.log'),
        logging.StreamHandler(sys.stdout)
    ]
)
logger = logging.getLogger(__name__)

app = Flask(__name__)

# Use an absolute path for radio_config.json based on this script's directory.
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
CONFIG_FILE = os.path.join(BASE_DIR, "radio_config.json")

# In-memory dictionaries for radio configurations and FFmpeg processes.
radio_config = {}
radio_processes = {}

# Create logs directory if it doesn't exist
LOGS_DIR = os.path.join(BASE_DIR, "logs")
os.makedirs(LOGS_DIR, exist_ok=True)

# Global lock for thread safety when modifying shared data
lock = threading.Lock()

# Maximum restart attempts before giving up
MAX_RESTART_ATTEMPTS = 3

def check_stream_availability(url, timeout=10):
    """
    Perform a comprehensive stream availability check using ffprobe.
    
    Args:
        url (str): Stream URL to check
        timeout (int): Timeout in seconds
    
    Returns:
        bool: True if stream is available, False otherwise
    """
    try:
        # Use ffprobe to check stream metadata
        result = subprocess.run(
            ["ffprobe", 
             "-v", "error", 
             "-show_entries", "format=duration", 
             "-of", "default=noprint_wrappers=1:nokey=1", 
             url],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            timeout=timeout,
            universal_newlines=True
        )
        
        # Check if ffprobe successfully extracted stream duration
        duration = result.stdout.strip()
        is_available = duration and float(duration) > 0
        
        logger.info(f"Stream availability check for {url}: {'Available' if is_available else 'Unavailable'}")
        return is_available
    
    except subprocess.TimeoutExpired:
        logger.warning(f"Stream check timed out for {url}")
        return False
    except Exception as e:
        logger.error(f"Detailed stream availability check failed for {url}: {e}")
        return False

def start_ffmpeg_process(radio_id, config):
    """
    Start an FFmpeg process for a given radio configuration.
    
    Args:
        radio_id (str): Unique identifier for the radio
        config (dict): Radio configuration details
    
    Returns:
        dict: Process information and metadata
    """
    log_path = os.path.join(LOGS_DIR, f"radio_{radio_id}.log")
    try:
        log_file = open(log_path, "a")
    except Exception as e:
        logger.error(f"Error opening log file {log_path}: {e}")
        raise

    timestamp = time.strftime("%Y-%m-%d %H:%M:%S")
    log_file.write(f"\n\n[{timestamp}] Starting new FFmpeg process for radio {radio_id}\n")
    
    # Validate required configuration keys
    required_keys = ['source_url', 'mount', 'bitrate', 'source', 'password', 'host', 'port']
    if not all(key in config for key in required_keys):
        logger.error(f"Missing configuration keys for radio {radio_id}")
        raise ValueError(f"Incomplete configuration for radio {radio_id}")

    # Build FFmpeg command with enhanced resilience
    ffmpeg_command = [
        "ffmpeg",
        "-reconnect", "1",
        "-reconnect_streamed", "1",
        "-reconnect_delay_max", "10",
        "-timeout", "60000000",  # 60 seconds
        "-rw_timeout", "60000000",  # Read/write timeout
        "-i", config['source_url'],
        "-c:a", "libmp3lame",
        "-b:a", f"{config['bitrate']}k",
        "-ar", "44100",
        "-f", "mp3",
        "-bufsize", "2M",
        f"icecast://{config['source']}:{config['password']}@{config['host']}:{config['port']}{config['mount']}"
    ]
    
    command_str = ' '.join(ffmpeg_command)
    logger.info(f"Starting stream for radio {radio_id} with command: {command_str}")
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
        """
        Monitor FFmpeg process output and log errors.
        """
        for line in process.stderr:
            ts = time.strftime("%Y-%m-%d %H:%M:%S")
            log_entry = f"[{ts}] {line.strip()}"
            logger.info(f"FFmpeg (radio {radio_id}): {line.strip()}")
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
    """
    Restart the FFmpeg process for a given radio.
    
    Args:
        radio_id (str): Unique identifier for the radio
        config (dict): Radio configuration details
    """
    logger.info(f"Attempting to restart stream for radio {radio_id}")
    
    try:
        # Terminate existing process
        with lock:
            if radio_id in radio_processes:
                proc = radio_processes[radio_id]['process']
                if proc.poll() is None:
                    proc.terminate()
                    for _ in range(10):
                        if proc.poll() is not None:
                            break
                        time.sleep(0.1)
                    if proc.poll() is None:
                        proc.kill()
                
                # Close log file
                if 'log_file' in radio_processes[radio_id]:
                    radio_processes[radio_id]['log_file'].close()
    except Exception as e:
        logger.error(f"Error shutting down FFmpeg for radio {radio_id}: {e}")
    
    # Validate source stream before restarting
    source_url = config.get('source_url')
    if not source_url or not check_stream_availability(source_url):
        logger.warning(f"Source stream {source_url} not available for radio {radio_id}")
        return None
    
    try:
        # Start new process
        new_proc_info = start_ffmpeg_process(radio_id, config)
        
        # Update process tracking
        with lock:
            radio_processes[radio_id] = new_proc_info
        
        return new_proc_info
    except Exception as e:
        logger.error(f"Failed to restart process for radio {radio_id}: {e}")
        return None

def update_radio(radio_id, new_config):
    """
    Update radio configuration and handle restart if needed.
    
    Args:
        radio_id (str): Unique identifier for the radio
        new_config (dict): New configuration details
    """
    need_restart = False
    
    # Validate configuration
    required_keys = ['source_url', 'mount', 'bitrate', 'source', 'password', 'host', 'port']
    if not all(key in new_config for key in required_keys):
        logger.error(f"Invalid configuration for radio {radio_id}. Missing required keys.")
        return
    
    with lock:
        if radio_id in radio_config:
            # Detect configuration changes
            config_changes = {
                key: (radio_config[radio_id].get(key), new_config.get(key))
                for key in required_keys
                if radio_config[radio_id].get(key) != new_config.get(key)
            }
            
            if config_changes:
                logger.info(f"Configuration changes for radio {radio_id}:")
                for key, (old_val, new_val) in config_changes.items():
                    logger.info(f"  {key}: {old_val} -> {new_val}")
                
                radio_config[radio_id] = new_config
                need_restart = True
            else:
                logger.info(f"No substantive changes detected for radio {radio_id}.")
        else:
            radio_config[radio_id] = new_config
            radio_processes[radio_id] = start_ffmpeg_process(radio_id, new_config)
    
    if need_restart:
        restart_ffmpeg_process(radio_id, new_config)

@app.route("/update_radio_config", methods=["POST"])
def update_radio_config():
    """
    HTTP endpoint to update radio configuration.
    """
    try:
        data = request.json
        logger.info(f"Received configuration update: {data}")
        
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
        logger.error(f"Exception in update_radio_config: {e}")
        return jsonify({"error": str(e)}), 500

@app.route("/health", methods=["GET"])
def health():
    """
    Health check endpoint to report radio process status.
    """
    health_info = {"status": "ok", "radios": {}}
    
    with lock:
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
    """
    Run Flask server in a separate thread.
    """
    app.run(host='0.0.0.0', port=5000, debug=False, use_reloader=False)

def monitor_processes():
    """
    Continuously monitor and manage radio processes.
    """
    # Load initial configuration from CONFIG_FILE
    if os.path.exists(CONFIG_FILE) and os.path.getsize(CONFIG_FILE) > 0:
        try:
            with open(CONFIG_FILE, 'r') as f:
                initial_config = json.load(f)
            
            with lock:
                for radio_id, conf in initial_config.items():
                    radio_config[radio_id] = conf
                    radio_processes[radio_id] = start_ffmpeg_process(radio_id, conf)
        except Exception as e:
            logger.error(f"Error loading configuration from {CONFIG_FILE}: {e}")
    else:
        logger.info("No initial configuration file found or file is empty. Waiting for updates...")
    
    try:
        while True:
            # Remove stale radio processes
            with lock:
                stale_ids = [radio_id for radio_id in radio_processes.keys() if radio_id not in radio_config]
            
            for radio_id in stale_ids:
                logger.info(f"Radio {radio_id} removed from configuration. Terminating its process.")
                try:
                    with lock:
                        proc = radio_processes[radio_id]['process']
                    if proc.poll() is None:
                        proc.terminate()
                except Exception as e:
                    logger.error(f"Error terminating radio {radio_id}: {e}")
                
                with lock:
                    if radio_id in radio_processes:
                        del radio_processes[radio_id]
            
            # Monitor existing processes
            with lock:
                current_radio_ids = list(radio_processes.keys())
            
            for radio_id in current_radio_ids:
                with lock:
                    info = radio_processes[radio_id]
                    proc = info['process']
                
                if proc.poll() is not None:
                    logger.warning(f"Stream for radio {radio_id} terminated unexpectedly. Exit code: {proc.poll()}")
                    
                    with lock:
                        info['restart_count'] += 1
                        current_restart_count = info['restart_count']
                    
                    if current_restart_count <= MAX_RESTART_ATTEMPTS:
                        backoff = min(30, 2 ** current_restart_count)
                        logger.info(f"Waiting {backoff} seconds before restarting radio {radio_id}...")
                        time.sleep(backoff)
                        
                        source_url = None
                        with lock:
                            source_url = radio_config[radio_id].get('source_url')
                        
                        if source_url and check_stream_availability(source_url):
                            new_proc_info = restart_ffmpeg_process(radio_id, radio_config[radio_id])
                            if new_proc_info:
                                with lock:
                                    radio_processes[radio_id] = new_proc_info
                        else:
                            logger.warning(f"Source stream {source_url} not available for radio {radio_id}")
                    else:
                        logger.error(f"Max restart attempts reached for radio {radio_id}. Stopping attempts.")
                
                elif time.time() - info['start_time'] > 21600:  # 6 hours uptime
                    logger.info(f"Routine restart for radio {radio_id} (uptime over 6 hours)")
                    restart_ffmpeg_process(radio_id, radio_config[radio_id])
            
            # Save current configuration to disk
            try:
                with open(CONFIG_FILE, 'w') as f:
                    with lock:
                        json.dump(radio_config, f, indent=2)
            except Exception as e:
                logger.error(f"Error saving radio configuration: {e}")
            
            time.sleep(5)
    
    except KeyboardInterrupt:
        logger.info("Shutting down streams...")
        with lock:
            for info in radio_processes.values():
                if info['process'].poll() is None:
                    info['process'].terminate()
        
        try:
            with open(CONFIG_FILE, 'w') as f:
                with lock:
                    json.dump(radio_config, f, indent=2)
        except Exception as e:
            logger.error(f"Error saving radio configuration: {e}")

if __name__ == "__main__":
    flask_thread = threading.Thread(target=flask_thread_func, daemon=True)
    flask_thread.start()
    monitor_processes()