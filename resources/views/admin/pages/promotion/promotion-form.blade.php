<!-- Add Verification Modal -->
<div wire:ignore.self class="modal fade" id="addPromotionModal" tabindex="-1" aria-labelledby="addPromotionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="savePromotion">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPromotionModalLabel">{{ __('Add Promotion Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="subName">{{ __('Sub Name') }}</label>
                            <input type="text" class="form-control @error('subName') is-invalid @enderror" wire:model="subName">
                            @error('subName')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
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
                            <label for="sell">{{ __('Price ($)') }}</label>
                            <input type="number" class="form-control @error('sell') is-invalid @enderror" wire:model="sell">
                            @error('sell')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="duration">{{ __('Days') }}</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" wire:model="duration">
                            @error('duration')<span class="text-danger">{{ __($message) }}</span>@enderror
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
                                        <label for="features.{{ $index }}.key">{{ __('Feature Title') }}</label>
                                        <input type="text" class="form-control @error('features.' . $index . '.key') is-invalid @enderror"
                                               placeholder="Enter feature" wire:model="features.{{ $index }}.key">
                                        @error('features.' . $index . '.key')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-4">
                                        <label for="features.{{ $index }}.value">{{ __('Feature Information') }}</label>
                                        <input type="text" class="form-control @error('features.' . $index . '.value') is-invalid @enderror"
                                               placeholder="Enter feature" wire:model="features.{{ $index }}.value">
                                        @error('features.' . $index . '.value')<span class="text-danger">{{ $message }}</span>@enderror
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Verification Modal -->
<div wire:ignore.self class="modal fade" id="updatePromotionModal" tabindex="-1" aria-labelledby="updatePromotionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="updatePromotion">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePromotionModalLabel">{{ __('Update Promotion Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="name">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                            @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="subName">{{ __('Sub Name') }}</label>
                            <input type="text" class="form-control @error('subName') is-invalid @enderror" wire:model="subName">
                            @error('subName')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-4">
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
                            <label for="sell">{{ __('Price ($)') }}</label>
                            <input type="number" class="form-control @error('sell') is-invalid @enderror" wire:model="sell">
                            @error('sell')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="duration">{{ __('Days') }}</label>
                            <input type="number" class="form-control @error('duration') is-invalid @enderror" wire:model="duration">
                            @error('duration')<span class="text-danger">{{ __($message) }}</span>@enderror
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
                                        <label for="features.{{ $index }}.key">{{ __('Feature Title') }}</label>
                                        <input type="text" class="form-control @error('features.' . $index . '.key') is-invalid @enderror"
                                               placeholder="Enter feature" wire:model="features.{{ $index }}.key">
                                        @error('features.' . $index . '.key')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-4">
                                        <label for="features.{{ $index }}.value">{{ __('Feature Information') }}</label>
                                        <input type="text" class="form-control @error('features.' . $index . '.value') is-invalid @enderror"
                                               placeholder="Enter feature" wire:model="features.{{ $index }}.value">
                                        @error('features.' . $index . '.value')<span class="text-danger">{{ $message }}</span>@enderror
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Verification Modal -->
<div wire:ignore.self class="modal fade" id="deletePromotionModal" tabindex="-1" aria-labelledby="deletePromotionModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePromotionModalLabel">{{ __('Delete Promotion Plan') }}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="destroyPromotion">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this PROMOTION plan?') }}</p>
                    <p>{{ __('Please type DELETE PROMOTION below to confirm:') }}</p>
                    <input type="text" wire:model="promoNameToDelete" class="form-control" placeholder="DELETE PROMOTION" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger" wire:disabled="promoNameToDelete !== 'DELETE PROMOTION'">
                        {{ __('Yes! Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
