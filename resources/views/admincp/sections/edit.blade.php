@extends('layouts.app')

@section('title', 'Edit a section - ' . env('APP_NAME'))

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
                            <h1 class="text-xl font-bold tracking-tight text-slate-800">Edit section</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $section->name }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            @if (request()->history && $section->user)
                                <span class="text-amber-600 font-medium">History snapshot created by {{ $section->user->first_name }} {{ $section->user->last_name }}</span>
                            @else
                                Update section details, SEO metadata, and content
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(isset($history) && count($history) > 0)
                            <form method="GET">
                                <select name="history" onchange="this.form.submit()"
                                    class="text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded shadow-xs px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="">History snapshots...</option>
                                    @foreach ($history as $his)
                                        <option value="{{ $his->id }}" @if (request()->history == $his->id) selected @endif>
                                            {{ date('d.m.Y H:i:s', strtotime($his->created_at)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @endif

                        <a href="{{ route('admincp.sections.index') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to sections
                        </a>
                    </div>
                </div>

                <form method="POST" class="w-full">
                    @csrf
                    <div id="editor"
                        data-image-browse-url="{{ env('APP_URL') . '/' . explode('-', app()->getlocale())[0] . '/admincp/images/' }}">
                    </div>

                    <div class="bg-white rounded border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
                        <div class="border-b border-slate-200 pb-3 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Main Information</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Core URL routing, naming, and hierarchy</p>
                        </div>

                        <div class="flex items-center justify-start my-3" id="lender-image-container">
                            @if ($section->image)
                                <div class="p-2 bg-slate-50 rounded border border-slate-200/70 inline-block h-28">
                                    <img class="object-contain max-h-full" src="{{ $section->image }}" alt="Logo" id="lender_image">
                                </div>
                            @else
                                <img class="object-contain max-w-full max-h-full" id="lender-image">
                            @endif
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                            <div>
                                <label for="section" class="block text-xs font-semibold text-slate-600 mb-1.5">Parent Section</label>
                                <select id="section" name="section"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="0">None (Top Level)</option>
                                    @foreach ($sections as $sec)
                                        <option value="{{ $sec->id }}"
                                            @if ($section->parent_section_id == $sec->id) selected @endif>{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="type" class="block text-xs font-semibold text-slate-600 mb-1.5">Section Type</label>
                                <select id="type" name="type"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="blog" @if ($section->type == 'blog') selected @endif>Blog</option>
                                    <option value="contact" @if ($section->type == 'contact') selected @endif>Contact Us</option>
                                    <option value="cookies" @if ($section->type == 'cookies') selected @endif>Cookies</option>
                                    <option value="home" @if ($section->type == 'home') selected @endif>Homepage</option>
                                    <option value="reviews" @if ($section->type == 'reviews') selected @endif>Reviews</option>
                                    <option value="standard" @if ($section->type == 'standard') selected @endif>Standard</option>
                                    <option value="unsubscribe" @if ($section->type == 'unsubscribe') selected @endif>Unsubscribe</option>
                                    <option value="faqs" @if ($section->type == 'faqs') selected @endif>FAQ</option>
                                </select>
                            </div>

                            <div>
                                <label for="routing_name" class="block text-xs font-semibold text-slate-600 mb-1.5">Routing Name (Slug) <span class="text-red-500">*</span></label>
                                <input id="routing_name" name="routing_name" type="text" value="{{ $section->route_name }}" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Section Name <span class="text-red-500">*</span></label>
                                <input id="name" name="name" type="text" value="{{ $section->name }}" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1.5">&lt;title&gt; Tag <span class="text-red-500">*</span></label>
                                <input id="title" name="title" type="text" value="{{ $section->title }}" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="link" class="block text-xs font-semibold text-slate-600 mb-1.5">Redirect Link</label>
                                <input id="link" name="link" type="text" value="{{ $section->redirect_link }}" placeholder="Optional redirect"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="fileInput" class="block text-xs font-semibold text-slate-600 mb-1.5">Image URL (required for blog)</label>
                                <input id="fileInput" name="image" type="url" value="{{ $section->image }}" onchange="displayImage(this);"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="image_alt_text" class="block text-xs font-semibold text-slate-600 mb-1.5">Image Alt Text</label>
                                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ $section->image_alt_text }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="googleDescription" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Meta Description</label>
                                <input id="googleDescription" name="googleDescription" type="text" value="{{ $section->description }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="googleKeywords" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Keywords</label>
                                <input id="googleKeywords" name="googleKeywords" type="text" value="{{ $section->keywords }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="anchor_element_title" class="block text-xs font-semibold text-slate-600 mb-1.5">Anchor Element Title</label>
                                <input id="anchor_element_title" name="anchor_element_title" type="text" value="{{ $section->anchor_element_title }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="h1" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Heading</label>
                                <input id="h1" name="h1" type="text" value="{{ $section->h1 }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="h1_description" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Description</label>
                                <input id="h1_description" name="h1_description" type="text" value="{{ $section->h1_description }}"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Head Script / HTML</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Section specific HTML or scripts in &lt;head&gt;</p>
                        </div>
                        <div class="mb-6">
                            <textarea rows="6" name="section_html" placeholder="<script>...</script> or meta tags"
                                class="w-full px-3 py-2 font-mono text-xs text-slate-800 bg-slate-50 border border-slate-300 rounded shadow-xs focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">{!! $section->section_html !!}</textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Section Source</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Citation or reference source description</p>
                        </div>
                        <div class="mb-6">
                            <textarea name="sectionSource" id="sectionSource">{!! $section->source !!}</textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Section Text</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Main formatted content on the page</p>
                        </div>
                        <div class="mb-6">
                            <textarea name="sectionText" id="sectionText">{!! $section->text !!}</textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-6">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800 mb-3">Visibility & Status</h2>
                            <div class="flex flex-col sm:flex-row gap-6">
                                <input type="hidden" name="display_in_the_header" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="display_in_the_header" type="checkbox" value="1"
                                        @if ($section->display_in_the_header && !request()->history) checked @endif
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Display in the header</span>
                                </label>

                                <input type="hidden" name="active" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="active" type="checkbox" value="1"
                                        @if ($section->active && !request()->history) checked @endif
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Active (published)</span>
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
            </div>
        </div>
    </div>
    <script>
        CKEDITOR.replace('sectionSource');
        CKEDITOR.replace('sectionText');

        function displayImage(input) {
            let lenderImage = document.getElementById("lender-image");
            let lenderImageContainer = document.getElementById("lender-image-container");
            if (lenderImage) {
                lenderImage.src = input.value;
                lenderImage.alt = "Logo";
            }
        }
    </script>
@endsection
