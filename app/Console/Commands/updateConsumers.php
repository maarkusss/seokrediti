<?php

namespace App\Console\Commands;

use App\Models\Admincp\Consumers\Consumers;
use App\Models\Visitors\Visitors;
use Illuminate\Console\Command;

class updateConsumers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consumers:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRON which updates old consumers with token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();

        $consumers = Consumers::whereNotNull('email')->where('sent_to_hoi', 0)->limit(50)->get();

        $i = 0;

        foreach ($consumers as $consumer) {
            $consumer->update([
                'sent_to_hoi' => 1,
            ]);

            $i++;

            // User agent device, os, browser
            $browser = null;
            $os = null;
            $device = null;
            if ($consumer->visitor_id) {
                $visitors = Visitors::where('id', $consumer->visitor_id)->firstOrFail();
                if ($visitors->user_agent) {
                    $browser = $this->get_browser_name($visitors->user_agent);
                    $os = $this->getOS($visitors->user_agent);
                    $device = $this->checkDevice($visitors->user_agent);
                }
            }

            $client->request('PUT', 'https://hoi.fm/api/mail/' . $consumer->email, [
                'query' => [
                    'name' => $consumer->first_name,
                    'token' => $consumer->hoi_token,
                    'device' => $device,
                    'os' => $os,
                    'browser' => $browser,
                ], 'headers' => [
                    'usertoken' => env('HOI_USER_TOKEN'),
                    'projecttoken' => env('HOI_EMAIL_PROJECT_TOKEN'),
                ],
            ]);

            echo "$i email: " . $consumer->email . ', name: ' . $consumer->first_name . ', token: ' . $consumer->hoi_token . ', device: ' . $device . ', os: ' . $os . ', browser: ' . $browser . "\n";
        }

        // Function used to update the consumers in hoi.fm SMS that had no hoi_token

        // $client = new \GuzzleHttp\Client();

        // $consumers = Consumers::WhereNull('hoi_token')->limit(50)->get();

        // $i = 0;

        // foreach ($consumers as $consumer) {

        //     $i++;

        //     $hoi_token = Str::random('6');

        //     // Create an entry in consumers__hoi_token
        //     $consumer_hoi_token = new ConsumersHoiToken();

        //     $consumer_hoi_token->consumer_id = $consumer->id;
        //     $consumer_hoi_token->hoi_token = $hoi_token;

        //     $consumer_hoi_token->save();

        //     // User agent device, os, browser
        //     $browser = null;
        //     $os = null;
        //     $device = null;
        //     if ($consumer->visitor_id) {
        //         $visitors = Visitors::where('id', $consumer->visitor_id)->firstOrFail();
        //         if ($visitors->user_agent) {
        //             $browser = $this->get_browser_name($visitors->user_agent);
        //             $os = $this->getOS($visitors->user_agent);
        //             $device = $this->checkDevice($visitors->user_agent);
        //         }
        //     }

        //     $client->request('PUT', 'https://hoi.fm/api/sms/' . Settings::where('name', 'country_code')->first()->value . $consumer->phone, ['query' => [
        //         'token' => $hoi_token,
        //         'name' => $consumer->first_name,
        //         'device' => $device,
        //         'os' => $os,
        //         'browser' => $browser
        //     ], 'headers' => [
        //         'usertoken' => env('HOI_USER_TOKEN'),
        //         'projecttoken' => env('HOI_SMS_PROJECT_TOKEN'),
        //     ]]);

        //     echo "$i phone: " . $consumer->phone . ", name: " . $consumer->first_name . ", token: " . $hoi_token . ", device: " . $device . ", os: " . $os . ", browser: " . $browser . "\n";

        //     $consumer->update([
        //         'hoi_token' => $hoi_token
        //     ]);
        // }
    }

    public function get_browser_name($user_agent)
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

    public function getOS($user_agent)
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

    public function checkDevice($user_agent)
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
}
