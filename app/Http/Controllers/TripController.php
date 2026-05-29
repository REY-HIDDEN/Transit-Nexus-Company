<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Route as TransportRoute;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $trips = Trip::query()
            ->with(['bus', 'route'])
            ->withCount(['confirmedBookings as booked_seats'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('bus', fn ($bus) => $bus->where('plate_number', 'like', "%{$search}%"))
                    ->orWhereHas('route', function ($route) use ($search) {
                        $route->where('origin', 'like', "%{$search}%")
                            ->orWhere('destination', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('departure_date')
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();

        return view('trips.index', compact('trips', 'search'));
    }

    public function create()
    {
        return view('trips.create', $this->formData(new Trip));
    }

    public function store(Request $request)
    {
        Trip::create($this->validated($request));

        return redirect()->route('trips.index')->with('success', 'Trip scheduled successfully.');
    }

    public function edit(Trip $trip)
    {
        return view('trips.edit', $this->formData($trip));
    }

    public function update(Request $request, Trip $trip)
    {
        $trip->update($this->validated($request));

        return redirect()->route('trips.index')->with('success', 'Trip updated successfully.');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'bus_id' => ['required', 'exists:buses,bus_id'],
            'route_id' => ['required', 'exists:routes,route_id'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_time' => ['nullable', 'date_format:H:i'],
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
        ]);
    }

    private function formData(Trip $trip): array
    {
        return [
            'trip' => $trip,
            'buses' => Bus::orderBy('plate_number')->get(),
            'routes' => TransportRoute::orderBy('origin')->orderBy('destination')->get(),
            'statuses' => $this->statuses(),
        ];
    }

    private function statuses(): array
    {
        return [
            'scheduled' => 'Scheduled',
            'boarding' => 'Boarding',
            'departed' => 'Departed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }
}
