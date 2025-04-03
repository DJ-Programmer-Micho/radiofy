<footer class="footer p-0">
    <div class="music-player">
        <div class="top-controls">
            <!-- Song Bar (left side) -->
            <div class="song-bar">
                <div class="song-infos">
                    <div class="image-container">
                        <img src="{{ $current_radio->profile->logo 
                                    ? asset('storage/' . $current_radio->profile->logo) 
                                    : asset('assets/logo/mradiofy-logo.png') }}" alt="Logo" />
                    </div>
                    <div class="song-description">
                        <p class="title">
                            <a wire:navigate href="{{ route('listener.radio',['slug' => $current_radio->radio_name_slug])}}" rel="noopener noreferrer">{{ $current_radio->radio_name }}</a>
                        </p>
                        <p class="artist">
                            <a wire:navigate href="{{ route('listener.radio',['slug' => $current_radio->radio_name_slug])}}" rel="noopener noreferrer">{{ $current_radio->subscriber->subscriber_profile->first_name }} {{ $current_radio->subscriber->subscriber_profile->last_name }}</a>
                        </p>
                        <p class="artist">
                            @if ($current_radio->verified )
                            Radio Verify 
                            <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15" class="mx-1 mb-2"
                            style="max-width:15px; max-height:15px">
                            @endif
                        </p>
                    </div>
                </div>
                <div class="icons">
                    @if(auth()->guard('listener')->user())
                        @if($isLiked)
                            <button wire:click="toggleLike" class="btn btn-dark p-1">
                                <i class="fa-solid fa-heart text-danger" style="font-size: 15px;"></i> <span> Liked</span>
                                <h6 class="text-white mb-1">{{ number_format($likesCount) }}</h6>
                            </button>
                        @else
                            <button wire:click="toggleLike" class="btn btn-dark p-1">
                                <i class="fa-regular fa-heart" style="font-size: 15px;"></i> <span> Like</span>
                                <h6 class="text-white mb-1">{{ number_format($likesCount) }}</h6>
                            </button>
                            @endif
                            @else
                            <a href="{{  route("lis.signin") }}" type="button" class="btn btn-dark p-1">
                                <i class="fa-regular fa-heart" style="font-size: 15px;"></i> <span> Like</span>
                                <h6 class="text-white mb-1">{{ number_format($likesCount) }}</h6>
                            </a>
                    @endif
                </div>
            </div>
            <!-- Control Buttons (center) -->
            <div class="control-buttons">
                <button type="button" class="btn btn-none" wire:click="playNowControl">
                    <i class="fa-solid fa-circle-{{ $isPlaying ? 'pause' : 'play' }} play-pause"></i>
                </button>
            </div>
            <!-- Other Features (right side) -->
            <div class="other-features">
                <i class="fa-regular fa-circle-dot fa-fade text-danger"></i>
                LISTEN LIVE
                {{-- <div class="volume-bar">
                    <i class="fas fa-volume-down"></i>
                    <div class="progress-bar" id="volume-bar">
                        <div class="progress" id="volume-progress"></div>
                    </div>
                </div> --}}
            </div>
        </div>
        <!-- Progress bar at the very bottom -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>
    </div>
</footer>

@push('scripts')
<!-- Include Howler.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" integrity="sha512-Pv7v88hS3yE2c+Zr7Ay/2q6RZlNRcxE0QAWC7e9GIVeZpqMk6FtdGqtXzLI7LJ83R9o2n7HLwMck2RrSJvWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    if (!window.turboAudioInitialized) {
    window.turboAudioInitialized = true;

    // Global Howler instance.
    window.currentHowl = null;

    // Listen for the "play-radio" event from Livewire.
    window.addEventListener('play-radio', event => {
        const streamUrl = event.detail.streamUrl;
        console.log('play-radio event received with URL:', streamUrl);
        if (window.currentHowl) {
            window.currentHowl.unload();
        }
        window.currentHowl = new Howl({
            src: [streamUrl],
            html5: true,
            onplay: function() {
                console.log('Howl started playing.');
                animateLiveProgress();
            },
            onloaderror: function(id, error) {
                console.error('Error loading stream:', error);
            }
        });
        window.currentHowl.play();
    });

    // Listen for the "pause-radio" event from Livewire.
    window.addEventListener('pause-radio', event => {
        console.log('pause-radio event received.');
        if (window.currentHowl) {
            // You can choose to pause or unload.
            window.currentHowl.pause();
            // Optionally, if pausing causes issues, try unloading:
            // window.currentHowl.unload();
            console.log("Stream paused");
        } else {
            console.log("No currentHowl instance to pause.");
        }
    });
}

    // Dummy animation for live progress (loops continuously)
    function animateLiveProgress() {
        if (window.currentHowl && window.currentHowl.playing()) {
            let progressElement = document.querySelector('.progress-container .progress');
            if (progressElement) {
                let width = parseFloat(progressElement.style.width) || 0;
                width += 0.1;
                if (width > 100) width = 0;
                progressElement.style.width = width + '%';
            }
            requestAnimationFrame(animateLiveProgress);
        }
    }

    // Volume control function
    function setVolume(value) {
        if (window.currentHowl) {
            window.currentHowl.volume(value);
            let volProgress = document.getElementById('volume-progress');
            if (volProgress) {
                volProgress.style.width = (value * 100) + '%';
            }
        }
    }

    // Attach delegated event listener to volume bar for click/tap
    $(document).on('click', '#volume-bar', function(e) {
        const rect = this.getBoundingClientRect();
        const clickX = e.clientX - rect.left; // x position within the element
        const volumeValue = clickX / rect.width;
        setVolume(volumeValue);
    });

    // Attach a click event on the speaker icon to toggle mute/unmute
    $(document).on('click', '.volume-bar i.fa-volume-down, .volume-bar i.fa-volume-mute', function(e) {
        e.stopPropagation(); // prevent triggering the volume bar click
        if (window.currentHowl) {
            let isMuted = window.currentHowl.mute(); // returns current mute state
            window.currentHowl.mute(!isMuted);
            // Toggle the icon class:
            if (!isMuted) {
                // It was not muted, so now mute it.
                $(this).removeClass('fa-volume-down').addClass('fa-volume-mute');
            } else {
                // It was muted, so unmute it.
                $(this).removeClass('fa-volume-mute').addClass('fa-volume-down');
            }
        }
    });

    // Set default volume.
    setVolume(0.9);
</script>
@endpush
