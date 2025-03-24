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
    /**
     * Show the listener sign in form.
     */
    public function signIn()
    {
        return view('listener.auth.signin-one');
    }
    /**
     * Process the listener sign in request.
     */
    public function handleSignIn(Request $request)
    { 
        // dd($request->all());
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:8',
            // 'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
    
        // Map form field names to the correct database column names
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

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

    /**
     * Show the registration wizard form.
     */
    public function signUp()
    {
        return view('listener.auth.register.index'); // Blade view for the registration wizard
    }


    /**
     * Log out the listener.
     */
    public function signOut()
    {
        Auth::guard('listener')->logout();
        return redirect()->route('lis.signin');
    }
}
