@extends('layouts.app')

@section('title', 'Sitemap ignored URLs - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        @include('admincp.components.header')
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-1 h-full">
            @include('admincp.components.sidebar')

            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Sitemap ignored URLs</h1>
                        <p class="text-xs text-slate-500 mt-0.5">URLs that are excluded from sitemap.xml</p>
                    </div>

                    @can('add sections')
                        <a href="{{ route('admincp.sitemap-ignored-urls.add.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add URL</span>
                        </a>
                    @endcan
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <form method="POST" action="{{ route('admincp.sitemap-ignored-urls.index.save') }}">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">URL</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Last edited</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Created</th>
                                        <th scope="col" class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 w-24">Active</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($urls as $url)
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-5 py-3.5 text-sm font-medium">
                                                <a class="text-blue-600 hover:text-blue-700 font-mono no-underline hover:underline"
                                                    href="{{ route('admincp.sitemap-ignored-urls.edit.index', ['id' => $url->id]) }}">
                                                    {{ $url->url }}
                                                </a>
                                            </td>

                                            <td class="px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $url->updated_at ? date('d.m.Y H:i', strtotime($url->updated_at)) : '—' }}
                                            </td>

                                            <td class="px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $url->created_at ? date('d.m.Y H:i', strtotime($url->created_at)) : '—' }}
                                            </td>

                                            <td class="px-5 py-3 text-center">
                                                <input type="hidden" name="ignored_url[active][{{ $url->id }}]" value="0">
                                                <input
                                                    class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer"
                                                    name="ignored_url[active][{{ $url->id }}]" value="1"
                                                    type="checkbox" @if ($url->active) checked @endif>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">
                                                No sitemap ignored URLs to display!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(count($urls) > 0)
                            @can('edit sections')
                                <div class="flex items-center justify-end px-5 py-3 bg-slate-50/50 border-t border-slate-100">
                                    <button
                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                        type="submit">
                                        Save
                                    </button>
                                </div>
                            @endcan
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
