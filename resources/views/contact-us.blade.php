@extends('layouts.public')

@section('title', $section->title ?: ('Par mums un saziņa — ' . brand_name()))
@section('description', $section->description ?: ('Uzzini par ' . brand_name() . ' neatkarīgo kredītu salīdzināšanas metodoloģiju, datu avotiem un sazinies ar mūsu komandu.'))

@section('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => clean_legacy_brand($section->h1 ?: ($section->name ?: 'Par mums un kontakti')),
            'description' => clean_legacy_brand($section->description ?: brand_tagline()),
            'url' => request()->url(),
            'mainEntity' => [
                '@id' => rtrim(url('/'), '/') . '/#organization',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <span class="kicker mb-2">Redakcija un metodoloģija</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                    {{ clean_legacy_brand($section->h1 ?: ($section->name ?: 'Par mums un saziņa')) }}
                </h1>
                <p class="standfirst mt-3">
                    {{ clean_legacy_brand($section->h1_description ?: 'Mūsu mērķis ir padarīt kredītu tirgu Latvijā caurskatāmu, objektīvu un saprotamu katram aizņēmējam — salīdzinot GPL, termiņus un reālas klientu atsauksmes vienuviet.') }}
                </p>
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-8 space-y-12">
                {{-- Editorial Transparency Pillars --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 border-y border-rule divide-y sm:divide-y-0 sm:divide-x divide-rule bg-paper-light">
                    <div class="p-5">
                        <div class="font-serif text-xs uppercase tracking-wider text-claret font-semibold mb-1">I princips</div>
                        <h2 class="font-serif text-lg font-semibold text-ink mb-1.5">Tikai licencēti aizdevēji</h2>
                        <p class="text-xs text-ink-soft leading-relaxed">
                            Salīdzinām tikai Latvijā reģistrētus un PTAC uzraudzītus nebanku un banku kredītdevējus.
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="font-serif text-xs uppercase tracking-wider text-claret font-semibold mb-1">II princips</div>
                        <h2 class="font-serif text-lg font-semibold text-ink mb-1.5">Caurskatāma GPL</h2>
                        <p class="text-xs text-ink-soft leading-relaxed">
                            Katram piedāvājumam uzrādām gada procentu likmi (GPL), termiņus un reprezentatīvu izmaksu piemēru.
                        </p>
                    </div>

                    <div class="p-5">
                        <div class="font-serif text-xs uppercase tracking-wider text-claret font-semibold mb-1">III princips</div>
                        <h2 class="font-serif text-lg font-semibold text-ink mb-1.5">Bezmaksas piekļuve</h2>
                        <p class="text-xs text-ink-soft leading-relaxed">
                            Salīdzināšanas tabulas un kalkulators lasītājiem ir bezmaksas un neuzliek saistības.
                        </p>
                    </div>
                </div>

                {{-- About Text --}}
                <article>
                    @php
                        $contentText = clean_legacy_brand($section->text ?? '');
                        if (str_contains($contentText, 'navbar-header') || str_contains($contentText, 'container-fluid')) {
                            if (preg_match('/<div class="panel-body">\s*(.*?)\s*<h2>Uzdod jautājumu/is', $contentText, $matches)) {
                                $contentText = $matches[1];
                            }
                        }
                    @endphp

                    <div class="seo-prose">
                        @if (trim($contentText) !== '')
                            {!! $contentText !!}
                        @else
                            <h2>Mūsu misija un salīdzināšanas metodoloģija</h2>
                            <p>
                                <strong>{{ brand_name() }}</strong> ir neatkarīga finanšu informācijas un kredītu salīdzināšanas platforma Latvijā. Mēs neizsniedzam kredītus, bet gan apkopojam un regulāri atjaunojam informāciju par licencētu kredītiestāžu un nebanku aizdevēju piedāvājumiem — tostarp <a href="{{ url('/atrie-krediti') }}">ātrajiem kredītiem</a>, <a href="{{ url('/paterina-krediti') }}">patēriņa kredītiem</a>, <a href="{{ url('/kreditu-apvienosana') }}">kredītu apvienošanu</a> un <a href="{{ url('/auto-lizings') }}">auto finansējumu</a>.
                            </p>
                            <p>
                                Lai palīdzētu pieņemt pārdomātu un finansiāli drošu lēmumu, mēs papildinām tehniskos parametrus ar reālām lietotāju <a href="{{ url('/atsauksmes') }}">atsauksmēm</a> un praktiskiem finanšu pratības rakstiem sadaļā <a href="{{ url('/blogs') }}">Blogs</a>.
                            </p>
                        @endif
                    </div>

                    <div class="mt-6 pt-5 border-t border-rule flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-sm">
                        <div>
                            Tiešā redakcijas e-pasta adrese:
                            <a href="mailto:{{ brand_email() }}" class="link font-semibold">{{ brand_email() }}</a>
                        </div>
                        <span class="meta">Atbildam darba dienās 24 stundu laikā</span>
                    </div>
                </article>

                {{-- Contact Form --}}
                <section id="sazina" class="border border-ink bg-paper-light p-6 sm:p-8" aria-labelledby="contact-form-heading">
                    <div class="mb-6">
                        <span class="kicker mb-1">Saziņa ar redakciju</span>
                        <h2 id="contact-form-heading" class="font-serif text-2xl font-semibold text-ink">
                            Uzdodiet jautājumu vai piesakiet sadarbību
                        </h2>
                        <p class="text-xs text-ink-muted mt-1">
                            Pamanījāt neprecizitāti datos, vēlaties uzdot jautājumu vai pārstāvat licencētu aizdevēju? Rakstiet mums zemāk.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 border-l-4 border-ink bg-paper p-4 text-sm font-medium text-ink" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 border-l-4 border-claret bg-claret-tint/50 p-4 text-sm text-ink" role="alert">
                            <div class="font-semibold mb-1">Lūdzu, pārbaudiet ievadītos datus:</div>
                            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form name="send_feedback" method="POST" action="{{ route('section.post', ['name' => $section->route_name]) }}" id="form" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="send_feedback_name" class="label">Vārds *</label>
                                <input id="send_feedback_name"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       maxlength="100"
                                       placeholder="Jūsu vārds"
                                       class="field">
                            </div>

                            <div>
                                <label for="send_feedback_email" class="label">E-pasta adrese *</label>
                                <input id="send_feedback_email"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       maxlength="150"
                                       placeholder="vards@piemers.lv"
                                       class="field">
                            </div>
                        </div>

                        <div>
                            <label for="send_feedback_question" class="label">Ziņojums vai jautājums *</label>
                            <textarea id="send_feedback_question"
                                      name="question"
                                      rows="5"
                                      required
                                      maxlength="3000"
                                      placeholder="Aprakstiet savu jautājumu vai sadarbības piedāvājumu..."
                                      class="field">{{ old('question') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end pt-1">
                            <div>
                                <label for="captcha-form" class="label">Drošības kods *</label>
                                <div class="flex items-center gap-3">
                                    <img src="{{ route('captcha') }}"
                                         id="captcha"
                                         alt="Drošības kods"
                                         width="160"
                                         height="48"
                                         loading="lazy"
                                         decoding="async"
                                         class="h-11 w-36 border border-rule bg-white object-cover shrink-0">
                                    <button type="button"
                                            onclick="document.getElementById('captcha').src='{{ route('captcha') }}?'+Math.random();document.getElementById('captcha-form').focus();"
                                            class="btn-line btn-sm"
                                            title="Atsvaidzināt drošības kodu">
                                        Jauns kods
                                    </button>
                                </div>
                            </div>

                            <div>
                                <input type="text"
                                       name="captcha"
                                       id="captcha-form"
                                       required
                                       maxlength="16"
                                       autocomplete="off"
                                       placeholder="Ievadiet attēlā redzamo kodu"
                                       class="field">
                            </div>
                        </div>

                        <div class="pt-3 border-t border-rule flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <p class="text-xs text-ink-muted">
                                Nosūtot ziņojumu, Jūs piekrītat datu apstrādei saskaņā ar <a href="{{ url('/privatuma-politika') }}" class="link">privātuma politiku</a>.
                            </p>
                            <button type="submit"
                                    name="send_feedback"
                                    value="Nosūtīt"
                                    class="btn-ink shrink-0">
                                Nosūtīt ziņojumu →
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
