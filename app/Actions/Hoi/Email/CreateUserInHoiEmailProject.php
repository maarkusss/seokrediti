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
        function get_browser_name($user_agent)
        {
            // Make case insensitive.
            $t = strtolower($user_agent);
            // If the string *starts* with the string, strpos returns 0 (i.e., FALSE). Do a ghetto hack and start with a space.
            // "[strpos()] may return Boolean FALSE, but may also return a non-Boolean value which evaluates to FALSE."
            //     http://php.net/manual/en/function.strpos.php
            $t = ' ' . $t;
            // Humans / Regular Users
            if (strpos($t, 'opera') || strpos($t, 'opr/')) {
                return 'Opera';
            } elseif (strpos($t, 'edge') || strpos($t, 'Edg')) {
                return 'Edge';
            } elseif (strpos($t, 'chrome')) {
                return 'Chrome';
            } elseif (strpos($t, 'safari')) {
                return 'Safari';
            } elseif (strpos($t, 'firefox')) {
                return 'Firefox';
            } elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) {
                return 'Internet Explorer';
            }
            // Search Engines
            elseif (strpos($t, 'google')) {
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
            }
            // Common Tools and Bots
            elseif (strpos($t, 'mj12bot')) {
                return '[Bot] Majestic';
            } elseif (strpos($t, 'ahrefs')) {
                return '[Bot] Ahrefs';
            } elseif (strpos($t, 'semrush')) {
                return '[Bot] SEMRush';
            } elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) {
                return '[Bot] Moz or OpenSiteExplorer';
            } elseif (strpos($t, 'frog') || strpos($t, 'screaming')) {
                return '[Bot] Screaming Frog';
            }
            // Miscellaneous
            elseif (strpos($t, 'facebook')) {
                return '[Bot] Facebook';
            } elseif (strpos($t, 'pinterest')) {
                return '[Bot] Pinterest';
            }
            // Check for strings commonly used in bot user agents
            elseif (
                strpos($t, 'crawler') || strpos($t, 'api') ||
                strpos($t, 'spider') || strpos($t, 'http') ||
                strpos($t, 'bot') || strpos($t, 'archive') ||
                strpos($t, 'info') || strpos($t, 'data')
            ) {
                return '[Bot] Other';
            }

            return 'Unknown';
        }

        function getOS($user_agent)
        {
            $os_platform = 'Unknown';
            $os_array = [
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
            foreach ($os_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                    $os_platform = $value;
                }
            }

            return $os_platform;
        }

        function checkDevice($user_agent)
        {
            if ($user_agent) {
                if (is_numeric(strpos(strtolower($user_agent), 'mobile'))) {
                    return is_numeric(strpos(strtolower($user_agent), 'tablet')) ? 'Tablet' : 'Phone';
                } else {
                    return 'Desktop';
                }
            } else {
                return 'Unknown';
            }
        }

        if ($_SERVER['HTTP_USER_AGENT']) {
            $device = urlencode(checkDevice($_SERVER['HTTP_USER_AGENT']));
            $os = urlencode(getOS($_SERVER['HTTP_USER_AGENT']));
            $browser = urlencode(get_browser_name($_SERVER['HTTP_USER_AGENT']));
        }

        return [
            'device' => $device ? $device : '',
            'os' => $os ? $os : '',
            'browser' => $browser ? $browser : '',
        ];
    }
}
