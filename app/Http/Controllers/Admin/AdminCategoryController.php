<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.categories');
        $query = ServiceCategory::with([
            'services.providers'
        ]);
        //search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }
        //status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }
        //pagination
        $categories = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
        //category statistics
        $totalCategories = ServiceCategory::count();
        $activeCategories = ServiceCategory::where(
            'is_active',
            1
        )
            ->count();
        $inactiveCategories = ServiceCategory::where(
            'is_active',
            0
        )
            ->count();
        //total services
        $totalServices = \App\Models\Service::count();
        //booking data
        $bookings = Booking::select(
            'id',
            'chosen_services'
        )->get();
        //category service,provider,booking counts
        $categoryStats = [];
        foreach ($categories as $category) {
            //service id belonging to this category
            $serviceIds = $category->services
                ->pluck('id')
                ->map(fn($id)
                    => (int) $id)
                ->toArray();
            //service count
            $serviceCount = count($serviceIds);
            //provider count
            $providerIds = [];
            foreach ($category->services as $service) {
                foreach ($service->providers as $provider) {
                    $providerIds[] = $provider->id;
                }
            }
            $providerCount = count(
                array_unique($providerIds)
            );
            //booking count
            $bookingCount = 0;
            foreach ($bookings as $booking) {
                $chosenServices = $booking->chosen_services;
                if (!is_array($chosenServices)) {
                    continue;
                }
                $bookingContainsCategoryService = false;
                foreach ($chosenServices as $chosenService) {
                    if (is_numeric($chosenService)) {
                        $serviceId = (int) $chosenService;
                        if (in_array($serviceId, $serviceIds, true)) {
                            $bookingContainsCategoryService = true;
                            break;
                        }
                    }
                    if (is_array($chosenService)) {
                        if (isset($chosenService['service_id']) && is_numeric($chosenService['service_id'])) {
                            $serviceId = (int) $chosenService['service_id'];
                            if (in_array($serviceId, $serviceIds, true)) {
                                $bookingContainsCategoryService = true;
                                break;
                            }
                        }
                    }
                }
                if ($bookingContainsCategoryService) {
                    $bookingCount++;
                }
            }
            //save statics for blade
            $categoryStats[$category->id] = [
                'services' => $serviceCount,
                'providers' => $providerCount,
                'bookings' => $bookingCount,
            ];
        }
        return view(
            'admin.categories',
            compact(
                'categories',
                'totalCategories',
                'activeCategories',
                'inactiveCategories',
                'totalServices',
                'categoryStats'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.categories-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:service_categories,name',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $category = ServiceCategory::create($validated);
        return redirect()->route('admin.categories.index', $category->id)
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = ServiceCategory::with([
            'services.providers'
        ])->findOrFail($id);
        //service id
        $serviceIds = $category->services
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();
        //provider count
        $providerIds = [];
        foreach ($category->services as $service) {
            foreach ($service->providers as $provider) {
                $providerIds[] = $provider->id;
            }
        }
        $providerCount = count(
            array_unique($providerIds)
        );
        //booking count
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
                $serviceId = null;
                if (is_numeric($chosenService)) {
                    $serviceId = (int) $chosenService;
                } elseif (
                    is_array($chosenService) && isset($chosenService['service_id'])
                    && is_numeric($chosenService['service_id'])
                ) {
                    $serviceId = (int) $chosenService['service_id'];
                }
                if ($serviceId !== null && in_array($serviceId, $serviceIds, true)) {
                    $bookingCount++;
                    break;
                }
            }
        }
        return view(
            'admin.categories-show',
            compact(
                'category',
                'providerCount',
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
        $category = ServiceCategory::findOrFail($id);
        return view(
            'admin.categories-edit',
            compact(
                'category'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $category = ServiceCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:service_categories,name,' . $category->id,
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $category->update($validated);
        return redirect()->route(
            'admin.categories.index',
            $category->id
        )
            ->with(
                'success',
                'Category updated successfully.'
            );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = ServiceCategory::findOrFail($id);
        $category->update([
            'is_active' => 0,
        ]);
        return redirect()->route(
            'admin.categories.index'
        )
            ->with(
                'success',
                'Category deactivated successfully.'
            );
    }
    public function reactivate(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->update(['is_active' => 1,]);
        return redirect()->route(
            'admin.categories.index'
        )
            ->with('success', 'Category reactivated successfully.');
    }
}
