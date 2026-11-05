<?php

namespace App\Console\Commands;

use App\Models\Admincp\Lenders\Lenders;
use App\Models\Admincp\LoanTypes\LoanTypes;
use App\Models\Admincp\Sections\Sections;
use App\Models\Admincp\Settings;
use App\Models\Reviews;
use App\Models\SitemapIgnoredUrl;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.';

    private string $targetScheme = 'https';
    private string $targetHost = 'www.eirokrediti.lv';
    private ?int $targetPort = null;

    public function handle()
    {
        $setting = Settings::where('name', 'sitemap')->first();

        if (!$setting || !$setting->value) {
            $this->error('Sitemap link is not configured.');
            return;
        }

        $sitemapLink = $setting->value;

        $parsed = parse_url($sitemapLink);
        $this->targetScheme = strtolower($parsed['scheme'] ?? 'https');
        $this->targetHost = strtolower($parsed['host'] ?? 'www.eirokrediti.lv');
        $this->targetPort = isset($parsed['port']) ? (int) $parsed['port'] : null;

        $ignoredUrls = SitemapIgnoredUrl::where('active', true)
            ->pluck('url')
            ->map(function ($url) {
                return $this->normalizeUrl($url);
            })
            ->toArray();

        $crawledUrls = [];

        $allowedHosts = array_unique([
            $this->targetHost,
            preg_replace('/^www\./', '', $this->targetHost),
            'www.' . preg_replace('/^www\./', '', $this->targetHost),
        ]);

        $sitemapPath = public_path('sitemap.xml');

        SitemapGenerator::create($sitemapLink)
            ->hasCrawled(function (Url $url) use ($ignoredUrls, &$crawledUrls, $allowedHosts) {
                $currentUrl = $this->normalizeUrl($url->url);
                $currentPath = parse_url($currentUrl, PHP_URL_PATH) ?? '/';
                $currentHost = parse_url($currentUrl, PHP_URL_HOST);

                if (!$currentHost || !in_array($currentHost, $allowedHosts)) {
                    return;
                }

                // Discard query strings (Google prefers clean canonical URLs in sitemaps)
                if (str_contains($url->url, '?')) {
                    return;
                }

                // Discard non-HTTP protocol handlers
                if (
                    str_starts_with($url->url, 'mailto:') ||
                    str_starts_with($url->url, 'tel:') ||
                    str_starts_with($url->url, 'javascript:')
                ) {
                    return;
                }

                // Discard affiliate redirect links (not indexable content)
                if (preg_match('#(^|/)go(/|$)#i', $currentPath)) {
                    return;
                }

                // Discard legacy duplicate blog URLs with trailing numeric IDs (/blogs/name/1)
                if (preg_match('#^/blogs/[^/]+/\d+$#i', $currentPath) || preg_match('#^/(lv|ru)/blogs/[^/]+/\d+$#i', $currentPath)) {
                    return;
                }

                // Discard AdminCP & setup paths if discovered
                if (preg_match('#(^|/)(admincp|setup|login|register)(/|$)#i', $currentPath)) {
                    return;
                }

                if ($this->isIgnoredUrl($currentUrl, $currentPath, $ignoredUrls)) {
                    return;
                }

                if (in_array($currentUrl, $crawledUrls)) {
                    return;
                }

                $crawledUrls[] = $currentUrl;

                $url->url = $currentUrl;
                $url->setPriority($this->getPriorityForPath($currentPath));
                $url->setChangeFrequency($this->getChangeFrequencyForPath($currentPath));
                $url->setLastModificationDate($this->getLastModForPath($currentPath));

                return $url;
            })
            ->writeToFile($sitemapPath);

        $this->info('Sitemap generated successfully.');
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);

        if (!$parts || !isset($parts['host'])) {
            $path = mb_strtolower($url);
        } else {
            $path = mb_strtolower($parts['path'] ?? '/');
        }

        $path = '/' . ltrim($path, '/');

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $port = $this->targetPort ? ':' . $this->targetPort : '';

        return $this->targetScheme . '://' . $this->targetHost . $port . $path;
    }

    private function isIgnoredUrl(string $currentUrl, string $currentPath, array $ignoredUrls): bool
    {
        if (in_array($currentUrl, $ignoredUrls)) {
            return true;
        }

        foreach ($ignoredUrls as $ignoredUrl) {
            $ignoredPath = parse_url($ignoredUrl, PHP_URL_PATH) ?? '/';

            if ($currentPath === $ignoredPath) {
                return true;
            }
        }

        return false;
    }

    private function getPriorityForPath(string $path): float
    {
        $normalizedPath = $this->normalizePathWithoutLocale($path);

        if ($normalizedPath === '/') {
            return 1.0;
        }

        // Galvenās kredītu / money lapas (visaugstākā komerciālā prioritāte)
        if (in_array($normalizedPath, [
            '/atrie-krediti',
            '/bezprocentu-krediti',
            '/paterina-krediti',
            '/auto-lizings',
            '/kreditu-apvienosana',
            '/sms-krediti',
            '/krediti',
            '/apvienosana',
            '/kreditnaya-liniya',
            '/obyedineniye-kreditov',
        ])) {
            return 0.9;
        }

        // Kredītu apakšsadaļas (uz-12-menesiem, no-18-gadiem, ar-sliktu-kreditvesturi utt.)
        if (
            str_starts_with($normalizedPath, '/atrie-krediti/') ||
            str_starts_with($normalizedPath, '/paterina-krediti/') ||
            str_starts_with($normalizedPath, '/auto-lizings/') ||
            str_starts_with($normalizedPath, '/bezprocentu-krediti/')
        ) {
            return 0.8;
        }

        // Atsauksmju hub lapa
        if (
            $normalizedPath === '/atsauksmes' ||
            $normalizedPath === '/otzivi'
        ) {
            return 0.8;
        }

        // Individuālas aizdevēju atsauksmju lapas
        if (
            str_starts_with($normalizedPath, '/atsauksmes/') ||
            str_starts_with($normalizedPath, '/otzivi/')
        ) {
            return 0.7;
        }

        // Bloga saraksta lapa
        if (
            $normalizedPath === '/blogs' ||
            $normalizedPath === '/blog'
        ) {
            return 0.7;
        }

        // Bloga raksti
        if (
            str_starts_with($normalizedPath, '/blogs/') ||
            str_starts_with($normalizedPath, '/blog/')
        ) {
            return 0.6;
        }

        // Uzticības / informatīvās lapas
        if (in_array($normalizedPath, [
            '/par-mums',
            '/kontakti',
            '/buj',
            '/faq',
            '/o-nas',
        ])) {
            return 0.5;
        }

        // Juridiskās lapas
        if (in_array($normalizedPath, [
            '/privatuma-politika',
            '/politika-konfidentsialnosti',
            '/lietosana',
            '/ispolzovanie',
            '/sikfaili',
            '/cookie',
        ])) {
            return 0.3;
        }

        return 0.5;
    }

    private function getChangeFrequencyForPath(string $path): string
    {
        $normalizedPath = $this->normalizePathWithoutLocale($path);

        if ($normalizedPath === '/') {
            return Url::CHANGE_FREQUENCY_DAILY;
        }

        // Galvenās kredītu sadaļas un to apakšsadaļas
        if (
            in_array($normalizedPath, [
                '/atrie-krediti',
                '/bezprocentu-krediti',
                '/paterina-krediti',
                '/auto-lizings',
                '/kreditu-apvienosana',
                '/sms-krediti',
                '/krediti',
            ]) ||
            str_starts_with($normalizedPath, '/atrie-krediti/') ||
            str_starts_with($normalizedPath, '/paterina-krediti/')
        ) {
            return Url::CHANGE_FREQUENCY_WEEKLY;
        }

        // Atsauksmju lapas
        if (
            str_starts_with($normalizedPath, '/atsauksmes') ||
            str_starts_with($normalizedPath, '/otzivi')
        ) {
            return Url::CHANGE_FREQUENCY_WEEKLY;
        }

        // Bloga saraksts
        if (
            $normalizedPath === '/blogs' ||
            $normalizedPath === '/blog'
        ) {
            return Url::CHANGE_FREQUENCY_WEEKLY;
        }

        // Bloga raksti
        if (
            str_starts_with($normalizedPath, '/blogs/') ||
            str_starts_with($normalizedPath, '/blog/')
        ) {
            return Url::CHANGE_FREQUENCY_MONTHLY;
        }

        // Juridiskās / statiskās lapas
        if (in_array($normalizedPath, [
            '/privatuma-politika',
            '/politika-konfidentsialnosti',
            '/lietosana',
            '/ispolzovanie',
            '/sikfaili',
            '/cookie',
            '/par-mums',
        ])) {
            return Url::CHANGE_FREQUENCY_YEARLY;
        }

        return Url::CHANGE_FREQUENCY_MONTHLY;
    }

    private function getLastModForPath(string $path): Carbon
    {
        $normalizedPath = $this->normalizePathWithoutLocale($path);

        try {
            // Sākumlapa
            if ($normalizedPath === '/') {
                $homepage = Sections::where('type', 'home')->first();
                return $homepage?->updated_at ?? now();
            }

            // Bloga raksts (/blogs/article-slug)
            if (preg_match('#^/(?:blogs|blog)/([^/]+)$#', $normalizedPath, $matches)) {
                $blog = Sections::where('route_name', $matches[1])
                    ->where('type', 'blog')
                    ->first();
                if ($blog && $blog->updated_at) {
                    return $blog->updated_at;
                }
            }

            // Bloga saraksts (/blogs)
            if ($normalizedPath === '/blogs' || $normalizedPath === '/blog') {
                $latestBlog = Sections::where('type', 'blog')
                    ->whereNotNull('parent_section_id')
                    ->orderBy('updated_at', 'desc')
                    ->first();
                if ($latestBlog && $latestBlog->updated_at) {
                    return $latestBlog->updated_at;
                }
            }

            // Aizdevēja atsauksmes (/atsauksmes/lender-slug)
            if (preg_match('#^/(?:atsauksmes|otzivi)/([^/]+)$#', $normalizedPath, $matches)) {
                $lender = Lenders::where('route_name', $matches[1])->first();
                if ($lender && $lender->updated_at) {
                    return $lender->updated_at;
                }
            }

            // Atsauksmju saraksts (/atsauksmes)
            if ($normalizedPath === '/atsauksmes' || $normalizedPath === '/otzivi') {
                $latestReview = Reviews::orderBy('created_at', 'desc')->first();
                if ($latestReview && $latestReview->created_at) {
                    return $latestReview->created_at;
                }
            }

            // Kredītu apakšsadaļa (/atrie-krediti/no-18-gadiem)
            if (preg_match('#^/[^/]+/([^/]+)$#', $normalizedPath, $matches)) {
                $subLoan = LoanTypes::where('route_name', $matches[1])->first();
                if ($subLoan && $subLoan->updated_at) {
                    return $subLoan->updated_at;
                }
            }

            // Kredītu galvenā sadaļa vai vispārīga sadaļa
            if (preg_match('#^/([^/]+)$#', $normalizedPath, $matches)) {
                $loan = LoanTypes::where('route_name', $matches[1])->first();
                if ($loan && $loan->updated_at) {
                    return $loan->updated_at;
                }
                $section = Sections::where('route_name', $matches[1])->first();
                if ($section && $section->updated_at) {
                    return $section->updated_at;
                }
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        return now();
    }

    private function normalizePathWithoutLocale(string $path): string
    {
        $path = '/' . trim($path, '/');

        if ($path === '/') {
            return '/';
        }

        $path = preg_replace('#^/(lv|ru|en)(/|$)#', '/', $path);

        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
