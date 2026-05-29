@extends('layouts.app')

@section('title', 'Edit Trip')
@section('subtitle', $trip->route?->name ?? 'Trip')

@section('content')
    <div class="panel" style="max-width: 860px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Edit Trip</h2>
        </div>
        <form method="post" action="{{ route('trips.update', $trip) }}" class="p-4">
            @method('PUT')
            @include('trips._form')
        </form>
    </div>
@endsection
