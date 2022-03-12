@extends('index')

@section('content')
    <h2>{{ __('Sign in') }}</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
            <div>
                <input
                        id="email" type="email" name="email"
                        class="form-input @error('email') form-input-error @enderror"
                        value="{{ old('email') }}" required autocomplete="email" autofocus
                >

                @error('email')
                <span class="form-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div>
            <label for="password" class="form-label"> Password </label>
            <div>
                <input id="password" name="password" type="password"
                       autocomplete="current-password" required
                       class="form-input @error('password') form-input-error @enderror"
                >
            </div>

            @error('email')
            <span class="form-invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="flex items-end justify-between">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>

        <div>
            <button type="submit"
                    class="button-primary">
                {{ __('Sign in') }}
            </button>
        </div>

        <div class="mt-6 relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500"> Or continue with </span>
            </div>
        </div>

        <div class="flex items-center">
            <a href="{{ route('register') }}" class="m-auto">
                {{ __('Register') }}
            </a>
        </div>
    </form>
@endsection
