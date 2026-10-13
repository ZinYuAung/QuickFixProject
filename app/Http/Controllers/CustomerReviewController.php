<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReviewController extends Controller
{
    /**
     * Store customer review.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking = Booking::with(['review', 'payment', 'provider'])
            ->where('id', $id)
            ->whereHas('customer', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Booking must be completed
        |--------------------------------------------------------------------------
        */
        if ($booking->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'You can review this booking only after the service is completed.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment must be completed
        |--------------------------------------------------------------------------
        */
        $payment = $booking->payment;

        if ($payment) {
            $remainingAmount = (float) ($payment->remaining_amount ?? 0);

            if (
                $remainingAmount > 0
                && $payment->final_payment_status !== 'verified'
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete the final payment before reviewing this service.',
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate review
        |--------------------------------------------------------------------------
        */
        if ($booking->review) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this booking.',
            ], 422);
        }

        if (!$booking->provider) {
            return response()->json([
                'success' => false,
                'message' => 'Provider information could not be found.',
            ], 422);
        }

        DB::transaction(function () use ($request, $booking) {

            /*
            |--------------------------------------------------------------------------
            | Create review
            |--------------------------------------------------------------------------
            */
            Review::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'provider_id' => $booking->provider_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            /*
 |--------------------------------------------------------------------------
 | Recalculate provider rating
 |--------------------------------------------------------------------------
 |
 | Standard review rating:
 | Average of all customer reviews
 |
 | Cancellation penalty:
 | 0.10 for each provider cancellation after acceptance
 |
 | Final rating:
 | Review average - total cancellation penalties
 |
 */

            $averageRating = Review::where(
                'provider_id',
                $booking->provider_id
            )->avg('rating');


            $totalPenalty = Booking::where(
                'provider_id',
                $booking->provider_id
            )
                ->sum('rating_penalty');


            $finalRating = max(
                0,
                (float) $averageRating - (float) $totalPenalty
            );


            $booking->provider->update([
                'rating' => round($finalRating, 2),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted.',
        ]);
    }
}