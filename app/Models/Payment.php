<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'service_amount',
        'prepaid_amount',
        'remaining_amount',

        'payment_method',
        'payment_status',

        // Final Payment
        'final_payment_status',
        'final_verified_at',
        'final_slip_image',
        'final_rejection_reason',

        'slip_image',
        'verified_by',
        'verified_at',
        'rejection_reason',

        // Refund
        'refund_status',
        'refunded_amount',
        'refunded_at',
        'refund_reason',
        'refund_slip_image',
'refund_submitted_at',
'refund_customer_verified_at',
'refund_customer_response',
    ];

    protected function casts(): array
    {
        return [
            'service_amount' => 'decimal:2',
            'prepaid_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'refunded_amount' => 'decimal:2',

            'verified_at' => 'datetime',
            'final_verified_at' => 'datetime',
            'refunded_at' => 'datetime',

            'refund_submitted_at' => 'datetime',
'refund_customer_verified_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(
            Booking::class,
            'booking_id'
        );
    }

    // Admin/User who verified the payment
    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }
}
