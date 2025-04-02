<!-- Add Plan Modal -->
<div wire:ignore.self class="modal fade" id="addPlanModal" tabindex="-1" aria-labelledby="addPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="savePlan">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPlanModalLabel">{{ __('Add Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Status -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="support">{{ __('Support') }}</label>
                            <select class="form-control @error('support') is-invalid @enderror" wire:model="support">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="ribbon">{{ __('Ribbon') }}</label>
                            <select class="form-control @error('ribbon') is-invalid @enderror" wire:model="ribbon">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="ribbonText">{{ __('Ribbon Text') }}</label>
                                <input type="text" class="form-control @error('ribbonText') is-invalid @enderror" wire:model="ribbonText" placeholder="{{ __('Popular') }}">
                                @error('ribbonText')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <!-- Priority -->
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="bitrate">{{ __('Bitrate (Kbps)') }}</label>
                                <input type="number" class="form-control @error('bitrate') is-invalid @enderror" wire:model="bitrate" placeholder="{{ __('196') }}">
                                @error('bitrate')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="maxListeners">{{ __('Max Listeners') }}</label>
                                <input type="number" class="form-control @error('maxListeners') is-invalid @enderror" wire:model="maxListeners" placeholder="{{ __('100') }}">
                                @error('maxListeners')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="sellPriceMonthly">{{ __('Sell Price Monthly ($)') }}</label>
                                <input type="number" class="form-control @error('sellPriceMonthly') is-invalid @enderror" wire:model="sellPriceMonthly" placeholder="{{ __('50') }}">
                                @error('sellPriceMonthly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="sellPriceYearly">{{ __('Sell Price Yearly ($)') }}</label>
                                <input type="number" class="form-control @error('sellPriceYearly') is-invalid @enderror" wire:model="sellPriceYearly" placeholder="{{ __('600') }}">
                                @error('sellPriceYearly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Plan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Plan Modal -->
<div wire:ignore.self class="modal fade" id="updatePlanModal" tabindex="-1" aria-labelledby="updatePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="updatePlan">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePlanModalLabel">{{ __('Update Plan') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Status -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="support">{{ __('Support') }}</label>
                            <select class="form-control @error('support') is-invalid @enderror" wire:model="support">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="ribbon">{{ __('Ribbon') }}</label>
                            <select class="form-control @error('ribbon') is-invalid @enderror" wire:model="ribbon">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="ribbonText">{{ __('Ribbon Text') }}</label>
                                <input type="text" class="form-control @error('ribbonText') is-invalid @enderror" wire:model="ribbonText" placeholder="{{ __('Popular') }}">
                                @error('ribbonText')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <!-- Priority -->
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="bitrate">{{ __('Bitrate (Kbps)') }}</label>
                                <input type="number" class="form-control @error('bitrate') is-invalid @enderror" wire:model="bitrate" placeholder="{{ __('196') }}">
                                @error('bitrate')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="maxListeners">{{ __('Max Listeners') }}</label>
                                <input type="number" class="form-control @error('maxListeners') is-invalid @enderror" wire:model="maxListeners" placeholder="{{ __('100') }}">
                                @error('maxListeners')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="sellPriceMonthly">{{ __('Sell Price Monthly ($)') }}</label>
                                <input type="number" class="form-control @error('sellPriceMonthly') is-invalid @enderror" wire:model="sellPriceMonthly" placeholder="{{ __('50') }}">
                                @error('sellPriceMonthly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="mb-3">
                                <label for="sellPriceYearly">{{ __('Sell Price Yearly ($)') }}</label>
                                <input type="number" class="form-control @error('sellPriceYearly') is-invalid @enderror" wire:model="sellPriceYearly" placeholder="{{ __('600') }}">
                                @error('sellPriceYearly')<span class="text-danger">{{ __($message) }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update Plan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="deletePlanModal" tabindex="-1" aria-labelledby="deletePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlanModalLabel">{{ __('Delete Plan') }}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="destroyPlan">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this Plan?') }}</p>
                    <p>{{ __('Please type DELETE PLAN below to confirm:') }}</p>
                    <input type="text" wire:model="planNameToDelete" class="form-control" placeholder="DELETE PLAN" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger" wire:disabled="planNameToDelete !== 'DELETE PLAN'">
                        {{ __('Yes! Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
