<?php

namespace App\Http\Middleware;

use App\Models\Admincp\RedirectLink;
use Closure;
use Illuminate\Http\Request;

class RedirectLinks
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($redirect_link = RedirectLink::where('url_from', $request->getRequestUri())->first()) {
            return redirect()->to($redirect_link->url_to, 301);
        }

        return $next($request);
    }
}
