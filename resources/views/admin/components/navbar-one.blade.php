<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                {{-- <img src="{{app('logo_57')}}" alt="Akito" height="27"> --}}
            </span>
            <span class="logo-lg">
                {{-- <img src="{{ app('main_logo') }}" alt="Akito" height="42"> --}}
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                {{-- <img src="{{app('logo_57')}}" alt="Akito" height="27"> --}}
            </span>
            <span class="logo-lg">
                {{-- <img src="{{ app('main_logo') }}" alt="Akito" height="42"> --}}
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar"
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">{{__('Side Bar')}}</span></li>
                {{-- @if (hasRole([1])) --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="bx bxs-dashboard"></i> <span data-key="t-dashboards">{{__('Dashboards')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                {{-- <a wire:navigate href="{{ route('super.dashboard', ['locale' => app()->getLocale()]) }}" class="nav-link" data-key="t-ecommerce">{{__('Dashboard')}}</a> --}}
                                <a wire:navigate href="#" class="nav-link" data-key="t-ecommerce">{{__('Dashboard')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                {{-- @endif --}}
                {{-- @if (hasRole([1,2,5,6,7])) --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarprops" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarprops">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Utilities')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarprops">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-genres')}}" class="nav-link" data-key="u-genre">{{__('Genre\'s')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-languages')}}" class="nav-link" data-key="u-language">{{__('Language\'s')}}</a>
                            </li>
                            
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarRadio" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarRadio">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Radio Stations')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarRadio">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-adjustments')}}" class="nav-link" data-key="r-radio">{{__('Radio Information')}}</a>
                            </li>
                            
                        </ul>
                    </div>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPlan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPlan">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Plans')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPlan">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-plan')}}" class="nav-link" data-key="p-plan">{{__('Radio Plans')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVerify" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarVerify">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Verify')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVerify">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-verify')}}" class="nav-link" data-key="p-plan">{{__('Radio Verify')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> 
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPromo" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPromo">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Promotion')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPromo">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-promotion')}}" class="nav-link" data-key="p-plan">{{__('Promotion Plans')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> 
            </ul>
        </div>
    <div class="sidebar-background"></div>
</div>