<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Provider;
use App\Models\Service;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //  Stat Cards Data
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProviders = Provider::where('approval_status', 'approved')->count();
        $pendingProviders = Provider::where('approval_status', 'pending')->count();
        
        $pendingProvidersList = Provider::with(['user', 'serviceArea.city'])
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $totalServices = Service::count();
        $totalCategories = Service::distinct('service_category_id')->count('service_category_id');
        
        $totalBookings = Booking::count();
        $todaysBookings = Booking::whereDate('created_at', today())->count();

        //  Provider Status Breakdown for Chart & Stats
        $providerStats = [
            'approved' => Provider::where('approval_status', 'approved')->count(),
            'pending' => $pendingProviders,
            'rejected' => Provider::where('approval_status', 'rejected')->count(),
            'suspended' => Provider::where('approval_status', 'suspended')->count(),
        ];
        
        $totalProviderCount = array_sum($providerStats);
        $approvalRate = $totalProviderCount > 0 
            ? round(($providerStats['approved'] / $totalProviderCount) * 100, 1) 
            : 0;
            $approvedEnd = $totalProviderCount > 0 ? ($providerStats['approved'] / $totalProviderCount) * 100 : 0;
        $pendingEnd = $approvedEnd + ($totalProviderCount > 0 ? ($providerStats['pending'] / $totalProviderCount) * 100 : 0);
        $rejectedEnd = $pendingEnd + ($totalProviderCount > 0 ? ($providerStats['rejected'] / $totalProviderCount) * 100 : 0);

       $pieGradient = $totalProviderCount > 0 
            ? "conic-gradient(#0d6efd 0% {$approvedEnd}%, #f59e0b {$approvedEnd}% {$pendingEnd}%, #ef4444 {$pendingEnd}% {$rejectedEnd}%, #6b7280 {$rejectedEnd}% 100%)"
            : "conic-gradient(#e5e7eb 0% 100%)";

       // Service Overview Data (Top 5 most used services)
       $bookings = Booking::all();
        $serviceCounts = [];
        $servicesMap = Service::pluck('service_category_id', 'id')->toArray();
        $categoryNames = [];
        try {
           $categoryNames = DB::table('service_categories')
                ->where('is_active', 1)
                ->pluck('name', 'id')
                ->toArray();
        } catch (\Exception $e) {
            try {
                $categoryNames = DB::table('categories')
                    ->where('is_active', 1)
                    ->pluck('name', 'id')
                    ->toArray();
            } catch (\Exception $e2) {
                $categoryNames = [];
            }
        }

        foreach ($bookings as $booking) {
            $chosenServices = $booking->chosen_services;
            if (is_array($chosenServices)) {
                $countedCategoriesInBooking = [];
                foreach ($chosenServices as $item) {
                    $serviceId = is_array($item) ? ($item['id'] ?? null) : $item;
                    if ($serviceId && isset($servicesMap[$serviceId])) {
                        $categoryId = $servicesMap[$serviceId];
                        // Count unique category once per booking to represent booking rate accurately
                       if (array_key_exists($categoryId, $categoryNames) && !in_array($categoryId, $countedCategoriesInBooking)) {
                            $countedCategoriesInBooking[] = $categoryId;
                            $categoryCounts[$categoryId] = ($categoryCounts[$categoryId] ?? 0) + 1;
                        }
                    }
                }
            }
        }

arsort($categoryCounts);
        $topCategories = array_slice($categoryCounts, 0, 5, true);

        $serviceOverviewData = [];
        foreach ($topCategories as $categoryId => $count) {
            $catName = $categoryNames[$categoryId] ?? 'Category #' . $categoryId;
            $serviceOverviewData[] = [
                'service_name' => $catName, // Kept key name so blade view works seamlessly
                'count' => $count,
            ];
        }

        // Fallback if no bookings exist yet
        if (empty($serviceOverviewData)) {
            $serviceOverviewData = [
                ['service_name' => 'No Data', 'count' => 0]
            ];
        }

        // Scale max bookings for SVG graph
        $maxServiceBookings = max(array_column($serviceOverviewData, 'count')) ?: 10;
        $serviceOverviewDateRange = 'Category booking rates from start date to ' . now()->format('M Y');

       $serviceNamesMap = Service::pluck('name', 'id')->toArray();
        // Recent Bookings Table (Latest 5 with customer and provider user data)
        $recentBookings = Booking::with([
            'customer.user',
            'provider.user',
        ])
            ->latest()
            ->take(5)
            ->get();


        // Pass all variables to the view
        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalProviders',
            'pendingProviders',
            'pendingProvidersList',
            'totalServices',
            'totalCategories',
            'totalBookings',
            'todaysBookings',
            'providerStats',
            'totalProviderCount', 
            'pieGradient',
            'approvalRate',
            'serviceOverviewData',
            'maxServiceBookings',
            'serviceOverviewDateRange',
            'serviceNamesMap',
            'recentBookings'
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
