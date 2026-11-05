@extends('layouts.app')

@section('title', 'Add a ban - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Add a new ban</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Add a new IP address to the block list</p>
                    </div>
                    <a href="{{ route('admincp.bans.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded transition-colors no-underline">
                        &larr; Back
                    </a>
                </div>

                <!-- Form Card -->
                <div class="w-full p-6 bg-white rounded border border-slate-200/80 shadow-xs">
                    <form action="{{ route('admincp.bans.store') }}" method="POST">
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
                                    name="ip" type="text" placeholder="192.168.1.1" required autofocus>
                            </div>
                            <div>
                                <label for="reason" class="block text-sm font-medium text-slate-700 mb-1.5">Reason</label>
                                <input
                                    id="reason"
                                    class="w-full px-3.5 py-2.5 text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs transition-all"
                                    name="reason" type="text" placeholder="Spam, attacks or other reasons">
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <a href="{{ route('admincp.bans.index') }}"
                                class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors no-underline">
                                Cancel
                            </a>
                            <button
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                type="submit">
                                Add ban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
