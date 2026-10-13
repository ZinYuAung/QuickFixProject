<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderLocation extends Model
{
    //
    protected $table = 'provider_locations';

    protected $fillable = [
        'provider_id',
        'booking_id',
        'address',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'recorded_at' => 'datetime',
        ];
    }

    public function provider() {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
