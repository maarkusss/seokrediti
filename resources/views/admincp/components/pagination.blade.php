@if ($paginator->hasPages())
    <nav class="flex items-center justify-between w-full mt-4 text-xs font-medium text-slate-700" aria-label="Pagination">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-3.5 py-2 text-slate-400 bg-white border border-slate-200 rounded cursor-not-allowed">
                    Previous
                </span>
            @else
                <a class="px-3.5 py-2 text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 shadow-2xs"
                   href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="px-3.5 py-2 ml-3 text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 shadow-2xs"
                   href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Next
                </a>
            @else
                <span class="px-3.5 py-2 ml-3 text-slate-400 bg-white border border-slate-200 rounded cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:items-center sm:justify-end sm:flex-1 gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-white border border-slate-200 rounded cursor-not-allowed" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a class="inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-300 rounded hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition-colors"
                   href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-8 h-8 text-slate-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-blue-600 rounded shadow-xs" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a class="inline-flex items-center justify-center w-8 h-8 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition-colors"
                               href="{{ $url }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="inline-flex items-center justify-center w-8 h-8 text-slate-600 bg-white border border-slate-300 rounded hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition-colors"
                   href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 bg-white border border-slate-200 rounded cursor-not-allowed" aria-disabled="true">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
