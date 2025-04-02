<div class="p-3">
    <h1>Today Radio's</h1>
    <div class="row">
        @foreach($shortCards as $radio)
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
            <div class="card radio-card-1">
                <div class="card-body d-flex justify-content-between align-items-stretch p-0">
                    <div class="d-flex align-items-center">
                        <div style="width: 80px; height: 80px; overflow: hidden; margin-right: 20px;">
                            <button type="button" class="btn btn-none p-0" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $radio->id }}, radioType: '{{ $radio->type }}' } }));">
                                <div class="player-icon" style="overflow: hidden;">
                            <img src="{{ $radio->profile->logo 
                                    ? asset('storage/' . $radio->profile->logo) 
                                    : asset('assets/logo/mradiofy-logo.png') }}" 
                                class="img-fluid rounded" 
                                width="80" 
                                height="80" 
                                alt="Logo">
                            </div>
                            </button>
                        </div>
                        <div style="flex-grow: 1;" class="my-1">
                            <div class="d-flex align-items-center">
                                @if ($radio->verified )
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15">
                                &nbsp;<h6 class="my-auto">Radio Verified</h6>
                                @endif
                            </div>
                            <a wire:navigate href="{{ route('listener.radio',['slug' => $radio->radio_name_slug])}}" rel="noopener noreferrer">
                                <h5 class="m-0"><b>{{ $radio->radio_name }}</b></h5>
                            </a>
                            
                            <p class="m-0">{{ number_format($radio->listeners_count) }} Listeners</p>
                        </div>
                    </div>
                    <div style="align-self: flex-end;" class="my-auto">

                    </div>
                </div>
            </div>
        </div>
        @endforeach 
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-stretch p-0">
                    <div class="d-flex align-items-center">
                        <div style="width: 80px; height: 80px; overflow: hidden; margin-right: 20px;">
                            <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_logo_2023181117003269916864.jpeg" class="img-fluid rounded" alt="Radio_logo_name">
                        </div>
                        <div style="flex-grow: 1;" class="my-1">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15">
                                &nbsp;<h6 class="my-auto">Radio Verified</h6>
                            </div>
                            <h5>M Radio Iraq</h5>
                            <p class="m-0">7,123,321 Listeners</p>
                        </div>
                    </div>
                    <div style="align-self: flex-end;" class="my-auto">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-stretch p-0">
                    <div class="d-flex align-items-center">
                        <div style="width: 80px; height: 80px; overflow: hidden; margin-right: 20px;">
                            <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_logo_2023181117003269916864.jpeg" class="img-fluid rounded" alt="Radio_logo_name">
                        </div>
                        <div style="flex-grow: 1;" class="my-1">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15">
                                &nbsp;<h6 class="my-auto">Radio Verified</h6>
                            </div>
                            <h5>M Radio Iraq</h5>
                            <p class="m-0">7,123,321 Listeners</p>
                        </div>
                    </div>
                    <div style="align-self: flex-end;" class="my-auto">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-stretch p-0">
                    <div class="d-flex align-items-center">
                        <div style="width: 80px; height: 80px; overflow: hidden; margin-right: 20px;">
                            <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_logo_2023181117003269916864.jpeg" class="img-fluid rounded" alt="Radio_logo_name">
                        </div>
                        <div style="flex-grow: 1;" class="my-1">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15">
                                &nbsp;<h6 class="my-auto">Radio Verified</h6>
                            </div>
                            <h5>M Radio Iraq</h5>
                            <p class="m-0">7,123,321 Listeners</p>
                        </div>
                    </div>
                    <div style="align-self: flex-end;" class="my-auto">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-stretch p-0">
                    <div class="d-flex align-items-center">
                        <div style="width: 80px; height: 80px; overflow: hidden; margin-right: 20px;">
                            <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_logo_2023181117003269916864.jpeg" class="img-fluid rounded" alt="Radio_logo_name">
                        </div>
                        <div style="flex-grow: 1;" class="my-1">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15">
                                &nbsp;<h6 class="my-auto">Radio Verified</h6>
                            </div>
                            <h5>M Radio Iraq</h5>
                            <p class="m-0">7,123,321 Listeners</p>
                        </div>
                    </div>
                    <div style="align-self: flex-end;" class="my-auto">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>