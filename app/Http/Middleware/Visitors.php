<?php

namespace App\Http\Middleware;

use Closure;

class Visitors
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
        $userAgent = $request->header('user-agent', '');

        if (stripos($userAgent, 'uptime') !== false || stripos($userAgent, 'bot') !== false) {
            return $next($request);
        }
        
        $response = $next($request);

        foreach ($request->request as $index => $parameter) {
            if ($parameter == null && !isset($request->utm_campaign)) {
                $request->utm_campaign = $index;
            }
        }

        if ($request->utm_source) {
            $response->withCookie(cookie()->forever('utm_source', $request->utm_source));
        }

        if ($request->utm_medium) {
            $response->withCookie(cookie()->forever('utm_medium', $request->utm_medium));
        }

        if ($request->utm_campaign) {
            $response->withCookie(cookie()->forever('utm_campaign', $request->utm_campaign));
        }

        if ($request->utm_content) {
            $response->withCookie(cookie()->forever('utm_content', $request->utm_content));
        }

        if ($request->gclid) {
            $response->withCookie(cookie()->forever('gclid', $request->gclid));
        }

        if ($request->hasCookie('visitor_id')) {
            return $response;
        } else {
            $visitor = new \App\Models\Visitors\Visitors();

            $visitor->user_agent = $request->header('user-agent');
            $visitor->ip = $request->ip();
            $visitor->utm_source = $request->utm_source;
            $visitor->utm_medium = $request->utm_medium;
            $visitor->utm_campaign = $request->utm_campaign;
            $visitor->utm_content = $request->utm_content;
            $visitor->gclid = $request->gclid;
            $visitor->from = $request->header('referer');
            $visitor->to = $request->fullUrl();

            $visitor->save();

            return $response->withCookie(cookie()->forever('visitor_id', $visitor->id));
        }
    }
}
