<?php

namespace App\Http\Livewire\Subscriber\Auth;

use Livewire\Component;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordSubResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgetSubsLivewire extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:subscribers,email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        // Find the Subscriber by email
        $subscriber = Subscriber::where('email', $this->email)->first();
        if (!$subscriber) {
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
        Mail::to($this->email)->send(new PasswordSubResetMail($token, $this->email));

        session()->flash('message', 'A reset link has been sent. Please check your email.');
    }

    public function render()
    {
        return view('subscriber.auth.forget.forget');
    }
}
