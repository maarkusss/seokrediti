@php
    $data = $lender->data;
    $customTitle = $data && !empty($data->title)
        ? clean_legacy_brand($data->title)
        : ($lender->name . ' atsauksmes, procentu likmes un kontakti (' . date('Y') . ')');
    $customDesc = $data && !empty($data->meta_description)
        ? clean_legacy_brand($data->meta_description)
        : ('Pārbaudītas klientu atsauksmes par ' . $lender->name . '. Uzziniet kredīta summas (' . $lender->min_amount . '–' . $lender->max_amount . ' €), GPL piemēru, darba laiku un rekvizītus.');

    $reviewCount = $reviews ? $reviews->count() : 0;
    $breakdown = $ratingBreakdown ?? [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

    $workMf = $data->work_time_m_f ?? ($data->work_time ?? '08:00 – 20:00');
    $workSa = $data->work_time_sa ?? '09:00 – 18:00';
    $workSu = $data->work_time_su ?? '09:00 – 17:00';

    $aprExample = $data && !empty($data->apr_example)
        ? clean_legacy_brand($data->apr_example)
        : ('Reprezentatīvs piemērs: aizņemoties 1 000 € uz 12 mēnešiem pie ' . $lender->name . ', kopējā atmaksājamā summa un GPL tiek aprēķināta individuāli pēc maksātspējas izvērtējuma.');

    $goUrl = route('go', ['lender_id' => $lender->id, 'lender_position' => 1]);
@endphp

@extends('layouts.public')

@section('title', $customTitle)
@section('description', $customDesc)

@section('schema')
    @php
        $financialSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FinancialService',
            'name' => $lender->name,
            'url' => url()->current(),
            'image' => $lender->image ? url($lender->image) : null,
            'priceRange' => $lender->min_amount . ' EUR - ' . $lender->max_amount . ' EUR',
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Latvia',
            ],
        ];

        if ($data && !empty($data->address)) {
            $financialSchema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $data->address,
                'addressCountry' => 'LV',
            ];
        }
        if ($data && !empty($data->phone)) {
            $financialSchema['telephone'] = $data->phone;
        }
        if ($data && !empty($data->email)) {
            $financialSchema['email'] = $data->email;
        }

        if ($reviewCount > 0 && (float) $average_rating > 0) {
            $financialSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $average_rating,
                'bestRating' => '5',
                'worstRating' => '1',
                'reviewCount' => (int) $reviewCount,
            ];
        }
    @endphp
    <script type="application/ld+json">
        {!! json_encode($financialSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    {{-- Lender Dossier Header --}}
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    <div class="w-32 h-16 border border-rule bg-white p-2.5 flex items-center justify-center shrink-0">
                        @if (!empty($lender->image))
                            <img src="{{ $lender->image }}" alt="{{ $lender->name }}" class="max-h-12 w-auto object-contain">
                        @else
                            <span class="font-serif text-lg font-bold text-ink">{{ $lender->name }}</span>
                        @endif
                    </div>

                    <div>
                        <div class="kicker mb-1.5">
                            Licencēts aizdevējs Latvijā
                            @if ($lender->zero_percent)
                                · 0% pirmais kredīts
                            @endif
                        </div>

                        <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                            {{ clean_legacy_brand($data->h1 ?? ($lender->name . ' — apskats un atsauksmes')) }}
                        </h1>

                        @if ($data && !empty($data->slogan))
                            <p class="font-serif italic text-base text-ink-soft mt-1">{{ clean_legacy_brand($data->slogan) }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="#pievienot-atsauksmi" class="btn-line">
                        Rakstīt atsauksmi
                    </a>
                    @if ($lender->active && !empty($lender->affiliate_link))
                        <a href="{{ $goUrl }}"
                           rel="nofollow sponsored noopener"
                           target="_blank"
                           class="btn-ink">
                            Apskatīt {{ $lender->name }} piedāvājumu →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8 space-y-12">
                {{-- 1. Key Loan Terms & Company Information --}}
                <section>
                    <h2 class="font-serif text-2xl font-semibold text-ink pb-3 border-b border-ink">Kredīta nosacījumi un rekvizīti</h2>

                    <dl class="grid grid-cols-2 sm:grid-cols-4 border-t border-l border-rule bg-paper-light">
                        <div class="p-4 border-b border-r border-rule">
                            <dt class="meta uppercase tracking-wider">Kredīta summa</dt>
                            <dd class="font-serif text-xl font-semibold text-ink num mt-1">
                                {{ number_format((int) $lender->min_amount, 0, ',', ' ') }}–{{ number_format((int) $lender->max_amount, 0, ',', ' ') }} €
                            </dd>
                        </div>
                        <div class="p-4 border-b border-r border-rule">
                            <dt class="meta uppercase tracking-wider">Termiņš</dt>
                            <dd class="font-serif text-xl font-semibold text-ink num mt-1">
                                {{ format_term_label($lender->min_term) }} – {{ format_term_label($lender->max_term) }}
                            </dd>
                        </div>
                        <div class="p-4 border-b border-r border-rule">
                            <dt class="meta uppercase tracking-wider">Vecums</dt>
                            <dd class="font-serif text-xl font-semibold text-ink num mt-1">
                                {{ $lender->min_years ?: 18 }}–{{ $lender->max_years ?: 75 }} gadi
                            </dd>
                        </div>
                        <div class="p-4 border-b border-r border-rule">
                            <dt class="meta uppercase tracking-wider">Naudas izmaksa</dt>
                            <dd class="font-serif text-xl font-semibold text-claret num mt-1">
                                ~ {{ $lender->receiving_time ?: '15' }} min
                            </dd>
                        </div>
                    </dl>

                    <div class="border-l-2 border-claret pl-4 py-2 my-6 text-sm text-ink-soft leading-relaxed">
                        <strong class="text-ink">GPL un izmaksu piemērs:</strong> {{ $aprExample }}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-5 border-y border-rule text-sm">
                        <div class="space-y-1.5">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">Juridiskā informācija</h3>
                            @if ($data && !empty($data->company_name))
                                <p class="text-ink-soft"><strong class="text-ink">Uzņēmums:</strong> {{ $data->company_name }}</p>
                            @endif
                            @if ($data && !empty($data->address))
                                <p class="text-ink-soft"><strong class="text-ink">Adrese:</strong> {{ $data->address }}</p>
                            @endif
                            @if ($data && !empty($data->phone))
                                <p class="text-ink-soft"><strong class="text-ink">Tālrunis:</strong> {{ $data->phone }}</p>
                            @endif
                            @if ($data && !empty($data->email))
                                <p class="text-ink-soft"><strong class="text-ink">E-pasts:</strong> {{ $data->email }}</p>
                            @endif
                        </div>

                        <div class="space-y-1.5">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-ink-muted mb-2">Darba laiks</h3>
                            <p class="text-ink-soft"><strong class="text-ink">Darba dienās (P.–Pk.):</strong> {{ $workMf }}</p>
                            <p class="text-ink-soft"><strong class="text-ink">Sestdienā:</strong> {{ $workSa }}</p>
                            <p class="text-ink-soft"><strong class="text-ink">Svētdienā:</strong> {{ $workSu }}</p>
                        </div>
                    </div>

                    @if ($data && !empty($data->description))
                        <div class="mt-6 seo-prose no-dropcap">
                            {!! clean_legacy_brand($data->description) !!}
                        </div>
                    @endif
                </section>

                {{-- 2. Customer Rating Scorecard & Reviews List --}}
                <section id="reviews" class="border-t-2 border-ink pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pb-6 mb-6 border-b border-rule items-center">
                        <div class="md:col-span-4">
                            <div class="kicker mb-1">Lasītāju vērtējums</div>
                            <div class="font-serif text-4xl sm:text-5xl font-bold text-ink num">
                                {{ $reviewCount > 0 ? number_format((float) $average_rating, 1, ',', '') : '—' }}
                                <span class="text-lg font-normal text-ink-muted">/ 5</span>
                            </div>
                            <div class="meta mt-1">
                                Balstīts uz {{ $reviewCount }} {{ $reviewCount === 1 ? 'atsauksmi' : 'atsauksmēm' }}
                            </div>
                        </div>

                        <div class="md:col-span-8 space-y-1.5">
                            @foreach ([5, 4, 3, 2, 1] as $star)
                                @php
                                    $cnt = $breakdown[$star] ?? 0;
                                    $pct = $reviewCount > 0 ? round(($cnt / $reviewCount) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="w-14 font-semibold text-ink num">{{ $star }} zvaigz.</span>
                                    <div class="flex-1 h-2 bg-paper-dark border border-rule">
                                        <div class="h-full bg-ink" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-8 text-right text-ink-muted num">{{ $cnt }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <h2 class="font-serif text-2xl font-semibold text-ink mb-4">{{ $lender->name }} klientu atsauksmes</h2>

                    @if ($reviewCount > 0)
                        <div class="divide-y divide-rule border-y border-rule">
                            @foreach ($reviews as $review)
                                <article class="py-5">
                                    <div class="flex flex-wrap items-baseline justify-between gap-2 mb-2 text-xs">
                                        <div>
                                            <span class="font-semibold text-ink">{{ $review->name ?: 'Klients' }}</span>
                                            <span class="text-ink-faint mx-1.5">·</span>
                                            <time class="text-ink-muted num">{{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}</time>
                                        </div>
                                        <span class="font-semibold text-claret num">{{ $review->rating }}/5</span>
                                    </div>
                                    <p class="font-serif text-base text-ink-soft leading-relaxed">{{ clean_legacy_brand($review->text) }}</p>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-ink-muted py-4 border-y border-rule">Par šo aizdevēju vēl nav pievienota neviena atsauksme. Esiet pirmais un padalieties ar savu pieredzi.</p>
                    @endif
                </section>

                {{-- 3. Submit Review Form --}}
                <section id="pievienot-atsauksmi"
                         x-data="{ rating: {{ (int) old('rating', 5) }} }"
                         class="border border-ink bg-paper-light p-6 sm:p-8 scroll-mt-20">
                    <div class="kicker mb-1">Publicēt viedokli</div>
                    <h3 class="font-serif text-2xl font-semibold text-ink mb-1">Pievienot atsauksmi par {{ $lender->name }}</h3>
                    <p class="text-xs text-ink-muted mb-6">Jūsu pieredze palīdz citiem aizņēmējiem pieņemt pārdomātu lēmumu.</p>

                    @include('components.contactsinfobox')

                    <form method="POST"
                          action="{{ route('companies.review.create', ['companies_name' => $section->route_name, 'route_name' => $lender->route_name]) }}"
                          class="space-y-4 mt-4">
                        @csrf
                        <input type="hidden" name="lenderID" value="{{ $lender->id }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="rev-name" class="label">Jūsu vārds *</label>
                                <input id="rev-name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       placeholder="piem., Jānis"
                                       class="field">
                            </div>

                            <div>
                                <label for="rev-rating" class="label">Vērtējums *</label>
                                <select id="rev-rating"
                                        name="rating"
                                        x-model.number="rating"
                                        required
                                        class="field font-semibold">
                                    <option value="5">5 — Teicami</option>
                                    <option value="4">4 — Labi</option>
                                    <option value="3">3 — Viduvēji</option>
                                    <option value="2">2 — Vāji</option>
                                    <option value="1">1 — Slikti</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="rev-comment" class="label">Jūsu atsauksme *</label>
                            <textarea id="rev-comment"
                                       name="comment"
                                       rows="4"
                                       required
                                       placeholder="Pastāstiet par pieteikšanās ātrumu, apkalpošanu un nosacījumu skaidrību..."
                                       class="field">{{ old('comment') }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pt-2">
                            <div>
                                <label for="captcha-form" class="label">Drošības kods *</label>
                                <div class="flex items-center gap-3">
                                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                                         data-lazy-src="{{ route('captcha') }}"
                                         id="captcha" alt="Drošības kods" width="150" height="44"
                                         loading="lazy" decoding="async"
                                         class="h-11 w-36 border border-rule bg-white object-cover">
                                    <button type="button"
                                            onclick="document.getElementById('captcha').src='{{ route('captcha') }}?sid='+Math.random();document.getElementById('captcha-form').focus();"
                                            class="btn-line btn-sm">
                                        Jauns kods
                                    </button>
                                    <input id="captcha-form"
                                           type="text"
                                           name="captcha"
                                           maxlength="16"
                                           autocomplete="off"
                                           required
                                           placeholder="Kods"
                                           class="field w-32">
                                </div>
                            </div>

                            <button type="submit" class="btn-ink">
                                Publicēt atsauksmi
                            </button>
                        </div>
                    </form>
                </section>
            </div>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
