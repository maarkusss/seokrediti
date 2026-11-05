<?php

namespace App\Http\Middleware;

use Closure;

class CustomHeaders
{
    public function handle($request, Closure $next)
    {
        header_remove('x-powered-by');

        return $next($request);
    }
}
