<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\Settings;
use App\Models\Clicks;
use App\Models\Visitors\Visitors;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoController extends Controller
{
    /**
     * Return go view.
     *
     * @param Request $request
     * @param string $lender_id
     * @param string $position
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
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

        $isExternalAffiliate = !empty($lender->affiliate_link) && preg_match('#^https?://#i', $lender->affiliate_link);

        if ($this->isBot($request->header('user-agent'))) {
            if ($isExternalAffiliate) {
                return redirect($lender->affiliate_link)->header('X-Robots-Tag', 'noindex, nofollow');
            }
            return redirect('/')->header('X-Robots-Tag', 'noindex, nofollow');
        }

        $utm_source = $request->utm_source ?: $request->cookie('utm_source');
        $utm_medium = $request->utm_medium ?: $request->cookie('utm_medium');
        $utm_campaign = $request->utm_campaign ?: $request->cookie('utm_campaign');
        $utm_content = $request->utm_content ?: $request->cookie('utm_content');
        $gclid = $request->gclid ?: $request->cookie('gclid');

        Clicks::create([
            'visitor_id' => $request->cookie('visitor_id'),
            'lender_id' => $lender->id,
            'referer' => $request->header('referer'),
            'utm_source' => $utm_source,
            'utm_medium' => $utm_medium,
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'gclid' => $gclid,
            'ip' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
        ]);

        if ($visitor) {
            $visitor->increment('clicks');
        }

        $lender->increment('clicks');
        $prefix = Settings::where('name', 'prefix')->first();

        $fallbackPrefix = Str::slug(brand_name(), '') ?: 'portal';
        $prefixValue = ($prefix && !empty($prefix->value) && stripos($prefix->value, 'eiro') === false)
            ? $prefix->value
            : $fallbackPrefix;

        $targetLink = $lender->affiliate_link ?: '';
        if (empty($targetLink) || !preg_match('#^https?://#i', $targetLink)) {
            $targetLink = url('/');
        }

        $params = array_filter([
            's1' => $prefixValue,
            's2' => $utm_campaign,
            's3' => $gclid,
            's4' => $utm_content,
            's5' => $utm_source,
            's6' => $position,
        ], fn ($v) => $v !== null && $v !== '');

        $separator = str_contains($targetLink, '?') ? '&' : '?';
        $finalLink = !empty($params) ? ($targetLink . $separator . http_build_query($params)) : $targetLink;

        return response()
            ->view('go', [
                'link' => $finalLink,
                'lender' => $lender,
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow');
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
