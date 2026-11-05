<?php

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Settings;
use Illuminate\Support\Facades\Request;

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
        $t = strtolower((string) $user_agent);
        $t = ' ' . $t;
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
            if (preg_match($regex, (string) $user_agent)) {
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
            }

            return 'Desktop';
        }

        return 'Unknown';
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
        $ua = (string) request()->header('user-agent', '');
        if (
            stristr($ua, 'bot') ||
            stristr($ua, 'crawler') ||
            stristr($ua, 'Skype')
        ) {
            return true;
        }

        return false;
    }
}

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

        return $available_locales[0] ?? 'lv';
    }
}

if (!function_exists('available_locales')) {
    function available_locales(): array
    {
        $available_locales = [];
        try {
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
        } catch (\Throwable $e) {
            return ['lv'];
        }

        return !empty($available_locales) ? $available_locales : ['lv'];
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
    function phone_extension(): string
    {
        try {
            return Settings::retrieve('country_code') ?: '371';
        } catch (\Throwable $e) {
            return '371';
        }
    }
}

if (!function_exists('brand_setting')) {
    /**
     * Retrieve a setting value safely from database with fallback.
     */
    function brand_setting(string $key, ?string $default = null): ?string
    {
        try {
            $val = Settings::retrieve($key);
            if ($val !== '') {
                return $val;
            }
        } catch (\Throwable $e) {
            // Database may not be initialized yet
        }

        return $default;
    }
}

if (!function_exists('brand_name')) {
    /**
     * Centralized website brand name (changeable in AdminCP Settings or config/brand.php).
     */
    function brand_name(): string
    {
        $dbName = brand_setting('name');

        if (!empty($dbName) && stripos($dbName, 'eirokrediti') === false) {
            return trim($dbName);
        }

        $cfgName = (string) config('brand.name', 'Credito.lv');
        if (stripos($cfgName, 'eirokrediti') !== false || strtolower($cfgName) === 'laravel') {
            return 'Credito.lv';
        }

        return trim($cfgName);
    }
}

if (!function_exists('brand_tagline')) {
    function brand_tagline(): string
    {
        return brand_setting('brand_tagline', (string) config('brand.tagline', 'Neatkarīgs kredītu salīdzinājums')) ?: 'Neatkarīgs kredītu salīdzinājums';
    }
}

if (!function_exists('brand_logo_url')) {
    function brand_logo_url(): ?string
    {
        $logo = brand_setting('brand_logo', (string) config('brand.logo_url', ''));
        $logo = trim((string) $logo);

        return $logo !== '' ? $logo : null;
    }
}

if (!function_exists('brand_email')) {
    function brand_email(): string
    {
        $email = brand_setting('contact_email', (string) config('brand.email', 'info@credito.lv'));
        if (empty($email) || stripos($email, 'eirokrediti') !== false || stripos($email, 'atrie.lv') !== false) {
            return (string) config('brand.email', 'info@credito.lv');
        }

        return $email;
    }
}

if (!function_exists('brand_disclaimer')) {
    function brand_disclaimer(): string
    {
        return brand_setting('brand_disclaimer', (string) config('brand.disclaimer')) ?: (string) config('brand.disclaimer');
    }
}

if (!function_exists('clean_legacy_brand')) {
    /**
     * Automatically replaces any legacy brand strings inside imported DB content or titles.
     */
    function clean_legacy_brand(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        return preg_replace(
            ['/\b(?:www\.)?eirokrediti\.lv\b/iu', '/\beirokrediti\b/iu', '/\b(?:www\.)?atrie\.lv\b/iu'],
            [brand_name(), brand_name(), brand_name()],
            $text
        ) ?? $text;
    }
}

if (!function_exists('seo_title')) {
    /**
     * Builds a clean, non-duplicated SEO <title> string with dynamic brand name.
     */
    function seo_title(?string $title = null): string
    {
        $brand = brand_name();
        $clean = trim(clean_legacy_brand($title));

        // Remove trailing separator if title already ended with "- Brand"
        $clean = preg_replace('/\s*[\-\|–—]\s*' . preg_quote($brand, '/') . '\s*$/iu', '', $clean) ?? $clean;
        $clean = trim($clean);

        if ($clean === '' || mb_strtolower($clean) === mb_strtolower($brand)) {
            return $brand . ' — ' . brand_tagline();
        }

        return $clean . ' | ' . $brand;
    }
}

if (!function_exists('format_term_label')) {
    /**
     * Formats numeric day durations into clean Latvian/Russian human-readable terms.
     */
    function format_term_label($days, bool $withUpTo = false): string
    {
        $locale = app()->getLocale();
        $prefix = $withUpTo ? ($locale === 'ru' ? 'до ' : 'līdz ') : '';

        if (!is_numeric($days)) {
            return trim($prefix . (string) $days);
        }

        $d = (int) $days;
        if ($d <= 0) {
            return $locale === 'ru' ? '1 мес.' : '1 mēn.';
        }

        if ($d < 30) {
            if ($locale === 'ru') {
                return $prefix . $d . ' дн.';
            }
            return $prefix . $d . ($withUpTo ? ' dienām' : ($d === 1 ? ' diena' : ' dienas'));
        }

        if ($d < 360) {
            $months = max(1, (int) round($d / 30));
            if ($locale === 'ru') {
                return $prefix . $months . ' мес.';
            }
            if ($withUpTo) {
                return $prefix . $months . ($months === 1 ? ' mēnesim' : ' mēnešiem');
            }
            return $months . ($months === 1 ? ' mēnesis' : ' mēneši');
        }

        $years = max(1, (int) round($d / 360));
        if ($locale === 'ru') {
            return $prefix . $years . ($years === 1 ? ' года' : ' лет');
        }
        if ($withUpTo) {
            return $prefix . $years . ($years === 1 ? ' gadam' : ' gadiem');
        }
        return $years . ($years === 1 ? ' gads' : ' gadi');
    }
}

if (!function_exists('generate_breadcrumbs')) {
    /**
     * Generates valid Schema.org BreadcrumbList items starting at root homepage.
     */
    function generate_breadcrumbs(): array
    {
        $segments = Request::segments();
        $locale = app()->getLocale();
        $homeLabel = $locale === 'ru' ? 'Главная' : 'Sākums';
        $baseUrl = rtrim(url('/'), '/');

        $breadcrumbs = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $homeLabel,
                'item' => $locale === 'ru' ? $baseUrl . '/ru' : $baseUrl . '/',
            ],
        ];

        if (empty($segments)) {
            return $breadcrumbs;
        }

        $currentUrl = $baseUrl;

        foreach ($segments as $index => $segment) {
            $currentUrl .= '/' . $segment;

            if ($index === 0 && in_array($segment, ['lv', 'ru'], true)) {
                continue;
            }

            $title = null;
            try {
                $title = Sections::where('route_name', $segment)->where('lang', $locale)->value('name')
                    ?? LoanTypes::where('route_name', $segment)->where('lang', $locale)->value('name')
                    ?? Lenders::where('route_name', $segment)->value('name')
                    ?? Sections::where('route_name', $segment)->value('name')
                    ?? LoanTypes::where('route_name', $segment)->value('name');
            } catch (\Throwable $e) {
                $title = null;
            }

            $title = $title ? clean_legacy_brand($title) : ucfirst(str_replace('-', ' ', $segment));

            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => count($breadcrumbs) + 1,
                'name' => $title,
                'item' => $currentUrl,
            ];
        }

        return $breadcrumbs;
    }
}
