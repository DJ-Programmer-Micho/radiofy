<?php

namespace App\Http\Livewire\Subscriber\Auth;

use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Livewire\Component;
use Imagine\Image\Point;
use Livewire\WithFileUploads;
use App\Models\Subscriber;
use App\Models\SubscriberProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterSubsLivewire extends Component
{
    use WithFileUploads;
    
    public $currentStep;

    // All form fields
    public $firstName;
    public $lastName;
    public $email;
    public $username;
    public $password = '';
    public $password_confirmation = '';
    public $dob;
    public $gender;
    public $avatar;
    
    // New checkbox properties
    public $news = 1; // Optional; default to 1 (checked)
    public $reg = 1;  // Required; default to 1 (checked)
    public $terms;    // Required; must be accepted (user must check)
    public $policy;   // Required; must be accepted (user must check)
    
    public function mount() {
        // Set initial step. (Adjust as needed; for example, start at step 1)
        $this->currentStep = 1;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // Cast gender to an integer when updated
    public function updatedGender($value)
    {
        $this->gender = (int) $value;
        $this->validateOnly('gender');
    }
    
    protected function rules()
    {
        return [
            'firstName'  => 'required|string|max:255',
            'lastName'   => 'required|string|max:255',
            'email'      => 'required|email|unique:subscribers,email',
            'username'   => 'required|string|max:255|unique:subscribers,username',
            'password'   => 'required|string|min:10|confirmed',
            'dob'        => 'required|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'gender'     => 'required|in:1,2,3',
            'reg'        => 'required|accepted',
            'terms'      => 'required|accepted',
            'policy'     => 'required|accepted',
            'avatar'     => 'nullable|image|max:3072',
            // 'news' is optional so we don't require it
        ];
    }

    public function validateStep($step)
    {
        if ($step === 1) {
            $this->validate([
                'firstName' => 'required|string|max:255',
                'lastName'  => 'required|string|max:255',
                'email'     => 'required|email|unique:subscribers,email',
                'username'  => 'required|string|max:255|unique:subscribers,username',
            ]);
        }
        if ($step === 2) {
            $this->validate([
                'password'              => 'required|string|min:10|regex:/^(?=.*[A-Za-z])(?=.*[\d\W]).+$/|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);
        }
        if ($step === 3) {
            $this->validate([
                'dob'    => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required|in:1,2,3',
            ]);
        }
        if ($step === 4) {
            $this->validate([
                'reg'    => 'required|accepted',
                'terms'  => 'required|accepted',
                'policy' => 'required|accepted',
            ]);
        }
    }

    public function nextStep()
    {
        $this->validateStep($this->currentStep);
        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    // Final submission method
    public function register()
    {
        // Validate all fields at once.
        $this->validate();
        $avatarPath = null;
        if ($this->avatar) {
            // Store file temporarily
            $tempPath = $this->avatar->store('temp', 'public');
            $fullTempPath = storage_path('app/public/' . $tempPath);
        
            // Initialize Imagine (using the GD driver)
            $imagine = new Imagine();
            $image = $imagine->open($fullTempPath);
        
            // Get image dimensions and calculate the smallest side
            $size = $image->getSize();
            $minDimension = min($size->getWidth(), $size->getHeight());
        
            // Calculate coordinates to crop the image centered
            $startX = ($size->getWidth() - $minDimension) / 2;
            $startY = ($size->getHeight() - $minDimension) / 2;
            $image->crop(new Point($startX, $startY), new Box($minDimension, $minDimension));
            $image->resize(new Box(300, 300));
            // Determine original file extension
            $extension = strtolower($this->avatar->getClientOriginalExtension());
            if ($extension === 'png') {
                // Convert PNG to JPEG for better compression
                $finalFilename = pathinfo(basename($tempPath), PATHINFO_FILENAME) . '.jpg';
                $finalPath = 'subscriber-avatar/' . $finalFilename;
                // Save as JPEG with quality 75 (adjust as needed)
                $image->save(storage_path('app/public/' . $finalPath), ['quality' => 60]);
            } else {
                // Assume it's already JPEG
                $finalFilename = basename($tempPath);
                $finalPath = 'subscriber-avatar/' . $finalFilename;
                $image->save(storage_path('app/public/' . $finalPath), ['quality' => 60]);
            }
        
            // Remove the temporary file
            Storage::disk('public')->delete($tempPath);
        
            $avatarPath = $finalPath;
        }
        // Use the unified dob input
        $dob = $this->dob;

        // Create the subscribers record.
        $subscriber = Subscriber::create([
            'username' => $this->username,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'dob'      => $dob,
        ]);

        // Create the associated subscriberProfile.
        SubscriberProfile::create([
            'subscriber_id' => $subscriber->id,
            'first_name'  => $this->firstName,
            'last_name'   => $this->lastName,
            'dob'         => $dob,
            'gender'      => $this->gender,
            'news'        => $this->news,
            'reg'         => $this->reg,
            'terms'       => $this->terms,
            'policy'      => $this->policy,
            'avatar'      => $avatarPath,
        ]);

        // Log the user in.
        Auth::guard('subscriber')->login($subscriber);

        // Redirect to dashboard with a success message.
        return redirect()->route('subscriber.dashboard');
    }

    public function render()
    {
        return view('subscriber.auth.register.register-form', [
          
        ]);
    }
}
