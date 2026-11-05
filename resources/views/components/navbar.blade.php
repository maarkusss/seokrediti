@php
    $locale = app()->getLocale();
    $headerSections = collect();
    $languages = collect();
    $loanTypes = collect();

    try {
        $headerSections = \App\Models\Admincp\Sections\Sections::where('lang', $locale)
            ->where('active', true)
            ->whereNull('parent_section_id')
            ->where('type', '!=', 'home')
            ->where('display_in_the_header', true)
            ->orderBy('order')
            ->get();

        $languages = \App\Models\Admincp\Settings::where('name', 'lang')->get();

        $loanTypes = \App\Models\Admincp\LoanTypes\LoanTypes::with(['children' => function ($q) {
            $q->where('active', true)->where('display_dropdown', true)->orderBy('order');
        }])
            ->where('lang', $locale)
            ->where('active', true)
            ->whereNull('parent_type_id')
            ->orderBy('order', 'asc')
            ->get();
    } catch (\Throwable $e) {
        // Render an empty navigation if the database is unavailable
    }

    $lvDays = ['svētdiena', 'pirmdiena', 'otrdiena', 'trešdiena', 'ceturtdiena', 'piektdiena', 'sestdiena'];
    $lvMonths = [1 => 'janvāris', 'februāris', 'marts', 'aprīlis', 'maijs', 'jūnijs', 'jūlijs', 'augusts', 'septembris', 'oktobris', 'novembris', 'decembris'];
    $now = now();
    $todayLabel = ucfirst($lvDays[$now->dayOfWeek]) . ', ' . $now->day . '. ' . $lvMonths[$now->month] . ', ' . $now->year;
@endphp

<header x-data="{ mobileOpen: false }" class="bg-paper">
    {{-- Dateline strip --}}
    <div class="border-b border-rule">
        <div class="wrap flex items-center justify-between gap-4 py-2 text-[11px] sm:text-xs text-ink-muted">
            <span class="num">{{ $todayLabel }}</span>
            <span class="hidden md:inline truncate">{{ brand_disclaimer() }}</span>
            @if ($languages->count() > 1)
                <span class="flex items-center gap-2 font-semibold">
                    @foreach ($languages as $langItem)
                        <a href="{{ $langItem->value === 'lv' ? url('/') : url('/' . $langItem->value) }}"
                           class="{{ $langItem->value === $locale ? 'text-ink underline underline-offset-4' : 'hover:text-ink' }}">{{ strtoupper($langItem->value) }}</a>
                    @endforeach
                </span>
            @else
                <a href="mailto:{{ brand_email() }}" class="hidden sm:inline hover:text-ink">{{ brand_email() }}</a>
            @endif
        </div>
    </div>

    {{-- Masthead --}}
    <div class="wrap">
        <div class="flex items-center justify-between lg:justify-center gap-4 py-5 sm:py-7 relative">
            <a href="{{ $locale === 'ru' ? url('/ru') : route('homepage') }}" aria-label="{{ brand_name() }} — sākumlapa" class="lg:text-center">
                <x-brand-logo size="lg" :showTagline="true" class="lg:items-center" />
            </a>

            <button type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-navigation"
                    class="lg:hidden font-sans text-xs font-semibold uppercase tracking-wider border border-ink px-3 py-2 cursor-pointer">
                <span x-text="mobileOpen ? 'Aizvērt' : 'Izvēlne'">Izvēlne</span>
            </button>
        </div>
    </div>

    {{-- Section navigation between rules --}}
    <nav class="hidden lg:block border-y border-ink" aria-label="Galvenā navigācija">
        <div class="wrap">
            <ul class="flex items-stretch justify-center flex-wrap font-sans text-[13px] font-semibold">
                @foreach ($loanTypes as $navLoan)
                    @php
                        $subItems = $navLoan->children ?? collect();
                        $isActive = request()->is($navLoan->route_name) || request()->is($navLoan->route_name . '/*');
                    @endphp
                    <li class="relative" @if ($subItems->isNotEmpty()) x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @endif>
                        <a href="{{ route('section', ['name' => $navLoan->route_name]) }}"
                           class="nav-link-editorial block px-3.5 py-3 {{ $isActive ? 'text-claret' : 'text-ink hover:text-claret' }}">
                            {{ clean_legacy_brand($navLoan->name) }}
                        </a>
                        @if ($isActive)
                            <span class="absolute left-3.5 right-3.5 -bottom-px h-[3px] bg-claret" aria-hidden="true"></span>
                        @endif

                        @if ($subItems->isNotEmpty())
                            <div x-cloak
                                 x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="absolute left-0 top-full z-50 w-64 bg-paper-light border border-ink border-t-0">
                                <a href="{{ route('section', ['name' => $navLoan->route_name]) }}"
                                   class="block px-4 py-2.5 border-b border-rule font-semibold hover:text-claret">
                                    Visi {{ mb_strtolower(clean_legacy_brand($navLoan->name)) }}
                                </a>
                                @foreach ($subItems as $sub)
                                    <a href="{{ route('section.deep', ['name' => $navLoan->route_name, 'deep_name' => $sub->route_name]) }}"
                                       class="block px-4 py-2 font-normal text-ink-soft hover:text-claret {{ !$loop->last ? 'border-b border-rule' : '' }}">
                                        {{ clean_legacy_brand($sub->name) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach

                @if ($headerSections->isNotEmpty())
                    <li class="flex items-center px-2 text-rule" aria-hidden="true">|</li>
                @endif

                @foreach ($headerSections as $hSec)
                    @php
                        $isSecActive = request()->is($hSec->route_name) || request()->is($hSec->route_name . '/*');
                    @endphp
                    <li class="relative">
                        <a href="{{ route('section', ['name' => $hSec->route_name]) }}"
                           class="nav-link-editorial block px-3.5 py-3 font-normal {{ $isSecActive ? 'text-claret' : 'text-ink-soft hover:text-claret' }}">
                            {{ clean_legacy_brand($hSec->name) }}
                        </a>
                        @if ($isSecActive)
                            <span class="absolute left-3.5 right-3.5 -bottom-px h-[3px] bg-claret" aria-hidden="true"></span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
    <div class="lg:hidden border-t border-ink"></div>

    {{-- Mobile navigation --}}
    <div x-cloak x-show="mobileOpen" id="mobile-navigation" class="lg:hidden border-b border-ink bg-paper-light">
        <div class="wrap py-2 font-sans">
            @foreach ($loanTypes as $navLoan)
                <div class="border-b border-rule py-2.5">
                    <a href="{{ route('section', ['name' => $navLoan->route_name]) }}" class="block font-serif text-lg font-semibold">
                        {{ clean_legacy_brand($navLoan->name) }}
                    </a>
                    @if (($navLoan->children ?? collect())->isNotEmpty())
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-ink-muted">
                            @foreach ($navLoan->children as $sub)
                                <a href="{{ route('section.deep', ['name' => $navLoan->route_name, 'deep_name' => $sub->route_name]) }}" class="hover:text-claret">
                                    {{ clean_legacy_brand($sub->name) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="flex flex-wrap gap-x-5 gap-y-2 py-3 text-sm">
                @foreach ($headerSections as $hSec)
                    <a href="{{ route('section', ['name' => $hSec->route_name]) }}" class="link">{{ clean_legacy_brand($hSec->name) }}</a>
                @endforeach
            </div>
        </div>
    </div>
</header>
