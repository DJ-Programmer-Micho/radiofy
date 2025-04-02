<!-- Add Verification Modal -->
<div wire:ignore.self class="modal fade" id="addVerificationModal" tabindex="-1" aria-labelledby="addVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="saveVerification">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVerificationModalLabel">{{ __('Add Verification Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sellPriceMonthly">{{ __('Monthly Price') }}</label>
                            <input type="number" class="form-control @error('sellPriceMonthly') is-invalid @enderror" wire:model="sellPriceMonthly">
                            @error('sellPriceMonthly')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sellPriceYearly">{{ __('Yearly Price') }}</label>
                            <input type="number" class="form-control @error('sellPriceYearly') is-invalid @enderror" wire:model="sellPriceYearly">
                            @error('sellPriceYearly')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ribbon">{{ __('Ribbon') }}</label>
                            <select class="form-control @error('ribbon') is-invalid @enderror" wire:model="ribbon">
                                <option value="1">{{ __('Show') }}</option>
                                <option value="0">{{ __('Hide') }}</option>
                            </select>
                            @error('ribbon')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="ribbonText">{{ __('Ribbon Text') }}</label>
                            <input type="text" class="form-control @error('ribbonText') is-invalid @enderror" wire:model="ribbonText">
                            @error('ribbonText')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="features">{{ __('Features') }}</label>
                        <div>
                            @if ($features)
                                
                            @foreach($features as $index => $feature)
                            <div class="row mb-2">
                                    <div class="col-4">
                                        <label for="features.{{ $index }}.text">{{ __('Feature Text') }}</label>
                                        <input type="text" class="form-control @error('features.' . $index . '.text') is-invalid @enderror"
                                               placeholder="Enter feature" wire:model="features.{{ $index }}.text">
                                        @error('features.' . $index . '.text')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-2">
                                        <label for="features.{{ $index }}.check">{{ __('Icon') }}</label>
                                        <select class="form-control @error('features.' . $index . '.check') is-invalid @enderror"
                                                wire:model="features.{{ $index }}.check">
                                            <option value="1">{{ __('Check') }}</option>
                                            <option value="0">{{ __('Times') }}</option>
                                        </select>
                                        @error('features.' . $index . '.check')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger" wire:click="removeFeature({{ $index }})">
                                            X {{ __('Remove feature') }}
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            <button type="button" class="btn btn-success" wire:click="addFeature">
                                + {{ __('Add feature') }}
                            </button>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Verification Modal -->
<div wire:ignore.self class="modal fade" id="updateVerificationModal" tabindex="-1" aria-labelledby="updateVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="updateVerification">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateVerificationModalLabel">{{ __('Update Verification Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                                @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status">{{ __('Status') }}</label>
                                <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                                @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="sellPriceMonthly">{{ __('Monthly Price') }}</label>
                                <input type="number" class="form-control @error('sellPriceMonthly') is-invalid @enderror" wire:model="sellPriceMonthly">
                                @error('sellPriceMonthly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sellPriceYearly">{{ __('Yearly Price') }}</label>
                                <input type="number" class="form-control @error('sellPriceYearly') is-invalid @enderror" wire:model="sellPriceYearly">
                                @error('sellPriceYearly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="priority">{{ __('Priority') }}</label>
                                <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority">
                                @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ribbon">{{ __('Ribbon') }}</label>
                                <select class="form-control @error('ribbon') is-invalid @enderror" wire:model="ribbon">
                                    <option value="1">{{ __('Show') }}</option>
                                    <option value="0">{{ __('Hide') }}</option>
                                </select>
                                @error('ribbon')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ribbonText">{{ __('Ribbon Text') }}</label>
                                <input type="text" class="form-control @error('ribbonText') is-invalid @enderror" wire:model="ribbonText">
                                @error('ribbonText')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="features">{{ __('Features') }}</label>
                            <div>
                                @if ($features)
                                    
                                @foreach($features as $index => $feature)
                                <div class="row mb-2">
                                        <div class="col-4">
                                            <label for="features.{{ $index }}.text">{{ __('Feature Text') }}</label>
                                            <input type="text" class="form-control @error('features.' . $index . '.text') is-invalid @enderror"
                                                   placeholder="Enter feature" wire:model="features.{{ $index }}.text">
                                            @error('features.' . $index . '.text')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-2">
                                            <label for="features.{{ $index }}.check">{{ __('Icon') }}</label>
                                            <select class="form-control @error('features.' . $index . '.check') is-invalid @enderror"
                                                    wire:model="features.{{ $index }}.check">
                                                <option value="1">{{ __('Check') }}</option>
                                                <option value="0">{{ __('Times') }}</option>
                                            </select>
                                            @error('features.' . $index . '.check')<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger" wire:click="removeFeature({{ $index }})">
                                                X {{ __('Remove feature') }}
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                <button type="button" class="btn btn-success" wire:click="addFeature">
                                    + {{ __('Add feature') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Verification Modal -->
<div wire:ignore.self class="modal fade" id="deleteVerificationModal" tabindex="-1" aria-labelledby="deleteVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVerificationModalLabel">{{ __('Delete Verification Plan') }}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="resetInput" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="destroyVerification">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this verification plan?') }}</p>
                    <p>{{ __('Please type DELETE VERIFICATION below to confirm:') }}</p>
                    <input type="text" wire:model="verifyNameToDelete" class="form-control" placeholder="DELETE VERIFICATION" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="resetInput">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger" wire:disabled="verifyNameToDelete !== 'DELETE VERIFICATION'">
                        {{ __('Yes! Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
