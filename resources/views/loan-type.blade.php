@php
    $locale = app()->getLocale();
@endphp

@extends('layouts.public')

@if (app()->getLocale() == 'ru')
    @section('title', $loanType->title . __(' ') . __(date('m') . 'date') . ' ' . date('Y') . __(' года'))
@else
    @section('title', $loanType->title . ' - ' . config('app.name'))
@endif
@section('description', $loanType->description)
@section('keywords', $loanType->keywords)

@section('schema')
    @if (count($lenders) > 0)
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "ItemList",
                "name": "{{ $loanType->h1 }}",
                "description": "{{ $loanType->description }}",
                "url": "{{ url()->current() }}",
                "itemListElement": [
                    @foreach ($lenders as $position => $lender)
                    {
                        "@type": "ListItem",
                        "position": {{ $position + 1 }},
                        "item": {
                            "@type": "FinancialProduct",
                            "name": "{{ $lender->name }}",
                            "url": "{{ route('go', ['lender_id' => $lender->id, 'lender_position' => $lender->position]) }}",
                            "image": "{{ $lender->image }}",
                            "offers": {
                                "@type": "AggregateOffer",
                                "priceCurrency": "EUR",
                                "lowPrice": "{{ $lender->min_amount }}",
                                "highPrice": "{{ $lender->max_amount }}"
                            }
                            @if ($lender->data?->work_time_m_f)
                            ,"hoursAvailable": {
                                "@type": "OpeningHoursSpecification",
                                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                                "opens": "{{ explode('-', $lender->data->work_time_m_f)[0] ?? '' }}",
                                "closes": "{{ explode('-', $lender->data->work_time_m_f)[1] ?? '' }}"
                            }
                            @endif
                        }
                    }@if (!$loop->last),@endif
                    @endforeach
                ]
            }
        </script>
    @endif

    @if (count($loanType->faqs()) > 0)
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": [
                    @foreach ($loanType->faqs() as $faq)
                    @php
                        $faq_details = App\Models\Admincp\Faqs\Faqs::find($faq->faq_id);
                    @endphp
                    @if ($faq_details)
                    {
                        "@type": "Question",
                        "name": @json($faq_details->question),
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": @json(strip_tags($faq_details->answer))
                        }
                    }@if (!$loop->last),@endif
                    @endif
                    @endforeach
                ]
            }
        </script>
    @endif
@endsection

