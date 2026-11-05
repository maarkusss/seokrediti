@extends('layouts.app')

@section('title', 'Edit a ban - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Main content -->
        <!-- Info box -->
        @include('admincp.components.infobox')
        <!-- End info box -->
        <div class="flex flex-row flex-auto h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')
            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Page Header Card -->
                <div class="flex items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Edit ban &mdash; <span class="font-mono text-blue-600">{{ $ban->ip }}</span>
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">Edit blocked IP record</p>
                    </div>
                    <a href="{{ route('admincp.bans.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form action="{{ route('admincp.bans.update', ['ban' => $ban->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <h2 class="text-base font-semibold text-slate-800 pb-3 mb-5 border-b border-slate-100">
                            Main information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label for="ip" class="block text-sm font-medium text-slate-700 mb-1.5">IP address *</label>
                                <input
                                    id="ip"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all font-mono"
                                    name="ip" type="text" value="{{ $ban->ip }}" placeholder="Banned IP" required>
                            </div>
                            <div>
                                <label for="reason" class="block text-sm font-medium text-slate-700 mb-1.5">Reason</label>
                                <input
                                    id="reason"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="reason" type="text" value="{{ $ban->reason }}" placeholder="Reason for banning">
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                @can('delete bans')
                                    <button type="button"
                                        onclick="if(confirm('Are you sure you want to delete this ban?')) document.getElementById('delete-ban-form').submit();"
                                        class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded transition-colors cursor-pointer">
                                        Delete
                                    </button>
                                @endcan
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admincp.bans.index') }}"
                                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                    Cancel
                                </a>
                                <button
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                    type="submit">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>

                    @can('delete bans')
                        <form id="delete-ban-form" action="{{ route('admincp.bans.destroy', ['ban' => $ban->id]) }}" method="POST" style="display: none;">
                            @method('DELETE')
                            @csrf
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
