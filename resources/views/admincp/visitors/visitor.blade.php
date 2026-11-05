@extends('layouts.app')

@section('title', 'Visitor #' . $visitor->id . ' - ' . env('APP_NAME'))

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
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-bold tracking-tight text-slate-800">Visitor Details</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-blue-100 text-blue-800">
                                #{{ $visitor->id }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Click log history and campaign attribution for this session</p>
                    </div>

                    <a href="{{ route('admincp.visitors.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded shadow-xs hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to visitors
                    </a>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden mb-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Lender</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Referer</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Source</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Medium</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Campaign</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Content</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">gclid</th>
                                    <th scope="col" class="px-4 py-3.5 text-center whitespace-nowrap">Country / IP</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($clicks as $click)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 font-semibold text-slate-800 whitespace-nowrap">
                                            {{ $click->lender ? $click->lender->name : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs max-w-xs truncate">
                                            @if ($click->referer)
                                                <a href="{{ $click->referer }}" target="_blank" class="text-blue-600 hover:underline">
                                                    {{ $click->referer }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">Direct / None</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $click->utm_source ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $click->utm_medium ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $click->utm_campaign ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $click->utm_content ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs font-mono text-slate-600 whitespace-nowrap">
                                            {{ $click->gclid ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @if ($click->country && $click->country !== 'ZZ')
                                                <div class="inline-flex items-center gap-1.5 cursor-pointer" onclick="prompt('IP','{{ $click->ip }}');return false;">
                                                    <img class="rounded-xs shadow-2xs" width="18" height="13"
                                                        src="https://lipis.github.io/flag-icon-css/flags/4x3/{{ strtolower($click->country) }}.svg" alt="{{ $click->country }}">
                                                    <span class="text-xs font-mono text-slate-600">{{ $click->country }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs font-mono text-slate-400 cursor-pointer" onclick="prompt('IP','{{ $click->ip }}');return false;">
                                                    {{ $click->ip }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $click->created_at ? date('d.m.Y H:i:s', strtotime($click->created_at)) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No clicks recorded for this visitor.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $clicks->links('admincp.components.pagination') }}
            </div>
        </div>
    </div>
@endsection
