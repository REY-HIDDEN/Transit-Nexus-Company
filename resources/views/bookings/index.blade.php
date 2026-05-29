@extends('layouts.app')

@section('title', __('messages.bookings'))
@section('subtitle', __('messages.generate_ticket'))

@section('actions')
    <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-icon">
        <i class="bi bi-plus-lg"></i><span>{{ __('messages.new_booking') }}</span>
    </a>
@endsection

@section('content')
    <section class="panel reveal">
        <div class="p-3 border-bottom" style="background: #fafbfc; border-radius: 12px 12px 0 0;">
            <form class="row g-2" method="get">
                <div class="col-md-8 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="{{ __('messages.search_passenger') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-icon">
                        <span>{{ __('messages.search') }}</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>{{ __('messages.ticket') }}</th>
                    <th>{{ __('messages.passenger') }}</th>
                    <th>{{ __('messages.trip') }}</th>
                    <th class="text-end">{{ __('messages.seat') }}</th>
                    <th>{{ __('messages.payment') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th class="text-end no-print">actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="fw-semibold" style="font-family: monospace;">{{ $booking->ticket_number }}</td>
                        <td>
                            <div class="fw-semibold">{{ $booking->passenger_name }}</div>
                            <div class="small text-secondary">{{ $booking->phone_number }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $booking->trip?->route?->name }}</div>
                            <div class="small text-secondary">
                                {{ $booking->trip?->departure_date?->format('M d, Y') }} {{ $booking->trip?->departure_time ? substr($booking->trip->departure_time, 0, 5) : '' }}
                            </div>
                        </td>
                        <td class="text-end fw-bold">{{ $booking->seat_number }}</td>
                        <td>
                            @php $ps = $booking->payment_status === 'paid' ? ['#d1fae5', '#065f46'] : ($booking->payment_status === 'pending' ? ['#fef3c7', '#92400e'] : ['#fee2e2', '#991b1b']); @endphp
                            <span class="badge" style="background: {{ $ps[0] }}; color: {{ $ps[1] }}; border: none;">{{ ucfirst($booking->payment_status) }}</span>
                        </td>
                        <td>
                            @php
                                $bs = $booking->booking_status;
                                $bsStyle = $bs === 'confirmed' ? ['#d1fae5', '#065f46'] : ($bs === 'pending' ? ['#fef3c7', '#92400e'] : ['#fee2e2', '#991b1b']);
                            @endphp
                            <span class="badge" style="background: {{ $bsStyle[0] }}; color: {{ $bsStyle[1] }}; border: none;">{{ ucfirst($booking->booking_status) }}</span>
                        </td>
                        <td class="text-end no-print">
                            <div class="d-inline-flex gap-1">
                                @if ($booking->booking_status === 'pending')
                                    <form method="post" action="{{ route('bookings.approve', $booking) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="approve_booking" style="background: #059669; color: white; border: none; border-radius: 6px; padding: 0.35rem 0.7rem;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary" title="view_ticket">
                                    <i class="bi bi-ticket-perforated"></i>
                                </a>
                                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-primary" title="edit_booking">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('bookings.destroy', $booking) }}" onsubmit="return confirm('Are you sure you want to delete this booking?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="delete_booking">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-ticket text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                            <p class="text-secondary mt-2 mb-0">{{ __('messages.no_bookings') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $bookings->links() }}
        </div>
    </section>
@endsection
