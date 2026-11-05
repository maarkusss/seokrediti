@php
$input_type = 'text';
if (!empty($type)) {
    $input_type = $type;
}
@endphp

<div class="flex flex-col flex-auto w-full {{ $container_class ?? '' }}">
    @if (!empty($label))
        <label for="{{ $id }}" class="mb-1.5 text-sm font-medium text-slate-700 {{ $label_class ?? '' }}">
            {{ $label }} @if (!empty($required))<span class="text-rose-500">*</span>@endif
        </label>
    @endif
    <input
        type="{{ $input_type }}"
        class="w-full px-3.5 py-2 text-slate-900 bg-white border border-slate-300 rounded shadow-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all {{ $class ?? '' }}"
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder ?? '' }}"
        autocomplete="off"
        @if (!empty($value_if_auth))
            @auth
                value="{{ $value_if_auth }}"
            @endauth
        @else
            value="{{ old($name, $value ?? '') }}"
        @endif
        @if (!empty($required))
            required
        @endif
    />
</div>
