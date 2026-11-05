@extends('layouts.app')

@if ($name = \App\Models\Admincp\Settings::where('name', 'name')->first())
    @section('title', 'Edit connected URL - ' . $name->value)
@else
    @section('title', 'Edit connected URL - ' . env('APP_NAME'))
@endif

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Edit connected URL</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Update mapping between URLs</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admincp.connected-urls.index') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to connected URLs
                        </a>
                    </div>
                </div>

                <form action="{{ route('admincp.connected-urls.update', ['connected_url' => $connectedUrl->id]) }}" method="POST"
                    class="w-full" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="bg-white rounded border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
                        <div class="border-b border-slate-200 pb-3 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">URL Mapping</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Specify both source and destination URLs</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2 mb-6">
                            <div>
                                <label for="urlFrom" class="block text-xs font-semibold text-slate-600 mb-1.5">URL From <span class="text-red-500">*</span></label>
                                <input id="urlFrom" name="urlFrom" type="url" value="{{ $connectedUrl->url_from }}" required
                                    class="w-full px-3 py-2 font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="urlTo" class="block text-xs font-semibold text-slate-600 mb-1.5">URL To <span class="text-red-500">*</span></label>
                                <input id="urlTo" name="urlTo" type="url" value="{{ $connectedUrl->url_to }}" required
                                    class="w-full px-3 py-2 font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-5 border-t border-slate-200">
                            @can('delete connected urls')
                                <button type="submit" name="action" value="delete"
                                    onclick="return confirm('Are you sure you want to delete this connected URL?');"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded shadow-xs hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            @else
                                <div></div>
                            @endcan

                            <button type="submit" name="action" value="save"
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
@endsection
