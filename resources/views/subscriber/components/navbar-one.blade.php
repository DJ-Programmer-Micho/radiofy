<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{app('app-icon')}}" alt="Mradiofy" height="32">
            </span>
            <span class="logo-lg">
                <img src="{{ app('app-icon-width') }}" alt="Mradiofy" height="64">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{app('app-icon')}}" alt="Mradiofy" height="32">
            </span>
            <span class="logo-lg">
                <img src="{{ app('app-icon-width') }}" alt="Mradiofy" height="64">
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
                                <a wire:navigate href="{{route('subscriber.dashboard')}}" class="nav-link" data-key="t-ecommerce">{{__('Dashboard')}}</a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                {{-- @endif --}}
                {{-- @if (hasRole([1,2,5,6,7])) --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarprops" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarprops">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Radio Server')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarprops">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs-radios')}}" class="nav-link" data-key="r-radio">{{__('Radio\'s')}}</a>
                            </li>                          
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarexternalprops" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarexternalprops">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('External Radio Server')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarexternalprops">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs-external-radios')}}" class="nav-link" data-key="r-radio">{{__('Radio\'s')}}</a>
                            </li>                          
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarplans" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarplans">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Plans')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarplans">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.my.plan')}}" class="nav-link" data-key="r-my-radio">{{__('Your Plan\'s')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.new.plan')}}" class="nav-link" data-key="r-add-radio">{{__('Available Plan\'s')}}</a>
                            </li>                      
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarPromote" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPromote">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Promote Radio')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarPromote">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.my.promo')}}" class="nav-link" data-key="r-my-radio">{{__('view Promotions')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.new.promo')}}" class="nav-link" data-key="r-add-radio">{{__('Available Plan\'s')}}</a>
                            </li>                      
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVerified" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarVerified">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Get Verified')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVerified">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.my.verify')}}" class="nav-link" data-key="r-my-radio">{{__('My Verify')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.new.verify')}}" class="nav-link" data-key="r-add-radio">{{__('Get Verify')}}</a>
                            </li>                      
                        </ul>
                    </div>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSoftware" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSoftware">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Download Software')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSoftware">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.soft.butt')}}" class="nav-link" data-key="r-my-radio">{{__('BUTT')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.soft.zara')}}" class="nav-link" data-key="r-add-radio">{{__('Zara Radio')}}</a>
                            </li>                      
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSupport" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSupport">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Support')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSupport">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs.support')}}" class="nav-link" data-key="r-my-radio">{{__('Send Message')}}</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    <div class="sidebar-background"></div>
</div>