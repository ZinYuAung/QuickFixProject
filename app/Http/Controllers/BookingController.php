<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\PaymentNotification;
use App\Models\Review;

class BookingController extends Controller
{
    /**
     * =====================================================
     * PROVIDER - BOOKINGS LIST
     * =====================================================
     */
    public function index()
    {
        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET PROVIDER BOOKINGS
        // =====================================================

        $bookings = Booking::with([
            'customer.user',
            'area.city',
            'statusHistory',
            'payment',
        ])
            ->where('provider_id', $provider->id)
            ->latest()
            ->get();


        // =====================================================
        // BOOKING COUNTS
        // =====================================================

        $totalBookings = $bookings->count();

        $pendingBookings = $bookings
            ->where('status', 'pending')
            ->count();

        $acceptedBookings = $bookings
            ->where('status', 'accepted')
            ->count();

        $completedBookings = $bookings
            ->where('status', 'completed')
            ->count();


        // =====================================================
        // RETURN VIEW
        // =====================================================

        return view(
            'provider.bookings',
            compact(
                'provider',
                'bookings',
                'totalBookings',
                'pendingBookings',
                'acceptedBookings',
                'completedBookings'
            )
        );
    }


    /**
     * =====================================================
     * SHOW BOOKING DETAIL
     * =====================================================
     */
    public function show(string $id)
    {
        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING
        // =====================================================

        $booking = Booking::with([
            'customer.user',
            'area.city',
            'statusHistory.updater',
            'payment',
        ])
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // GET CHOSEN SERVICES
        // =====================================================

        $serviceIds = collect(
            $booking->chosen_services ?? []
        )
            ->flatten()
            ->filter()
            ->values();


        $services = Service::whereIn(
            'id',
            $serviceIds
        )->get();


        // =====================================================
        // RETURN DETAIL PAGE
        // =====================================================

        return view(
            'provider.booking-detail',
            compact(
                'provider',
                'booking',
                'services'
            )
        );
    }


