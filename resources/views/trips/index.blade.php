@extends('layouts.app')

@section('title', 'Trips')
@section('subtitle', 'Schedule departures and monitor booked seats.')

@section('actions')
    <a href="{{ route('trips.create') }}" class="btn btn-primary btn-icon">
        <i class="bi bi-plus-lg"></i><span>Schedule Trip</span>
    </a>
@endsection

@section('content')
    <section class="panel reveal">
        <div class="p-3 border-bottom" style="background: #fafbfc; border-radius: 12px 12px 0 0;">
            <form class="row g-2" method="get">
                <div class="col-md-8 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search bus, route, or status">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-icon">
                        <span>Search</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Route</th>
                    <th>Bus</th>
                    <th>Departure</th>
                    <th>Status</th>
                    <th class="text-end">Seats</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($trips as $trip)
                    <tr>
                        <td class="fw-semibold">{{ $trip->route?->name }}</td>
                        <td>{{ $trip->bus?->plate_number }}</td>
                        <td>{{ $trip->departure_date?->format('M d, Y') }} {{ substr($trip->departure_time, 0, 5) }}</td>
                        <td><span class="badge badge-soft">{{ ucfirst($trip->status) }}</span></td>
                        <td class="text-end">{{ $trip->booked_seats }}/{{ $trip->bus?->capacity ?? 0 }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('trips.edit', $trip) }}" class="btn btn-sm btn-outline-primary" title="Edit trip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('trips.destroy', $trip) }}" onsubmit="return confirm('Delete this trip? Related bookings will also be removed.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete trip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No trips found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $trips->links() }}
        </div>
    </section>
@endsection
