@extends('layouts.public')

@section('title', __('Atrakstīties') . ' - ' . env('APP_NAME'))
@if (app()->getLocale() == 'lv')
    @section('description',
        'Atsakies no jaunumiem, ja vairs nevēlies saņemt informatīvus paziņojumus par jaunumiem finanšu
        jomā.')
    @else
    @section('description',
        'Отпишитесь, если больше не хотите получать информативные уведомления о новостях в
        финансовой сфере.')
    @endif

@section('keywords', 'Keywords')
{{-- @section('schema')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "@yield('title')",
            "description": "@yield('description')",
            "url": "{{ url()->current() }}",
            "logo": "https://www.eirokrediti.lv/images/logo.png",
            "image": "https://www.eirokrediti.lv/images/logo.png",
            "email": "mailto:info@atrie.lv",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Saldus",
                "postalCode": "3801",
                "streetAddress": "Liela iela 2B",
                "addressCountry": "LV"
            }
        }
    </script>
@endsection --}}
@section('content')
    <main class="md:translate-x-0">
        <section class="pt-28 pb-[6rem] bg-no-repeat bg-cover hero-bg">
            <div class="max-w-[880px] xl:max-w-screen-lg 2xl:max-w-screen-xl mx-auto">
                <div class="pb-40">
                    <h1 class="pb-1.5 text-[3.5em] text-center text-white">
                        @lang('Atrakstīties no kredītu jaunumiem')
                        {{-- {{ $section->h1 }} --}}
                    </h1>
                    <p class="text-xl text-center text-white">
                        @lang('Noklikšķinot uz pogas “Atrakstīties”, jūs neatgriezeniski atteiksieties no mūsu e-pasta ziņām. Tas nozīmē, ka turpmāk uz jūsu norādīto adresi netiks nosūtīta nekāda informācija. Lūdzu, ņemiet vērā, ka šī izvēle ir galīga un neatgriezeniska.')
                        {{-- {{ $section->h1_description }} --}}
                    </p>
                </div>
            </div>
        </section>
        {{-- <section class="py-20 bg-secondary-normal -translate-y-[10rem]">
            <div class="max-w-xl px-6 mx-auto">
                <div class="flex items-center justify-center pb-9">
                    <h2 class="text-3xl text-secondary-dark">@lang('Pieraksties kredītu jaunumiem')</h2>
                </div>
                <div id="subscribed"
                     class="flex items-center justify-center gap-4">
                    <input class="w-full h-16 p-5 border-none rounded-md placeholder:text-xl bg-opacity-20 bg-secondary-dark placeholder:text-secondary-dark focus:border-white focus:ring-white"type="email"
                           name="email"
                           id="email"
                           placeholder="E-pasts">
                    <input class="h-16 py-2 pl-12 text-xl bg-white rounded-md px-7 text-secondary-normal"
                           onclick="subscribe()"
                           type="submit"
                           value="Pieteikties"
                           class="fit">
                </div> --}}
        {{-- @dd(request()->getHttpHost()) --}}
        {{-- <div class="flex items-center justify-center pt-6">
                    <a href="{{ request()->getHttpHost() . '/' . app()->getLocale() . '/unsubscribe' }}"
                       class="text-xl text-secondary-dark hover:underline">@lang('Atrakstīties')</a>
                </div>
            </div>
        </section> --}}
        <section class="-translate-y-[10rem]">
            {{-- <div class="relative px-4 mx-auto lg:px-[8rem] max-w-screen-2xl"> --}}
            <div class="p-12 border-b-2 rounded-md bg-secondary-normal">
                <div class="max-w-xl px-4 mx-auto">
                    <div class="flex items-center justify-center text-center pb-9">
                        <h2 class="xl:text-3xl text-[22px] tracking-tight text-secondary-dark">@lang('Atrakstīties no kredītu jaunumiem')</h2>
                    </div>
                    @include('components.contactsinfobox')
                    <form method="POST">
                        @csrf
                        <div id="subscribed" class="flex flex-col items-center justify-center gap-4 xs:flex-row">
                            <input
                                class="w-full p-5 font-bold text-white border-none rounded-md h-11 sm:placeholder:text-xl bg-opacity-20 bg-secondary-dark placeholder:text-secondary-dark focus:border-white focus:ring-white"type="email"
                                type="email" name="email" id="email" autocomplete="email"
                                value="@auth() {{ auth()->user()->email }} @endauth" placeholder="@lang('Your e-mail')"
                                required placeholder="E-pasts">
                            <button type="submit"
                                class="w-full text-base bg-white rounded-md h-11 sm:text-xl xs:w-auto px-7 text-secondary-normal">
                                @lang('Atrakstīties')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- </div> --}}
        </section>
    </main>
@endsection
