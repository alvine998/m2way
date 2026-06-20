<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: 1. Query param, 2. Session, 3. Cookie, 4. Default config
        $locale = $request->query('lang', 
            Session::get('locale', 
                $request->cookie('locale', config('app.locale'))
            )
        );

        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
            date_default_timezone_set($locale === 'id' ? 'Asia/Jakarta' : 'UTC');
            Session::put('locale', $locale);
            Cookie::queue('locale', $locale, 60 * 24 * 365); // 1 year
        }

        return $next($request);
    }
}
