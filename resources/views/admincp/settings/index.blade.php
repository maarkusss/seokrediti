@extends('layouts.app')

@section('title', 'Settings - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Settings</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Global website configuration, tracking scripts and system parameters</p>
                    </div>
                    @include('admincp.components.infobox')
                </div>

                <form method="POST" enctype="multipart/form-data" class="w-full space-y-6">
                    @csrf

                    <!-- 1. Main information -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Main information</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Basic website data and GoodAff identifiers</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
                            <div>
                                <label for="setting_name" class="block text-sm font-medium text-slate-700 mb-1.5">Name *</label>
                                <input id="setting_name"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="name" type="text" placeholder="Website's name"
                                    value="{{ $name->value ?? '' }}" required>
                            </div>

                            <div>
                                <label for="setting_api_key" class="block text-sm font-medium text-slate-700 mb-1.5">API KEY *</label>
                                <input id="setting_api_key"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="api_key" type="text" placeholder="Websites api_key"
                                    value="{{ $api_key->value ?? '' }}" required>
                            </div>

                            <div>
                                <label for="setting_prefix" class="block text-sm font-medium text-slate-700 mb-1.5">GoodAff S1 *</label>
                                <input id="setting_prefix"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="prefix" type="text" placeholder="eirokrediti"
                                    value="{{ $prefix->value ?? '' }}" required>
                            </div>

                            <div>
                                <label for="setting_epc" class="block text-sm font-medium text-slate-700 mb-1.5">Individual days EPC *</label>
                                <input id="setting_epc"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="individual_days_epc" type="text" placeholder="30"
                                    value="{{ $individual_days_epc->value ?? '' }}" required>
                            </div>

                            <div>
                                <label for="setting_country_code" class="block text-sm font-medium text-slate-700 mb-1.5">Country code *</label>
                                <input id="setting_country_code"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="country_code" type="text" placeholder="LV"
                                    value="{{ $country_code->value ?? '' }}" required>
                            </div>

                            <div>
                                <label for="setting_phone_length" class="block text-sm font-medium text-slate-700 mb-1.5">Phone length *</label>
                                <input id="setting_phone_length"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="phone_length" type="text" placeholder="8"
                                    value="{{ $phone_length->value ?? '' }}" required>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="setting_sitemap" class="block text-sm font-medium text-slate-700 mb-1.5">Sitemap crawler *</label>
                                <input id="setting_sitemap"
                                    class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="sitemap" type="url" placeholder="https://www.eirokrediti.lv/sitemap.xml"
                                    value="{{ $sitemap->value ?? '' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Scripts and tracking codes (<head>) -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Head code</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Google Tag Manager, Analytics or other custom tracking scripts</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @include('admincp.includes.textarea', [
                                'id' => 'head_code',
                                'name' => 'head_code',
                                'label' => 'Main Head code (all public pages)',
                                'placeholder' => '<!-- Google Tag Manager, Meta Pixel etc. -->',
                                'rows' => 10,
                                'value_if_auth' => $head_code->value ?? '',
                            ])

                            @include('admincp.includes.textarea', [
                                'id' => 'head_code_go',
                                'name' => 'head_code_go',
                                'label' => 'Head code for redirect page (/go)',
                                'placeholder' => '<!-- Code for /go redirect page only -->',
                                'rows' => 10,
                                'value_if_auth' => $head_code_go->value ?? '',
                            ])
                        </div>
                    </div>

                    <!-- 3. Static SEO and system files -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">SEO and static files</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Search engine instructions and PWA configuration</p>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @include('admincp.includes.textarea', [
                                'id' => 'robots_txt',
                                'name' => 'robots_txt',
                                'label' => 'robots.txt content',
                                'placeholder' => "User-agent: *\nDisallow: /admincp/\nSitemap: https://www.eirokrediti.lv/sitemap.xml",
                                'rows' => 9,
                                'value_if_auth' => $robots_txt->value ?? '',
                            ])

                            @include('admincp.includes.textarea', [
                                'id' => 'manifest_json',
                                'name' => 'manifest_json',
                                'label' => 'manifest.json content',
                                'placeholder' => "{\n  \"name\": \"Eirokrediti.lv\"\n}",
                                'rows' => 9,
                                'value_if_auth' => $manifest_json->value ?? '',
                            ])
                        </div>
                    </div>

                    <!-- 4. Localization and cookies -->
                    <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                        <div class="pb-3.5 mb-5 border-b border-slate-100">
                            <h2 class="text-base font-bold text-slate-800">Localization and cookies</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Available website languages and cookie notice</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <!-- Languages -->
                            <div class="p-4 border border-slate-200 rounded bg-slate-50/60">
                                <label class="block text-sm font-semibold text-slate-800 mb-2.5">Active languages</label>
                                <div class="space-y-2.5">
                                    <label class="flex items-center gap-3 text-sm text-slate-700 cursor-pointer select-none">
                                        <input name="lang['lv']" value="lv" type="checkbox"
                                            class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                            @if ($lang && $lang->where('value', 'lv')->first()) checked @endif>
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

                            <!-- Cookie Banner -->
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

                        <!-- Save Main Information Button (Removed separate button; single unified Save button at bottom of page) -->
                    </div>

                    <!-- 5. System configuration (Config) -->
                    @can('edit settings config')
                        <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                            <div class="pb-3.5 mb-5 border-b border-slate-100">
                                <h2 class="text-base font-bold text-slate-800">System configuration (Config)</h2>
                                <p class="text-xs text-slate-500 mt-0.5">GoodAff commissions import and algorithm multipliers</p>
                            </div>

                            <!-- Import Commissions -->
                            <div class="mb-6 max-w-2xl">
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">GoodAff commissions synchronization</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="sm:col-span-2">
                                        <label for="setting_commissions_link" class="block text-sm font-medium text-slate-700 mb-1.5">Commissions link (URL)</label>
                                        <input id="setting_commissions_link"
                                            class="w-full px-3.5 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                            name="commissions_link" type="text" placeholder="https://goodaff.com/api/v1/..."
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

                            <!-- Frequency Multipliers -->
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
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-sm hover:shadow transition-all cursor-pointer"
                            type="submit" name="action" value="save_all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save settings</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
