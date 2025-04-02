<!-- Edit Promotion Modal -->
<div wire:ignore.self class="modal fade" id="updatePromotionEditModal" tabindex="-1" aria-labelledby="updatePromotionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg text-white">
        <div class="modal-content bg-dark">
            <form wire:submit.prevent="updatePromotion">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePromotionEditModalLabel">{{ __('Edit Campaign') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editingSponserText">{{ __('Sponser Text') }}</label>
                        <input type="text" class="form-control @error('editingSponserText') is-invalid @enderror"
                               wire:model="editingSponserText" placeholder="Enter promotion text" maxlength="50">
                        @error('editingSponserText')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label>{{ __('Target Gender') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetGenders" value="1" id="editGenderMale">
                            <label class="form-check-label" for="editGenderMale">{{ __('Male') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetGenders" value="2" id="editGenderFemale">
                            <label class="form-check-label" for="editGenderFemale">{{ __('Female') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetGenders" value="3" id="editGenderNotSay">
                            <label class="form-check-label" for="editGenderNotSay">{{ __('Rather Not Say') }}</label>
                        </div>
                        @error('editingTargetGenders') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label>{{ __('Target Age Range') }}</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="13-17" id="editAge13to17">
                            <label class="form-check-label" for="editAge13to17">{{ __('13-17') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="18-25" id="editAge18to25">
                            <label class="form-check-label" for="editAge18to25">{{ __('18-25') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="26-32" id="editAge26to32">
                            <label class="form-check-label" for="editAge26to32">{{ __('26-32') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="33-40" id="editAge33to40">
                            <label class="form-check-label" for="editAge33to40">{{ __('33-40') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="41-64" id="editAge41to64">
                            <label class="form-check-label" for="editAge41to64">{{ __('41-64') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="editingTargetAgeRanges" value="65+" id="editAge65plus">
                            <label class="form-check-label" for="editAge65plus">{{ __('65+') }}</label>
                        </div>
                        @error('editingTargetAgeRanges') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update Campaign') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
