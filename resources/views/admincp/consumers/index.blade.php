@extends('layouts.app')

@section('title', 'Consumers - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Consumers</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Loan applicants, subscribers, and UTM tracking data</p>
                    </div>

                    <form method="GET" class="w-full sm:w-auto">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input class="admin-search-input w-full sm:w-64"
                                name="search" type="text" value="{{ request()->search }}" placeholder="Search by name, email, IP...">
                        </div>
                    </form>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden mb-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Name, Surname</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Birth Date</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">E-mail</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Phone</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Amount</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Term</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Type</th>
                                    <th scope="col" class="px-3 py-3.5 text-center whitespace-nowrap">Confirmed</th>
                                    <th scope="col" class="px-3 py-3.5 text-center whitespace-nowrap">Subscribed</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">IP</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Source</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">UTM Campaign</th>
                                    <th scope="col" class="px-4 py-3.5 text-left whitespace-nowrap">Date</th>
                                    <th scope="col" class="w-16 px-4 py-3.5 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($consumers as $consumer)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 font-medium text-slate-800 whitespace-nowrap">
                                            {{ $consumer->first_name }} {{ $consumer->last_name }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $consumer->birth ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-blue-600 whitespace-nowrap">
                                            {{ $consumer->email }}
                                        </td>
                                        <td class="px-4 py-3 text-xs font-mono text-slate-600 whitespace-nowrap">
                                            {{ $consumer->phone ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs font-semibold text-emerald-700 whitespace-nowrap">
                                            €{{ $consumer->amount }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            {{ $consumer->term }} days
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-0.5 text-2xs font-semibold bg-slate-100 rounded">
                                                {{ $consumer->type }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            @if ($consumer->confirmed)
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-600">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-400">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-center whitespace-nowrap">
                                            @if ($consumer->subscribed)
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-600">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-400">
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-500 whitespace-nowrap">
                                            {{ $consumer->ip }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $consumer->utm_source ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $consumer->utm_campaign ?: '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $consumer->created_at ? date('d.m.Y H:i', strtotime($consumer->created_at)) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <form method="POST"
                                                action="{{ route('admincp.consumers.delete', ['consumer_id' => $consumer->id]) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1 text-slate-400 hover:text-red-600 transition-colors"
                                                    title="Delete consumer record"
                                                    onclick="return confirm('Are you sure you want to delete this consumer record?');">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No consumers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $consumers->links('admincp.components.pagination') }}
            </div>
        </div>
    </div>
@endsection
