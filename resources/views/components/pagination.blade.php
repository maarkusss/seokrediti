@if ($paginator->hasPages())
    <nav class="flex justify-center my-5">
        <div class="inline-flex">
            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <a class="-mr-px p-1.5 flex justify-center items-center bg-white text-[#337ab7] rounded-l text-sm leading-4 hover:bg-gray-100 border-gray-200 border"
                   href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   type="button">
                    <svg class="w-4 h-4"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                              clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300"
                          aria-disabled="true">
                        {{ $element }}
                    </span>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a class="px-3 py-1.5 bg-[#337ab7] border border-[#337ab7] @if (!$paginator->hasMorePages()) rounded-r @endif @if ($paginator->onFirstPage()) rounded-l @endif flex justify-center items-center text-sm leading-4 text-white"
                               aria-current="page"
                               type="button"
                               href="#">
                                {{ $page }}
                            </a>
                        @else
                            <a class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 flex justify-center items-center text-sm leading-4 text-[#337ab7]"
                               href="{{ $url }}"
                               type="button">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="-ml-px p-1.5 flex justify-center items-center bg-white text-[#337ab7] rounded-r text-sm leading-4 hover:bg-gray-100 border-gray-200 border"
                   href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   type="button">
                    <svg class="w-4 h-4"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
    </nav>
@endif
