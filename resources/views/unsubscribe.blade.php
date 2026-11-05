@extends('layouts.public')

@section('title', __('Atrakstīties no jaunumiem'))
@section('description', app()->getLocale() === 'ru'
    ? 'Отпишитесь от информационных уведомлений портала сравнения кредитов.'
    : 'Atrakstieties no informatīvajiem paziņojumiem un e-pasta jaunumiem.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-8 sm:py-10">
            <x-breadcrumbs />

            <div class="max-w-3xl">
                <span class="kicker mb-2">Oficiālā informācija</span>
                <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-[-0.02em] text-ink">
                    @lang('Atrakstīties no kredītu jaunumiem')
                </h1>
                <p class="standfirst mt-3">
                    @lang('Noklikšķinot uz pogas “Atrakstīties”, jūs neatgriezeniski atteiksieties no mūsu e-pasta ziņām. Tas nozīmē, ka turpmāk uz jūsu norādīto adresi netiks nosūtīta nekāda informācija.')
                </p>
            </div>
        </div>
    </section>

    <div class="wrap py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <article class="lg:col-span-8">
                <div class="border border-ink bg-paper-light p-6 sm:p-8">
                    <div class="kicker mb-1">E-pasta iestatījumi</div>
                    <h2 class="font-serif text-2xl font-semibold text-ink mb-2">
                        Ievadiet savu e-pasta adresi
                    </h2>
                    <p class="text-sm text-ink-soft mb-6 leading-relaxed">
                        Norādiet e-pasta adresi, uz kuru saņēmāt informatīvo paziņojumu, lai nekavējoties pārtrauktu turpmāku ziņu izsūtīšanu.
                    </p>

                    @include('components.contactsinfobox')

                    <form method="POST" action="{{ url('/unsubscribe') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input type="email"
                               name="email"
                               id="email"
                               autocomplete="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="Jūsu e-pasta adrese"
                               class="field flex-1">
                        <button type="submit" class="btn-ink shrink-0">
                            @lang('Atrakstīties')
                        </button>
                    </form>
                </div>
            </article>

            <div class="lg:col-span-4 lg:pl-8 lg:border-l lg:border-rule">
                <x-right-sidebar />
            </div>
        </div>
    </div>
@endsection
