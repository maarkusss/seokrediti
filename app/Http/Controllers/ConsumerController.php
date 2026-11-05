<?php

namespace App\Http\Controllers;

use App\Mail\LenderOffer;
use App\Models\Admincp\Consumers\Consumers;
use App\Models\Admincp\Consumers\ConsumersHoiToken;
use App\Models\Admincp\Settings;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ConsumerController extends Controller
{
    public function postOffer(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        if ($consumer = Consumers::where(['email' => $request->email])->first()) {
            if (!$consumer->subscribed) {
                $consumer->update([
                    'subscribed' => 1,
                ]);
            }

            $hoi_token = Str::random('6');

            $consumer->update([
                'utm_source' => $request->cookie('utm_source'),
                'utm_campaign' => $request->cookie('utm_campaign'),
                'utm_medium' => $request->cookie('utm_medium'),
                'utm_content' => $request->cookie('utm_content'),
                'gclid' => $request->cookie('gclid'),
                'hoi_token' => $hoi_token,
                'phone' => $request->phone,
            ]);

            // Create an entry in consumers__hoi_token
            $consumer_hoi_token = new ConsumersHoiToken();

            $consumer_hoi_token->consumer_id = $consumer->id;
            $consumer_hoi_token->hoi_token = $hoi_token;

            $consumer_hoi_token->save();

            // User agent device, os, browser
            $user_agent = $this->getConsumerUserAgentData();

            if (!$consumer->email_verified_at) {
                $consumer->sendEmailVerificationNotification();
            } else {
                Mail::to($consumer)->send(new LenderOffer($consumer));
            }

            // Delete and then register the phone number again
            if (env('APP_ENV') == 'production' && env('HOI_USER_TOKEN') && env('HOI_SMS_PROJECT_TOKEN')) {
                $client->request('DELETE', 'https://hoi.fm/api/sms/' . (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone, [
                    'query' => [
                        'number' => (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone,
                    ],
                    'headers' => [
                        'usertoken' => env('HOI_USER_TOKEN'),
                        'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
                    ],
                ]);
                $client->request('POST', 'https://hoi.fm/api/sms', [
                    'query' => [
                        'name' => $request->name,
                        'number' => (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone,
                        'registration' => 1,
                        'token' => $hoi_token,
                        'device' => $user_agent['device'],
                        'os' => $user_agent['os'],
                        'browser' => $user_agent['browser'],
                    ],
                    'headers' => [
                        'usertoken' => env('HOI_USER_TOKEN'),
                        'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
                    ],
                ]);
            }
        } else {
            $validated = $request->validate([
                'email' => 'required|email',
                'phone' => 'required|digits:9',
            ]);

            $user = new Consumers();

            $hoi_token = Str::random('6');

            $user->phone = $validated['phone'];
            $user->email = $validated['email'];
            $user->visitor_id = $request->cookie('visitor_id');
            $user->utm_source = $request->cookie('utm_source');
            $user->utm_campaign = $request->cookie('utm_campaign');
            $user->utm_medium = $request->cookie('utm_medium');
            $user->utm_content = $request->cookie('utm_content');
            $user->gclid = $request->cookie('gclid');
            $user->ip = $request->ip();
            $user->subscribed = 1;
            $user->term = $request->term;
            $user->amount = $request->amount;
            $user->first_name = $request->name;
            $user->hoi_token = $hoi_token;

            $user->save();

            // Create an entry in consumers__hoi_token
            $consumer_hoi_token = new ConsumersHoiToken();

            $consumer_hoi_token->consumer_id = $user->id;
            $consumer_hoi_token->hoi_token = $hoi_token;

            $consumer_hoi_token->save();

            // User agent device, os, browser
            $user_agent = $this->getConsumerUserAgentData();

            if (env('APP_ENV') == 'production' && env('HOI_USER_TOKEN') && env('HOI_SMS_PROJECT_TOKEN')) {
                $client->request('POST', 'https://hoi.fm/api/sms', [
                    'query' => [
                        'name' => $request->name,
                        'number' => (Settings::where('name', 'country_code')->first()?->value ?? '371') . $user->phone,
                        'registration' => 1,
                        'token' => $user->hoi_token,
                        'device' => $user_agent['device'],
                        'os' => $user_agent['os'],
                        'browser' => $user_agent['browser'],
                    ],
                    'headers' => [
                        'usertoken' => env('HOI_USER_TOKEN'),
                        'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
                    ],
                ]);
            }

            if ($request->cookie('utm_source') == 'goodaff' && env('GOODAFF_CAMPAIGN_ID')) {
                $client->request('GET', 'https://postback.goodaff.com', ['query' => [
                    'campaign_id' => env('GOODAFF_CAMPAIGN_ID'),
                    'transaction_id' => $user->id,
                    'click_id' => $request->cookie('utm_content'),
                    'type' => 'cpl',
                    'status' => 'P',
                ]]);
            }

            $user->sendEmailVerificationNotification();
        }

        if ($request->segment(2) == 'top-krediti-v-internete') {
            return redirect()->back();
        }

        return redirect(route('offer') . '?registered=1');
    }

    public function consumerTokenRedirect($hoi_token, $sending_token = null)
    {
        $consumer_hoi_token = ConsumersHoiToken::where('hoi_token', $hoi_token)->first();

        if ($consumer_hoi_token) {
            $consumer_hoi_token->update([
                'clicked' => 1,
                'clicked_date' => date('Y-m-d H:i:s'),
            ]);

            $consumer = Consumers::where('id', $consumer_hoi_token->consumer_id)->first();

            if ($consumer) {
                $new_hoi_token = Str::random('6');

                // Update the consumer with a new token
                $consumer->update([
                    'hoi_token' => $new_hoi_token,
                ]);

                // Create an entry in consumers__hoi_token
                $consumer_hoi_token = new ConsumersHoiToken();

                $consumer_hoi_token->consumer_id = $consumer->id;
                $consumer_hoi_token->hoi_token = $new_hoi_token;

                $consumer_hoi_token->save();

                // Delete and then register the phone number again
                $client = new Client();
                $user_agent = $this->getConsumerUserAgentData();

                if (env('APP_ENV') == 'production' && env('HOI_USER_TOKEN') && env('HOI_SMS_PROJECT_TOKEN')) {
                    $client->request('DELETE', 'https://hoi.fm/api/sms/' . (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone, [
                        'query' => [
                            'number' => (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone,
                        ],
                        'headers' => [
                            'usertoken' => env('HOI_USER_TOKEN'),
                            'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
                        ],
                    ]);
                    $client->request('POST', 'https://hoi.fm/api/sms', [
                        'query' => [
                            'name' => $consumer->first_name,
                            'number' => (Settings::where('name', 'country_code')->first()?->value ?? '371') . $consumer->phone,
                            'registration' => 1,
                            'token' => $new_hoi_token,
                            'device' => $user_agent['device'],
                            'os' => $user_agent['os'],
                            'browser' => $user_agent['browser'],
                        ],
                        'headers' => [
                            'usertoken' => env('HOI_USER_TOKEN'),
                            'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
                        ],
                    ]);
                }

                // Get the utm parameters from the consumer
                $utm_source = $consumer->utm_source;
                $utm_medium = $consumer->utm_medium;
                $utm_campaign = $sending_token ?: $consumer->utm_campaign;
                $utm_content = $consumer->utm_content;
                $gclid = $consumer->gclid;

                return redirect('/uk/offer?utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content . '&gclid=' . $gclid);
            } else {
                return redirect('/uk/offer');
            }
        } else {
            return redirect('/uk/offer');
        }
    }

    public function getConsumerUserAgentData(): array
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
