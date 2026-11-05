@extends('layouts.public')

@section('title', $section->title ?: 'Ātro kredītu un patēriņu kredītu atsauksmes')
@section('description', $section->description ?: 'Populārāko ātro kredītu atsauksmes. Izveidota sadaļa, lai palīdzētu pieņemt labāko risinājumu.')
@section('keywords', $section->keywords)

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
@endphp
<div class="container" itemscope itemtype="https://schema.org/FinancialService">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h2" itemprop="name">{{ $section->h1 ?: 'Kredītu atsauksmes' }}</h1>
            <ol class="breadcrumb">
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a></li>
                <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="/atsauksmes"><span itemprop="title">kredītu atsauksmes</span></a></li>
            </ol>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-8 container-large">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>Lai Jūs varētu pieņemt labāko risinājumu, izvēlēties Jums izdevīgāko aizdevēju kompāniju, esam izveidojuši sadaļu atsauksmes par aizdevējiem.</p>
                    <h2 class="h4" style="margin-top:0;margin-bottom:20px;">Izvēlies attiecīgo aizdevēju</h2>
                    <div class="row">
                        @foreach ($lendersList as $lender)
                            <div class="col-md-2 col-sm-3 col-xs-6" style="height:90px;">
                                <a title="{{ $lender->name }} atsauksmes" href="/atsauksmes/{{ $lender->route_name }}">
                                    <img class="img-responsive" alt="{{ $lender->name }}" src="{{ $lender->image }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h2 class="panel-title">Pēdējās atsauksmes</h2></div>
                <div class="panel-body loan-body">
                    @if (isset($recentReviews) && $recentReviews->count() > 0)
                        @foreach ($recentReviews as $rev)
                            <div class="row small-reviews">
                                <div class="col-md-2 col-xs-6">
                                    <a href="/atsauksmes/{{ $rev->lender->route_name ?? '' }}">
                                        <img class="img-responsive" alt="{{ $rev->lender->name ?? '' }}" src="{{ $rev->lender->image ?? '' }}">
                                    </a>
                                </div>
                                <div class="col-md-10 col-xs-6 text-right">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($rev->rating >= $i)
                                            <span class="glyphicon glyphicon-star"></span>
                                        @else
                                            <span class="glyphicon glyphicon-star-empty"></span>
                                        @endif
                                    @endfor
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <blockquote>{{ $rev->text }}</blockquote>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 container-small">
            <div class="panel panel-default">
                <div class="panel-heading"><h2 class="panel-title">TOP 5 <a href="/atrie-krediti">ātrie kredīti</a> {{ $currentMonth }}</h2></div>
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
                <div class="panel-heading"><h2 class="panel-title">Atsauksmes</h2></div>
                <div class="panel-body loan-body">
                    @foreach ($lendersList as $lender)
                        <div class="row small-links">
                            <div class="col-md-8 col-xs-6">
                                <a href="/atsauksmes/{{ $lender->route_name }}">{{ $lender->name }}</a>
                            </div>
                            <div class="col-md-4 col-xs-6 text-right count">{{ $lender->reviews_count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
