@extends('layouts.public')

@section('title', 'Login | Transit Nexus')

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="auth-panel p-4 p-md-5 mx-auto" style="max-width: 460px;">
                <div class="text-center mb-4">
                    <span class="brand-mark d-inline-flex mx-auto mb-3"><i class="bi bi-bus-front"></i></span>
                    <h1 class="h3 fw-bold mb-1">Welcome Back</h1>
                    <p class="text-secondary mb-0">Login to access your tickets or dashboard.</p>
                </div>

                <form method="post" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <div class="mt-2 text-end">
                            <a href="{{ route('password.request') }}" class="small text-muted">{{ __('messages.forgot_password') }}</a>
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label for="remember" class="form-check-label text-secondary">Remember me</label>
                    </div>
                    <button class="btn btn-primary w-100 btn-icon justify-content-center py-2">
                        <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-secondary small">New passenger?</span>
                    <a href="{{ route('register') }}" class="small fw-semibold">Create a customer account</a>
                </div>
            </div>
        </div>
    </section>
@endsection
