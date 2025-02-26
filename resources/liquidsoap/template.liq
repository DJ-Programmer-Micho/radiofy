# resources/liquidsoap/template.liq
set("log.file", false)

# Define the source stream from the encoder. In a real setup, you might have a URL that the encoder is pushing to.
original = input.http("http://{encoder_ip}:{encoder_port}/{mount}")

# Transcode the stream to MP3 at the specified bitrate.
transcoded = encode(
  %mp3,
  "mp3enc -b {bitrate}",
  original
)

# Output to Icecast
output.icecast(
  %mp3,
  host="localhost",
  port=8000,
  password="hackme",
  mount="{mount}",
  transcoded
)
