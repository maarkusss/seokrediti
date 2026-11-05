@extends('layouts.public')

@section('title', $homepage->title ?: 'Tavs palīgs kredītu salīdzināšanā - eirokrediti.lv')
@section('description', $homepage->description)
@section('keywords', $homepage->keywords)

@section('content')
    <div class="container" itemscope itemtype="https://schema.org/FinancialService">
        <div class="row">
            <div class="col-md-12">
                <h1 class="h2" itemprop="name">{{ $homepage->h1 ?: 'Palīgs kredītu salīdzināšanā' }}</h1>
                <ol class="breadcrumb">
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a>
                    </li>
                </ol>
            </div>
            <div class="clearfix"></div>

            {{-- Main Column (840px desktop) --}}
            <div class="col-md-8 container-large">
                {{-- Panel 1: Carousel & Intro Text --}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="carousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel" data-slide-to="0" class="active" aria-label="Slīds 1"></li>
                                <li data-target="#carousel" data-slide-to="1" aria-label="Slīds 2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="/images/other/salidzini-kreditus-ar-telefonu-vai-plansetdatoru.jpg"
                                        alt="salīdzini kredītus jebkurā ierīcē">
                                    <div class="carousel-caption">
                                        <p class="h4" style="margin:0;font-weight:600;">Salīdzini dažādus kredītus jebkurā ierīcē</p>
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="/images/other/atrie-eiro.jpg" alt="saņem izdevīgāko kredītu">
                                    <div class="carousel-caption">
                                        <p class="h4" style="margin:0;font-weight:600;">Izvēlies un aizņemies sev izdevigāko kredītu</p>
                                    </div>
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" aria-label="Iepriekšējais slīds">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Iepriekšējais</span>
                            </a>
                            <a class="right carousel-control" href="#carousel" role="button" data-slide="next" aria-label="Nākamais slīds">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Nākamais</span>
                            </a>
                        </div>

                        {!! $homepage->text !!}
                    </div>
                </div>

                {{-- Panel 2: Blog Articles --}}
                @if (isset($latestBlogs) && count($latestBlogs) > 0)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title">
                                <a href="{{ $blogSection ? '/' . $blogSection->route_name : '/blogs' }}">
                                    {{ $blogSection->name ?? 'Kredītu jaunumi' }}
                                </a>
                            </h2>
                        </div>
                        <div class="panel-body">
                            @foreach ($latestBlogs as $blog)
                                @php
                                    $blogUrl = '/' . ($blogSection->route_name ?? 'blogs') . '/' . $blog->route_name;
                                    $excerpt = $blog->description ?: ($blog->source ?: \Illuminate\Support\Str::limit(strip_tags($blog->text), 220));
                                @endphp
                                <div class="blog">
                                    <h3 class="h4" @if($loop->first) style="margin-top:0;" @endif>
                                        <a href="{{ $blogUrl }}">{{ $blog->name }}</a>
                                    </h3>
                                    <div class="row">
                                        @if ($blog->image)
                                            <div class="col-md-12">
                                                <div class="thumbnails">
                                                    <a href="{{ $blogUrl }}">
                                                        <img class="img-responsive" src="{{ $blog->image }}"
                                                            alt="{{ $blog->name }}">
                                                        <span></span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <p>{!! $excerpt !!}</p>
                                            <p>
                                                <a class="btn btn-sm btn-primary" href="{{ $blogUrl }}" role="button">
                                                    Skatīt vairāk
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @if (!$loop->last)
                                    <div class="clearfix"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column (330px desktop) --}}
            @include('components.right-sidebar')
        </div>
    </div>
@endsection
