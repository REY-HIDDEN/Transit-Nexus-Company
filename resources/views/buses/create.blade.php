@extends('layouts.app')

@section('title', 'Add Bus')
@section('subtitle', 'Register a vehicle and its seating capacity.')

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Add Bus</h2>
        </div>
        <form method="post" action="{{ route('buses.store') }}" class="p-4">
            @include('buses._form')
        </form>
    </div>
@endsection
