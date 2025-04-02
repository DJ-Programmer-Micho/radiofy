<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public $selectedLanguages = ['en','ar','ku','asr'];

    public function handle(Request $request, Closure $next): Response
    {
        if (empty($selectedLanguages)) {
            $selectedLanguages = ['en']; // Fallback languages
        }

        App::setLocale($selectedLanguages[0]);
        if ($request->session()->has('applocale')) {
            App::setLocale($request->session()->get('applocale'));
        } else {
           'en';
        }
        
        return $next($request);
    }

    public function setLocale(Request $request)
    {
        $selectedLocale = $request->input('locale');

        // Check if the selected locale is supported
        if (in_array($selectedLocale, $this->selectedLanguages)) {
            // Store the selected language in the session
            $request->session()->put('locale', $selectedLocale);
            $request->session()->put('applocale', $selectedLocale);
            // Set the application locale for the current request
            App::setLocale($selectedLocale);
            // dd($selectedLocale);
        }
        return back();
    } // END FUNCTION
}
