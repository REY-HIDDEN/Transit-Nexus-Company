<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $primaryKey = 'trip_id';

    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_date',
        'departure_time',
        'arrival_time',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
    ];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'bus_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'trip_id', 'trip_id');
    }

    public function confirmedBookings(): HasMany
    {
        return $this->bookings()->where('booking_status', 'confirmed');
    }

    public function reservedBookings(): HasMany
    {
        return $this->bookings()->whereIn('booking_status', ['pending', 'confirmed']);
    }

    public function bookedSeats(): int
    {
        return $this->reservedBookings()->count();
    }

    public function remainingSeats(): int
    {
        return max(0, ($this->bus?->capacity ?? 0) - $this->bookedSeats());
    }

    public function isFull(): bool
    {
        return $this->remainingSeats() <= 0;
    }

    public function nextAvailableSeat(): ?int
    {
        $capacity = $this->bus?->capacity ?? 0;
        $taken = $this->reservedBookings()->pluck('seat_number')->map(fn ($seat) => (int) $seat)->all();

        for ($seat = 1; $seat <= $capacity; $seat++) {
            if (! in_array($seat, $taken, true)) {
                return $seat;
            }
        }

        return null;
    }
}
