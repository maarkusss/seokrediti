@extends('layouts.app')

@section('title', 'Edit sitemap ignored URL - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        @include('admincp.components.header')
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-auto h-full">
            @include('admincp.components.sidebar')

            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Edit sitemap ignored URL
                        </h1>
                    </div>
                    <a href="{{ route('admincp.sitemap-ignored-urls.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form method="POST" action="{{ route('admincp.sitemap-ignored-urls.edit.post', ['id' => $url->id]) }}">
                        @csrf
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Main information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="url" class="block text-sm font-medium text-slate-700 mb-1.5">URL *</label>
                                <input
                                    id="url"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="url" type="text" placeholder="https://www.atrie.lv/krediti"
                                    value="{{ old('url', $url->url) }}" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <input type="hidden" name="active" value="0">
                            <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer shrink-0"
                                    name="active" type="checkbox" value="1"
                                    @if ($url->active) checked @endif>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                @can('edit sections')
                                    <button type="button"
                                        onclick="if(confirm('Are you sure you want to delete this URL?')) document.getElementById('delete-url-form').submit();"
                                        class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded transition-colors cursor-pointer">
                                        Delete
                                    </button>
                                @endcan
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admincp.sitemap-ignored-urls.index') }}"
                                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                    Cancel
                                </a>
                                <button
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                    type="submit">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>

                    @can('edit sections')
                        <form id="delete-url-form" method="POST"
                            action="{{ route('admincp.sitemap-ignored-urls.delete', ['id' => $url->id]) }}" style="display: none;">
                            @csrf
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
