<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatusHistory extends Model
{
    //
   protected $table = 'booking_status_history';

    protected $fillable = [
        'booking_id',
        'status',
        'updated_by',
        'note',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Who updated the status (linked to users table)
    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