    /**
     * =====================================================
     * PROVIDER - ACCEPT BOOKING
     *
     * pending → accepted
     *
     * IMPORTANT:
     * Accepting a booking DOES NOT mean prepayment is paid.
     *
     * If total >= 30,000:
     *     10% prepayment is REQUIRED.
     *
     * The customer must pay it separately.
     *
     * payment_status:
     *     pending = payment still required
     *     verified = payment has actually been verified
     * =====================================================
     */
    public function accept(string $id)
    {
        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING WITH PAYMENT
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // ONLY PENDING CAN BE ACCEPTED
        // =====================================================

        if ($booking->status !== 'pending') {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'This booking can no longer be accepted.',
            ], 422);
        }


        // =====================================================
        // CHECK RESPONSE DEADLINE
        // =====================================================

        if (
            $booking->response_deadline &&
            now()->greaterThan(
                $booking->response_deadline
            )
        ) {

            DB::transaction(function () use ($booking) {

                $booking->update([
                    'status' => 'timeout',
                ]);

                $this->createStatusHistory(
                    $booking,
                    'timeout',
                    'Provider response time expired.'
                );
            });


            $this->notifyCustomer(
                $booking,
                'timeout'
            );


            return response()->json([
                'success' => false,
                'status' => 'timeout',
                'message' =>
                    'The response time for this booking has expired.',
            ], 422);
        }


        // =====================================================
        // ACCEPT BOOKING
        // =====================================================

        DB::transaction(function () use ($booking) {

            // =================================================
            // UPDATE BOOKING STATUS
            // =================================================

            $booking->update([
                'status' => 'accepted',
            ]);


            // =================================================
            // PREPARE PAYMENT
            // =================================================

            $payment = $booking->payment;


            if ($payment) {

                // =================================================
                // TOTAL SERVICE AMOUNT
                // =================================================

                $totalAmount = (float) $booking->total_amount;


                // =================================================
                // PREPAYMENT RULE
                //
                // < 30,000
                //     → no prepayment
                //
                // >= 30,000
                //     → 10% prepayment required
                // =================================================

                $prepaymentThreshold = 30000;

                $prepaidAmount = 0;


                if ($totalAmount >= $prepaymentThreshold) {

                    $prepaidAmount = round(
                        $totalAmount * 0.10,
                        2
                    );
                }


                // =================================================
                // REMAINING AMOUNT
                //
                // This is the amount after the required
                // prepayment.
                // =================================================

                $remainingAmount = round(
                    $totalAmount - $prepaidAmount,
                    2
                );


                // =================================================
                // PAYMENT STATUS
                //
                // VERY IMPORTANT
                //
                // pending does NOT mean paid.
                //
                // pending = customer must pay
                //
                // verified = payment was actually checked
                // and approved.
                // =================================================

                $paymentStatus =
                    $prepaidAmount > 0
                    ? 'pending'
                    : 'verified';


                // =================================================
                // UPDATE PAYMENT
                // =================================================

                $payment->update([

                    // Total service price
                    'service_amount' =>
                        $totalAmount,

                    // Amount customer is REQUIRED
                    // to prepay
                    'prepaid_amount' =>
                        $prepaidAmount,

                    // Amount remaining after required
                    // prepayment
                    'remaining_amount' =>
                        $remainingAmount,

                    // IMPORTANT:
                    // This is NOT "paid amount".
                    //
                    // pending = customer still needs to pay
                    // verified = no prepayment required
                    // or payment has actually been verified
                    'payment_status' =>
                        $paymentStatus,

                    // Final payment does not start
                    // until service is completed.
                    'final_payment_status' =>
                        'not_required',

                    // Clear old rejection information
                    'rejection_reason' =>
                        null,

                    'final_rejection_reason' =>
                        null,
                ]);
            }


            // =================================================
            // STATUS HISTORY
            // =================================================

            $this->createStatusHistory(
                $booking,
                'accepted'
            );
        });


        // =====================================================
        // NOTIFY CUSTOMER
        // =====================================================

        $this->notifyCustomer(
            $booking,
            'booking_accepted'
        );


        // =====================================================
        // AJAX RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,
            'status' => 'accepted',
            'message' =>
                'Booking accepted successfully. Prepayment is required separately if applicable.',
        ]);
    }
    /**
     * =====================================================
     * PROVIDER - VERIFY PREPAYMENT
     *
     * Customer uploads payment slip
     *        ↓
     * Provider verifies
     *
     * payment_status:
     *     pending → verified
     * =====================================================
     */
    public function verifyPayment(string $id)
    {
        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET PROVIDER BOOKING WITH PAYMENT
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // GET PAYMENT
        // =====================================================

        $payment = $booking->payment;


        // =====================================================
        // PAYMENT MUST EXIST
        // =====================================================

        if (!$payment) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Payment information was not found.'
                );
        }


        // =====================================================
        // CHECK PREPAYMENT IS REQUIRED
        // =====================================================

        if (
            (float) $payment->prepaid_amount <= 0
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This booking does not require prepayment.'
                );
        }


        // =====================================================
        // PAYMENT MUST STILL BE PENDING
        // =====================================================

        if (
            $payment->payment_status !== 'pending'
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This payment has already been processed.'
                );
        }


        // =====================================================
        // CUSTOMER MUST UPLOAD PAYMENT SLIP
        // =====================================================

        if (
            empty($payment->slip_image)
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'The customer has not uploaded a payment slip yet.'
                );
        }


        // =====================================================
        // VERIFY PAYMENT
        // =====================================================

        DB::transaction(function () use ($payment) {

            $payment->update([

                // Payment approved
                'payment_status' =>
                    'verified',

                // Provider who verified it
                'verified_by' =>
                    auth()->id(),

                // Verification time
                'verified_at' =>
                    now(),

                // Clear previous rejection
                'rejection_reason' =>
                    null,
            ]);
        });


        // =====================================================
        // REDIRECT
        // =====================================================

        return redirect()
            ->route(
                'provider.bookings.show',
                $booking->id
            )
            ->with(
                'success',
                'Prepayment has been verified successfully.'
            );
    }

    /**
     * =====================================================
     * PROVIDER - REJECT BOOKING
     *
     * pending → cancelled
     * =====================================================
     */
    public function reject(
        Request $request,
        string $id
    ) {
        // =====================================================
        // GET PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING
        // =====================================================

        $booking = Booking::where(
            'provider_id',
            $provider->id
        )->findOrFail($id);


        // =====================================================
        // ONLY PENDING CAN BE REJECTED
        // =====================================================

        if ($booking->status !== 'pending') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This booking can no longer be rejected.'
                );
        }


        // =====================================================
        // VALIDATE NOTE
        // =====================================================

        $validated = $request->validate([
            'rejection_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        $rejectionNote =
            $validated['rejection_note'] ?? null;


        if (
            trim($rejectionNote ?? '') === ''
        ) {

            $rejectionNote =
                'Booking rejected by provider.';
        }


        // =====================================================
        // UPDATE BOOKING
        // =====================================================

        DB::transaction(function () use ($booking, $rejectionNote) {

            $booking->update([
                'status' => 'cancelled',
            ]);


            $this->createStatusHistory(
                $booking,
                'cancelled',
                $rejectionNote
            );
        });


        // =====================================================
        // NOTIFY CUSTOMER
        // =====================================================

        $this->notifyCustomer(
            $booking,
            'booking_rejected'
        );


        // =====================================================
// AJAX RESPONSE
// =====================================================

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'status' => 'cancelled',
                'booking_id' => $booking->id,
                'message' => 'Booking rejected successfully.',
            ]);
        }


        // =====================================================
