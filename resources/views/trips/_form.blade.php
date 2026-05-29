@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="bus_id" class="form-label">Bus</label>
        <select id="bus_id" name="bus_id" class="form-select" required>
            <option value="">Select bus</option>
            @foreach ($buses as $bus)
                <option value="{{ $bus->bus_id }}" @selected((int) old('bus_id', $trip->bus_id) === $bus->bus_id)>
                    {{ $bus->plate_number }} - {{ $bus->driver_name }} ({{ $bus->capacity }} seats)
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="route_id" class="form-label">Route</label>
        <select id="route_id" name="route_id" class="form-select" required>
            <option value="">Select route</option>
            @foreach ($routes as $route)
                <option value="{{ $route->route_id }}" @selected((int) old('route_id', $trip->route_id) === $route->route_id)>
                    {{ $route->name }} - {{ number_format($route->ticket_price, 2) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="departure_date" class="form-label">Departure Date</label>
        <input type="date" id="departure_date" name="departure_date" class="form-control" value="{{ old('departure_date', $trip->departure_date?->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-4">
        <label for="departure_time" class="form-label">Departure Time</label>
        <input type="time" id="departure_time" name="departure_time" class="form-control" value="{{ old('departure_time', $trip->departure_time ? substr($trip->departure_time, 0, 5) : '') }}" required>
    </div>
    <div class="col-md-4">
        <label for="arrival_time" class="form-label">Arrival Time</label>
        <input type="time" id="arrival_time" name="arrival_time" class="form-control" value="{{ old('arrival_time', $trip->arrival_time ? substr($trip->arrival_time, 0, 5) : '') }}">
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $trip->status ?: 'scheduled') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('trips.index') }}" class="btn btn-outline-secondary btn-icon">
        <i class="bi bi-x-lg"></i><span>Cancel</span>
    </a>
    <button class="btn btn-primary btn-icon">
        <i class="bi bi-check2"></i><span>Save Trip</span>
    </button>
</div>
