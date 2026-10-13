<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    //
    protected $table = 'providers';

    protected $fillable = [
        'user_id',
        'area_id',
        'bio',
        'experience_years',
        'rating',
        'availability_status',
        'approve_by',
        'approval_status',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'rating' => 'decimal:2',
    ];
    // Inverse relationship to the central user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function serviceArea()
    {
        return $this->belongsTo(
            ServiceArea::class,
            'area_id'
        );
    }

    public function approvedBy()
    {
        return $this->belongsTo(
            Admin::class,
            'approve_by'
        );
    }
    // Bookings assigned to this provider
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    // Services provided via pivot table
    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_services', 'provider_id', 'service_id');
    }
    public function locations()
    {
        return $this->hasMany(
            ProviderLocation::class,
            'provider_id'
        );
    }
}
