<div class="page-content">
    {{-- @include('admin.pages.radio.radio-form', ['title' => "Radio Configuration"]) --}}
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
        <!-- Add Radio Button -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addRadioModal">
            <i class="fa-regular fa-plus me-2"></i>{{ __('Add Radio') }}
        </button>
        <button type="button" class="btn btn-info" wire:click="updateIcecastXml">
            <i class="fa-solid fa-sync"></i> {{ __('Update Icecast XML') }}
        </button>
        <button type="button" class="btn btn-info" wire:click="restartAllRadios">
            <i class="fa-solid fa-sync"></i> {{ __('Update Radio Python XML') }}
        </button>
        <button type="button" class="btn btn-info" wire:click="generateStatusFile">
            <i class="fa-solid fa-sync"></i> {{ __('Update Radio JSON XML') }}
        </button>
        <div class="container my-3">
            @if (session()->has('xml_update'))
                <div class="alert alert-success mt-2">
                    {{ session('xml_update') }}
                </div>
            @endif
            @if (session()->has('xml_reload'))
                <div class="alert alert-success mt-2">
                    {{ session('xml_reload') }}
                </div>
            @endif
        </div>
        
        <!-- Radio Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row g-4">
                            <div class="col-sm-auto">
                                {{-- Optional actions --}}
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="search" wire:model="search" class="form-control" id="searchRadioList" placeholder="{{ __('Search Radios...') }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if($statusFilter === 'all') active @endif" style="cursor: pointer" wire:click="changeTab('all')" role="tab">
                                    {{ __('All') }} <span class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">{{ $activeCount + $nonActiveCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($statusFilter === 'active') active @endif" style="cursor: pointer" wire:click="changeTab('active')" role="tab">
                                    {{ __('Active') }} <span class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">{{ $activeCount }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($statusFilter === 'non-active') active @endif" style="cursor: pointer" wire:click="changeTab('non-active')" role="tab">
                                    {{ __('Non-Active') }} <span class="badge bg-danger-subtle text-danger align-middle rounded-pill ms-1">{{ $nonActiveCount }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        @if ($tableData->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Radio Name') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tableData as $data)
                                        <tr wire:key="radio-{{ $data->id }}">
                                            <td class="align-middle">
                                                {{ $data->radio_name ?? 'Unknown' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge {{ $data->status ? 'bg-success' : 'bg-danger' }} p-2" style="font-size: 0.7rem;">
                                                    {{ $data->status ? __('Active') : __('Non-Active') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button class="dropdown-item" type="button" wire:click="updateStatus({{ $data->id }})">
                                                                @if ($data->status == 1)
                                                                    <span class="text-danger"><i class="fa-solid fa-xmark me-2"></i>{{ __('Deactivate') }}</span>
                                                                @else
                                                                    <span class="text-success"><i class="fa-solid fa-check me-2"></i>{{ __('Activate') }}</span>
                                                                @endif
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item edit-radio" data-bs-toggle="modal" data-bs-target="#updateRadioModal" wire:click="editRadio({{ $data->id }})">
                                                                <i class="fa-regular fa-pen-to-square me-2"></i>{{ __('Edit') }}
                                                            </button>
                                                        </li>
                                                        <!-- New button to update the specific radio via Python -->
                                                        <li>
                                                            <button type="button" class="dropdown-item" wire:click="updateRadioPython({{ $data->id }})">
                                                                <i class="fa-solid fa-sync me-2"></i>{{ __('Update Radio Python') }}
                                                            </button>
                                                        </li>
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <button type="button" class="dropdown-item" wire:click="removeRadio({{ $data->id }})">
                                                                <i class="fa-regular fa-trash-can me-2"></i>{{ __('Delete') }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                                                <h5 class="mt-4">{{ __('Sorry! No Result Found') }}</h5>
                                            </td>
                                        </tr>
                                    @endforelse
                                    
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $tableData->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                                <h5 class="mt-4">{{ __('Sorry! No Result Found') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>