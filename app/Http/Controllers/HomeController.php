<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $trips = Trip::query()
            ->with(['bus', 'route'])
            ->whereIn('status', ['scheduled', 'boarding'])
            ->whereDate('departure_date', '>=', now()->toDateString())
            ->when($search, function ($query) use ($search) {
                $query->whereHas('route', function ($route) use ($search) {
                    $route->where('origin', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%");
                });
            })
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->take(8)
            ->get();

        return view('home', compact('trips', 'search'));
    }
}
