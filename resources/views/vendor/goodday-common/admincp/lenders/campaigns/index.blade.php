@extends('layouts.app')

@section('title', ($page_title ?? 'Lender Campaigns') . ' - ' . env('APP_NAME'))

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
                <!-- Page Header Card -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admincp.lenders.index') }}" class="text-xs font-medium text-slate-500 hover:text-blue-600 transition-colors">
                                &larr; Back to Lenders
                            </a>
                        </div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800 mt-1">{{ $page_title ?? 'Lender Campaigns' }}</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Campaign IDs regularly downloaded via GoodAff API</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="hidden md:flex items-center gap-3 px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded text-slate-600">
                            <div>
                                <span class="text-slate-400">Url:</span>
                                <span class="font-medium text-slate-700">{{ !empty(config('services.goodaff.url')) ? config('services.goodaff.url') : '—' }}</span>
                            </div>
                            <span class="text-slate-300">|</span>
                            <div>
                                <span class="text-slate-400">Country:</span>
                                <span class="font-medium text-slate-700">
                                    @if (!empty(config('app.country_code')))
                                        {{ config('app.country_code') }}
                                    @else
                                        {{ !empty(config('services.goodaff.country')) ? config('services.goodaff.country') : '—' }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('admincp.lenders.campaigns.truncate') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all lender campaigns?');">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-600 bg-white hover:bg-red-50 border border-red-200 rounded shadow-xs transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                <span>Truncate</span>
                            </button>
                        </form>

                        <form action="{{ route('admincp.lenders.campaigns.download') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>Download campaigns</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="w-20 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">ID</th>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Campaign Name</th>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Campaign Domain</th>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Lender</th>
                                    <th scope="col" class="w-24 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Active</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($campaigns as $campaign)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3 text-xs font-mono text-slate-500">
                                            #{{ $campaign->id }}
                                        </td>
                                        <td class="px-5 py-3 text-sm font-medium text-slate-800">
                                            {{ $campaign->name }}
                                        </td>
                                        <td class="px-5 py-3 text-sm text-slate-600 font-mono text-xs">
                                            {{ $campaign->campaign_domain ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-sm">
                                            @if ($campaign->lender)
                                                <a href="{{ route('admincp.lenders.edit.index', $campaign->lender->id) }}"
                                                   class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                                    {{ $campaign->lender->name }}
                                                </a>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-500">
                                                    Not linked
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            @if ($campaign->active)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-50 text-emerald-600">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                </svg>
                                                <span>No Lender Campaigns found.</span>
                                                <p class="text-xs text-slate-400">Click "Download campaigns" to fetch them from GoodAff API.</p>
                                            </div>
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
