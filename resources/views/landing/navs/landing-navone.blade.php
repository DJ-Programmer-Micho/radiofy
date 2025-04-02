<nav class="navbar navbar-expand-lg bg-light navbar-landing fixed-top" id="navbar">
    <style>
.navbar-landing .navbar-nav .nav-item .nav-link.active, .navbar-landing .navbar-nav .nav-item .nav-link:focus, .navbar-landing .navbar-nav .nav-item .nav-link:hover {
            color: #cc0022 !important;
        }
    </style>
    <div class="container">
        <a class="navbar-brand" href="index.html">
            <img src="{{ app('app-icon-width') }}" class="card-logo card-logo-dark" alt="Mradiofy" height="64">
            <img src="{{ app('app-icon-width') }}" class="card-logo card-logo-light" alt="Mradiofy" height="64">
        </a>
        <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="mdi mdi-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold active" href="#hero">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#top-radio">Top Radio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#plans">Plans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#faqs">FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-15 fw-semibold" href="#contact">Contact</a>
                </li>
            </ul>

            <div class="">
                @if (auth()->guard('subscriber')->check())
                <button type="button" class="btn px-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <img class="rounded-circle" width="64" src="{{isset(auth()->guard('subscriber')->user()->subscriber_profile->avatar) ?  asset('storage/' . auth()->guard('subscriber')->user()->subscriber_profile->avatar) : app('user')}}" alt="{{auth()->guard('subscriber')->user()->subscriber_profile->first_name . ' ' . auth()->guard('subscriber')->user()->subscriber_profile->last_name}}">
                        <span class="text-start ms-xl-2">
                            <span class="ms-1 fw-medium user-name-text">{{auth()->guard('subscriber')->user()->subscriber_profile->first_name . ' ' . auth()->guard('subscriber')->user()->subscriber_profile->last_name}}</span><br>
                            <span class="ms-1 fs-12 user-name-sub-text">Subscriber</span>
                        </span>
                    </span>
                </button>
                @elseif (auth()->guard('listener')->check())
                <button type="button" class="btn px-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <img class="rounded-circle" width="64" src="{{isset(auth()->guard('listener')->user()->listener_profile->avatar) ?  asset('storage/' . auth()->guard('listener')->user()->listener_profile->avatar) : app('user')}}" alt="{{auth()->guard('listener')->user()->listener_profile->first_name . ' ' . auth()->guard('listener')->user()->listener_profile->last_name}}">
                        <span class="text-start ms-xl-2">
                            <span class="ms-1 fw-medium user-name-text">{{auth()->guard('listener')->user()->listener_profile->first_name . ' ' . auth()->guard('listener')->user()->listener_profile->last_name}}</span><br>
                            <span class="ms-1 fs-12 user-name-sub-text">Listener</span>
                        </span>
                    </span>
                </button>
                @else
                <a wire:navigate href="{{  route("lis.signin") }}" type="button" class="btn btn-success" id="page-header-user-dropdown">
                    <span class="d-flex align-items-center">
                        <span class="d-none d-sm-block">Free Login</span>
                        <lord-icon
                        src="https://cdn.lordicon.com/ozlkyfxg.json"
                        trigger="loop"
                        delay="2000"
                        colors="primary:#e4e4e4,secondary:#cc0022"
                        style="width:32px;height:32px">
                    </lord-icon>
                    </span>
                </a>
                <a wire:navigate href="{{  route("subs.signup") }}" class="btn btn-primary">                         
                    <span class="d-flex align-items-center">
                        <span class="d-none d-sm-block">Build Your Radio</span>
                        <lord-icon
                            src="https://cdn.lordicon.com/mhridhuu.json"
                            trigger="loop"
                            delay="2000"
                            colors="primary:#e4e4e4,secondary:#cc0022"
                            style="width:32px;height:32px">
                        </lord-icon>
                    </span>
                </a>
                @endif
            </div>
        </div>
    </div>
</nav>