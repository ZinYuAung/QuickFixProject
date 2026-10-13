<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'area_id',
        'address',
    ];

    // Inverse relationship to the central user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Bookings made by this customer profile
    public function bookings() {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    //adding for service area
    public function serviceArea() {
        return $this->belongsTo(ServiceArea::class, 'area_id');
    }
}
