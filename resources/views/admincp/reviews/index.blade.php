@extends('layouts.app')

@if ($name = \App\Models\Admincp\Settings::where('name', 'name')->first())
    @section('title', 'Reviews | ' . $name->value)
@else
    @section('title', 'Reviews | ' . env('APP_NAME'))
@endif

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
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Reviews</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Customer feedback and lender reviews submitted by visitors</p>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="w-full bg-white rounded border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th scope="col" class="w-16 px-4 py-3.5 text-center">ID</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Lender</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Author</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Review Text</th>
                                    <th scope="col" class="px-5 py-3.5 text-left">Submitted Date</th>
                                    <th scope="col" class="w-16 px-4 py-3.5 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($reviews as $review)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-4 py-3 text-xs font-mono text-center text-slate-500">
                                            #{{ $review->id }}
                                        </td>
                                        <td class="px-5 py-3 font-medium text-slate-800 whitespace-nowrap">
                                            @if ($review->lender)
                                                <a href="{{ route('admincp.lenders.edit.index', ['lender_id' => $review->lender->id]) }}"
                                                    class="inline-flex items-center gap-2 hover:text-blue-600 transition-colors">
                                                    @if ($review->lender->image)
                                                        <img src="{{ $review->lender->image }}" alt="{{ $review->lender->name }}"
                                                            class="w-6 h-6 object-contain rounded bg-slate-50 border border-slate-200 p-0.5">
                                                    @endif
                                                    <span class="font-semibold">{{ $review->lender->name }}</span>
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Deleted lender (#{{ $review->lender_id }})</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-slate-700 whitespace-nowrap">
                                            <div class="font-medium text-slate-800">{{ $review->name }}</div>
                                            @if ($review->rating)
                                                <div class="flex items-center gap-0.5 text-amber-400 text-xs mt-0.5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            ★
                                                        @else
                                                            <span class="text-slate-300">★</span>
                                                        @endif
                                                    @endfor
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-700 max-w-xl">
                                            <p class="line-clamp-2">{{ $review->text }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $review->created_at ? date('d.m.Y H:i', strtotime($review->created_at)) : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center whitespace-nowrap">
                                            <a href="{{ route('admincp.reviews.delete', ['id' => $review->id]) }}"
                                                onclick="return confirm('Are you sure you want to delete this review?');"
                                                class="inline-flex p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Delete review">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500">
                                            No reviews found.
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
