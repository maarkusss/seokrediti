@extends('layouts.public')

@section('title', clean_legacy_brand($section->title) ?: 'Aizdevēju atsauksmes un reitings Latvijā')
@section('description', clean_legacy_brand($section->description) ?: 'Neatkarīgas klientu atsauksmes, vērtējumi un detalizēta informācija par Latvijas nebanku un banku kredītdevējiem.')

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <span class="kicker mb-2">Aizdevēju katalogs un reitings</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                    {{ clean_legacy_brand($section->h1 ?: 'Kredītdevēju atsauksmes un novērtējums') }}
                </h1>
                <p class="standfirst mt-3">
                    Izpētiet reālu aizņēmēju pieredzi, salīdziniet aizdevēju darba laikus, procentu likmes (GPL), juridiskos rekvizītus un pievienojiet savu atsauksmi.
                </p>
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8 space-y-12" x-data="{ query: '' }">
                {{-- Directory Search Header --}}
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-ink pb-4">
                    <div>
                        <h2 class="font-serif text-2xl font-semibold text-ink">Reģistrētie aizdevēji ({{ $lenders->count() }})</h2>
                        <p class="meta mt-0.5">Atveriet aizdevēja kartīti, lai lasītu apskatu un klientu atsauksmes</p>
                    </div>
                    <div class="w-full sm:w-64">
                        <input type="search"
                               x-model="query"
                               placeholder="Meklēt pēc nosaukuma..."
                               aria-label="Meklēt aizdevēju"
                               class="field py-2 text-xs">
                    </div>
                </div>

                {{-- Lender Directory Rows --}}
                <div class="divide-y divide-rule border-b border-rule -mt-8">
                    @foreach ($lenders as $idx => $lenderItem)
                        @php
                            $lRevCount = $lenderItem->reviews->whereNotNull('rating')->count();
                            $lAvg = $lRevCount > 0
                                ? number_format((float) $lenderItem->reviews->whereNotNull('rating')->avg('rating'), 1, ',', '')
                                : null;
                            $lSlug = $lenderItem->route_name ?: \Illuminate\Support\Str::slug($lenderItem->name);
                            $lProfileUrl = route('section.deep', ['name' => $section->route_name, 'deep_name' => $lSlug]);
                        @endphp
                        <article
                            x-show="query.trim() === '' || '{{ strtolower(addslashes($lenderItem->name)) }}'.includes(query.trim().toLowerCase())"
                            class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <a href="{{ $lProfileUrl }}"
                                   class="w-24 h-12 bg-white border border-rule p-1.5 flex items-center justify-center shrink-0">
                                    @if (!empty($lenderItem->image))
                                        <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                             data-lazy-src="{{ $lenderItem->image }}"
                                             alt="{{ $lenderItem->name }}"
                                             loading="lazy" decoding="async" fetchpriority="low"
                                             class="max-h-9 w-auto object-contain">
                                    @else
                                        <span class="font-serif text-sm font-semibold">{{ $lenderItem->name }}</span>
                                    @endif
                                </a>

                                <div class="min-w-0">
                                    <div class="flex items-baseline gap-3">
                                        <h3 class="font-serif text-xl font-semibold text-ink">
                                            <a href="{{ $lProfileUrl }}" class="hover:text-claret">{{ $lenderItem->name }}</a>
                                        </h3>
                                        @if ($lAvg)
                                            <span class="text-xs font-semibold text-claret num">{{ $lAvg }}/5 ({{ $lRevCount }})</span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-xs text-ink-muted num">
                                        Summa: <strong class="text-ink">{{ number_format((int) $lenderItem->min_amount, 0, ',', ' ') }}–{{ number_format((int) $lenderItem->max_amount, 0, ',', ' ') }} €</strong>
                                        · Termiņš: <strong class="text-ink">{{ format_term_label($lenderItem->max_term, true) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                <a href="{{ $lProfileUrl }}" class="btn-line btn-sm">
                                    Atsauksmes ({{ $lRevCount }})
                                </a>
                                @if ($lenderItem->active && !empty($lenderItem->affiliate_link))
                                    <a href="{{ route('go', ['lender_id' => $lenderItem->id, 'lender_position' => $idx + 1]) }}"
                                       rel="nofollow sponsored noopener"
                                       target="_blank"
                                       class="btn-ink btn-sm">
                                        Piedāvājums
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Latest Customer Reviews Feed --}}
                @if (isset($recentReviews) && $recentReviews->isNotEmpty())
                    <section>
                        <div class="section-head">
                            <div>
                                <span class="kicker">Jaunākie ieraksti</span>
                                <h2 class="section-title mt-1">Pēdējās pievienotās atsauksmes</h2>
                            </div>
                        </div>

                        <div class="divide-y divide-rule border-y border-rule">
                            @foreach ($recentReviews as $rev)
                                @php
                                    $revSlug = $rev->lender?->route_name ?: ($rev->lender ? \Illuminate\Support\Str::slug($rev->lender->name) : '');
                                @endphp
                                <article class="py-5">
                                    <div class="flex flex-wrap items-baseline justify-between gap-2 mb-2 text-xs">
                                        <div>
                                            <span class="font-semibold text-ink">{{ $rev->name ?: 'Klients' }}</span>
                                            @if ($rev->lender)
                                                <span class="text-ink-faint mx-1.5">/</span>
                                                <a href="{{ route('section.deep', ['name' => $section->route_name, 'deep_name' => $revSlug]) }}"
                                                   class="link font-semibold">
                                                    {{ $rev->lender->name }}
                                                </a>
                                            @endif
                                        </div>
                                        <span class="num font-semibold text-claret">Vērtējums: {{ $rev->rating }}/5</span>
                                    </div>
                                    <p class="font-serif text-base text-ink-soft leading-relaxed">{{ clean_legacy_brand($rev->text) }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
