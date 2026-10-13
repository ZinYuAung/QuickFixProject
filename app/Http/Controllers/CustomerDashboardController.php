<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    /**
     * Display customer dashboard.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Popular Service Categories
        |--------------------------------------------------------------------------
        | Count how many bookings belong to each service category.
        |
        | Booking stores selected service IDs inside `chosen_services`
        | as a JSON array.
        |--------------------------------------------------------------------------
        */

        // Get active service categories
        $categories = ServiceCategory::where('is_active', true)
            ->with([
                'services' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->get();

        // Get all bookings that have selected services
        $bookings = Booking::whereNotNull('chosen_services')
            ->get(['id', 'chosen_services']);

        // Count bookings for each category
        $categoryBookingCounts = [];

        foreach ($categories as $category) {
            $serviceIds = $category->services
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $bookingCount = 0;

            foreach ($bookings as $booking) {
                $chosenServices = $booking->chosen_services ?? [];

                foreach ($chosenServices as $serviceId) {
                    if (in_array((int) $serviceId, $serviceIds, true)) {
                        $bookingCount++;
                        break;
                    }
                }
            }

            $categoryBookingCounts[$category->id] = $bookingCount;
        }

        // Add booking count to each category
        $popularCategories = $categories->map(function ($category) use ($categoryBookingCounts) {
            $category->booking_count =
                $categoryBookingCounts[$category->id] ?? 0;

            $category->services_count =
                $category->services->count();

            return $category;
        });

        // Most booked category first
        $popularCategories = $popularCategories
            ->sortByDesc('booking_count')
            ->values();

            $customer = auth()->user()->customer;

$recentBookings = Booking::where('customer_id', $customer->id)
    ->whereIn('status', [
        'pending',
        'accepted',
        'on_the_way',
        'arrived',
        'in_progress',
    ])
    ->with([
        'provider.user',
        'area.city',
        'payment',
    ])
    ->latest()
    ->take(5)
    ->get();

foreach ($recentBookings as $booking) {

    $serviceIds = $booking->chosen_services ?? [];

    $booking->selected_services = Service::whereIn(
        'id',
        $serviceIds
    )->get();
}
$cities = City::orderBy('name')->get();

$serviceAreas = ServiceArea::where('is_active', true)
    ->orderBy('name')
    ->get();

$services = Service::where('is_active', true)
    ->with('serviceAreas')
    ->orderBy('name')
    ->get();

    return view('customer.dashboard', compact(
    'popularCategories',
    'recentBookings',
    'cities',
    'serviceAreas',
    'services'
));
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
}