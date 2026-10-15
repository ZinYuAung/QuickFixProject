<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\PaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * =====================================================
     * CUSTOMER - PAYMENT LIST
     * =====================================================
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            abort(403, 'Customer account not found.');
        }

        $bookings = Booking::with([
            'provider.user',
            'area.city',
            'payment',
        ])
            ->where('customer_id', $customer->id)
            ->latest()
            ->paginate(10);

        return view(
            'customer.payment',
            compact(
                'bookings',
                'customer'
            )
        );
    }


    /**
     * =====================================================
     * CUSTOMER - PAYMENT PAGE
     * =====================================================
     *
     * Payment stages:
     *
     * 1. Prepayment
     *    Booking accepted
     *    Total >= 30,000
     *    → 10% prepayment
     *
     * 2. Final payment
     *    Booking completed
     *    → remaining amount
     */
    public function show(int $id)
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            abort(403, 'Customer account not found.');
        }

        $booking = Booking::with([
            'provider.user',
            'area.city',
            'payment',
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $serviceAmount = (float) $booking->total_amount;

        $payment = $booking->payment;

        $prepaymentThreshold = 30000;

        $prepaymentRate = 10;

        $prepaidAmount = (float) (
            $payment?->prepaid_amount ?? 0
        );

        $remainingAmount = (float) (
            $payment?->remaining_amount ?? 0
        );


        // =================================================
        // CALCULATE REQUIRED PREPAYMENT
        // =================================================

        $requiredPrepayment = 0;

        if (
            $serviceAmount >=
            $prepaymentThreshold
        ) {
            $requiredPrepayment = round(
                $serviceAmount *
                ($prepaymentRate / 100),
                2
            );
        }


        // =================================================
        // DETERMINE PAYMENT STAGE
        // =================================================

        $paymentStage = 'none';

        /*
        |--------------------------------------------------------------------------
        | PREPAYMENT
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status === 'accepted' &&
            $requiredPrepayment > 0 &&
            (
                !$payment ||
                $payment->payment_status !== 'verified'
            )
        ) {
            $paymentStage = 'prepayment';
        }


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT
        |--------------------------------------------------------------------------
        */

        if (
            $booking->status === 'completed'
        ) {
            $paymentStage = 'final';
        }


        /*
        |--------------------------------------------------------------------------
        | ALREADY FULLY PAID
        |--------------------------------------------------------------------------
        */

        if (
            $payment &&
            $payment->final_payment_status === 'verified'
        ) {
            $paymentStage = 'completed';
        }


        return view(
            'customer.payment-form',
            compact(
                'booking',
                'customer',
                'serviceAmount',
                'prepaymentRate',
                'prepaidAmount',
                'requiredPrepayment',
                'remainingAmount',
                'payment',
                'paymentStage'
            )
        );
    }



    //  * =====================================================
//  * CUSTOMER - PAYMENT STATUS
//  * =====================================================
//  *
//  * Used by AJAX polling.
//  */
    public function status(int $id)
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            abort(403, 'Customer account not found.');
        }

        $booking = Booking::where(
            'id',
            $id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->firstOrFail();

        $payment = Payment::where(
            'booking_id',
            $booking->id
        )->first();


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $paymentStatus =
            $payment?->payment_status;

        $finalPaymentStatus =
            $payment?->final_payment_status;


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT
        |--------------------------------------------------------------------------
        */



        // A payment is submitted when its submission timestamp exists.
// This works for both COD and KBZPay.
        $finalPaymentSubmitted =
            !empty($payment?->final_payment_submitted_at);

        $finalSlipUploaded =
            !empty($payment?->final_slip_image);


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT REQUIRED
        |--------------------------------------------------------------------------
        |
        | Booking is completed and there is still
        | money remaining to pay.
        |
        */

        $remainingAmount =
            (float) (
                $payment?->remaining_amount ?? 0
            );

        $finalPaymentRequired =
            $booking->status === 'completed' &&
            $remainingAmount > 0 &&
            $finalPaymentStatus !== 'verified';


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT PENDING VERIFICATION
        |--------------------------------------------------------------------------
        */


        $finalPaymentPending =
            $booking->status === 'completed' &&
            $finalPaymentStatus === 'pending' &&
            $finalPaymentSubmitted;



        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT VERIFIED
        |--------------------------------------------------------------------------
        */

        $finalPaymentVerified =
            $booking->status === 'completed' &&
            $finalPaymentStatus === 'verified';


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            /*
            |--------------------------------------------------
            | PAYMENT RECORD
            |--------------------------------------------------
            */

            'payment_exists' =>
                (bool) $payment,


            /*
            |--------------------------------------------------
            | PREPAYMENT
            |--------------------------------------------------
            */

            'status' =>
                $paymentStatus,

            'slip_uploaded' =>
                !empty($payment?->slip_image),

            'payment_method' =>
                $payment?->payment_method,

            'prepaid_amount' =>
                $payment?->prepaid_amount,

            'remaining_amount' =>
                $remainingAmount,

            'rejection_reason' =>
                $payment?->rejection_reason,


            /*
            |--------------------------------------------------
            | FINAL PAYMENT
            |--------------------------------------------------
            */

            'final_payment_status' =>
                $finalPaymentStatus,

            'final_rejection_reason' =>
                $payment?->final_rejection_reason,

            'final_slip_image' =>
                $payment?->final_slip_image,

            'final_slip_uploaded' =>
                $finalSlipUploaded,

            'final_payment_required' =>
                $finalPaymentRequired,

            'final_payment_pending' =>
                $finalPaymentPending,

            'final_payment_verified' =>
                $finalPaymentVerified,

            'final_payment_submitted' =>
                $finalPaymentSubmitted,

            'final_payment_submitted_at' =>
                $payment?->final_payment_submitted_at,

            /*
            |--------------------------------------------------
            | BOOKING
            |--------------------------------------------------
            */

            'booking_status' =>
                $booking->status,


            /*
            |--------------------------------------------------
            | REVIEW
            |--------------------------------------------------
            |
            | We will use this later when the Review system
            | is connected.
            |
            */

            'review_required' =>
                $finalPaymentVerified,

        ]);
    }


    /**
     * =====================================================
     * CUSTOMER - SUBMIT PAYMENT
     * =====================================================
     *
     * Handles:
     *
     * PREPAYMENT
     *     accepted + >= 30,000
     *
     * FINAL PAYMENT
     *     completed booking
     *
     * Same payments record is reused.
     */
    public function store(
        Request $request,
        int $bookingId
    ) {
        $customer = Auth::user()->customer;

        if (!$customer) {
            abort(403, 'Customer account not found.');
        }


        // =================================================
        // GET BOOKING
        // =================================================

        $booking = Booking::with([
            'provider.user',
            'payment',
        ])
            ->where(
                'id',
                $bookingId
            )
            ->where(
                'customer_id',
                $customer->id
            )
            ->firstOrFail();


        // =================================================
        // GET PAYMENT
        // =================================================

        $payment = $booking->payment;

        $serviceAmount = (float) $booking->total_amount;


        // =================================================
        // PREPAYMENT CALCULATION
        // =================================================

        $prepaymentThreshold = 30000;

        $prepaymentRate = 10;

        $requiredPrepayment = 0;

        if (
            $serviceAmount >=
            $prepaymentThreshold
        ) {
            $requiredPrepayment = round(
                $serviceAmount *
                ($prepaymentRate / 100),
                2
            );
        }


        // =================================================
        // DETERMINE PAYMENT STAGE
        // =================================================

        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT
        |--------------------------------------------------------------------------
        |
        | Once booking is completed, customer pays
        | the remaining amount.
        |
        */

        $isFinalPayment =
            $booking->status === 'completed';


        /*
        |--------------------------------------------------------------------------
        | PREPAYMENT
        |--------------------------------------------------------------------------
        */

        $isPrepayment =
            $booking->status === 'accepted' &&
            $requiredPrepayment > 0;


        /*
        |--------------------------------------------------------------------------
        | NO PAYMENT REQUIRED
        |--------------------------------------------------------------------------
        |
        | Booking is not accepted yet and there is no
        | payment stage available.
        |
        */

        if (
            !$isFinalPayment &&
            !$isPrepayment
        ) {

            return back()
                ->with(
                    'error',
                    'Payment is not required at this stage.'
                );
        }


        // =================================================
        // FINAL PAYMENT ALREADY VERIFIED
        // =================================================

        if (
            $isFinalPayment &&
            $payment &&
            $payment->final_payment_status === 'verified'
        ) {

            return back()
                ->with(
                    'error',
                    'The final payment has already been verified.'
                );
        }


        // =================================================
        // PREPAYMENT ALREADY VERIFIED
        // =================================================

        if (
            $isPrepayment &&
            $payment &&
            $payment->payment_status === 'verified'
        ) {

            return back()
                ->with(
                    'error',
                    'The prepayment has already been verified.'
                );
        }


        // =================================================
        // VALIDATION
        // =================================================

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:cod,kbzpay',
            ],

            'slip_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);


        // =================================================
        // PREVENT DUPLICATE PREPAYMENT SUBMISSION
        // =================================================



        if (
            $isPrepayment &&
            $payment &&
            $payment->payment_status === 'pending' &&
            !empty($payment->slip_image)
        ) {
            return back()
                ->with(
                    'error',
                    'Your prepayment has already been submitted. Please wait for provider verification.'
                );
        }



        // =================================================
        // PREVENT DUPLICATE FINAL PAYMENT SUBMISSION
        // =================================================


        if (
            $isFinalPayment &&
            $payment &&
            $payment->final_payment_status === 'pending' &&
            !empty($payment->final_payment_submitted_at)
        ) {
            return back()
                ->with(
                    'error',
                    'Your final payment has already been submitted. Please wait for provider verification.'
                );
        }



        // =================================================
        // CASH ON DELIVERY
        // =================================================

        if (
            $validated['payment_method'] === 'cod'
        ) {

            /*
            |--------------------------------------------------------------------------
            | PREPAYMENT COD
            |--------------------------------------------------------------------------
            |
            | We do not allow COD for the required
            | 10% prepayment.
            |
            */

            if ($isPrepayment) {

                return back()
                    ->withErrors([
                        'payment_method' =>
                            'A KBZPay prepayment is required before the service starts.',
                    ])
                    ->withInput();
            }


            /*
            |--------------------------------------------------------------------------
            | FINAL COD PAYMENT
            |--------------------------------------------------------------------------
            */

            $remainingAmount =
                $payment
                ? (float) $payment->remaining_amount
                : $serviceAmount;


            $payment = Payment::updateOrCreate(
                [
                    'booking_id' =>
                        $booking->id,
                ],
                [
                    'service_amount' =>
                        $serviceAmount,

                    'remaining_amount' =>
                        $remainingAmount,

                    'payment_method' =>
                        'cod',

                    'final_payment_status' =>
                        'pending',

                    'final_payment_submitted_at' =>
                        now(),

                    'final_slip_image' =>
                        null,

                    'final_rejection_reason' =>
                        null,
                ]
            );


            // =================================================
            // NOTIFY PROVIDER
            // =================================================

            $providerUser =
                $booking->provider?->user;

            if ($providerUser) {

                $providerUser->notify(
                    new PaymentNotification(
                        $payment,
                        'payment_submitted'
                    )
                );
            }


            return redirect()
                ->route(
                    'customer.payments'
                )
                ->with(
                    'success',
                    'Cash payment has been submitted. Please wait for provider verification.'
                );
        }


        // =================================================
        // KBZPAY
        // =================================================

        if (
            $validated['payment_method'] ===
            'kbzpay'
        ) {

            // =================================================
            // SLIP REQUIRED
            // =================================================

            if (
                !$request->hasFile(
                    'slip_image'
                )
            ) {

                return back()
                    ->withErrors([
                        'slip_image' =>
                            'Please upload your KBZPay payment slip.',
                    ])
                    ->withInput();
            }


            // =================================================
            // PREPAYMENT
            // =================================================

            if ($isPrepayment) {

                $prepaidAmount =
                    $requiredPrepayment;

                $remainingAmount = round(
                    $serviceAmount -
                    $prepaidAmount,
                    2
                );


                // =============================================
                // DELETE OLD REJECTED SLIP
                // =============================================

                if (
                    $payment &&
                    $payment->payment_status ===
                    'rejected' &&
                    $payment->slip_image
                ) {

                    Storage::disk(
                        'public'
                    )->delete(
                            $payment->slip_image
                        );
                }


                // =============================================
                // STORE NEW SLIP
                // =============================================

                $slipPath =
                    $request
                        ->file('slip_image')
                        ->store(
                            'payment-slips',
                            'public'
                        );


                // =============================================
                // SAVE PREPAYMENT
                // =============================================

                $payment =
                    Payment::updateOrCreate(
                        [
                            'booking_id' =>
                                $booking->id,
                        ],
                        [
                            'service_amount' =>
                                $serviceAmount,

                            'prepaid_amount' =>
                                $prepaidAmount,

                            'remaining_amount' =>
                                $remainingAmount,

                            'payment_method' =>
                                'kbzpay',

                            'payment_status' =>
                                'pending',

                            'slip_image' =>
                                $slipPath,

                            'verified_by' =>
                                null,

                            'verified_at' =>
                                null,

                            'rejection_reason' =>
                                null,

                        ]
                    );


                // =============================================
                // NOTIFY PROVIDER
                // =============================================

                $providerUser =
                    $booking->provider?->user;

                if ($providerUser) {

                    $providerUser->notify(
                        new PaymentNotification(
                            $payment,
                            'payment_submitted'
                        )
                    );
                }


                return redirect()
                    ->route(
                        'customer.payments'
                    )
                    ->with(
                        'success',
                        '10% prepayment slip submitted successfully. Please wait for provider verification.'
                    );
            }


            // =================================================
            // FINAL PAYMENT
            // =================================================

            if ($isFinalPayment) {

                $remainingAmount =
                    $payment
                    ? (float) $payment->remaining_amount
                    : $serviceAmount;


                if (
                    $remainingAmount <= 0
                ) {

                    return back()
                        ->with(
                            'error',
                            'There is no remaining amount to pay.'
                        );
                }


                // =============================================
                // DELETE OLD REJECTED FINAL SLIP
                // =============================================

                if (
                    $payment &&
                    $payment->final_payment_status ===
                    'rejected' &&
                    $payment->final_slip_image
                ) {

                    Storage::disk(
                        'public'
                    )->delete(
                            $payment->final_slip_image
                        );
                }


                // =============================================
                // STORE FINAL SLIP
                // =============================================

                $finalSlipPath =
                    $request
                        ->file('slip_image')
                        ->store(
                            'payment-slips/final',
                            'public'
                        );


                // =============================================
                // SAVE FINAL PAYMENT
                // =============================================

                $payment =
                    Payment::updateOrCreate(
                        [
                            'booking_id' =>
                                $booking->id,
                        ],
                        [
                            'service_amount' =>
                                $serviceAmount,

                            'remaining_amount' =>
                                $remainingAmount,

                            'payment_method' =>
                                'kbzpay',

                            'final_payment_status' =>
                                'pending',

                            'final_payment_submitted_at' => now(),

                            'final_slip_image' =>
                                $finalSlipPath,

                            'final_rejection_reason' =>
                                null,
                        ]
                    );


                // =============================================
                // NOTIFY PROVIDER
                // =============================================

                $providerUser =
                    $booking->provider?->user;

                if ($providerUser) {

                    $providerUser->notify(
                        new PaymentNotification(
                            $payment,
                            'payment_submitted'
                        )
                    );
                }


                return redirect()
                    ->route(
                        'customer.payments'
                    )
                    ->with(
                        'success',
                        'Final payment slip submitted successfully. Please wait for provider verification.'
                    );
            }
        }


        // =================================================
        // INVALID METHOD
        // =================================================

        return back()
            ->withErrors([
                'payment_method' =>
                    'Invalid payment method.',
            ])
            ->withInput();
    }
}