<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'insurance',
        'insurance_fee',
        'status',
        'transaction_reference',
        'paid_at',
        'verified_at',
        'verified_by',
        'notes',
    ];

    protected $casts = [
        'insurance' => 'boolean',
        'amount' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
}
