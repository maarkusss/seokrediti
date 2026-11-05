@extends('layouts.app')

@section('title', 'Visitors - ' . brand_name())

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
                <!-- Header card with search -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Visitors Log</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Real-time visitor sessions, devices, referrers, and destination links</p>
                    </div>

                    <form method="GET" class="w-full sm:w-auto">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input class="admin-search-input w-full sm:w-64"
                                name="search" type="text" value="{{ request()->search }}" placeholder="Search by IP, visitor ID...">
                        </div>
                    </form>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden mb-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 text-left">Visitor ID</th>
                                    <th scope="col" class="px-4 py-3.5 text-center">Clicks</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Time</th>
                                    <th scope="col" class="px-4 py-3.5 text-center">Device & Browser</th>
                                    <th scope="col" class="px-4 py-3.5 text-center">Country</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Came From</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Went To</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($visitors as $visitor)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3 font-mono text-xs whitespace-nowrap">
                                            <a class="font-semibold text-blue-600 hover:text-blue-800 hover:underline"
                                                href="{{ route('admincp.visitors.visitor', ['visitor_id' => $visitor->id]) }}"
                                                target="_blank">
                                                #{{ $visitor->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $visitor->clicks > 0 ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                                                {{ $visitor->clicks }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ date('d.m.Y H:i:s', strtotime($visitor->created_at)) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1.5 text-slate-600">
                                                @if ($agent->isMobile($visitor->user_agent))
                                                    <span title="Mobile ({{ $visitor->user_agent }})">
                                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @elseif($agent->isTablet($visitor->user_agent))
                                                    <span title="Tablet ({{ $visitor->user_agent }})">
                                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @else
                                                    <span title="Desktop ({{ $visitor->user_agent }})">
                                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif

                                                @if ($agent->is('Windows', $visitor->user_agent))
                                                    <span class="text-2xs font-semibold px-1 py-0.5 rounded bg-slate-100 text-slate-700">Win</span>
                                                @elseif($agent->isiOS($visitor->user_agent))
                                                    <span class="text-2xs font-semibold px-1 py-0.5 rounded bg-slate-100 text-slate-700">iOS</span>
                                                @elseif($agent->isAndroidOS($visitor->user_agent))
                                                    <span class="text-2xs font-semibold px-1 py-0.5 rounded bg-slate-100 text-slate-700">And</span>
                                                @elseif($agent->is('OS X', $visitor->user_agent))
                                                    <span class="text-2xs font-semibold px-1 py-0.5 rounded bg-slate-100 text-slate-700">Mac</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            @if ($visitor->country && $visitor->country !== 'ZZ')
                                                <div class="inline-flex items-center gap-1.5 cursor-pointer" onclick="prompt('IP Address','{{ $visitor->ip }}');return false;" title="IP: {{ $visitor->ip }}">
                                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-mono font-semibold text-slate-700">{{ strtoupper($visitor->country) }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs font-mono text-slate-400 cursor-pointer" onclick="prompt('IP Address','{{ $visitor->ip }}');return false;" title="IP: {{ $visitor->ip }}">
                                                    {{ $visitor->ip }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-xs max-w-xs truncate">
                                            @if ($visitor->from)
                                                <a class="text-blue-600 hover:underline hover:text-blue-800" href="{{ $visitor->from }}" target="_blank">
                                                    {{ $visitor->from }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">Direct / None</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-xs max-w-xs truncate">
                                            @if ($visitor->to)
                                                <a class="text-blue-600 hover:underline hover:text-blue-800 font-mono" href="{{ $visitor->to }}" target="_blank">
                                                    {{ $visitor->to }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No visitors recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $visitors->links('admincp.components.pagination') }}
            </div>
        </div>
    </div>
@endsection
