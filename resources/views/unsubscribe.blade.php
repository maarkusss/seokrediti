@extends('layouts.public')

@section('title', __('Atrakstīties no jaunumiem'))
@section('description', app()->getLocale() === 'ru'
    ? 'Отпишитесь от информационных уведомлений портала сравнения кредитов.'
    : 'Atrakstieties no informatīvajiem paziņojumiem un e-pasta jaunumiem.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="border-b border-ink">
        <div class="wrap py-10 sm:py-12 max-w-2xl">
            <span class="kicker mb-2">E-pasta iestatījumi</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-ink">
                @lang('Atrakstīties no kredītu jaunumiem')
            </h1>
            <p class="standfirst mt-3 text-base">
                @lang('Noklikšķinot uz pogas “Atrakstīties”, jūs neatgriezeniski atteiksieties no mūsu e-pasta ziņām. Tas nozīmē, ka turpmāk uz jūsu norādīto adresi netiks nosūtīta nekāda informācija.')
            </p>
        </div>
    </section>

    <div class="wrap py-10 sm:py-14">
        <div class="max-w-xl border border-ink bg-paper-light p-6 sm:p-8">
            <h2 class="font-serif text-xl font-semibold text-ink mb-2">
                Ievadiet savu e-pasta adresi
            </h2>
            <p class="text-xs text-ink-muted mb-5">
                Norādiet e-pastu, uz kuru saņēmāt informatīvo paziņojumu, lai pārtrauktu izsūtīšanu.
            </p>

            @include('components.contactsinfobox')

            <form method="POST" action="{{ url('/unsubscribe') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="email"
                       name="email"
                       id="email"
                       autocomplete="email"
                       value="@auth{{ auth()->user()->email }}@endauth"
                       required
                       placeholder="@lang('Your e-mail')"
                       class="field flex-1">
                <button type="submit" class="btn-ink shrink-0">
                    @lang('Atrakstīties')
                </button>
            </form>
        </div>
    </div>
@endsection
