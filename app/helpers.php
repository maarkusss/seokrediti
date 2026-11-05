<?php

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Settings;
use Illuminate\Support\Facades\Request;
use App\Models\InternalLog;

if (!function_exists('strip_phone_number_formatting')) {
    function strip_phone_number_formatting(string $phone_number, ?string $extension = ''): string
    {
        if (empty($extension)) {
            $extension = '+' . phone_extension();
        }

        if (!str_starts_with($extension, '+')) {
            $extension = '+' . $extension;
        }

        return str_replace(['(', ')', $extension], '', $phone_number);
    }
}

if (!function_exists('get_browser_name')) {
    function get_browser_name($user_agent): string
    {
        // Make case-insensitive.
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
        } // Search Engines
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
        } // Common Tools and Bots
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
        } // Miscellaneous
        elseif (strpos($t, 'facebook')) {
            return '[Bot] Facebook';
        } elseif (strpos($t, 'pinterest')) {
            return '[Bot] Pinterest';
        } // Check for strings commonly used in bot user agents
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
}

if (!function_exists('get_os')) {
    function get_os($user_agent): string
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
}

if (!function_exists('check_device')) {
    function check_device($user_agent): string
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

if (!function_exists('get_consumer_user_agent_data')) {
    function get_consumer_user_agent_data(): array
    {
        $http_user_agent = request()->server('HTTP_USER_AGENT');
        if ($http_user_agent) {
            $device = urlencode(check_device($http_user_agent));
            $os = urlencode(get_os($http_user_agent));
            $browser = urlencode(get_browser_name($http_user_agent));
        }

        return [
            'device' => $device ?? '',
            'os' => $os ?? '',
            'browser' => $browser ?? '',
        ];
    }
}

if (!function_exists('is_bot')) {
    function is_bot(): bool
    {
        if (
            stristr(request()->header('user-agent'), 'bot') ||
            stristr(request()->header('user-agent'), 'crawler') ||
            stristr(request()->header('user-agent'), 'Skype')
        ) {
            return true;
        }

        return false;
    }
}

// if (!function_exists('internal_log')) {
//     function internal_log(
//         string $provider,
//         string $method,
//         $email = null,
//         $phone = null,
//         $sender = null,
//         $message = null,
//         $request = null,
//         $response = null
//     ): ?InternalLog {
//         if (config('app.internal_log_enabled') === true) {
//             try {
//                 $internal_log = new InternalLog();
//                 $internal_log->provider = $provider;
//                 $internal_log->env = app()->environment();
//                 $internal_log->method = $method;
//                 $internal_log->email = $email ?? '';
//                 $internal_log->phone = $phone ?? '';
//                 $internal_log->sender = $sender ?? '';
//                 $internal_log->message = $message ?? '';
//                 $internal_log->request = !empty($request) ? $request : null;
//                 $internal_log->response = !empty($response) ? $response : null;
//                 $internal_log->save();

//                 return $internal_log;
//             } catch (Throwable $e) {
//             }
//         }

//         return null;
//     }
// }

if (!function_exists('inactive_locales')) {
    function inactive_locales(): array
    {
        return collect(available_locales())
            ->reject(fn ($locale) => $locale == app()->getLocale())
            ->toArray();
    }
}

if (!function_exists('default_locale')) {
    function default_locale(): string
    {
        $available_locales = available_locales();

        return $available_locales[0];
    }
}

if (!function_exists('available_locales')) {
    function available_locales(): array
    {
        $available_locales = [];
        $settings = Settings::query()
            ->where('name', 'lang')
            ->orderBy('value', 'DESC')
            ->select('value')
            ->get();

        if ($settings->isNotEmpty()) {
            foreach ($settings as $setting) {
                if (!empty($setting->value)) {
                    $available_locales[] = $setting->value;
                }
            }
        }

        return $available_locales;
    }
}

if (!function_exists('available_permissions')) {
    function available_permissions(string $type = 'add'): array
    {
        $available_permissions = [];

        if ($type === 'add') {
            $available_permissions['admins'] = [
                'view admins',
                'edit admins',
                'add admins',
                'delete admins',
            ];
            $available_permissions['bans'] = [
                'view bans',
                'edit bans',
                'add bans',
                'delete bans',
            ];
            $available_permissions['settings'] = [
                'view settings',
                'edit settings',
            ];
        } elseif ($type === 'edit') {
            $available_permissions['admins'] = [
                'view admins',
                'edit admins',
                'add admins',
                'delete admins',
            ];
            $available_permissions['bans'] = [
                'view bans',
                'edit bans',
                'add bans',
                'delete bans',
            ];
            $available_permissions['sections'] = [
                'view sections',
                'edit sections',
                'add sections',
                'delete sections',
            ];
            $available_permissions['visitors'] = [
                'view visitors',
            ];
            $available_permissions['settings'] = [
                'view settings',
                'edit settings',
            ];
            $available_permissions['loan types'] = [
                'view loantypes',
                'edit loantypes',
                'add loantypes',
                'delete loantypes',
            ];
            $available_permissions['lenders'] = [
                'view lenders',
                'edit lenders',
                'add lenders',
                'delete lenders',
            ];
            $available_permissions['consumers'] = [
                'view consumers',
                'delete consumers',
            ];
            $available_permissions['statistics'] = [
                'view statistics',
            ];
        }

        return $available_permissions;
    }
}

if (!function_exists('flag_filename')) {
    function flag_filename(?string $locale = null): string
    {
        if (empty($locale)) {
            $locale = app()->getLocale();
        }

        $flag_filename = strtolower($locale);

        if ($flag_filename === 'kk') {
            $flag_filename = 'kz';
        } elseif ($flag_filename === 'en') {
            $flag_filename = 'gb';
        } elseif ($flag_filename === 'uk') {
            $flag_filename = 'ua';
        }

        return $flag_filename;
    }
}

if (!function_exists('phone_extension')) {
    /**
     * Returns the prefix to be added before all phone numbers, depends on main country of the website
     * Must start with a number, i.e. cannot start with "+"
     *
     * @return string
     */
    function phone_extension(): string
    {
        return Settings::retrieve('country_code');
    }
}

if (!function_exists('generate_breadcrumbs')) {
    function generate_breadcrumbs()
    {
        $segments = Request::segments();
        $breadcrumbs = [];

        $url = url('/');

        if (!empty($segments)) {
            $url .= '/' . $segments[0];

            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Sākums',
                'item' => $url,
            ];
        }

        foreach ($segments as $index => $segment) {
            if ($index === 0) continue;

            $url .= '/' . $segment;

            $title =
            Sections::where('route_name', $segment)->value('name') ??
            LoanTypes::where('route_name', $segment)->value('name') ??
            Lenders::where('route_name', $segment)->value('name') ??
            ucfirst(str_replace('-', ' ', $segment));

            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => count($breadcrumbs) + 1,
                'name' => $title,
                'item' => $url,
            ];
        }

        return $breadcrumbs;
    }
}