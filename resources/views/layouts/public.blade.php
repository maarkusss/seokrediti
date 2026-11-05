<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta property="og:url" content="{{ request()->url() }}">
    <title>@yield('title')</title>
    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="website" />
    <meta name="description" content="@yield('description')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="{{ asset('images/icon-192x192.png') }}">
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ Request::url() }}" />
    <meta property="twitter:title" content="@yield('title')" />
    <meta property="twitter:description" content="@yield('description')" />
    <meta property="twitter:image" content="{{ asset('images/icon-192x192.png') }}" />
    <link rel="canonical" href="{{ Request::url() }}" />
    <meta name="keywords" content="@yield('keywords')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="stylesheet" href="{{ asset('css/eirokrediti.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (\App\Models\Admincp\Settings::where('name', 'head_code')->exists())
        {!! \App\Models\Admincp\Settings::where('name', 'head_code')->first()?->value !!}
    @endif
    <!-- Schema start -->
    @yield('schema')
    <script type="application/ld+json">
        {!! json_encode([
          "@context" => "https://schema.org",
          "@type" => "BreadcrumbList",
          "itemListElement" => generate_breadcrumbs()
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <!-- Schema end -->
    @if (isset($section))
        @if (isset($lender->data) && $section->type == 'reviews' && count(request()->segments()) >= 3)
            @php
                $lender = \App\Models\Admincp\Lenders\Lenders::where('route_name', request()->segment(3))->first();
            @endphp
            @if ($lender)
                {!! $lender->data->section_html !!}
            @endif
        @else
            {!! $section->section_html !!}
        @endif
    @endif
    @if (isset($homepage))
        {!! $homepage->section_html !!}
    @endif
    @if (isset($loanType))
        {!! $loanType->section_html !!}
    @endif
</head>

<body>
    <div id="vadJUDZvLdUy1-seo" class="hidden"></div>

    <a href="#main-content" class="sr-only sr-only-focusable">Pāriet uz galveno saturu</a>

    @include('components.navbar')
    <main id="main-content" role="main">
        @yield('content')
    </main>
    @include('components.footer')
    @if (\App\Models\Admincp\Settings::where('name', 'cookie')->first()?->value == 'true')
        @include('components.cookies-notification')
    @endif
    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="{{ asset('js/page.js') }}"></script>
    @yield('javascript')

</body>

</html>
