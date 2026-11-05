@php
    $locale = app()->getLocale();
    $topSidebarLenders = collect();
    $sidebarReviews = collect();
    $reviewBaseSlug = 'atsauksmes';

    try {
        $topSidebarLenders = \App\Models\Admincp\Lenders\Lenders::with('data')
            ->where('active', true)
            ->orderBy('position', 'asc')
            ->take(5)
            ->get();

        $sidebarReviews = \App\Models\Reviews::with('lender')
            ->whereNotNull('text')
            ->where('text', '!=', '')
            ->whereHas('lender')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $reviewSection = \App\Models\Admincp\Sections\Sections::where('type', 'reviews')
            ->where('lang', $locale)
            ->where('active', true)
            ->first();
        $reviewBaseSlug = $reviewSection?->route_name ?: 'atsauksmes';
    } catch (\Throwable $e) {
        // Resilient fallback if database is temporarily unavailable
    }
@endphp

<aside class="space-y-8 lg:sticky lg:top-8" aria-label="Papildinformācija un top piedāvājumi">
    {{-- Top Active Offers Index --}}
    @if ($topSidebarLenders->isNotEmpty())
        <div class="border-t-2 border-ink pt-3">
            <div class="flex items-baseline justify-between mb-3">
                <h2 class="font-serif text-lg font-semibold text-ink">Pieprasītākie aizdevēji</h2>
                <span class="meta">{{ __(date('m') . 'date') }}</span>
            </div>

            <div class="divide-y divide-rule border-y border-rule bg-paper-light">
                @foreach ($topSidebarLenders as $idx => $sLender)
                    @php
                        $pos = $idx + 1;
                        $sReviewUrl = url('/' . $reviewBaseSlug . '/' . ($sLender->route_name ?: \Illuminate\Support\Str::slug($sLender->name)));
                    @endphp
                    <div class="p-3.5 flex items-center justify-between gap-3">
                        <a href="{{ $sReviewUrl }}" class="flex items-baseline gap-3 min-w-0 group">
                            <span class="font-serif text-base font-semibold num {{ $pos === 1 ? 'text-claret' : 'text-ink-faint' }} w-4 shrink-0">{{ $pos }}.</span>
                            <div class="min-w-0">
                                <div class="font-serif text-[15px] font-semibold text-ink group-hover:text-claret truncate">{{ $sLender->name }}</div>
                                <div class="text-xs text-ink-muted num">
                                    līdz {{ number_format((int) $sLender->max_amount, 0, ',', ' ') }} €
                                    @if ($sLender->zero_percent)
                                        <span class="text-claret font-semibold">· 0% 1. kred.</span>
                                    @endif
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('go', ['lender_id' => $sLender->id, 'lender_position' => $pos]) }}"
                           rel="nofollow sponsored noopener"
                           target="_blank"
                           class="btn-line btn-sm shrink-0">
                            Apskatīt
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Responsible Borrowing Memorandum --}}
    <div class="border border-ink bg-paper-light p-5">
        <div class="kicker mb-1.5">Atgādinājums</div>
        <h3 class="font-serif text-lg font-semibold text-ink mb-3">Četri punkti pirms līguma parakstīšanas</h3>
        <ol class="space-y-2.5 text-xs text-ink-soft leading-relaxed list-decimal pl-4">
            <li>
                Salīdziniet <strong class="text-ink">gada procentu likmi (GPL)</strong> un kopējo pārmaksu eiro izteiksmē, nevis tikai ikmēneša maksājumu.
            </li>
            <li>
                Sekojiet, lai visu kredītsaistību maksājumi nepārsniegtu 25–30% no mājsaimniecības neto ienākumiem.
            </li>
            <li>
                Pārliecinieties, ka aizdevējam ir spēkā esoša <strong class="text-ink">PTAC licence</strong> darbam Latvijā.
            </li>
            <li>
                Pārbaudiet tiesības veikt pilnu vai daļēju pirmstermiņa atmaksu bez papildu komisijas.
            </li>
        </ol>
    </div>

    {{-- Recent Customer Reviews --}}
    @if ($sidebarReviews->isNotEmpty())
        <div class="border-t-2 border-ink pt-3">
            <div class="flex items-baseline justify-between mb-3">
                <h3 class="font-serif text-lg font-semibold text-ink">Lasītāju pieredze</h3>
                <a href="{{ url('/' . $reviewBaseSlug) }}" class="link-more text-xs">Visas →</a>
            </div>

            <div class="divide-y divide-rule border-y border-rule">
                @foreach ($sidebarReviews as $rev)
                    @php
                        $revSlug = $rev->lender->route_name ?: \Illuminate\Support\Str::slug($rev->lender->name);
                    @endphp
                    <blockquote class="py-3.5 space-y-1.5">
                        <div class="flex items-baseline justify-between gap-2 text-xs">
                            <a href="{{ url('/' . $reviewBaseSlug . '/' . $revSlug) }}" class="font-semibold text-ink hover:text-claret">
                                {{ $rev->lender->name }}
                            </a>
                            <span class="num text-ink-muted">{{ $rev->rating }}/5</span>
                        </div>
                        <p class="font-serif italic text-sm text-ink-soft leading-snug">
                            “{{ \Illuminate\Support\Str::limit(clean_legacy_brand($rev->text), 130) }}”
                        </p>
                        <footer class="text-[11px] text-ink-muted">
                            — {{ $rev->name ?: 'Klients' }}
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </div>
    @endif
</aside>
