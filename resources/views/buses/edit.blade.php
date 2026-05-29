@extends('layouts.app')

@section('title', 'Edit Bus')
@section('subtitle', $bus->plate_number)

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Edit Bus</h2>
        </div>
        <form method="post" action="{{ route('buses.update', $bus) }}" class="p-4">
            @method('PUT')
            @include('buses._form')
        </form>
    </div>
@endsection
