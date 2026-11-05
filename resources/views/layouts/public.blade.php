@php
    $locale = app()->getLocale();
    $rawYieldTitle = trim($__env->yieldContent('title'));
    $metaTitle = seo_title($rawYieldTitle);

    $rawYieldDesc = trim($__env->yieldContent('description'));
    $metaDescription = $rawYieldDesc !== ''
        ? clean_legacy_brand($rawYieldDesc)
        : (brand_name() . ' — ' . brand_tagline() . '. Salīdzini aizdevēju procentu likmes (GPL), summas, termiņus un reālas atsauksmes.');

    $canonicalUrl = request()->url();
    if ((int) request()->query('page', 1) > 1) {
        $canonicalUrl .= '?page=' . (int) request()->query('page');
    }

    $ogImage = trim($__env->yieldContent('og_image'));
    if ($ogImage === '') {
        $ogImage = asset(ltrim((string) config('brand.og_image', '/images/brand/og-cover.svg'), '/'));
    }

    $robotsDirective = trim($__env->yieldContent('robots'));
    $breadcrumbs = generate_breadcrumbs();

    $orgLogoUrl = brand_logo_url() ? url(brand_logo_url()) : asset('favicon.svg');

    $connectedHreflangs = collect();
    try {
        $connectedHreflangs = \App\Models\Admincp\ConnectedRoutes::where('url_from', request()->url())->get();
    } catch (\Throwable $e) {
        $connectedHreflangs = collect();
    }
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', $locale) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if ($robotsDirective !== '')
        <meta name="robots" content="{{ $robotsDirective }}">
    @endif

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ $canonicalUrl }}">
    @if ($locale === 'lv')
        <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">
    @endif
    @foreach ($connectedHreflangs as $altRoute)
        @if (!empty($altRoute->lang) && !empty($altRoute->url_to))
            <link rel="alternate" hreflang="{{ $altRoute->lang }}" href="{{ $altRoute->url_to }}">
        @endif
    @endforeach

    {{-- OpenGraph & Social Metadata --}}
    <meta property="og:site_name" content="{{ brand_name() }}">
    <meta property="og:locale" content="{{ $locale === 'ru' ? 'ru_LV' : 'lv_LV' }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#F6F3EC">
    <meta name="apple-mobile-web-app-title" content="{{ brand_name() }}">

    {{-- Brand Icons & Manifest --}}
    <link rel="icon" type="image/svg+xml" href="/favicon.svg?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=2">
    <link rel="alternate icon" type="image/x-icon" href="/favicon.ico?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2">
    <link rel="mask-icon" href="/safari-pinned-tab.svg?v=2" color="#9E1B1B">
    <link rel="manifest" href="{{ route('manifest.json') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=source-serif-4:400,400i,600,700|ibm-plex-sans:400,500,600,700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (brand_setting('head_code'))
        {!! brand_setting('head_code') !!}
    @endif

    {{-- Structured Data (JSON-LD) --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => rtrim(url('/'), '/') . '/#organization',
            'name' => brand_name(),
            'url' => rtrim(url('/'), '/') . '/',
            'logo' => $orgLogoUrl,
            'email' => brand_email(),
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Latvia',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    @if (request()->routeIs('homepage'))
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                '@id' => rtrim(url('/'), '/') . '/#website',
                'name' => brand_name(),
                'description' => brand_tagline(),
                'url' => rtrim(url('/'), '/') . '/',
                'inLanguage' => $locale,
                'publisher' => [
                    '@id' => rtrim(url('/'), '/') . '/#organization',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif

    @if (count($breadcrumbs) > 1)
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $breadcrumbs,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif

    @yield('schema')
    @stack('schema')

    @if (isset($section))
        @if (!(isset($lender) && $section->type === 'reviews') && !empty($section->section_html))
            {!! clean_legacy_brand($section->section_html) !!}
        @endif
    @endif
    @if (isset($homepage) && !empty($homepage->section_html))
        {!! clean_legacy_brand($homepage->section_html) !!}
    @endif
    @if (isset($loanType) && !empty($loanType->section_html))
        {!! clean_legacy_brand($loanType->section_html) !!}
    @endif
</head>

<body class="min-h-screen flex flex-col bg-paper text-ink">
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:px-4 focus:py-2 focus:bg-ink focus:text-paper-light focus:font-semibold">
        Pāriet uz galveno saturu
    </a>

    @include('components.navbar')

    <main id="main-content" role="main" class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')

    @if (brand_setting('cookie') === 'true')
        @include('components.cookies-notification')
    @endif

    @yield('javascript')
</body>

</html>
