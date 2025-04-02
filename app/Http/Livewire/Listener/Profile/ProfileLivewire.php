<?php

namespace App\Http\Livewire\Listener\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class ProfileLivewire extends Component
{
    use WithFileUploads;

    public $listener;
    
    // Listener profile fields
    public $first_name;
    public $last_name;
    public $dob;
    public $gender;
    public $country;
    public $city;
    public $address;
    public $zip_code;
    public $phone_number;
    public $avatar; // for file upload

    // Listener fields
    public $email;
    public $username; // optional if you want to update it

    public function mount()
    {
        // Get the logged-in listener
        $this->listener = auth()->guard('listener')->user();

        // Initialize fields from listener profile if available
        $profile = $this->listener->listener_profile;
        if ($profile) {
            $this->first_name   = $profile->first_name;
            $this->last_name    = $profile->last_name;
            $this->dob          = $profile->dob;
            $this->gender       = $profile->gender;
            $this->country      = $profile->country;
            $this->city         = $profile->city;
            $this->address      = $profile->address;
            $this->zip_code     = $profile->zip_code;
            $this->phone_number = $profile->phone_number;
        }
        // Initialize the email and username from the listener record
        $this->email = $this->listener->email;
        $this->username = $this->listener->username;
    }

    public function updateProfile()
    {
        // Validate input data. Adjust the rules as needed.
        $validatedData = $this->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'dob'          => 'nullable|date',
            'gender'       => 'nullable|in:1,2,3', // 1=Male, 2=Female, 3=Prefer not to say
            'country'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:255',
            'zip_code'     => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'email'        => ['required', 'email', Rule::unique('listeners')->ignore($this->listener->id)],
            'username'     => ['required', 'string', Rule::unique('listeners')->ignore($this->listener->id)],
            'avatar'       => 'nullable|image|max:3072', // optional avatar upload (max 3MB)
        ]);
    
        // Update the Listener record (email and username belong to listener)
        $this->listener->email = $validatedData['email'];
        $this->listener->username = $validatedData['username'];
        $this->listener->save();
    
        // Prepare profile data by selecting only fields that belong to the profile.
        $profileData = [
            'first_name'   => $validatedData['first_name'],
            'last_name'    => $validatedData['last_name'],
            'dob'          => $validatedData['dob'] ?? null,
            'gender'       => $validatedData['gender'] ?? null,
            'country'      => $validatedData['country'] ?? null,
            'city'         => $validatedData['city'] ?? null,
            'address'      => $validatedData['address'] ?? null,
            'zip_code'     => $validatedData['zip_code'] ?? null,
            'phone_number' => $validatedData['phone_number'] ?? null,
        ];
    
        // Process the avatar only if a new file is provided.
        if ($this->avatar) {
            // Store file temporarily in the "temp" folder
            $tempPath = $this->avatar->store('temp', 'public');
            $fullTempPath = storage_path('app/public/' . $tempPath);
    
            // Use Imagine to process the image (using the GD driver)
            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open($fullTempPath);
    
            // Get image dimensions and calculate the smallest side
            $size = $image->getSize();
            $minDimension = min($size->getWidth(), $size->getHeight());
    
            // Calculate coordinates to crop the image centered
            $startX = ($size->getWidth() - $minDimension) / 2;
            $startY = ($size->getHeight() - $minDimension) / 2;
            $image->crop(new \Imagine\Image\Point($startX, $startY), new \Imagine\Image\Box($minDimension, $minDimension));
    
            // Resize to 300x300 pixels
            $image->resize(new \Imagine\Image\Box(300, 300));
    
            // Ensure the destination directory exists
            $destinationDir = storage_path('app/public/listener-avatars');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }
    
            // Determine original file extension and set final path
            $extension = strtolower($this->avatar->getClientOriginalExtension());
            if ($extension === 'png') {
                // Convert PNG to JPEG for better compression
                $finalFilename = pathinfo(basename($tempPath), PATHINFO_FILENAME) . '.jpg';
            } else {
                // Assume it's JPEG; use the original filename
                $finalFilename = basename($tempPath);
            }
            $finalPath = 'listener-avatars/' . $finalFilename;
            // Save as JPEG with quality 60 (adjust as needed)
            $image->save(storage_path('app/public/' . $finalPath), ['quality' => 60]);
    
            // Remove the temporary file
            \Illuminate\Support\Facades\Storage::disk('public')->delete($tempPath);
    
            // Update the avatar field in the profile data
            $profileData['avatar'] = $finalPath;
        }
    
        // Update or create the ListenerProfile record
        if ($this->listener->listener_profile) {
            $this->listener->listener_profile->update($profileData);
        } else {
            $this->listener->listener_profile()->create($profileData);
        }
    
        session()->flash('message', 'Profile updated successfully.');
    }
    
    

    public function render()
    {
        return view('listener.pages.profile.profile', [
            'listener' => $this->listener,
        ]);
    }
}