// NORMAL REDIRECT
// =====================================================

        return redirect()
            ->route(
                'provider.bookings.show',
                $booking->id
            )
            ->with(
                'success',
                'Booking rejected successfully.'
            );
    }


    /**
     * =====================================================
     * PROVIDER - CANCEL BOOKING
     *
     * accepted / on_the_way / arrived → cancelled
     *
     * If verified prepayment exists:
     *     refund_status = pending
     *     Provider must refund customer
     * =====================================================
     */
    public function cancel(
        Request $request,
        string $id
    ) {
        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING WITH PAYMENT
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // ONLY ACTIVE BOOKINGS CAN BE CANCELLED
        // =====================================================

        if (
            !in_array(
                $booking->status,
                [
                    'accepted',
                    'on_the_way',
                    'arrived',
                ],
                true
            )
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This booking cannot be cancelled at its current status.'
                );
        }


        // =====================================================
        // VALIDATE CANCELLATION REASON
        // =====================================================

        $validated = $request->validate([
            'cancellation_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        $cancellationReason =
            trim($validated['cancellation_reason']);


        // =====================================================
        // CHECK VERIFIED PREPAYMENT
        // =====================================================

        $payment = $booking->payment;


        $hasVerifiedPrepayment =
            $payment &&
            $payment->payment_status === 'verified' &&
            (float) $payment->prepaid_amount > 0;


        // =====================================================
        // REFUND AMOUNT
        // =====================================================

        $refundAmount = $hasVerifiedPrepayment
            ? (float) $payment->prepaid_amount
            : 0;


        // =====================================================
        // CANCEL BOOKING
        // =====================================================

        DB::transaction(function () use ($booking, $cancellationReason, $payment, $hasVerifiedPrepayment, $provider) {

            // =================================================
            // UPDATE BOOKING
            // =================================================

            $booking->update([
                'status' =>
                    'cancelled',

                'cancelled_by' =>
                    'provider',

                'cancellation_reason' =>
                    $cancellationReason,

                'cancelled_at' =>
                    now(),

                'rating_penalty' =>
                    0.10,
            ]);
            // =================================================
// RECALCULATE PROVIDER RATING IMMEDIATELY
// =================================================

            $averageRating = Review::where(
                'provider_id',
                $provider->id
            )->avg('rating');

            $totalPenalty = Booking::where(
                'provider_id',
                $provider->id
            )->sum('rating_penalty');

            $finalRating = max(
                0,
                (float) ($averageRating ?? 0) - (float) $totalPenalty
            );

            $provider->update([
                'rating' => round($finalRating, 2),
            ]);


            // =================================================
            // STATUS HISTORY
            // =================================================

            $this->createStatusHistory(
                $booking,
                'cancelled',
                $cancellationReason
            );


            // =================================================
            // REFUND VERIFIED PREPAYMENT
            // =================================================

            if ($hasVerifiedPrepayment) {

                $payment->update([
                    'refund_status' => 'pending',
                    'refunded_amount' => $payment->prepaid_amount,
                    'refunded_at' => null,
                    'refund_reason' => $cancellationReason ?: 'Provider cancelled the booking.',

                    // New refund cycle
                    'refund_customer_response' => 'pending',

                    // Clear previous refund proof
                    'refund_slip_image' => null,
                    'refund_submitted_at' => null,
                    'refund_customer_verified_at' => null,
                ]);
            }
        });


        // =====================================================
        // NOTIFY CUSTOMER
        // =====================================================

        $this->notifyCustomer(
            $booking,
            'provider_cancelled'
        );


        // =====================================================
        // REDIRECT
        //
        // If refund is required, tell the Blade to
        // automatically open the refund modal.
        // =====================================================

        if ($hasVerifiedPrepayment) {

            return redirect()
                ->route(
                    'provider.bookings.show',
                    $booking->id
                )
                ->with(
                    'success',
                    'Booking cancelled. You must refund the customer.'
                )
                ->with(
                    'open_refund_modal',
                    true
                )
                ->with(
                    'refund_amount',
                    $refundAmount
                );
        }


        // =====================================================
        // NO REFUND REQUIRED
        // =====================================================

        return redirect()
            ->route(
                'provider.bookings.show',
                $booking->id
            )
            ->with(
                'success',
                'Booking cancelled successfully.'
            );
    }
    /**
     * =====================================================
     * PROVIDER - SUBMIT REFUND PROOF
     *
     * Provider cancels booking
     *        ↓
     * refund_status = pending
     *        ↓
     * Provider refunds customer
     *        ↓
     * Provider uploads refund proof
     *
     * IMPORTANT:
     * refund_status remains "pending"
     * until customer verifies the refund.
     * =====================================================
     */
    public function submitRefund(
        Request $request,
        Booking $booking
    ) {

        // =====================================================
        // GET CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // CHECK BOOKING BELONGS TO PROVIDER
        // =====================================================

        if (
            (int) $booking->provider_id !==
            (int) $provider->id
        ) {

            abort(403);
        }


        // =====================================================
        // LOAD PAYMENT
        // =====================================================

        $booking->load('payment');

        $payment = $booking->payment;


        // =====================================================
        // PAYMENT MUST EXIST
        // =====================================================

        if (!$payment) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Payment information was not found.'
                );
        }


        // =====================================================
        // BOOKING MUST BE CANCELLED
        // =====================================================

        if (
            $booking->status !== 'cancelled'
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Refund can only be submitted for a cancelled booking.'
                );
        }


        // =====================================================
        // VERIFIED PREPAYMENT MUST EXIST
        // =====================================================

        if (
            $payment->payment_status !== 'verified' ||
            (float) $payment->prepaid_amount <= 0
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'There is no verified prepayment to refund.'
                );
        }


        // =====================================================
        // REFUND MUST BE PENDING
        // =====================================================

        if (
            $payment->refund_status !== 'pending'
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This refund is not currently pending.'
                );
        }


        // =====================================================
