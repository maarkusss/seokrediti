@extends('layouts.app')

@section('title', 'Paroles atjaunošana - ' . brand_name())

@section('content')
    <div class="flex flex-col items-center justify-center w-full min-h-screen p-4 bg-slate-50">
        <div class="flex flex-col items-center gap-1.5 mb-6">
            <x-brand-logo size="md" />
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Vadības panelis</span>
        </div>
        @if ($errors->any())
            <div class="flex items-center justify-start p-3 mb-4 bg-rose-50 border border-rose-200 rounded-xl">
                <span class="text-sm font-medium text-rose-800">{{ $errors->first() }}</span>
            </div>
        @endif
        @if (session('status'))
            <div class="flex items-center justify-start p-3 mb-4 bg-emerald-50 border border-emerald-200 rounded-xl"
                 role="alert">
                <span class="text-sm font-medium text-emerald-800">{{ session('status') }}</span>
            </div>
        @endif
        <div class="flex flex-col items-center justify-center w-full max-w-sm p-6 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <h1 class="mb-4 text-xl font-bold text-slate-800">{{ __('Reset password') }}</h1>
            <form method="POST"
                  action="{{ route('password.email') }}"
                  class="w-full">
                @csrf
                <div>
                    <label class="flex flex-col">
                        <span class="mb-1.5 text-sm font-medium text-slate-700">{{ __('E-mail address') }}</span>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               class="w-full px-3.5 py-2.5 text-slate-900 bg-white border border-slate-300 rounded-lg text-sm">
                    </label>
                </div>
                <button type="submit"
                        class="w-full px-4 py-2.5 mt-5 text-sm font-semibold text-center text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors cursor-pointer">
                    {{ __('Send password reset link') }}
                </button>
            </form>
        </div>
    </div>
@endsection
