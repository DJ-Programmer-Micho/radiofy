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
    <div class="container-fluid">
        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="{{ $radio['profile']['banner'] 
                ? asset('storage/' . $radio['profile']['banner']) 
                : asset('assets/logo/mradiofy-banner.png') }}" alt="" class="profile-wid-img" />
            </div>
        </div>
        <div class="pt-4 profile-wrapper" >
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{ $radio['profile']['logo'] 
                                    ? asset('storage/' . $radio['profile']['logo']) 
                                    : asset('assets/logo/mradiofy-logo.png') }}" alt="user-img" class="img-thumbnail" />
                    </div>
                </div>
                <!--end col-->
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1"> {{ $radio->radio_name }} 
                            <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="25" height="25" style="max-width:25px; max-height:25px" class="mb-3">
                        </h3>
                        <p class="text-white text-opacity-75">{{ $radio->subscriber->subscriber_profile->first_name .' '. $radio->subscriber->subscriber_profile->last_name }}</p>
                        <div class="hstack text-white-50 gap-1">
                            <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white text-opacity-75 fs-16 align-middle"></i>{{ $radio->profile->location }}</div>
                            <div>


                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
                <div class="col-12 col-lg-2 order-last order-lg-0">
                    <div class="row text text-white-50 text-center">
                        <!-- Follow Section -->
                        <div class="col-lg-6 col-4">
                            <div class="p-1">
                                <h4 class="text-white mb-1">{{ number_format($followersCount) }}</h4>
                                @if(auth()->guard('listener')->user())
                                    @if($isFollowed)
                                        <button wire:click="toggleFollow" class="btn btn-dark p-1">
                                            <i class="fa-solid fa-user text-success" style="font-size: 15px;"></i> Followed
                                        </button>
                                    @else
                                        <button wire:click="toggleFollow" class="btn btn-dark p-1">
                                            <i class="fa-solid fa-user-plus" style="font-size: 15px;"></i> Follow
                                        </button>
                                    @endif
                                @else
                                    <a href="{{  route("lis.signin") }}" type="button" class="btn btn-dark p-1">
                                        <i class="fa-solid fa-user-plus" style="font-size: 15px;"></i> Follow
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- Like Section -->
                        <div class="col-lg-6 col-4">
                            <div class="p-1">
                                <h4 class="text-white mb-1">{{ number_format($likesCount) }}</h4>
                                @if(auth()->guard('listener')->user())
                                    @if($isLiked)
                                        <button wire:click="toggleLike" class="btn btn-dark p-1">
                                            <i class="fa-solid fa-heart text-danger" style="font-size: 15px;"></i> Liked
                                        </button>
                                    @else
                                        <button wire:click="toggleLike" class="btn btn-dark p-1">
                                            <i class="fa-regular fa-heart" style="font-size: 15px;"></i> Like
                                        </button>
                                    @endif
                                @else
                                    <a href="{{  route("lis.signin") }}" type="button" class="btn btn-dark p-1">
                                        <i class="fa-regular fa-heart" style="font-size: 15px;"></i> Like
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--end col-->

            </div>
            <!--end row-->
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex profile-wrapper">
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                    <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Activities</span>
                                </a>
                            </li>

                        </ul>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-success" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $radio->id }}, radioType: '{{ $radio->type }}' } }));">
                                Play <i class="fa-solid fa-play" style="font-size:12px"></i>
                            </button>
                            {{-- <a href="pages-profile-settings.html" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a> --}}
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">Links</h5>
                                            <div class="d-flex flex-wrap gap-2">
                                            @if(!empty(json_decode($radio->profile->social_media)->facebook))
                                                <div>
                                                    <a href="{{ json_decode($radio->profile->social_media)->facebook }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-primary">
                                                            <i class="fab fa-facebook"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->instagram))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->instagram }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-secondary">
                                                            <i class="fab fa-instagram"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->tiktok))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->tiktok }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-dark">
                                                            <i class="fab fa-tiktok"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->snapchat))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->snapchat }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-warning">
                                                            <i class="fab fa-snapchat"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->twitter))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->twitter }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-dark">
                                                            <i class="fab fa-x-twitter"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->linkedin))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->linkedin }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-info">
                                                            <i class="fab fa-linkedin"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if(!empty(json_decode($radio->profile->social_media)->youtube))
                                                <div>
                                                    <a href="{{json_decode($radio->profile->social_media)->youtube }}" class="avatar-xs d-block" target="_blank">
                                                        <span class="avatar-title rounded-circle fs-16 bg-danger">
                                                            <i class="fab fa-youtube"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            @endif
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                    @php
                                        $localeIds = [];
                                        if (isset($radio->profile->radio_locale)) {
                                            $localeIds = is_array($radio->profile->radio_locale)
                                                ? $radio->profile->radio_locale
                                                : json_decode($radio->profile->radio_locale, true) ?? [];
                                        }
                                        $locales = \App\Models\Language::whereIn('id', $localeIds)->pluck('name')->toArray();

                                        // Genres: Instead of reading from the profile JSON,
                                        // use the polymorphic relationship on the $radio model.
                                        $genres = $radio->genres()->with('genreTranslation')->get()->map(function($genre) {
                                        return [
                                            'id'    => $genre->id,
                                            'name'  => $genre->genreTranslation ? $genre->genreTranslation->name : 'N/A',
                                            'image' => $genre->image_sq ?? 'N/A',
                                        ];
                                        })->toArray();
                                    @endphp
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">Language's</h5>
                                            <div class="d-flex flex-wrap gap-2 fs-15">
                                                @if(!empty($locales))
                                                    @foreach($locales as $locale)
                                                        <span class="badge bg-primary-subtle text-info">{{ $locale }}</span>
                                                    @endforeach
                                                @else
                                                    <p>No locales available.</p>
                                                @endif
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">Genre's</h5>
                                            <div class="d-flex flex-wrap gap-2 fs-15">
                                                @if(!empty($genres))
                                                    @foreach($genres as $genre)
                                                    <a wire:navigate href="{{  route('listener.genreView',['genreId' => $genre['id']]) }}">
                                                    <img src="{{ asset('storage/'.$genre['image']) }}" width="80" height="80" style="max-width: 80px; max-hight: 80px;" alt="{{ $genre['name'] }}"
                                                    class="rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $genre['name'] }}">
                                                    </a>
                                                    @endforeach
                                                @else
                                                    <p>No locales available.</p>
                                                @endif
                                            </div>
                                        </div><!-- end card body -->
                                    </div><!-- end card -->
                                </div>
                                    <!--end card-->

                                <div class="col-xxl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">About</h5>
                                            <p>{{ $radio->profile->description }}</p>
                                            <!--end row-->
                                        </div>
                                        <!--end card-body-->
                                    </div><!-- end card -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <div class="tab-pane fade" id="activities" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Other Radios</h5>
                                    <div class="row">
                                    @foreach ($otherRadio as $oRadio)
                                            <div class="col-6 col-md-3 col-lg-2">
                                                <div class="card-poster radio-card-1">
                                                    <button type="button" class="btn btn-none p-0" onclick="window.dispatchEvent(new CustomEvent('switch-radio', { detail: { radioId: {{ $oRadio['id'] }}, radioType: '{{ $oRadio['type'] }}' } }));">
                                                        <div class="player-icon" style="overflow: hidden;">
                                                            <img src="{{ $oRadio['profile']['logo'] ? asset('storage/' . $oRadio['profile']['logo']) : asset('assets/logo/mradiofy-logo.png') }}"
                                                                class="img-fluid" 
                                                                alt="Logo">
                                                        </div>
                                                    </button>
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <a href="{{ route('listener.radio',['slug' => $oRadio['radio_name_slug']])}}" rel="noopener noreferrer">
                                                                <p class="m-0"><b>{{ $oRadio['radio_name'] }}</b></p>
                                                            </a>
                                                            </div>
                                                            <div>
                                                                @if ($oRadio['verified'] )
                                                                <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="15" height="15" class="mx-1"
                                                                style="max-width:15px; max-height:15px">
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <p class="m-0">{{ number_format($oRadio['listeners_count']) }} Listeners</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end tab-pane-->
                    </div>
                    <!--end tab-content-->
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div><!-- container-fluid -->
</div><!-- End Page-content -->