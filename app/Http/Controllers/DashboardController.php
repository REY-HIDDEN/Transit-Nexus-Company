<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses = Bus::count();
        $totalRoutes = Route::count();
        $totalTrips = Trip::count();
        $totalBookings = Booking::where('booking_status', 'confirmed')->count();

        $revenue = Booking::paid()
            ->where('booking_status', 'confirmed')
            ->with('trip.route')
            ->get()
            ->sum(fn (Booking $booking) => (float) ($booking->trip?->route?->ticket_price ?? 0));

        $recentBookings = Booking::with(['trip.bus', 'trip.route'])
            ->latest('booking_id')
            ->take(8)
            ->get();

        $upcomingTrips = Trip::with(['bus', 'route'])
            ->whereDate('departure_date', '>=', now()->toDateString())
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->take(6)
            ->get();

        $routes = Route::withCount('trips')->latest('route_id')->get();

        $allTrips = Trip::with(['bus', 'route'])
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->get();

        return view('dashboard', compact(
            'totalBuses',
            'totalRoutes',
            'totalTrips',
            'totalBookings',
            'revenue',
            'recentBookings',
            'upcomingTrips',
            'routes',
            'allTrips',
        ));
    }
}
