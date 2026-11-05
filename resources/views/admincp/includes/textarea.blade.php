@php
    /** @var string $name */
    if (!empty($value_if_auth)) {
        $textarea_value = $value_if_auth;
    } else {
        $textarea_value = old($name, $value ?? '');
    }
@endphp

<div class="flex flex-col flex-auto w-full {{ $container_class ?? '' }}">
    <label for="{{ $id }}" class="mb-1.5 text-sm font-medium text-slate-700">
        {{ $label }} @if (!empty($required))
            <span class="text-rose-500">*</span>
        @endif
    </label>
    <textarea
        class="w-full px-3.5 py-2 text-slate-900 bg-white border border-slate-300 rounded shadow-sm resize-y placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all {{ $class ?? '' }}"
        id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder ?? '' }}" cols="{{ $cols ?? '' }}"
        rows="{{ $rows ?? 4 }}">{{ $textarea_value }}</textarea>
</div>
