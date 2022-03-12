@extends('index')

@section('content')
    <h2>{{ __('Reset Password') }}</h2>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Heroicon name: solid/check-circle -->
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div>
            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>

            <div>
                <input id="email" type="email" name="email"
                       class="form-input @error('email') form-input-error @enderror"
                       value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="form-invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div>
            <button type="submit"
                    class="button-primary">
                {{ __('Send Password Reset Link') }}
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
