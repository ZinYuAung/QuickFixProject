<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderReviewController extends Controller
{
    public function index()
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $reviews = Review::with([
            'booking.customer.user',
            'booking.provider.user',
        ])
            ->whereHas('booking', function ($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })
            ->whereNull('provider_hidden_at')
            ->latest()
            ->paginate(10);

        $averageReviewRating = Review::whereHas(
    'booking',
    function ($query) use ($provider) {
        $query->where('provider_id', $provider->id);
    }
)->avg('rating');

$totalPenalty = Booking::where(
    'provider_id',
    $provider->id
)->sum('rating_penalty');

$cancellationCount = Booking::where(
    'provider_id',
    $provider->id
)
    ->where('rating_penalty', '>', 0)
    ->count();

$averageRating = max(
    0,
    (float) ($averageReviewRating ?? 0) -
    (float) $totalPenalty
);

        $totalReviews = Review::whereHas(
            'booking',
            function ($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            }
        )->count();

        $ratingCounts = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $ratingCounts[$rating] = Review::whereHas(
                'booking',
                function ($query) use ($provider) {
                    $query->where('provider_id', $provider->id);
                }
            )
                ->where('rating', $rating)
                ->count();
        }

        return view('provider.reviews.index', compact(
            'provider',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingCounts',
            'cancellationCount'
        ));
    }

    public function destroy(string $id)
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $review = Review::where('id', $id)
            ->whereHas('booking', function ($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })
            ->firstOrFail();

        $review->update([
    'provider_hidden_at' => now(),
]);

        return redirect()
            ->route('provider.reviews.index')
            ->with(
                'success',
                'Review has been removed from your review history.'
            );
    }
}