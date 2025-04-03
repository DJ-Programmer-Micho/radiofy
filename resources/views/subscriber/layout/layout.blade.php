<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-layout="vertical" data-topbar="light" data-sidebar-size="lg" data-sidebar="dark" data-sidebar-image="none" data-preloader="disable" data-sidebar-visibility="show" data-layout-style="default" data-bs-theme="dark" data-layout-width="fluid" data-layout-position="fixed">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta name="HandheldFriendly" content="True"/>
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="publisher" content="Michel Shabo">
        <meta name="theme-color" content="#cc0022">
        <meta name="mobile-web-app-title" content="Mradiofy">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Mradiofy">
        <meta name="apple-mobile-web-app-status-bar-style" content="#cc0022">
        <meta name='identifier-URL' content='{{url()->current()}}'>
        <meta name="author" content="Shabo Shabo">
        <meta name="copyright" content="Mradiofy">
        <meta name="page-topic" content="online-radio-station">
        <meta name="page-type" content="website">
        <meta name="audience" content="Everyone">
        <meta name="robots" content="index, follow">
        <link rel="shortcut icon" href="{{app('app-icon')}}">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{app('app-icon')}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{app('app-icon')}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{app('app-icon')}}">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{app('app-icon')}}">
        <link rel="apple-touch-icon-precomposed" href="{{app('app-icon')}}">

        {{-- <link rel="stylesheet" href="{{asset('main/assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css')}}"> --}}
        <!--Swiper slider css-->
        <link href="{{asset('dashboard/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Layout config Js -->
        <script src="{{asset('dashboard/js/layout.js')}}"></script>
        <!-- Bootstrap Css -->
        <link href="{{asset('dashboard/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('dashboard/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('dashboard/css/app.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/css/dash_cust.css')}}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        {{-- <link href="{{asset('css/custom.min.css')}}" rel="stylesheet" type="text/css" /> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js" integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link href="{{asset('dashboard/css/toaster.css')}}" rel="stylesheet" type="text/css">
        <title>{{ 'Dashboard | Radiofy' }}</title>
        <style>
            .ar-shift {
                direction: rtl;
                text-align: right;
            }
        </style>
        {{-- @vite('resources/js/app.js') --}}
        @livewireStyles
    </head>
    <body>
        <x-subscriber.components.header-one />
        {{-- @livewire('admin.partial.header-one-livewire') <--CJECL :UTER --}} 
        <x-subscriber.components.navbar-one/>
        <div class="vertical-overlay"></div>
         <div class="main-content">{{-- style="margin-top: 90px;" --}}
            @yield('subscriber-content')
            <x-subscriber.components.footer-one />
        </div>
    <!-- JAVASCRIPT -->
    <script src="{{asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dashboard/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('dashboard/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('dashboard/libs/feather-icons/feather.min.js')}}"></script>
    {{-- <script src="{{asset('dashboard/js/pages/plugins/lord-icon-2.1.0.js')}}"></script> --}}
    <script src="{{asset('dashboard/js/plugins.js')}}"></script>
    <script src="{{asset('dashboard/js/jquery.min.js')}}"></script>
    <!-- apexcharts -->
    {{-- <script src="{{asset('dashboard/libs/apexcharts/apexcharts.min.js')}}"></script> --}}
    <!-- Vector map-->
    <script src="{{asset('dashboard/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    
    <script src="{{asset('dashboard/libs/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('dashboard/js/pages/chartjs.init.js')}}"></script>
    <script src="{{asset('dashboard/libs/jsvectormap/maps/world-merc.js')}}"></script>
    <!--Swiper slider js-->
    <script src="{{asset('dashboard/libs/swiper/swiper-bundle.min.js')}}"></script>
    <!-- Dashboard init -->
    <script src="{{asset('dashboard/js/pages/dashboard-ecommerce.init.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('dashboard/js/app.js')}}"></script>
    @stack('tproductscript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
    @stack('subscriber_script')
  
    <script src="{{asset('dashboard/js/lordicon.js')}}"></script>
    
    <form id="logout-form" action="{{ route('subs.logout.post') }}" method="POST" style="display: none;">
        @csrf
    </form>
    {{-- <form id="languageForm" action="{{ route('setLocale') }}" method="post">
        @csrf
        <input type="hidden" name="locale" id="selectedLocale" value="{{ app()->getLocale() }}">
    </form> --}}
    
    {{-- <script>
        function changeLanguage(locale) {
            document.getElementById('selectedLocale').value = locale;
            document.getElementById('languageForm').submit();
        }
    </script> --}}
    
    <script>
        window.addEventListener('alert', event => { 
            toastr[event.detail.type](event.detail.message, 
            event.detail.title ?? ''), toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }
        });
    </script>
    <script>
        if (window.currentHowl) {
            window.currentHowl.unload();
            console.log("Audio player unloaded on logout.");
        }
    </script>

@livewireScripts
@stack('radio_script')
    @stack('teamDelivery')
    @stack('dashScript')

</body>
</html>