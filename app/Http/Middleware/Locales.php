<?php

namespace App\Http\Middleware;

use App\Models\Admincp\Settings;
use Closure;
use Illuminate\Support\Facades\App;

class Locales
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $firstSegment = $request->segment(1);
        $secondSegment = $request->segment(2);

        // Redirect bare /admincp or /admincp/... to /lv/admincp/...
        if ($firstSegment === 'admincp') {
            $path = $request->path();
            $queryString = $request->getQueryString();
            $cleanPath = '/lv/' . $path . ($queryString ? '?' . $queryString : '');
            return redirect($cleanPath, 301);
        }

        // Redirect bare /login to /lv/login
        if ($firstSegment === 'login') {
            $queryString = $request->getQueryString();
            return redirect('/lv/login' . ($queryString ? '?' . $queryString : ''), 301);
        }

        // Check if this request is for admin or auth
        $isAdminOrAuth = in_array($secondSegment, ['admincp', 'login', 'logout', 'register', 'forgot-password', 'reset-password']);

        // 301 redirect legacy public /lv and /lv/... to clean root-based canonical URLs
        // Keep /lv locale prefix for AdminCP and Auth routes
        if ($firstSegment === 'lv' && !$isAdminOrAuth) {
            $segments = $request->segments();
            array_shift($segments);
            $cleanPath = '/' . implode('/', $segments);
            $queryString = $request->getQueryString();
            if ($queryString) {
                $cleanPath .= '?' . $queryString;
            }
            return redirect($cleanPath, 301);
        }

        if ($firstSegment === 'ru') {
            App::setLocale('ru');
        } else {
            App::setLocale('lv');
        }

        return $next($request);
    }

    public function locales()
    {
        $locales = [];

        foreach (Settings::where('name', 'lang')->get() as $lang) {
            $locales[] = $lang->value;
        }

        return $locales;
    }
}
