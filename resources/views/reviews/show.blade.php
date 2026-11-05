@extends('layouts.public')

@section('title', 'Aizdevēja ' . $lender->name . ' atsauksmes un informācija')
@section('description', 'Iespējams aplūkot un ierakstīt ' . $lender->name . ' atsauksmes. Apskati atsauksmes un informāciju vai arī ieraksti komentāru.')
@section('keywords', $lender->keywords ?? '')

@section('content')
@php
    $lvMonths = [
        1 => 'janvārī', 2 => 'februārī', 3 => 'martā', 4 => 'aprīlī',
        5 => 'maijā', 6 => 'jūnijā', 7 => 'jūlijā', 8 => 'augustā',
        9 => 'septembrī', 10 => 'oktobrī', 11 => 'novembrī', 12 => 'decembrī'
    ];
    $currentMonth = $lvMonths[now()->month] ?? 'šomēnes';

    $topLenders = \App\Models\Admincp\Lenders\Lenders::where('active', 1)
        ->where('top_lender', 1)
        ->orderBy('position', 'asc')
        ->take(5)
        ->get();

    if ($topLenders->isEmpty()) {
        $topLenders = \App\Models\Admincp\Lenders\Lenders::where('active', 1)
            ->orderBy('position', 'asc')
            ->take(5)
            ->get();
    }

    $lendersList = \App\Models\Admincp\Lenders\Lenders::withCount('reviews')
        ->orderBy('name', 'asc')
        ->get();

    $minMonths = ($lender->min_term && $lender->min_term >= 30) ? round($lender->min_term / 30) : null;
    $maxMonths = ($lender->max_term && $lender->max_term >= 30) ? round($lender->max_term / 30) : null;

    $workMf = $lender->data->work_time_m_f ?? null;
    $workSa = $lender->data->work_time_sa ?? null;
    $workSu = $lender->data->work_time_su ?? null;

    if (!$workMf || !$workSa || !$workSu) {
        $parts = preg_split('/\s+(?=\d|—|-)/', trim($lender->receiving_time ?? ''));
        $workMf = $workMf ?: ($parts[0] ?? '8:00 - 20:00');
        $workSa = $workSa ?: ($parts[1] ?? $workMf);
        $workSu = $workSu ?: ($parts[2] ?? $workMf);
    }
@endphp

