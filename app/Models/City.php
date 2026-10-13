<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

     public function serviceAreas()
    {
        return $this->hasMany(
            ServiceArea::class,
            'city_id'
        );
    }

     public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            ServiceArea::class,
            'city_id',
            'area_id',
            'id',
            'id'
        );
    }

     public function providers()
    {
        return $this->hasManyThrough(
            Provider::class,
            ServiceArea::class,
            'city_id',
            'area_id',
            'id',
            'id'
        );
    }
public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            ServiceArea::class,
            'city_id',
            'area_id',
            'id',
            'id'
        );
    }

}
