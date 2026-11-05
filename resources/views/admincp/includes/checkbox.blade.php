<div class="flex items-center gap-3 my-2">
    <input type="checkbox"
           class="w-4 h-4 text-blue-600 bg-white border border-slate-300 rounded focus:ring-2 focus:ring-blue-500/20 cursor-pointer shrink-0 transition-all {{ $class ?? '' }}"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ old($name, $value ?? '') }}"
           @if (!empty($checked))
               checked="checked"
           @endif
    />
    @if (!empty($label))
        <label for="{{ $id }}" class="text-sm font-medium text-slate-700 cursor-pointer select-none {{ $label_class ?? '' }}">
            {{ $label }} @if (!empty($required))
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif
</div>
