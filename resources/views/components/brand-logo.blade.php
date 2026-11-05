@props([
    'variant' => 'light', // 'light' (ink on paper) | 'dark' (paper on ink)
    'size' => 'md',       // 'sm' | 'md' | 'lg'
    'showTagline' => false,
    'link' => false,
])

@php
    $name = brand_name();
    $logoUrl = brand_logo_url();
    $tagline = brand_tagline();
    $showNameWithLogo = (bool) config('brand.show_name_with_logo', false);

    $imgHeights = [
        'sm' => 'h-7 max-w-[160px]',
        'md' => 'h-10 max-w-[220px]',
        'lg' => 'h-14 max-w-[300px]',
    ];
    $textSizes = [
        'sm' => 'text-xl',
        'md' => 'text-2xl sm:text-[2rem]',
        'lg' => 'text-4xl sm:text-[3.35rem]',
    ];

    $imgClass = $imgHeights[$size] ?? $imgHeights['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
    $textColor = $variant === 'dark' ? 'text-paper-light' : 'text-ink';
    $taglineColor = $variant === 'dark' ? 'text-paper-dark/70' : 'text-ink-muted';

    // Domain suffix such as ".lv" is set in italic claret for a clean financial newspaper wordmark
    $mainPart = $name;
    $suffixPart = '';
    if (preg_match('/^(.+?)(\.[a-z]{2,4})$/iu', $name, $m)) {
        $mainPart = $m[1];
        $suffixPart = $m[2];
    }
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex flex-col select-none leading-none']) }}>
    @if ($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $name }}" class="{{ $imgClass }} w-auto object-contain" loading="eager" decoding="async">
        @if ($showNameWithLogo)
            <span class="font-serif font-bold tracking-[-0.03em] mt-1 {{ $textClass }} {{ $textColor }}">{{ $name }}</span>
        @endif
    @else
        <span class="font-serif font-bold tracking-[-0.03em] {{ $textClass }} {{ $textColor }}">{{ $mainPart }}@if ($suffixPart)<span class="italic font-semibold text-claret">{{ $suffixPart }}</span>@endif</span>
    @endif

    @if ($showTagline && $tagline)
        <span class="font-sans text-[11px] tracking-[0.14em] uppercase mt-2.5 {{ $taglineColor }}">{{ $tagline }}</span>
    @endif
</span>
