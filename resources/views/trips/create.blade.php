@extends('layouts.app')

@section('title', 'Schedule Trip')
@section('subtitle', 'Assign a bus and route to a departure.')

@section('content')
    <div class="panel" style="max-width: 860px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Schedule Trip</h2>
        </div>
        <form method="post" action="{{ route('trips.store') }}" class="p-4">
            @include('trips._form')
        </form>
    </div>
@endsection
