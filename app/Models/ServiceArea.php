<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    //
    protected $table = 'service_areas';

    protected $fillable = [
        'city_id',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function customers() {
        return $this->hasMany(Customer::class, 'area_id');
    }

    public function providers() {
        return $this->hasMany(Provider::class, 'area_id');
    }
     public function bookings()
    {
        return $this->hasMany(Booking::class, 'area_id');
    }
     public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'service_area_service',
            'service_area_id',
            'service_id'
        );
    }
}
