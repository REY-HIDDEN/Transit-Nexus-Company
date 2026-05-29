<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $buses = Bus::query()
            ->withCount('trips')
            ->when($search, function ($query) use ($search) {
                $query->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->latest('bus_id')
            ->paginate(10)
            ->withQueryString();

        return view('buses.index', compact('buses', 'search'));
    }

    public function create()
    {
        return view('buses.create', ['bus' => new Bus, 'statuses' => $this->statuses()]);
    }

    public function store(Request $request)
    {
        Bus::create($this->validated($request));

        return redirect()->route('buses.index')->with('success', 'Bus added successfully.');
    }

    public function edit(Bus $bus)
    {
        return view('buses.edit', ['bus' => $bus, 'statuses' => $this->statuses()]);
    }

    public function update(Request $request, Bus $bus)
    {
        $bus->update($this->validated($request, $bus));

        return redirect()->route('buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();

        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully.');
    }

    private function validated(Request $request, ?Bus $bus = null): array
    {
        return $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('buses', 'plate_number')->ignore($bus?->bus_id, 'bus_id'),
            ],
            'agency' => ['nullable', 'string', 'max:120'],
            'capacity' => ['required', 'integer', 'min:1', 'max:200'],
            'driver_name' => ['required', 'string', 'max:120'],
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
        ]);
    }

    private function statuses(): array
    {
        return [
            'active' => 'Active',
            'maintenance' => 'Maintenance',
            'inactive' => 'Inactive',
        ];
    }
}
