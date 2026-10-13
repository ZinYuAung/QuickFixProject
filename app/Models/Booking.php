<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $table = 'bookings';

    protected $fillable = [
        'customer_id',
        'provider_id',
        'area_id',
        'booking_type',
        'scheduled_at',
        'address',
        'latitude',
        'longitude',
        'chosen_services',
        'total_amount',
        'response_deadline',
        'status',
        'customer_note',
        'problem_photo',
        'cancelled_by',
'cancellation_reason',
'cancelled_at',
'rating_penalty',
    ];

    protected function casts(): array
    {
        return [
            'chosen_services' => 'array', // Automatically casts JSON column to array
            'scheduled_at' => 'datetime',
            'response_deadline' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'total_amount' => 'decimal:2',
            'cancelled_at' => 'datetime',
            'rating_penalty' => 'decimal:2',
        ];
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function provider() {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

   public function area()
{
    return $this->belongsTo(ServiceArea::class, 'area_id');
}

    public function statusHistory() {
        return $this->hasMany(BookingStatusHistory::class, 'booking_id');
    }

    public function chats() {
        return $this->hasMany(Chat::class, 'booking_id');
    }

    public function payment() {
        return $this->hasOne(Payment::class, 'booking_id');
    }
    public function review()
{
    return $this->hasOne(Review::class, 'booking_id');
}
}
