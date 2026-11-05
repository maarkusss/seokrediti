@if ($paginator->hasPages())
    <nav aria-label="Lapu navigācija" class="flex justify-center my-8">
        <div class="inline-flex items-center border border-ink divide-x divide-ink bg-paper-light text-xs font-semibold">
            @if (!$paginator->onFirstPage())
                <a class="px-3.5 py-2 text-ink hover:bg-ink hover:text-paper-light transition-colors"
                   href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   aria-label="Iepriekšējā lapa">
                    ← Iepriekšējā
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-ink-muted" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="px-3.5 py-2 bg-ink text-paper-light num">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3.5 py-2 text-ink hover:bg-ink hover:text-paper-light transition-colors num">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="px-3.5 py-2 text-ink hover:bg-ink hover:text-paper-light transition-colors"
                   href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   aria-label="Nākamā lapa">
                    Nākamā →
                </a>
            @endif
        </div>
    </nav>
@endif
