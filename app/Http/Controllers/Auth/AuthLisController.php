<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Listener;
use Illuminate\Http\Request;
use App\Models\ListenerProfile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthLisController extends Controller
{
    public function signUp()
    {
        return view('listener.auth.register.index'); 
    }

    public function signIn()
    {
        return view('listener.auth.signin-one');
    }

    public function handleSignIn(Request $request)
    { 
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:6',
            // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        
        // Map form field names to the correct database column names
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // dd($request->all());
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];
    
        if (Auth::guard('listener')->attempt($credentials)) {
            // Authentication successful
            return redirect()->route('listener.home', ['locale' => app()->getLocale()]); // Adjust the route as needed
        }
    
        // If authentication fails
        return back()->withErrors([
            'login' => 'Invalid email or password.',
        ])->withInput();
    }

    public function forgetPassword()
    {
        return view('listener.auth.forget.index'); 
    }
    public function newPassword()
    {
        return view('listener.auth.password.index'); 
    }

    public function signOut()
    {
        Auth::guard('listener')->logout();
        return redirect()->route('lis.signin');
    }
}
