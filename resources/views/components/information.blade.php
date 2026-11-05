@if (session('success') || session('error') || session('info') || $errors->any())
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show"
        class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>

        <!-- Modal -->
        <div class="relative w-full max-w-md p-8 bg-white shadow-2xl rounded-2xl">
            <!-- Close -->
            <button @click="show = false"
                class="absolute flex items-center justify-center w-8 h-8 text-gray-400 transition rounded-full top-4 right-4 hover:bg-gray-100 hover:text-gray-600">
                ✕
            </button>

            <!-- Icon -->
            <div class="flex justify-center">
                <div
                    class="flex items-center justify-center w-16 h-16 rounded-full mb-6
                @if (session('success')) bg-background-dark/10 text-background-dark
                @elseif(session('error') || $errors->any()) bg-red-100 text-red-600
                @else bg-blue-100 text-blue-600 @endif">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h3
                class="text-xl font-bold text-center mb-2
            @if (session('success')) text-background-dark
            @elseif(session('error') || $errors->any()) text-red-600
            @else text-blue-600 @endif">
                @if (session('success'))
                    @lang('Veiksmīgi!')
                @elseif (session('error') || $errors->any())
                    @lang('Kļūda')
                @else
                    @lang('Informācija')
                @endif
            </h3>

            <!-- Message -->
            <p class="text-base leading-relaxed text-center text-gray-600">
                @if (session('success'))
                    {{ session('success') }}
                @elseif (session('error'))
                    {{ session('error') }}
                @elseif (session('info'))
                    {{ session('info') }}
                @else
                    {{ $errors->first() }}
                @endif
            </p>

            <!-- Action -->
            <div class="flex justify-center mt-6">
                <button @click="show = false"
                    class="px-6 py-2 rounded-full font-semibold transition
                @if (session('success')) bg-background-dark text-white hover:bg-background-dark/90
                @elseif(session('error') || $errors->any())
                    bg-red-600 text-white hover:bg-red-700
                @else
                    bg-blue-600 text-white hover:bg-blue-700 @endif">
                    @lang('Aizvērt')
                </button>
            </div>
        </div>
    </div>
@endif
