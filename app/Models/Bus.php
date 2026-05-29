<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $primaryKey = 'bus_id';

    protected $fillable = [
        'plate_number',
        'capacity',
        'driver_name',
        'agency',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'bus_id', 'bus_id');
    }
}
