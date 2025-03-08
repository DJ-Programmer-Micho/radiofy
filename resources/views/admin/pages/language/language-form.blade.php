<!-- Add Language Modal -->
<div wire:ignore.self class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="saveLanguage">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLanguageModalLabel">{{ __('Add Language') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Priority -->
                        <div class="col-md-6">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">{{ __('CODE') }}</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model="code" placeholder="{{ __('Enter Language Code') }}">
                                @error('code')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="{{ __('Enter Language Name') }}">
                                @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Language') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Language Modal -->
<div wire:ignore.self class="modal fade" id="updateLanguageModal" tabindex="-1" aria-labelledby="updateLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="updateLanguage">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateLanguageModalLabel">{{ __('Update Language') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Priority -->
                        <div class="col-md-6">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code">{{ __('CODE') }}</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" wire:model="code" placeholder="{{ __('Enter Language Code') }}">
                                @error('code')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="{{ __('Enter Language Name') }}">
                                @error('name')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update Language') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="deleteLanguageModal" tabindex="-1" aria-labelledby="deleteLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLanguageModalLabel">{{ __('Delete Language') }}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="destroyLanguage">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this Language?') }}</p>
                    <p>{{ __('Please type DELETE LANGUAGE below to confirm:') }}</p>
                    <input type="text" wire:model="languageNameToDelete" class="form-control" placeholder="DELETE LANGUAGE" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger" wire:disabled="languageNameToDelete !== 'DELETE LANGUAGE'">
                        {{ __('Yes! Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
