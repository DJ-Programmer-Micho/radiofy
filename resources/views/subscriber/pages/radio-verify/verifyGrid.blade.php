<div class="page-content">
    <style>
        .m-index {
            z-index: 9999;
        }
    </style>

    @include('subscriber.pages.radio-verify.formVerifyGrid', [
        'verify' => $this->verificationSelected ?? null,
        'duration' => $this->durationSelect,
        'availableRadios' => $availableRadios ?? []
    ])

    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Radios') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Radios') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Options -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-5">
                <div class="text-center mb-4">
                    <h4 class="fs-22">{{ __('Get Verified') }}
                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="35" height="35" class="mb-4"
                        style="max-width:35px; max-height:35px"></h4>
                    <p class="text-muted mb-4 fs-15">{{ __('Simple pricing. No hidden fees. Advanced features for your business.') }}</p>
                    <div class="d-inline-flex">
                        <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold @if($durationSelect === 'monthly') active @endif" wire:click='changeDuration("monthly")'>{{ __('Monthly') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold @if($durationSelect === 'yearly') active @endif" wire:click='changeDuration("yearly")'>{{ __('Annually') }} <span class="badge bg-success">17% Off</span></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Plans -->
        <div class="row">
            @foreach ($verifications as $verify)
                <div class="col-xxl-3 col-lg-6">
                    <div class="card pricing-box ribbon-box right">
                        <div class="card-body p-4 m-2">
                            @if ($verify->ribbon)
                                <div class="ribbon-two ribbon-two-danger"><span>{{ $verify->rib_text }}</span></div>
                            @endif

                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $verify->name }}</h5>
                                </div>
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-light rounded-circle text-primary">
                                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="35" height="35" 
                                        style="max-width:35px; max-height:35px">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                @if($durationSelect === 'monthly')
                                    <h2 class="month mb-0">${{ $verify->sell_price_monthly }} <small class="fs-13 text-muted">/Month</small></h2>
                                @else
                                    <h2 class="month mb-0">${{ number_format($verify->sell_price_yearly / 12, 2) }} <small class="fs-13 text-muted">/Month</small></h2>
                                    <h4 class="annual mb-0">
                                        <small class="fs-16">
                                            <del>${{ number_format($verify->sell_price_monthly * 12, 2) }}</del>
                                        </small>
                                        ${{ $verify->sell_price_yearly }}
                                        <small class="fs-13 text-muted">/billed yearly</small>
                                    </h4>
                                @endif
                            </div>

                            <hr class="my-4 text-muted">

                            @if ($verify->features)
                            <ul class="list-unstyled vstack gap-3 text-muted">
                                @foreach($verify->features as $feature)
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-1">
                                                @if($feature['check'])
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle text-success"></i>
                                                @else
                                                    <i class="ri-close-circle-fill fs-15 align-middle text-danger"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $feature['text'] }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        

                            <div class="mt-4">
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectVerification('{{ $verify->id }}')">
                                    {{ __('Get Verified') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('radio_script')
@endpush
