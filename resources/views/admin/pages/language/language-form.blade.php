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
                    <hr class="bg-light">
                    <div class="row">
                        <!-- Image Upload with FilePond -->
                        <div class="col-12 col-md-8 col-lg-8 mt-3" wire:ignore>
                            <input type="file" id="addFilepond" 
                                    class="filepond @error('objectName') is-invalid @enderror @if(!$errors->has('objectName') && !empty($objectName)) is-valid @endif" 
                                    name="file" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectName')
                                <span class="text-danger">{{ __($message) }}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mt-3" wire:ignore>
                            <input type="file" id="addFilepond_sq" 
                                    class="filepond @error('objectNameSq') is-invalid @enderror @if(!$errors->has('objectNameSq') && !empty($objectNameSq)) is-valid @endif" 
                                    name="file_sq" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectNameSq')
                                <span class="text-danger">{{ __($message) }}</span>
                            @enderror
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
                    <hr class="bg-light">
                    <div class="row">
                        <!-- Image Upload with FilePond -->
                        <div class="col-12 col-md-8 col-lg-8 mt-3" wire:ignore>
                            <input type="file" id="updateFilepond" 
                                    class="filepond @error('objectName') is-invalid @enderror @if(!$errors->has('objectName') && !empty($objectName)) is-valid @endif" 
                                    name="file" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectName')
                                <span class="text-danger">{{ __($message) }}</span>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mt-3" wire:ignore>
                            <input type="file" id="updateFilepond_sq" 
                                    class="filepond @error('objectNameSq') is-invalid @enderror @if(!$errors->has('objectNameSq') && !empty($objectNameSq)) is-valid @endif" 
                                    name="file_sq" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectNameSq')
                                <span class="text-danger">{{ __($message) }}</span>
                            @enderror
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
@push('scripts')
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css" rel="stylesheet">

<!-- Include FilePond JS -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>

<script>
    // Register FilePond plugins
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginFileValidateSize,
        FilePondPluginImageTransform 
    );

    // Initialize FilePond for the Landscape Image in Add Modal
    const addPond = FilePond.create(document.querySelector('#addFilepond'), {
        fileParameterName: 'file',
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 100,
        imageCropAspectRatio: '5:2',
        imageCropAutoCropArea: 1,
        allowImageCrop: true,
        imageResizeTargetWidth: 250,
        imageResizeTargetHeight: 100,
        allowImageResize: true,
        imageResizeMode: 'cover',
        allowImageTransform: true,
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        imageTransformOutputQuality: 100,
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Initialize FilePond for the Square Image (1:1) in Add Modal
    const addPondSq = FilePond.create(document.querySelector('#addFilepond_sq'), {
        fileParameterName: 'file_sq',
        labelIdle: `Drag & Drop your square image or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 100,
        imageCropAspectRatio: '1:1',
        imageCropAutoCropArea: 1,
        allowImageCrop: true,
        imageResizeTargetWidth: 250,
        imageResizeTargetHeight: 250,
        allowImageResize: true,
        imageResizeMode: 'cover',
        allowImageTransform: true,
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        imageTransformOutputQuality: 100,
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Initialize FilePond for the Landscape Image in Update Modal
    const updatePond = FilePond.create(document.querySelector('#updateFilepond'), {
        fileParameterName: 'file',
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 100,
        imageCropAspectRatio: '5:2',
        imageCropAutoCropArea: 1,
        allowImageCrop: true,
        imageResizeTargetWidth: 250,
        imageResizeTargetHeight: 100,
        allowImageResize: true,
        imageResizeMode: 'cover',
        allowImageTransform: true,
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        imageTransformOutputQuality: 100,
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Initialize FilePond for the Square Image (1:1) in Update Modal
    const updatePondSq = FilePond.create(document.querySelector('#updateFilepond_sq'), {
        fileParameterName: 'file_sq',
        labelIdle: `Drag & Drop your square image or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 100,
        imageCropAspectRatio: '1:1',
        imageCropAutoCropArea: 1,
        allowImageCrop: true,
        imageResizeTargetWidth: 250,
        imageResizeTargetHeight: 250,
        allowImageResize: true,
        imageResizeMode: 'cover',
        allowImageTransform: true,
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Set up server options for all FilePond instances
    const serverOptions = {
        process: {
            url: '{{ route("filepond.lang.upload") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            onload: (response) => {
                const cleanedResponse = response.replace(/^"|"$/g, '');
                Livewire.emit('fileUploaded', cleanedResponse);
                return cleanedResponse;
            },
            onerror: (response) => {
                console.error(response);
            }
        },
        revert: (uniqueFileId, load, error) => {
            fetch('{{ route("filepond.lang.revert") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ file: uniqueFileId })
            })
            .then(response => response.json())
            .then(() => load())
            .catch(err => error('Error deleting file'));
        }
    };

    const serverOptionsSq = {
        process: {
            url: '{{ route("filepond.lang.upload") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            onload: (response) => {
                const cleanedResponse = response.replace(/^"|"$/g, '');
                Livewire.emit('fileUploadedSq', cleanedResponse);
                return cleanedResponse;
            },
            onerror: (response) => {
                console.error(response);
            }
        },
        revert: (uniqueFileId, load, error) => {
            fetch('{{ route("filepond.lang.revert") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ file: uniqueFileId })
            })
            .then(response => response.json())
            .then(() => load())
            .catch(err => error('Error deleting file'));
        }
    };


    addPond.setOptions({ server: serverOptions });
    addPondSq.setOptions({ server: serverOptionsSq });
    updatePond.setOptions({ server: serverOptions });
    updatePondSq.setOptions({ server: serverOptionsSq  });

    // Listen for events to preload images when updating (optional)
    window.addEventListener('setFilePondFile', event => {
        const fileUrl = event.detail.file;
        updatePond.removeFiles(); // Clear existing file(s)
        updatePond.addFile(fileUrl);
    });
    window.addEventListener('setFilePondFileSq', event => {
        const fileUrl = event.detail.file;
        updatePondSq.removeFiles(); // Clear existing file(s)
        updatePondSq.addFile(fileUrl);
    });

    window.addEventListener('clearFilePondInputs', () => {
    addPond.removeFiles();
    addPondSq.removeFiles();
    updatePond.removeFiles();
    updatePondSq.removeFiles();
});

</script>
@endpush
