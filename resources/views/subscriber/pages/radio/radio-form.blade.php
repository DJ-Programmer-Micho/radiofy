{{-- file path: resources/views/super-admins/pages/Radios/Radio-form.blade.php --}}
<div>
    {{-- Add Radio Modal --}}
    <div wire:ignore.self class="modal fade overflow-auto" id="addRadioModal" tabindex="-1"
         aria-labelledby="addRadioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-white mx-1 mx-lg-auto">
            <div class="modal-content bg-dark">
                <form wire:submit.prevent="addRadio">
                    <div class="modal-body">
                        <div class="modal-header mb-3">
                            <h5 class="modal-title" id="addRadioModalLabel">{{ __('Add Radio') }}</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal"
                                    aria-label="Close"><i class="fas fa-times"></i></button>
                        </div>
                        <hr class="bg-white">
                        <div class="row">
                            {{-- Left Column --}}
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="radioName">{{__('Radio Name')}}</label>
                                        <input type="text" class="form-control @error('radioName') is-invalid @enderror @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                               wire:model="radioName" placeholder="Radio Name">
                                        @error('radioName')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="source">{{__('Server Source')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('source') is-invalid @enderror @if(!$errors->has('source') && !empty($source)) is-valid @endif"
                                            wire:model="source" placeholder="Erbil, Baghdad, - Iraq">
                                            <span class="input-group-text" id="basic-addon2">@mradiofy</span>
                                        </div>
                                        @error('source')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="source_password">{{ __('Genre') }}</label>
                                        <input type="text" class="form-control @error('source_password') is-invalid @enderror @if(!$errors->has('source_password') && !empty($genre)) is-valid @endif"
                                               wire:model="source_password" placeholder="Rock, Pop, etc.">
                                        @error('source_password')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- Right Column --}}
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="location">{{__('Radio Location')}}</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror @if(!$errors->has('location') && !empty($location)) is-valid @endif"
                                               wire:model="location" placeholder="Erbil, Baghdad, - Iraq">
                                        @error('location')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="source_password">{{__('Source Password')}}</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror @if(!$errors->has('location') && !empty($location)) is-valid @endif"
                                               wire:model="location" placeholder="Erbil, Baghdad, - Iraq">
                                        @error('location')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="plan">{{ __('Plan') }}</label>
                                        <select class="form-control @error('selectedPlanId') is-invalid @enderror"
                                                wire:model="selectedPlanId">
                                            <option value="">{{ __('Select Plan') }}</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}">
                                                    {{ $plan->bitrate }} kbps - {{ $plan->max_listeners }} listeners - ${{ number_format($plan->sell_price, 2) }}/mo
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('selectedPlanId')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    
                                </div>
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

    {{-- Update Radio Modal --}}

</div>
