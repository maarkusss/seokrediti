@php
    $items = generate_breadcrumbs();
@endphp

@if (count($items) > 1)
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 font-sans text-xs text-ink-muted">
            @foreach ($items as $idx => $crumb)
                <li class="inline-flex items-center gap-2">
                    @if ($idx > 0)
                        <span aria-hidden="true" class="text-ink-faint">/</span>
                    @endif
                    @if ($loop->last)
                        <span class="text-ink truncate max-w-[240px] sm:max-w-md" aria-current="page">{{ $crumb['name'] }}</span>
                    @else
                        <a href="{{ $crumb['item'] }}" class="hover:text-claret hover:underline underline-offset-4">{{ $crumb['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
