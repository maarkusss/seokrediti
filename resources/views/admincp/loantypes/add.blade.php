@extends('layouts.app')

@section('title', 'Add a loan type - ' . env('APP_NAME'))

@section('head')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Info box -->
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-auto h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Header card -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add loan type</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Create a new loan type category</p>
                    </div>
                    <a href="{{ route('admincp.loantypes.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to loan types
                    </a>
                </div>

                <form action="{{ route('admincp.loantypes.store') }}" method="POST"
                    class="w-full" enctype="multipart/form-data">
                    @csrf
                    <div id="editor"
                        data-image-browse-url="{{ env('APP_URL') . '/' . explode('-', app()->getlocale())[0] . '/admincp/images/' }}">
                    </div>

                    <div class="bg-white rounded border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
                        <div class="border-b border-slate-200 pb-3 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Main Information</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Core settings and metadata for this loan type</p>
                        </div>

                        <div class="flex items-center justify-start my-4" id="lender-image-container">
                            <img id="lender-image" class="object-contain max-w-full max-h-full">
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                            <div>
                                <label for="type" class="block text-xs font-semibold text-slate-600 mb-1.5">Parent Type</label>
                                <select name="type" id="type"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="0">Select a type</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Name</label>
                                <input id="name" name="name" type="text" placeholder="Loan type's name"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="route_name" class="block text-xs font-semibold text-slate-600 mb-1.5">Routing name <span class="text-red-500">*</span></label>
                                <input id="route_name" name="route_name" type="text" placeholder="e.g. atrie-krediti" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1.5">Page Title &lt;title&gt; <span class="text-red-500">*</span></label>
                                <input id="title" name="title" type="text" placeholder="Meta title" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="description" class="block text-xs font-semibold text-slate-600 mb-1.5">Meta Description</label>
                                <input id="description" name="description" type="text" placeholder="Meta description"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="keywords" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Keywords</label>
                                <input id="keywords" name="keywords" type="text" placeholder="keywords, comma, separated"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="anchor_element_title" class="block text-xs font-semibold text-slate-600 mb-1.5">Anchor element title</label>
                                <input id="anchor_element_title" name="anchor_element_title" type="text" placeholder="Anchor title attribute" maxlength="191"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Heading & Content</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Primary H1 tag and descriptions</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 mb-6">
                            <div>
                                <label for="h1" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Heading</label>
                                <input id="h1" name="h1" type="text" placeholder="Main H1 heading" maxlength="191" autocomplete="off"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="h1_description" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Description</label>
                                <input id="h1_description" name="h1_description" type="text" placeholder="Short description under H1" maxlength="191" autocomplete="off"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Head Script / HTML</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Custom code inserted directly into the &lt;head&gt; tag</p>
                        </div>
                        <div class="mb-6">
                            <textarea rows="6" name="section_html" placeholder="<script>...</script> or meta tags"
                                class="w-full px-3 py-2 font-mono text-xs text-slate-800 bg-slate-50 border border-slate-300 rounded shadow-xs focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"></textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Bottom Article & Design</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Choose layout style and edit the descriptive text below the loan offers</p>
                        </div>

                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-600 mb-2">Article Layout Design</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="layout-option-card relative flex flex-col p-3 rounded cursor-pointer">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="text_layout" value="panel" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Klasiskais panelis</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Virsraksts pelēkā paneļa galvenē (1. attēls - standarta panel-heading)</p>
                                </label>

                                <label class="layout-option-card relative flex flex-col p-3 rounded cursor-pointer">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="text_layout" value="lead_intro" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Lead Intro virsraksts</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Virsraksts atsevišķi virs baltā paneļa (2. attēls - brīvs H2)</p>
                                </label>

                                <label class="layout-option-card relative flex flex-col p-3 rounded cursor-pointer">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="text_layout" value="raw" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Brīvs HTML (Raw)</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Teksts tieši tā, kā ievadīts redaktorā, bez automātiska apvalka</p>
                                </label>
                            </div>
                        </div>
                        <div class="mb-6">
                            <textarea name="text" id="text"></textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-6">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800 mb-3">Visibility & Status</h2>
                            <div class="flex flex-col sm:flex-row gap-6">
                                <input name="active" type="hidden" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="active" type="checkbox" value="1" checked
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Active (published)</span>
                                </label>

                                <input name="display_dropdown" type="hidden" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="display_dropdown" type="checkbox" value="1"
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Display under dropdown</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-5 border-t border-slate-200">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create loan type
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('text');
    </script>
@endsection
