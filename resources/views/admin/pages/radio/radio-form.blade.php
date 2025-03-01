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
                                        <label for="radioName">Radio Name</label>
                                        <input type="text" class="form-control @error('radioName') is-invalid @enderror @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                               wire:model="radioName" placeholder="Radio Name">
                                        @error('radioName')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="location">Radio Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror @if(!$errors->has('location') && !empty($location)) is-valid @endif"
                                               wire:model="location" placeholder="Erbil, Baghdad, - Iraq">
                                        @error('location')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverAdmin">Server Admin</label>
                                        <input type="email" class="form-control @error('serverAdmin') is-invalid @enderror @if(!$errors->has('serverAdmin') && !empty($serverAdmin)) is-valid @endif"
                                               wire:model="serverAdmin" placeholder="name@radioname.com">
                                        @error('serverAdmin')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverPassword">Server Password</label>
                                        <input type="password" class="form-control @error('serverPassword') is-invalid @enderror @if(!$errors->has('serverPassword') && !empty($serverPassword)) is-valid @endif"
                                               wire:model="serverPassword" placeholder="********">
                                        @error('serverPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="maxListeners">Max Listeners</label>
                                        <input type="number" class="form-control @error('maxListeners') is-invalid @enderror @if(!$errors->has('maxListeners') && !empty($maxListeners)) is-valid @endif"
                                               wire:model="maxListeners" placeholder="100">
                                        @error('maxListeners')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="port">Port Number</label>
                                        <input type="number" class="form-control @error('port') is-invalid @enderror @if(!$errors->has('port') && !empty($port)) is-valid @endif"
                                               wire:model="port" placeholder="8000">
                                        @error('port')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="bindAddress">Bind Address</label>
                                        <input type="text" class="form-control @error('bindAddress') is-invalid @enderror @if(!$errors->has('bindAddress') && !empty($bindAddress)) is-valid @endif"
                                               wire:model="bindAddress" placeholder="0.0.0.0">
                                        @error('bindAddress')
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
                                        <label for="burstSize">{{ __('Burst Size') }}</label>
                                        <select class="form-control @error('burstSize') is-invalid @enderror @if(!$errors->has('burstSize') && $burstSize !== null) is-valid @endif"
                                                wire:model="burstSize">
                                            <option value="">{{ __('Select Burst Size') }}</option>
                                            <option value="65536">{{ __('64 KB') }}</option>
                                            <option value="98304">{{ __('96 KB') }}</option>
                                            <option value="131072">{{ __('128 KB') }}</option>
                                            <option value="262144">{{ __('256 KB') }}</option>
                                            <option value="327680">{{ __('320 KB') }}</option>
                                        </select>
                                        @error('burstSize')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="sourcePassword">Source Password</label>
                                        <input type="password" class="form-control @error('sourcePassword') is-invalid @enderror @if(!$errors->has('sourcePassword') && !empty($sourcePassword)) is-valid @endif"
                                               wire:model="sourcePassword" placeholder="********">
                                        @error('sourcePassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="relayPassword">Relay Password</label>
                                        <input type="password" class="form-control @error('relayPassword') is-invalid @enderror @if(!$errors->has('relayPassword') && !empty($relayPassword)) is-valid @endif"
                                               wire:model="relayPassword" placeholder="********">
                                        @error('relayPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminPassword">Admin Password</label>
                                        <input type="password" class="form-control @error('adminPassword') is-invalid @enderror @if(!$errors->has('adminPassword') && !empty($adminPassword)) is-valid @endif"
                                               wire:model="adminPassword" placeholder="********">
                                        @error('adminPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="fallbackMount">Fallback Mount</label>
                                        <input type="text" class="form-control @error('fallbackMount') is-invalid @enderror @if(!$errors->has('fallbackMount') && !empty($fallbackMount)) is-valid @endif"
                                               wire:model="fallbackMount" placeholder="/fallback.ogg">
                                        @error('fallbackMount')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="genre">Genre</label>
                                        <input type="text" class="form-control @error('genre') is-invalid @enderror @if(!$errors->has('genre') && !empty($genre)) is-valid @endif"
                                               wire:model="genre" placeholder="Rock, Pop, etc.">
                                        @error('genre')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select class="form-control @error('status') is-invalid @enderror @if(!$errors->has('status') && $status !== null) is-valid @endif"
                                                wire:model="status">
                                            <option value="">{{ __('Select Status') }}</option>
                                            <option value="1" @if($status==1) selected @endif>{{ __('Active') }}</option>
                                            <option value="0" @if($status==0) selected @endif>{{ __('Non-Active') }}</option>
                                        </select>
                                        @error('status')
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
    <div wire:ignore.self class="modal fade overflow-auto" id="updateRadioModal" tabindex="-1"
         aria-labelledby="updateRadioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-white mx-1 mx-lg-auto">
            <div class="modal-content bg-dark">
                <form wire:submit.prevent="updateRadio">
                    <div class="modal-body">
                        <div class="modal-header mb-3">
                            <h5 class="modal-title" id="updateRadioModalLabel">{{ __('Edit Radio') }}</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal"
                                    aria-label="Close"><i class="fas fa-times"></i></button>
                        </div>
                        <hr class="bg-white">
                        <div class="row">
                            {{-- Same form fields as above, pre-filled for editing --}}
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="radioName">Radio Name</label>
                                        <input type="text" class="form-control @error('radioName') is-invalid @enderror @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                               wire:model="radioName" placeholder="Radio Name">
                                        @error('radioName')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="location">Radio Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror @if(!$errors->has('location') && !empty($location)) is-valid @endif"
                                               wire:model="location" placeholder="Erbil, Baghdad, - Iraq">
                                        @error('location')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverAdmin">Server Admin</label>
                                        <input type="email" class="form-control @error('serverAdmin') is-invalid @enderror @if(!$errors->has('serverAdmin') && !empty($serverAdmin)) is-valid @endif"
                                               wire:model="serverAdmin" placeholder="name@radioname.com">
                                        @error('serverAdmin')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="serverPassword">Server Password</label>
                                        <input type="password" class="form-control @error('serverPassword') is-invalid @enderror @if(!$errors->has('serverPassword') && !empty($serverPassword)) is-valid @endif"
                                               wire:model="serverPassword" placeholder="********">
                                        @error('serverPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="maxListeners">Max Listeners</label>
                                        <input type="number" class="form-control @error('maxListeners') is-invalid @enderror @if(!$errors->has('maxListeners') && !empty($maxListeners)) is-valid @endif"
                                               wire:model="maxListeners" placeholder="100">
                                        @error('maxListeners')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="port">Port Number</label>
                                        <input type="number" class="form-control @error('port') is-invalid @enderror @if(!$errors->has('port') && !empty($port)) is-valid @endif"
                                               wire:model="port" placeholder="8000">
                                        @error('port')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="bindAddress">Bind Address</label>
                                        <input type="text" class="form-control @error('bindAddress') is-invalid @enderror @if(!$errors->has('bindAddress') && !empty($bindAddress)) is-valid @endif"
                                               wire:model="bindAddress" placeholder="0.0.0.0">
                                        @error('bindAddress')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="filter-choices-input">
                                    <div class="mb-3">
                                        <label for="burstSize">{{ __('Burst Size') }}</label>
                                        <select class="form-control @error('burstSize') is-invalid @enderror @if(!$errors->has('burstSize') && $burstSize !== null) is-valid @endif"
                                                wire:model="burstSize">
                                            <option value="">{{ __('Select Burst Size') }}</option>
                                            <option value="65536">{{ __('64 KB') }}</option>
                                            <option value="98304">{{ __('96 KB') }}</option>
                                            <option value="131072">{{ __('128 KB') }}</option>
                                            <option value="262144">{{ __('256 KB') }}</option>
                                            <option value="327680">{{ __('320 KB') }}</option>
                                        </select>
                                        @error('burstSize')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="sourcePassword">Source Password</label>
                                        <input type="password" class="form-control @error('sourcePassword') is-invalid @enderror @if(!$errors->has('sourcePassword') && !empty($sourcePassword)) is-valid @endif"
                                               wire:model="sourcePassword" placeholder="********">
                                        @error('sourcePassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="relayPassword">Relay Password</label>
                                        <input type="password" class="form-control @error('relayPassword') is-invalid @enderror @if(!$errors->has('relayPassword') && !empty($relayPassword)) is-valid @endif"
                                               wire:model="relayPassword" placeholder="********">
                                        @error('relayPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="adminPassword">Admin Password</label>
                                        <input type="password" class="form-control @error('adminPassword') is-invalid @enderror @if(!$errors->has('adminPassword') && !empty($adminPassword)) is-valid @endif"
                                               wire:model="adminPassword" placeholder="********">
                                        @error('adminPassword')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="fallbackMount">Fallback Mount</label>
                                        <input type="text" class="form-control @error('fallbackMount') is-invalid @enderror @if(!$errors->has('fallbackMount') && !empty($fallbackMount)) is-valid @endif"
                                               wire:model="fallbackMount" placeholder="/fallback.ogg">
                                        @error('fallbackMount')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="genre">Genre</label>
                                        <input type="text" class="form-control @error('genre') is-invalid @enderror @if(!$errors->has('genre') && !empty($genre)) is-valid @endif"
                                               wire:model="genre" placeholder="Rock, Pop, etc.">
                                        @error('genre')
                                            <div>
                                                <span class="text-danger">{{ __($message) }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select class="form-control @error('status') is-invalid @enderror @if(!$errors->has('status') && $status !== null) is-valid @endif"
                                                wire:model="status">
                                            <option value="">{{ __('Select Status') }}</option>
                                            <option value="1" @if($status==1) selected @endif>{{ __('Active') }}</option>
                                            <option value="0" @if($status==0) selected @endif>{{ __('Non-Active') }}</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ __($message) }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary submitJs">{{ __('Update Radio') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="deleteRadioModal" tabindex="-1" aria-labelledby="deleteRadioModalLabel"
    aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRadioModalLabel">{{__('Delete')}}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal"
                    aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <form wire:submit.prevent="destroyRadio">
                <div class="modal-body @if(app()->getLocale() != 'en') ar-shift @endif">
                    <p>{{ __('Are you sure you want to delete this Radio?') }}</p>
                    <p>{{ __('Please enter the in below to confirm:')}}</p>
                    {{-- <p>{{$showTextTemp}}</p> --}}
                    <input type="text" wire:model="RadioNameToDelete" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal"
                        data-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="submit" class="btn btn-danger"
                        wire:disabled="!confirmDelete || RadioNameToDelete !== $showTextTemp">
                        {{ __('Yes! Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
