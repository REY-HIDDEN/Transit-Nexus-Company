<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'user_id',
        'trip_id',
        'passenger_name',
        'phone_number',
        'seat_number',
        'booking_date',
        'payment_status',
        'booking_status',
        'ticket_number',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'seat_number' => 'integer',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public static function generateTicketNumber(): string
    {
        do {
            $ticketNumber = 'TNX-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (self::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }
}
