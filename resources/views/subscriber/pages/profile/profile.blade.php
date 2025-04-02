<div class="page-content">
    <div class="container-fluid">
      <!-- Cover Image Section -->
      <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ asset('assets/banner/hero_1.png') }}" class="profile-wid-img" alt="">
            <div class="overlay-content">
            </div>
        </div>
    </div>

  
      <div class="row">
        <!-- Sidebar: Profile Picture and Basic Info -->
        <div class="col-xxl-3">
          <div class="card mt-n5">
            <div class="card-body p-4">
              <div class="text-center">
                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                    <img src="{{ $avatar ? $avatar->temporaryUrl() : (isset($subscriber->subscriber_profile->avatar) ? asset('storage/' . $subscriber->subscriber_profile->avatar) : asset('assets/default-avatar.png')) }}" 
                    alt="{{ $subscriber->subscriber_profile->first_name ?? 'User' }} {{ $subscriber->subscriber_profile->last_name ?? '' }}" 
                    class="rounded-circle avatar-xl img-thumbnail user-profile-image">
               
                  <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                    <input id="profile-img-file-input" type="file" class="profile-img-file-input" wire:model="avatar">
                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                      <span class="avatar-title rounded-circle bg-light text-body">
                        <i class="ri-camera-fill"></i>
                      </span>
                    </label>
                  </div>
                </div>
                <h5 class="fs-16 mb-1">{{ $subscriber->subscriber_profile->first_name ?? '' }} {{ $subscriber->subscriber_profile->last_name ?? '' }}</h5>
                <p class="text-muted mb-0">Subscriber</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Main Content: Profile Details Form -->
        <div class="col-xxl-9">
          <div class="card mt-xxl-n5">
            <div class="card-header">
              <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item">
                  <a class="nav-link text-body active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                    <i class="fas fa-home"></i> Personal Details
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-body" data-bs-toggle="tab" href="#changePassword" role="tab">
                    <i class="far fa-user"></i> Change Password
                  </a>
                </li>
              </ul>
            </div>
            <div class="card-body p-4">
              @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
              @endif
              <div class="tab-content">
                <!-- Personal Details Tab -->
                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                  <form wire:submit.prevent="updateProfile" autocomplete="off">
                    <div class="row">
                      <!-- First and Last Name -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="firstName" class="form-label">First Name</label>
                          <input type="text" class="form-control" id="firstName" placeholder="Enter your first name" wire:model="first_name" required>
                          @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="lastName" class="form-label">Last Name</label>
                          <input type="text" class="form-control" id="lastName" placeholder="Enter your last name" wire:model="last_name" required>
                          @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <!-- Email and Username -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="email" class="form-label">Email</label>
                          <input type="email" class="form-control" id="email" placeholder="email@example.com" wire:model="email" required>
                          @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="username" class="form-label">Username</label>
                          <input type="text" class="form-control" id="username" placeholder="Your username" wire:model="username" required>
                          @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <!-- Phone Number -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="phoneNumber" class="form-label">Phone Number</label>
                          <input type="text" class="form-control" id="phoneNumber" placeholder="Enter your phone number" wire:model="phone_number">
                          @error('phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <!-- Date of Birth -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="dob" class="form-label">Date of Birth</label>
                          <input type="date" class="form-control" id="dob" wire:model.defer="dob" required>
                          @error('dob') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <!-- Gender Radio Buttons -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label class="form-label">Gender</label>
                          <div class="form-check form-radio-primary mb-2">
                            <input type="radio" id="genderMale" value="1" wire:model="gender" class="form-check-input" required>
                            <label for="genderMale" class="form-check-label">Male</label>
                          </div>
                          <div class="form-check form-radio-secondary mb-2">
                            <input type="radio" id="genderFemale" value="2" wire:model="gender" class="form-check-input" required>
                            <label for="genderFemale" class="form-check-label">Female</label>
                          </div>
                          <div class="form-check form-radio-danger">
                            <input type="radio" id="genderNotSay" value="3" wire:model="gender" class="form-check-input" required>
                            <label for="genderNotSay" class="form-check-label">Prefer Not to Say</label>
                          </div>
                          @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <!-- Optional: Other fields like Country, City, Address, Zip Code -->
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="country" class="form-label">Country</label>
                          <input type="text" class="form-control" id="country" placeholder="Enter your country" wire:model="country">
                          @error('country') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="city" class="form-label">City</label>
                          <input type="text" class="form-control" id="city" placeholder="Enter your city" wire:model="city">
                          @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="address" class="form-label">Address</label>
                          <input type="text" class="form-control" id="address" placeholder="Enter your address" wire:model="address">
                          @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label for="zip_code" class="form-label">Zip Code</label>
                          <input type="text" class="form-control" id="zip_code" placeholder="Enter your zip code" wire:model="zip_code">
                          @error('zip_code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>
                    </div>
                    <div class="text-end">
                      <button type="submit" class="btn btn-success">Update Profile</button>
                    </div>
                  </form>
                </div>
                
                <!-- Change Password Tab (Implement as needed) -->
                <div class="tab-pane" id="changePassword" role="tabpanel">
                  @livewire('subscriber.profile.change-password-livewire')
                </div>
                
              </div>
            </div>
          </div>
        </div>
        <!-- End Main Content -->
      </div>
      <!-- End Row -->
    </div>
    <!-- End Container Fluid -->
  </div>
  