@extends('layouts.app')

@section('title', 'Edit a loan type - ' . env('APP_NAME'))

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
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-bold tracking-tight text-slate-800">Edit loan type</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $loantype->name }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            @if (request()->history && $loantype->user)
                                <span class="text-amber-600 font-medium">History snapshot created by {{ $loantype->user->first_name }} {{ $loantype->user->last_name }}</span>
                            @else
                                Update loan type details and section contents
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admincp.loantypes.index') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to loan types
                        </a>
                        @can('delete loantypes')
                            <button type="button"
                                onclick="if(confirm('Are you sure you want to delete this loan type?')) { document.getElementById('delete-loantype-form').submit(); }"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-red-600 rounded shadow-xs hover:bg-red-700 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        @endcan
                    </div>
                </div>

                <!-- Main Form -->
                <form action="{{ route('admincp.loantypes.update', ['loantype' => $loantype->id]) }}" method="POST"
                    class="w-full" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div id="editor"
                        data-image-browse-url="{{ env('APP_URL') . '/' . explode('-', app()->getlocale())[0] . '/admincp/images/' }}">
                    </div>

                    <div class="bg-white rounded border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
                        <div class="border-b border-slate-200 pb-3 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Main Information</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Core parameters, SEO metadata, and parent hierarchy</p>
                            </div>
                            
                        </div>

                        @if ($loantype->image)
                            <div class="flex items-center justify-start h-20 my-3 p-2 bg-slate-50 rounded border border-slate-200/70 inline-block" id="lender-image-container">
                                <img src="{{ $loantype->image }}" alt="Logo" id="lender_image"
                                    class="object-contain max-h-full">
                            </div>
                        @endif

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                            <div>
                                <label for="type" class="block text-xs font-semibold text-slate-600 mb-1.5">Parent loan type</label>
                                <select name="type" id="type"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="0">None (Top Level)</option>
                                    @foreach ($types as $type)
                                        @if ($type->id != $loantype->id)
                                            <option value="{{ $type->id }}"
                                                @if ($loantype->parent_type_id == $type->id) selected @endif>{{ $type->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Name</label>
                                <input id="name" name="name" type="text" value="{{ $loantype->name }}" placeholder="Loan type name"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="route_name" class="block text-xs font-semibold text-slate-600 mb-1.5">Routing name <span class="text-red-500">*</span></label>
                                <input id="route_name" name="route_name" type="text" value="{{ $loantype->route_name }}" placeholder="URL slug" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1.5">Page Title &lt;title&gt; <span class="text-red-500">*</span></label>
                                <input id="title" name="title" type="text" value="{{ $loantype->title }}" placeholder="Page title" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="description" class="block text-xs font-semibold text-slate-600 mb-1.5">Meta Description</label>
                                <input id="description" name="description" type="text" value="{{ $loantype->description }}" placeholder="Meta description"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="keywords" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Keywords</label>
                                <input id="keywords" name="keywords" type="text" value="{{ $loantype->keywords }}" placeholder="Keywords"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="anchor_element_title" class="block text-xs font-semibold text-slate-600 mb-1.5">Anchor element title</label>
                                <input id="anchor_element_title" name="anchor_element_title" type="text" value="{{ $loantype->anchor_element_title }}" placeholder="Anchor title" maxlength="191"
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
                                <input id="h1" name="h1" type="text" placeholder="Loan type H1" maxlength="191" autocomplete="off" value="{{ $loantype->h1 }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="h1_description" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Description</label>
                                <input id="h1_description" name="h1_description" type="text" placeholder="Loan type H1 description" maxlength="191" autocomplete="off" value="{{ $loantype->h1_description }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Head Script / HTML</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Section specific HTML in head tag</p>
                        </div>
                        <div class="mb-6">
                            <textarea rows="6" name="section_html" placeholder="<script>...</script> or meta tags"
                                class="w-full px-3 py-2 font-mono text-xs text-slate-800 bg-slate-50 border border-slate-300 rounded shadow-xs focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">{!! $loantype->section_html !!}</textarea>
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
                                        <input type="radio" name="text_layout" value="panel" {{ ($loantype->text_layout ?? 'panel') === 'panel' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Klasiskais panelis</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Virsraksts pelēkā paneļa galvenē (1. attēls - standarta panel-heading)</p>
                                </label>

                                <label class="layout-option-card relative flex flex-col p-3 rounded cursor-pointer">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="text_layout" value="lead_intro" {{ ($loantype->text_layout ?? '') === 'lead_intro' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Lead Intro virsraksts</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Virsraksts atsevišķi virs baltā paneļa (2. attēls - brīvs H2)</p>
                                </label>

                                <label class="layout-option-card relative flex flex-col p-3 rounded cursor-pointer">
                                    <div class="flex items-center gap-2 mb-1">
                                        <input type="radio" name="text_layout" value="raw" {{ ($loantype->text_layout ?? '') === 'raw' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-800">Brīvs HTML (Raw)</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-normal pl-6">Teksts tieši tā, kā ievadīts redaktorā, bez automātiska apvalka</p>
                                </label>
                            </div>
                        </div>
                        <div class="mb-6">
                            <textarea name="text" id="text">{!! $loantype->text !!}</textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-6">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800 mb-3">Visibility & Status</h2>
                            <div class="flex flex-col sm:flex-row gap-6">
                                <input type="hidden" name="active" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="active" type="checkbox" value="1"
                                        @if ($loantype->active && !request()->history) checked @endif
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Active (published)</span>
                                </label>

                                <input type="hidden" name="display_dropdown" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="display_dropdown" type="checkbox" value="1"
                                        @if ($loantype->display_dropdown && !request()->history) checked @endif
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Display under dropdown</span>
                                </label>
                            </div>
                        </div>


                        <div class="flex items-center justify-end pt-5 border-t border-slate-200">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Hidden Delete Form -->
                @can('delete loantypes')
                    <form id="delete-loantype-form" action="{{ route('admincp.loantypes.destroy', ['loantype' => $loantype->id]) }}"
                        method="POST" class="hidden">
                        @method('DELETE')
                        @csrf
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        var editorConfig = {
            scayt_autoStartup: false,
            enterMode: CKEDITOR.ENTER_BR,
            forcePasteAsPlainText: true,
            forceSimpleAmpersand: true,
            entities: false,
            basicEntities: false,
            entities_greek: false,
            entities_latin: false,
            toolbarCanCollapse: false
        };

        if (document.getElementById('text')) {
            CKEDITOR.replace('text', editorConfig);
        }

        
    </script>
@endsection
