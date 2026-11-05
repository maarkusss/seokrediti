@php
    $locale = app()->getLocale();
    $footerLoans = collect();
    $footerSections = collect();

    try {
        $footerLoans = \App\Models\Admincp\LoanTypes\LoanTypes::where('lang', $locale)
            ->where('active', true)
            ->whereNull('parent_type_id')
            ->orderBy('order')
            ->take(8)
            ->get();

        $footerSections = \App\Models\Admincp\Sections\Sections::where('lang', $locale)
            ->where('active', true)
            ->whereNull('parent_section_id')
            ->where('type', '!=', 'home')
            ->orderBy('order')
            ->get();
    } catch (\Throwable $e) {
        // Keep footer renderable without a database
    }
@endphp

<footer class="mt-20 border-t-4 border-double border-ink bg-paper" data-nosnippet>
    <div class="wrap pt-10 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-0 md:divide-x md:divide-rule">
            <div class="md:col-span-5 md:pr-8">
                <a href="{{ $locale === 'ru' ? url('/ru') : route('homepage') }}" class="inline-block">
                    <x-brand-logo size="md" :showTagline="true" />
                </a>
                <p class="mt-4 font-serif text-[15px] leading-relaxed text-ink-soft max-w-md">
                    {{ config('brand.editorial_note') }}
                </p>
            </div>

            <div class="md:col-span-4 md:px-8">
                <h3 class="font-sans text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-muted mb-3">Salīdzinājumi</h3>
                <ul class="space-y-1.5 text-sm">
                    @foreach ($footerLoans as $fLoan)
                        <li><a href="{{ route('section', ['name' => $fLoan->route_name]) }}" class="hover:text-claret hover:underline underline-offset-4">{{ clean_legacy_brand($fLoan->name) }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="md:col-span-3 md:pl-8">
                <h3 class="font-sans text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-muted mb-3">Par portālu</h3>
                <ul class="space-y-1.5 text-sm">
                    @foreach ($footerSections as $fSec)
                        <li><a href="{{ route('section', ['name' => $fSec->route_name]) }}" class="hover:text-claret hover:underline underline-offset-4">{{ clean_legacy_brand($fSec->name) }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="mt-10 pt-5 border-t border-rule">
            <p class="text-xs leading-relaxed text-ink-muted max-w-4xl">
                <strong class="text-ink font-semibold">Svarīgi.</strong>
                {{ brand_name() }} nav kredītdevējs un neizsniedz aizdevumus. Vietnē apkopota salīdzinoša informācija par Latvijas Republikā licencētiem patērētāju kreditēšanas pakalpojumu sniedzējiem, kurus uzrauga Patērētāju tiesību aizsardzības centrs (PTAC).
                Reprezentatīvs piemērs: aizņemoties 1 000 € uz 12 mēnešiem ar fiksētu aizņēmuma likmi 18% gadā, ikmēneša maksājums ir 91,68 €, kopējā atmaksājamā summa 1 100,16 €, GPL 19,56%. Faktiskos nosacījumus nosaka aizdevējs pēc maksātspējas izvērtējuma. {{ brand_disclaimer() }}
            </p>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between gap-2 text-xs text-ink-muted">
            <p>&copy; {{ date('Y') }} {{ brand_name() }}. Visas tiesības aizsargātas.</p>
            <p class="italic font-serif">{{ brand_tagline() }}</p>
        </div>
    </div>
</footer>

<x-scroll-to-top-button />
