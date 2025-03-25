<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <style>
    .navbar-menu .navbar-nav .nav-link {
        padding: .625rem 1rem;
    }
    </style>
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
                <ul class="nav">
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('listener.home') }}" class="nav-link menu-link" data-key="l-home">
                            <lord-icon
                            src="https://cdn.lordicon.com/rzgcaxjz.json"
                            trigger="loop"
                            state="loop-roll"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span class="ml-2">{{__('Home')}}</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('listener.genre') }}" class="nav-link menu-link" data-key="l-home">
                            <lord-icon
                            src="https://cdn.lordicon.com/jectmwqf.json"
                            trigger="loop"
                            delay="2000"
                            state="hover-squeeze"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span class="ml-2">{{__('Genre')}}</span>
                        </a>
                    </li>
                </ul>
                {{-- <li class="nav-item">
                    
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="bx bxs-dashboard"></i> 
                        <lord-icon
                            src="https://cdn.lordicon.com/rzgcaxjz.json"
                            trigger="loop"
                            state="loop-roll"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                        </lord-icon>
                        <span data-key="t-dashboards">{{__('HOME')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{ route('listener.home') }}" class="nav-link" data-key="t-ecommerce">{{__('Home')}}</a>
                            </li>
                        </ul>
                    </div>
                </li>  --}}
                {{-- @endif --}}
                {{-- @if (hasRole([1,2,5,6,7])) --}}
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarprops" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarprops">
                        <i class="bx bx-list-ul"></i> <span data-key="t-dashboards">{{__('Radio Server')}}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarprops">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs-radios')}}" class="nav-link" data-key="r-add-radio">{{__('Add New Radio')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('subs-radios')}}" class="nav-link" data-key="r-radio">{{__('Radio\'s')}}</a>
                            </li>
                            <li class="nav-item">
                                <a wire:navigate href="{{route('radio-languages')}}" class="nav-link" data-key="u-language">{{__('Language\'s')}}</a>
                            </li>
                            
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
               

            </ul>
        </div>
    <div class="sidebar-background"></div>
</div>