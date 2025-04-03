@if (count($watchRadios) > 0)
<div class="p-3">
    <div class="tab-content tab-content-carousel">
        <h1>Continue Listening</h1>
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
                @foreach($watchRadios as $radio)
                    <div class="card-poster radio-card-1">
                        {{-- <button type="button" class="btn btn-none p-0" wire:click="$emit('playNowEvent', {{ $radio->id }})"> --}}
                        <button type="button" class="btn btn-none p-0" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $radio->id }}, radioType: '{{ $radio->type }}' } }));">
                            <div class="player-icon" style="overflow: hidden;">
                            <img src="{{ $radio->profile->logo 
                                        ? asset('storage/' . $radio->profile->logo) 
                                        : asset('assets/logo/mradiofy-logo.png') }}" 
                                class="img-fluid" 
                                width="50" 
                                height="50" 
                                alt="Logo">
                            </div>
                        </button>
                        <div class="d-flex justify-content-start">
                            <div>
                                <a href="{{ route('listener.radio',['slug' => $radio->radio_name_slug])}}" rel="noopener noreferrer">
                                    <p class="m-0"><b>{{ $radio->radio_name }}</b></p>
                                </a>
                            </div>
                            <div>
                                @if ($radio->verified )
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15" class="mx-1"
                                style="max-width:15px; max-height:15px">
                                @endif
                            </div>
                        </div>
                        <p class="m-0">{{ number_format($radio->listeners_count) }} Listeners</p>
                    </div>
                @endforeach              
            </div><!-- End .owl-carousel -->
    </div><!-- End .tab-content -->

</div><!-- End .container -->
@else
<div></div>
@endif