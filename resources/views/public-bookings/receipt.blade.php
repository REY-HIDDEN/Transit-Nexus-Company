@extends('layouts.public')

@section('title', 'Ticket Receipt | ' . __('messages.title'))

@section('content')
    <section class="section-pad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 reveal">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>
                                <strong>Payment Successful!</strong><br>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    {{-- Ticket Card --}}
                    <div class="content-panel p-0 overflow-hidden reveal">
                        {{-- Ticket Header --}}
                        <div style="background: var(--tn-gradient); padding: 2rem 2rem 1.5rem; color: white;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-uppercase small fw-semibold mb-1" style="opacity: 0.8; letter-spacing: 0.06em;">
                                        <i class="bi bi-ticket-perforated me-1"></i>ticket receipt
                                    </div>
                                    <h1 class="h3 fw-bold mb-0" style="letter-spacing: -0.02em;">{{ $booking->passenger_name }}</h1>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="font-family: monospace; font-size: 1.1rem; background: rgba(255,255,255,0.15); padding: 0.25rem 0.75rem; border-radius: 8px;">
                                        {{ $booking->ticket_number }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ticket Body --}}
                        <div class="p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="text-secondary small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">route</div>
                                    <div class="fw-bold fs-5 mb-3" style="color: var(--tn-blue);">{{ $booking->trip?->route?->name }}</div>

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">date</div>
                                            <div class="fw-semibold"><i class="bi bi-calendar3 text-secondary me-1"></i>{{ $booking->trip?->departure_date?->format('M d, Y') }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">time</div>
                                            <div class="fw-semibold"><i class="bi bi-clock text-secondary me-1"></i>{{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : '' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">bus</div>
                                            <div class="fw-semibold"><i class="bi bi-bus-front text-secondary me-1"></i>{{ $booking->trip?->bus?->plate_number ?? '-' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">seat</div>
                                            <div class="fw-bold fs-4" style="color: var(--tn-blue);">{{ $booking->seat_number }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">passenger</div>
                                            <div class="fw-semibold">{{ $booking->passenger_name }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">phone</div>
                                            <div class="fw-semibold">{{ $booking->phone_number }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Details --}}
                            @php $payment = $booking->latestPayment; @endphp
                            @if ($payment)
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-1"></i>payment details</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3 col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">amount paid</div>
                                            <div class="fw-bold fs-5" style="color: #059669;">{{ number_format($payment->amount, 2) }}</div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">method</div>
                                            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">status</div>
                                            <span class="badge" style="background: #d1fae5; color: #065f46; border: none; font-weight: 600;">
                                                <i class="bi bi-check-circle"></i> paid
                                            </span>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-secondary small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.04em;">reference</div>
                                            <div class="fw-semibold" style="font-family: monospace;">{{ $payment->transaction_reference ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4 pt-3 border-top">
                                <div class="text-secondary small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Save your ticket number <strong class="fw-bold" style="font-family: monospace;">{{ $booking->ticket_number }}</strong> to look up your ticket later.
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="window.print()" class="btn btn-outline-secondary btn-icon">
                                        <i class="bi bi-printer"></i><span>print</span>
                                    </button>
                                    <a href="{{ route('tickets.lookup') }}" class="btn btn-primary btn-icon">
                                        <i class="bi bi-ticket-perforated"></i><span>my tickets</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lookup Reminder --}}
                    <div class="text-center mt-4 reveal" style="transition-delay: 0.2s;">
                        <div class="d-inline-flex align-items-center gap-2 p-3" style="background: var(--tn-panel); border: 1px solid var(--tn-line); border-radius: 12px;">
                            <i class="bi bi-phone fs-5 text-secondary"></i>
                            <span class="text-secondary small">
                                To find your tickets later, visit <a href="{{ route('tickets.lookup') }}" class="fw-semibold" style="color: var(--tn-blue);">Find My Ticket</a> and enter your phone number <strong>{{ $booking->phone_number }}</strong>.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media print {
            .site-nav, footer, .theme-toggle, .lang-pills, .alert, .btn { display: none !important; }
            .content-panel { box-shadow: none !important; border: 2px solid #000 !important; }
            [style*="background: var(--tn-gradient)"] { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .badge { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
@endsection
