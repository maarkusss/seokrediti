@extends('layouts.app')

@if ($name = \App\Models\Admincp\Settings::where('name', 'name')->first())
    @section('title', 'Redirect links - ' . $name->value)
@else
    @section('title', 'Redirect links - ' . env('APP_NAME'))
@endif

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Info box -->
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Header card -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Redirect Links</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Manage 301/302 URL redirects across the site</p>
                    </div>
                    <a href="{{ route('admincp.redirect-links.create') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create redirect link
                    </a>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 text-left">URL From</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">URL To</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($redirect_links as $redirect_link)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3">
                                            <a class="font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline break-all"
                                                href="{{ route('admincp.redirect-links.edit', ['redirect_link' => $redirect_link->id]) }}">
                                                {{ $redirect_link->url_from }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="font-mono text-xs text-slate-700 break-all">
                                                {{ $redirect_link->url_to }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No redirect links configured. Click "Create redirect link" to add one.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
