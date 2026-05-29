<?php

namespace App\Http\Controllers;

use App\Mail\BookingApproved;
use App\Mail\BookingDeclined;
use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $bookings = Booking::query()
            ->with(['trip.bus', 'trip.route'])
            ->when($search, function ($query) use ($search) {
                $query->where('passenger_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('ticket_number', 'like', "%{$search}%")
                    ->orWhereHas('trip.bus', fn ($bus) => $bus->where('plate_number', 'like', "%{$search}%"))
                    ->orWhereHas('trip.route', function ($route) use ($search) {
                        $route->where('origin', 'like', "%{$search}%")
                            ->orWhere('destination', 'like', "%{$search}%");
                    });
            })
            ->latest('booking_id')
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', compact('bookings', 'search'));
    }

    public function create()
    {
        return view('bookings.create', $this->formData(new Booking));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $trip = Trip::with('bus')->findOrFail($data['trip_id']);
        $data['seat_number'] = $this->resolveSeat($trip, $data['seat_number'] ?? null);
        $data['booking_date'] = $data['booking_date'] ?? now()->toDateString();
        $data['booking_status'] = 'confirmed';
        $data['ticket_number'] = Booking::generateTicketNumber();

        $booking = Booking::create($data);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking created and ticket generated.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['trip.bus', 'trip.route']);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('bookings.edit', $this->formData($booking));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $this->validated($request, $booking);
        $trip = Trip::with('bus')->findOrFail($data['trip_id']);
        $data['seat_number'] = $this->resolveSeat($trip, $data['seat_number'] ?? $booking->seat_number, $booking);

        $booking->update($data);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function approve(Booking $booking)
    {
        if ($booking->booking_status === 'confirmed') {
            return redirect()->route('bookings.index')->with('error', 'This booking is already confirmed.');
        }

        $booking->update(['booking_status' => 'confirmed']);

        if ($booking->user_id && $booking->user?->email) {
            Mail::to($booking->user->email)->send(new BookingApproved($booking));
        }

        return redirect()->route('bookings.index')->with('success', "Booking {$booking->ticket_number} approved and user notified.");
    }

    public function decline(Booking $booking)
    {
        if ($booking->booking_status === 'cancelled') {
            return redirect()->route('bookings.index')->with('error', 'This booking has already been cancelled.');
        }

        $booking->update(['booking_status' => 'cancelled']);

        if ($booking->user_id && $booking->user?->email) {
            Mail::to($booking->user->email)->send(new BookingDeclined($booking));
        }

        return redirect()->route('bookings.index')->with('success', "Booking {$booking->ticket_number} declined and user notified.");
    }

    private function validated(Request $request, ?Booking $booking = null): array
    {
        $tripId = (int) $request->input('trip_id');

        return $request->validate([
            'trip_id' => ['required', 'exists:trips,trip_id'],
            'passenger_name' => ['required', 'string', 'max:140'],
            'phone_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{7,30}$/'],
            'seat_number' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('bookings', 'seat_number')
                    ->where(fn ($query) => $query->where('trip_id', $tripId))
                    ->ignore($booking?->booking_id, 'booking_id'),
            ],
            'booking_date' => ['nullable', 'date'],
            'payment_status' => ['required', Rule::in(array_keys($this->paymentStatuses()))],
            'booking_status' => ['sometimes', Rule::in(array_keys($this->bookingStatuses()))],
        ]);
    }

    private function resolveSeat(Trip $trip, ?int $requestedSeat, ?Booking $ignoreBooking = null): int
    {
        $capacity = $trip->bus?->capacity ?? 0;

        if ($capacity < 1) {
            throw ValidationException::withMessages([
                'trip_id' => 'This trip does not have a valid bus capacity.',
            ]);
        }

        $reservedCount = $trip->reservedBookings()
            ->when($ignoreBooking, fn ($query) => $query->whereKeyNot($ignoreBooking->booking_id))
            ->count();

        if ($reservedCount >= $capacity && ($ignoreBooking?->booking_status !== 'confirmed')) {
            throw ValidationException::withMessages([
                'trip_id' => 'This trip is fully booked.',
            ]);
        }

        if ($requestedSeat !== null) {
            if ($requestedSeat > $capacity) {
                throw ValidationException::withMessages([
                    'seat_number' => "Seat {$requestedSeat} is outside this bus capacity.",
                ]);
            }

            $seatTaken = $trip->reservedBookings()
                ->when($ignoreBooking, fn ($query) => $query->whereKeyNot($ignoreBooking->booking_id))
                ->where('seat_number', $requestedSeat)
                ->exists();

            if ($seatTaken) {
                throw ValidationException::withMessages([
                    'seat_number' => "Seat {$requestedSeat} is already booked for this trip.",
                ]);
            }

            return $requestedSeat;
        }

        $takenSeats = $trip->reservedBookings()
            ->when($ignoreBooking, fn ($query) => $query->whereKeyNot($ignoreBooking->booking_id))
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

    private function formData(Booking $booking): array
    {
        $trips = Trip::with(['bus', 'route'])
            ->where(function ($query) use ($booking) {
                $query->whereIn('status', ['scheduled', 'boarding'])
                    ->when($booking->trip_id, fn ($query) => $query->orWhere('trip_id', $booking->trip_id));
            })
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        return [
            'booking' => $booking,
            'trips' => $trips,
            'paymentStatuses' => $this->paymentStatuses(),
            'bookingStatuses' => $this->bookingStatuses(),
        ];
    }

    private function paymentStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];
    }

    private function bookingStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
        ];
    }
}
