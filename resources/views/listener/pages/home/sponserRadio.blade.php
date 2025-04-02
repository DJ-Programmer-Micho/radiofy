<div style="background-color: #313437" class="rounded p-3">
    <div class="p-3">
        <div class="tab-content tab-content-carousel">
            <h1 class="text-primary">Sponsored Campaigns <i class="fa-solid fa-rectangle-ad"></i></h1>
            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": true,
                    "lazyLoad": true,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": { "items": 2 },
                        "600": { "items": 3 },
                        "792": { "items": 4 },
                        "1040": { "items": 5 },
                        "1200": { "items": 6 },
                        "1500": { "items": 7 },
                        "1800": { "items": 9 }
                    }
                }'>
                @foreach($sponserRadios as $campaign)
                    @php
                        $radio = $campaign->radioable;
                    @endphp
                    @if(!$radio)
                        <p>Campaign ID {{ $campaign->id }}: No radio found.</p>
                    @else
                        <div class="card-poster radio-card-1">
                            <button type="button" class="btn btn-none p-0"
                            onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $radio->id }}, radioType: '{{ get_class($radio) }}',sponser:  1,campaignId: {{ $radio->id }} } }));">
                            <div class="player-icon" style="overflow: hidden;">
                                    <img src="{{ ($radio->profile && $radio->profile->logo) ? asset('storage/' . $radio->profile->logo) : asset('assets/logo/mradiofy-logo.png') }}" 
                                    class="img-fluid" width="50" height="50" alt="Logo">
                                </div>
                            </button>
                            <div class="d-flex justify-content-start">
                                <div>
                                    <a href="{{ route('listener.radio',['slug' => $radio->radio_name_slug]) }}" rel="noopener noreferrer">
                                        <p class="m-0"><b>{{ $radio->radio_name }}</b></p>
                                        <p class="m-0"><strong>"{{ $campaign->promotion_text ?? 'N/A' }}"</strong></p>
                                    </a>
                                </div>
                                <div>
                                    @if($radio->verified)
                                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="Verified" width="15" height="15" class="mx-1" style="max-width:15px; max-height:15px">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div><!-- End .owl-carousel -->
        </div><!-- End .tab-content -->
    </div><!-- End .container -->
</div>
