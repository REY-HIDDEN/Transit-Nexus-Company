@csrf

<div class="row g-3">
    <div class="col-12">
        <label for="trip_id" class="form-label">{{ __('messages.trip') }}</label>
        <select id="trip_id" name="trip_id" class="form-select" required>
            <option value="">{{ __('messages.select_trip') }}</option>
            @foreach ($trips as $trip)
                <option value="{{ $trip->trip_id }}" @selected((int) old('trip_id', $booking->trip_id) === $trip->trip_id)>
                    {{ $trip->departure_date?->format('M d, Y') }} {{ substr($trip->departure_time, 0, 5) }}
                    - {{ $trip->route?->name }}
                    - {{ $trip->bus?->plate_number }}
                    - {{ $trip->remainingSeats() }} {{ __('messages.seats_left') }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="passenger_name" class="form-label">{{ __('messages.passenger_name') }}</label>
        <input type="text" id="passenger_name" name="passenger_name" class="form-control" value="{{ old('passenger_name', $booking->passenger_name) }}" required>
    </div>
    <div class="col-md-6">
        <label for="phone_number" class="form-label">{{ __('messages.phone_number') }}</label>
        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $booking->phone_number) }}" required>
    </div>
    <div class="col-md-4">
        <label for="seat_number" class="form-label">{{ __('messages.seat_number') }}</label>
        <input type="number" id="seat_number" name="seat_number" min="1" class="form-control" value="{{ old('seat_number', $booking->seat_number) }}" placeholder="{{ __('messages.auto_assign') }}">
    </div>
    <div class="col-md-4">
        <label for="booking_date" class="form-label">{{ __('messages.booking_date_label') }}</label>
        <input type="date" id="booking_date" name="booking_date" class="form-control" value="{{ old('booking_date', $booking->booking_date?->format('Y-m-d') ?? now()->toDateString()) }}">
    </div>
    <div class="col-md-4">
        <label for="payment_status" class="form-label">{{ __('messages.payment_status') }}</label>
        <select id="payment_status" name="payment_status" class="form-select" required>
            @foreach ($paymentStatuses as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_status', $booking->payment_status ?: 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="booking_status" class="form-label">{{ __('messages.booking_status') }}</label>
        <select id="booking_status" name="booking_status" class="form-select">
            @foreach ($bookingStatuses as $value => $label)
                <option value="{{ $value }}" @selected(old('booking_status', $booking->booking_status ?: 'confirmed') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-icon">
        <i class="bi bi-x-lg"></i><span>{{ __('messages.cancel') }}</span>
    </a>
    <button class="btn btn-primary btn-icon">
        <i class="bi bi-check2"></i><span>{{ __('messages.save_booking') }}</span>
    </button>
</div>
