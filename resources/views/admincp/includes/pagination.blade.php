@if ($paginator->hasPages())
    <nav class="flex justify-end mt-4">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <a class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
                   aria-disabled="true"
                   aria-label="@lang('pagination.previous')"
                   type="button">
                    Previous
                </a>
            @else
                <a class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
                   href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   aria-label="@lang('pagination.previous')"
                   type="button">
                    Previous
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
                   href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   aria-label="@lang('pagination.next')"
                   type="button">
                    Next
                </a>
            @else
                <a class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded hover:text-gray-500 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
                   aria-disabled="true"
                   aria-label="@lang('pagination.next')"
                   type="button"
                   type="button">
                    Next
                </a>
            @endif
        </div>
        <div class="hidden sm:inline-flex">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <a class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-l-md hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500"
                   aria-disabled="true"
                   aria-label="@lang('pagination.previous')"
                   type="button">
                    <svg class="w-5 h-5"
                         aria-hidden="true"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                              clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <a class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-l-md hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500"
                   href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   aria-label="@lang('pagination.previous')"
                   type="button">
                    <svg class="w-5 h-5"
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
                            <a class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border border-gray-300 text-primary-normal hover:text-primary-dark focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
                               aria-current="page"
                               type="button"
                               href="#">
                                {{ $page }}
                            </a>
                        @else
                            <a class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 hover:text-gray-500 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700"
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
                <a class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-r-md hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500"
                   href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   aria-label="@lang('pagination.next')"
                   type="button">
                    <svg class="w-5 h-5"
                         fill="currentColor"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <a class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-r-md hover:text-gray-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500"
                   aria-disabled="true"
                   aria-label="@lang('pagination.next')"
                   type="button">
                    <svg aria-hidden="true"
                         class="w-5 h-5"
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
