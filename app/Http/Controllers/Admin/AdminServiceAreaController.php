<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.service-areas');
        $query = ServiceArea::query()
            ->with('city')
            ->withCount([
                'providers',
                'customers',
                'services',
                'bookings',
            ]);
        //search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas('city', function ($cityQuery) use ($search) {

                    $cityQuery->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    );

                });

            });
        }
        //city filter
        if (
            $request->filled('city_id') &&
            $request->city_id !== 'all'
        ) {
            $query->where(
                'city_id',
                $request->city_id
            );
        }
        //status filter
        if ($request->status === 'active') {

            $query->where(
                'is_active',
                true
            );

        } elseif ($request->status === 'inactive') {

            $query->where(
                'is_active',
                false
            );
        }
        //pagination
        $serviceAreas = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
        //statistice
        $totalServiceAreas = ServiceArea::count();

        $activeServiceAreas = ServiceArea::where(
            'is_active',
            true
        )->count();

        $citiesCovered = ServiceArea::distinct(
            'city_id'
        )->count('city_id');
        //available providers
        $availableProviders = ServiceArea::where(
            'is_active',
            true
        )
            ->withCount('providers')
            ->get()
            ->sum('providers_count');
        //new areas this month
        $newServiceAreasThisMonth = ServiceArea::whereBetween(
            'created_at',
            [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]
        )->count();
        //city filter
        $cities = City::query()
            ->orderBy('name')
            ->get();

        return view(
            'admin.service-areas',
            compact(
                'serviceAreas',
                'totalServiceAreas',
                'activeServiceAreas',
                'citiesCovered',
                'availableProviders',
                'newServiceAreasThisMonth',
                'cities'
            )
        );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //active cityies
        $cities = City::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        // Active services
        $services = Service::query()
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();


        return view(
            'admin.service-areas-create',
            compact('cities', 'services')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'service_areas',
                    'name'
                )->where(function ($query) use ($request) {

                    return $query->where(
                        'city_id',
                        $request->city_id
                    );

                }),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
            // SERVICES
            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'integer',
                'exists:services,id',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        //create service area
        $serviceArea = ServiceArea::create([
            'city_id' => $validated['city_id'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
        ]);
        //attach service
        $serviceArea->services()->sync(
            $request->input('services', [])
        );

        return redirect()
            ->route('admin.service-areas.index')
            ->with(
                'success',
                'Service area created successfully.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $serviceArea = ServiceArea::query()
            ->with('city', 'services.category', )
            ->withCount([
                'providers',
                'customers',
                'services',
                'bookings',
            ])
            ->findOrFail($id);

        return view(
            'admin.service-areas-show',
            compact('serviceArea')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $serviceArea = ServiceArea::query()
            ->with([
                'city',
                'services',
            ])
            ->findOrFail($id);
        //include acitve cities+curent city
        $cities = City::query()
            ->where(function ($query) use ($serviceArea) {

                $query->where(
                    'is_active',
                    true
                )->orWhere(
                        'id',
                        $serviceArea->city_id
                    );

            })
            ->orderBy('name')
            ->get();
        // Include active services
        $services = Service::query()
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        return view(
            'admin.service-areas-edit',
            compact(
                'serviceArea',
                'cities',
                'services'
            )
        );

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $serviceArea = ServiceArea::findOrFail($id);

        $validated = $request->validate([
            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',

                Rule::unique(
                    'service_areas',
                    'name'
                )
                    ->where(function ($query) use ($request) {

                        return $query->where(
                            'city_id',
                            $request->city_id
                        );

                    })
                    ->ignore($serviceArea->id),
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
            // SERVICES
            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'integer',
                'exists:services,id',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        //service area update
        $serviceArea->update([
            'city_id' => $validated['city_id'],
            'name' => $validated['name'],
            'is_active' => $validated['is_active'],
        ]);
        //update services
        $serviceArea->services()->sync(
            $request->input('services', [])
        );

        return redirect()
            ->route(
                'admin.service-areas.index'
            )
            ->with(
                'success',
                'Service area updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $serviceArea = ServiceArea::findOrFail($id);

        $serviceArea->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route(
                'admin.service-areas.index'
            )
            ->with(
                'success',
                'Service area deactivated successfully.'
            );
    }
    public function reactivate(string $id)
    {
        $serviceArea = ServiceArea::findOrFail($id);

        $serviceArea->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route(
                'admin.service-areas.index'
            )
            ->with(
                'success',
                'Service area reactivated successfully.'
            );
    }
}
