@extends('layouts.app')

@section('title', 'Password reset link - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col items-center justify-center w-full min-h-screen p-4 bg-slate-50">
        <div class="flex items-center gap-3 mb-6">
            <img src="/images/logo-coin.png" class="w-8 h-8 object-contain shrink-0" alt="eirokrediti.lv">
            <div class="flex flex-col">
                <span class="text-xl font-bold tracking-tight text-slate-900">eirokrediti.lv</span>
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Admin Panel</span>
            </div>
        </div>
        @if ($errors->any())
            <div class="flex items-center justify-start p-3 mb-4 bg-red-200 border border-red-800 rounded">
                <span class="text-sm font-medium text-red-800">{{ $errors->first() }}</span>
            </div>
        @endif
        @if (session('status'))
            <div class="flex items-center justify-start p-3 mb-4 bg-green-200 border border-green-800 rounded"
                 role="alert">
                <span class="text-sm font-medium text-green-800">{{ session('status') }}</span>
            </div>
        @endif
        <div class="flex flex-col items-center justify-center w-full p-4 bg-white rounded sm:w-auto">
            <h1 class="mb-4 text-2xl font-medium text-gray-700">{{ __('Reset password') }}</h1>
            <form method="POST"
                  action="{{ route('password.email') }}"
                  class="w-full sm:w-auto">
                @csrf
                <div>
                    <label class="flex flex-col">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('E-mail address') }}</span>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               class="w-full px-3 py-2 text-gray-700 bg-gray-200 border rounded appearance-none sm:w-64">
                    </label>
                </div>
                <button type="submit"
                        class="w-full px-3 py-2 mt-6 text-sm font-medium text-center text-white bg-gray-700 rounded hover:bg-gray-800">
                    {{ __('Send password reset link') }}
                </button>
            </form>
        </div>
    </div>
@endsection
