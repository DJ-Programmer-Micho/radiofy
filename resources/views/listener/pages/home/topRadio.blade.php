{{-- NOT USED COMPONENT --}}
<div class="px-3">
    <div class="tab-content tab-content-carousel">
        <h1>Top Radios</h1>
            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": true,
                    "lazyLoad": true,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": {
                            "items":2
                        },
                        "600": {
                            "items":3
                        },
                        "792": {
                            "items":4
                        },
                        "1040": {
                            "items":5
                        },
                        "1200": {
                            "items":6
                        },
                        "1500": {
                            "items":7
                        },
                        "1800": {
                            "items":9
                        }
                    }
                }'>
                @foreach($topRadios as $radio)
                    <div class="card-poster radio-card-1">
                        {{-- <button type="button" class="btn btn-none p-0" wire:click="$emit('playNowEvent', {{ $radio->id }})"> --}}
                            <button type="button" class="btn btn-none p-0" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $radio->id }} } }));">
                            <div class="player-icon" style="overflow: hidden;">
                                <img src="{{ $radio->radio_configuration_profile->logo 
                                            ? asset('storage/' . $radio->radio_configuration_profile->logo) 
                                            : asset('assets/logo/mradiofy-logo.png') }}" 
                                    class="img-fluid" 
                                    width="50" 
                                    height="50" 
                                    alt="Logo">
                            </div>
                        </button>
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('listener.radio',['radio' => $radio->id])}}" rel="noopener noreferrer">
                                    <p class="m-0"><b>{{ $radio->radio_name }}</b></p>
                                </a>
                            </div>
                            <div>
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15" 
                                style="max-width:15px; max-height:15px">
                            </div>
                        </div>
                        <p class="m-0">{{ number_format($radio->listeners_count) }} Listeners</p>
                    </div>
                @endforeach              
            </div><!-- End .owl-carousel -->
    </div><!-- End .tab-content -->

</div><!-- End .container -->

@push('scripts')
<script>
    if (!window.turboAudioSwitchInitialized) {
    window.turboAudioSwitchInitialized = true;

    window.addEventListener('switch-radio', event => {
        const radioId = event.detail.radioId;
        console.log('Switch radio event received with ID:', radioId);
        // Now re-emit the Livewire event to trigger the switch in the persistent component
        Livewire.emit('playNowEvent', radioId);
    });
}

</script>
@endpush