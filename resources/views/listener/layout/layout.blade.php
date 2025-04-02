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
{{-- 

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
        <link href="{{asset('dashboard/css/listener-custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        {{-- <link href="{{asset('css/custom.min.css')}}" rel="stylesheet" type="text/css" /> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js" integrity="sha512-6sSYJqDreZRZGkJ3b+YfdhB3MzmuP9R7X1QZ6g5aIXhRvR1Y/N/P47jmnkENm7YL3oqsmI6AK+V6AD99uWDnIw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link href="{{asset('dashboard/css/toaster.css')}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <title>{{ 'Mradiofy' }}</title>
        <style>
            .ar-shift {
                direction: rtl;
                text-align: right;
            }
            .footer {
                padding: 20px calc(1.5rem* .5);
                position: fixed;
                height: 7rem;
                left: 0;
                z-index: 1002;
            }
            [data-layout=vertical]:is([data-sidebar-size=sm],[data-sidebar-size=sm-hover]) .footer {
                left: 0;
            }
            .simplebar-scrollable-y{
                height: calc(100% - 5.5rem) !important;
            }
            </style>
        {{-- @vite('resources/js/app.js') --}}
        @livewireStyles
        <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@latest/dist/turbo.es2017-esm.min.js"></script>
        <link href="{{asset('dashboard/css/player.css')}}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <x-listener.components.header-one />
        {{-- @livewire('admin.partial.header-one-livewire') <--CJECL :UTER --}} 
        <x-listener.components.navbar-one/>
        <div class="vertical-overlay"></div>
         <div class="main-content mb-5">{{-- style="margin-top: 90px;" --}}
            @yield('listener-content')
            {{-- <x-listener.components.player-one /> --}}
            {{-- <x-listener.components.footer-one /> --}}
            {{-- @livewire('listener.layout.player-one-livewire',['last_radio' => 12]) --}}



            <hr>
            <footer class="custom-footer py-5 position-relative" style="margin-bottom: 150px">
                <div class="container">
                    <div class="row">
  
                        <div class="col-lg-12 ms-lg-auto">
                            <div class="row">
                                <div class="col-sm-3 mt-4">
                                    <h5 class="text-white mb-0">Company</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-profile.html">About Us</a></li>
                                            <li><a href="pages-gallery.html">Jobs</a></li>
                                            <li><a href="apps-projects-overview.html">For The Record</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-4">
                                    <h5 class="text-white mb-0">Communities</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-profile.html">For Radio Owner</a></li>
                                            <li><a href="pages-gallery.html">For Listeners</a></li>
                                            <li><a href="apps-projects-overview.html">Advertising</a></li>
                                            <li><a href="pages-timeline.html">Investors</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-4">
                                    <h5 class="text-white mb-0">Useful links</h5>
                                    <div class="text-muted">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-pricing.html"></a></li>
                                            <li><a href="apps-mailbox.html">Terms and Condition</a></li>
                                            <li><a href="apps-mailbox.html">Privacy Policy</a></li>
                                            <li><a href="apps-mailbox.html">Legal</a></li>
                                            <li><a href="apps-mailbox.html">Cookies</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-4">
                                    <h5 class="text-white mb-0">Info</h5>
                                    <div class="text-muted mt-3">
                                        <ul class="list-unstyled ff-secondary footer-list fs-14">
                                            <li><a href="pages-faqs.html">Support</a></li>
                                            <li><a href="pages-faqs.html">Contact</a></li>
                                            <li><a href="pages-faqs.html">FAQ</a></li>
                                            <li><a href="pages-faqs.html">Stats</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
    
                    <div class="row text-center text-sm-start align-items-center mt-5">
                        <div class="col-sm-6">
    
                            <div>
                                <p class="copy-rights mb-0">
                                    <script>
                                        
                                    </script>2025 © M Radiofy - MET IRAQ
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end mt-3 mt-sm-0">
                                <ul class="list-inline mb-0 footer-social-link">
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-facebook-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-github-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-linkedin-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-google-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-dribbble-line"></i>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>






            <div id="persistent-player" data-turbo-permanent>
                @livewire('listener.layout.player-one-livewire')
            </div>
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
    <script src="{{asset('dashboard/libs/apexcharts/apexcharts.min.js')}}"></script>
    <!-- Vector map-->
    <script src="{{asset('dashboard/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('dashboard/libs/jsvectormap/maps/world-merc.js')}}"></script>
    <!--Swiper slider js-->
    <script src="{{asset('dashboard/libs/swiper/swiper-bundle.min.js')}}"></script>
    <!-- Dashboard init -->
    <script src="{{asset('dashboard/js/pages/dashboard-ecommerce.init.js')}}"></script>
    <script src="{{asset('dashboard/js/pages/form-wizard.init.js')}}"></script>
    <!-- App js -->
    <script src="{{asset('dashboard/js/app.js')}}"></script>
    @stack('tproductscript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js" integrity="sha512-xi/RZRIF/S0hJ+yJJYuZ5yk6/8pCiRlEXZzoguSMl+vk2i3m6UjUO/WcZ11blRL/O+rnj94JRGwt/CHbc9+6EA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
    <script src="https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    


    

    @stack('scripts')
    @stack('subscriber_script')
  
    <script>
        $(document).ready(function () {
            'use strict';
            owlCarousels();
        });
    function owlCarousels($wrap, options) {
        if ( $.fn.owlCarousel ) {
            var owlSettings = {
                items: 1,
                loop: true,
                margin: 0,
                responsiveClass: true,
                nav: true,
                navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
                dots: true,
                smartSpeed: 400,
                autoplay: false,
                autoplayTimeout: 15000
            };
            if (typeof $wrap == 'undefined') {
                $wrap = $('body');
            }
            if (options) {
                owlSettings = $.extend({}, owlSettings, options);
            }

            // Init all carousel
            $wrap.find('[data-toggle="owl"]').each(function () {
                var $this = $(this),
                    newOwlSettings = $.extend({}, owlSettings, $this.data('owl-options'));

                $this.owlCarousel(newOwlSettings);
                
            });   
        }
    }
    </script>

    
    <form id="logout-form" action="{{ route('lis.logout.post') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script>
        function changeLanguage(locale) {
            document.getElementById('selectedLocale').value = locale;
            document.getElementById('languageForm').submit();
        }
        </script>
    
    <script>
        window.addEventListener('alert', event => { 
            toastr[event.detail.type](event.detail.message, 
            event.detail.title ?? ''), toastr.options = {
                "closeButton": true,
                "progressBar": true,
            }
        });
    </script>
    <script src="{{asset('dashboard/js/lordicon.js')}}"></script>
    
    <form id="languageForm" action="{{ route('setLocale') }}" method="post">
        @csrf
        <input type="hidden" name="locale" id="selectedLocale" value="{{ app()->getLocale() }}">
    </form>

<script>
    window.addEventListener('auto-play-radio', event => {
        alert('Auto-playing radio with stream URL: ' + event.detail.streamUrl);
        // Here, you could call your audio player code, for example:
        // audioPlayer.play(event.detail.streamUrl);
    });
</script>

<script>
    if (!window.turboAudioSwitchInitialized) {
        window.turboAudioSwitchInitialized = true;

        window.addEventListener('switch-radio', event => {
            const radioId = event.detail.radioId;
            const radioType = event.detail.radioType;
            const sponser = event.detail.sponser ?? null;
            const campaignId = event.detail.campaignId ?? null;
            console.log(
                radioId,
                radioType,
                sponser,
                campaignId
            )
            if(sponser){
                Livewire.emit('incrementCampaignClick', campaignId);
            }
            Livewire.emit('playNowEvent', radioId, radioType);
        });
    }
</script>
@livewireScripts
<script>
// document.addEventListener("turbo:render", () => {
//     Livewire.restart();
// });
// document.addEventListener("turbo:load", () => {
//     Livewire.hook('component.initialized', (component) => {
//     });
// });
document.addEventListener("turbo:before-render", function(event) {
    event.preventDefault();
    if (window.Livewire) {
        Livewire.stop();
    }
    setTimeout(() => event.detail.resume(), 50);
});

document.addEventListener("turbo:render", function() {
    if (window.Livewire) {
        Livewire.start();
    }
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Livewire.emit('updateLimit', window.innerWidth);
    });
</script>


@stack('radio_script')
@stack('teamDelivery')
@stack('dashScript')

</body>
</html>