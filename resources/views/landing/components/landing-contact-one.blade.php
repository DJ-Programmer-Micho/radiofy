<section class="section" id="contact">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h3 class="mb-3 fw-bold">Get In Touch</h3>
                    <p class="text-muted mb-4">We thrive when coming up with innovative ideas but also
                        understand that a smart concept should be supported with faucibus sapien odio measurable
                        results.</p>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row gy-4">
            <div class="col-lg-4">
                <div>
                    <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Office Address 1:</h5>
                        <div class="fw-semibold">Kurdistan - Erbil, <br /> 44001</div>
                    </div>
                    <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Office Address 2:</h5>
                        <div class="fw-semibold">Iraq - Baghdad, <br /> 10011</div>
                    </div>
                    <div class="mt-4">
                        <h5 class="fs-13 text-muted text-uppercase">Response Hours:</h5>
                        <div class="fw-semibold">10:00am to 4:00pm</div>
                    </div>
                </div>
            </div>
            <!-- end col -->
            <div class="col-lg-8">
                <div>
                    <form action="/contactus-whatsapp" class="contact-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label fs-13">Name</label>
                                    <input name="name" id="name" type="text" class="form-control bg-light border-light" placeholder="Your name*" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fs-13">Email</label>
                                    <input name="email" id="email" type="email" class="form-control bg-light border-light" placeholder="Your email*" value="{{ old('email') }}" required>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="subject" class="form-label fs-13">Subject</label>
                                    <input type="text" class="form-control bg-light border-light" id="subject" name="subject" placeholder="Your Subject.." value="{{ old('subject') }}" required/>
                                    @error('subject')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="subject" class="form-label fs-13">Phone Number (Optional)</label>
                                    <input type="text" class="form-control bg-light border-light" id="phone" name="phone" placeholder="00964" pattern="^[\+0-9\-\s]{7,20}$" value="{{ old('phone') }}" />
                                    @error('phone')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="message" class="form-label fs-13">Message</label>
                                    <textarea name="message" id="message" rows="3" class="form-control bg-light border-light" placeholder="Your message..." value="{{ old('message') }}" required></textarea>
                                    @error('message')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-end">
                                <input type="submit" id="submit" name="send" class="submitBnt btn btn-primary" value="Send Message">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>