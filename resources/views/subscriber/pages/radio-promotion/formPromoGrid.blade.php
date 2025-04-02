<div>
    <!-- Promote Radio Modal -->
    <div wire:ignore.self class="modal fade overflow-auto" id="getRadioModal" tabindex="-1" aria-labelledby="getRadioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-white mx-1 mx-lg-auto">
            <div class="modal-content bg-dark">
                <form wire:submit.prevent="promoteRadio">
                    <div class="modal-body">
                        <div class="modal-header mb-3">
                            <h5 class="modal-title" id="getRadioModalLabel">{{ __('Promote Radio') }}</h5>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <hr class="bg-white">
                    
                        <div class="row">
                            <!-- Left: Radio Selection -->
                            <div class="col-md-6 mb-3">
                                <div class="mb-3">
                                    <label for="radioSelection">{{ __('Select a Radio to Promote') }}</label>
                                    <select wire:model="radioSlug" wire:change="calculatePricing" class="form-control @error('radioSlug') is-invalid @enderror">
                                        <option value="">{{ __('-- Choose Radio --') }}</option>
                                        @foreach($availableRadios as $slug => $radio)
                                            <option value="{{ $slug }}">{{ $radio['name'] }} ({{ ucfirst($radio['type']) }})</option>
                                        @endforeach
                                    </select>
                                    @error('radioSlug') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="sponserText">{{ __('Sponser Text') }}</label>
                                    <input type="text" class="form-control @error('sponserText') is-invalid @enderror" wire:model="sponserText" placeholder="Listen To The NEWS!!!" maxlength="50">
                                    @error('sponserText')<span class="text-danger">{{ __($message) }}</span>@enderror
                                </div>
                                <!-- Additional Filters -->
                                <div class="mb-3">
                                    <label>{{ __('Target Gender') }}</label>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" wire:model="targetGenders" value="1" id="genderMale">
                                        <label class="form-check-label" for="genderMale">{{ __('Male') }}</label>
                                    </div>
                                    <div class="form-check form-check-secondary">
                                        <input class="form-check-input" type="checkbox" wire:model="targetGenders" value="2" id="genderFemale">
                                        <label class="form-check-label" for="genderFemale">{{ __('Female') }}</label>
                                    </div>
                                    <div class="form-check form-check-success">
                                        <input class="form-check-input" type="checkbox" wire:model="targetGenders" value="3" id="genderNotSay">
                                        <label class="form-check-label" for="genderNotSay">{{ __('Rather Not Say') }}</label>
                                    </div>
                                    @error('targetGenders') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label>{{ __('Target Age Range') }}</label>
                                    <div class="form-check form-check-success">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="13-17" id="age13to17">
                                        <label class="form-check-label" for="age13to17">{{ __('13-17') }}</label>
                                    </div>
                                    <div class="form-check form-check-warning">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="18-25" id="age18to25">
                                        <label class="form-check-label" for="age18to25">{{ __('18-25') }}</label>
                                    </div>
                                    <div class="form-check form-check-info">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="26-32" id="age26to32">
                                        <label class="form-check-label" for="age26to32">{{ __('26-32') }}</label>
                                    </div>
                                    <div class="form-check form-check-primary">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="33-40" id="age33to40">
                                        <label class="form-check-label" for="age33to40">{{ __('33-40') }}</label>
                                    </div>
                                    <div class="form-check form-check-secondary">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="41-64" id="age41to64">
                                        <label class="form-check-label" for="age41to64">{{ __('41-64') }}</label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <input class="form-check-input" type="checkbox" wire:model="targetAgeRanges" value="65+" id="age65plus">
                                        <label class="form-check-label" for="age65plus">{{ __('65+') }}</label>
                                    </div>
                                    @error('targetAgeRanges') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                
                                <div class="mt-4">
                                    <label>{{ __('The Deal') }}</label>
                                    <div class="card-body pb-0">
                                        @if ($promoSelected)
                                        <ul class="list-unstyled vstack gap-3 mb-0">
                                            @foreach($promoSelected->features as $feature)
                                                <li>{{ is_array($feature) ? $feature['key'] . ': ' . $feature['value'] : $feature }}</li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
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
                    
                                @if ($promoSelected)
                                    <table class="table table-bordered text-white" style="border-color: #aaa">
                                        <tr>
                                            <td>{{ __('Total') }}:</td>
                                            <td>${{ number_format($promoSelected->sell, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Days') }}:</td>
                                            <td>{{ $promoSelected->duration }} Days</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Fees') }}:</td>
                                            <td><strong class="text-success">{{ __('NO FEES') }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Discount') }}:</td>
                                            <td><strong class="text-danger">{{ $calculatedDiscount }}%</strong></td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Total Cost') }}:</td>
                                            <td>
                                                <strong>${{ number_format($calculatedFinalPrice, 2) }}</strong>
                                            </td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Promote Now') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
