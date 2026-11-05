@props([
    'code' => '404',
    'heading' => 'Lapa nav atrasta',
    'message' => 'Pieprasītā lapa neeksistē, ir pārvietota vai tās adrese ievadīta neprecīzi.',
])

@php
    $quickCategories = collect();
    try {
        $quickCategories = \App\Models\Admincp\LoanTypes\LoanTypes::where('lang', app()->getLocale())
            ->whereNull('parent_type_id')
            ->where('active', true)
            ->orderBy('order')
            ->take(5)
            ->get();
    } catch (\Throwable $e) {
        $quickCategories = collect();
    }
@endphp

<div class="wrap py-14 sm:py-20">
    <div class="max-w-2xl mx-auto border-y-2 border-ink py-10 sm:py-14">
        <div class="kicker mb-2">Kļūdas ziņojums · {{ $code }}</div>

        <h1 class="font-serif text-3xl sm:text-5xl font-bold text-ink tracking-tight leading-tight">
            {{ $heading }}
        </h1>

        <p class="mt-4 font-serif text-lg text-ink-soft leading-relaxed">
            {{ $message }}
        </p>

        <div class="mt-8 flex flex-wrap items-center gap-3">
            <a href="{{ url('/') }}" class="btn-ink">
                Uz sākumlapu
            </a>
            <a href="{{ url('/atsauksmes') }}" class="btn-line">
                Aizdevēju atsauksmes
            </a>
        </div>

        @if ($quickCategories->isNotEmpty())
            <div class="mt-10 pt-6 border-t border-rule">
                <p class="text-xs font-semibold uppercase tracking-wider text-ink-muted mb-3">
                    Galvenās salīdzinājumu sadaļas
                </p>
                <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm">
                    @foreach ($quickCategories as $cat)
                        <a href="{{ url('/' . $cat->route_name) }}" class="link">
                            {{ clean_legacy_brand($cat->name) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
