<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Customer;
use App\Models\ServiceArea;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.customers');

        // Customer Query
        $query = Customer::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city'
        ])
            ->withCount('bookings');

        // search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->withTrashed()
                    ->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        //status filter
        if ($request->status === 'active') {

            $query->whereHas('user', function ($q) {
                $q->withTrashed()->where('is_active', 1);
            });

        } elseif ($request->status === 'inactive') {

            $query->whereHas('user', function ($q) {
                $q->withTrashed()->where('is_active', 0);
            });
        }

        // City filter 
        if ($request->filled('city_id')) {
            $query->whereHas('serviceArea', function ($q) use ($request) {
                $q->where('city_id', $request->city_id);
            });
        }
        //service area filter
        if ($request->filled('location')) {

            $query->whereHas(
                'serviceArea',
                function ($q) use ($request) {

                    $q->where('id', $request->location);

                }
            );
        }

        //customer
        $customers = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        //statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereHas(
            'user',
            function ($query) {
                $query->where('is_active', 1);
            }
        )->count();

        $inactiveCustomers = Customer::whereHas(
            'user',
            function ($query) {
                $query->withTrashed()->where('is_active', 0);
            }
        )->count();

        $newCustomers = Customer::whereHas(
            'user',
            function ($query) {
                $query->whereDate(
                    'created_at',
                    today()
                );
            }
        )->count();

        //service area
        $areas = ServiceArea::where('is_active', true)
            ->with('city')
            ->orderBy('name')
            ->get();

        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.customers',
            compact(
                'customers',
                'totalCustomers',
                'activeCustomers',
                'inactiveCustomers',
                'newCustomers',
                'cities',
                'areas'
            )
        );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // Active cities
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();
        // Active service areas with city
        $areas = ServiceArea::where('is_active', true)
            ->with('city')
            ->orderBy('name')
            ->get();

        return view(
            'admin.customers-create',
            compact('cities', 'areas')
        );
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
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:users,phone'
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'city_id' => ['required', 'exists:cities,id'],

            'area_id' => [
                'required',
                'exists:service_areas,id'
            ],

            'address' => [
                'required',
                'string',
                'max:500'
            ],
        ]);

        $area = ServiceArea::findOrFail($validated['area_id']);

        if ((int) $area->city_id !== (int) $validated['city_id']) {
            return back()
                ->withErrors([
                    'area_id' => 'The selected service area does not belong to the selected city.'
                ])->withInput();
        }

        DB::transaction(function () use ($validated) {
            //create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
                'is_active' => 1,
            ]);
            //create customer
            Customer::create([
                'user_id' => $user->id,
                'area_id' => $validated['area_id'],
                'address' => $validated['address'],
            ]);
        });

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $customer = Customer::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city'
        ])
            ->withCount('bookings')
            ->findOrFail($id);


        return view(
            'admin.customer-show',
            compact('customer')
        );

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $customer = Customer::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city'
        ])
            ->findOrFail($id);
        // Active cities
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Active service areas with city
        $areas = ServiceArea::where('is_active', true)
            ->with('city')
            ->orderBy('name')
            ->get();

        return view(
            'admin.customers-edit',
            compact(
                'customer',
                'cities',
                'areas'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $customer = Customer::with('user')
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $customer->user_id
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:users,phone,' . $customer->user_id
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
            'city_id' => [
                'required',
                'exists:cities,id'
            ],

            'area_id' => [
                'required',
                'exists:service_areas,id'
            ],

            'address' => [
                'required',
                'string',
                'max:500'
            ],

            'is_active' => [
                'required',
                'boolean'
            ],
        ]);

        // Make sure the selected area belongs to the selected city
        $area = ServiceArea::findOrFail(
            $validated['area_id']
        );

        if (
            (int) $area->city_id !==
            (int) $validated['city_id']
        ) {

            return back()
                ->withErrors([
                    'area_id' =>
                        'The selected service area does not belong to the selected city.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $customer) {
            //update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => $validated['is_active'],
            ];
            //only change pw if entered
            if (!empty($validated['password'])) {

                $userData['password'] = Hash::make(
                    $validated['password']
                );
            }
            //update user
            $customer->user->update($userData);

            //update customer profile
            $customer->update([
                'area_id' => $validated['area_id'],
                'address' => $validated['address'],
            ]);
        });


        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $customer = Customer::with([
            'user' => function ($query) {
                $query->withTrashed();
            }
        ])->findOrFail($id);

        if (!$customer->user) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Customer account not found.');
        }

        //deactivate
        $customer->user->update([
            'is_active' => 0,
        ]);


        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer deactivated successfully.');
    }

    public function reactivate(string $id)
    {
        $customer = Customer::with([
            'user' => function ($query) {
                $query->withTrashed();
            }
        ])->findOrFail($id);

        if (!$customer->user) {
            return redirect()
                ->route('admin.customers.index')
                ->with('error', 'Customer account not found.');
        }

        $customer->user->update([
            'is_active' => 1,
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with(
                'success',
                'Customer reactivated successfully.'
            );
    }


}
