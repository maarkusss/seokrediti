<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ brand_name() }}</title>
    <style>
        .body {
            background: #f8fafc;
            margin: 0;
            padding: 20px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
        }
        .logo {
            text-align: center;
            margin: 0 0 18px 0;
        }
        .logo a {
            color: #0b221e;
            font-weight: 800;
            font-size: 1.25rem;
            text-decoration: none;
        }
        .title {
            text-align: center;
            font-size: 1rem;
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .lender-box-outside {
            width: 25%;
            float: left;
            overflow: hidden;
            position: relative;
        }
        .lender-box-inside {
            margin: 0 15px 15px 0;
            text-align: center;
            padding: 14px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .lender-box-inside-primary {
            border: 1px solid #059669;
        }
        .lender-slogan {
            font-size: .75rem;
            color: #334155;
            padding: 4px 8px;
            font-weight: 600;
            border-radius: 6px;
            background-color: #f1f5f9;
            margin-bottom: 10px;
        }
        .lender-slogan-primary {
            background-color: #ecfdf5;
            color: #065f46;
        }
        .lender-grid {
            overflow: hidden;
        }
        .lender-left, .lender-right {
            float: left;
            width: 50%;
        }
        .lender-title {
            font-size: .75rem;
            color: #64748b;
            text-align: left;
        }
        .lender-text {
            font-size: 1rem;
            color: #0f172a;
            font-weight: 700;
            text-align: left;
            margin-top: 4px;
            margin-bottom: 12px;
        }
        .lender-text-primary {
            color: #059669;
        }
        .lender-logo-container {
            margin-bottom: 12px;
            height: 44px;
        }
        .lender-logo-img {
            max-width: 100%;
            max-height: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .lender-button {
            border-radius: 8px;
            font-size: .95rem;
            color: #ffffff !important;
            text-align: center;
            padding: 12px 0;
            display: block;
            font-weight: 600;
            background-color: #059669;
            text-decoration: none;
        }
        .clear {
            clear: both;
        }
        .unsubscribe {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: .85rem;
            color: #64748b;
        }
        .unsubscribe a {
            color: #059669 !important;
        }
        @media screen and (max-width: 900px) {
            .lender-box-outside { width: 50%; }
        }
        @media screen and (max-width: 520px) {
            .lender-box-outside { width: 100%; }
            .lender-box-inside { margin-right: 0; }
        }
    </style>
</head>

<body class="body">
    <div class="logo">
        <a href="{{ url('/') }}" target="_blank" rel="noopener">{{ brand_name() }}</a>
    </div>

    <p class="title">Salīdzini licencētu aizdevēju piedāvājumus un izvēlies savai situācijai piemērotāko risinājumu.</p>

    @php $i = 0; @endphp
    @foreach ($lenders as $lender)
        @php
            $i++;
            $lenders_data = \App\Models\Admincp\Lenders\LendersData::where('lender_id', $lender->id)
                ->where('lang', app()->getLocale())
                ->first();
        @endphp
        <div class="lender-box-outside">
            <div class="lender-box-inside{{ $i <= 3 ? ' lender-box-inside-primary' : '' }}">
                @if ($lenders_data && $lenders_data->slogan)
                    <div class="lender-slogan{{ $i <= 3 ? ' lender-slogan-primary' : '' }}">
                        {{ clean_legacy_brand($lenders_data->slogan) }}
                    </div>
                @endif
                <div class="lender-logo-container">
                    <a href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $i]) }}?utm_source={{ $user->utm_source }}&utm_medium={{ $user->utm_medium }}&utm_campaign={{ $user->utm_campaign }}&utm_content={{ $user->utm_content }}&gclid={{ $user->gclid }}"
                       rel="nofollow sponsored noopener"
                       target="_blank">
                        <img class="lender-logo-img" src="{{ url($lender->image) }}" alt="{{ $lender->name }}">
                    </a>
                </div>
                <div class="lender-grid">
                    <div class="lender-left">
                        <div class="lender-title">Summa</div>
                        <div class="lender-text">{!! $lender->zero_percent ? '<span class="lender-text-primary">0%</span> ' : '' !!}{{ __('līdz') }} {{ $lender->max_amount }} €</div>
                    </div>
                    <div class="lender-right">
                        <div class="lender-title">Termiņš</div>
                        <div class="lender-text">{{ format_term_label($lender->max_term, true) }}</div>
                    </div>
                    <div class="clear"></div>
                    <div class="lender-left">
                        <div class="lender-title">Vecums</div>
                        <div class="lender-text">{{ $lender->min_years }}–{{ $lender->max_years }} g.</div>
                    </div>
                </div>
                <a class="lender-button"
                   href="{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $i]) }}?utm_source={{ $user->utm_source }}&utm_medium={{ $user->utm_medium }}&utm_campaign={{ $user->utm_campaign }}&utm_content={{ $user->utm_content }}&gclid={{ $user->gclid }}"
                   rel="nofollow sponsored noopener"
                   target="_blank">{{ __('Receive a loan') }}</a>
            </div>
        </div>
    @endforeach
    <div class="clear"></div>

    <p class="unsubscribe">
        {{ brand_disclaimer() }}<br>
        Lai atteiktos no turpmākiem jaunumiem, spiediet <a href="{{ url('/unsubscribe') }}" target="_blank" rel="noopener">šeit</a>.
    </p>
</body>
</html>
