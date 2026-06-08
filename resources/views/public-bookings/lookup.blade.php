@extends('layouts.public')

@section('title', 'Find My Ticket | ' . __('messages.title'))

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    {{-- Lookup Form --}}
                    <div class="content-panel p-4 reveal">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px; background: var(--tn-gradient); color: white; font-size: 1.1rem;">
                                <i class="bi bi-search"></i>
                            </span>
                            <div>
                                <h1 class="h4 fw-bold mb-0" style="letter-spacing: -0.02em;">Find My Ticket</h1>
                                <div class="text-secondary small">Enter your phone number to look up your tickets.</div>
                            </div>
                        </div>

                        <form method="post" action="{{ route('tickets.lookup.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control form-control-lg"
                                       value="{{ old('phone_number') }}" required placeholder="+250 7XX XXX XXX"
                                       style="border-radius: 10px;">
                                @error('phone_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-icon w-100 justify-content-center" style="padding: 0.7rem 1rem; border-radius: 10px;">
                                <i class="bi bi-search"></i><span>find tickets</span>
                            </button>
                        </form>
                    </div>

                    {{-- Results --}}
                    @if (isset($bookings))
                        <div class="mt-4 reveal" style="transition-delay: 0.15s;">
                            <h2 class="h5 fw-bold mb-3">
                                <i class="bi bi-ticket-perforated me-1"></i>
                                {{ $bookings->total() }} ticket(s) found
                            </h2>

                            @forelse ($bookings as $booking)
                                <div class="content-panel p-3 mb-3" style="border-left: 4px solid {{ $booking->payment_status === 'paid' ? '#059669' : '#f59e0b' }};">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold" style="font-family: monospace;">{{ $booking->ticket_number }}</span>
                                                @if ($booking->payment_status === 'paid')
                                                    <span class="badge" style="background: #d1fae5; color: #065f46; border: none;"><i class="bi bi-check-circle"></i> paid</span>
                                                @else
                                                    <span class="badge" style="background: #fef3c7; color: #92400e; border: none;"><i class="bi bi-clock"></i> pending</span>
                                                @endif
                                            </div>
                                            <div class="text-secondary small">
                                                <i class="bi bi-bus-front me-1"></i>{{ $booking->trip?->route?->name ?? '-' }}
                                                &middot;
                                                <i class="bi bi-calendar3 ms-1 me-1"></i>{{ $booking->trip?->departure_date?->format('M d, Y') }}
                                                &middot;
                                                <i class="bi bi-person me-1"></i>{{ $booking->passenger_name }}
                                                &middot;
                                                <span class="fw-semibold">Seat {{ $booking->seat_number }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            @if ($booking->payment_status === 'pending')
                                                <a href="{{ route('payment.create', $booking) }}" class="btn btn-success btn-sm btn-icon" style="background: #059669; border: none;">
                                                    <i class="bi bi-credit-card"></i><span>pay now</span>
                                                </a>
                                            @else
                                                <a href="{{ route('tickets.receipt', $booking) }}" class="btn btn-primary btn-sm btn-icon">
                                                    <i class="bi bi-eye"></i><span>view ticket</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="content-panel p-5 text-center">
                                    <i class="bi bi-ticket text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="text-secondary mt-3 mb-0">No tickets found for this phone number.</p>
                                    <a href="{{ route('home') }}#trips" class="btn btn-primary btn-icon mt-3">
                                        <i class="bi bi-ticket-perforated"></i><span>book a trip</span>
                                    </a>
                                </div>
                            @endforelse

                            @if ($bookings->hasPages())
                                <div class="mt-3">
                                    {{ $bookings->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
