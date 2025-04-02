<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">
            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Create New Password</h5>
                    <p class="text-muted">Your new password must be different from your previous password.</p>
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="p-2">
                    <form wire:submit.prevent="resetPassword">
                        <div class="mb-3">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="position-relative auth-pass-inputgroup" wire:ignore>
                                <input type="password" class="form-control pe-5 password-input" onpaste="return false" id="newPassword" placeholder="Enter Password" wire:model="newPassword" required>
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                            @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            
                            <label class="form-label" for="newPassword_confirmation">Confirm Password</label>
                            <div class="position-relative auth-pass-inputgroup" wire:ignore>
                                <input type="password" class="form-control pe-5 password-input" id="newPassword_confirmation" placeholder="Confirm Password" wire:model="newPassword_confirmation">
                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="confirm-password-input"><i class="ri-eye-fill align-middle"></i></button>
                            </div>
                            @error('newPassword_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Real-time validation indicators -->
                        <div class="mt-3">
                            <p id="letter-check">
                                @if(!preg_match('/[A-Za-z]/', $newPassword ?? ''))
                                    <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                @else
                                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                @endif
                                1 letter
                            </p>
                            <p id="number-special-check">
                                @if(!preg_match('/[\d\W]/', $newPassword ?? ''))
                                    <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                @else
                                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                @endif
                                1 number or special character (e.g. # ? ! &)
                            </p>
                            <p id="length-check">
                                @if(strlen($newPassword ?? '') < 10)
                                    <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                @else
                                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                @endif
                                10 characters
                            </p>
                            <p id="pass-check">
                                @if(($newPassword ?? '') === '' || ($newPassword !== $newPassword_confirmation))
                                    <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                @else
                                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                @endif
                                Password Identical
                            </p>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <p class="mb-0">Remember your password? <a href="{{ route('lis.signin') }}" class="fw-semibold text-primary text-decoration-underline">Click here</a></p>
        </div>
    </div>
    <script>
        // Toggle for the new password field
        document.getElementById('password-addon').addEventListener('click', function() {
            var passwordInput = document.getElementById('newPassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    
        // Toggle for the confirm password field
        document.getElementById('confirm-password-input').addEventListener('click', function() {
            var confirmInput = document.getElementById('newPassword_confirmation');
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
            } else {
                confirmInput.type = 'password';
            }
        });
    </script>
    
</div>
