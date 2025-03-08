<!-- Add Genre Modal -->
<div wire:ignore.self class="modal fade" id="addGenreModal" tabindex="-1" aria-labelledby="addGenreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="saveGenre">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGenreModalLabel">{{ __('Add Genre') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Priority -->
                        <div class="col-md-4">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-4">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Image Upload with FilePond -->
                        <div class="col-md-4" wire:ignore>
                            <input type="file" id="addFilepond" class="filepond" name="file" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectName')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        @foreach($filteredLocales as $locale)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genre_{{ $locale }}">{{ __('Genre') }} ({{ strtoupper($locale) }})</label>
                                <input type="text" class="form-control @error('genres.' . $locale) is-invalid @enderror" wire:model="genres.{{ $locale }}" placeholder="{{ __('Enter genre name') }}">
                                @error('genres.' . $locale)
                                    <span class="text-danger">{{ __($message) }}</span>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Genre') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Genre Modal -->
<div wire:ignore.self class="modal fade" id="updateGenreModal" tabindex="-1" aria-labelledby="updateGenreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark text-white">
            <form wire:submit.prevent="updateGenre">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateGenreModalLabel">{{ __('Update Genre') }}</h5>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="resetInput">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <!-- Priority -->
                        <div class="col-md-4">
                            <label for="priority">{{ __('Priority') }}</label>
                            <input type="number" class="form-control @error('priority') is-invalid @enderror" wire:model="priority" placeholder="Priority">
                            @error('priority')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-4">
                            <label for="status">{{ __('Status') }}</label>
                            <select class="form-control @error('status') is-invalid @enderror" wire:model="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Non-active') }}</option>
                            </select>
                            @error('status')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                        <!-- Image Upload with FilePond -->
                        <div class="col-md-4" wire:ignore>
                            <input type="file" id="updateFilepond" class="filepond" name="file" accept="image/png, image/jpeg, image/jpg, image/svg"/>
                            @error('objectName')<span class="text-danger">{{ __($message) }}</span>@enderror
                        </div>
                    </div>
                    <hr class="bg-light">
                    <div class="row">
                        @foreach($filteredLocales as $locale)
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genre_{{ $locale }}">{{ __('Genre') }} ({{ strtoupper($locale) }})</label>
                                <input type="text" class="form-control @error('genres.' . $locale) is-invalid @enderror" wire:model="genres.{{ $locale }}" placeholder="{{ __('Enter genre name') }}">
                                @error('genres.' . $locale)
                                    <span class="text-danger">{{ __($message) }}</span>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetInput">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Update Genre') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="deleteGenreModal" tabindex="-1" aria-labelledby="deleteGenreModalLabel" aria-hidden="true">
    <div class="modal-dialog text-white">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteGenreModalLabel">{{ __('Delete Genre') }}</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="closeModal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form wire:submit.prevent="destroyGenre">
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this Genre?') }}</p>
                    <p>{{ __('Please type DELETE GENRE below to confirm:') }}</p>
                    <input type="text" wire:model="genreNameToDelete" class="form-control" placeholder="DELETE GENRE" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger" wire:disabled="genreNameToDelete !== 'DELETE GENRE'">
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

    // Initialize FilePond for Add Modal with cropping enabled
    const addPond = FilePond.create(document.querySelector('#addFilepond'), {
        fileParameterName: 'file',
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 150,
        imageCropAspectRatio: '1:1',
        imageCropAutoCropArea: 1,
        allowImageCrop: true, // Enable cropping
        imageResizeTargetWidth: 500,
        imageResizeTargetHeight: 500,
        allowImageResize: true,
        imageResizeMode: 'cover',
        allowImageTransform: true,
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        imageTransformOutputQuality: 90,
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Initialize FilePond for Update Modal
    const updatePond = FilePond.create(document.querySelector('#updateFilepond'), {
        fileParameterName: 'file',
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 150,
        imageCropAspectRatio: '1:1',
        imageTransformOutputQuality: 90,
        imageCropAutoCropArea: 1,
        allowImageCrop: true, // Enable cropping
        imageResizeTargetWidth: 500,
        imageResizeTargetHeight: 500,
        allowImageResize: true,
        allowImageTransform: true,
        imageResizeMode: 'cover',
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        maxFileSize: '2MB',
        labelMaxFileSizeExceeded: 'File is too large',
        labelMaxFileSize: 'Maximum file size is {filesize}'
    });

    // Set up server options for both instances
    const serverOptions = {
        process: {
            url: '{{ route("filepond.upload") }}',
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
            fetch('{{ route("filepond.revert") }}', {
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
    updatePond.setOptions({ server: serverOptions });

    // Listen for event to preload the update image
    window.addEventListener('setFilePondFile', event => {
        const fileUrl = event.detail.file;
        updatePond.removeFiles(); // Clear existing file(s)
        updatePond.addFile(fileUrl);
    });
</script>
@endpush

