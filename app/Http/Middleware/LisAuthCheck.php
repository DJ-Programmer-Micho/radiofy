<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LisAuthCheck
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('listener')->check()) {
            return $next($request);
        } else {        
            return redirect('/account-mradiofy');
        }
    }
}
