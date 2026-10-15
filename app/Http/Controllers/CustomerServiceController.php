<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    /**
     * Display customer's service history.
     */
    public function index()
    {
        // =====================================================
        // GET CURRENT CUSTOMER
        // =====================================================

        $customer = Customer::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET CUSTOMER BOOKINGS
        // =====================================================

        $bookings = Booking::where(
            'customer_id',
            $customer->id
        )
            ->with([
                'provider.user',
                'area.city',
            ])
            ->latest('scheduled_at')
            ->get();


        // =====================================================
        // GET SERVICES FROM chosen_services
        // =====================================================

        $serviceIds = $bookings
            ->flatMap(function ($booking) {

                return collect(
                    $booking->chosen_services ?? []
                )
                    ->flatten()
                    ->filter()
                    ->map(fn($id) => (int) $id);
            })
            ->unique()
            ->values()
            ->all();


        $services = Service::with('category')
            ->whereIn('id', $serviceIds)
            ->get()
            ->keyBy('id');


        // =====================================================
        // PREPARE SERVICE HISTORY
        // =====================================================

        $serviceHistory = collect();


        foreach ($bookings as $booking) {

            $chosenServiceIds = collect(
                $booking->chosen_services ?? []
            )
                ->flatten()
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique();


            foreach ($chosenServiceIds as $serviceId) {

                $service = $services->get($serviceId);


                // Skip if service was deleted
                if (!$service) {
                    continue;
                }


                $serviceHistory->push([
                    'booking' => $booking,
                    'service' => $service,
                ]);
            }
        }


        // =====================================================
        // SUMMARY COUNTS
        // =====================================================

        $totalServices = $serviceHistory->count();


        $completedServices = $serviceHistory
            ->where('booking.status', 'completed')
            ->count();


        $cancelledServices = $serviceHistory
            ->where('booking.status', 'cancelled')
            ->count();


        // =====================================================
        // RETURN PAGE
        // =====================================================

        return view(
            'customer.services',
            compact(
                'customer',
                'serviceHistory',
                'totalServices',
                'completedServices',
                'cancelledServices'
            )
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Browse active services and their available providers.
     */
    public function browse(Request $request)
    {
        $categoryId = $request->query('category_id');

        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $servicesQuery = Service::with([
            'category',
            'providers' => function ($query) {
                $query->where('approval_status', 'approved')
                    ->where('availability_status', 'online')
                    ->with(['user', 'serviceArea']);
            },
        ])->where('is_active', true);

        if ($categoryId !== null) {
            $servicesQuery->where('service_category_id', $categoryId);
        }

        $services = $servicesQuery
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('customer.services-browse', compact(
            'categories',
            'services',
            'categoryId'
        ));
    }

    /**
     * Show a provider's public profile, rating and reviews.
     */
    public function providerShow($id)
    {
        $provider = Provider::with([
            'user',
            'serviceArea',
            'locations',
            'services' => function ($query) {
                $query->where('is_active', true)
                    ->with('category');
            },
        ])
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        // Only reviews customers are allowed to see.
        // Soft-deleted reviews are excluded automatically by the Review model.
        $reviewsQuery = Review::where('provider_id', $provider->id)
            ->whereNull('provider_hidden_at')
            ->with(['customer.user'])
            ->latest();


        $averageRating = (clone $reviewsQuery)->avg('rating');
        $totalReviews = (clone $reviewsQuery)->count();

        // Calculate total rating penalties from provider bookings.
        $totalPenalty = Booking::where(
            'provider_id',
            $provider->id
        )->sum('rating_penalty');

        // Subtract penalties from the average review rating.
        $averageRating = max(
            0,
            (float) ($averageRating ?? 0) - (float) $totalPenalty
        );


        $reviews = $reviewsQuery->paginate(10);

        return view('customer.provider-detail', compact(
            'provider',
            'averageRating',
            'totalReviews',
            'reviews'
        ));
    }

}