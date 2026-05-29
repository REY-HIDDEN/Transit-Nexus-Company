<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $primaryKey = 'route_id';

    protected $fillable = [
        'origin',
        'destination',
        'distance',
        'ticket_price',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'ticket_price' => 'decimal:2',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'route_id', 'route_id');
    }

    public function getNameAttribute(): string
    {
        return "{$this->origin} to {$this->destination}";
    }
}
