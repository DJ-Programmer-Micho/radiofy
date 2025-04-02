<section class="section pb-0 hero-section" id="hero">
    <div class="bg-overlay bg-overlay-pattern"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-10">
                <div class="text-center mt-lg-5 pt-5">
                    <h1 class="display-6 fw-bold mb-3 lh-base">The better way to manage your Radio with <span class="text-danger">M Radiofy </span></h1>
                    {{-- <p class="lead text-muted lh-base">Velzon is a fully responsive, multipurpose and premium Bootstrap 5 Admin & Dashboard Template built in multiple frameworks.</p> --}}

                    <div class="d-flex gap-2 justify-content-center mt-4">
                        <a href="{{ route("subs.signup") }}" class="btn btn-primary">Get Started <i class="ri-arrow-right-line align-middle ms-1"></i></a>
                        <a href="#plans" class="btn btn-danger">View Plans <i class="ri-eye-line align-middle ms-1"></i></a>
                    </div>
                </div>

                <div class="mt-4 mt-sm-5 pt-sm-5 mb-sm-n5 demo-carousel">
                    <div class="demo-img-patten-top d-none d-sm-block">
                        <img src="{{ asset('assets/img/img-pattern.png') }}" class="d-block img-fluid" alt="...">
                    </div>
                    <div class="demo-img-patten-bottom d-none d-sm-block">
                        <img src="{{ asset('assets/img/img-pattern.png') }}" class="d-block img-fluid" alt="...">
                    </div>
                    <div class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner shadow-lg p-2 bg-white rounded">
                            <div class="carousel-item active" data-bs-interval="2000">
                                <img src="{{ asset('assets/img/ho1.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="{{ asset('assets/img/ho2.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="{{ asset('assets/img/ho3.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item" data-bs-interval="2000">
                                <img src="{{ asset('assets/img/ho4.jpg') }}" class="d-block w-100" alt="...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end shape -->
</section>