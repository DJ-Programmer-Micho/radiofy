<div class="page-content">
    <style>
        .m-index{
            z-index: 9999;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Radios') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Radios') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="updateConfig">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header" wire:ignore>
                            <h6>Radio Information</h6>
                        </div>
                        <div class="card-body" >
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="radio_name">{{__('Radio Name')}}</label>
                                        <input 
                                            type="text" 
                                            class="form-control 
                                            {{-- @if($locale != 'en') ar-shift @endif  --}}
                                            @error('radioName') is-invalid @enderror
                                            @if(!$errors->has('radioName') && !empty($radioName)) is-valid @endif"
                                            wire:model.debounce.500ms="radioName" 
                                            wire:input="updateRadioNamePreview"
                                            placeholder="{{__('Radio FM')}}"
                                        >
                                        @error('radioName')
                                        <div class="@if(app()->getLocale() != 'en') ar-shift @endif">
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-3" wire:ignore>
                                        <label for="selectedGenres">{{__('Genre')}}</label>
                                        <select class="js-genre-multiple form-select" name="selectedGenres[]" multiple="multiple" wire:model="selectedGenres">
                                            @foreach($genresOptions as $genre)
                                                <option value="{{ $genre->id }}">{{ $genre->genreTranslation->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('genre')
                                        <div class="@if(app()->getLocale() != 'en') ar-shift @endif">
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-3" wire:ignore>
                                        <label for="selectedLanguages">{{__('Language')}}</label>
                                        <select class="js-language-multiple form-select" name="selectedLanguages[]" multiple="multiple" wire:model="selectedLanguages">
                                            @foreach($languagesOptions as $lang)
                                                <option value="{{ $lang->id }}">{{ $lang->code .' - '. $lang->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedLanguages')
                                        <div class="@if(app()->getLocale() != 'en') ar-shift @endif">
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-3">
                                        <label for="location">{{__('Location')}}</label>
                                        <input 
                                            type="text" 
                                            class="form-control 
                                            {{-- @if($locale != 'en') ar-shift @endif  --}}
                                            @error('location') is-invalid @enderror
                                            @if(!$errors->has('location') && !empty($location)) is-valid @endif"
                                            wire:model="location" 
                                            placeholder="{{__('Iraq - Erbil')}}"
                                        >
                                        @error('location')
                                        <div class="@if(app()->getLocale() != 'en') ar-shift @endif">
                                            <span class="text-danger">{{ __($message) }}</span>
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="description">{{ __('description') }}</label>
                                <div class="form-floating">
                                    <textarea 
                                    class="form-control 
                                    {{-- @if($locale != 'en') ar-shift @endif  --}}
                                    @error('description') is-invalid @enderror
                                    @if(!$errors->has('description') && !empty($description)) is-valid @endif" 
                                    placeholder="{{__('Description')}}"  
                                    style="height: 100px;"
                                    wire:model="description"></textarea>
                                    <label for="floatingTextarea2">{{__('Description')}}</label>
                                  </div>
                                    @error('contents')
                                    <div class="@if(app()->getLocale() != 'en') ar-shift @endif">
                                        <span class="text-danger">{{ __($message) }}</span>
                                    </div>
                                    @enderror
                            </div>

                        </div>
                        <!-- end card body -->
                    </div>
                    <div class="card">
                        <div class="card-header" wire:ignore>
                            <h5 class="card-title mb-0">{{__('Image Uploader')}}</h5>
                        </div>
                        <!-- end card header -->
                        <div class="card-body" wire:ignore>
                            <div class="row">
                                <div class="col-md-4" wire:ignore>
                                    <input type="file" id="logoFilepond" class="filepond" name="file" accept="image/png, image/jpeg, image/jpg"/>
                                    @error('objectName')<span class="text-danger">{{ __($message) }}</span>@enderror
                                    <small class="text-info">Image Size: 1000px X 1000px</small>
                                </div>
                                <div class="col-md-8" wire:ignore>
                                    <input type="file" id="bannarFilepond" class="filepond" name="file" accept="image/png, image/jpeg, image/jpg"/>
                                    @error('objectName')<span class="text-danger">{{ __($message) }}</span>@enderror
                                    <small class="text-info">Phone Size: 450px X 300px</small><br>
                                    <small class="text-info">Computer Size: 2160px X 300px</small>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>

                    <div class="card">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{__('Meta Keywords')}}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-2">{{__('Add a short Keywords for the product (Multi Langage if needed)')}}</p>
                                    <textarea 
                                        class="form-control" 
                                        placeholder="Must enter a minimum of 100 characters" 
                                        rows="3" 
                                        wire:model.lazy="meta_keywords"
                                    ></textarea>
                                    <small class="text-info">mradiofy, m radio iraq, راديو الغد, اغاني</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ __('Social Media Links') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-facebook"></i></span>
                                        <input type="text" class="form-control" placeholder="Facebook URL" wire:model.defer="social_media.facebook">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                                        <input type="text" class="form-control" placeholder="Instagram URL" wire:model.defer="social_media.instagram">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-snapchat"></i></span>
                                        <input type="text" class="form-control" placeholder="Snapchat URL" wire:model.defer="social_media.snapchat">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-tiktok"></i></span>
                                        <input type="text" class="form-control" placeholder="TikTok URL" wire:model.defer="social_media.tiktok">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-youtube"></i></span>
                                        <input type="text" class="form-control" placeholder="YouTube URL" wire:model.defer="social_media.youtube">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-linkedin"></i></span>
                                        <input type="text" class="form-control" placeholder="LinkedIn URL" wire:model.defer="social_media.linkedin">
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fa-brands fa-x-twitter"></i></span>
                                        <input type="text" class="form-control" placeholder="Twitter URL" wire:model.defer="social_media.twitter">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                    </div>


                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <a type="button" href="{{ route('subs-radios') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-danger submitJs">{{ __('Publish') }}</button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{__('Preview')}}</h5>
                        </div>
                        <div class="card-body">
                            <h6>{{ env('APP_URL').'/'.$radioNameSlug }}</h6>
                            <div class="position-relative banner">
                                @if($banner)
                                    <img src="{{ asset('storage/' . $banner) }}" class="img-fluid" alt="Banner Image">
                                @else
                                    <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_hed_2023201117005030471039.jpeg" class="img-fluid" alt="Default Banner">
                                @endif
                              <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.4);"></div> 
                                <div class="position-absolute bottom-0 start-0 p-3 text-white">
                                    <div class="d-flex">
                                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="25" height="25">&nbsp;<h6 class="my-auto">Radio Verified</h6>
                                    </div>
                                    <h1>{{ $radioName }}</h1>
                                    <p class="m-0">{{ number_format($highestPeakListeners) }} Height Listeners</p>
                                </div>
                                <div class="position-absolute top-0 end-0 p-2">
                                    @foreach($selectedLanguages as $languageId)
                                        @php 
                                            $language = $languagesOptions->firstWhere('id', $languageId);
                                        @endphp
                                        @if($language)
                                            <span class="badge bg-secondary">{{ strtoupper($language->code) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="position-absolute bottom-0 end-0 p-3">
                                    @if(!empty($social_media['facebook']))
                                        <a href="{{ $social_media['facebook'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['instagram']))
                                        <a href="{{ $social_media['instagram'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['tiktok']))
                                        <a href="{{ $social_media['tiktok'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-tiktok"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['snapchat']))
                                        <a href="{{ $social_media['snapchat'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-snapchat"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['twitter']))
                                        <a href="{{ $social_media['twitter'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-x-twitter"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['linkedin']))
                                        <a href="{{ $social_media['linkedin'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                    @endif
                                    @if(!empty($social_media['youtube']))
                                        <a href="{{ $social_media['youtube'] }}" class="text-white me-2" target="_blank">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    @endif
                                </div>                            
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div style="width: 150px; height: 150px; overflow: hidden; margin-right: 20px;">
                                    @if($logo)
                                        <img src="{{ asset('storage/' . $logo) }}" class="img-fluid" alt="Logo">
                                    @else
                                        <img src="https://d7tztcuqve7v9.cloudfront.net/rest/yamiyam/setting/yamiyam_logo_2023181117003269916864.jpeg" class="img-fluid" alt="Default Logo">
                                    @endif
                                </div>
                                <div>
                                    <div class="d-flex"> 
                                        <img src="{{ asset('/assets/img/verify2.png') }}" alt="" width="25" height="25">
                                        &nbsp;<h6 class="my-auto">Radio Verified</h6>
                                    </div>
                                    <h1>{{ $radioName }}</h1>
                                    <p>{{ number_format($highestPeakListeners) }} Height Listeners</p>
                                    <div style="color: var(--vz-heading-color)">
                                        @if(!empty($social_media['facebook']))
                                            <a href="{{ $social_media['facebook'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-facebook"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['instagram']))
                                            <a href="{{ $social_media['instagram'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['tiktok']))
                                            <a href="{{ $social_media['tiktok'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-tiktok"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['snapchat']))
                                            <a href="{{ $social_media['snapchat'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-snapchat"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['twitter']))
                                            <a href="{{ $social_media['twitter'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-x-twitter"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['linkedin']))
                                            <a href="{{ $social_media['linkedin'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                        @endif
                                        @if(!empty($social_media['youtube']))
                                            <a href="{{ $social_media['youtube'] }}" class="me-2" target="_blank">
                                                <i class="fab fa-youtube"></i>
                                            </a>
                                        @endif
                                    </div>
                                
                                </div>
                              </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@push('radio_script')
