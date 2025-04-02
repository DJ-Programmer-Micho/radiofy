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
                            class="responsiveIcon"
                            src="https://cdn.lordicon.com/rzgcaxjz.json"
                            trigger="loop"
                            state="loop-roll"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span style="padding-left: 10px">{{__('Exploler')}}</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        @if (auth()->guard('listener')->check())
                        <a wire:navigate href="{{ route('listener.library') }}" class="nav-link menu-link" data-key="l-home">
                        @else
                        <a wire:navigate href="{{ route("lis.signin") }}" class="nav-link menu-link" data-key="l-home">
                        @endif
                            <lord-icon
                            class="responsiveIcon"
                            src="https://cdn.lordicon.com/gcupmfyg.json"
                            trigger="loop"
                            delay="2000"
                            state="hover-squeeze"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span style="padding-left: 10px">{{__('Library')}}</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('listener.genre') }}" class="nav-link menu-link" data-key="l-home">
                            <lord-icon
                            class="responsiveIcon"
                            src="https://cdn.lordicon.com/cqefxcni.json"
                            trigger="loop"
                            delay="2000"
                            state="hover-squeeze"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span style="padding-left: 10px">{{__('Genre')}}</span>
                        </a>
                    </li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <a wire:navigate href="{{ route('listener.language') }}" class="nav-link menu-link" data-key="l-home">
                            <lord-icon
                            class="responsiveIcon"
                            src="https://cdn.lordicon.com/onmwuuox.json"
                            trigger="loop"
                            delay="2000"
                            state="hover-squeeze"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                            </lord-icon>
                            <span style="padding-left: 10px">{{__('Language')}}</span>
                        </a>
                    </li>
                </ul>
            </ul>
        </div>
    <div class="sidebar-background"></div>
</div>
@push('scripts')
<script>
    function updateIconSize() {
        const icons = document.getElementsByClassName('responsiveIcon');
        for (let i = 0; i < icons.length; i++) {
            if (window.innerWidth > 1024) { // Desktop breakpoint
                icons[i].style.width = '44px';
                icons[i].style.height = '44px';
            } else { // Mobile
                icons[i].style.width = '32px';
                icons[i].style.height = '32px';
            }
        }
    }

    // Initial size and on resize
    updateIconSize();
    console.log('asd');
    window.addEventListener('resize', updateIconSize);
</script>
@endpush