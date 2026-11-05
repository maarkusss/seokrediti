<?php

namespace App\Actions\Hoi\Email;

use App\Models\Admincp\Consumers\Consumers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserInHoiEmailProject
{
    use AsAction;

    /**
     * Create a user in Hoi e-mail project.
     *
     * @param  Consumers $consumer
     * @return void
     */
    public function handle(Consumers $consumer): void
    {
        if (env('HOI_USER_TOKEN') && env('HOI_EMAIL_PROJECT_TOKEN')) {
            // User agent device, os, browser
            $user_agent = $this->getUserUserAgentData();

            Http::withHeaders([
                'usertoken' => env('HOI_USER_TOKEN'),
                'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
            ])->post('https://hoi.fm/api/mail', [
                'mail' => $consumer->email,
                'fingerprint' => $consumer->fingerprint,
                'lang' => App::getLocale(),
                'registration' => 1,
                'token' => $consumer->hoi_token,
                'device' => $user_agent['device'],
                'os' => $user_agent['os'],
                'browser' => $user_agent['browser'],
            ]);
        }

        // GoodAff
        if (env('APP_ENV') == 'production') {
            if ($consumer->utm_source == 'goodaff' && env('GOODAFF_CAMPAIGN_ID')) {
                Http::get('https://postback.goodaff.com', [
                    'campaign_id' => env('GOODAFF_CAMPAIGN_ID'),
                    'transaction_id' => $consumer->id,
                    'click_id' => $consumer->utm_content,
                    'type' => 'cpl',
                    'status' => 'A',
                ]);
            }
        }
    }

    /**
     * Get the current user's user agent data.
     *
     * @return array
     */
    private function getUserUserAgentData(): array
    {
        $userAgent = (string) request()->header('User-Agent', $_SERVER['HTTP_USER_AGENT'] ?? '');

        if ($userAgent === '') {
            return [
                'device' => '',
                'os' => '',
                'browser' => '',
            ];
        }

        return [
            'device' => urlencode($this->checkDevice($userAgent)),
            'os' => urlencode($this->getOS($userAgent)),
            'browser' => urlencode($this->getBrowserName($userAgent)),
        ];
    }

    private function getBrowserName(string $userAgent): string
    {
        $t = ' ' . strtolower($userAgent);

        if (strpos($t, 'opera') || strpos($t, 'opr/')) {
            return 'Opera';
        } elseif (strpos($t, 'edge') || strpos($t, 'edg')) {
            return 'Edge';
        } elseif (strpos($t, 'chrome')) {
            return 'Chrome';
        } elseif (strpos($t, 'safari')) {
            return 'Safari';
        } elseif (strpos($t, 'firefox')) {
            return 'Firefox';
        } elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) {
            return 'Internet Explorer';
        } elseif (strpos($t, 'google')) {
            return '[Bot] Googlebot';
        } elseif (strpos($t, 'bing')) {
            return '[Bot] Bingbot';
        } elseif (strpos($t, 'slurp')) {
            return '[Bot] Yahoo! Slurp';
        } elseif (strpos($t, 'duckduckgo')) {
            return '[Bot] DuckDuckBot';
        } elseif (strpos($t, 'baidu')) {
            return '[Bot] Baidu';
        } elseif (strpos($t, 'yandex')) {
            return '[Bot] Yandex';
        } elseif (strpos($t, 'sogou')) {
            return '[Bot] Sogou';
        } elseif (strpos($t, 'exabot')) {
            return '[Bot] Exabot';
        } elseif (strpos($t, 'msn')) {
            return '[Bot] MSN';
        } elseif (strpos($t, 'mj12bot')) {
            return '[Bot] Majestic';
        } elseif (strpos($t, 'ahrefs')) {
            return '[Bot] Ahrefs';
        } elseif (strpos($t, 'semrush')) {
            return '[Bot] SEMRush';
        } elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) {
            return '[Bot] Moz or OpenSiteExplorer';
        } elseif (strpos($t, 'frog') || strpos($t, 'screaming')) {
            return '[Bot] Screaming Frog';
        } elseif (strpos($t, 'facebook')) {
            return '[Bot] Facebook';
        } elseif (strpos($t, 'pinterest')) {
            return '[Bot] Pinterest';
        } elseif (
            strpos($t, 'crawler') || strpos($t, 'api') ||
            strpos($t, 'spider') || strpos($t, 'http') ||
            strpos($t, 'bot') || strpos($t, 'archive') ||
            strpos($t, 'info') || strpos($t, 'data')
        ) {
            return '[Bot] Other';
        }

        return 'Unknown';
    }

    private function getOS(string $userAgent): string
    {
        $osPlatform = 'Unknown';
        $osArray = [
            '/windows nt 10/i' => 'Windows',
            '/windows nt 6.3/i' => 'Windows',
            '/windows nt 6.2/i' => 'Windows',
            '/windows nt 6.1/i' => 'Windows',
            '/windows nt 6.0/i' => 'Windows',
            '/windows nt 5.2/i' => 'Windows',
            '/windows nt 5.1/i' => 'Windows',
            '/windows xp/i' => 'Windows',
            '/windows nt 5.0/i' => 'Windows',
            '/windows me/i' => 'Windows',
            '/win98/i' => 'Windows',
            '/win95/i' => 'Windows',
            '/win16/i' => 'Windows',
            '/macintosh|mac os x/i' => 'Mac OS',
            '/mac_powerpc/i' => 'Mac OS',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Linux',
            '/iphone/i' => 'iOS',
            '/ipod/i' => 'iPodOS',
            '/ipad/i' => 'iPadOS',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        ];

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }

        return $osPlatform;
    }

    private function checkDevice(string $userAgent): string
    {
        $lower = strtolower($userAgent);
        if (strpos($lower, 'mobile') !== false) {
            return strpos($lower, 'tablet') !== false ? 'Tablet' : 'Phone';
        }

        return 'Desktop';
    }
}
