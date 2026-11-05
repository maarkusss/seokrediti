<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class RemoveExtraSegments
{
    public function handle($request, Closure $next)
    {
        $segments = $request->segments();
        dd($segments);

        // Check if there are more than 2 'section' segments
        if (count($segments) > 2) {
            // Remove all segments after the second 'section'
            $segments = array_slice($segments, 0, 3);

            // Redirect to the modified URL
            return Redirect::to(implode('/', $segments), 301);
        }

        return $next($request);
    }
}
