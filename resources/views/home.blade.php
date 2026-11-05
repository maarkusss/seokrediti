@extends('layouts.public')

@section('title', clean_legacy_brand($homepage->title) ?: ('Kredītu salīdzinājums internetā — ' . brand_tagline()))
@section('description', clean_legacy_brand($homepage->description) ?: 'Salīdzini licencētu Latvijas aizdevēju ātros kredītus, patēriņa kredītus un kredītu apvienošanu vienuviet. Aprēķini ikmēneša maksājumu, pārbaudi GPL un lasi atsauksmes.')

@section('schema')
    @if (!empty($lenders))
        @php
            $homeItemList = [];
            $idx = 1;
            foreach ($lenders as $lenderItem) {
                $slug = $lenderItem->route_name ?: \Illuminate\Support\Str::slug($lenderItem->name);
                $homeItemList[] = [
                    '@type' => 'ListItem',
                    'position' => $idx++,
                    'item' => [
                        '@type' => 'LoanOrCredit',
                        'name' => $lenderItem->name,
                        'url' => url('/' . ($reviewSection?->route_name ?: 'atsauksmes') . '/' . $slug),
                        'image' => $lenderItem->image ? url($lenderItem->image) : null,
                        'amount' => [
                            '@type' => 'MonetaryAmount',
                            'currency' => 'EUR',
                            'minValue' => (int) ($lenderItem->min_amount ?: 50),
                            'maxValue' => (int) ($lenderItem->max_amount ?: 5000),
                        ],
                    ],
                ];
            }
        @endphp
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => clean_legacy_brand($homepage->h1 ?: 'Populārākie kredītu piedāvājumi Latvijā'),
                'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                'numberOfItems' => count($homeItemList),
                'itemListElement' => $homeItemList,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endsection

