<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthSubsController extends Controller
{
    public function signIn(){
        return view('subscriber.auth.signin-one');
    }
    
    public function handleSignIn(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt login
        // Authentication successful
        if (Auth::guard('subscriber')->attempt($request->only('email', 'password'))) {
            return redirect()->route('subscriber.dashboard', ['locale' => app()->getLocale()]);
        }
        // If authentication fails
        return back()->withErrors([
            'email' => 'Invalid email or password 00.',
        ])->withInput();
    }
    
    public function signUp(){
        return view('subscriber.auth.register.index');
    }

    public function forgetPassword()
    {
        return view('subscriber.auth.forget.index'); 
    }
    public function newPassword()
    {
        return view('subscriber.auth.password.index'); 
    }

    public function signOut(){
        Auth::guard('subscriber')->logout(); // Log out the user
        return redirect()->route('listener.home');
    }
}
