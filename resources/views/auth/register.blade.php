@extends('layouts.public')

@section('title', 'Register | Transit Nexus')

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="auth-panel p-4 p-md-5 mx-auto" style="max-width: 500px;">
                <div class="text-center mb-4">
                    <span class="brand-mark d-inline-flex mx-auto mb-3"><i class="bi bi-person-plus"></i></span>
                    <h1 class="h3 fw-bold mb-1">Create Account</h1>
                    <p class="text-secondary mb-0">Register to reserve seats and manage bookings.</p>
                </div>

                <form method="post" action="{{ route('register.store') }}">
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
                    <button class="btn btn-primary w-100 btn-icon justify-content-center py-2">
                        <i class="bi bi-person-plus"></i><span>Create Account</span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <span class="text-secondary small">Already registered?</span>
                    <a href="{{ route('login') }}" class="small fw-semibold">Login</a>
                </div>
            </div>
        </div>
    </section>
@endsection
