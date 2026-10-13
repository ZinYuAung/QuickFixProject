<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification
{
    use Queueable;

    public Payment $payment;
    public string $event;
    public ?string $reason;

    public function __construct(
        Payment $payment,
        string $event,
        ?string $reason = null
    ) {
        $this->payment = $payment;
        $this->event = $event;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $bookingId = $this->payment->booking_id;

        switch ($this->event) {

            /*
            |--------------------------------------------------------------------------
            | Customer submitted payment
            | Provider receives this notification
            |--------------------------------------------------------------------------
            */
            case 'payment_submitted':

                return [
                    'type' => 'payment_submitted',
                    'title' => 'New Payment Submitted',
                    'message' =>
                        'A customer has submitted a payment for Booking #' .
                        $bookingId .
                        '. Please review the payment.',
                    'booking_id' => $bookingId,
                    'payment_id' => $this->payment->id,
                ];


            /*
            |--------------------------------------------------------------------------
            | Provider verified payment
            | Customer receives this notification
            |--------------------------------------------------------------------------
            */
            case 'payment_verified':

                return [
                    'type' => 'payment_verified',
                    'title' => 'Payment Verified',
                    'message' =>
                        'Your payment for Booking #' .
                        $bookingId .
                        ' has been verified successfully.',
                    'booking_id' => $bookingId,
                    'payment_id' => $this->payment->id,
                ];


            /*
            |--------------------------------------------------------------------------
            | Provider rejected payment
            | Customer receives this notification
            |--------------------------------------------------------------------------
            */
            case 'payment_rejected':

                return [
                    'type' => 'payment_rejected',
                    'title' => 'Payment Rejected',
                    'message' =>
                        'Your payment for Booking #' .
                        $bookingId .
                        ' was rejected. Reason: ' .
                        ($this->reason ?? 'No reason provided.'),
                    'booking_id' => $bookingId,
                    'payment_id' => $this->payment->id,
                    'rejection_reason' => $this->reason,
                ];

            /*
            |--------------------------------------------------------------------------
            | Provider submitted refund proof
            | Customer receives this notification
            |--------------------------------------------------------------------------
            */
            case 'refund_submitted':

                return [
                    'type' => 'refund_submitted',

                    'title' => 'Refund Proof Submitted',

                    'message' =>
                        'The provider has submitted refund proof for Booking #' .
                        $bookingId .
                        '. Please review and confirm the refund.',

                    'booking_id' => $bookingId,

                    'payment_id' => $this->payment->id,
                ];


            /*
            |--------------------------------------------------------------------------
            | Customer says refund was not received
            | Provider receives this notification
            |--------------------------------------------------------------------------
            */
            case 'refund_not_received':

                return [
                    'type' => 'refund_not_received',

                    'title' => 'Refund Not Received',

                    'message' =>
                        'The customer reported that the refund for Booking #' .
                        $bookingId .
                        ' was not received. Please review and submit a new refund proof.',

                    'booking_id' => $bookingId,

                    'payment_id' => $this->payment->id,
                ];


            /*
            |--------------------------------------------------------------------------
            | Customer confirmed refund
            | Provider receives this notification
            |--------------------------------------------------------------------------
            */
            case 'refund_received':

                return [
                    'type' => 'refund_received',

                    'title' => 'Refund Confirmed',

                    'message' =>
                        'The customer has confirmed receiving the refund for Booking #' .
                        $bookingId .
                        '.',

                    'booking_id' => $bookingId,

                    'payment_id' => $this->payment->id,
                ];
            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */
            default:

                return [
                    'type' => 'payment_update',
                    'title' => 'Payment Updated',
                    'message' =>
                        'Your payment for Booking #' .
                        $bookingId .
                        ' has been updated.',
                    'booking_id' => $bookingId,
                    'payment_id' => $this->payment->id,
                ];
        }
    }
}
