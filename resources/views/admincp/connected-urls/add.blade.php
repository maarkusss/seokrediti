@extends('layouts.app')

@if ($name = \App\Models\Admincp\Settings::where('name', 'name')->first())
    @section('title', 'Add connected URL - ' . $name->value)
@else
    @section('title', 'Add connected URL - ' . env('APP_NAME'))
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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add connected URL</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Establish a bidirectional URL relationship</p>
                    </div>
                    <a href="{{ route('admincp.connected-urls.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to connected URLs
                    </a>
                </div>

                <form action="{{ route('admincp.connected-urls.store') }}" method="POST"
                    class="w-full" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white rounded border border-slate-200/80 shadow-xs p-5 sm:p-6 mb-6">
                        <div class="border-b border-slate-200 pb-3 mb-5">
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-slate-800">URL Mapping</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Specify both source and destination URLs</p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2 mb-6">
                            <div>
                                <label for="urlFrom" class="block text-xs font-semibold text-slate-600 mb-1.5">URL From <span class="text-red-500">*</span></label>
                                <input id="urlFrom" name="urlFrom" type="url" placeholder="https://example.com/lv/page" required
                                    class="w-full px-3 py-2 font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>

                            <div>
                                <label for="urlTo" class="block text-xs font-semibold text-slate-600 mb-1.5">URL To <span class="text-red-500">*</span></label>
                                <input id="urlTo" name="urlTo" type="url" placeholder="https://example.com/ru/page" required
                                    class="w-full px-3 py-2 font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded shadow-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-5 border-t border-slate-200">
                            <button type="submit" name="action" value="save"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Save connected URL
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
