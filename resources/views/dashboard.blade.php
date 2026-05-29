@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('subtitle', __('messages.dashboard_subtitle'))

@section('actions')
    <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-icon">
        <i class="bi bi-plus-lg"></i><span>{{ __('messages.new_booking') }}</span>
    </a>
@endsection

@section('content')
    <div class="row g-4 mb-4 reveal-stagger">
        <div class="col-md-6 col-xl-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">{{ __('messages.buses') }}</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($totalBuses) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-bus-front"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">{{ __('messages.routes') }}</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($totalRoutes) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-signpost-2"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">{{ __('messages.trips') }}</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($totalTrips) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-calendar2-week"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary small text-uppercase" style="letter-spacing: 0.04em; font-size: 0.72rem;">{{ __('messages.revenue') }}</div>
                        <div class="fs-3 fw-bold mt-1">{{ number_format($revenue, 2) }}</div>
                    </div>
                    <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <section class="panel reveal">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #fafbfc; border-radius: 14px 14px 0 0;">
                    <h2 class="h6 mb-0 fw-bold">{{ __('messages.recent_bookings') }}</h2>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary btn-icon">
                        <span>{{ __('messages.view_all') }}</span><i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>{{ __('messages.ticket') }}</th>
                            <th>{{ __('messages.passenger') }}</th>
                            <th>{{ __('messages.trip') }}</th>
                            <th class="text-end">{{ __('messages.seat') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($recentBookings as $booking)
                            <tr>
                                <td class="fw-semibold" style="font-family: monospace;">{{ $booking->ticket_number }}</td>
                                <td>{{ $booking->passenger_name }}</td>
                                <td>{{ $booking->trip?->route?->name ?? __('messages.route_unavailable') }}</td>
                                <td class="text-end fw-semibold">{{ $booking->seat_number }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                    {{ __('messages.no_bookings_yet') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <div class="col-xl-5">
            <section class="panel reveal">
                <div class="p-3 border-bottom" style="background: #fafbfc; border-radius: 14px 14px 0 0;">
                    <h2 class="h6 mb-0 fw-bold">{{ __('messages.upcoming_trips') }}</h2>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($upcomingTrips as $trip)
                        <div class="list-group-item px-3 py-3" style="border-left: 3px solid #2563eb;">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $trip->route?->name }}</div>
                                    <div class="small text-secondary mt-1">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $trip->departure_date?->format('M d, Y') }}
                                        <i class="bi bi-clock ms-2 me-1"></i>{{ substr($trip->departure_time, 0, 5) }}
                                    </div>
                                </div>
                                <span class="badge badge-soft align-self-start">{{ ucfirst($trip->status) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-secondary">
                            <i class="bi bi-calendar-x d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                            {{ __('messages.no_upcoming_trips') }}
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12">
            <section class="panel reveal">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #fafbfc; border-radius: 14px 14px 0 0;">
                    <h2 class="h6 mb-0 fw-bold">{{ __('messages.routes') }}</h2>
                    <a href="{{ route('routes.index') }}" class="btn btn-sm btn-outline-secondary btn-icon">
                        <span>{{ __('messages.view_all') }}</span><i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>{{ __('messages.route') }}</th>
                            <th>{{ __('messages.origin') }}</th>
                            <th>{{ __('messages.destination') }}</th>
                            <th class="text-end">{{ __('messages.distance') }}</th>
                            <th class="text-end">{{ __('messages.price') }}</th>
                            <th class="text-end">{{ __('messages.trips') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($routes as $route)
                            <tr>
                                <td class="fw-semibold">{{ $route->name }}</td>
                                <td>{{ $route->origin }}</td>
                                <td>{{ $route->destination }}</td>
                                <td class="text-end">{{ number_format($route->distance, 2) }}</td>
                                <td class="text-end fw-semibold">{{ number_format($route->ticket_price, 2) }}</td>
                                <td class="text-end">{{ $route->trips_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    <i class="bi bi-signpost-2 d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                    No routes available.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12">
            <section class="panel reveal">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #fafbfc; border-radius: 14px 14px 0 0;">
                    <h2 class="h6 mb-0 fw-bold">{{ __('messages.trips') }}</h2>
                    <a href="{{ route('trips.index') }}" class="btn btn-sm btn-outline-secondary btn-icon">
                        <span>{{ __('messages.view_all') }}</span><i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>{{ __('messages.route') }}</th>
                            <th>{{ __('messages.bus') }}</th>
                            <th>{{ __('messages.bus_agency') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.departure') }}</th>
                            <th>{{ __('messages.arrival') }}</th>
                            <th class="text-end">{{ __('messages.seats_left') }}</th>
                            <th>{{ __('messages.status') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($allTrips as $trip)
                            <tr>
                                <td class="fw-semibold">{{ $trip->route?->name }}</td>
                                <td>{{ $trip->bus?->plate_number }}</td>
                                <td>{{ $trip->bus?->agency ?? '-' }}</td>
                                <td>{{ $trip->departure_date?->format('M d, Y') }}</td>
                                <td>{{ $trip->departure_time ? substr($trip->departure_time, 0, 5) : '' }}</td>
                                <td>{{ $trip->arrival_time ? substr($trip->arrival_time, 0, 5) : '' }}</td>
                                <td class="text-end">{{ $trip->remainingSeats() }}</td>
                                <td><span class="badge badge-soft">{{ ucfirst($trip->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary py-4">
                                    <i class="bi bi-calendar-x d-block mb-2" style="font-size: 1.5rem; opacity: 0.3;"></i>
                                    No trips available.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
