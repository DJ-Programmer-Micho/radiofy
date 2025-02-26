# resources/liquidsoap/template.liq
set("log.file", false)

# Define the source stream from the encoder. 
original = mksafe(input.http("http://{encoder_ip}:{encoder_port}/{mount}"))

# Output to Icecast using the built-in MP3 output settings.
output.icecast(
  %mp3(bitrate={bitrate}, samplerate=44100, stereo=true),
  mount="{mount}",
  host="localhost",
  port=8000,
  password="hackme",
  original
)
