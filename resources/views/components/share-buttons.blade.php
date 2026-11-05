@props(['title' => null])

@php
    $shareUrl = url()->current();
    $shareTitle = clean_legacy_brand($title ?: brand_name());
@endphp

<div class="flex flex-wrap items-center gap-4 pt-6 mt-8 border-t border-rule text-xs">
    <span class="uppercase tracking-wider font-semibold text-ink-muted">Dalīties rakstā:</span>

    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
       target="_blank"
       rel="nofollow noopener"
       class="link">
        Facebook
    </a>

    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}"
       target="_blank"
       rel="nofollow noopener"
       class="link">
        X (Twitter)
    </a>

    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}"
       target="_blank"
       rel="nofollow noopener"
       class="link">
        LinkedIn
    </a>
</div>
