@extends('layouts.app')

@section('title', __('new booking'))
@section('subtitle', __('generate_ticket'))

@section('content')
    <div class="panel" style="max-width: 860px;">
        <div style="background: var(--tn-gradient); padding: 1.25rem 1.5rem; border-radius: 12px 12px 0 0;">
            <h2 class="h5 mb-0 fw-bold text-white">new booking title</h2>
        </div>
        <form method="post" action="{{ route('bookings.store') }}" class="p-4">
            @include('bookings._form')
        </form>
    </div>
@endsection
