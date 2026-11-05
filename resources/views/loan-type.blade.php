@php
    $locale = app()->getLocale();
    $pageTitle = clean_legacy_brand($loanType->title ?: $loanType->name);
    $pageDescription = clean_legacy_brand(
        $loanType->description ?: ('Salīdzini labākos ' . mb_strtolower($loanType->name) . ' piedāvājumus Latvijā. Pārskatāmas procentu likmes (GPL), summas, atmaksas termiņi un klientu atsauksmes.')
    );
    $reviewSlug = $reviewSection?->route_name ?: 'atsauksmes';

    $mappedFaqPivots = $loanType->faqs();
    $categoryFaqs = ($mappedFaqPivots && $mappedFaqPivots->count() > 0) ? $mappedFaqPivots : ($faqs ?? collect());

    $activeParent = $parentLoanType ?? ($loanType->parent_type_id ? $loanType->parent : $loanType);
    $subCategoryPills = $activeParent ? ($activeParent->children ?? collect()) : collect();
@endphp

@extends('layouts.public')

@section('title', $pageTitle)
@section('description', $pageDescription)

@section('schema')
    @if (count($lenders) > 0)
        @php
            $itemListElements = [];
            $posCounter = 1;
            foreach ($lenders as $lenderItem) {
                $lSlug = $lenderItem->route_name ?: \Illuminate\Support\Str::slug($lenderItem->name);
                $itemListElements[] = [
                    '@type' => 'ListItem',
                    'position' => $posCounter++,
                    'item' => [
                        '@type' => 'LoanOrCredit',
                        'name' => $lenderItem->name,
                        'url' => url('/' . $reviewSlug . '/' . $lSlug),
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
                'name' => clean_legacy_brand($loanType->h1 ?: $loanType->name),
                'description' => $pageDescription,
                'url' => url()->current(),
                'numberOfItems' => count($itemListElements),
                'itemListElement' => $itemListElements,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endsection

@section('content')
    {{-- Category Header --}}
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                <div class="lg:col-span-8">
                    <span class="kicker mb-2">Kredītu salīdzinājums · {{ date('Y') }}</span>
                    <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink leading-tight">
                        {{ clean_legacy_brand($loanType->h1 ?: $loanType->name) }}
                    </h1>
                    <p class="standfirst mt-3">
                        {{ $pageDescription }}
                    </p>
                </div>

                <dl class="lg:col-span-4 grid grid-cols-2 gap-4 border-t lg:border-t-0 lg:border-l border-rule pt-4 lg:pt-0 lg:pl-6">
                    <div>
                        <dt class="meta uppercase tracking-wider">Aizdevēji sadaļā</dt>
                        <dd class="font-serif text-2xl font-semibold text-ink num mt-0.5">{{ count($lenders) }}</dd>
                    </div>
                    <div>
                        <dt class="meta uppercase tracking-wider">Pieteikuma izskatīšana</dt>
                        <dd class="font-serif text-2xl font-semibold text-claret num mt-0.5">no 15 min</dd>
                    </div>
                </dl>
            </div>

            {{-- Subcategory Navigation Strip --}}
            @if ($activeParent && $subCategoryPills->isNotEmpty())
                <div class="flex flex-wrap items-center gap-1.5 mt-6 pt-4 border-t border-rule">
                    <a href="{{ route('section', ['name' => $activeParent->route_name]) }}"
                       class="tab {{ !$loanType->parent_type_id ? 'tab-on' : '' }}">
                        Visi {{ mb_strtolower(clean_legacy_brand($activeParent->name)) }}
                    </a>

                    @foreach ($subCategoryPills as $pill)
                        <a href="{{ route('section.deep', ['name' => $activeParent->route_name, 'deep_name' => $pill->route_name]) }}"
                           class="tab {{ $loanType->id === $pill->id ? 'tab-on' : '' }}">
                            {{ clean_legacy_brand($pill->name) }}
                        </a>
                    @endforeach
                </div>
            @elseif (isset($siblingLoanTypes) && $siblingLoanTypes->count() > 1)
                <div class="flex flex-wrap items-center gap-1.5 mt-6 pt-4 border-t border-rule">
                    @foreach ($siblingLoanTypes as $sib)
                        <a href="{{ route('section', ['name' => $sib->route_name]) }}"
                           class="tab {{ $sib->id === $loanType->id ? 'tab-on' : '' }}">
                            {{ clean_legacy_brand($sib->name) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Main Two-Column Content Area --}}
    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8 space-y-12"
                 x-data="lenderFilterList({ totalCount: {{ count($lenders) }} })">

                @if (count($lenders) > 0)
                    <div>
                        {{-- Filter Toolbar --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-rule">
                            <div class="flex flex-wrap items-center gap-1.5">
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
                                    Ātrā izmaksa
                                </button>
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="inline-amount-select" class="text-xs font-semibold uppercase tracking-wider text-ink-muted shrink-0">Summa:</label>
                                <select id="inline-amount-select"
                                        x-model.number="amount"
                                        @change="activeFilter = amount > 0 || zeroOnly || age18Only || fastPayout; $nextTick(() => recount())"
                                        class="field py-1.5 px-2.5 text-xs w-auto">
                                    <option value="0">Jebkura summa</option>
                                    <option value="200">200 €</option>
                                    <option value="500">500 €</option>
                                    <option value="1000">1 000 €</option>
                                    <option value="3000">3 000 €</option>
                                    <option value="5000">5 000 €</option>
                                    <option value="10000">10 000 €</option>
                                </select>
                            </div>
                        </div>

                        <x-lender-table-head />

                        <div id="piedavajumi" class="border-t border-ink lg:border-t-0">
                            @foreach ($lenders as $position => $lender)
                                <x-lender-card
                                    :lender="$lender"
                                    :position="$position"
                                    :reviewSectionSlug="$reviewSlug"
                                />
                            @endforeach
                        </div>

                        <div x-cloak x-show="activeFilter && visibleCount === 0" class="py-8 text-center border-b border-rule">
                            <p class="font-serif text-lg font-semibold text-ink mb-1">Šādiem filtra parametriem atbilstošu piedāvājumu nav.</p>
                            <p class="text-sm text-ink-muted mb-4">Atceliet filtrus, lai apskatītu visus kategorijas aizdevējus.</p>
                            <button type="button" @click="resetFilters()" class="btn-line btn-sm">
                                Rādīt visus ({{ count($lenders) }})
                            </button>
                        </div>
                    </div>
                @else
                    <div class="boxed p-8 text-center">
                        <p class="text-ink-soft">Šobrīd šajā kategorijā piedāvājumi tiek atjaunoti.</p>
                    </div>
                @endif

                {{-- Editorial SEO Guide Content --}}
                @if (!empty($loanType->text))
                    <section class="border-t-2 border-ink pt-6">
                        <span class="kicker mb-2">Redakcijas apskats</span>
                        <div class="seo-prose mt-3">
                            {!! clean_legacy_brand($loanType->text) !!}
                        </div>
                    </section>
                @endif

                {{-- Category FAQs --}}
                @if ($categoryFaqs && $categoryFaqs->count() > 0)
                    <x-faq :faqs="$categoryFaqs" :faqPage="false" :heading="'Biežāk uzdotie jautājumi — ' . clean_legacy_brand($loanType->name)" />
                @endif
            </div>

            {{-- Right Sidebar --}}
            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
