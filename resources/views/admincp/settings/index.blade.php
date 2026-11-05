@extends('layouts.app')

@section('title', 'Settings - ' . brand_name())

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Iestatījumi un zīmols (Settings)</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Vietnes nosaukums, logo, kontakti, SEO faili un sistēmas parametri</p>
                    </div>
                    @include('admincp.components.infobox')
                </div>

                <form method="POST" enctype="multipart/form-data" class="w-full space-y-6">
                    @csrf

                    <!-- 0. White-Label Brand & Logo Identity -->
                    <div class="w-full p-6 bg-white rounded border border-emerald-200 shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-5 border-b border-slate-100">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1.5">
                                    White-Label identitāte
                                </span>
                                <h2 class="text-base font-bold text-slate-800">Zīmols, Nosaukums un Logo</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Mainot nosaukumu vai augšupielādējot logo šeit, tas automātiski atjaunojas visā lapā, SEO virsrakstos, kājenē un strukturētajos datos.</p>
                            </div>
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 shrink-0">
                                <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Pašreizējais priekšskatījums</div>
                                <x-brand-logo size="sm" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-5">
                            <div>
                                <label for="setting_name" class="block text-sm font-medium text-slate-700 mb-1.5">Lapas nosaukums (Brand Name) *</label>
                                <input id="setting_name"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all"
                                    name="name" type="text" placeholder="piem., KredītuSalīdzinātājs vai TavsZīmols.lv"
                                    value="{{ old('name', $name->value ?? brand_name()) }}" required>
                            </div>

                            <div>
                                <label for="setting_brand_tagline" class="block text-sm font-medium text-slate-700 mb-1.5">Apakšvirsraksts / Saukls (Tagline)</label>
                                <input id="setting_brand_tagline"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all"
                                    name="brand_tagline" type="text" placeholder="Neatkarīgs kredītu salīdzinājums Latvijā"
                                    value="{{ old('brand_tagline', $brand_tagline->value ?? brand_tagline()) }}">
                            </div>

                            <div>
                                <label for="setting_contact_email" class="block text-sm font-medium text-slate-700 mb-1.5">Kontaktu e-pasts</label>
                                <input id="setting_contact_email"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all"
                                    name="contact_email" type="email" placeholder="info@tavszimols.lv"
                                    value="{{ old('contact_email', $contact_email->value ?? brand_email()) }}">
                            </div>

                            <div>
                                <label for="setting_brand_logo_file" class="block text-sm font-medium text-slate-700 mb-1.5">Augšupielādēt jaunu Logo (SVG, PNG, WEBP)</label>
                                <input id="setting_brand_logo_file"
                                    class="w-full px-3 py-1.5 text-sm text-slate-700 bg-white border border-slate-300 rounded file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                                    name="brand_logo_file" type="file" accept=".svg,.png,.jpg,.jpeg,.webp">
                                <p class="text-[11px] text-slate-500 mt-1">Ja logo nav augšupielādēts, tiek rādīts automātisks vektora simbols ar lapas nosaukumu.</p>
                            </div>

                            <div>
                                <label for="setting_brand_logo" class="block text-sm font-medium text-slate-700 mb-1.5">Vai Logo ceļš / URL</label>
                                <input id="setting_brand_logo"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all font-mono"
                                    name="brand_logo" type="text" placeholder="/images/brand/logo.svg"
                                    value="{{ old('brand_logo', $brand_logo->value ?? '') }}">
                                @if (!empty($brand_logo->value))
                                    <label class="inline-flex items-center gap-2 text-xs text-rose-600 mt-2 cursor-pointer">
                                        <input type="checkbox" name="remove_brand_logo" value="1" class="rounded border-slate-300 text-rose-600">
                                        <span>Noņemt attēla logo (izmantot dinamisko teksta + ikonas logo)</span>
                                    </label>
                                @endif
                            </div>

                            <div>
                                <label for="setting_brand_disclaimer" class="block text-sm font-medium text-slate-700 mb-1.5">Atbildīgas aizņemšanās brīdinājums (PTAC)</label>
                                <textarea id="setting_brand_disclaimer" name="brand_disclaimer" rows="2"
                                    class="w-full px-3.5 py-2 text-xs text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 shadow-xs transition-all"
                                    placeholder="Aizņemies atbildīgi, izvērtējot savas iespējas kredītu atmaksāt.">{{ old('brand_disclaimer', $brand_disclaimer->value ?? brand_disclaimer()) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Main technical parameters -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Tehniskie un partneru tīkla parametri</h2>
                            <p class="text-xs text-slate-500 mt-0.5">API atslēgas, S1 identifikators un lapas kartes ģenerēšanas adrese</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-2">
                            <div>
                                <label for="setting_api_key" class="block text-sm font-medium text-slate-700 mb-1.5">API KEY *</label>
                                <input id="setting_api_key"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="api_key" type="text" placeholder="portal_api_key"
                                    value="{{ old('api_key', $api_key->value ?? '') }}" required>
                            </div>

                            <div>
                                <label for="setting_prefix" class="block text-sm font-medium text-slate-700 mb-1.5">Partneru S1 prefikss *</label>
                                <input id="setting_prefix"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="prefix" type="text" placeholder="krediti"
                                    value="{{ old('prefix', $prefix->value ?? '') }}" required>
                            </div>

                            <div>
                                <label for="setting_epc" class="block text-sm font-medium text-slate-700 mb-1.5">Individual days EPC *</label>
                                <input id="setting_epc"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="individual_days_epc" type="text" placeholder="30"
                                    value="{{ old('individual_days_epc', $individual_days_epc->value ?? '30') }}" required>
                            </div>

                            <div>
                                <label for="setting_country_code" class="block text-sm font-medium text-slate-700 mb-1.5">Country code *</label>
                                <input id="setting_country_code"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="country_code" type="text" placeholder="371"
                                    value="{{ old('country_code', $country_code->value ?? '371') }}" required>
                            </div>

                            <div>
                                <label for="setting_phone_length" class="block text-sm font-medium text-slate-700 mb-1.5">Phone length *</label>
                                <input id="setting_phone_length"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="phone_length" type="text" placeholder="8"
                                    value="{{ old('phone_length', $phone_length->value ?? '8') }}" required>
                            </div>

                            <div class="sm:col-span-2 lg:col-span-3">
                                <label for="setting_sitemap" class="block text-sm font-medium text-slate-700 mb-1.5">Sitemap bāzes URL *</label>
                                <input id="setting_sitemap"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="sitemap" type="url" placeholder="{{ url('/') }}"
                                    value="{{ old('sitemap', $sitemap->value ?? url('/')) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Scripts and tracking codes (<head>) -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Head code</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Google Tag Manager, Analytics vai citi analītikas skripti</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @include('admincp.includes.textarea', [
                                'id' => 'head_code',
                                'name' => 'head_code',
                                'label' => 'Main Head code (all public pages)',
                                'placeholder' => '<!-- Google Tag Manager, Analytics etc. -->',
                                'rows' => 8,
                                'value_if_auth' => $head_code->value ?? '',
                            ])

                            @include('admincp.includes.textarea', [
                                'id' => 'head_code_go',
                                'name' => 'head_code_go',
                                'label' => 'Head code for redirect page (/go)',
                                'placeholder' => '<!-- Code for /go redirect page only -->',
                                'rows' => 8,
                                'value_if_auth' => $head_code_go->value ?? '',
                            ])
                        </div>
                    </div>

                    <!-- 3. Static SEO and system files -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">SEO un sistēmas faili</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Ja atstāts tukšs, sistēma automātiski ģenerē korektu robots.txt un manifest.json ar aktuālo zīmolu</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @include('admincp.includes.textarea', [
                                'id' => 'robots_txt',
                                'name' => 'robots_txt',
                                'label' => 'robots.txt content (optional override)',
                                'placeholder' => "User-agent: *\nAllow: /\nDisallow: /go/\nDisallow: /admincp/\nSitemap: " . url('/sitemap.xml'),
                                'rows' => 8,
                                'value_if_auth' => $robots_txt->value ?? '',
                            ])

                            @include('admincp.includes.textarea', [
                                'id' => 'manifest_json',
                                'name' => 'manifest_json',
                                'label' => 'manifest.json content (optional override)',
                                'placeholder' => "{\n  \"name\": \"" . brand_name() . "\"\n}",
                                'rows' => 8,
                                'value_if_auth' => $manifest_json->value ?? '',
                            ])
                        </div>
                    </div>

                    <!-- 4. Localization and cookies -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Valodas un sīkfailu paziņojums</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Aktīvās vietnes valodas un sīkfailu josla apmeklētājiem</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-2">
                            <div class="p-4 border border-slate-200 rounded bg-slate-50/60">
                                <label class="block text-sm font-semibold text-slate-800 mb-2.5">Active languages</label>
                                <div class="space-y-2.5">
                                    <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer select-none">
                                        <input name="lang['lv']" value="lv" type="checkbox"
                                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                            @if (!$lang || $lang->isEmpty() || $lang->where('value', 'lv')->first()) checked @endif>
                                        <span class="font-medium">Latvian (LV)</span>
                                    </label>
                                    <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer select-none">
                                        <input name="lang['ru']" value="ru" type="checkbox"
                                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                            @if ($lang && $lang->where('value', 'ru')->first()) checked @endif>
                                        <span class="font-medium">Russian (RU)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="p-4 border border-slate-200 rounded bg-slate-50/60">
                                <label class="block text-sm font-semibold text-slate-800 mb-2.5">Cookies</label>
                                <input name="cookie" value="false" type="hidden">
                                <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer select-none mt-3">
                                    <input name="cookie" value="true" type="checkbox"
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                        @if ($cookie && $cookie->value == 'true') checked @endif>
                                    <span>Show cookie notice bar to visitors</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 5. System configuration (Config) -->
                    @can('edit settings config')
                        <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                            <div class="pb-3.5 mb-5 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-800">System configuration (Config)</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Partneru komisiju sinhronizācija un algoritma reizinātāji</p>
                            </div>

                            <div class="mb-6 max-w-2xl">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Commissions synchronization</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="sm:col-span-2">
                                        <label for="setting_commissions_link" class="block text-sm font-medium text-slate-700 mb-1.5">Commissions link (URL)</label>
                                        <input id="setting_commissions_link"
                                            class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                            name="commissions_link" type="text" placeholder="https://api.example.com/v1/commissions"
                                            value="{{ $commissions_link->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_commissions_id" class="block text-sm font-medium text-slate-700 mb-1.5">Commissions ID (User ID)</label>
                                        <input id="setting_commissions_id"
                                            class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                            name="commissions_id" type="text" placeholder="1234"
                                            value="{{ $commissions_id->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_commissions_token" class="block text-sm font-medium text-slate-700 mb-1.5">Commissions token</label>
                                        <input id="setting_commissions_token"
                                            class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                            name="commissions_token" type="text" placeholder="token_..."
                                            value="{{ $commissions_token->value ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-5 border-t border-slate-100 max-w-2xl">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Algorithm frequency multipliers & Average commission</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3.5">
                                    <div>
                                        <label for="setting_freq_5" class="block text-xs font-medium text-slate-600 mb-1">Frequency 5</label>
                                        <input id="setting_freq_5"
                                            class="w-full px-3 py-2 text-sm text-center font-mono text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                            name="freq_5" type="text" placeholder="0.35"
                                            value="{{ $freq_5->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_freq_4" class="block text-xs font-medium text-slate-600 mb-1">Frequency 4</label>
                                        <input id="setting_freq_4"
                                            class="w-full px-3 py-2 text-sm text-center font-mono text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                            name="freq_4" type="text" placeholder="0.40"
                                            value="{{ $freq_4->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_freq_3" class="block text-xs font-medium text-slate-600 mb-1">Frequency 3</label>
                                        <input id="setting_freq_3"
                                            class="w-full px-3 py-2 text-sm text-center font-mono text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                            name="freq_3" type="text" placeholder="0.50"
                                            value="{{ $freq_3->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_freq_2" class="block text-xs font-medium text-slate-600 mb-1">Frequency 2</label>
                                        <input id="setting_freq_2"
                                            class="w-full px-3 py-2 text-sm text-center font-mono text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                            name="freq_2" type="text" placeholder="0.70"
                                            value="{{ $freq_2->value ?? '' }}">
                                    </div>
                                    <div>
                                        <label for="setting_avg_commission" class="block text-xs font-medium text-slate-600 mb-1">Avg. Comm. (€)</label>
                                        <input id="setting_avg_commission"
                                            class="w-full px-3 py-2 text-sm text-center font-mono text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20"
                                            name="avg_commission" type="text" placeholder="20"
                                            value="{{ $avg_commission->value ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    <!-- Single unified Save settings button -->
                    <div class="flex items-center justify-end pt-2 pb-6">
                        <button
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded shadow-sm hover:shadow transition-all cursor-pointer"
                            type="submit" name="action" value="save_all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Saglabāt iestatījumus</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
