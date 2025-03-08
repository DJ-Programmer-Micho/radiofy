<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signIn(){
        return view('admin.auth.signin-one');
    }

    public function handleSignIn(Request $request){
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt login
        // Authentication successful
        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return redirect()->route('super.dashboard', ['locale' => app()->getLocale()]);
        }
        // If authentication fails
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    public function signOut(){
        Auth::guard('admin')->logout(); // Log out the user
        return redirect()->to('/auth-logout');
    }
}
