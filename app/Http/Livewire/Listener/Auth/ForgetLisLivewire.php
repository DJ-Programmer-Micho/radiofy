<?php

namespace App\Http\Livewire\Listener\Auth;

use Livewire\Component;
use App\Models\Listener;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class ForgetLisLivewire extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:listeners,email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Find the listener by email
        $listener = Listener::where('email', $this->email)->first();
        if (!$listener) {
            $this->addError('email', 'No account found with that email.');
            return;
        }

        // Generate a secure token
        $token = Str::random(60);

        // Insert or update the token in the password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $this->email],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );

        // Send reset email via Mailtrap (using a Mailable)
        Mail::to($this->email)->send(new PasswordResetMail($token, $this->email));

        session()->flash('message', 'A reset link has been sent. Please check your email.');
    }

    public function render()
    {
        return view('listener.auth.forget.forget');
    }
}
