<div>
    {{-- Add Radio Modal --}}
    <div wire:ignore.self class="modal fade overflow-auto" id="getRadioModal" tabindex="-1"
         aria-labelledby="getRadioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-white mx-1 mx-lg-auto">
            <div class="modal-content bg-dark">
                <form wire:submit.prevent="addRadio">
                    <div class="modal-body">
                        <div class="modal-header mb-3">
                            <h5 class="modal-title" id="getRadioModalLabel">{{ __('Add Radio') }}</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal"
                                    aria-label="Close"><i class="fas fa-times"></i></button>
                        </div>
                        <hr class="bg-white">
                        <div class="row">
                            {{-- Left Column --}}
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="radioName">{{ __('Radio Name') }}</label>
                                        <input type="text"
                                               class="form-control @error('radioName') is-invalid @enderror @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                               wire:model="radioName" placeholder="Radio Name" required>
                                        @error('radioName')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverSource">{{ __('Server Source') }}</label>
                                        <div class="input-group">
                                            <!-- Note: Changed wire:model from "source" to "serverSource" -->
                                            <input type="text"
                                                   class="form-control @error('serverSource') is-invalid @enderror @if(!$errors->has('serverSource') && !empty($serverSource)) is-valid @endif"
                                                   wire:model="serverSource" placeholder="Server Source" required>
                                            <span class="input-group-text" id="basic-addon2">@mradiofy</span>
                                        </div>
                                        @error('serverSource')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverPassword">{{ __('Source Password') }}</label>
                                        <!-- Note: Changed wire:model from "location" to "serverPassword" -->
                                        <input type="text"
                                               class="form-control @error('serverPassword') is-invalid @enderror @if(!$errors->has('serverPassword') && !empty($serverPassword)) is-valid @endif"
                                               wire:model="serverPassword" placeholder="********" required>
                                        @error('serverPassword')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    <table class="table" style="border-color: #aaa">
                                        <tr>
                                            <td>Package Name:</td>
                                            <td><b>{{ $plan->id ?? null }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>Bitrate:</td>
                                            <td><b>{{ $plan->bitrate ?? null }} Kbps</b></td>
                                        </tr>
                                        <tr>
                                            <td>Max Listeners:</td>
                                            <td><b>{{ $plan->max_listeners ?? null }} Listeners</b></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            {{-- Right Column --}}
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <label for="">Payment Method</label>
                                    <div class="row">
                                        <!-- Payment methods -->
                                        <div class="col-lg-6">
                                            <div class="card mb-3">
                                                <div class="card-body p-3 bg-light">
                                                    <div class="form-check form-check-primary mb-3">
                                                        <input class="form-check-input" type="radio" name="payment" value="1" wire:model.defer="news">
                                                        <label class="form-check-label d-flex justify-content-between">
                                                            <div>Credit Card</div>
                                                            <div>
                                                                <img src="{{ asset('assets/payments_icon/visamaster.webp') }}" width="65px" alt="">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card mb-3">
                                                <div class="card-body p-3 bg-light">
                                                    <div class="form-check form-check-primary mb-3">
                                                        <input class="form-check-input" type="radio" name="payment" value="2" wire:model.defer="news">
                                                        <label class="form-check-label d-flex justify-content-between">
                                                            <div>Zain Cash</div>
                                                            <div>
                                                                <img src="{{ asset('assets/payments_icon/zaincash.png') }}" width="65px" alt="">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                @if ($plan)
                                    <table class="table" style="border-color: #aaa">
                                        <tr>
                                            <td>Total:</td>
                                            @if($durationSelect === 'monthly')
                                                <td>${{ number_format($plan->sell_price_monthly,2) }}/Month</td>
                                            @elseif($durationSelect === 'yearly')
                                                <td>${{ number_format($plan->sell_price_yearly,2) }}/Year</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Fees:</td>
                                            <td><b class="text-success">NO FEES</b></td>
                                        </tr>
                                        <tr>
                                            <td>Total Cost:</td>
                                            @if($durationSelect === 'monthly')
                                                <td><b>${{ number_format($plan->sell_price_monthly,2) }}/Month</b></td>
                                            @elseif($durationSelect === 'yearly')
                                                <td><b>${{ number_format($plan->sell_price_yearly,2) }}/Year</b></td>
                                            @endif
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary submitJs">{{ __('Add Radio') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Update Radio Modal could be added similarly if needed --}}
</div>
