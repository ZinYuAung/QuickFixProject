<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Provider;
use App\Models\ServiceArea;
use Illuminate\Http\Request;

class AdminCityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.cities');
        $query = City::query()
            ->withCount([
                'serviceAreas',
                'providers',
                'customers',
                'bookings',
            ]);
        //search city
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(
                'name',
                'like',
                '%' . $search . '%'
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
        //paganation
        $cities = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
        //dashboard statistics
        $totalCities = City::count();

        $activeCities = City::where(
            'is_active',
            true
        )->count();

        $totalServiceAreas = ServiceArea::count();

        $totalProviders = Provider::count();

        //new city this month
        $newCitiesThisMonth = City::whereBetween(
            'created_at',
            [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]
        )->count();
        return view(
            'admin.cities',
            compact(
                'cities',
                'totalCities',
                'activeCities',
                'totalServiceAreas',
                'totalProviders',
                'newCitiesThisMonth'
            )
        );


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.cities-create');
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
                'unique:cities,name',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        //check box handling
        $validated['is_active'] = $request->boolean(
            'is_active'
        );
        //create city
        City::create($validated);
        return redirect()
            ->route('admin.cities.index')
            ->with(
                'success',
                'City created successfully.'
            );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $city = City::with('serviceAreas')
            ->withCount([
                'serviceAreas',
                'providers',
                'customers',
                'bookings',
            ])
            ->findOrFail($id);


        return view(
            'admin.cities-show',
            compact('city')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $city = City::findOrFail($id);

        return view(
            'admin.cities-edit',
            compact('city')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $city = City::findOrFail($id);
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',

                // Allow the current city name
                'unique:cities,name,' . $city->id,
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);
        //check box handle
        $validated['is_active'] = $request->boolean(
            'is_active'
        );
        //update city
        $city->update($validated);
        return redirect()
            ->route('admin.cities.index')
            ->with(
                'success',
                'City updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $city = City::findOrFail($id);


        $city->update([
            'is_active' => false,
        ]);


        return redirect()
            ->route('admin.cities.index')
            ->with(
                'success',
                'City deactivated successfully.'
            );
    }

    public function reactivate(string $id)
    {
        $city = City::findOrFail($id);


        $city->update([
            'is_active' => true,
        ]);


        return redirect()
            ->route('admin.cities.index')
            ->with(
                'success',
                'City reactivated successfully.'
            );
    }
}
