<?php

namespace App\Http\Livewire\Subscriber\Auth;

use Livewire\Component;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordSubsLivewire extends Component
{
    public $token;
    public $email;
    public $newPassword;
    public $newPassword_confirmation;

    protected $rules = [
        'newPassword' => [
            'required',
            'string',
            'min:10',
            'regex:/^(?=.*[A-Za-z])(?=.*[\d\W]).+$/',
            'confirmed'
        ],
    ];

    public function mount($token, $email)
    {
        $this->token = $token;
        $this->email = urldecode($email);
    }
    

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetPassword()
    {
        $this->validate();

        // Validate the token exists for this email
        $record = DB::table('password_resets')
                    ->where('email', $this->email)
                    ->where('token', $this->token)
                    ->first();
        if (!$record) {
            $this->addError('newPassword', 'Invalid or expired token.');
            return;
        }

        // (Optional) Check token expiration (e.g., token is valid for 60 minutes)

        // Find the Subscriber and update the password
        $subscriber = Subscriber::where('email', $this->email)->first();
        if (!$subscriber) {
            $this->addError('newPassword', 'No user found for this email.');
            return;
        }

        $subscriber->password = Hash::make($this->newPassword);
        $subscriber->save();

        // Remove the reset token
        DB::table('password_resets')->where('email', $this->email)->delete();

        session()->flash('message', 'Password reset successfully.');
        // Optionally, you can redirect to the login page here.
    }

    public function render()
    {
        return view('subscriber.auth.password.password');
    }
}
