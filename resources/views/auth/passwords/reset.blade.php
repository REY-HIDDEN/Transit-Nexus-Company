@extends('layouts.public')

@section('title', __('messages.reset_password'))

@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="auth-panel p-4 p-md-5 mx-auto auth-panel-narrow">
                <div class="text-center mb-4">
                    <span class="auth-brand-icon auth-brand-icon-sm mx-auto mb-3">
                        <i class="bi bi-key"></i>
                    </span>
                    <h1 class="auth-heading mb-1">{{ __('messages.reset_password') }}</h1>
                    <p class="auth-subtext">{{ __('messages.check_form') }}</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('messages.password') }}</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_password') }}</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-login w-100 btn-icon justify-content-center">
                        <i class="bi bi-key"></i><span>{{ __('messages.reset_password') }}</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="auth-link-register">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
