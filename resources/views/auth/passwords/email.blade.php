@extends('layouts.public')

@section('title', __('messages.forgot_password'))

@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="auth-panel p-4 p-md-5 mx-auto auth-panel-narrow">
                <div class="text-center mb-4">
                    <span class="auth-brand-icon auth-brand-icon-sm mx-auto mb-3">
                        <i class="bi bi-shield-question"></i>
                    </span>
                    <h1 class="auth-heading mb-1">Forgot password</h1>
                    <p class="auth-subtext">{{ __('messages.check_form') }}</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-login w-100 btn-icon justify-content-center">
                        <i class="bi bi-envelope"></i><span>{{ __('messages.send_reset_link') }}</span>
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
