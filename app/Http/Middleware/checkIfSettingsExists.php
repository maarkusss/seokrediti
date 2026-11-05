<?php

namespace App\Http\Middleware;

use App\Models\Admincp\Settings;
use Closure;

class checkIfSettingsExists
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
        $settings = Settings::where('name', 'lang')->exists();

        if ($settings) {
            return $next($request);
        } else {
            return redirect(route('setup.index'));
        }
    }
}
