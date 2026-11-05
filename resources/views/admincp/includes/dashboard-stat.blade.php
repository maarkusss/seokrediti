<div
    class="flex items-center justify-between flex-1 p-5 leading-none {{ $bg_color ?? 'bg-white' }} rounded {{ isset($bg_color) && !str_contains($bg_color, 'bg-white') ? 'border border-transparent' : 'border border-slate-200/80' }} shadow-xs hover:shadow-sm transition-all">
    <div class="flex flex-col">
        @if (!empty($link))
            <a href="{{ $link }}" class="flex flex-col hover:opacity-80">
        @endif
        <span class="mb-2 text-2xl font-bold tracking-tight lg:text-3xl {{ $value_color ?? 'text-slate-800' }}">{{ $value }}</span>
        <span class="text-xs font-medium tracking-wider uppercase {{ $title_color ?? 'text-slate-500' }}">
            @if (!empty($link))
                <a href="{{ $link }}" class="{{ $title_color ?? '' }}">
            @endif
            {{ $title }}
            @if (!empty($link))
                </a>
            @endif
        </span>
        @if (!empty($link))
            </a>
        @endif
    </div>
    <div class="h-full">
        @if (!empty($detail))
            <span class="text-xl font-bold {{ $detail_color ?? ($value_color ?? 'text-slate-800') }}">{{ $detail }}</span>
        @endif

        @if (!empty($details_array))
            <div class="flex flex-col justify-start h-full text-xs text-right md:text-sm text-slate-500">
                @foreach ($details_array as $detail_key => $detail_value)
                    <div class="{{ $detail_color ?? ($title_color ?? 'text-slate-500') }} {{ $detail_container_class ?? '' }}">
                        <span class="mr-1 font-semibold {{ !empty($title_color) ? 'text-white' : 'text-slate-700' }}">{{ $detail_key }}:</span>
                        <span class="font-medium">{!! $detail_value !!}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
