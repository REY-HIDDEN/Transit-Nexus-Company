@extends('layouts.public')

@section('title', __('book_a_trip') . ' | ' . __('title'))

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="content-panel p-4" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                        <div class="d-flex align-items-center gap-2 text-secondary small text-uppercase mb-2" style="letter-spacing: 0.05em;">
                            <i class="bi bi-info-circle"></i> selected trip
                        </div>
                        <h1 class="h3 fw-bold mb-3" style="letter-spacing: -0.02em;">{{ $trip->route?->name }}</h1>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">date</div>
                                <div class="fw-semibold"><i class="bi bi-calendar3 text-secondary me-1"></i>{{ $trip->departure_date?->format('M d, Y') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">time</div>
                                <div class="fw-semibold"><i class="bi bi-clock text-secondary me-1"></i>{{ substr($trip->departure_time, 0, 5) }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">bus</div>
                                <div class="fw-semibold"><i class="bi bi-bus-front text-secondary me-1"></i>{{ $trip->bus?->plate_number }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">seats left</div>
                                <div class="fw-bold" style="color: #059669;">{{ $trip->remainingSeats() }} remaining</div>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top d-flex justify-content-between">
                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">price per seat</div>
                            <div class="fw-bold fs-5" style="color: var(--tn-blue);">{{ number_format($trip->route?->ticket_price ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form method="post" action="{{ route('public.bookings.store', $trip) }}" class="content-panel p-4">
                        @csrf
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; background: var(--tn-gradient); color: white; font-size: 0.85rem;">
                                <i class="bi bi-person"></i>
                            </span>
                            <h2 class="h4 fw-bold mb-0" style="letter-spacing: -0.02em;">passenger details</h2>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="passenger_name" class="form-label">full_name</label>
                                <input type="text" id="passenger_name" name="passenger_name" class="form-control" value="{{ old('passenger_name', auth()->user()->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label">phone_number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required placeholder="+250 7XX XXX XXX">
                            </div>
                            <div class="col-md-6">
                                <label for="seat_number" class="form-label">seat_number <span class="text-secondary fw-normal">(optional)</span></label>
                                <input type="number" id="seat_number" name="seat_number" min="1" max="{{ $trip->bus?->capacity }}" class="form-control" value="{{ old('seat_number') }}" placeholder="auto_assign">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('home') }}#trips" class="btn btn-outline-secondary btn-icon">
                                <i class="bi bi-arrow-left"></i><span>back</span>
                            </a>
                            <button class="btn btn-primary btn-icon">
                                <i class="bi bi-ticket-perforated"></i><span>reserve ticket</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
