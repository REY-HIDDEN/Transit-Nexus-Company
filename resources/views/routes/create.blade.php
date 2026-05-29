@extends('layouts.app')

@section('title', 'Add Route')
@section('subtitle', 'Create a transport route with distance and pricing.')

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Add Route</h2>
        </div>
        <form method="post" action="{{ route('routes.store') }}" class="p-4">
            @include('routes._form')
        </form>
    </div>
@endsection
