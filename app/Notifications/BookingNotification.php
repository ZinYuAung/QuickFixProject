<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification
{
    use Queueable;

    public Booking $booking;
    public string $event;

    public function __construct(
        Booking $booking,
        string $event
    ) {
        $this->booking = $booking;
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        switch ($this->event) {

           case 'booking_created':

    // Load payment information for this booking
    $this->booking->loadMissing('payment');

    $payment = $this->booking->payment;

    $serviceAmount = $payment
        ? (float) $payment->service_amount
        : (float) $this->booking->total_amount;

    $prepaidAmount = $payment
        ? (float) $payment->prepaid_amount
        : 0;

    $prepaymentRequired = $prepaidAmount > 0;

    return [
        'type' => 'booking_created',

        'title' => 'New Booking Request',

        'message' => $prepaymentRequired
            ? 'You have received a new booking request. A prepayment of '
                . number_format($prepaidAmount, 2)
                . ' MMK is required.'
            : 'You have received a new booking request. No prepayment is required.',

        'booking_id' => $this->booking->id,

        // Payment information
        'service_amount' => $serviceAmount,

        'prepayment_required' => $prepaymentRequired,

        'prepaid_amount' => $prepaidAmount,

        'payment_method' => $payment?->payment_method,

        'payment_status' => $payment?->payment_status,
    ];

            case 'booking_accepted':
                return [
                    'type' => 'booking_accepted',
                    'title' => 'Booking Accepted',
                    'message' => 'Your booking has been accepted by the provider.',
                    'booking_id' => $this->booking->id,
                ];

            case 'booking_rejected':
                return [
                    'type' => 'booking_rejected',
                    'title' => 'Booking Rejected',
                    'message' => 'Your booking was rejected by the provider.',
                    'booking_id' => $this->booking->id,
                ];

            case 'on_the_way':
                return [
                    'type' => 'on_the_way',
                    'title' => 'Provider Is On The Way',
                    'message' => 'Your provider is on the way.',
                    'booking_id' => $this->booking->id,
                ];

            case 'arrived':
                return [
                    'type' => 'arrived',
                    'title' => 'Provider Has Arrived',
                    'message' => 'Your provider has arrived at the service location.',
                    'booking_id' => $this->booking->id,
                ];

            case 'in_progress':
                return [
                    'type' => 'in_progress',
                    'title' => 'Service Started',
                    'message' => 'Your service is now in progress.',
                    'booking_id' => $this->booking->id,
                ];

           case 'completed':
    return [
        'type' => 'completed',
        'title' => 'Service Completed',
        'message' => 'Your service has been completed.',
        'booking_id' => $this->booking->id,
    ];

case 'customer_cancelled':
    return [
        'type' => 'customer_cancelled',
        'title' => 'Booking Cancelled by Customer',
        'message' => 'The customer has cancelled the booking.',
        'booking_id' => $this->booking->id,
    ];

case 'provider_cancelled':
    return [
        'type' => 'provider_cancelled',
        'title' => 'Booking Cancelled by Provider',
        'message' => 'The provider has cancelled your booking.',
        'booking_id' => $this->booking->id,
    ];


            case 'timeout':
                return [
                    'type' => 'timeout',
                    'title' => 'Booking Timed Out',
                    'message' => 'The provider did not respond to your booking request.',
                    'booking_id' => $this->booking->id,
                ];

            default:
                return [
                    'type' => 'booking_update',
                    'title' => 'Booking Updated',
                    'message' => 'Your booking has been updated.',
                    'booking_id' => $this->booking->id,
                ];
        }
    }
}