@section('content')
<div class="container" itemscope itemtype="https://schema.org/FinancialService">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h2" itemprop="name">{{ $loanType->h1 ?: $loanType->name }}</h1>
            <ol class="breadcrumb">
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                    <a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a>
                </li>
                @if ($loanType->parent_type_id)
                    @php
                        $parentType = \App\Models\Admincp\LoanTypes\LoanTypes::find($loanType->parent_type_id);
                        $subTitle = mb_strtolower($loanType->name);
                        if ($parentType) {
                            $subTitle = trim(str_ireplace(mb_strtolower($parentType->name), '', $subTitle));
                        }
                        if ($loanType->route_name === 'uz-12-menesiem') {
                            $subTitle = 'uz 12 mēnešiem';
                        }
                    @endphp
                    @if ($parentType)
                        <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                            <a itemprop="url" href="/{{ $parentType->route_name }}"><span itemprop="title">{{ mb_strtolower($parentType->name) }}</span></a>
                        </li>
                    @endif
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/{{ $parentType->route_name }}/{{ $loanType->route_name }}"><span itemprop="title">{{ $subTitle }}</span></a>
                    </li>
                @else
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/{{ $loanType->route_name }}"><span itemprop="title">{{ mb_strtolower($loanType->name) }}</span></a>
                    </li>
                @endif
            </ol>
        </div>
        <div class="clearfix"></div>

        {{-- Main Column (840px desktop) --}}
        <div class="col-md-8 container-large">
            {{-- Comparison Table Panel --}}
            <div class="panel panel-default">
                <div class="panel-body loan-body2">
                    @if (!$loanType->parent_type_id)
                        @if ($loanType->id == 1)
                            <p>Palīdzēsim izvēlēties Tev piemērotāko <strong>kredītu internetā bez ķīlas</strong>, kuri ir pieejami summā <strong>no 50 &euro; līdz 25000 &euro;</strong>, ar termiņu no dažām dienām <strong>līdz 120 mēnešiem</strong>.<br>Salīdzināšanas tabulā pieejami, mūsuprāt, Latvijas populārākie {{ date('Y') }}. gada {{ __(date('m') . 'month_gen') }} īstermiņa aizdevumi, kurus vari aizņemties, galvenokārt, <strong>10-15 minūtēs</strong>!</p>
                        @elseif ($loanType->id == 3)
                            <p>Palīdzēsim izvēlēties Tev piemērotāko <strong>kredītu</strong>, kuri ir pieejami summā <strong>no 50 &euro; līdz 25000 &euro;</strong>, ar termiņu no dažām dienām <strong>līdz 10 gadiem</strong>.<br>Salīdzināšanas tabulā pieejami, mūsuprāt, Latvijas populārākie {{ date('Y') }}. gada {{ __(date('m') . 'month_gen') }} bezprocentu aizdevumi, kurus vari aizņemties, galvenokārt, <strong>10-15 minūtēs</strong>!</p>
                        @elseif ($loanType->id == 4)
                            <p>Palīdzēsim izvēlēties Tev piemērotāko <strong>kredītu apvienošanas</strong> iestādi, kura palīdzēs apvienot Tavus kredītus līdz 25000 &euro;, ar termiņu līdz vairākiem gadiem.<br>Salīdzināšanas tabulā pieejami, mūsuprāt, Latvijas populārākās {{ date('Y') }}. gada {{ __(date('m') . 'month_gen') }} kredītiestādes, kurās vari apvienot kredītus <strong>30-60 minūšu</strong> laikā!</p>
                        @elseif ($loanType->id == 5)
                            <p>Palīdzēsim izvēlēties Tev piemērotāko <strong>patēriņa kredītu internetā</strong>, kuri ir pieejami summā <strong>līdz 25000 &euro;</strong>, ar termiņu <strong>līdz 10 gadiem</strong>.<br>Salīdzināšanas tabulā pieejami, mūsuprāt, Latvijas populārākie {{ date('Y') }}. gada {{ __(date('m') . 'month_gen') }} nebanku patēriņa aizdevumi, kurus vari aizņemties, galvenokārt, <strong>30-60 minūtēs</strong>!</p>
                        @elseif ($loanType->id == 6)
                            <p>Palīdzēsim izvēlēties Tev piemērotāko <strong>patēriņa kredītu internetā</strong>, kuri ir pieejami summā <strong>līdz 25000 &euro;</strong>, ar termiņu <strong>līdz 10 gadiem</strong>.</p>
                        @endif
                    @endif

                    {{-- Loans List --}}
                    @if (count($lenders) > 0)
                        @foreach ($lenders as $position => $lender)
                            @php
                                // Format terms
                                $termText = '';
                                if (is_numeric($lender->max_term)) {
                                    if ($lender->max_term <= 30) {
                                        $termText = 'līdz ' . $lender->max_term . ' dienām';
                                    } elseif ($lender->max_term <= 360) {
                                        $termText = 'līdz ' . round($lender->max_term / 30) . ' mēnešiem';
                                    } elseif ($lender->max_term <= 2520) {
                                        $termText = 'līdz ' . round($lender->max_term / 30) . ' mēnešiem';
                                    } else {
                                        $termText = 'līdz ' . round($lender->max_term / 360) . ' gadiem';
                                    }
                                } else {
                                    $termText = $lender->max_term ?: 'līdz 3 gadiem';
                                }

                                // Work time
                                $workTimeMf = $lender->data?->work_time_m_f ?: $lender->receiving_time ?: '8:00 - 17:00';
                                $workTimeSa = $lender->data?->work_time_sa ?: '-';
                                $workTimeSu = $lender->data?->work_time_su ?: '-';
                                $rawDesc = $lender->data?->description ?: $lender->description;

                                // Extract only the first line of description
                                $firstLineDesc = '';
                                if ($rawDesc) {
                                    $cleanDesc = preg_replace('/<p[^>]*>\s*<p[^>]*>/i', '<p>', $rawDesc);
                                    $cleanDesc = preg_replace('/<\/p>\s*<\/p>/i', '</p>', $cleanDesc);

                                    if (preg_match_all('/<p\b[^>]*>(.*?)<\/p>/is', $cleanDesc, $pMatches)) {
                                        foreach ($pMatches[1] as $pContent) {
                                            $plain = trim(strip_tags(html_entity_decode($pContent, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
                                            $plain = trim(str_replace("\xc2\xa0", '', $plain));
                                            if ($plain !== '') {
                                                $firstLineDesc = trim($pContent);
                                                break;
                                            }
                                        }
                                    }

                                    if ($firstLineDesc === '') {
                                        $parts = preg_split('/(<br\s*\/?>|\r\n|\n|\r)/i', trim($cleanDesc));
                                        $firstLineDesc = !empty($parts[0]) ? trim($parts[0]) : trim($cleanDesc);
                                    }
                                }
                            @endphp

                            <div class="row loans2">
                                {{-- 1. Logo --}}
                                <div class="col-md-2 col-sm-12 col-xs-6">
                                    <p>
                                        <a onclick="ga('send', 'event', 'Affiliate link', '{{ $lender->route_name ?: strtolower($lender->name) }}');" rel="nofollow" target="_blank" href="{{ $lender->affiliate_link ?: route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}">
                                            <img class="img-responsive" alt="{{ $lender->name }}" src="{{ $lender->image }}">
                                        </a>
                                    </p>
                                </div>

                                {{-- 2. Reviews --}}
                                <div class="col-md-2 col-sm-3 reviews text-center col-xs-6">
                                    <p>
                                        <a href="/atsauksmes/{{ $lender->route_name ?: strtolower($lender->name) }}">
                                            <span class="glyphicon glyphicon-star"></span>
                                            <span class="glyphicon glyphicon-star"></span>
                                            <span class="glyphicon glyphicon-star"></span>
                                            <span class="glyphicon glyphicon-star"></span>
                                            <span class="glyphicon glyphicon-star"></span>
                                        </a>
                                    </p>
                                </div>
                                <div class="clearfix visible-xs-block"></div>

                                {{-- 3. Amount --}}
                                <div class="col-md-2 col-sm-3 amount col-xs-6 text-center">
                                    <p>{{ $lender->min_amount }}<span style="padding:0 1px;">-</span>{{ $lender->max_amount }}€</p>
                                </div>

                                {{-- 4. Term --}}
                                <div class="col-md-2 col-sm-3 col-xs-6 term text-center">
                                    <p>{{ $termText }}</p>
                                </div>
                                <div class="clearfix visible-xs-block"></div>

                                {{-- 5. Time --}}
                                <div class="col-md-2 col-sm-3 text-center time">
                                    <p style="line-height:18px;">
                                        <span style="color:green;">{{ $workTimeMf }}</span><br/>
                                        {{ $workTimeSa }}<br/>
                                        {{ $workTimeSu }}
                                    </p>
                                </div>
                                <div class="clearfix visible-sm-block"></div>

                                {{-- 6. Button --}}
                                <div class="col-md-2 text-right">
                                    <a onclick="ga('send', 'event', 'Affiliate link', '{{ $lender->route_name ?: strtolower($lender->name) }}');" rel="nofollow" target="_blank" class="get btn btn-success" href="{{ $lender->affiliate_link ?: route('go', ['lender_id' => $lender->id, 'lender_position' => $position]) }}">
                                        Saņemt<br>kredītu
                                    </a>
                                </div>
                                <div class="clearfix"></div>

                                {{-- 7. Description --}}
                                @if (!empty($firstLineDesc))
                                    <div class="col-md-12 description">
                                        <p>{!! $firstLineDesc !!}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Article from Database with layout choice --}}
            @if ($loanType->text)
                @php
                    $layout = $loanType->text_layout ?? 'panel';
                    $rawHtml = $loanType->text;
                @endphp

                @if ($layout === 'raw')
                    {{-- Raw layout: verbatim output without touching anything --}}
                    {!! $rawHtml !!}
                @else
                    {{-- Robust parser: extracts H2 title and inner body regardless of previous div wrappers --}}
                    @php
                        $articleH2 = '';
                        if (preg_match('/<div class="panel-heading">\s*<h2[^>]*>(.*?)<\/h2>\s*<\/div>/is', $rawHtml, $m)) {
                            $articleH2 = trim(strip_tags($m[1]));
                        } elseif (preg_match('/<div class="lead-intro">\s*<h2[^>]*>(.*?)<\/h2>\s*<\/div>/is', $rawHtml, $m)) {
                            $articleH2 = trim(strip_tags($m[1]));
                        } elseif (preg_match('/<h2[^>]*>(.*?)<\/h2>/is', $rawHtml, $m)) {
                            $articleH2 = trim(strip_tags($m[1]));
                        }

                        if (preg_match('/<div class="panel-body">(.*?)<\/div>\s*<\/div>\s*<\/div>\s*$/is', $rawHtml, $m)) {
                            $articleBody = trim($m[1]);
                        } else {
                            $cleaned = preg_replace('/<div class="lead-intro">.*?<\/div>/is', '', $rawHtml);
                            $cleaned = preg_replace('/<div class="panel-heading">.*?<\/div>/is', '', $cleaned);
                            $cleaned = preg_replace('/^<div class="seo-article">\s*<div class="panel panel-default">/is', '', $cleaned);
                            $cleaned = preg_replace('/<\/div>\s*<\/div>\s*$/is', '', $cleaned);
                            $articleBody = trim($cleaned);
                        }
                    @endphp

                    @if ($layout === 'lead_intro')
                        {{-- Lead Intro Layout: standalone H2 above the card --}}
                        @if ($articleH2)
                            <div class="lead-intro">
                                <h2>{{ $articleH2 }}</h2>
                            </div>
                        @endif
                        <div class="seo-article">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    {!! $articleBody !!}
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Classic Panel Layout: H2 inside grey panel-heading --}}
                        <div class="seo-article">
                            <div class="panel panel-default">
                                @if ($articleH2)
                                    <div class="panel-heading">
                                        <h2 class="panel-title">{{ $articleH2 }}</h2>
                                    </div>
                                @endif
                                <div class="panel-body">
                                    {!! $articleBody !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
            {{-- FAQs if present --}}
            @if (count($loanType->faqs()) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Biežāk uzdotie jautājumi</h2>
                    </div>
                    <div class="panel-body">
                        <x-faq :faqs="$loanType->faqs()" :faqPage="false"></x-faq>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Sidebar (330px desktop) --}}
        @include('components.right-sidebar')
    </div>
</div>
@endsection
