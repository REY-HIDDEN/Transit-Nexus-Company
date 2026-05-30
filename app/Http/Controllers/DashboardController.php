<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route as TransportRoute;
use App\Models\Trip;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses = Bus::count();
        $totalRoutes = TransportRoute::count();
        $totalTrips = Trip::count();
        $totalBookings = Booking::where('booking_status', 'confirmed')->count();

        $revenue = Booking::query()->paid()
            ->where('booking_status', 'confirmed')
            ->join('trips', 'bookings.trip_id', '=', 'trips.trip_id')
            ->join('routes', 'trips.route_id', '=', 'routes.route_id')
            ->sum('routes.ticket_price');

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

        $routes = TransportRoute::withCount('trips')->latest('route_id')->get();

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
