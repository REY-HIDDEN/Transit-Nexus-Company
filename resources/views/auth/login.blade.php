@extends('layouts.public')

@section('title', $portal === 'admin' ? 'Admin Login | Transit Nexus' : ($portal === 'customer' ? 'Customer Login | Transit Nexus' : 'Login | Transit Nexus'))

@section('content')
    <section class="auth-section">
        <div class="container">

            {{-- ──────────────────────────────────────────────── --}}
            {{-- PORTAL SELECTION: Two cards when no portal chosen --}}
            {{-- ──────────────────────────────────────────────── --}}
            @if (! $portal)
                <div class="text-center mb-5">
                    <span class="auth-brand-icon mx-auto mb-4">
                        <i class="bi bi-bus-front"></i>
                    </span>
                    <h1 class="portal-select-heading mb-2">Welcome to Transit Nexus</h1>
                    <p class="portal-select-sub">Choose your portal to get started.</p>
                </div>

                <div class="row g-4 justify-content-center portal-card-row">

                    {{-- Customer Portal --}}
                    <div class="col-md-6">
                        <a href="{{ route('login.customer') }}" class="portal-card portal-card-customer">
                            <div class="portal-icon portal-icon-customer">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-2 portal-card-title">Customer Portal</h3>
                            <p class="text-secondary mb-3 portal-card-desc">
                                Book tickets, view your trips, and manage your account.
                            </p>
                            <span class="btn btn-primary btn-icon px-4 py-2">
                                <i class="bi bi-box-arrow-in-right"></i><span>Login as Customer</span>
                            </span>
                            <div class="mt-3">
                                <small class="text-muted">Don't have an account?
                                    <a href="{{ route('register') }}" class="portal-card-link">Register here</a>
                                </small>
                            </div>
                        </a>
                    </div>

                    {{-- Admin Portal --}}
                    <div class="col-md-6">
                        <a href="{{ route('login.admin') }}" class="portal-card portal-card-admin">
                            <div class="portal-icon portal-icon-admin">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-2 portal-card-title">Admin Portal</h3>
                            <p class="text-secondary mb-3 portal-card-desc">
                                Manage buses, routes, trips, and oversee all bookings.
                            </p>
                            <span class="portal-btn-admin">
                                <i class="bi bi-shield-check"></i><span>Login as Admin</span>
                            </span>
                        </a>
                    </div>

                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('home') }}" class="auth-link-home">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>
            @endif

            {{-- ──────────────────────────────────────────────── --}}
            {{-- LOGIN FORM: Shown when a portal is selected --}}
            {{-- ──────────────────────────────────────────────── --}}
            @if ($portal === 'admin' || $portal === 'customer')
                <div class="auth-panel p-4 p-md-5 mx-auto auth-panel-narrow {{ $portal === 'admin' ? 'auth-form-admin' : '' }}">
                    <div class="text-center mb-4">
                        {{-- Portal badge --}}
                        <div class="d-inline-flex align-items-center gap-2 mb-3">
                            @if ($portal === 'admin')
                                <span class="portal-badge portal-badge-admin">
                                    <i class="bi bi-shield-lock me-1"></i>Admin Portal
                                </span>
                            @else
                                <span class="portal-badge portal-badge-customer">
                                    <i class="bi bi-person-badge me-1"></i>Customer Portal
                                </span>
                            @endif
                        </div>

                        <span class="auth-brand-icon auth-brand-icon-sm mx-auto mb-3">
                            <i class="bi bi-bus-front"></i>
                        </span>
                        <h1 class="auth-heading mb-1">
                            @if ($portal === 'admin')
                                Admin Login
                            @else
                                Welcome Back
                            @endif
                        </h1>
                        <p class="auth-subtext">
                            @if ($portal === 'admin')
                                Sign in to manage your transit operations.
                            @else
                                Login to access your tickets or dashboard.
                            @endif
                        </p>
                    </div>

                    <form method="post" action="{{ route('login.store') }}" class="auth-form">
                        @csrf
                        <input type="hidden" name="portal" value="{{ $portal }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <div class="mt-2 text-end">
                                <a href="{{ route('password.request') }}" class="auth-link-forgot">{{ __('messages.forgot_password') }}</a>
                            </div>
                        </div>
                        <div class="form-check mb-4">
                            <input type="checkbox" id="remember" name="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember me</label>
                        </div>
                        <button class="btn btn-login w-100 btn-icon justify-content-center">
                            <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
                        </button>
                    </form>

                    @if ($portal === 'customer')
                        <div class="text-center mt-4">
                            <span class="text-secondary small">New passenger?</span>
                            <a href="{{ route('register') }}" class="auth-link-register ms-1">Create a customer account</a>
                        </div>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="auth-link-switch">
                            <i class="bi bi-arrow-left"></i> Choose a different portal
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection
