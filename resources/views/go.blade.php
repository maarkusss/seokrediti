<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $lender->name ? ($lender->name . ' — ' . brand_name()) : brand_name() }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="alternate icon" type="image/x-icon" href="/favicon.ico">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=source-serif-4:400,600,700|ibm-plex-sans:400,500,600&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (\App\Models\Admincp\Settings::where('name', 'head_code_go')->exists())
        {!! \App\Models\Admincp\Settings::where('name', 'head_code_go')->first()?->value !!}
    @endif

    <script>
        window.setTimeout(function () {
            window.location.href = @json($link);
        }, 450);
    </script>
</head>

<body class="min-h-screen bg-paper text-ink flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-paper-light border-2 border-ink p-8 text-center">
        <div class="flex justify-center pb-5 mb-6 border-b border-rule">
            <x-brand-logo size="sm" :link="false" />
        </div>

        @if (!empty($lender->image))
            <div class="mx-auto mb-5 w-36 h-16 border border-rule bg-white p-2.5 flex items-center justify-center">
                <img src="{{ $lender->image }}" alt="{{ $lender->name }}" class="max-h-11 w-auto object-contain">
            </div>
        @endif

        <p class="kicker justify-center mb-2">Pāradresācija</p>

        <h1 class="font-serif text-2xl font-bold text-ink">
            Savienojam ar {{ $lender->name }}
        </h1>

        <p class="mt-2 text-xs text-ink-muted leading-relaxed">
            Lūdzu, uzgaidiet — atveram aizdevēja oficiālo pieteikuma lapu.
        </p>

        <div class="mt-6 pt-5 border-t border-rule">
            <a href="{{ $link }}"
               rel="nofollow sponsored noopener"
               class="btn-ink w-full">
                Turpināt uz {{ $lender->name }} →
            </a>
        </div>
    </main>
</body>

</html>
