@extends('layouts.app')

@section('title', 'Add a section - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add section</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Create a new website section or page</p>
                    </div>
                    <a href="{{ route('admincp.sections.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to sections
                    </a>
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

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                            <div>
                                <label for="section" class="block text-xs font-semibold text-slate-600 mb-1.5">Parent Section</label>
                                <select id="section" name="section"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="0">None (Top Level)</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="type" class="block text-xs font-semibold text-slate-600 mb-1.5">Section Type</label>
                                <select id="type" name="type"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                                    <option value="blog">Blog</option>
                                    <option value="contact">Contact us</option>
                                    <option value="cookies">Cookies</option>
                                    <option value="home">Homepage</option>
                                    <option value="reviews">Reviews</option>
                                    <option value="standard" selected>Standard</option>
                                    <option value="unsubscribe">Unsubscribe</option>
                                    <option value="faqs">FAQ</option>
                                </select>
                            </div>

                            <div>
                                <label for="routing_name" class="block text-xs font-semibold text-slate-600 mb-1.5">Routing Name (Slug) <span class="text-red-500">*</span></label>
                                <input id="routing_name" name="routing_name" type="text" placeholder="e.g. par-mums" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-600 mb-1.5">Section Name <span class="text-red-500">*</span></label>
                                <input id="name" name="name" type="text" placeholder="Display name" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1.5">&lt;title&gt; Tag <span class="text-red-500">*</span></label>
                                <input id="title" name="title" type="text" placeholder="Page meta title" required
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="link" class="block text-xs font-semibold text-slate-600 mb-1.5">Redirect Link</label>
                                <input id="link" name="link" type="text" placeholder="Optional external or internal link"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="fileInput" class="block text-xs font-semibold text-slate-600 mb-1.5">Image URL (required for blog)</label>
                                <input id="fileInput" name="image" type="url" placeholder="https://..." onchange="displayImage(this);"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="image_alt_text" class="block text-xs font-semibold text-slate-600 mb-1.5">Image Alt Text</label>
                                <input id="image_alt_text" name="image_alt_text" type="text" placeholder="Alt description"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="googleDescription" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Meta Description</label>
                                <input id="googleDescription" name="googleDescription" type="text" placeholder="Search snippet description"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="googleKeywords" class="block text-xs font-semibold text-slate-600 mb-1.5">Google Keywords</label>
                                <input id="googleKeywords" name="googleKeywords" type="text" placeholder="Keywords"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="anchor_element_title" class="block text-xs font-semibold text-slate-600 mb-1.5">Anchor Element Title</label>
                                <input id="anchor_element_title" name="anchor_element_title" type="text" placeholder="Anchor title attribute"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="h1" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Heading</label>
                                <input id="h1" name="h1" type="text" placeholder="H1 heading"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="h1_description" class="block text-xs font-semibold text-slate-600 mb-1.5">H1 Description</label>
                                <input id="h1_description" name="h1_description" type="text" placeholder="H1 sub-description"
                                    class="w-full px-3 py-2 text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Head Script / HTML</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Section specific HTML or scripts in &lt;head&gt;</p>
                        </div>
                        <div class="mb-6">
                            <textarea rows="6" name="section_html" placeholder="<script>...</script> or meta tags"
                                class="w-full px-3 py-2 font-mono text-xs text-slate-800 bg-slate-50 border border-slate-300 rounded shadow-xs focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors"></textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Section Source</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Citation or reference source description</p>
                        </div>
                        <div class="mb-6">
                            <textarea name="sectionSource" id="sectionSource"></textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">Section Text</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Main formatted content on the page</p>
                        </div>
                        <div class="mb-6">
                            <textarea name="sectionText" id="sectionText"></textarea>
                        </div>

                        <div class="border-t border-slate-200 pt-5 mt-6 mb-6">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800 mb-3">Visibility & Status</h2>
                            <div class="flex flex-col sm:flex-row gap-6">
                                <input type="hidden" name="display_in_the_header" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="display_in_the_header" type="checkbox" value="1"
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Display in the header</span>
                                </label>

                                <input type="hidden" name="active" value="0">
                                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                                    <input name="active" type="checkbox" value="1" checked
                                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                                    <span class="text-sm font-medium text-slate-700">Active (published)</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-5 border-t border-slate-200">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add section
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
    </script>
@endsection
