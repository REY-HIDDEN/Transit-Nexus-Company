<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function lookupForm()
    {
        return view('public-bookings.lookup');
    }

    public function lookup(Request $request)
    {
        $data = $request->validate([
            'phone_number' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{7,30}$/'],
        ]);

        $bookings = Booking::with(['trip.bus', 'trip.route'])
            ->where('phone_number', $data['phone_number'])
            ->latest('booking_id')
            ->paginate(10);

        return view('public-bookings.lookup', compact('bookings'));
    }

    public function receipt(Booking $booking)
    {
        $booking->load(['trip.bus', 'trip.route', 'payments']);

        return view('public-bookings.receipt', compact('booking'));
    }
}
