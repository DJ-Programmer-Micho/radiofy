<div>
{{-- Add Radio Modal --}}
<div wire:ignore.self class="modal fade overflow-auto" id="addRadioModal" tabindex="-1" aria-labelledby="addRadioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl text-white mx-1 mx-auto">
        <div class="modal-content bg-dark">
            <form wire:submit.prevent="addRadio">
                <div class="modal-body">
                    <div class="modal-header mb-3">
                        <h5 class="modal-title" id="addRadioModalLabel">{{ __('Add Radio') }}</h5>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal"
                                aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <hr class="bg-white">
                    <div class="row">
                        {{-- Left Column --}}
                        <div class="col-12 col-md-6">
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
                            </div>
                        </div>
                        {{-- Right Column --}}
                        <div class="col-12 col-md-6">
                            <div class="filter-choices-input">
                                <div class="mb-3">
                                    <label for="streamUrl">{{__('Stream URL')}}</label>
                                    <input type="text" class="form-control @error('streamUrl') is-invalid @enderror @if(!$errors->has('streamUrl') && !empty($streamUrl)) is-valid @endif"
                                           wire:model="streamUrl" placeholder="https://---">
                                    @error('streamUrl')
                                        <div>
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary submitJs">{{ __('Add Radio') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Update Radio Modal --}}
<div wire:ignore.self class="modal fade overflow-auto" id="updateRadioModal" tabindex="-1" aria-labelledby="updateRadioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl text-white mx-1 mx-auto">
        <div class="modal-content bg-dark">
            <form wire:submit.prevent="updateRadio">
                <div class="modal-body">
                    <div class="modal-header mb-3">
                        <h5 class="modal-title" id="updateRadioModalLabel">{{ __('Update Radio') }}</h5>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <hr class="bg-white">
                    <div class="row">
                        {{-- Left Column --}}
                        <div class="col-12 col-md-6">
                            <div class="filter-choices-input">
                                <div class="mb-3">
                                    <label for="radioName">{{ __('Radio Name') }}</label>
                                    <input type="text" class="form-control @error('radioName') is-invalid @enderror @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                        wire:model="radioName" placeholder="Radio Name">
                                    @error('radioName')
                                        <div>
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- Right Column --}}
                        <div class="col-12 col-md-6">
                            <div class="filter-choices-input">
                                <div class="mb-3">
                                    <label for="streamUrl">{{ __('Stream URL') }}</label>
                                    <input type="text" class="form-control @error('streamUrl') is-invalid @enderror @if(!$errors->has('streamUrl') && !empty($streamUrl)) is-valid @endif"
                                        wire:model="streamUrl" placeholder="https://---">
                                    @error('streamUrl')
                                        <div>
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary submitJs">{{ __('Update Radio') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Radio Modal --}}
<div wire:ignore.self class="modal fade" id="deleteRadioModal" tabindex="-1" aria-labelledby="deleteRadioModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRadioModalLabel">{{ __('Delete Radio') }}</h5>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="removeRadio">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this radio?') }}</p>
                    <p>{{ __('Please type DELETE RADIO below to confirm:') }}</p>
                    <input type="text" wire:model="radioNameToDelete" class="form-control" placeholder="DELETE RADIO" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger" wire:disabled="radioNameToDelete !== 'DELETE RADIO'">{{ __('Yes, Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