// PREVENT DUPLICATE SUBMISSION
//
// A new refund proof is allowed only when:
// customer reported the previous refund was not received.
//
// Normal flow:
//     proof submitted
//         ↓
//     waiting for customer
//         ↓
//     customer received
//         → refund completed
//
// Re-refund flow:
//     customer says not received
//         ↓
//     provider can submit new proof
// =====================================================

        if (
            $payment->refund_submitted_at &&
            $payment->refund_customer_response !== 'not_received'
        ) {

            return redirect()
                ->route(
                    'provider.bookings.show',
                    $booking->id
                )
                ->with(
                    'error',
                    'Refund proof has already been submitted and is waiting for customer verification.'
                );
        }


        // =====================================================
        // VALIDATE REFUND PROOF
        // =====================================================

        $request->validate([
            'refund_slip_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);


        // =====================================================
        // STORE REFUND PROOF
        // =====================================================

        $refundSlipPath =
            $request
                ->file('refund_slip_image')
                ->store(
                    'refund-slips',
                    'public'
                );


        // =====================================================
        // UPDATE REFUND
        // =====================================================

        $payment->update([

            // Store the new refund proof image
            'refund_slip_image' =>
                $refundSlipPath,

            // Provider submitted refund proof now
            'refund_submitted_at' =>
                now(),

            // Customer must verify this new refund
            'refund_customer_verified_at' =>
                null,

            // Reset customer response for this new proof
            'refund_customer_response' =>
                'pending',

            // Refund is still waiting for customer confirmation
            'refund_status' =>
                'pending',

            // The refund is not confirmed yet
            'refunded_at' =>
                null,
        ]);
        // =====================================================
// NOTIFY CUSTOMER
// Provider submitted refund proof
// =====================================================

        $customerUser = $booking->customer?->user;

        if ($customerUser) {

            $customerUser->notify(
                new PaymentNotification(
                    $payment,
                    'refund_submitted'
                )
            );
        }
        // =====================================================
        // REDIRECT
        // =====================================================

        return redirect()
            ->route(
                'provider.bookings.show',
                $booking->id
            )
            ->with(
                'success',
                'Refund proof submitted successfully. Waiting for customer verification.'
            );
    }


    /**
     * =====================================================
     * PROVIDER - GO TO SITE
     *
     * accepted → on_the_way
     * =====================================================
     */
    public function goToSite(string $id)
    {
        // =====================================================
        // GET PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // VALIDATE STATUS
        // =====================================================

        if ($booking->status !== 'accepted') {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'Only accepted bookings can be started as on the way.',
            ], 422);
        }
        // =====================================================
