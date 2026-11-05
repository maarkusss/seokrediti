<?php

namespace App\Http\Middleware;

use Closure;

class ActiveCookie
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
        if ($request->hasCookie('valid')) {
            if ($request->segment(2) !== 'offer') {
                return redirect(route('offer'));
            }
        }

        return $next($request);
    }
}
