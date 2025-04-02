<div>
<div class="page-content-dash">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between" class="mb-0">
                    <h4 class="mb-sm-0">{{ __('Exploler') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Exploler') }}</a></li>
                            <li class="breadcrumb-item active">M Radiofy</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@livewire('listener.home.short-card-livewire')
@livewire('listener.home.top-radio-livewire')
@livewire('listener.home.main-ads-livewire')
@livewire('listener.home.discover-radio-livewire')
<div class="px-3">
    @livewire('listener.home.some-genre-livewire')
</div>
@livewire('listener.home.verified-radio-livewire')
<div class="px-3">
    @livewire('listener.home.some-language-livewire')
</div>
@if (auth()->guard('listener')->check())
@livewire('listener.home.sponser-radio-livewire')
@livewire('listener.home.watch-radio-livewire')
@livewire('listener.home.followed-radio-livewire')
@livewire('listener.home.liked-radio-livewire')
@endif
<!-- container-fluid -->
</div>