<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        @include('subscriber.pages.my-promotion.form', [
        ])
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ __('My Campaigns') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('My Campaigns') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Promotion Button -->
        <a href="{{ route('subs.new.promo') }}" class="btn btn-primary mb-3">
            <i class="fa-regular fa-plus me-2"></i>{{ __('Add New Campaign') }}
        </a>
        <!-- Campaign Table -->
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
                                        <input type="search" wire:model="search" class="form-control" placeholder="{{ __('Search Campaigns...') }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($tableData->isNotEmpty())
                            <div class="table-responsive" style="min-height: 450px;">
                                <table class="table table-bordered table-striped table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ __('Logo') }}</th>
                                            <th class="text-center">{{ __('Radio Name') }}</th>
                                            <th class="text-center">{{ __('Promotion Name') }}</th>
                                            <th class="text-center">{{ __('Promotion Text') }}</th>
                                            <th class="text-center">{{ __('Expire Date') }}</th>
                                            <th class="text-center">{{ __('Target Gender') }}</th>
                                            <th class="text-center">{{ __('Target Age Range') }}</th>
                                            <th class="text-center">{{ __('Status') }}</th>
                                            <th class="text-center">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData as $data)
                                            @php
                                                $radio = $data->radioable;
                                                $expire = \Carbon\Carbon::parse($data->expire_date);
                                                // Prepare demographic text from JSON arrays
                                                $genderMap = [1 => 'Male', 2 => 'Female', 3 => 'Rather Not Say'];
                                                $genders = is_array($data->target_gender) ? implode(', ', array_map(fn($g) => $genderMap[$g] ?? $g, $data->target_gender)) : 'N/A';
                                                $ageRanges = is_array($data->target_age_range) ? implode(', ', $data->target_age_range) : 'N/A';
                                            @endphp
                                            <tr wire:key="promotion-{{ $data->id }}">
                                                <td class="align-middle text-center">
                                                    @if($radio && $radio->profile && $radio->profile->logo)
                                                        <img src="{{ asset('storage/' . $radio->profile->logo) }}" width="50" height="50" alt="Logo">
                                                    @else
                                                        <img src="{{ asset('assets/logo/mradiofy-logo.png') }}" width="50" height="50" alt="Default Logo">
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $radio->radio_name ?? __('Unknown') }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $data->promotion->name ?? __('Unknown') }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $data->promotion_text ?? '' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($expire->isPast())
                                                        <span class="text-danger">{{ $expire->format('Y-m-d H:i') }}</span>
                                                    @elseif(now()->diffInDays($expire) <= 7)
                                                        <span class="text-warning">{{ $expire->format('Y-m-d H:i') }}</span>
                                                    @else
                                                        <span class="text-success">{{ $expire->format('Y-m-d H:i') }}</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $genders }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $ageRanges }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge {{ $data && $data->status ? 'bg-success' : 'bg-danger' }} p-2" style="font-size: 0.7rem;">
                                                        {{ $data && $data->status ? __('Active') : __('Non-Active') }} 
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end m-index">
                                                            <li>
                                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updatePromotionEditModal" wire:click="editPromotion({{ $data->id }})">
                                                                    <i class="fa-regular fa-pen-to-square me-2"></i>{{ __('Edit Target') }}
                                                                </button>
                                                            </li>
                                                            <li class="dropdown-divider"></li>
                                                            <li><button class="dropdown-item" type="button" wire:click="updateStatus({{ $data->id }})">
                                                                {{-- <i class="codicon align-bottom me-2 text-muted"></i> --}}
                                                                @if ( $data->status == 1)
                                                                <span class="text-danger"><i class="fa-solid fa-xmark me-2"></i> {{__('De-Active')}}</span>
                                                                @else
                                                                <span class="text-success"><i class="fa-solid fa-check me-2"></i> {{__('Active')}}</span>
                                                                @endif
                                                                </button>
                                                            </li>
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
                                <h5 class="mt-4">{{ __('Sorry! No Campaign Found') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
