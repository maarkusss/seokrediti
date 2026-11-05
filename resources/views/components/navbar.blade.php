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
            $q->where('active', true)->orderBy('order');
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
                    <li class="relative" @if ($subItems->isNotEmpty()) x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @keydown.escape.window="open = false" @endif>
                        <div class="flex items-center">
                            <a href="{{ route('section', ['name' => $navLoan->route_name]) }}"
                               class="{{ $subItems->isEmpty() ? 'nav-link-editorial' : '' }} inline-flex items-center gap-1.5 px-3.5 py-3 transition-colors {{ $isActive ? 'text-claret' : 'text-ink hover:text-claret' }}">
                                <span>{{ clean_legacy_brand($navLoan->name) }}</span>
                                @if ($subItems->isNotEmpty())
                                    <svg class="w-3 h-3 text-ink-muted transition-transform duration-200"
                                         :class="open ? 'rotate-180 text-claret' : ''"
                                         viewBox="0 0 12 12"
                                         fill="none"
                                         aria-hidden="true">
                                        <path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="square"/>
                                    </svg>
                                @endif
                            </a>
                        </div>
                        @if ($isActive && $subItems->isEmpty())
                            <span class="absolute left-3.5 right-3.5 -bottom-px h-[3px] bg-claret" aria-hidden="true"></span>
                        @elseif ($isActive && $subItems->isNotEmpty())
                            <span x-show="!open" class="absolute left-3.5 right-3.5 -bottom-px h-[3px] bg-claret" aria-hidden="true"></span>
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
                                 class="absolute left-0 top-full z-50 w-72">
                                <div class="bg-paper-light border border-ink border-t-0 shadow-[4px_4px_0_0_rgba(21,20,18,0.12)]">
                                    {{-- Dropdown Kicker Header --}}
                                    <div class="px-4 py-2 bg-paper border-b border-rule flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-[0.14em] text-ink-muted">Apakšsadaļas</span>
                                        <a href="{{ route('section', ['name' => $navLoan->route_name]) }}"
                                           class="text-[11px] font-semibold text-claret hover:underline underline-offset-2">
                                            Visi piedāvājumi →
                                        </a>
                                    </div>

                                    {{-- Child Category Links --}}
                                    <div class="divide-y divide-rule">
                                        @foreach ($subItems as $idx => $sub)
                                            @php
                                                $isChildActive = request()->is($navLoan->route_name . '/' . $sub->route_name);
                                            @endphp
                                            <a href="{{ route('section.deep', ['name' => $navLoan->route_name, 'deep_name' => $sub->route_name]) }}"
                                               class="group flex items-baseline justify-between gap-3 px-4 py-2.5 text-[13px] transition-colors {{ $isChildActive ? 'bg-paper text-claret font-semibold' : 'text-ink hover:bg-paper hover:text-claret font-medium' }}">
                                                <span class="flex items-baseline gap-2.5 min-w-0">
                                                    <span class="font-mono text-[10px] text-ink-faint group-hover:text-claret num shrink-0">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                                    <span class="truncate">{{ clean_legacy_brand($sub->name) }}</span>
                                                </span>
                                                <span class="text-xs text-ink-faint group-hover:text-claret group-hover:translate-x-0.5 transition-all shrink-0" aria-hidden="true">→</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
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
                @php
                    $mobSubs = $navLoan->children ?? collect();
                @endphp
                <div class="border-b border-rule py-2.5" @if ($mobSubs->isNotEmpty()) x-data="{ subOpen: false }" @endif>
                    <div class="flex items-center justify-between gap-2">
                        <a href="{{ route('section', ['name' => $navLoan->route_name]) }}" class="font-serif text-lg font-semibold text-ink hover:text-claret">
                            {{ clean_legacy_brand($navLoan->name) }}
                        </a>
                        @if ($mobSubs->isNotEmpty())
                            <button type="button"
                                    @click="subOpen = !subOpen"
                                    class="px-2 py-1 text-xs font-mono text-ink-muted border border-rule bg-paper cursor-pointer"
                                    :aria-expanded="subOpen.toString()">
                                <span x-text="subOpen ? '−' : '+ ({{ $mobSubs->count() }})'">+ ({{ $mobSubs->count() }})</span>
                            </button>
                        @endif
                    </div>
                    @if ($mobSubs->isNotEmpty())
                        <div x-cloak x-show="subOpen" class="mt-2 pl-3 border-l-2 border-claret space-y-1.5 py-1 text-sm">
                            @foreach ($mobSubs as $sub)
                                <a href="{{ route('section.deep', ['name' => $navLoan->route_name, 'deep_name' => $sub->route_name]) }}"
                                   class="block text-ink-soft hover:text-claret">
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
