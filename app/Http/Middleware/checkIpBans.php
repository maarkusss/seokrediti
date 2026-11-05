<?php

namespace App\Http\Middleware;

use App\Models\Admincp\Bans\Bans;
use Closure;

class checkIpBans
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
        $check = Bans::where('ip', $request->ip())->exists();

        if ($check) {
            return abort(403);
        }

        return $next($request);
    }
}
