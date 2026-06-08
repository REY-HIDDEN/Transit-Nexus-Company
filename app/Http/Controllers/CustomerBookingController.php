<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerBookingController extends Controller
{
    public function create(Trip $trip)
    {
        $trip->load(['bus', 'route']);

        return view('public-bookings.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $trip->load('bus');

        $data = $request->validate([
            'passenger_name' => ['required', 'string', 'max:140'],
            'phone_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{7,30}$/'],
            'seat_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['seat_number'] = $this->resolveSeat($trip, $data['seat_number'] ?? null);
        $data['user_id'] = $request->user()?->id; // null for guests, set for logged-in users
        $data['trip_id'] = $trip->trip_id;
        $data['booking_date'] = now()->toDateString();
        $data['payment_status'] = 'pending';
        $data['booking_status'] = 'pending';
        $data['ticket_number'] = Booking::generateTicketNumber();

        $booking = Booking::create($data);

        return redirect()->route('payment.create', $booking)->with('success', "Ticket {$booking->ticket_number} created – please complete payment.");
    }

    private function resolveSeat(Trip $trip, ?int $requestedSeat): int
    {
        $capacity = $trip->bus?->capacity ?? 0;

        if ($capacity < 1 || $trip->reservedBookings()->count() >= $capacity) {
            throw ValidationException::withMessages([
                'trip_id' => 'This trip is full or no bus assigned.',
            ]);
        }

        if ($requestedSeat !== null) {
            if ($requestedSeat > $capacity) {
                throw ValidationException::withMessages([
                    'seat_number' => "Seat {$requestedSeat} is outside this bus capacity.",
                ]);
            }

            if ($trip->reservedBookings()->where('seat_number', $requestedSeat)->exists()) {
                throw ValidationException::withMessages([
                    'seat_number' => "Seat {$requestedSeat} is already booked for this trip.",
                ]);
            }

            return $requestedSeat;
        }

        $takenSeats = $trip->reservedBookings()
            ->pluck('seat_number')
            ->map(fn ($seat) => (int) $seat)
            ->all();

        for ($seat = 1; $seat <= $capacity; $seat++) {
            if (! in_array($seat, $takenSeats, true)) {
                return $seat;
            }
        }

        throw ValidationException::withMessages([
            'trip_id' => 'No seats are available for this trip.',
        ]);
    }
}
