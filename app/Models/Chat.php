<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $table = 'chats';

    protected $fillable = [
        'booking_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // The user who sent the message
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
    // The user who receives the message
public function receiver()
{
    return $this->belongsTo(User::class, 'receiver_id');
}
}
