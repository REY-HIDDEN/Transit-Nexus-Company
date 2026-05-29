@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="origin" class="form-label">Origin</label>
        <input type="text" id="origin" name="origin" class="form-control" value="{{ old('origin', $route->origin) }}" required>
    </div>
    <div class="col-md-6">
        <label for="destination" class="form-label">Destination</label>
        <input type="text" id="destination" name="destination" class="form-control" value="{{ old('destination', $route->destination) }}" required>
    </div>
    <div class="col-md-6">
        <label for="distance" class="form-label">Distance</label>
        <div class="input-group">
            <input type="number" step="0.01" min="0.1" id="distance" name="distance" class="form-control" value="{{ old('distance', $route->distance) }}" required>
            <span class="input-group-text">km</span>
        </div>
    </div>
    <div class="col-md-6">
        <label for="ticket_price" class="form-label">Ticket Price</label>
        <input type="number" step="0.01" min="0" id="ticket_price" name="ticket_price" class="form-control" value="{{ old('ticket_price', $route->ticket_price) }}" required>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('routes.index') }}" class="btn btn-outline-secondary btn-icon">
        <i class="bi bi-x-lg"></i><span>Cancel</span>
    </a>
    <button class="btn btn-primary btn-icon">
        <i class="bi bi-check2"></i><span>Save Route</span>
    </button>
</div>
