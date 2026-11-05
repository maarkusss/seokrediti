<?php

namespace App\Http\Middleware;

use App\Models\Admincp\Lenders\LendersSorting;
use Closure;

class Campaigns
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
        if ($request->utm_campaign) {
            LendersSorting::firstOrCreate([
                'campaign_name' => $request->utm_campaign,
            ]);
        }

        return $next($request);
    }
}
