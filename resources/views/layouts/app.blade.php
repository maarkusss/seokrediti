<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', brand_name())</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @yield('head')
</head>

<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen">
    @yield('content')

    @yield('javascript')
</body>

</html>
