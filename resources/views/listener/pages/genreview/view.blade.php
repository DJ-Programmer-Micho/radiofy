<div class="page-content">
    <style>
        .profile-wid-bg::before{
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            opacity: .9;
            background: transparent;
            background: -webkit-gradient(linear, left bottom, left top, from(#1c4eb200), to(#6691e700));
            background: linear-gradient(to top, #1c4eb200, #6691e700);
        }
    </style>
    @livewire('listener.home.main-ads-livewire')
    <div class="container-fluid">
        <div class="d-flex justify-content-start align-items-center p-0">
            <div>
                <img src="{{ asset('storage/'.$selectedGenre->image_sq) }}" class="rounded" width="75px" alt="">
            </div>
            <div>
                <h1 class="px-3">{{ $selectedGenre->genreTranslation->name }}</h1>
            </div>
        </div>
        <hr>

        <div class="mb-3">
            <div class="tab-content tab-content-carousel">
                <h1>Radio's</h1>
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
                        @foreach($filterRadios as $radio)
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
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('listener.radio',['slug' => $radio->radio_name_slug])}}" rel="noopener noreferrer">
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

        @livewire('listener.home.some-genre-livewire')

        @if(count($otherRadios) > 0)
        <div class="row">
            <div>
                <h1 class="">More Radio On This Genre</h1>
            </div>
            @foreach ($otherRadios as $oRadio)
              <div class="radio-card-1 col-xxl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <button type="button" class="btn btn-none p-0" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $oRadio->id }}, radioType: '{{ $oRadio->type }}' } }));">
                    <div class="player-icon" style="overflow: hidden;">
                        <img src="{{ $oRadio->profile->logo 
                                    ? asset('storage/' . $oRadio->profile->logo) 
                                    : asset('assets/logo/mradiofy-logo.png') }}" 
                            class="img-fluid"  
                            alt="Logo">
                    </div>
                </button>

                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('listener.radio',['slug' => $oRadio->radio_name_slug])}}" rel="noopener noreferrer">
                            <p class="m-0"><b>{{ $oRadio->radio_name }}</b></p>
                        </a>
                    </div>
                    <div>
                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15" 
                        style="max-width:15px; max-height:15px">
                    </div>
                </div>
                <p class="m-0">{{ number_format($oRadio->listeners_count) }} Listeners</p>
            </div>
              
            @endforeach
        </div>
        @endif
        @if (auth()->guard('listener')->check())
            @livewire('listener.home.watch-radio-livewire')
        @endif
        @livewire('listener.home.top-radio-livewire')


    </div><!-- container-fluid -->
</div><!-- End Page-content -->