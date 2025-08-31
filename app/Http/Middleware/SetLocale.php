<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session or default to Arabic
        $locale = Session::get('locale', 'ar');
        
        // Ensure locale is valid, fallback to Arabic if not
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Set RTL direction for Arabic
        if ($locale === 'ar') {
            view()->share('isRTL', true);
            view()->share('direction', 'rtl');
        } else {
            view()->share('isRTL', false);
            view()->share('direction', 'ltr');
        }
        
        return $next($request);
    }
}
