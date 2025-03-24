<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Radios') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('My Plans') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Radio Button -->
        <a href="{{ route('subs.new.plan') }}" class="btn btn-primary mb-3">
            <i class="fa-regular fa-plus me-2"></i>{{ __('Add New Radio') }}
        </a>
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
                            <div class="table-responsive" style="min-height: 450px;">
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ __('Logo') }}</th>
                                            <th class="text-center">{{ __('Radio Name') }}</th>
                                            <th class="text-center">{{ __('Bitrate (Kbps)') }}</th>
                                            <th class="text-center">{{ __('Max Listeners') }}</th>
                                            <th class="text-center">{{ __('Highest Listeners') }}</th>
                                            <th class="text-center">{{ __('Server Mount') }}</th>
                                            <th class="text-center">{{ __('Radio Expire') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Radio Refresh') }}</th>
                                            <th class="text-center">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tableData as $data)
                                        <tr wire:key="radio-{{ $data->id }}">
                                            <td class="align-middle text-center">
                                                    @if($data->radio_configuration_profile->logo)
                                                        <img src="{{ asset('storage/' . $data->radio_configuration_profile->logo) }}" class="img-fluid" width="50" height="50" alt="Logo">
                                                    @else
                                                        <img src="{{ asset('assets/logo/mradiofy-logo.png') }}" class="img-fluid" width="50" height="50" alt="Default Logo">
                                                    @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $data->radio_name ?? 'Unknown' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $data->plan->bitrate ?? 0 }} Kbps
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $data->plan->max_listeners ?? 0 }} 
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $data->radio_configuration_profile->highest_peak_listeners ?? 0 }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ env('APP_URL').'/'.$data->radio_name_slug ?? 'Unknown' }}" target="blank" class="text-decoration-underline">
                                                    {{ env('APP_URL').'/'.$data->radio_name_slug ?? 'Unknown' }} <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                </a>
                                            </td>
                                            @php
                                                $expire = \Carbon\Carbon::parse($data->duration->expire_date);
                                            @endphp
                                            
                                            <td class="align-middle text-center">
                                                @if($expire->isPast())
                                                    <span class="text-danger"><i class="fa-solid fa-skull-crossbones fa-beat-fade"></i> {{ $expire->format('Y-m-d H:i') }}</span>
                                                @elseif($now->diffInDays($expire) <= 7)
                                                    <span class="text-warning"><i class="fa-solid fa-triangle-exclamation fa-bounce"></i> {{ $expire->format('Y-m-d H:i') }}</span>
                                                @else
                                                    <span class="text-success">{{ $expire->format('Y-m-d H:i') }}</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge {{ $data->status ? 'bg-success' : 'bg-danger' }} p-2" style="font-size: 0.7rem;">
                                                    {{ $data->status ? __('Active') : __('Non-Active') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="updateRadioPython({{ $data->id }})">
                                                    <i class="fa-solid fa-rotate"></i>
                                                </button>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end m-index">
                                                        <li>
                                                            <a href="{{ route('subs-radio-manage',['radio_id' => $data->id]) }}" class="dropdown-item edit-radio">
                                                                <span class="text-success"><i class="fa-regular fa-credit-card me-2"></i>{{ __('Renew') }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <a href="{{ route('subs-radio-manage',['radio_id' => $data->id]) }}" class="dropdown-item edit-radio">
                                                                <i class="fa-regular fa-pen-to-square me-2"></i>{{ __('Radio Management') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('subs-radio-server',['radio_id' => $data->id]) }}" class="dropdown-item edit-radio">
                                                                <i class="fa-solid fa-server me-2"></i>{{ __('Radio Server') }}
                                                            </a>
                                                        </li>
                                                        <li class="dropdown-divider"></li>
                                                        <li>
                                                            <button type="button" class="dropdown-item" wire:click="removeRadio({{ $data->id }})">
                                                                <span class="text-danger"><i class="fa-regular fa-trash-can me-2"></i>{{ __('Delete Radio') }}</span>
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