@section('content')
    {{-- 1. Front-Page Lead Editorial & Payment Calculator --}}
    <section class="border-b border-rule">
        <div class="wrap py-8 sm:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                {{-- Lead Column --}}
                <div class="lg:col-span-8 lg:pr-8 lg:border-r lg:border-rule">
                    <div class="kicker mb-3">Latvijas kreditēšanas tirgus pārskats · {{ date('Y') }}</div>

                    <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink leading-[1.08]">
                        {{ clean_legacy_brand($homepage->h1) ?: 'Neatkarīgs kredītu un procentu likmju salīdzinājums' }}
                    </h1>

                    <p class="standfirst mt-4">
                        Salīdziniet Latvijas Republikā licencēto aizdevēju piedāvājumus pēc faktiskās gada procentu likmes (GPL), aizdevuma summas, atmaksas termiņa un klientu atsauksmēm — vienā pārskatāmā tabulā bez reklāmas trokšņa.
                    </p>

                    {{-- Key Market Figures Strip --}}
                    <dl class="grid grid-cols-3 gap-4 mt-8 pt-5 border-t border-ink">
                        <div>
                            <dt class="meta uppercase tracking-wider">Licencēti aizdevēji</dt>
                            <dd class="font-serif text-2xl sm:text-3xl font-semibold text-ink num mt-0.5">{{ count($lenders) }}</dd>
                        </div>
                        <div class="border-l border-rule pl-4">
                            <dt class="meta uppercase tracking-wider">Jauno klientu likme</dt>
                            <dd class="font-serif text-2xl sm:text-3xl font-semibold text-claret num mt-0.5">no 0%</dd>
                        </div>
                        <div class="border-l border-rule pl-4">
                            <dt class="meta uppercase tracking-wider">Lasītāju vērtējums</dt>
                            <dd class="font-serif text-2xl sm:text-3xl font-semibold text-ink num mt-0.5">{{ str_replace('.', ',', (string) ($avgPortalRating ?? '4.8')) }}/5</dd>
                        </div>
                    </dl>

                    {{-- Index of Sections --}}
                    @if (isset($parentLoanTypes) && $parentLoanTypes->isNotEmpty())
                        <div class="mt-8 pt-5 border-t border-rule">
                            <div class="text-[11px] font-semibold uppercase tracking-wider text-ink-muted mb-2.5">Rubrikas un salīdzinājumi</div>
                            <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm">
                                @foreach ($parentLoanTypes as $heroCat)
                                    <a href="{{ route('section', ['name' => $heroCat->route_name]) }}" class="link font-medium">
                                        {{ clean_legacy_brand($heroCat->name) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Calculator Column --}}
                <div class="lg:col-span-4 lg:max-w-[390px] lg:ml-auto w-full">
                    <x-loan-calculator :initialAmount="1000" :maxAmount="15000" :initialMonths="12" :maxMonths="72" />
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Main Lender Comparison Table --}}
    <section id="piedavajumi"
             x-data="lenderFilterList({ totalCount: {{ count($lenders) }} })"
             @credit-filter-change.window="applyExternalFilter($event.detail)"
             class="py-10 sm:py-14 scroll-mt-16"
             aria-labelledby="offers-heading">
        <div class="wrap">
            <div class="section-head">
                <div>
                    <span class="kicker">Salīdzinājuma tabula</span>
                    <h2 id="offers-heading" class="section-title mt-1">
                        Licencēto kredītdevēju piedāvājumi ({{ date('Y') }})
                    </h2>
                </div>

                {{-- Rectangular Filter Tabs --}}
                <div class="flex flex-wrap items-center gap-1.5 pt-2 sm:pt-0">
                    <button type="button"
                            @click="resetFilters()"
                            class="tab"
                            :class="!activeFilter ? 'tab-on' : ''">
                        Visi ({{ count($lenders) }})
                    </button>
                    <button type="button"
                            @click="toggleChip('zeroOnly')"
                            class="tab"
                            :class="zeroOnly ? 'tab-on' : ''">
                        0% pirmais kredīts
                    </button>
                    <button type="button"
                            @click="toggleChip('age18Only')"
                            class="tab"
                            :class="age18Only ? 'tab-on' : ''">
                        No 18 gadiem
                    </button>
                    <button type="button"
                            @click="toggleChip('fastPayout')"
                            class="tab"
                            :class="fastPayout ? 'tab-on' : ''">
                        Izmaksa ~15 min
                    </button>
                </div>
            </div>

            {{-- Active Filter Notice --}}
            <div x-cloak x-show="activeFilter" class="mb-4 px-4 py-2.5 bg-paper-light border border-ink flex flex-wrap items-center justify-between gap-2 text-xs text-ink">
                <span>
                    Atlasīti <strong x-text="visibleCount" class="num"></strong> no {{ count($lenders) }} aizdevējiem
                    <template x-if="amount > 0">
                        <span>· summa <strong x-text="amount + ' €'" class="num"></strong>, termiņš <strong x-text="months + ' mēn.'" class="num"></strong></span>
                    </template>
                </span>
                <button type="button" @click="resetFilters()" class="link-more text-xs cursor-pointer">
                    Atcelt atlasi ×
                </button>
            </div>

            <x-lender-table-head />

            <div class="border-t border-ink lg:border-t-0">
                @foreach ($lenders as $pos => $lender)
                    <x-lender-card
                        :lender="$lender"
                        :position="$pos"
                        :reviewSectionSlug="$reviewSection?->route_name ?: 'atsauksmes'"
                    />
                @endforeach
            </div>

            <div x-cloak x-show="activeFilter && visibleCount === 0" class="py-10 text-center border-b border-rule">
                <p class="font-serif text-lg font-semibold text-ink mb-1">Ar šādiem filtra kritērijiem precīzu sakritību nav.</p>
                <p class="text-sm text-ink-muted mb-4">Pamēģiniet mainīt summu, termiņu vai atcelt filtrus, lai apskatītu visus piedāvājumus.</p>
                <button type="button" @click="resetFilters()" class="btn-line btn-sm">
                    Rādīt visus aizdevējus
                </button>
            </div>
        </div>
    </section>

    {{-- 3. Directory of Loan Categories (Newspaper Columns) --}}
    @if (isset($parentLoanTypes) && $parentLoanTypes->isNotEmpty())
        <section data-reveal class="py-10 sm:py-14 border-t border-rule" aria-labelledby="categories-heading">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <span class="kicker">Tematiskais rādītājs</span>
                        <h2 id="categories-heading" class="section-title mt-1">
                            Kredītu veidi pēc mērķa un termiņa
                        </h2>
                    </div>
                    <p class="meta max-w-md">
                        Katram aizdevuma veidam atšķiras likumā noteiktie ierobežojumi, atmaksas grafiks un vidējā gada procentu likme.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 border-t border-l border-rule">
                    @foreach ($parentLoanTypes as $cat)
                        @php
                            $catMin = $cat->minAmount() ?: 50;
                            $catMax = $cat->maxAmount() ?: 15000;
                        @endphp
                        <div class="editorial-cell p-5 sm:p-6 flex flex-col justify-between bg-paper-light/60 border-b border-r border-rule">
                            <div>
                                <div class="flex items-baseline justify-between gap-2 mb-2">
                                    <h3 class="font-serif text-xl font-semibold text-ink">
                                        <a href="{{ route('section', ['name' => $cat->route_name]) }}" class="hover:text-claret">
                                            {{ clean_legacy_brand($cat->name) }}
                                        </a>
                                    </h3>
                                    <span class="text-xs text-ink-muted num shrink-0">
                                        {{ number_format((int) $catMin, 0, ',', ' ') }}–{{ number_format((int) $catMax, 0, ',', ' ') }} €
                                    </span>
                                </div>

                                @if (!empty($cat->description))
                                    <p class="text-sm text-ink-soft leading-relaxed">
                                        {{ clean_legacy_brand($cat->description) }}
                                    </p>
                                @endif

                                @if ($cat->children && $cat->children->isNotEmpty())
                                    <ul class="mt-4 pt-3 border-t border-rule space-y-1 text-xs">
                                        @foreach ($cat->children->take(4) as $subCat)
                                            <li>
                                                <a href="{{ route('section.deep', ['name' => $cat->route_name, 'deep_name' => $subCat->route_name]) }}"
                                                   class="link">
                                                    {{ clean_legacy_brand($subCat->name) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="mt-5 pt-3">
                                <a href="{{ route('section', ['name' => $cat->route_name]) }}" class="link-more text-xs">
                                    Atvērt sadaļu →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 4. Editorial Analysis, Reader Reviews, FAQ & Latest Articles --}}
    <div class="wrap py-10 sm:py-14 space-y-16">
        {{-- Editorial Guide + Process Sidebar --}}
        @if (!empty($homepage->text))
            <section data-reveal class="border-t-2 border-ink pt-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    <div class="lg:col-span-8 lg:pr-8 lg:border-r lg:border-rule">
                        <span class="kicker mb-2">Redakcijas skaidrojums</span>
                        <div class="seo-prose mt-3">
                            {!! clean_legacy_brand($homepage->text) !!}
                        </div>
                    </div>

                    <aside class="lg:col-span-4 space-y-6">
                        <div class="border border-ink bg-paper-light p-5">
                            <div class="kicker mb-1.5">Metodoloģija</div>
                            <h3 class="font-serif text-lg font-semibold text-ink mb-3">Kā notiek salīdzināšana 3 soļos</h3>
                            <ol class="space-y-4 text-sm text-ink-soft divide-y divide-rule">
                                <li class="pt-2 first:pt-0">
                                    <strong class="font-serif text-ink block">I. Salīdziniet GPL un termiņu</strong>
                                    Atrodiet aizdevēju ar zemāko kopējo pārmaksu un piemērotāko atmaksas grafiku.
                                </li>
                                <li class="pt-3">
                                    <strong class="font-serif text-ink block">II. Aizpildiet pieteikumu tiešsaistē</strong>
                                    Apstipriniet identitāti aizdevēja vietnē ar Smart-ID, eParakstu vai internetbanku.
                                </li>
                                <li class="pt-3">
                                    <strong class="font-serif text-ink block">III. Izvērtējiet individuālo līgumu</strong>
                                    Pirms parakstīšanas iepazīstieties ar aizdevēja sagatavoto Eiropas patērētāja kredīta standartinformāciju.
                                </li>
                            </ol>
                        </div>
                    </aside>
                </div>
            </section>
        @endif

        {{-- Recent Customer Reviews --}}
        @if (isset($recentReviews) && $recentReviews->isNotEmpty())
            <section data-reveal aria-labelledby="home-reviews-heading">
                <div class="section-head">
                    <div>
                        <span class="kicker">Lasītāju balsis</span>
                        <h2 id="home-reviews-heading" class="section-title mt-1">
                            Aizņēmēju atsauksmes par kredītdevējiem
                        </h2>
                    </div>
                    @if ($reviewSection)
                        <a href="{{ route('section', ['name' => $reviewSection->route_name]) }}" class="link-more">
                            Visas atsauksmes ({{ $allReviewsCount }}) →
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 border-y border-rule divide-y md:divide-y-0 md:divide-x divide-rule">
                    @foreach ($recentReviews->take(3) as $rev)
                        @if ($rev->lender)
                            @php
                                $rSlug = $rev->lender->route_name ?: \Illuminate\Support\Str::slug($rev->lender->name);
                                $rUrl = url('/' . ($reviewSection?->route_name ?: 'atsauksmes') . '/' . $rSlug);
                            @endphp
                            <article class="p-5 sm:p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-baseline justify-between gap-3 mb-3 text-xs">
                                        <a href="{{ $rUrl }}" class="font-sans font-bold uppercase tracking-wider text-ink hover:text-claret">
                                            {{ $rev->lender->name }}
                                        </a>
                                        <span class="num font-semibold text-claret">{{ $rev->rating }}/5</span>
                                    </div>
                                    <p class="font-serif italic text-base text-ink-soft leading-relaxed">
                                        “{{ \Illuminate\Support\Str::limit(clean_legacy_brand($rev->text), 190) }}”
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-rule flex items-center justify-between text-xs text-ink-muted">
                                    <span>— {{ $rev->name ?: 'Klients' }}</span>
                                    <a href="{{ $rUrl }}" class="link">Apskats</a>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Frequently Asked Questions --}}
        @if (isset($faqs) && $faqs->isNotEmpty())
            <div data-reveal class="max-w-4xl">
                <x-faq :faqs="$faqs" :faqPage="true" heading="Biežāk uzdotie jautājumi par kredītiem internetā" />
            </div>
        @endif

        {{-- Latest Financial Guides / Blog Articles --}}
        @if (isset($latestBlogs) && count($latestBlogs) > 0)
            <section data-reveal aria-labelledby="home-blog-heading">
                <div class="section-head">
                    <div>
                        <span class="kicker">Analītika un ceļveži</span>
                        <h2 id="home-blog-heading" class="section-title mt-1">
                            {{ clean_legacy_brand($blogSection->name ?? 'Noderīgi raksti par gudru aizņemšanos') }}
                        </h2>
                    </div>
                    @if ($blogSection)
                        <a href="{{ route('section', ['name' => $blogSection->route_name]) }}" class="link-more">
                            Visi raksti →
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($latestBlogs as $blog)
                        @php
                            $blogUrl = route('section.deep', [
                                'name' => $blogSection->route_name ?? 'blogs',
                                'deep_name' => $blog->route_name,
                            ]);
                            $excerpt = clean_legacy_brand(
                                $blog->description ?: strip_tags($blog->source ?: \Illuminate\Support\Str::limit(strip_tags($blog->text), 160))
                            );
                        @endphp
                        <article class="flex flex-col justify-between border-b border-rule pb-6">
                            <div>
                                @if (!empty($blog->image))
                                    <a href="{{ $blogUrl }}" class="block aspect-[16/10] overflow-hidden border border-rule mb-4 bg-paper-dark">
                                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                             data-lazy-src="{{ $blog->image }}"
                                             alt="{{ clean_legacy_brand($blog->name) }}"
                                             loading="lazy"
                                             decoding="async"
                                             fetchpriority="low"
                                             class="w-full h-full object-cover">
                                    </a>
                                @endif
                                <h3 class="font-serif text-xl font-semibold text-ink leading-snug">
                                    <a href="{{ $blogUrl }}" class="hover:text-claret">{{ clean_legacy_brand($blog->name) }}</a>
                                </h3>
                                <p class="text-sm text-ink-soft mt-2.5 leading-relaxed">
                                    {{ $excerpt }}
                                </p>
                            </div>
                            <div class="mt-4 pt-3">
                                <a href="{{ $blogUrl }}" class="link-more text-xs">
                                    Lasīt rakstu →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
