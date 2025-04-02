@extends('listener.auth.layout')
@section('listener-auth')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Welcome Back Listener!</h5>
                    <p class="text-muted">Sign in to continue to M Radiofy</p>
                </div>
                <div class="p-2 mt-4">
                    <form action="{{ route('lis.signin.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">Email</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Enter your email" value="{{ old('login') }}" required autocomplete="email">
                            @error('login')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="float-end">
                                {{-- <a href="{{ route('super.password.reset') }}" class="text-muted">Forgot password?</a> --}}
                            </div>
                            <label class="form-label" for="password-input">Password</label>
                            <div class="position-relative auth-pass-inputgroup mb-1">
                                <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input" name="password" required autocomplete="current-password">
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon">
                                    <i class="ri-eye-fill align-middle"></i>
                                </button>
                            </div>
                            <p class="my-0">
                                <a href="{{ route('lis.forget') }}" class="fw-semibold text-primary text-decoration-underline">Forget Password?</a>
                            </p>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="auth-remember-check" name="remember">
                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">Sign In</button>
                        </div>
                        <hr>
                        <div class="mt-4 text-center">
                            <div class="mb-4 text-center">
                                <p class="mb-0">
                                    Don't Have Account yet? <a href="{{ route('lis.signup') }}" class="fw-semibold text-primary text-decoration-underline">Sign Up</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->

        {{-- <div class="mt-4 text-center">
            <p class="mb-0">Don't have an account ? <a href="auth-signup-basic.html" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
        </div> --}}

    </div>
    <script>
        document.getElementById('password-addon').addEventListener('click', function() {
            var passwordInput = document.getElementById('password-input');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    
    </script>
</div>
@endsection