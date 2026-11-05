@props([
    'lender',
    'position' => 1,
    'reviewSectionSlug' => 'atsauksmes',
])

@php
    $data = $lender->data;
    $reviewsCollection = $lender->relationLoaded('reviews') ? $lender->reviews : $lender->reviews()->get();
    $reviewCount = $reviewsCollection->whereNotNull('rating')->count();
    $avgRating = $reviewCount > 0
        ? number_format((float) $reviewsCollection->whereNotNull('rating')->avg('rating'), 1, ',', '')
        : null;

    $lenderSlug = $lender->route_name ?: \Illuminate\Support\Str::slug($lender->name);
    $reviewUrl = url('/' . trim($reviewSectionSlug, '/') . '/' . $lenderSlug);
    $goUrl = route('go', ['lender_id' => $lender->id, 'lender_position' => $position]);

    $minAmount = (int) ($lender->min_amount ?: 50);
    $maxAmount = (int) ($lender->max_amount ?: 5000);
    $maxDays = is_numeric($lender->max_term) ? (int) $lender->max_term : 360;
    $minYears = (int) ($lender->min_years ?: 18);
    $maxYears = (int) ($lender->max_years ?: 75);
    $receivingMinutes = is_numeric($lender->receiving_time) ? (int) $lender->receiving_time : 15;
    $isZero = (bool) $lender->zero_percent;

    $slogan = $data && !empty($data->slogan) ? clean_legacy_brand($data->slogan) : null;
    $maxApr = $data && !empty($data->max_apr) ? $data->max_apr : 'līdz 49,9%';
    $aprMin = $data && !empty($data->real_apr_min) ? $data->real_apr_min : null;
    $aprExample = $data && !empty($data->apr_example)
        ? clean_legacy_brand($data->apr_example)
        : 'Aizņemoties 500 € uz 12 mēnešiem, ikmēneša maksājums no 46,05 €, kopējā atmaksājamā summa 552,60 €, GPL no 20,4%.';
@endphp

<article
    data-lender-card
    x-data="{ detailsOpen: false }"
    x-show="typeof matchesCard === 'function' ? matchesCard({
        minAmount: {{ $minAmount }},
        maxAmount: {{ $maxAmount }},
        maxDays: {{ $maxDays }},
        zeroPercent: {{ $isZero ? 'true' : 'false' }},
        minYears: {{ $minYears }},
        receivingMinutes: {{ $receivingMinutes }}
    }) : true"
    x-transition.opacity.duration.200ms
    class="lender-row border-b border-rule px-2 sm:px-3 -mx-2 sm:-mx-3">

    <div class="grid grid-cols-12 gap-x-4 gap-y-3 py-5 items-center">
        {{-- Lender --}}
        <div class="col-span-12 lg:col-span-3 flex items-center gap-4 min-w-0">
            <a href="{{ $reviewUrl }}" class="w-24 h-12 shrink-0 bg-white border border-rule flex items-center justify-center p-1.5" title="{{ $lender->name }} — apskats">
                @if (!empty($lender->image))
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                         data-lazy-src="{{ $lender->image }}"
                         alt="{{ $lender->name }}" width="96" height="40"
                         loading="lazy" decoding="async" fetchpriority="low"
                         class="max-h-9 w-auto object-contain">
                @else
                    <span class="font-serif text-sm font-semibold">{{ $lender->name }}</span>
                @endif
            </a>
            <div class="min-w-0">
                <h3 class="font-serif text-lg font-semibold leading-tight">
                    <a href="{{ $reviewUrl }}" class="hover:text-claret">{{ $lender->name }}</a>
                </h3>
                <div class="mt-0.5 text-xs text-ink-muted">
                    @if ($avgRating)
                        <a href="{{ $reviewUrl }}" class="hover:text-claret"><span class="text-ink font-semibold num">{{ $avgRating }}</span>/5 · {{ $reviewCount }} {{ $reviewCount === 1 ? 'atsauksme' : 'atsauksmes' }}</a>
                    @else
                        <a href="{{ $reviewUrl }}" class="hover:text-claret">Lasīt apskatu</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Figures --}}
        <dl class="col-span-12 lg:col-span-7 grid grid-cols-2 sm:grid-cols-5 gap-x-4 gap-y-2 text-sm lg:border-l lg:border-rule lg:pl-4">
            <div>
                <dt class="lg:sr-only text-[11px] uppercase tracking-wider text-ink-muted">Summa</dt>
                <dd class="num font-semibold">{{ number_format($minAmount, 0, ',', ' ') }}–{{ number_format($maxAmount, 0, ',', ' ') }} €</dd>
            </div>
            <div>
                <dt class="lg:sr-only text-[11px] uppercase tracking-wider text-ink-muted">Termiņš</dt>
                <dd class="num">{{ format_term_label($lender->min_term) }} – {{ format_term_label($lender->max_term) }}</dd>
            </div>
            <div>
                <dt class="lg:sr-only text-[11px] uppercase tracking-wider text-ink-muted">GPL</dt>
                <dd class="num">
                    @if ($isZero)
                        <span class="text-claret font-semibold">0%</span> <span class="text-ink-muted text-xs">1. kredītam</span>
                    @else
                        {{ $aprMin ? $aprMin . ' – ' : '' }}{{ $maxApr }}
                    @endif
                </dd>
            </div>
            <div>
                <dt class="lg:sr-only text-[11px] uppercase tracking-wider text-ink-muted">Vecums</dt>
                <dd class="num">{{ $minYears }}–{{ $maxYears }} gadi</dd>
            </div>
            <div>
                <dt class="lg:sr-only text-[11px] uppercase tracking-wider text-ink-muted">Izmaksa</dt>
                <dd class="num">{{ $receivingMinutes }} min</dd>
            </div>
        </dl>

        {{-- Action --}}
        <div class="col-span-12 lg:col-span-2 flex lg:flex-col items-center lg:items-stretch gap-3 lg:gap-1.5">
            <a href="{{ $goUrl }}" rel="nofollow sponsored noopener" target="_blank" class="btn-ink flex-1 lg:flex-none">
                Pieteikties
            </a>
            <button type="button" @click="detailsOpen = !detailsOpen" :aria-expanded="detailsOpen.toString()"
                    class="text-xs text-ink-muted hover:text-ink underline underline-offset-4 decoration-rule cursor-pointer whitespace-nowrap">
                <span x-text="detailsOpen ? 'Aizvērt' : 'GPL piemērs'">GPL piemērs</span>
            </button>
        </div>
    </div>

    @if ($slogan || $isZero)
        <p class="-mt-2 pb-4 text-sm font-serif italic text-ink-soft">
            {{ $slogan }}
            @if ($isZero && $lender->first_loan)
                <span class="not-italic font-sans text-xs text-claret ml-1">Jaunajiem klientiem līdz {{ number_format((int) $lender->first_loan, 0, ',', ' ') }} € bez procentiem.</span>
            @endif
        </p>
    @endif

    <div x-cloak
         x-show="detailsOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="pb-5">
        <div class="border-l-2 border-claret pl-4 text-sm text-ink-soft leading-relaxed space-y-2">
            <p><span class="font-semibold text-ink">Reprezentatīvs piemērs.</span> {{ $aprExample }}</p>
            @if ($data && (!empty($data->company_name) || !empty($data->address)))
                <p class="text-xs text-ink-muted">
                    {{ $data->company_name }}@if (!empty($data->address)), {{ $data->address }}@endif.
                    <a href="{{ $reviewUrl }}" class="link ml-1">Pilns apskats</a>
                </p>
            @endif
        </div>
    </div>
</article>
