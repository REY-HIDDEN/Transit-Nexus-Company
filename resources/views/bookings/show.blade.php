@extends('layouts.app')

@section('title', __('ticket'))
@section('subtitle', $booking->ticket_number)

@section('actions')
    @if ($booking->booking_status === 'pending')
        <form method="post" action="{{ route('bookings.approve', $booking) }}" class="d-inline no-print">
            @csrf
            <button class="btn btn-success btn-icon" style="background: #059669; border: none;">
                <i class="bi bi-check2"></i><span>approve</span>
            </button>
        </form>
    @endif
    <button type="button" class="btn btn-outline-secondary btn-icon no-print" onclick="window.print()">
        <i class="bi bi-printer"></i><span>print</span>
    </button>
    <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-icon no-print">
        <i class="bi bi-arrow-left"></i><span>bookings</span>
    </a>
@endsection

@section('content')
    <section class="panel mx-auto" style="max-width: 640px; overflow: hidden;">
        <div style="background: var(--tn-gradient); padding: 2rem; color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small" style="opacity: 0.8; letter-spacing: 0.06em;">Transit Nexus</div>
                    <div class="fw-bold" style="font-size: 1.4rem; letter-spacing: -0.01em;">{{ $booking->ticket_number }}</div>
                </div>
                <div class="text-end">
                    <div class="small" style="opacity: 0.8;">status</div>
                    @php $bs = $booking->booking_status; $bsBg = $bs === 'confirmed' ? '#d1fae5' : ($bs === 'pending' ? '#fef3c7' : '#fee2e2'); $bsFg = $bs === 'confirmed' ? '#065f46' : ($bs === 'pending' ? '#92400e' : '#991b1b'); @endphp
                    <span class="badge" style="background: {{ $bsBg }}; color: {{ $bsFg }}; border: none; padding: 0.35em 0.75em;">{{ ucfirst($booking->booking_status) }}</span>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">passenger</div>
                    <div class="fw-bold fs-5">{{ $booking->passenger_name }}</div>
                    <div class="text-secondary">{{ $booking->phone_number }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">seat</div>
                    <div class="fw-bold" style="font-size: 2.5rem; letter-spacing: -0.03em; color: var(--tn-blue);">{{ $booking->seat_number }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">route</div>
                    <div class="fw-semibold">{{ $booking->trip?->route?->name }}</div>
                    <div class="fw-bold" style="color: var(--tn-blue);">{{ number_format($booking->trip?->route?->ticket_price ?? 0, 2) }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">departure</div>
                    <div class="fw-semibold">
                        <i class="bi bi-calendar3 me-1"></i>{{ $booking->trip?->departure_date?->format('M d, Y') }}
                    </div>
                    <div><i class="bi bi-clock me-1"></i>{{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : '' }}</div>
                    <div class="text-secondary">{{ $booking->trip?->bus?->plate_number }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">payment</div>
                    @php $pc = $booking->payment_status === 'paid' ? ['#d1fae5', '#065f46'] : ($booking->payment_status === 'pending' ? ['#fef3c7', '#92400e'] : ['#fee2e2', '#991b1b']); @endphp
                    <span class="badge" style="background: {{ $pc[0] }}; color: {{ $pc[1] }}; border: none;">{{ ucfirst($booking->payment_status) }}</span>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">booking date</div>
                    <div class="fw-semibold">{{ $booking->booking_date?->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </section>
@endsection