// CHECK REQUIRED PREPAYMENT
// =====================================================
//
// If prepayment is required, the provider must verify
// the customer's payment before going to the site.
//

        $payment = $booking->payment;

        if (
            $payment &&
            (float) $payment->prepaid_amount > 0 &&
            $payment->payment_status !== 'verified'
        ) {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'Prepayment must be verified before you go to the customer.',
            ], 422);
        }

        // =====================================================
        // UPDATE STATUS
        // =====================================================

        DB::transaction(function () use ($booking) {

            $booking->update([
                'status' =>
                    'on_the_way',
            ]);


            $this->createStatusHistory(
                $booking,
                'on_the_way'
            );
        });


        $this->notifyCustomer(
            $booking,
            'on_the_way'
        );


        // =====================================================
        // AJAX RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,
            'status' => 'on_the_way',
            'message' =>
                'You are now on the way to the customer.',
        ]);
    }


    /**
     * =====================================================
     * PROVIDER - ARRIVE
     *
     * on_the_way → arrived
     * =====================================================
     */
    public function arrive(string $id)
    {
        // =====================================================
        // GET PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING
        // =====================================================

        $booking = Booking::where(
            'provider_id',
            $provider->id
        )->findOrFail($id);


        // =====================================================
        // VALIDATE STATUS
        // =====================================================

        if ($booking->status !== 'on_the_way') {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'You must be on the way before marking the booking as arrived.',
            ], 422);
        }


        // =====================================================
        // UPDATE STATUS
        // =====================================================

        DB::transaction(function () use ($booking) {

            $booking->update([
                'status' =>
                    'arrived',
            ]);


            $this->createStatusHistory(
                $booking,
                'arrived'
            );
        });


        $this->notifyCustomer(
            $booking,
            'arrived'
        );


        // =====================================================
        // AJAX RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,
            'status' => 'arrived',
            'message' =>
                'You have arrived at the customer location.',
        ]);
    }


    /**
     * =====================================================
     * PROVIDER - START SERVICE
     *
     * arrived → in_progress
     * =====================================================
     */
    public function start(string $id)
    {
        // =====================================================
        // GET PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING
        // =====================================================

        $booking = Booking::where(
            'provider_id',
            $provider->id
        )->findOrFail($id);


        // =====================================================
        // VALIDATE STATUS
        // =====================================================

        if ($booking->status !== 'arrived') {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'You must arrive at the customer location before starting the service.',
            ], 422);
        }


        // =====================================================
        // UPDATE STATUS
        // =====================================================

        DB::transaction(function () use ($booking) {

            $booking->update([
                'status' =>
                    'in_progress',
            ]);


            $this->createStatusHistory(
                $booking,
                'in_progress'
            );
        });


        $this->notifyCustomer(
            $booking,
            'in_progress'
        );


        // =====================================================
        // AJAX RESPONSE
        // =====================================================

        return response()->json([
            'success' => true,
            'status' => 'in_progress',
            'message' =>
                'Service has started successfully.',
        ]);
    }


    /**
     * =====================================================
     * PROVIDER - COMPLETE SERVICE
     *
     * in_progress → completed
     *
     * After completion:
     *
     * remaining_amount > 0
     *     → final_payment_status = pending
     * =====================================================
     */
    public function complete(string $id)
    {
        // =====================================================
        // GET PROVIDER
        // =====================================================

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET BOOKING WITH PAYMENT
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'provider_id',
                $provider->id
            )
            ->findOrFail($id);


        // =====================================================
        // VALIDATE STATUS
        // =====================================================

        if ($booking->status !== 'in_progress') {

            return response()->json([
                'success' => false,
                'status' => $booking->status,
                'message' =>
                    'Only in-progress bookings can be completed.',
            ], 422);
        }


        // =====================================================
        // COMPLETE BOOKING
        // =====================================================

        DB::transaction(function () use ($booking) {

            // =================================================
            // UPDATE BOOKING STATUS
            // =================================================

            $booking->update([
                'status' =>
                    'completed',
            ]);


            // =================================================
            // UPDATE FINAL PAYMENT STATUS
            // =================================================

            $payment = $booking->payment;


            if ($payment) {

                $remainingAmount =
                    (float) $payment->remaining_amount;


                // =================================================
                // REMAINING PAYMENT EXISTS
                // =================================================

                if ($remainingAmount > 0) {

                    $payment->update([
                        'final_payment_status' =>
                            'pending',

                        'final_rejection_reason' =>
                            null,
                    ]);
                }


                // =================================================
                // NOTHING REMAINS
                // =================================================
                else {

                    $payment->update([
                        'final_payment_status' =>
                            'verified',

                        'final_rejection_reason' =>
                            null,
                    ]);
                }
            }


            // =================================================
            // STATUS HISTORY
            // =================================================

            $this->createStatusHistory(
                $booking,
                'completed'
            );
        });


        // =====================================================
        // NOTIFY CUSTOMER
        // =====================================================

        $this->notifyCustomer(
            $booking,
            'completed'
        );


        // =====================================================
        // AJAX RESPONSE
        // =====================================================

        // =====================================================
