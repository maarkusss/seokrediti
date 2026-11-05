<div class="col-md-4 container-small">
    @php
        $routeName = $loanType->route_name ?? '';
        $parentTypeId = $loanType->parent_type_id ?? null;
        $isHomepage = request()->routeIs('homepage') || request()->path() === '/' || (isset($homepage) && $homepage->type === 'home');
        $showTop5 = in_array($routeName, ['uz-6-menesiem', 'uz-12-menesiem', 'uz-24-menesiem']) || $isHomepage;
        $showSteps = (in_array($routeName, ['atrie-krediti', 'bezprocentu-krediti']) || ($parentTypeId == 1 && $routeName != 'ar-sliktu-kreditvesturi') || ($parentTypeId == 1)) && !$isHomepage;
        if (isset($section) && $section->type != 'home') {
            $showSteps = false;
        }
        if (isset($loanType) && ($loanType->id == 4 || $loanType->id == 5 || $loanType->id == 6 || ($parentTypeId == 5))) {
            $showSteps = false;
        }
    @endphp

    {{-- TOP 5 panel for duration subpages --}}
    @if ($showTop5)
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
                        līdz <span>25000€</span>
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
                        līdz <span>25000€</span>
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
                        līdz <span>5000€</span>
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
                        līdz <span>25000€</span>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 text-right">
                        <a onclick="ga('send', 'event', 'Affiliate link', 'soscredit');" rel="nofollow" target="_blank" style="width:100%;" class="get btn btn-sm btn-success" href="/go/soscredit">Saņemt</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Helped count panel --}}
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12" style="background:url(/images/other/palidzejusi.jpg) no-repeat left center;padding-left:130px;min-height:85px;">
                    <p>Tavs palīgs kredītu salīdzināšanā.<br/>Mūsu pakalpojumus izmantojuši <strong>844422</strong> reizes.</p>
                    <div style="margin-top: 8px;">
                        <iframe title="Facebook Page Like" src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Feirokrediti&width=90&layout=button_count&action=like&size=small&share=false&height=21&appId" width="90" height="21" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Steps banner panel --}}
    @if ($showSteps)
        <div class="panel panel-default">
            <div class="panel-body">
                <p><img title="Salīdzini kredītus un izvēlies izdevīgāko" alt="salīdzini kredītus" class="img-responsive" src="/images/other/eirokrediti.jpg"></p>
                <p class="text-center"><img alt="solis" src="/images/other/bulta.jpg"></p>
                <p><img title="Reģistrējies un aizpildi pieteikumu" alt="aizpildi pieteikumu" class="img-responsive" src="/images/other/forma.jpg"></p>
                <p class="text-center"><img alt="solis" src="/images/other/bulta.jpg"></p>
                <p><img title="Saņem naudu savā kontā" alt="saņem naudu" class="img-responsive" src="/images/other/sanem-naudu.jpg"></p>
            </div>
        </div>
    @endif

    {{-- Recent reviews panel --}}
    @php
        $sidebarReviews = \App\Models\Reviews::with('lender')
            ->where('lang', app()->getLocale())
            ->whereNotNull('text')
            ->where('text', '!=', '')
            ->whereHas('lender')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    @endphp

    @if ($sidebarReviews->count() > 0)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title"><a href="/atsauksmes">Pēdējās atsauksmes</a></h2>
            </div>
            <div class="panel-body loan-body">
                @foreach ($sidebarReviews as $rev)
                    @php
                        $lenderSlug = $rev->lender->route_name ?? \Illuminate\Support\Str::slug($rev->lender->name);
                        $reviewLink = '/atsauksmes/' . $lenderSlug;
                    @endphp
                    <div class="row small-reviews">
                        <div class="col-md-6 col-xs-6">
                            <a href="{{ $reviewLink }}">
                                <img class="img-responsive" alt="{{ $rev->lender->name }}" src="{{ $rev->lender->image }}">
                            </a>
                        </div>
                        <div class="col-md-6 col-xs-6 text-right" aria-label="{{ $rev->rating }} no 5 zvaigznēm">
                            <span class="sr-only">{{ $rev->rating }} no 5 zvaigznēm</span>
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="glyphicon {{ $i <= $rev->rating ? 'glyphicon-star' : 'glyphicon-star-empty' }}" aria-hidden="true"></span>
                            @endfor
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <blockquote>{{ $rev->text }}</blockquote>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
