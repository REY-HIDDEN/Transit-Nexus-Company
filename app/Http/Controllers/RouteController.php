<?php

namespace App\Http\Controllers;

use App\Models\Route as TransportRoute;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();

        $routes = TransportRoute::query()
            ->withCount('trips')
            ->when($search, function ($query) use ($search) {
                $query->where('origin', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%");
            })
            ->latest('route_id')
            ->paginate(10)
            ->withQueryString();

        return view('routes.index', compact('routes', 'search'));
    }

    public function create()
    {
        return view('routes.create', ['route' => new TransportRoute]);
    }

    public function store(Request $request)
    {
        TransportRoute::create($this->validated($request));

        return redirect()->route('routes.index')->with('success', 'Route added successfully.');
    }

    public function edit(TransportRoute $route)
    {
        return view('routes.edit', compact('route'));
    }

    public function update(Request $request, TransportRoute $route)
    {
        $route->update($this->validated($request));

        return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
    }

    public function destroy(TransportRoute $route)
    {
        $route->delete();

        return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'origin' => ['required', 'string', 'max:120'],
            'destination' => ['required', 'string', 'max:120', 'different:origin'],
            'distance' => ['required', 'numeric', 'min:0.1', 'max:99999'],
            'ticket_price' => ['required', 'numeric', 'min:0', 'max:99999999'],
        ]);
    }
}
