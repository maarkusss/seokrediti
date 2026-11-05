@extends('layouts.public')

@section('title', $section->title ?: 'Blogs par kredītiem - eirokrediti.lv')
@section('description', $section->description)
@section('keywords', $section->keywords)

@section('schema')
    @if (count($blogPosts) > 0)
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Blog",
                "name": "{{ $section->title }}",
                "description": "{{ $section->description }}",
                "url": "{{ url()->current() }}",
                "blogPost": [
                    @foreach ($blogPosts as $blogPost)
                    {
                        "@type": "BlogPosting",
                        "headline": "{{ $blogPost->name }}",
                        "image": "{{ $blogPost->image }}",
                        "datePublished": "{{ \Carbon\Carbon::parse($blogPost->created_at)->toIso8601String() }}",
                        "url": "{{ route('section.deep', ['name' => $section->route_name, 'deep_name' => $blogPost->route_name]) }}"
                    }@if (!$loop->last),@endif
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
                <h1 class="h2" itemprop="name">{{ $section->h1 ?: 'Blogs' }}</h1>
                <ol class="breadcrumb">
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a>
                    </li>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/blogs"><span itemprop="title">kredītu jaunumi</span></a>
                    </li>
                </ol>
            </div>
            <div class="clearfix"></div>

            {{-- Main Column (840px desktop) --}}
            <div class="col-md-8 container-large">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="blogs">
                            @foreach ($blogPosts as $blogPost)
                                @php
                                    $blogUrl = route('section.deep', ['name' => $section->route_name, 'deep_name' => $blogPost->route_name]);
                                @endphp
                                <div class="blog">
                                    <h2 @if($loop->first) style="margin-top:0;" @endif>
                                        <a href="{{ $blogUrl }}">{{ $blogPost->name }}</a>
                                    </h2>
                                    <div class="row">
                                        @if ($blogPost->image)
                                            <div class="col-md-12">
                                                <div class="thumbnails">
                                                    <a href="{{ $blogUrl }}">
                                                        <img class="img-responsive" src="{{ $blogPost->image }}" alt="{{ $blogPost->name }}"/>
                                                        <span></span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            {!! $blogPost->source ?: '<p>' . strip_tags($blogPost->text) . '</p>' !!}
                                            <p>
                                                <a class="btn btn-sm btn-primary" href="{{ $blogUrl }}" role="button">Skatīt vairāk</a>
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
                </div>
            </div>

            {{-- Right Sidebar (330px desktop) --}}
            <div class="col-md-4 container-small">
                {{-- Panel 1: TOP 5 ātrie kredīti --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">TOP 5 <a href="/atrie-krediti">ātrie kredīti</a> {{ __(date('m').'date') }}</h2>
                    </div>
                    <div class="panel-body loan-body">
                        <div class="row small-loans">
                            <div class="col-md-4 col-sm-4 col-xs-4 logo">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'sosocredit');" rel="nofollow" target="_blank" href="/go/sosocredit">
                                    <img class="img-responsive" alt="Sosocredit" src="/images/other/20242407112054_5143.jpg">
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center amount">
                                līdz <span>25000&euro;</span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'sosocredit');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="/go/sosocredit">Saņemt</a>
                            </div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-sm-4 col-xs-4 logo">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'kreditstar');" rel="nofollow" target="_blank" href="/go/kreditstar">
                                    <img class="img-responsive" alt="Kreditstar" src="/images/other/kreditstar.jpg">
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center amount">
                                līdz <span>25000&euro;</span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'kreditstar');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="/go/kreditstar">Saņemt</a>
                            </div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-sm-4 col-xs-4 logo">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'viasms');" rel="nofollow" target="_blank" href="/go/viasms">
                                    <img class="img-responsive" alt="Viasms" src="/images/other/viasms-lv-kv.jpg">
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center amount">
                                līdz <span>5000&euro;</span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'viasms');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="/go/viasms">Saņemt</a>
                            </div>
                        </div>
                        <div class="row small-loans">
                            <div class="col-md-4 col-sm-4 col-xs-4 logo">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'soscredit');" rel="nofollow" target="_blank" href="/go/soscredit">
                                    <img class="img-responsive" alt="Soscredit" src="/images/other/sosocredit.jpg">
                                </a>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-center amount">
                                līdz <span>25000&euro;</span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                                <a onclick="ga('send', 'event', 'Affiliate link', 'soscredit');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="/go/soscredit">Saņemt</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel 2: Facebook Counter --}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12" style="background:url(/images/other/palidzejusi.jpg) no-repeat left center;padding-left:130px;min-height:85px;">
                                <p>Tavs palīgs kredītu salīdzināšanā.<br/>Mūsu pakalpojumus izmantojuši <strong>844599</strong> reizes.</p>
                                <div style="margin-top: 8px;">
                                    <iframe title="Facebook Page Like" src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Feirokrediti&width=90&layout=button_count&action=like&size=small&share=false&height=21&appId" width="90" height="21" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel 3: TOP jaunumi --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">TOP jaunumi</h2>
                    </div>
                    <div class="panel-body loan-body">
                        @foreach ($blogPosts as $post)
                            <div class="row small-links">
                                <div class="col-md-12">
                                    <a href="{{ route('section.deep', ['name' => $section->route_name, 'deep_name' => $post->route_name]) }}">
                                        {{ $post->name }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
