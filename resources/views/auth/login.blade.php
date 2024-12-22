@extends('layouts.guest')
@section('title', 'Login')
@section('content')
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> -->
    <div class="col-lg-5">
    <div class="card shadow-lg border-0 rounded-lg mt-5">
        <div class="card-header">
            <h3 class="text-center font-weight-light my-4">Login</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputEmail" type="email" value="{{old('email')}}" required autofocus autocomplete="username" />
                    <label for="inputEmail">{{__('Email')}}</label>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" id="inputPassword" type="password" required autocomplete="current-password" />
                    <label for="inputPassword">{{__('Password')}}</label>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" id="remember_me" type="checkbox" name="remember" />
                    <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                    <x-primary-button class="btn btn-primary">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
