<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SubsAuthCheck
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('subscriber')->check()) {
            return $next($request);
        } else {        
            return redirect('/subs-signin');
        }
    }
}
