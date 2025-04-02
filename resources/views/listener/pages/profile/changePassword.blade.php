<div>
    <form wire:submit.prevent="updatePassword" autocomplete="off">
        <div class="row g-2">
            <!-- Old Password -->
            <div class="col-lg-4">
                <label for="oldPassword" class="form-label">Old Password*</label>
                <div class="position-relative auth-pass-inputgroup" wire:ignore>
                    <input type="password" class="form-control" id="oldPassword" placeholder="Enter current password" wire:model="oldPassword">
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="oldPassword-input"><i class="ri-eye-fill align-middle"></i></button>
                    @error('oldPassword') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- New Password -->
            <div class="col-lg-4">
                <label for="newPassword" class="form-label">New Password*</label>
                <div class="position-relative auth-pass-inputgroup" wire:ignore>
                    <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" wire:model="newPassword">
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="newPassword-input"><i class="ri-eye-fill align-middle"></i></button>
                    @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Confirm Password -->
            <div class="col-lg-4">
                <label for="newPassword_confirmation" class="form-label">Confirm Password*</label>
                <div class="position-relative auth-pass-inputgroup" wire:ignore>
                    <input type="password" class="form-control" id="newPassword_confirmation" placeholder="Confirm new password" wire:model="newPassword_confirmation">
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="newPassword_confirmation-input"><i class="ri-eye-fill align-middle"></i></button>
                    @error('newPassword_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Real-time password validation checks -->
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

        <div class="col-lg-12 text-end mt-3">
            <button type="submit" class="btn btn-success">Change Password</button>
        </div>
    </form>
    <script>
        // Toggle for the new password field
        document.getElementById('oldPassword-input').addEventListener('click', function() {
            var passwordInput = document.getElementById('oldPassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    
        // Toggle for the confirm password field
        document.getElementById('newPassword-input').addEventListener('click', function() {
            var confirmInput = document.getElementById('newPassword');
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
            } else {
                confirmInput.type = 'password';
            }
        });

        document.getElementById('newPassword_confirmation-input').addEventListener('click', function() {
            var confirmInput = document.getElementById('newPassword_confirmation');
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
            } else {
                confirmInput.type = 'password';
            }
        });
    </script>
    
</div>
