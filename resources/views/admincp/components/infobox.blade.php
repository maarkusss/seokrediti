@if (session('success') || session('error') || session('info') || $errors->any())
    <div class="flex items-center @if (session('success')) bg-emerald-50 text-emerald-800 border-emerald-200 @elseif(session('error') || $errors->any()) bg-rose-50 text-rose-800 border-rose-200 @else bg-blue-50 text-blue-800 border-blue-200 @endif border text-sm font-medium px-4 py-3 mx-4 mt-4 rounded shadow-xs"
         role="alert">
        <svg class="w-5 h-5 mr-2 shrink-0 fill-current"
             xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 20 20">
            @if (session('success'))
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            @else
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            @endif
        </svg>
        @if (session('success'))
            <span>{{ session('success') }}</span>
        @elseif(session('error'))
            <span>{{ session('error') }}</span>
        @elseif(session('info'))
            <span>{{ session('info') }}</span>
        @else
            <span>{{ $errors->first() }}</span>
        @endif
    </div>
@endif
