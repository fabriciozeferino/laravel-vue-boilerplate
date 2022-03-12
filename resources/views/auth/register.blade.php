@extends('index')

@section('content')
    <h2 class="card-header">{{ __('Register') }}</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <div>
                <input id="name" type="text" name="name"
                       class="form-input @error('name') form-input-error @enderror"
                       value="{{ old('name') }}" required autocomplete="name" autofocus
                >

                @error('name')
                <span class="form-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div>
            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
            <div>
                <input id="email" type="email" name="email"
                       class="form-input @error('email') form-input-error @enderror"
                       value="{{ old('email') }}" required autocomplete="email"
                >

                @error('email')
                <span class="form-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div>
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div>
                <input id="password" type="password" name="password"
                       class="form-input @error('password') form-input-error @enderror"
                       required autocomplete="new-password"
                >

                @error('password')
                <span class="form-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div>
            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
            <div>
                <input id="password-confirm" type="password"
                       class="form-input" name="password_confirmation"
                       required autocomplete="new-password">
            </div>
        </div>

        <div>
            <button type="submit"
                    class="button-primary">
                {{ __('Register') }}
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
            <a href="{{ route('login') }}" class="m-auto">
                {{ __('Sign in') }}
            </a>
        </div>
    </form>
@endsection
