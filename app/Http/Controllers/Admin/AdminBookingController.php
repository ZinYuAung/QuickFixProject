<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.bookings');
          $query = Booking::with([
            'customer.user',
            'provider.user',
            'area',
        ]);

        // SEARCH
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('id', 'like', "%{$search}%")

                    ->orWhereHas('customer.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('provider.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // STATUS
        if (
            $request->filled('status') &&
            $request->status !== 'all'
        ) {
            $query->where('status', $request->status);
        }

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
            $serviceNamesMap = Service::pluck('name', 'id')->toArray();

        // STATISTICS
        $totalBookings = Booking::count();

        $pendingBookings = Booking::where(
            'status',
            'pending'
        )->count();

        $inProgressBookings = Booking::where(
            'status',
            'in_progress'
        )->count();

        $completedBookings = Booking::where(
            'status',
            'completed'
        )->count();

        return view('admin.bookings', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'inProgressBookings',
            'completedBookings',
            'serviceNamesMap'
        ));
    }

    public function show(string $id)
    {
        $booking = Booking::with([
            'customer.user',
            'customer.serviceArea',
            'provider.user',
            'provider.serviceArea',
            'area',
            'statusHistory.updater',
            'payment',
        ])->findOrFail($id);
        // Get service names and prices using service ID
    $servicesMap = Service::select('id', 'name', 'base_price')
        ->get()
        ->keyBy('id');
        $serviceNamesMap = Service::pluck('name', 'id')->toArray();

        return view(
            'admin.booking-show',
            compact('booking', 'serviceNamesMap','servicesMap')
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
