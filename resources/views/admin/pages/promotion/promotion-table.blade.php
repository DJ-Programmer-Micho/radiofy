<div class="page-content">
    @include('admin.pages.promotion.promotion-form')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('Promotion Plans') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('Promotion Plans') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Button -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
            <i class="fa-solid fa-plus me-2"></i>{{ __('Add Promotion Plan') }}
        </button>        
        <!-- Promotion Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row g-4">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input type="search" wire:model="search" class="form-control" placeholder="{{ __('Search...') }}">
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
                                            <th class="text-center">#</th>
                                            <th class="text-center">{{ __('Name') }}</th>
                                            <th class="text-center">{{ __('Price ($)') }}</th>
                                            <th class="text-center">{{ __('Duration') }}</th>
                                            <th class="text-center">{{ __('Ribbon') }}</th>
                                            <th class="text-center">{{ __('Ribbon Text') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Priority') }}</th>
                                            <th class="text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData as $index => $data)
                                        <tr wire:key="verify-{{ $data->id }}">
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $data->name }}</td>
                                            <td class="text-center">${{ number_format($data->sell, 2) }}</td>
                                            <td class="text-center">{{ $data->duration }} days</td>
                                            <td class="text-center">
                                                <span class="badge {{ $data->ribbon ? 'bg-success' : 'bg-danger' }} p-2">
                                                    {{ $data->ribbon ? __('Yes') : __('No') }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $data->rib_text ?? '-' }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $data->status ? 'bg-success' : 'bg-danger' }} p-2">
                                                    {{ $data->status ? __('Active') : __('Inactive') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <input type="number" id="priority_{{ $data->id }}" value="{{ $data->priority }}" class="form-control bg-dark text-white" style="max-width: 80px">
                                                    <button type="button" class="btn btn-warning btn-icon text-dark" onclick="updatePriorityValue({{ $data->id }})">
                                                        <i class="fas fa-sort"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-fill"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><button class="dropdown-item" wire:click="updateStatus({{ $data->id }})">
                                                            @if ($data->status)
                                                                <span class="text-danger"><i class="fa-solid fa-xmark me-2"></i> {{ __('Deactivate') }}</span>
                                                            @else
                                                                <span class="text-success"><i class="fa-solid fa-check me-2"></i> {{ __('Activate') }}</span>
                                                            @endif
                                                        </button></li>
                                                        <li><button class="dropdown-item" wire:click="editPromotion({{ $data->id }})" data-bs-toggle="modal" data-bs-target="#updatePromotionModal">
                                                            <i class="fa-regular fa-pen-to-square me-2"></i>{{ __('Edit') }}
                                                        </button></li>
                                                        <li class="dropdown-divider"></li>
                                                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deletePromotionModal" wire:click="confirmDeletePromotion({{ $data->id }})">
                                                            <i class="fa-regular fa-trash-can me-2"></i>{{ __('Delete') }}
                                                        </button></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
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
@push('super_script')
<script>
    function updatePriorityValue(itemId) {
        var input = document.getElementById('priority_' + itemId);
        var updatedPriority = input.value;
        @this.call('updatePriority', itemId, updatedPriority);
    }
</script>
@endpush
