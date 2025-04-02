<div class="page-content">
    <style>
        .m-index {
            z-index: 9999;
        }
    </style>

    @include('subscriber.pages.radio-promotion.formPromoGrid', [
        'promo' => $this->promoSelected ?? null,
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

        <!-- Promotion Options -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-5">
                <div class="text-center mb-4">
                    <h4 class="fs-22">{{ __('Get Promoted') }}<span class="badge badge-label bg-success"><i class="mdi mdi-circle-medium"></i> AD</span></h4>
                    <p class="text-muted mb-4 fs-15">{{ __('Simple pricing. No hidden fees. Advanced features for your business.') }}</p>
                </div>
            </div>
        </div>

        <!-- Promotion Plans -->
        <div class="row">
            @foreach ($promotions as $promo)
            <div class="col-lg-6">
                <div class="card pricing-box ribbon-box ribbon-fill text-center">
                    @if ($promo->ribbon)
                    <div class="ribbon ribbon-primary">{{ $promo->rib_text }}</div>
                    @endif
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="card-body h-100">
                                <div>
                                    <h5 class="mb-1">{{ $promo->name }}</h5>
                                    <p class="text-muted">{{ $promo->sub_name }}</p>
                                </div>
                                <div class="py-4">
                                    <h2><sup><small>$</small></sup>{{ $promo->sell }} <span class="fs-13 text-muted"></span></h2>
                                </div>
                                <div class="text-center plan-btn mt-2">
                                    <a href="javascript:void(0);" class="btn btn-success w-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#getRadioModal" wire:click="selectPromotion('{{ $promo->id }}')">Promote</a>
                                </div>
                            </div>
                        </div>
                        <!-- Plan Features -->
                        <div class="col-lg-6">
                            <div class="card-body border-start mt-4 mt-lg-0">
                                <div class="card-header bg-light">
                                    <h5 class="fs-15 mb-0">Plan Features:</h5>
                                </div>
                                <div class="card-body pb-0">
                                    @if ($promo->features)
                                    <ul class="list-unstyled vstack gap-3 mb-0">
                                        @foreach($promo->features as $feature)
                                        <li>{{ is_array($feature) ? $feature['key'] . ': ' . $feature['value'] : $feature }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('radio_script')
@endpush
