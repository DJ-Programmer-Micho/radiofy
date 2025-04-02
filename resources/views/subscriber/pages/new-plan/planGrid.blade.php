<div>

        <div class="row justify-content-center mt-4">
            @include('subscriber.pages.new-plan.formPlanGrid',[
                'plan' => $this->planSelected ?? null,
                'duration' => $this->durationSelect
                ])
            <div class="col-lg-5">
                <div class="text-center mb-4">
                    <h4 class="fs-22">Plans & Pricing</h4>
                    <p class="text-muted mb-4 fs-15">Simple pricing. No hidden fees. Advanced features for
                        you business.</p>

                    <div class="d-inline-flex">
                        <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold @if($durationSelect === 'monthly') active @endif" wire:click='changeDuration("monthly")' id="month-tab" type="button" role="tab" aria-selected="true">Monthly</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold @if($durationSelect === 'yearly') active @endif" wire:click='changeDuration("yearly")' id="annual-tab" type="button" role="tab" aria-selected="false">Annually <span class="badge bg-success">17% Off</span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <!-- 64 Plan -->
        <div class="row">
            <div class="text-left">
                <h4 class="fs-22">64 Bitrate Plans</h4>
            </div>
            @foreach ($plans_64 as $plan)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-body bg-light m-2 p-4">
                        @if ($plan->ribbon)
                        <div class="ribbon-two ribbon-two-danger"><span>{{ $plan->rib_text }}</span></div>
                        @endif
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Plan-{{ $plan->id }}</h5>
                            </div>
                            <div class="ms-auto">
                                @if($durationSelect === 'monthly')
                                <h2 class="month mb-0">${{ $plan->sell_price_monthly }} <small class="fs-13 text-muted text-right">/Month</small>
                                </h2>
                                @elseif($durationSelect === 'yearly')
                                <h2 class="month mb-0" style="text-align: right">${{ number_format($plan->sell_price_yearly/12,2) }} <small class="fs-13 text-muted">/Month</small>
                                </h2>
                                <h4 class="annual mb-0"><small class="fs-16"><del>${{ number_format($plan->sell_price_monthly*12, 2) }}</del></small> ${{ $plan->sell_price_yearly }}
                                    <small class="fs-13 text-muted">/billed yearly</small>
                                </h4>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->bitrate }}</b> Bitrate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->max_listeners }}</b> Max Listeners
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Scalable Bandwidth
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Server Control
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    @if ($plan->support)
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        @else
                                    <div class="flex-shrink-0 text-danger me-1">
                                        <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>24/7</b> Support
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            @if (auth()->guard('subscriber')->check())
                                @if (in_array($plan->id, $subscriberPlanIds))
                                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                        Add one more
                                    </button>
                                @else
                                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                        Get Radio
                                    </button>
                                @endif
                            @else
                            <a wire:navigate href="{{ route("subs.signup") }}" type="button" class="btn btn-info w-100">
                                Get Radio
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!--end col-->
        </div>
        <!--end row-->
        <!-- 96 Plan -->
        <div class="row">
            <div class="text-left">
                <h4 class="fs-22">96 Bitrate Plans</h4>
            </div>
            @foreach ($plans_96 as $plan)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-body bg-light m-2 p-4">
                        @if ($plan->ribbon)
                        <div class="ribbon-two ribbon-two-danger"><span>{{ $plan->rib_text }}</span></div>
                        @endif
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Plan-{{ $plan->id }}</h5>
                            </div>
                            <div class="ms-auto">
                                @if($durationSelect === 'monthly')
                                <h2 class="month mb-0">${{ $plan->sell_price_monthly }} <small class="fs-13 text-muted text-right">/Month</small>
                                </h2>
                                @elseif($durationSelect === 'yearly')
                                <h2 class="month mb-0" style="text-align: right">${{ number_format($plan->sell_price_yearly/12,2) }} <small class="fs-13 text-muted">/Month</small>
                                </h2>
                                <h4 class="annual mb-0"><small class="fs-16"><del>${{ number_format($plan->sell_price_monthly*12, 2) }}</del></small> ${{ $plan->sell_price_yearly }}
                                    <small class="fs-13 text-muted">/billed yearly</small>
                                </h4>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->bitrate }}</b> Bitrate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->max_listeners }}</b> Max Listeners
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Scalable Bandwidth
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Server Control
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    @if ($plan->support)
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        @else
                                    <div class="flex-shrink-0 text-danger me-1">
                                        <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>24/7</b> Support
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            @if (in_array($plan->id, $subscriberPlanIds))
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Add one more
                                </button>
                            @else
                                <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Get Radio
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!--end col-->
        </div>
        <!--end row-->
        <!-- 128 Plan -->
        <div class="row">
            <div class="text-left">
                <h4 class="fs-22">128 Bitrate Plans</h4>
            </div>
            @foreach ($plans_128 as $plan)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-body bg-light m-2 p-4">
                        @if ($plan->ribbon)
                        <div class="ribbon-two ribbon-two-danger"><span>{{ $plan->rib_text }}</span></div>
                        @endif
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Plan-{{ $plan->id }}</h5>
                            </div>
                            <div class="ms-auto">
                                @if($durationSelect === 'monthly')
                                <h2 class="month mb-0">${{ $plan->sell_price_monthly }} <small class="fs-13 text-muted text-right">/Month</small>
                                </h2>
                                @elseif($durationSelect === 'yearly')
                                <h2 class="month mb-0" style="text-align: right">${{ number_format($plan->sell_price_yearly/12,2) }} <small class="fs-13 text-muted">/Month</small>
                                </h2>
                                <h4 class="annual mb-0"><small class="fs-16"><del>${{ number_format($plan->sell_price_monthly*12, 2) }}</del></small> ${{ $plan->sell_price_yearly }}
                                    <small class="fs-13 text-muted">/billed yearly</small>
                                </h4>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->bitrate }}</b> Bitrate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->max_listeners }}</b> Max Listeners
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Scalable Bandwidth
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Server Control
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    @if ($plan->support)
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        @else
                                    <div class="flex-shrink-0 text-danger me-1">
                                        <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>24/7</b> Support
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            @if (in_array($plan->id, $subscriberPlanIds))
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Add one more
                                </button>
                            @else
                                <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Get Radio
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!--end col-->
        </div>
        <!--end row-->
        <!-- 256 Plan -->
        <div class="row">
            <div class="text-left">
                <h4 class="fs-22">256 Bitrate Plans</h4>
            </div>
            @foreach ($plans_256 as $plan)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-body bg-light m-2 p-4">
                        @if ($plan->ribbon)
                        <div class="ribbon-two ribbon-two-danger"><span>{{ $plan->rib_text }}</span></div>
                        @endif
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Plan-{{ $plan->id }} {{ $durationSelect }}</h5>
                            </div>
                            <div class="ms-auto">
                                
                                @if($durationSelect === 'monthly')
                                <h2 class="month mb-0">${{ $plan->sell_price_monthly }} <small class="fs-13 text-muted text-right">/Month</small>
                                </h2>
                                @elseif($durationSelect === 'yearly')
                                <h2 class="month mb-0" style="text-align: right">${{ number_format($plan->sell_price_yearly/12,2) }} <small class="fs-13 text-muted">/Month</small>
                                </h2>
                                <h4 class="annual mb-0"><small class="fs-16"><del>${{ number_format($plan->sell_price_monthly*12, 2) }}</del></small> ${{ $plan->sell_price_yearly }}
                                    <small class="fs-13 text-muted">/billed yearly</small>
                                </h4>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->bitrate }}</b> Bitrate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->max_listeners }}</b> Max Listeners
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Scalable Bandwidth
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Server Control
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    @if ($plan->support)
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        @else
                                    <div class="flex-shrink-0 text-danger me-1">
                                        <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>24/7</b> Support
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            @if (in_array($plan->id, $subscriberPlanIds))
                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Add one more
                                </button>
                            @else
                                <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">
                                    Get Radio
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!--end col-->
        </div>
        <!--end row-->
        <!-- 320 Plan -->
        <div class="row">
            <div class="text-left">
                <h4 class="fs-22">320 Bitrate Plans</h4>
            </div>
            @foreach ($plans_320 as $plan)
            <div class="col-xxl-3 col-lg-6">
                <div class="card pricing-box ribbon-box right">
                    <div class="card-body bg-light m-2 p-4">
                        @if ($plan->ribbon)
                        <div class="ribbon-two ribbon-two-danger"><span>{{ $plan->rib_text }}</span></div>
                        @endif
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Plan-{{ $plan->id }}</h5>
                            </div>
                            <div class="ms-auto">
                                @if($durationSelect === 'monthly')
                                <h2 class="month mb-0">${{ $plan->sell_price_monthly }} <small class="fs-13 text-muted text-right">/Month</small>
                                </h2>
                                @elseif($durationSelect === 'yearly')
                                <h2 class="month mb-0" style="text-align: right">${{ number_format($plan->sell_price_yearly/12,2) }} <small class="fs-13 text-muted">/Month</small>
                                </h2>
                                <h4 class="annual mb-0"><small class="fs-16"><del>${{ number_format($plan->sell_price_monthly*12, 2) }}</del></small> ${{ $plan->sell_price_yearly }}
                                    <small class="fs-13 text-muted">/billed yearly</small>
                                </h4>
                                @endif
                            </div>
                        </div>

                        <p class="text-muted">The perfect way to get started and get used to our tools.</p>
                        <ul class="list-unstyled vstack gap-3">
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->bitrate }}</b> Bitrate
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>{{ $plan->max_listeners }}</b> Max Listeners
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Scalable Bandwidth
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Server Control
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex">
                                    @if ($plan->support)
                                    <div class="flex-shrink-0 text-success me-1">
                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                        @else
                                    <div class="flex-shrink-0 text-danger me-1">
                                        <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <b>24/7</b> Support
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-3 pt-2">
                            <button type="button" class="btn btn-info w-100"  data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPlan('{{ $plan->id }}')">Get Radio</button>
                        </div>
                        {{-- <div class="mt-3 pt-2">
                            <a href="javascript:void(0);" class="btn btn-danger disabled w-100">Your Current
                                Plan</a>
                        </div> --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
</div>

@push('radio_script')

@endpush

