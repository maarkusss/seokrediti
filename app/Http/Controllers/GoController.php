<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Settings;
use App\Models\Clicks;
use App\Models\Visitors\Visitors;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoController extends Controller
{
    /**
     * Return go view.
     *
     * @param Request $request
     * @param string $lender_id
     * @param string $position
     * @return View
     */
    public function index(Request $request, string $lender_id, string $position)
    {
        $lender = Lenders::where('id', $lender_id)->firstOrFail();
        return $this->processRedirect($request, $lender, (string) $position);
    }

    /**
     * Return go view by lender slug (route_name).
     *
     * @param Request $request
     * @param string $lender_slug
     * @return View|\Illuminate\Http\RedirectResponse
     */
    public function bySlug(Request $request, string $lender_slug)
    {
        $lender = Lenders::where('route_name', $lender_slug)
            ->orWhereRaw('LOWER(route_name) = ?', [strtolower($lender_slug)])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($lender_slug)])
            ->firstOrFail();

        $position = $request->query('p', $lender->position ?? 1);

        return $this->processRedirect($request, $lender, (string) $position);
    }

    protected function processRedirect(Request $request, Lenders $lender, string $position)
    {
        $visitor = Visitors::where('id', $request->cookie('visitor_id'))->first();

        // If affiliate_link starts with external protocol http:// or https://, redirect to it;
        // if affiliate_link is another relative /go/ link or empty, prevent infinite redirect loop
        $isExternalAffiliate = !empty($lender->affiliate_link) && preg_match('#^https?://#i', $lender->affiliate_link);

        if ($this->isBot($request->header('user-agent'))) {
            if ($isExternalAffiliate) {
                return redirect($lender->affiliate_link);
            }
            return redirect('/');
        }

        $utm_source = $request->cookie('utm_source');
        $utm_medium = $request->cookie('utm_medium');
        $utm_campaign = $request->cookie('utm_campaign');
        $utm_content = $request->cookie('utm_content');
        $gclid = $request->cookie('gclid');

        if ($request->utm_source) {
            $utm_source = $request->utm_source;
        }

        if ($request->utm_medium) {
            $utm_medium = $request->utm_medium;
        }

        if ($request->utm_campaign) {
            $utm_campaign = $request->utm_campaign;
        }

        if ($request->utm_content) {
            $utm_content = $request->utm_content;
        }

        if ($request->gclid) {
            $gclid = $request->gclid;
        }

        Clicks::create([
            'visitor_id' => $request->cookie('visitor_id'),
            'lender_id' => $lender->id,
            'referer' => $request->header('referer'),
            'utm_source' => $request->cookie('utm_source'),
            'utm_medium' => $request->cookie('utm_medium'),
            'utm_campaign' => $request->cookie('utm_campaign'),
            'utm_content' => $request->cookie('utm_content'),
            'gclid' => $request->cookie('gclid'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
        ]);

        if ($visitor) {
            $visitor->increment('clicks');
        }

        $lender->increment('clicks');
        $prefix = Settings::where('name', 'prefix')->first();

        $prefixValue = $prefix ? $prefix->value : 'eiro';

        // Build target affiliate redirect URL
        $targetLink = $lender->affiliate_link ?: '';
        if (empty($targetLink) || !preg_match('#^https?://#i', $targetLink)) {
            // Default fallback if no external affiliate URL is configured yet in AdminCP
            $targetLink = 'https://www.eirokrediti.lv';
        }

        $params = [
            's1' => $prefixValue,
            's2' => $utm_campaign,
            's3' => $gclid,
            's4' => $utm_content,
            's5' => $utm_source,
            's6' => $position,
        ];

        $separator = str_contains($targetLink, '?') ? '&' : '?';
        $finalLink = $targetLink . $separator . http_build_query($params);

        return view('go')->with([
            'link' => $finalLink,
            'lender' => $lender,
        ]);
    }

    private function isBot(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $bots = [
            'bot', 'crawler', 'spider', 'crawling',
            'AhrefsBot', 'SemrushBot', 'MJ12bot', 'DotBot',
            'BLEXBot', 'DataForSeoBot', 'PetalBot',
            'GuzzleHttp', 'python-requests', 'curl', 'wget',
            'PostmanRuntime', 'axios', 'libwww-perl', 'Go-http-client',
            'Scrapy', 'HeadlessChrome',
        ];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
