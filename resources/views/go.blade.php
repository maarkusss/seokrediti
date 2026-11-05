<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!-- Meta tags -->
    <title>
        @if ($lender->name)
            {{ $lender->name . ' - ' . config('app.name') }}
        @else
            {{ config('app.name') }}
        @endif
    </title>
    <meta name="title"
        content="@if ($lender->name) {{ $lender->name . ' - ' . config('app.name') }}@else{{ config('app.name') }} @endif" />
    <meta name="description" content="@if ($lender->data && $lender->data->meta_description) {{ $lender->data->meta_description }} @endif" />
    <meta name="image"
        content="@if ($lender->image) {{ url($lender->image) }} @endif" />
    <!-- OG/FB meta tags -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:title"
        content="@if ($lender->name) {{ $lender->name . ' - ' . config('app.name') }}@else{{ config('app.name') }} @endif" />
    <meta property="og:description"
        content="@if ($lender->data && $lender->data->meta_description) {{ $lender->data->meta_description }} @endif" />
    <meta property="og:image"
        content="@if ($lender->image) {{ url($lender->image) }} @endif" />
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ request()->url() }}" />
    <meta property="twitter:title"
        content="@if ($lender->name) {{ $lender->name . ' - ' . config('app.name') }}@else{{ config('app.name') }} @endif" />
    <meta property="twitter:description"
        content="@if ($lender->data && $lender->data->meta_description) {{ $lender->data->meta_description }} @endif" />
    <meta property="twitter:image"
        content="@if ($lender->image) {{ url($lender->image) }} @endif" />
    <!-- Canonical -->
    <link rel="canonical" href="{{ request()->url() }}" />
    <!-- Robots -->
    <meta name="robots" content="noindex, nofollow" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" />
    @if (\App\Models\Admincp\Settings::where('name', 'head_code_go')->exists())
        {!! \App\Models\Admincp\Settings::where('name', 'head_code_go')->first()?->value !!}
    @endif
    <script>
        window.setTimeout(function() {
            window.location = "{!! $link !!}";
        }, 500);
    </script>
</head>

<body>
    <div class="flex justify-center p-4">
        <div class="flex flex-col items-center justify-start p-6 border rounded-lg">
            <h1 class="mb-4 text-3xl font-bold text-center text-gray-800">
                @lang('We redirect to the lender\'s page')
                <span class="text">
                    {{ $lender->name }}
                </span>
            </h1>
            <h2 class="mb-4 text-2xl font-normal text-center text-gray-800">
                @lang('Thank you for working with us! Auto redirect...')
            </h2>
            <div style="text-align:center;"><img src="/loading.gif" alt="loading"></div>
        </div>
    </div>
</body>

</html>
