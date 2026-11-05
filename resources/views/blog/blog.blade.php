@extends('layouts.public')

@section('title', $blogPost->title . ' | ' . env('APP_NAME'))
@section('description', $blogPost->description)
@section('keywords', $blogPost->keywords)

@section('schema')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": "{{ $blogPost->name }}",
            "description": "{{ $blogPost->description }}",
            "image": "{{ $blogPost->image }}",
            "url": "{{ url()->current() }}",
            "datePublished": "{{ \Carbon\Carbon::parse($blogPost->created_at)->toIso8601String() }}",
            "dateModified": "{{ \Carbon\Carbon::parse($blogPost->updated_at)->toIso8601String() }}",
            "author": {
                "@type": "Organization",
                "name": "{{ env('APP_NAME') }}"
            },
            "publisher": {
                "@type": "Organization",
                "name": "{{ env('APP_NAME') }}"
            }
        }
    </script>
@endsection

@section('content')
    <div class="container" itemscope itemtype="https://schema.org/FinancialService">
        <div class="row">
            <div class="col-md-12">
                <h1 class="h2" itemprop="name">{{ $blogPost->name }}</h1>
                <ol class="breadcrumb">
                    <li itemscope itemtype="https://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/"><span itemprop="title">eirokrediti.lv</span></a>
                    </li>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="/blogs"><span itemprop="title">kredītu jaunumi</span></a>
                    </li>
                    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                        <a itemprop="url" href="{{ url()->current() }}"><span itemprop="title">{{ $blogPost->name }}</span></a>
                    </li>
                </ol>
            </div>
            <div class="clearfix"></div>

            {{-- Main Column (840px desktop) --}}
            <div class="col-md-8 container-large">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @php
                            $content = trim($blogPost->text);
                            // Strip any redundant outer panel wrapper divs
                            while (preg_match('/^\s*<div\s+class=[\"\'\s]*panel\s+panel-default[\"\'\s]*>\s*<div\s+class=[\"\'\s]*panel-body[\"\'\s]*>/i', $content)) {
                                $content = preg_replace('/^\s*<div\s+class=[\"\'\s]*panel\s+panel-default[\"\'\s]*>\s*<div\s+class=[\"\'\s]*panel-body[\"\'\s]*>/i', '', $content);
                                $content = preg_replace('/<\/div>\s*<\/div>\s*$/i', '', $content);
                                $content = trim($content);
                            }
                        @endphp

                        @if (str_contains($content, 'id="open_blog"'))
                            {!! $content !!}
                        @else
                            <div id="open_blog">
                                @if ($blogPost->image)
                                    <div class="image">
                                        <p><img class="img-responsive" src="{{ $blogPost->image }}" alt="{{ $blogPost->name }}"/></p>
                                    </div>
                                @endif

                                @if ($blogPost->source)
                                    <div id="intro_text">
                                        {!! $blogPost->source !!}
                                    </div>
                                @endif

                                <div id="text">
                                    {!! $content !!}
                                </div>
                            </div>
                        @endif

                        {{-- Native Clean Share Buttons (Code-side, no obsolete tracking scripts) --}}
                        @include('components.share-buttons', ['title' => $blogPost->name])
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
                                    <img class="img-responsive" alt="Sosocredit" src="https://www.eirokrediti.lv/images/other/20242407112054_5143.jpg">
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
                                    <img class="img-responsive" alt="Kreditstar" src="https://www.eirokrediti.lv/images/other/kreditstar.jpg">
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
                                    <img class="img-responsive" alt="Viasms" src="https://www.eirokrediti.lv/images/other/viasms-lv-kv.jpg">
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
                                    <img class="img-responsive" alt="Soscredit" src="https://www.eirokrediti.lv/images/other/sosocredit.jpg">
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
                @php
                    $sidebarPosts = App\Models\Admincp\Sections\Sections::where('type', 'blog')
                        ->whereNotNull('parent_section_id')
                        ->where('lang', app()->getLocale())
                        ->where('active', true)
                        ->orderBy('order')
                        ->get();
                @endphp

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">TOP jaunumi</h2>
                    </div>
                    <div class="panel-body loan-body">
                        @foreach ($sidebarPosts as $post)
                            @php
                                $isActive = ($post->id === $blogPost->id);
                                $postUrl = '/blogs/' . $post->route_name;
                            @endphp
                            <div class="row small-links">
                                <div class="col-md-12">
                                    <a class="{{ $isActive ? 'active' : '' }}" href="{{ $postUrl }}">
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
