@extends('layouts.public')

@section('title', 'Register | Transit Nexus')

@section('content')
    <section class="auth-section">
        <div class="container">
            <div class="auth-panel p-4 p-md-5 mx-auto auth-panel-wide">
                <div class="text-center mb-4">
                    <span class="auth-brand-icon auth-brand-icon-sm mx-auto mb-3">
                        <i class="bi bi-person-plus"></i>
                    </span>
                    <h1 class="auth-heading mb-1">Create Account</h1>
                    <p class="auth-subtext">Register to reserve seats and manage bookings.</p>
                </div>

                <form method="post" action="{{ route('register.store') }}" class="auth-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-login w-100 btn-icon justify-content-center">
                        <i class="bi bi-person-plus"></i><span>Create Account</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-secondary small">Already registered?</span>
                    <a href="{{ route('login') }}" class="auth-link-register ms-1">Login</a>
                </div>
            </div>
        </div>
    </section>
@endsection
