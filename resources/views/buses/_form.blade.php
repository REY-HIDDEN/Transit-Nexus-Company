@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="plate_number" class="form-label">Plate Number</label>
        <input type="text" id="plate_number" name="plate_number" class="form-control" value="{{ old('plate_number', $bus->plate_number) }}" required>
    </div>
    <div class="col-md-6">
        <label for="driver_name" class="form-label">Driver Name</label>
        <input type="text" id="driver_name" name="driver_name" class="form-control" value="{{ old('driver_name', $bus->driver_name) }}" required>
    </div>
    <div class="col-md-6">
        <label for="agency" class="form-label">Agency</label>
        <input type="text" id="agency" name="agency" class="form-control" value="{{ old('agency', $bus->agency) }}" placeholder="e.g. Kigali Express">
    </div>
    <div class="col-md-6">
        <label for="capacity" class="form-label">Capacity</label>
        <input type="number" id="capacity" name="capacity" min="1" max="200" class="form-control" value="{{ old('capacity', $bus->capacity) }}" required>
    </div>
    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $bus->status ?: 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('buses.index') }}" class="btn btn-outline-secondary btn-icon">
        <i class="bi bi-x-lg"></i><span>Cancel</span>
    </a>
    <button class="btn btn-primary btn-icon">
        <i class="bi bi-check2"></i><span>Save Bus</span>
    </button>
</div>
