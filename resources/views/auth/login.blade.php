@extends('layouts.app')

@section('title', 'Pieslēgties - ' . brand_name())

@section('content')
    <div class="flex flex-col items-center justify-center w-full min-h-screen p-4 bg-slate-50">
        @if ($errors->any())
            <div class="flex items-center justify-start w-full max-w-sm p-4 mb-4 text-sm font-medium text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs">
                <svg class="w-5 h-5 mr-2 shrink-0 fill-current text-rose-600" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif
        <div class="flex flex-col items-center justify-center w-full max-w-sm p-8 bg-white rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex flex-col items-center gap-1.5 mb-6">
                <x-brand-logo size="md" />
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">Vadības panelis</span>
            </div>

            <form method="POST" action="{{ route('login') }}" class="w-full">
                @csrf
                <div class="mb-4">
                    <label for="username" class="block mb-1.5 text-sm font-medium text-slate-700">
                        {{ __('Lietotājvārds / E-pasts') }}
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 text-slate-900 bg-white border border-slate-300 rounded-lg shadow-xs placeholder-slate-400 focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm"
                        placeholder="admin">
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-1.5 text-sm font-medium text-slate-700">
                        {{ __('Parole') }}
                    </label>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 text-slate-900 bg-white border border-slate-300 rounded-lg shadow-xs placeholder-slate-400 focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm font-medium text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-2 focus:ring-emerald-500/20 mr-2"
                            {{ old('remember') ? 'checked' : '' }}>
                        {{ __('Atcerēties mani') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800 hover:underline">
                            {{ __('Aizmirsi paroli?') }}
                        </a>
                    @endif
                </div>
                <button type="submit"
                    class="w-full py-2.5 px-4 text-sm font-semibold text-center text-white bg-emerald-600 rounded-lg shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 transition-all cursor-pointer">
                    {{ __('Pieslēgties') }}
                </button>
            </form>
        </div>
    </div>
@endsection
