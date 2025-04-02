<?php

namespace App\Http\Livewire\Listener\Auth;

use Livewire\Component;
use App\Models\Listener;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordLisLivewire extends Component
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

        // Find the listener and update the password
        $listener = Listener::where('email', $this->email)->first();
        if (!$listener) {
            $this->addError('newPassword', 'No user found for this email.');
            return;
        }

        $listener->password = Hash::make($this->newPassword);
        $listener->save();

        // Remove the reset token
        DB::table('password_resets')->where('email', $this->email)->delete();

        session()->flash('message', 'Password reset successfully.');
        // Optionally, you can redirect to the login page here.
    }

    public function render()
    {
        return view('listener.auth.password.password');
    }
}
