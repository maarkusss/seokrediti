@extends('layouts.app')

@section('title', 'Register - ' . env('APP_NAME'))

@section('content')
    <div class="flex flex-col items-center justify-center w-full min-h-screen p-4 bg-gray-100">
        @if ($errors->any())
            <div class="flex items-center justify-start p-3 mb-4 bg-red-200 border border-red-800 rounded">
                <span class="text-sm font-medium text-red-800">{{ $errors->first() }}</span>
            </div>
        @endif
        <div
            class="flex flex-col items-center justify-center w-full p-8 bg-white rounded-xl border border-primary-normal sm:w-auto">
            <h1 class="mb-4 text-2xl font-medium text-gray-700">{{ __('Register') }}</h1>
            <form method="POST" action="{{ route('register') }}" class="w-full sm:w-auto">
                @csrf
                <div class="flex flex-col sm:-mx-2 sm:flex-row">
                    <label class="flex flex-col mb-3 sm:mx-2">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('First name') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="text" name="first_name" id="name" value="{{ old('first_name') }}" required
                            autocomplete="name">
                    </label>
                    <label class="flex flex-col mb-3 sm:mx-2">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('Last name') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="text" name="last_name" id="last-name" value="{{ old('last_name') }}" required
                            autocomplete="name">
                    </label>
                </div>
                <div class="flex flex-col sm:-mx-2 sm:flex-row">
                    <label class="flex flex-col mb-3 sm:mx-2">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('Username') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="text" name="username" id="username" value="{{ old('username') }}" required>
                    </label>
                    <label class="flex flex-col mb-3 sm:mx-2">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('E-mail') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="email" name="email" id="email" value="{{ old('email') }}" required>
                    </label>
                </div>
                <div class="flex flex-col sm:-mx-2 sm:flex-row">
                    <label class="flex flex-col mb-3 sm:mx-2 sm:mb-0">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('Password') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="password" name="password" id="password" required autocomplete="new-password">
                    </label>
                    <label class="flex flex-col sm:mx-2">
                        <span class="mb-2 text-sm font-medium text-gray-600">{{ __('Confirm password') }}</span>
                        <input
                            class="px-3 py-2 text-gray-700 bg-gray-100 border-gray-300 rounded focus:ring-primary-dark focus:border-primary-dark"
                            type="password" name="password_confirmation" id="password-confirmation" required
                            autocomplete="new-password">
                    </label>
                </div>
                <button
                    class="w-full px-3 py-2 mt-4 text-sm font-medium text-center text-white rounded bg-primary-normal hover:bg-primary-dark"
                    type="submit">
                    {{ __('Register') }}
                </button>
            </form>
        </div>
    </div>
@endsection
