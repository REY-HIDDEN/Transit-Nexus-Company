@extends('layouts.app')

@section('title', 'Edit Route')
@section('subtitle', $route->name)

@section('content')
    <div class="panel" style="max-width: 720px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">Edit Route</h2>
        </div>
        <form method="post" action="{{ route('routes.update', $route) }}" class="p-4">
            @method('PUT')
            @include('routes._form')
        </form>
    </div>
@endsection