<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>


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

<link rel="stylesheet" href="{{asset('dashboard/css/select2.css')}}">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

    // Server options for logo upload: store in radio/logo
    const logoServerOptions = {
        process: {
            url: '{{ route("filepond.upload.logo.temp") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            onload: (response) => {
                const cleanedResponse = response.replace(/^"|"$/g, '');
                Livewire.emit('logoUploaded', cleanedResponse);
                return cleanedResponse;
            },
            onerror: (response) => { console.error(response); }
        },
        revert: (uniqueFileId, load, error) => {
            fetch('{{ route("filepond.revert.logo.temp") }}', {
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

    // Server options for temporary banner upload
    const bannerServerOptions = {
        process: {
            url: '{{ route("filepond.upload.banner.temp") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            onload: (response) => {
                const cleanedResponse = response.replace(/^"|"$/g, '');
                Livewire.emit('bannerUploaded', cleanedResponse);
                return cleanedResponse;
            },
            onerror: (response) => { console.error(response); }
        },
        revert: (uniqueFileId, load, error) => {
            fetch('{{ route("filepond.revert.banner.temp") }}', {
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

    // Initialize FilePond for logo upload
    const logoPond = FilePond.create(document.querySelector('#logoFilepond'), {
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
    logoPond.setOptions({ server: logoServerOptions });

    // Initialize FilePond for banner upload
    const bannerPond = FilePond.create(document.querySelector('#bannarFilepond'), {
        fileParameterName: 'file',
        labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
        imagePreviewHeight: 150,
        imageCropAspectRatio: '3.6:1',
        imageCropAutoCropArea: 1,
        allowImageCrop: true, // Enable cropping
        imageResizeTargetWidth: 2160,
        imageResizeTargetHeight: 600,
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
    bannerPond.setOptions({ server: bannerServerOptions });

    document.addEventListener('livewire:load', () => {
    // Initialize select2 elements
    $('.js-genre-multiple').select2();
    $('.js-language-multiple').select2();

    // Set initial values from Livewire properties
    let initialGenres = @json($selectedGenres);
    let initialLanguages = @json($selectedLanguages);
    console.log('Initial genres:', initialGenres, 'Initial languages:', initialLanguages);
    $('.js-genre-multiple').val(initialGenres).trigger('change');
    $('.js-language-multiple').val(initialLanguages).trigger('change');

    // Bind change event to update Livewire data
    $('.js-genre-multiple').on('change', function () {
        var selectedGenres = $(this).val();
        @this.set('selectedGenres', selectedGenres);
    });
    $('.js-language-multiple').on('change', function () {
        var selectedLanguages = $(this).val();
        @this.set('selectedLanguages', selectedLanguages);
    });
});

</script>
@endpush

