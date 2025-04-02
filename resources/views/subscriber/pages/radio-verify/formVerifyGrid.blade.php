<div>
    <!-- Verify Radio Modal -->
    <div wire:ignore.self class="modal fade overflow-auto" id="getRadioModal" tabindex="-1" aria-labelledby="getRadioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-white mx-1 mx-lg-auto">
            <div class="modal-content bg-dark">
                <form wire:submit.prevent="verifyRadio">
                    <div class="modal-body">
                        <div class="modal-header mb-3">
                            <h5 class="modal-title" id="getRadioModalLabel">{{ __('Verify Radio') }}</h5>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <hr class="bg-white">

                        <div class="row">
                            <!-- Left: Radio Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="radioSelection">{{ __('Select a Radio to Verify') }}</label>
                                <select wire:model="radioSlug" class="form-control @error('radioSlug') is-invalid @enderror">
                                    <option value="">{{ __('-- Choose Radio --') }}</option>
                                    @foreach($availableRadios as $slug => $radio)
                                        <option value="{{ $slug }}">{{ $radio['name'] }} ({{ ucfirst($radio['type']) }})</option>
                                    @endforeach
                                </select>
                                @error('radioSlug') <span class="text-danger">{{ $message }}</span> @enderror
                                <div class="mt-4">
                                    @if ($verify)
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
                                </div>
                            </div>

                            <!-- Right: Payment Info & Pricing -->
                            <div class="col-md-6">
                                <div>
                                    <label for="paymentMethod">{{ __('Payment Method') }}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card mb-3 bg-light">
                                                <div class="card-body p-3">
                                                    <div class="form-check form-check-primary">
                                                        <input class="form-check-input" type="radio" wire:model.defer="paymentMethod" value="credit_card" id="paymentCreditCard">
                                                        <label class="form-check-label d-flex justify-content-between" for="paymentCreditCard">
                                                            <span>{{ __('Credit Card') }}</span>
                                                            <img src="{{ asset('assets/payments_icon/visamaster.webp') }}" width="65" alt="Visa Master">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3 bg-light">
                                                <div class="card-body p-3">
                                                    <div class="form-check form-check-primary">
                                                        <input class="form-check-input" type="radio" wire:model.defer="paymentMethod" value="zain_cash" id="paymentZainCash">
                                                        <label class="form-check-label d-flex justify-content-between" for="paymentZainCash">
                                                            <span>{{ __('Zain Cash') }}</span>
                                                            <img src="{{ asset('assets/payments_icon/zaincash.png') }}" width="65" alt="ZainCash">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                @if ($verify)
                                    <table class="table table-bordered text-white" style="border-color: #aaa">
                                        <tr>
                                            <td>{{ __('Total') }}:</td>
                                            <td>
                                                @if($duration === 'monthly')
                                                    ${{ number_format($verify->sell_price_monthly, 2) }}/{{ __('Month') }}
                                                @else
                                                    ${{ number_format($verify->sell_price_yearly, 2) }}/{{ __('Year') }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Fees') }}:</td>
                                            <td><strong class="text-success">{{ __('NO FEES') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Total Cost') }}:</td>
                                            <td>
                                                <strong>
                                                    @if($duration === 'monthly')
                                                        ${{ number_format($verify->sell_price_monthly, 2) }}/{{ __('Month') }}
                                                    @else
                                                        ${{ number_format($verify->sell_price_yearly, 2) }}/{{ __('Year') }}
                                                    @endif
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Verify Now') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
