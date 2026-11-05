<div class="flex flex-col flex-auto w-full {{ $container_class ?? '' }}">
    @if (!empty($label))
        <label for="{{ $id }}" class="mb-1.5 text-sm font-medium text-slate-700">
            {{ $label }} @if (!empty($required))
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif
    <select
        class="w-full px-3.5 py-2 text-slate-900 bg-white border border-slate-300 rounded shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all {{ $class ?? '' }}"
        name="{{ $name }}" id="{{ $id }}">
        @if (!empty($default_option))
            <option value="">{{ $default_option }}</option>
        @endif
        @if (!empty($options))
            @foreach ($options as $option_key => $option_name)
                <option value="{{ $option_key }}" @if (!empty($value) && $value == $option_key) selected @endif>{{ $option_name }}
                </option>
            @endforeach
        @endif
    </select>
</div>
