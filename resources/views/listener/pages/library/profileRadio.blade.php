{{-- NOT USED COMPONENT --}}
<div class="px-3">
    <div class="tab-content tab-content-carousel">
        <h1>Radio Owner's You Followed</h1>
            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": true, 
                    "dots": true,
                    "lazyLoad": true,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": {
                            "items":1
                        },
                        "600": {
                            "items":2
                        },
                        "792": {
                            "items":2
                        },
                        "1040": {
                            "items":2
                        },
                        "1200": {
                            "items":2
                        },
                        "1500": {
                            "items":4
                        },
                        "1800": {
                            "items":6
                        }
                    }
                }'>
                @foreach($followedOwner as $radio)
                <div class="card">
                    <a wire:navigate href="{{ route('listener.radio',['slug' => $radio->radio_name_slug])}}">
                        <div class="text-center my-3">
                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                <img src="{{ 
                                    $radio->subscriber && 
                                    $radio->subscriber->subscriber_profile && 
                                    $radio->subscriber->subscriber_profile->avatar 
                                    ? asset('storage/' . $radio->subscriber->subscriber_profile->avatar) 
                                    : asset('assets/default-avatar.png') 
                                }}" 
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" 
                                alt="user-profile-image">
                            </div>
                            <h5 class="fs-16 mb-1">
                                {{ 
                                    ($radio->subscriber && $radio->subscriber->subscriber_profile)
                                    ? $radio->subscriber->subscriber_profile->first_name . ' ' . $radio->subscriber->subscriber_profile->last_name 
                                    : 'No Name' 
                                }}
                            </h5>
                            <p class="text-muted mb-0">Radio Owner</p>
                        </div>
                    </a>
                </div>
            @endforeach
            </div><!-- End .owl-carousel -->

    </div><!-- End .tab-content -->
</div><!-- End .container -->