@extends('layouts.app')

@section('title', 'Routes')
@section('subtitle', 'Define destinations, distance, and ticket pricing.')

@section('actions')
    <a href="{{ route('routes.create') }}" class="btn btn-primary btn-icon">
        <i class="bi bi-plus-lg"></i><span>Add Route</span>
    </a>
@endsection

@section('content')
    <section class="panel reveal">
        <div class="p-3 border-bottom" style="background: #fafbfc; border-radius: 12px 12px 0 0;">
            <form class="row g-2" method="get">
                <div class="col-md-8 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search origin or destination">
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
                    <th class="text-end">Distance</th>
                    <th class="text-end">Ticket Price</th>
                    <th class="text-end">Trips</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($routes as $route)
                    <tr>
                        <td class="fw-semibold">{{ $route->name }}</td>
                        <td class="text-end">{{ number_format($route->distance, 2) }} km</td>
                        <td class="text-end">{{ number_format($route->ticket_price, 2) }}</td>
                        <td class="text-end">{{ $route->trips_count }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('routes.edit', $route) }}" class="btn btn-sm btn-outline-primary" title="Edit route">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('routes.destroy', $route) }}" onsubmit="return confirm('Delete this route? Related trips and bookings will also be removed.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete route">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">No routes found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $routes->links() }}
        </div>
    </section>
@endsection
