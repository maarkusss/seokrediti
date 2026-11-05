@php
    $openByDefault = session('newsletter_open') ? true : false;
    $flashSuccess = session('newsletter_success');
    $flashError = session('newsletter_error');
@endphp

<div x-data="{
        open: @js($openByDefault),
        loading: false,
        email: @js(old('email', '')),
        captcha: '',
        consent: @js((bool) old('consent', false)),
        websiteUrl: '',
        captchaUrl: '',
        statusType: @js($flashSuccess ? 'success' : ($flashError ? 'error' : '')),
        statusMessage: @js($flashSuccess ?: ($flashError ?: '')),
        init() {
            if (this.open) {
                this.refreshCaptcha();
            }
        },
        openDrawer() {
            this.open = true;
            if (!this.captchaUrl) {
                this.refreshCaptcha();
            }
            this.$nextTick(() => {
                const emailInput = document.getElementById('nl-email');
                if (emailInput && this.statusType !== 'success') {
                    emailInput.focus();
                }
            });
        },
        closeDrawer() {
            this.open = false;
        },
        refreshCaptcha() {
            this.captchaUrl = '{{ route('captcha') }}?sid=' + Date.now() + Math.random().toString(36).substring(2, 6);
        },
        async submitForm() {
            if (this.loading) return;
            this.loading = true;
            this.statusType = '';
            this.statusMessage = '';

            try {
                const response = await fetch('{{ route('subscribe.email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        captcha: this.captcha,
                        consent: this.consent ? 1 : 0,
                        website_url: this.websiteUrl
                    })
                });

                const data = await response.json().catch(() => ({}));

                if (response.ok && data.status === 'ok') {
                    this.statusType = 'success';
                    this.statusMessage = data.message || 'Paldies! Jūsu e-pasts ir veiksmīgi reģistrēts.';
                    this.email = '';
                    this.captcha = '';
                    this.consent = false;
                } else {
                    this.statusType = 'error';
                    if (response.status === 429) {
                        this.statusMessage = 'Pārāk daudz mēģinājumu. Lūdzu, uzgaidiet minūti un mēģiniet vēlreiz.';
                    } else {
                        this.statusMessage = data.message || 'Neizdevās saglabāt pieteikumu. Lūdzu, pārbaudiet ievadītos datus.';
                    }
                    this.captcha = '';
                    this.refreshCaptcha();
                }
            } catch (err) {
                this.statusType = 'error';
                this.statusMessage = 'Savienojuma kļūda. Lūdzu, mēģiniet vēlreiz.';
                this.refreshCaptcha();
            } finally {
                this.loading = false;
            }
        }
    }"
    @keydown.escape.window="if (open) closeDrawer()"
    data-nosnippet>

    {{-- Bottom-Left Floating Editorial Trigger Button --}}
    <button type="button"
            @click="openDrawer()"
            x-show="!open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            aria-label="Pierakstīties kredītu jaunumiem"
            class="fixed bottom-5 left-5 z-40 group flex items-center gap-3 bg-paper-light text-ink border border-ink border-l-4 border-l-claret px-4 py-2.5 shadow-[3px_3px_0_0_#151412] hover:bg-ink hover:text-paper-light transition-all duration-200 cursor-pointer">
        <svg class="w-4 h-4 text-claret group-hover:text-paper-light transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="square" stroke-linejoin="miter" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z" />
        </svg>
        <div class="text-left leading-tight">
            <span class="block text-[10px] font-bold uppercase tracking-[0.14em] text-claret group-hover:text-paper-dark transition-colors">
                E-pasta apskats
            </span>
            <span class="block font-serif text-sm font-semibold">
                Saņemt jaunumus
            </span>
        </div>
    </button>

    {{-- Backdrop Overlay --}}
    <div x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDrawer()"
         class="fixed inset-0 z-50 bg-ink/50 backdrop-blur-[1px]"
         aria-hidden="true"></div>

    {{-- Left Slide-Over Editorial Drawer --}}
    <aside x-cloak
           x-show="open"
           x-transition:enter="transition transform ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition transform ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           role="dialog"
           aria-modal="true"
           aria-labelledby="newsletter-drawer-title"
           class="fixed inset-y-0 left-0 z-50 w-full max-w-md bg-paper-light text-ink border-r-2 border-ink flex flex-col justify-between overflow-y-auto shadow-2xl">

        <div>
            {{-- Drawer Top Bar --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-ink bg-paper">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-claret inline-block"></span>
                    <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink">
                        {{ brand_name() }}
                    </span>
                </div>
                <button type="button"
                        @click="closeDrawer()"
                        aria-label="Aizvērt logu"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-ink-muted hover:text-claret transition-colors cursor-pointer py-1 px-2 border border-transparent hover:border-rule">
                    <span>Aizvērt</span>
                    <span class="text-base leading-none">&times;</span>
                </button>
            </div>

            {{-- Drawer Body --}}
            <div class="p-6 sm:p-8">
                <div class="kicker mb-2">Finanšu un kredītu apskats</div>
                <h2 id="newsletter-drawer-title" class="font-serif text-2xl sm:text-3xl font-bold text-ink leading-tight">
                    Aktuālie kredītu piedāvājumi jūsu e-pastā
                </h2>
                <p class="mt-3 text-sm text-ink-soft leading-relaxed">
                    Pierakstieties {{ brand_name() }} informatīvajiem paziņojumiem un uzziniet pirmie par zemākajām GPL likmēm, jauniem licencētiem aizdevējiem un bezprocentu akcijām.
                </p>

                {{-- Benefits List --}}
                <ul class="mt-5 pt-4 border-t border-rule space-y-2 text-xs text-ink-soft">
                    <li class="flex items-start gap-2.5">
                        <span class="text-claret font-bold mt-0.5">01</span>
                        <span>Salīdzināti un pārbaudīti Latvijas kredītu piedāvājumi</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-claret font-bold mt-0.5">02</span>
                        <span>Praktiski padomi kredītu apvienošanai un procentu samazināšanai</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-claret font-bold mt-0.5">03</span>
                        <span>Iespēja jebkurā brīdī atrakstīties ar vienu klikšķi</span>
                    </li>
                </ul>

                {{-- Status Alert --}}
                <div x-show="statusMessage !== ''"
                     x-transition
                     class="mt-6 border-l-4 p-4 text-sm"
                     :class="statusType === 'success' ? 'border-emerald-800 bg-paper text-ink' : 'border-claret bg-claret-tint/60 text-ink'"
                     role="alert">
                    <div class="font-semibold" x-text="statusType === 'success' ? 'Pieteikums saņemts' : 'Uzmanību'"></div>
                    <p class="text-xs text-ink-soft mt-1 leading-relaxed" x-text="statusMessage"></p>
                    <template x-if="statusType === 'success'">
                        <button type="button"
                                @click="closeDrawer()"
                                class="mt-3 btn-line btn-sm">
                            Turpināt lasīt portālu
                        </button>
                    </template>
                </div>

                {{-- Subscription Form --}}
                <form id="newsletter_form"
                      method="POST"
                      action="{{ route('subscribe.email') }}"
                      @submit.prevent="submitForm()"
                      x-show="statusType !== 'success'"
                      class="mt-6 pt-6 border-t border-ink space-y-4">
                    @csrf

                    {{-- Honeypot against automated spam bots --}}
                    <div class="sr-only" aria-hidden="true">
                        <label for="nl-website-url">Website</label>
                        <input id="nl-website-url"
                               type="text"
                               name="website_url"
                               x-model="websiteUrl"
                               tabindex="-1"
                               autocomplete="off">
                    </div>

                    <div>
                        <label for="nl-email" class="label">Jūsu e-pasta adrese *</label>
                        <input id="nl-email"
                               type="email"
                               name="email"
                               x-model="email"
                               required
                               autocomplete="email"
                               placeholder="Jūsu e-pasta adrese"
                               class="field">
                    </div>

                    {{-- CAPTCHA Verification --}}
                    <div>
                        <label for="nl-captcha" class="label">Drošības kods (pret mēstulēm) *</label>
                        <div class="flex items-center gap-2.5">
                            <div class="h-11 w-36 border border-rule bg-white flex items-center justify-center shrink-0 overflow-hidden">
                                <template x-if="captchaUrl">
                                    <img :src="captchaUrl"
                                         alt="Drošības kods"
                                         width="144"
                                         height="44"
                                         class="h-11 w-36 object-cover">
                                </template>
                            </div>
                            <button type="button"
                                    @click="refreshCaptcha(); $nextTick(() => document.getElementById('nl-captcha')?.focus())"
                                    title="Ģenerēt jaunu kodu"
                                    class="btn-line btn-sm shrink-0">
                                Jauns kods
                            </button>
                            <input id="nl-captcha"
                                   type="text"
                                   name="captcha"
                                   x-model="captcha"
                                   maxlength="10"
                                   autocomplete="off"
                                   required
                                   placeholder="Kods"
                                   class="field min-w-0 flex-1 uppercase tracking-wider font-semibold">
                        </div>
                    </div>

                    {{-- GDPR & Terms Consent --}}
                    <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                        <input type="checkbox"
                               name="consent"
                               value="1"
                               x-model="consent"
                               required
                               class="mt-0.5 h-4 w-4 accent-[#9e1b1b] border-ink rounded-none shrink-0">
                        <span class="text-xs text-ink-soft leading-relaxed">
                            Piekrītu saņemt informatīvus jaunumus un kredītu piedāvājumus e-pastā saskaņā ar
                            <a href="{{ url('/privatuma-politika') }}" class="link">Privātuma politiku</a>.
                        </span>
                    </label>

                    <div class="pt-2">
                        <button type="submit"
                                :disabled="loading"
                                class="btn-ink w-full justify-center py-3 disabled:opacity-60">
                            <span x-show="!loading">Pierakstīties jaunumiem →</span>
                            <span x-show="loading">Reģistrē e-pastu...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Drawer Footer --}}
        <div class="px-6 py-4 border-t border-rule bg-paper flex items-center justify-between text-[11px] text-ink-muted">
            <span>Datu aizsardzība · GDPR</span>
            <a href="{{ url('/unsubscribe') }}" class="link text-[11px]">
                Atrakstīties no jaunumiem
            </a>
        </div>
    </aside>
</div>
