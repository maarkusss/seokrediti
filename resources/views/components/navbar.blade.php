@php
    $footerPages = App\Models\Admincp\Sections\Sections::where('lang', app()->getLocale())
        ->where('active', true)
        ->where('parent_section_id', null)
        ->where('type', '!=', 'home')
        ->where('display_in_the_header', true)
        ->orderBy('order')
        ->get();

    $languages = App\Models\Admincp\Settings::where('name', 'lang')->get();

    $loanTypes = App\Models\Admincp\LoanTypes\LoanTypes::where('lang', app()->getLocale())
        ->where('active', true)
        ->where('parent_type_id', null)
        ->orderBy('order', 'asc')
        ->get();

    $currentUrl = request()->path();
@endphp

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" style="margin-right: 0;"
                        data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"
                        aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a style="padding-left:28px;background:url(/images/logo.png) no-repeat left center;" class="navbar-brand" href="{{ route('homepage') }}">eirokrediti.lv</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        @foreach ($loanTypes as $loanType)
                            @php
                                $subsections = \App\Models\Admincp\LoanTypes\LoanTypes::where(
                                    'parent_type_id',
                                    $loanType->id,
                                )
                                    ->where('active', 1)
                                    ->where('display_dropdown', 1)
                                    ->orderBy('order')
                                    ->get();

                                $isActive = request()->is($loanType->route_name) || request()->is($loanType->route_name . '/*');
                            @endphp

                            @if ($subsections->count() > 0)
                                <li class="dropdown {{ $isActive ? 'active' : '' }}">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                                       href="{{ route('section', ['name' => $loanType->route_name]) }}">
                                        {{ $loanType->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="{{ request()->is($loanType->route_name) ? 'active' : '' }}">
                                            <a href="{{ route('section', ['name' => $loanType->route_name]) }}">
                                                {{ $loanType->name }} internetā
                                            </a>
                                        </li>
                                        @foreach ($subsections as $subsection)
                                            @php
                                                $isSubActive = request()->is($loanType->route_name . '/' . $subsection->route_name) ||
                                                               request()->is($loanType->route_name . '/' . $subsection->route_name . '/*');
                                            @endphp
                                            <li class="{{ $isSubActive ? 'active' : '' }}">
                                                <a href="{{ route('section.deep', ['name' => $loanType->route_name, 'deep_name' => $subsection->route_name]) }}">
                                                    {{ $subsection->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="{{ $isActive ? 'active' : '' }}">
                                    <a href="{{ route('section', ['name' => $loanType->route_name]) }}">
                                        {{ $loanType->name }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Sections like Atsauksmes, Blogs --}}
                        @if (count($footerPages) > 0)
                            @foreach ($footerPages as $footerPage)
                                @php
                                    $isActive = request()->is($footerPage->route_name) || request()->is($footerPage->route_name . '/*');
                                @endphp
                                <li class="{{ $isActive ? 'active' : '' }}">
                                    <a href="{{ route('section', ['name' => $footerPage->route_name]) }}">
                                        {{ $footerPage->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif

                        {{-- Language switcher --}}
                        @if (count($languages) > 1)
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach ($languages as $language)
                                        @if ($language->value != app()->getLocale())
                                            <li>
                                                <a href="{{ url('/' . $language->value) }}">
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
            </div>
        </div>
    </div>
</nav>
