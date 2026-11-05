@php
    $sidebarLoanTypes = $loanTypes ?? \App\Models\Admincp\LoanTypes\LoanTypes::where('lang', app()->getLocale())
        ->where('active', true)
        ->where('parent_type_id', null)
        ->orderBy('order', 'asc')
        ->get();
    $subsections = \App\Models\Admincp\LoanTypes\LoanTypes::whereNotNull('parent_type_id')
        ->where('active', 1)
        ->orderBy('order')
        ->get();
@endphp
<div class="flex w-[275px] h-screen text-sm overflow-y-scroll text-white bg-background-dark lg:mt-0" id="sidebar">
    <ul class="w-full">
        {{-- Loantypes --}}
        @if (isset($sidebarLoanTypes))
            @foreach ($sidebarLoanTypes as $loanTypeItem)
                <li class="flex flex-col cursor-pointer">
                    @if ($subsections->where('parent_type_id', $loanTypeItem->id)->count() > 0)
                        <div
                            class="flex items-center justify-start w-full p-3 font-medium text-white transition-all duration-200 border-b border-gray-700 cursor-pointer">
                            {{ $loanTypeItem->name }}
                        </div>
                    @else
                        <a class="flex items-center justify-start w-full p-3 font-medium text-white transition-all duration-200 border-b border-gray-700 cursor-pointer"
                            href="{{ route('section', ['name' => $loanTypeItem->route_name]) }}"
                            title="{{ $loanTypeItem->anchor_element_title }}">
                            {{ $loanTypeItem->name }}
                        </a>
                    @endif
                </li>
                @if ($subsections->where('parent_type_id', $loanTypeItem->id)->count() > 0)
                    @foreach ($subsections->where('parent_type_id', $loanTypeItem->id) as $subsection)
                        <li class="flex flex-col cursor-pointer">
                            <a class="flex items-center justify-start w-full p-3 font-medium transition-all duration-200 border-b border-gray-700 cursor-pointer pl-7 text-primary-dark"
                                @if ($subsection->route_name == 'atrie' || $subsection->route_name == 'bistrie-krediti') href="{{ route('homepage') }}" @else href="{{ route('section.deep', ['name' => $loanTypeItem->route_name, 'deep_name' => $subsection->route_name]) }}" @endif
                                title="{{ $subsection->anchor_element_title }}">
                                {{ $subsection->name }}
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        @endif
        {{-- @if (isset($subsections))
            @if ($subsections->count() > 0)
                <div class="flex flex-col w-full py-3 text-sm transition-all duration-200 border-b border-gray-700 text-primary-dark whitespace-nowrap">
                    @foreach ($subsections as $subsection)
                        <a class="w-full py-3 text-sm transition-all duration-200 text-primary-dark whitespace-nowrap px-7"
                           href="{{ route('section.deep', ['name' => $loanType->route_name, 'deep_name' => $subsection->route_name]) }}"
                           title="{{ $subsection->anchor_element_title }}">
                            {{ $subsection->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        @endif --}}
        {{-- Sections --}}
        @if (count($footerPages) > 0)
            @foreach ($footerPages as $footerPage)
                <li class="flex items-center border-b border-gray-700">
                    <a class="flex items-center w-full h-full p-3 md:w-auto"
                        href="{{ route('section', ['name' => $footerPage->route_name]) }}"
                        title="{{ $footerPage->name }}">
                        {{ $footerPage->name }}
                    </a>
                </li>
            @endforeach
            <li>
        @endif
        {{-- Languages --}}
        @if (count($languages) > 1)
            <li class="relative flex flex-col pb-12 md:items-center md:flex-row">
                {{-- <button class="flex items-center w-full h-full p-3 md:w-auto"
                        id="language_dropdown_button"
                        type="button"
                        title="{{ strtoupper(app()->getLocale()) }}">
                    {{ strtoupper(app()->getLocale()) }}
                </button> --}}
                <ul class="md:border md:rounded-b md:translate-y-full md:inset-x-0 md:bottom-0 md:absolute"
                    id="language_dropdown_menu">
                    @foreach ($languages as $language)
                        @if ($language->value != app()->getLocale())
                            <li class="flex items-center w-full">
                                <a class="flex items-center w-full p-3 text-white" href="/{{ $language->value }}"
                                    title="{{ strtoupper($language->value) }}">
                                    {{ strtoupper($language->value) }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif
    </ul>
</div>
<div class="absolute top-0 left-0 hidden w-screen h-screen -z-10" id="sidebar-overlay"></div>
<style>
    #dropdown-toggle,
    #dropdown-toggle_1 {
        transition: .2s;
    }

    #dropdown-toggle.toggled,
    #dropdown-toggle_1.toggled {
        transform: rotate(180deg);
        transition: .2s;
    }
</style>

{{-- <script>
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    sidebarToggle.onclick = () => {
        toggle();
        if (sidebarOverlay.classList.contains("hidden")) {
            slideIn();
        } else {
            slideOut();
        }
    };

    sidebarOverlay.onclick = () => {
        toggle();
        if (sidebarOverlay.classList.contains("hidden")) {
            slideIn();
        } else {
            slideOut();
        }
    };

    function slideIn() {
        sidebar.parentNode.classList.add("animate-slideBar");
        setTimeout(function() {
            sidebar.parentNode.classList.toggle("-translate-x-[17.188rem]");
        }, 500);

        document.querySelector('header').children[0].classList.add("animate-slide");

        document.querySelector('main').classList.add("animate-slide");
        document.querySelector('footer').classList.add("animate-slide");

        setTimeout(function() {
            sidebar.parentNode.classList.remove("animate-slideBar");
            document.querySelector('header').children[0].classList.remove("animate-slide");
            document.querySelector('main').classList.remove("animate-slide");
            document.querySelector('footer').classList.remove("animate-slide");
            sidebarOverlay.classList.toggle("hidden");
        }, 600);
    }

    function slideOut() {
        sidebar.parentNode.classList.add("animate-slideBarOut");
        sidebar.parentNode.classList.toggle("-translate-x-[17.188rem]");

        document.querySelector('header').children[0].classList.add("animate-slideOut");
        document.querySelector('main').classList.add("animate-slideOut");
        document.querySelector('footer').classList.add("animate-slideOut");

        setTimeout(function() {
            sidebar.parentNode.classList.remove("animate-slideBarOut");
            document.querySelector('header').children[0].classList.remove("animate-slideOut");
            document.querySelector('main').classList.remove("animate-slideOut");
            document.querySelector('footer').classList.remove("animate-slideOut");
            sidebarOverlay.classList.toggle("hidden");
        }, 600);
    }

    function toggle() {
        document.querySelector('header').children[0].classList.toggle("translate-x-[17.188rem]");
        document.querySelector('main').classList.toggle("translate-x-[17.188rem]");
        document.querySelector('footer').classList.toggle("translate-x-[17.188rem]");
    }
</script> --}}
