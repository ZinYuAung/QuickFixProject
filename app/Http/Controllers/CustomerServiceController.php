<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
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
                    ->map(fn ($id) => (int) $id);
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
                ->map(fn ($id) => (int) $id)
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
}