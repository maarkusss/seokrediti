@extends('layouts.app')

@section('title', 'FAQ - ' . env('APP_NAME'))

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Frequently Asked Questions (FAQ)</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Manage question and answer list</p>
                    </div>
                    @can('add faqs')
                        <a class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors"
                            href="{{ route('admincp.faqs.create') }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Add faq</span>
                        </a>
                    @endcan
                </div>

                <!-- Table Card with Form -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <form action="{{ route('admincp.faqs.updateOrder') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-left">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 w-24">Order</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Question</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Last updated</th>
                                        <th scope="col" class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-600">Created at</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-600 w-24">Active</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($faqs as $faq)
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-4 py-3 text-center">
                                                <input
                                                    class="w-16 px-2 py-1 text-center font-mono text-sm text-slate-800 bg-white border border-slate-300 rounded focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-xs inline-block"
                                                    name="faq[order][{{ $faq->id }}]" type="number"
                                                    value="{{ $faq->order }}">
                                            </td>
                                            <td class="px-5 py-3.5 text-sm font-medium">
                                                <a class="text-slate-800 hover:text-blue-600 no-underline hover:underline transition-colors line-clamp-2"
                                                    href="{{ route('admincp.faqs.edit', ['faq' => $faq->id]) }}">
                                                    {{ $faq->question }}
                                                </a>
                                            </td>
                                            <td class="px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $faq->updated_at ? $faq->updated_at->format('d.m.Y H:i') : '—' }}
                                            </td>
                                            <td class="px-5 py-3.5 text-sm text-slate-500 whitespace-nowrap">
                                                {{ $faq->created_at ? $faq->created_at->format('d.m.Y H:i') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <input type="hidden" name="faq[active][{{ $faq->id }}]" value="0">
                                                <input
                                                    class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500/20 cursor-pointer"
                                                    name="faq[active][{{ $faq->id }}]" value="1"
                                                    type="checkbox"
                                                    @if ($faq->active) checked @endif>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                                                No FAQ records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($faqs->count() > 0)
                            @can('edit faqs')
                                <div class="flex items-center justify-end px-5 py-3 bg-slate-50/50 border-t border-slate-100">
                                    <button
                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-xs transition-colors cursor-pointer"
                                        type="submit">
                                        Save order and status
                                    </button>
                                </div>
                            @endcan
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
