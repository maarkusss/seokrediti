@extends('layouts.app')

@section('title', 'Bans - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <!-- Info box -->
        @include('admincp.components.infobox')
        <!-- End info box -->
        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')
            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Bans</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Manage blocked IP addresses</p>
                    </div>
                    @can('add bans')
                        <a class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors"
                            href="{{ route('admincp.bans.create') }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add ban</span>
                        </a>
                    @endcan
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">IP</th>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Reason</th>
                                    <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Date</th>
                                    @can('delete bans')
                                        <th scope="col" class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($bans as $ban)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3.5 text-sm font-medium">
                                            <a href="{{ route('admincp.bans.edit', ['ban' => $ban->id]) }}"
                                                class="text-blue-600 hover:text-blue-700 font-mono no-underline hover:underline">
                                                {{ $ban->ip }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-3.5 text-sm text-slate-600">
                                            {{ $ban->reason ?: '—' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-sm text-slate-500">
                                            @if ($ban->created_at)
                                                {{ $ban->created_at->format('d.m.Y H:i') }}
                                            @else
                                                <span class="italic text-slate-400">Added manually via DB</span>
                                            @endif
                                        </td>
                                        @can('delete bans')
                                            <td class="px-5 py-3.5 text-sm text-right">
                                                <form action="{{ route('admincp.bans.destroy', ['ban' => $ban->id]) }}"
                                                    method="POST" class="inline-block">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit"
                                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-colors cursor-pointer"
                                                        title="Delete ban"
                                                        onclick="return confirm('Are you sure you want to delete this ban?');">
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->can('delete bans') ? 4 : 3 }}" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No bans found.
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
