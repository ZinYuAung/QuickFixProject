<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'provider_id',
        'rating',
        'comment',
        'provider_hidden_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'deleted_at' => 'datetime',
            'provider_hidden_at' => 'datetime',
        ];
    }

    // Each review belongs to a specific booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}