@extends('layouts.app')

@section('title', 'Admin logs - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Admin Logs</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Audit trail of actions and modifications performed by administrators</p>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden mb-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 text-left">Administrator</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Action / Log</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($adminlogs as $adminlog)
                                    @php
                                        $adminUser = null;
                                        if ($adminlog->user_id) {
                                            foreach ($users as $user) {
                                                if ($adminlog->user_id == $user->id) {
                                                    $adminUser = $user;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @if ($adminUser)
                                                <div class="flex items-center gap-2.5">
                                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                                                        {{ substr($adminUser->first_name, 0, 1) }}{{ substr($adminUser->last_name, 0, 1) }}
                                                    </span>
                                                    <span class="font-medium text-slate-800">
                                                        {{ $adminUser->first_name }} {{ $adminUser->last_name }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400">System / Unknown</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3">
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono text-slate-700 bg-slate-100 rounded border border-slate-200/60 break-all">
                                                {{ $adminlog->log }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $adminlog->created_at ? date('d.m.Y H:i:s', strtotime($adminlog->created_at)) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No logs recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $adminlogs->links('admincp.components.pagination') }}
            </div>
        </div>
    </div>
@endsection
