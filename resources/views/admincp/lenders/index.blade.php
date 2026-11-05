@extends('layouts.app')

@section('title', 'Lenders - ' . env('APP_NAME'))

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
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-3">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Lenders</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Lender list, positions, frequency and EPC settings</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <form method="GET" class="inline-block">
                            <select class="pl-3 pr-8 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs cursor-pointer min-w-[200px]"
                                    name="sorting"
                                    onchange="this.form.submit()">
                                <option value="">Select campaign / sorting</option>
                                @foreach ($sortings as $sorting)
                                    <option value="{{ $sorting->id }}"
                                            @if ($sorting->id == request()->sorting) selected @endif>{{ $sorting->campaign_name }}
                                        ({{ $sorting->clicks }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <a href="{{ route('admincp.lenders.campaigns.index') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded shadow-xs transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                            </svg>
                            <span>Lender campaigns</span>
                        </a>
                        <a href="{{ route('admincp.lenders.add.index') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add lender</span>
                        </a>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <form method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Lender</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Position</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Frequency</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Daily EPC</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Daily EPC before</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Guaranteed EPC</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Earnings</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">Clicks</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600">EPC</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 w-20">Active</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($lenders as $lender)
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-5 py-3 text-sm">
                                                <a href="{{ route('admincp.lenders.edit.index', ['lender_id' => $lender->id]) }}"
                                                    class="flex items-center gap-3 no-underline group">
                                                    <div class="w-16 h-10 p-1 bg-slate-50 border border-slate-200 rounded flex items-center justify-center shrink-0">
                                                        <img class="max-h-full max-w-full object-contain"
                                                             src="{{ $lender->image }}"
                                                             alt="{{ $lender->name }}">
                                                    </div>
                                                    <span class="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $lender->name }}</span>
                                                </a>
                                            </td>
                                            <td class="px-2 py-2.5 text-center">
                                                <input class="table-input font-mono"
                                                       name="position[{{ $lender->id }}]"
                                                       type="text"
                                                       value="{{ $lender->position }}">
                                            </td>
                                            <td class="px-2 py-2.5 text-center">
                                                <input class="table-input font-mono"
                                                       name="frequency[{{ $lender->id }}]"
                                                       type="number"
                                                       value="{{ $lender->frequency }}">
                                            </td>
                                            <td class="px-2 py-2.5 text-center">
                                                <input class="table-input font-mono"
                                                       name="daily_epc[{{ $lender->id }}]"
                                                       type="number"
                                                       value="{{ $lender->daily_epc }}">
                                            </td>
                                            <td class="px-2 py-2.5 text-center">
                                                <input class="table-input font-mono"
                                                       name="daily_epc_before[{{ $lender->id }}]"
                                                       type="number"
                                                       value="{{ $lender->daily_epc_before }}">
                                            </td>
                                            <td class="px-2 py-2.5 text-center">
                                                <input class="table-input font-mono"
                                                       name="guaranteed_epc[{{ $lender->id }}]"
                                                       type="text"
                                                       value="{{ $lender->guaranteed_epc }}">
                                            </td>
                                            <td class="px-3 py-3 text-center text-xs text-slate-600 font-mono">
                                                {{ $lender->earnings }}
                                            </td>
                                            <td class="px-3 py-3 text-center text-xs text-slate-600 font-mono">
                                                {{ $lender->clicks }}
                                            </td>
                                            <td class="px-3 py-3 text-center text-xs text-slate-600 font-mono">
                                                {{ $lender->epc }}
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <input type="hidden" name="active[{{ $lender->id }}]" value="0">
                                                <input class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer"
                                                       name="active[{{ $lender->id }}]"
                                                       type="checkbox"
                                                       value="1"
                                                       @if ($lender->active) checked @endif>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-5 py-8 text-center text-sm text-slate-500">
                                                No lenders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if (!request()->sorting && count($lenders) > 0)
                            <div class="flex items-center justify-end px-5 py-3 bg-slate-50/50 border-t border-slate-100">
                                <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                        type="submit"
                                        name="action"
                                        value="save">
                                    Save changes
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
