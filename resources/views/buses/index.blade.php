@extends('layouts.app')

@section('title', 'Buses')
@section('subtitle', 'Manage fleet capacity, drivers, and availability.')

@section('actions')
    <a href="{{ route('buses.create') }}" class="btn btn-primary btn-icon">
        <i class="bi bi-plus-lg"></i><span>Add Bus</span>
    </a>
@endsection

@section('content')
    <section class="panel reveal">
        <div class="p-3 border-bottom" style="background: #fafbfc; border-radius: 12px 12px 0 0;">
            <form class="row g-2" method="get">
                <div class="col-md-8 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search plate, driver, or status">
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
                    <th>Plate</th>
                    <th>Driver</th>
                    <th>Agency</th>
                    <th class="text-end">Capacity</th>
                    <th>Status</th>
                    <th class="text-end">Trips</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($buses as $bus)
                    <tr>
                        <td class="fw-semibold">{{ $bus->plate_number }}</td>
                        <td>{{ $bus->driver_name }}</td>
                        <td>{{ $bus->agency ?? '-' }}</td>
                        <td class="text-end">{{ $bus->capacity }}</td>
                        <td><span class="badge badge-soft">{{ ucfirst($bus->status) }}</span></td>
                        <td class="text-end">{{ $bus->trips_count }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('buses.edit', $bus) }}" class="btn btn-sm btn-outline-primary" title="Edit bus">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('buses.destroy', $bus) }}" onsubmit="return confirm('Delete this bus? Related trips and bookings will also be removed.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete bus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No buses found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $buses->links() }}
        </div>
    </section>
@endsection