// GET LATEST PAYMENT DATA
// =====================================================

        $payment = $booking->payment;

        $remainingAmount = $payment
            ? (float) $payment->remaining_amount
            : 0;

        $finalPaymentStatus = $payment
            ? $payment->final_payment_status
            : 'not_required';

        $finalPaymentRequired =
            $remainingAmount > 0 &&
            $finalPaymentStatus !== 'verified';


        // =====================================================
// AJAX RESPONSE
// =====================================================

        return response()->json([
            'success' => true,

            'status' => 'completed',

            'message' =>
                'Service has been completed successfully.',

            'payment' => [
                'id' => $payment?->id,
                'remaining_amount' => $remainingAmount,
                'final_payment_status' => $finalPaymentStatus,
                'final_payment_required' => $finalPaymentRequired,
                'final_slip_image' => $payment?->final_slip_image,
                'final_rejection_reason' => $payment?->final_rejection_reason,
                'review_url' => $payment
                    ? route('provider.payments.show', $payment->id)
                    : null,
            ],
        ]);
    }


    /**
     * =====================================================
     * CREATE STATUS HISTORY
     * =====================================================
     */
    private function createStatusHistory(
        Booking $booking,
        string $status,
        ?string $note = null
    ): void {

        BookingStatusHistory::create([
            'booking_id' =>
                $booking->id,

            'status' =>
                $status,

            'updated_by' =>
                auth()->id(),

            'note' =>
                $note,
        ]);
    }


    /**
     * =====================================================
     * UNUSED RESOURCE METHODS
     * =====================================================
     */

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(
        Request $request,
        string $id
    ) {
        //
    }


    public function destroy(string $id)
    {
        //
    }


    /**
     * =====================================================
     * NOTIFY CUSTOMER
     * =====================================================
     */
    private function notifyCustomer(
        Booking $booking,
        string $event
    ): void {

        $booking->loadMissing(
            'customer.user'
        );


        if ($booking->customer?->user) {

            $booking->customer->user->notify(
                new \App\Notifications\BookingNotification(
                    $booking,
                    $event
                )
            );
        }
    }
}