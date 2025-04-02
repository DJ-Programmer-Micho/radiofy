<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mt-4">
            <div class="card-body">
                <form wire:submit.prevent="register" class="form-steps" autocomplete="off">
                    <div class="text-center pt-3 pb-4 mb-1">
                        <h5>Signup Mradiofy</h5>
                    </div>
                    <!-- Progress bar and steps navigation -->
                    <div id="custom-progress-bar" class="progress-nav mb-4">
                        <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($currentStep - 1) * 25 }}%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link rounded-pill {{ $currentStep == 1 || $currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5 ? 'active' : '' }}" id="step1-tab" disabled>1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link rounded-pill {{ $currentStep == 2 || $currentStep == 3 || $currentStep == 4 || $currentStep == 5 ? 'active' : '' }}" id="step2-tab" disabled>2</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link rounded-pill {{ $currentStep == 3 || $currentStep == 4 || $currentStep == 5 ? 'active' : '' }}" id="step3-tab" disabled>3</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link rounded-pill {{ $currentStep == 4 || $currentStep == 5 ? 'active' : '' }}" id="step4-tab" disabled>4</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link rounded-pill {{ $currentStep == 5 ? 'active' : '' }}" id="step5-tab" disabled>5</button>
                            </li>
                        </ul>
                    </div>
            
                    <!-- Step 1: General Information -->
                    @if ($currentStep == 1)
                    <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel" aria-labelledby="pills-gen-info-tab">
                        <div class="mb-4">
                            <div>
                                <h5 class="mb-1">General Information Livewire</h5>
                                <p class="text-muted">Fill all Information as below</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="firstName">First Name</label>
                                    <input class="form-control @error('firstName') is-invalid @enderror @if(!$errors->has('firstName') && !empty($firstName)) is-valid @endif"
                                    type="text" id="firstName" wire:model="firstName" placeholder="Michel" required>
                                    @error('firstName')
                                    <div>
                                        <span class="text-danger">{{ __($message) }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="lastName">Last Name</label>
                                    <input class="form-control @error('lastName') is-invalid @enderror @if(!$errors->has('lastName') && !empty($lastName)) is-valid @endif"
                                    type="text" id="lastName" wire:model="lastName" placeholder="Shabo" required>
                                    @error('lastName')
                                    <div>
                                        <span class="text-danger">{{ __($message) }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror @if(!$errors->has('email') && !empty($email)) is-valid @endif"
                                    type="email" id="email" wire:model="email" placeholder="email@example.com" required>
                                    @error('email')
                                    <div>
                                        <span class="text-danger">{{ __($message) }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="username">Username</label>
                                    <input class="form-control @error('username') is-invalid @enderror @if(!$errors->has('username') && !empty($username)) is-valid @endif"
                                    type="text" id="username" wire:model="username" placeholder="michel.shabo" required>
                                    @error('username')
                                    <div>
                                        <span class="text-danger">{{ __($message) }}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button 
                        type="button" 
                        class="btn btn-success btn-label right ms-auto nexttab" 
                        wire:click="nextStep"
                    >
                        <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                Go to more info
                            </button>
                        </div>
                        <div class="mt-4 text-center">
                            <div class="signin-other-title">
                                <h5 class="fs-13 mb-4 title">Do have an account?</h5>
                            </div>
                        </div>
                        <div class="mb-4 text-center">
                            <p class="mb-0">
                                <a href="{{ route('subs.signin') }}" class="fw-semibold text-primary text-decoration-underline">Signin</a>
                            </p>
                        </div>
                    </div>
                    @endif
            
                    <!-- Step 2: Password -->
                    @if ($currentStep == 2)
                    <div class="tab-pane show fade" id="pills-info-pass" role="tabpanel" aria-labelledby="pills-info-pass-tab"
                         x-data="{ password: @entangle('password'), password_confirmation: @entangle('password_confirmation') }">
                        <div>
                            <div class="mb-4">
                                <div>
                                    <h5 class="mb-1">Create a password</h5>
                                    <p class="text-muted">Fill all Information as below</p>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Password Field -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="password-input">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input 
                                            type="password" 
                                            id="password-input" 
                                            placeholder="**********" 
                                            autocomplete="new-password"
                                            required 
                                            wire:model.debounce.300ms="password"
                                            class="form-control password-input pe-5 @error('password') is-invalid @elseif(!$errors->has('password') && !empty($password)) is-valid @endif"
                                            style="background-image: none;"
                                            >
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Confirm Password Field -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="confirm-password-input">Confirm Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input 
                                            type="password" 
                                            id="confirm-password-input" 
                                            placeholder="**********" 
                                            autocomplete="new-password"
                                            required 
                                            wire:model.debounce.300ms="password_confirmation"
                                            class="form-control password-input pe-5 @error('password_confirmation') is-invalid @elseif(!$errors->has('password_confirmation') && !empty($password_confirmation)) is-valid @endif"
                                            style="background-image: none;"
                                            >                                        
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="confirm-password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Validation Feedback -->
                                <div class="col-lg-12">
                                    <p id="letter-check">
                                        @if(!preg_match('/[A-Za-z]/', $password))
                                            <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                        @endif
                                        1 letter
                                    </p>
                                    <p id="number-special-check">
                                        @if(!preg_match('/[\d\W]/', $password))
                                            <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                        @endif
                                        1 number or special character (e.g. # ? ! &)
                                    </p>
                                    <p id="length-check">
                                        @if(strlen($password) < 10)
                                            <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                        @endif
                                        10 characters
                                    </p>
                                    <p id="pass-check">
                                        @if($password === '' || $password !== $password_confirmation)
                                            <i class="fa-solid fa-circle-xmark me-2 text-danger"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check me-2 text-success"></i>
                                        @endif
                                        Password Identical
                                    </p>
                                </div>                                
                            </div>
                        </div>
                        <button 
                        type="button" 
                        class="btn btn-link text-decoration-none btn-label previestab" 
                        wire:click="previousStep"
                        >
                        <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                        Back To General Information
                        </button>
                        <button 
                        type="button" 
                        class="btn btn-success btn-label right ms-auto nexttab" 
                        wire:click="nextStep"
                    >
                        <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                Next
                            </button>
                        </div>
                    </div>
                    @endif
                                
                    @if ($currentStep == 3)
                    <div class="tab-pane show fade" id="pills-info-bod" role="tabpanel" aria-labelledby="pills-info-bod-tab">
                        <div class="mb-1">
                            <div>
                                <h5 class="mb-1">Tell us about yourself</h5>
                                <p class="text-muted">Why do we need your data?</p>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Avatar Upload (optional) -->
                            <div class="col-lg-12 mb-3">
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto">
                                        @if($avatar)
                                            <img src="{{ $avatar->temporaryUrl() }}" class="rounded-circle avatar-lg img-thumbnail user-profile-image" alt="user-profile-image">
                                        @else
                                            <img src="{{ app('user') }}" class="rounded-circle avatar-lg img-thumbnail user-profile-image" alt="user-profile-image">
                                        @endif
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file" wire:model="avatar" class="profile-img-file-input" accept="image/png, image/jpeg">
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('avatar') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="mb-3 col-12">
                                        <label class="form-label" for="dob">Date of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" wire:model.defer="dob" required>
                                        @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Gender -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gender">Gender</label>
                                    <div class="form-check form-radio-primary mb-3">
                                        <input 
                                            class="form-check-input @error('gender') is-invalid @enderror" 
                                            type="radio" 
                                            wire:model="gender" 
                                            name="gender" 
                                            value="1" 
                                            id="formradioRight5" 
                                            required>
                                        <label class="form-check-label" for="formradioRight5">Male</label>
                                    </div>
                                    <div class="form-check form-radio-secondary mb-3">
                                        <input 
                                            class="form-check-input @error('gender') is-invalid @enderror" 
                                            type="radio" 
                                            wire:model="gender" 
                                            name="gender" 
                                            value="2" 
                                            id="formradioRight6" 
                                            required>
                                        <label class="form-check-label" for="formradioRight6">Female</label>
                                    </div>
                                    <div class="form-check form-radio-danger mb-3">
                                        <input 
                                            class="form-check-input @error('gender') is-invalid @enderror" 
                                            type="radio" 
                                            wire:model="gender" 
                                            name="gender" 
                                            value="3" 
                                            id="formradioRight7" 
                                            required>
                                        <label class="form-check-label" for="formradioRight7">Prefer Not to say</label>
                                    </div>
                                    @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mt-4">
                            <button 
                                type="button" 
                                class="btn btn-link text-decoration-none btn-label previestab" 
                                wire:click="previousStep">
                                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                Back to Password
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-success btn-label right ms-auto nexttab" 
                                wire:click="nextStep">
                                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                Next
                            </button>
                        </div>
                    </div>
                    @endif

            
                    @if ($currentStep == 4)
                    <div class="tab-pane fade show" id="pills-terms-policy" role="tabpanel" aria-labelledby="pills-terms-policy-tab">
                        <div class="row">
                            <!-- Optional: "Send me news" checkbox -->
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body bg-light">
                                        <div class="form-check form-check-primary mb-3">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="news" 
                                                name="news" 
                                                value="1" 
                                                wire:model.defer="news"
                                            >
                                            <label class="form-check-label" for="news">
                                                Please send me news and offers from Mradiofy
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Required: Share my registration data checkbox -->
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body bg-light">
                                        <div class="form-check form-check-success mb-3">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                id="reg" 
                                                name="reg" 
                                                value="1" 
                                                wire:model.defer="reg"
                                            >
                                            <label class="form-check-label" for="reg">
                                                Share my registration data with Mradiofy's content providers for marketing purposes
                                            </label>
                                            @error('reg')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Required: Accept Terms checkbox -->
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body bg-light">
                                        <div class="form-check form-check-warning mb-3">
                                            <input 
                                                class="form-check-input @error('terms') is-invalid @enderror" 
                                                type="checkbox" 
                                                id="terms" 
                                                name="terms" 
                                                value="1" 
                                                wire:model.defer="terms"
                                            >
                                            <label class="form-check-label" for="terms">
                                                I agree to Mradiofy's 
                                                <a class="text-danger text-decoration-underline" href="#" target="_blank">
                                                    Terms & Conditions
                                                </a>
                                            </label>
                                            @error('terms')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Required: Accept Policy checkbox -->
                            <div class="col-lg-6">
                                <div class="card mb-3">
                                    <div class="card-body bg-light">
                                        <div class="form-check form-check-danger mb-3">
                                            <input 
                                                class="form-check-input @error('policy') is-invalid @enderror" 
                                                type="checkbox" 
                                                id="policy" 
                                                name="policy" 
                                                value="1" 
                                                wire:model.defer="policy"
                                            >
                                            <label class="form-check-label" for="policy">
                                                I agree that Mradiofy collects, uses, shares and protects my personal data.
                                                To learn more, please see 
                                                <a class="text-danger text-decoration-underline" href="#" target="_blank">
                                                    Mradiofy Privacy Policy
                                                </a>
                                            </label>
                                            @error('policy')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Step Navigation -->
                        <div class="d-flex align-items-start gap-3 mt-4">
                            <button 
                                type="button" 
                                class="btn btn-link text-decoration-none btn-label previestab" 
                                wire:click="previousStep"
                            >
                                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                Back to Information
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-success btn-label right ms-auto nexttab" 
                                wire:click="nextStep"
                            >
                                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                Next
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    
            
                    <!-- Step 5: Success -->
                    @if ($currentStep == 5)
                        <div class="text-center">
                            <div class="mb-4">
                                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>
                            </div>

                            <h5>Well Done !</h5>
                            <p class="text-muted">Please review your details and click submit to register.</p>
                            {{-- <button type="button" class="btn btn-secondary" wire:click="previousStep">Back</button>
                            <button type="submit" class="btn btn-success">Submit</button> --}}
                            <div class="d-flex align-items-start gap-3 mt-4">
                            <button 
                                type="button" 
                                class="btn btn-link text-decoration-none btn-label previestab" 
                                wire:click="previousStep"
                            >
                                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                Back to Terms and Policy
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-success btn-label right ms-auto nexttab" 
                                wire:click="register"
                            >
                                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                Submit
                            </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <!-- end card -->
    </div>
</div>