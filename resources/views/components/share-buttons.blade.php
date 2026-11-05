@php
    $shareUrl = url()->current();
    $shareTitle = $title ?? config('app.name');
@endphp

<div class="share-buttons-container" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #EEEEEE; clear: both;">
    {{-- Draugiem.lv Share --}}
    <div style="float: left; margin-right: 12px; margin-bottom: 10px;">
        <a href="https://www.draugiem.lv/say/ext/add.php?title={{ urlencode($shareTitle) }}&link={{ urlencode($shareUrl) }}" 
           target="_blank" 
           rel="nofollow noopener" 
           style="display: inline-flex; align-items: center; gap: 7px; background: #FF7700; color: #FFFFFF; font-weight: 600; font-size: 13px; line-height: 1; padding: 7px 14px; border-radius: 4px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.08); transition: opacity 0.15s ease;"
           onmouseover="this.style.opacity='0.9'" 
           onmouseout="this.style.opacity='1'">
            <svg style="width: 15px; height: 15px; fill: currentColor; flex-shrink: 0;" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12c0 4.41 2.87 8.14 6.84 9.47.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.61.07-.61 1 .07 1.53 1.03 1.53 1.03.89 1.52 2.34 1.08 2.91.83.09-.65.35-1.08.63-1.33-2.22-.25-4.56-1.11-4.56-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.64 0 0 .84-.27 2.75 1.02.8-.22 1.65-.33 2.5-.33.85 0 1.7.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.37.2 2.39.1 2.64.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10.02 10.02 0 0022 12c0-5.52-4.48-10-10-10z" style="display:none;"/>
                {{-- Draugiem authentic icon representation --}}
                <circle cx="12" cy="7" r="4.5"/>
                <path d="M4 19c0-3.87 3.58-7 8-7s8 3.13 8 7v1H4v-1z"/>
            </svg>
            <span>Ieteikt</span>
        </a>
    </div>

    {{-- Facebook Share --}}
    <div style="float: left; margin-right: 12px; margin-bottom: 10px;">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
           target="_blank" 
           rel="nofollow noopener" 
           style="display: inline-flex; align-items: center; gap: 7px; background: #1877F2; color: #FFFFFF; font-weight: 600; font-size: 13px; line-height: 1; padding: 7px 14px; border-radius: 4px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.08); transition: opacity 0.15s ease;"
           onmouseover="this.style.opacity='0.9'" 
           onmouseout="this.style.opacity='1'">
            <svg style="width: 15px; height: 15px; fill: currentColor; flex-shrink: 0;" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Dalīties</span>
        </a>
    </div>

    {{-- X (Twitter) Share --}}
    <div style="float: left; margin-right: 12px; margin-bottom: 10px;">
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}" 
           target="_blank" 
           rel="nofollow noopener" 
           style="display: inline-flex; align-items: center; gap: 7px; background: #000000; color: #FFFFFF; font-weight: 600; font-size: 13px; line-height: 1; padding: 7px 14px; border-radius: 4px; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.08); transition: opacity 0.15s ease;"
           onmouseover="this.style.opacity='0.9'" 
           onmouseout="this.style.opacity='1'">
            <svg style="width: 14px; height: 14px; fill: currentColor; flex-shrink: 0;" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>Post</span>
        </a>
    </div>

    <div style="clear: both;"></div>
</div>
