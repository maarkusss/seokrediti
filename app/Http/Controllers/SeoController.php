<?php

namespace App\Http\Controllers;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Settings;
use App\Models\SitemapIgnoredUrl;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function manifestJson(): Response
    {
        $manifestJson = trim(clean_legacy_brand((string) Settings::retrieve('manifest_json')));

        if ($manifestJson !== '') {
            return response($manifestJson, 200, ['Content-Type' => 'application/manifest+json; charset=UTF-8']);
        }

        $brand = brand_name();
        $payload = [
            'name' => $brand . ' — ' . brand_tagline(),
            'short_name' => $brand,
            'description' => brand_tagline(),
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#F6F3EC',
            'theme_color' => '#151412',
            'lang' => 'lv',
            'icons' => [
                [
                    'src' => '/favicon.svg',
                    'sizes' => 'any',
                    'type' => 'image/svg+xml',
                    'purpose' => 'any',
                ],
                [
                    'src' => '/android-chrome-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                ],
                [
                    'src' => '/android-chrome-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
        ];

        return response(
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT),
            200,
            ['Content-Type' => 'application/manifest+json; charset=UTF-8']
        );
    }

    public function robotsTxt(): Response
    {
        $robotsTxt = trim(clean_legacy_brand((string) Settings::retrieve('robots_txt')));

        if ($robotsTxt !== '') {
            return response($robotsTxt, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        if (app()->environment('staging')) {
            return $this->robotsTxtDisallowAll();
        }

        $sitemapUrl = rtrim(url('/'), '/') . '/sitemap.xml';
        $defaultRobots = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /go/',
            'Disallow: /admincp/',
            'Disallow: /lv/admincp/',
            'Disallow: /ru/admincp/',
            'Disallow: /lv/login',
            'Disallow: /ru/login',
            'Disallow: /setup',
            'Disallow: /captcha',
            'Disallow: /u/',
            '',
            'Sitemap: ' . $sitemapUrl,
        ]);

        return response($defaultRobots, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemapXml(): Response
    {
        $staticPath = public_path('sitemap.xml');
        if (is_file($staticPath)) {
            $raw = (string) file_get_contents($staticPath);
            if ($raw !== '' && stripos($raw, 'eirokrediti') === false) {
                return response($raw, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
            }
        }

        $baseUrl = rtrim(url('/'), '');
        $ignoredPaths = [];

        try {
            $ignoredPaths = SitemapIgnoredUrl::where('active', true)
                ->pluck('url')
                ->map(function ($u) {
                    $path = parse_url((string) $u, PHP_URL_PATH) ?: (string) $u;
                    $path = '/' . ltrim(strtolower(trim($path)), '/');
                    return $path !== '/' ? rtrim($path, '/') : '/';
                })
                ->toArray();
        } catch (\Throwable $e) {
            $ignoredPaths = [];
        }

        $entries = [];
        $addEntry = function (string $path, string $priority, string $changefreq, $lastmod = null) use (&$entries, $baseUrl, $ignoredPaths) {
            $normalized = '/' . ltrim($path, '/');
            if ($normalized !== '/') {
                $normalized = rtrim($normalized, '/');
            }
            if (in_array(strtolower($normalized), $ignoredPaths, true)) {
                return;
            }
            $loc = $normalized === '/' ? $baseUrl . '/' : $baseUrl . $normalized;
            $entries[$normalized] = [
                'loc' => $loc,
                'lastmod' => $lastmod ? \Carbon\Carbon::parse($lastmod)->toAtomString() : now()->toAtomString(),
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        };

        try {
            $homeSection = Sections::where('type', 'home')->where('lang', 'lv')->first();
            $addEntry('/', '1.0', 'daily', $homeSection?->updated_at);

            $parentLoans = LoanTypes::whereNull('parent_type_id')
                ->where('lang', 'lv')
                ->where('active', true)
                ->orderBy('order')
                ->get();

            foreach ($parentLoans as $parent) {
                $addEntry('/' . $parent->route_name, '0.9', 'weekly', $parent->updated_at);

                $children = LoanTypes::where('parent_type_id', $parent->id)
                    ->where('lang', 'lv')
                    ->where('active', true)
                    ->orderBy('order')
                    ->get();

                foreach ($children as $child) {
                    $addEntry('/' . $parent->route_name . '/' . $child->route_name, '0.8', 'weekly', $child->updated_at);
                }
            }

            $sections = Sections::whereNull('parent_section_id')
                ->where('lang', 'lv')
                ->where('active', true)
                ->where('type', '!=', 'home')
                ->orderBy('order')
                ->get();

            foreach ($sections as $sec) {
                $priority = in_array($sec->type, ['reviews', 'blog'], true) ? '0.8' : ($sec->type === 'contact' ? '0.6' : '0.4');
                $freq = in_array($sec->type, ['reviews', 'blog'], true) ? 'weekly' : 'monthly';
                $addEntry('/' . $sec->route_name, $priority, $freq, $sec->updated_at);

                if ($sec->type === 'reviews') {
                    $lenders = Lenders::orderBy('position')->get();
                    foreach ($lenders as $lender) {
                        if (!empty($lender->route_name)) {
                            $addEntry('/' . $sec->route_name . '/' . $lender->route_name, '0.7', 'weekly', $lender->updated_at);
                        }
                    }
                }

                if ($sec->type === 'blog') {
                    $posts = Sections::where('parent_section_id', $sec->id)
                        ->where('lang', 'lv')
                        ->where('active', true)
                        ->orderBy('order')
                        ->get();

                    foreach ($posts as $post) {
                        $addEntry('/' . $sec->route_name . '/' . $post->route_name, '0.6', 'monthly', $post->updated_at);
                    }
                }
            }
        } catch (\Throwable $e) {
            $addEntry('/', '1.0', 'daily', now());
        }

        $xml = ['<?xml version="1.0" encoding="UTF-8"?>'];
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($entries as $entry) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</loc>';
            $xml[] = '    <lastmod>' . htmlspecialchars($entry['lastmod'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</lastmod>';
            $xml[] = '    <changefreq>' . $entry['changefreq'] . '</changefreq>';
            $xml[] = '    <priority>' . $entry['priority'] . '</priority>';
            $xml[] = '  </url>';
        }
        $xml[] = '</urlset>';

        return response(implode("\n", $xml), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function llmsTxt(): Response
    {
        $brand = brand_name();
        $tagline = brand_tagline();
        $baseUrl = rtrim(url('/'), '');
        $email = brand_email();

        $lines = [
            '# ' . $brand,
            '',
            '> ' . $tagline . '. Neatkarīga Latvijas nebanku un banku kredītu salīdzināšanas platforma ar caurspīdīgiem GPL (APR) aprēķiniem, licencētu aizdevēju profiliem un pārbaudītām lietotāju atsauksmēm.',
            '',
            '## Pamatinformācija',
            '- Vietne: ' . $baseUrl . '/',
            '- Valoda: Latviešu (lv-LV)',
            '- Reģions: Latvija (LV)',
            '- Valūta: EUR (€)',
            '- Uzraugošā iestāde nozarē: Patērētāju tiesību aizsardzības centrs (PTAC)',
            '- Kontakti: ' . $email,
            '- Lapas karte (Sitemap): ' . $baseUrl . '/sitemap.xml',
            '',
            '## Galvenās kredītu salīdzināšanas sadaļas',
        ];

        try {
            $parentLoans = LoanTypes::whereNull('parent_type_id')
                ->where('lang', 'lv')
                ->where('active', true)
                ->orderBy('order')
                ->get();

            foreach ($parentLoans as $loan) {
                $desc = trim(clean_legacy_brand((string) ($loan->description ?: $loan->h1 ?: $loan->name)));
                $lines[] = '- [' . clean_legacy_brand($loan->name) . '](' . $baseUrl . '/' . $loan->route_name . '): ' . $desc;

                $children = LoanTypes::where('parent_type_id', $loan->id)
                    ->where('lang', 'lv')
                    ->where('active', true)
                    ->orderBy('order')
                    ->get();

                foreach ($children as $child) {
                    $lines[] = '  - [' . clean_legacy_brand($child->name) . '](' . $baseUrl . '/' . $loan->route_name . '/' . $child->route_name . ')';
                }
            }

            $sections = Sections::whereNull('parent_section_id')
                ->where('lang', 'lv')
                ->where('active', true)
                ->where('type', '!=', 'home')
                ->orderBy('order')
                ->get();

            if ($sections->isNotEmpty()) {
                $lines[] = '';
                $lines[] = '## Atsauksmes, ceļveži un informācija';
                foreach ($sections as $sec) {
                    $lines[] = '- [' . clean_legacy_brand($sec->name) . '](' . $baseUrl . '/' . $sec->route_name . ')';
                }
            }
        } catch (\Throwable $e) {
            // Database not seeded yet
        }

        $lines[] = '';
        $lines[] = '## Atbildīgas aizņemšanās brīdinājums';
        $lines[] = brand_disclaimer();

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    protected function robotsTxtDisallowAll(): Response
    {
        return response(
            "User-agent: *\nDisallow: /",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }
}
