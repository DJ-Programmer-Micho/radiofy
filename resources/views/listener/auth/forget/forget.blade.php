<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">
            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Forgot Password?</h5>
                    <p class="text-muted">Enter your email and instructions will be sent to you!</p>
                    <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop" colors="primary:#0ab39c" class="avatar-xl">
                    </lord-icon>
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="p-2">
                    <form wire:submit.prevent="sendResetLink">
                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email" wire:model="email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success w-100" type="submit">Send Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-4 text-center">
            <p class="mb-0">Wait, I remember my password... <a href="{{ route('lis.signin') }}" class="fw-semibold text-primary text-decoration-underline">Click here</a></p>
        </div>
    </div>
</div>