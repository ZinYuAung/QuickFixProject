<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Models\Booking;

class AdminServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.services');
        $query = Service::with(
            'category',
            'providers'
        );
        // SEARCH 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        // CATEGORY FILTER 
        if ($request->filled('category_id')) {
            $query->where(
                'service_category_id',
                $request->category_id
            );
        }
        // STATUS FILTER 
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }
        // PAGINATION 
        $services = $query->latest()
            ->paginate(10)->withQueryString();
        // CATEGORIES FOR FILTER 
        $categories = ServiceCategory::orderBy('name')->get();
        // STATISTICS 
        $totalServices = Service::count();
        $activeServices = Service::where(
            'is_active',
            1
        )->count();
        $inactiveServices = Service::where(
            'is_active',
            0
        )->count();
        $categoriesCount = ServiceCategory::count();
        //booking count
        $bookingCounts = [];
        $bookings = Booking::select(
            'id',
            'chosen_services'
        )->get();
        foreach ($bookings as $booking) {
            $chosenServices = $booking->chosen_services;
            if (!is_array($chosenServices)) {
                continue;
            }
            foreach ($chosenServices as $chosenService) {
                if (is_numeric($chosenService)) {
                    $serviceId = (int) $chosenService;
                    $bookingCounts[$serviceId] = ($bookingCounts[$serviceId] ?? 0) + 1;
                    continue;
                }
                if (is_array($chosenService)) {
                    if (isset($chosenService['service_id']) && is_numeric($chosenService['service_id'])) {
                        $serviceId = (int) $chosenService['service_id'];
                        $bookingCounts[$serviceId] = ($bookingCounts[$serviceId] ?? 0) + 1;
                    }
                }
            }
        }
        return view(
            'admin.services',
            compact(
                'services',
                'categories',
                'totalServices',
                'activeServices',
                'inactiveServices',
                'categoriesCount',
                'bookingCounts'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = ServiceCategory::where(
            'is_active',
            1
        )
            ->orderBy('name')->get();
        return view('admin.services-create', compact(
            'categories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'service_category_id' => [
                'required',
                'exists:service_categories,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'base_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'estimated_minutes' => [
                'required',
                'integer',
                'min:1',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        Service::create($validated);
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $service = Service::with([
            'category',
            'providers',
        ])->findOrFail($id);

        $bookingCount = 0;
        $bookings = Booking::select(
            'id',
            'chosen_services'
        )->get();
        foreach ($bookings as $booking) {
            $chosenServices = $booking->chosen_services;
            if (!is_array($chosenServices)) {
                continue;
            }
            foreach ($chosenServices as $chosenService) {
                if (is_numeric($chosenService) && (int) $chosenService === (int) $id) {
                    $bookingCount++;
                    break;
                }
                if (is_array($chosenService) && isset($chosenService['service_id']) && (int) $chosenService['service_id'] === (int) $id) {
                    $bookingCount++;
                    break;
                }
            }
        }
        return view(
            'admin.services-show',
            compact(
                'service',
                'bookingCount'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::where(
            'is_active',
            1
        )->orderBy('name')->get();
        return view(
            'admin.services-edit',
            compact(
                'service',
                'categories'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $service = Service::findOrFail($id);
        $validated = $request->validate([
            'service_category_id' => [
                'required',
                'exists:service_categories,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'base_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'estimated_minutes' => [
                'required',
                'integer',
                'min:1',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $service->update($validated);
        return redirect()
            ->route('admin.services.index', $service->id)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $service = Service::findOrFail($id);
        $service->update(['is_active' => 0,]);
        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Service deactivated successfully.'
            );
    }
    public function reactivate(string $id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => 1,]);
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service reactivated successfully.');
    }
}