<div class="container" itemscope itemtype="https://schema.org/FinancialService">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h2" itemprop="name">{{ $lender->name }} atsauksmes un informācija</h1>
            <ol class="breadcrumb">
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a></li>
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/atsauksmes"><span itemprop="title">kredītu atsauksmes</span></a></li>
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/atsauksmes/{{ $lender->route_name }}"><span itemprop="title">{{ $lender->name }}</span></a></li>
            </ol>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-8 container-large">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>
                        <a onclick="ga('send', 'event', 'Affiliate link', '{{ $lender->route_name }}');" rel="nofollow" target="_blank" href="/go/{{ $lender->route_name }}">
                            <img itemprop="image" class="img-responsive" alt="{{ $lender->name }}" src="{{ $lender->image }}">
                        </a>
                    </p>
                    <meta itemprop="priceRange" content="{{ $lender->min_amount }} &euro; - {{ $lender->max_amount }} &euro;">
                    <p>Kredīta summa: no <strong>{{ $lender->min_amount }}</strong> &euro; līdz <strong>{{ $lender->max_amount }}</strong> &euro;</p>
                    <p>Kredīta termiņš: no <strong>{{ $minMonths ?: ($lender->min_term ?: 30) }}</strong> {{ $minMonths ? 'mēnešiem' : 'dienām' }} līdz <strong>{{ $maxMonths ?: ($lender->max_term ?: 1080) }}</strong> {{ $maxMonths ? 'mēnešiem' : 'dienām' }}</p>
                    <p>Aktivizācijas maksa: <strong>0.01 &euro;</strong></p>
                    <p>Vecuma ierobežojums: <strong>{{ $lender->min_years ?: 18 }}</strong> - <strong>{{ $lender->max_years ?: 75 }}</strong> gadi</p>
                    <p>Darba laiks darbadienās: <strong>{{ $workMf }}</strong></p>
                    <p>Darba laiks sestdienā: <strong>{{ $workSa }}</strong></p>
                    <p>Darba laiks svētdienā: <strong>{{ $workSu }}</strong></p>
                    <a onclick="ga('send', 'event', 'Affiliate link', '{{ $lender->route_name }}');" rel="nofollow" target="_blank" class="get btn btn-success get-lg" href="/go/{{ $lender->route_name }}">Saņemt kredītu</a>
                    <hr>

                    @if (!empty($lender->data->section_html))
                        {!! $lender->data->section_html !!}
                    @else
                        @if (!empty($lender->data->description))
                            <p>{!! $lender->data->description !!}</p>
                        @else
                            <p>Patēriņa kredīts līdz {{ $lender->max_amount }} € no {{ $lender->min_years ?: 18 }} līdz {{ $lender->max_years ?: 75 }} gadiem.</p>
                        @endif
                        <hr>
                        <a onclick="ga('send', 'event', 'Affiliate link', '{{ $lender->route_name }}');" rel="nofollow" target="_blank" class="get btn btn-success get-lg" href="/go/{{ $lender->route_name }}">Saņemt kredītu</a>
                    @endif

                    <div class="row"></div>
                    <h2>{{ $lender->name }} atsauksmes</h2>

                    <div id="reviews">
                        <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                            <meta itemprop="ratingValue" content="{{ $average_rating }}">
                            <meta itemprop="reviewCount" content="{{ $reviews ? $reviews->count() : 0 }}">
                        </div>

                        @if ($reviews && $reviews->count() > 0)
                            @foreach ($reviews as $review)
                                @php
                                    $c = \Carbon\Carbon::parse($review->created_at);
                                    $diffYears = (int)$c->diffInYears(now());
                                    $diffMonths = (int)$c->diffInMonths(now());
                                    if ($diffYears >= 1) {
                                        $dateStr = $diffYears == 1 ? 'pirms gada' : ('pirms ' . $diffYears . ' gadiem');
                                    } elseif ($diffMonths >= 1) {
                                        $dateStr = $diffMonths == 1 ? 'pirms mēneša' : ('pirms ' . $diffMonths . ' mēnešiem');
                                    } else {
                                        $diffDays = (int)$c->diffInDays(now());
                                        $dateStr = $diffDays == 0 ? 'šodien' : ($diffDays == 1 ? 'pirms dienas' : ('pirms ' . $diffDays . ' dienām'));
                                    }
                                @endphp
                                <div class="row big-reviews{{ $loop->first ? ' review-first' : '' }}" itemprop="review" itemscope itemtype="https://schema.org/Review">
                                    <div class="col-md-8 name">
                                        <strong itemprop="author">{{ $review->name }}</strong>
                                        <span class="lender">{{ $lender->name }} atsauksmes un informācija</span>
                                        <span class="date">{{ $dateStr }}<meta itemprop="datePublished" content="{{ $review->created_at }}"></span>
                                    </div>
                                    <div class="col-md-4 text-right" itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($review->rating >= $i)
                                                <span class="glyphicon glyphicon-star"></span>
                                            @else
                                                <span class="glyphicon glyphicon-star-empty"></span>
                                            @endif
                                        @endfor
                                        <meta itemprop="ratingValue" content="{{ $review->rating }}">
                                    </div>
                                    <div class="col-md-12">
                                        <blockquote itemprop="description">{{ $review->text }}</blockquote>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <h3>Pievieno atsauksmi</h3>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div style="padding:10px 0;">
                        <form name="send_feedback" method="post" action="{{ route('companies.review.create', ['companies_name' => $section->route_name, 'route_name' => $lender->route_name]) }}" id="form">
                            @csrf
                            <input type="hidden" name="lenderID" value="{{ $lender->id }}">

                            <div style="padding-bottom:10px;">
                                <label for="send_feedback_name" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">Vārds <span style="color:#D50000;">*</span></label>
                                <input id="send_feedback_name" class="form-control" type="text" name="name" value="{{ old('name') }}" required />
                            </div>
                            <div style="padding-bottom:10px;">
                                <label for="send_feedback_rating" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">Vērtējums <span style="color:#D50000;">*</span></label>
                                <select id="send_feedback_rating" class="form-control" name="rating" required>
                                    <option value="">Izvēlies vērtējumu</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 zvaigzne</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 zvaigznes</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 zvaigznes</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 zvaigznes</option>
                                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 zvaigznes</option>
                                </select>
                            </div>
                            <div style="padding-bottom:10px;">
                                <label for="send_feedback_comment" style="padding:0 0 2px 5px;font-weight:normal;display:block;margin:0;">Komentārs </label>
                                <textarea id="send_feedback_comment" class="form-control" name="comment" cols="20" rows="5" style="overflow:auto" required>{{ old('comment') }}</textarea>
                            </div>

                            <div style="padding-bottom:10px;">
                                <div style="position:relative;width:200px;height:70px;background:#f8fafc;border:1px solid #cbd5e1;border-radius:4px;overflow:hidden;margin-bottom:8px;">
                                    <img src="{{ route('captcha') }}" id="captcha" alt="Drošības kods" style="width:200px;height:70px;display:block;" />
                                    <a href="javascript:;" onclick="document.getElementById('captcha').src='{{ route('captcha') }}?sid='+Math.random();document.getElementById('captcha-form').focus();" title="Atjaunot drošības kodu" style="position:absolute;top:4px;right:4px;background:rgba(255,255,255,0.9);border:1px solid #cbd5e1;border-radius:3px;padding:3px 5px;display:inline-flex;align-items:center;justify-content:center;line-height:0;text-decoration:none;">
                                        <svg style="width:16px;height:16px;color:#2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </a>
                                </div>
                                <input type="text" class="form-control" name="captcha" id="captcha-form" maxlength="16" autocomplete="off" required placeholder="Ievadi drošības kodu" style="width:200px;" />
                            </div>

                            <div>
                                <input class="form-control btn btn-sm btn-primary" name="send_feedback" value="Pievienot" type="submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 container-small">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">TOP 5 <a href="/atrie-krediti">ātrie kredīti</a> {{ $currentMonth }}</h2>
                </div>
                <div class="panel-body loan-body">
                    @foreach ($topLenders as $top)
                        <div class="row small-loans">
                            <div class="col-md-4 col-sm-4 col-xs-4 logo">
                                <a onclick="ga('send', 'event', 'Affiliate link', '{{ $top->route_name }}');" rel="nofollow" target="_blank" href="{{ $top->affiliate_link ?: ('/go/' . $top->route_name) }}">
                                    <img class="img-responsive" alt="{{ $top->name }}" src="{{ $top->image }}">
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center amount">līdz <span>{{ $top->max_amount }}&euro;</span></div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <a onclick="ga('send', 'event', 'Affiliate link', '{{ $top->route_name }}');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="{{ $top->affiliate_link ?: ('/go/' . $top->route_name) }}">Saņemt</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Atsauksmes</h2>
                </div>
                <div class="panel-body loan-body">
                    @foreach ($lendersList as $sideLender)
                        @php
                            $isActive = ($sideLender->route_name == $lender->route_name);
                        @endphp
                        <div class="row small-links">
                            <div class="col-md-8 col-xs-6">
                                <a @if($isActive)class="active" @endif href="/atsauksmes/{{ $sideLender->route_name }}">{{ $sideLender->name }}</a>
                            </div>
                            <div class="col-md-4 col-xs-6 text-right count">{{ $sideLender->reviews_count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
