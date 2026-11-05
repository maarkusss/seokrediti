@if (session('success') || session('error') || session('info') || $errors->any())
    @php
        $borderClass = (session('error') || $errors->any()) ? 'border-claret bg-claret-tint/50' : 'border-ink bg-paper';
    @endphp
    <div class="border-l-4 p-4 text-sm font-medium text-ink {{ $borderClass }}" role="alert">
        @if (session('success'))
            <span>{{ session('success') }}</span>
        @elseif (session('error'))
            <span>{{ session('error') }}</span>
        @elseif (session('info'))
            <span>{{ session('info') }}</span>
        @else
            <span>{{ $errors->first() }}</span>
        @endif
    </div>
@endif
