<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return view('admin.providers');

        //provider
        $query = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city',
            'services',
        ])->withCount('bookings');
        //search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('user', function ($userQuery) use ($search) {

                $userQuery->withTrashed()
                    ->where(function ($q) use ($search) {

                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }
        //approval status filer
        if ($request->filled('approval_status')) {

            $query->where(
                'approval_status',
                $request->approval_status
            );
        }
        //service area
        if ($request->filled('area_id')) {

            $query->where(
                'area_id',
                $request->area_id
            );
        }
        //acc status filter
        if ($request->filled('account_status')) {

            $accountStatus = $request->account_status;

            $query->whereHas('user', function ($userQuery) use ($accountStatus) {

                $userQuery->withTrashed();

                if ($accountStatus === 'active') {

                    $userQuery
                        ->where('is_active', 1);

                } elseif ($accountStatus === 'inactive') {

                    $userQuery
                        ->where('is_active', 0);

                }
            });
        }
        //pagination
        $providers = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
        //dashboard statistics
        $totalProviders = Provider::count();

        $approvedProviders = Provider::where(
            'approval_status',
            'approved'
        )->count();

        $pendingProviders = Provider::where(
            'approval_status',
            'pending'
        )->count();

        $suspendedProviders = Provider::where(
            'approval_status',
            'suspended'
        )->count();
        //filter data
        $areas = ServiceArea::with('city')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        return view('admin.providers', compact(
            'providers',
            'totalProviders',
            'approvedProviders',
            'pendingProviders',
            'suspendedProviders',
            'areas'
        ));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();

        $areas = ServiceArea::with('city')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $services = Service::orderBy('name')
            ->get();

        return view('admin.providers-create', compact(
            'cities',
            'areas',
            'services'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //validation
        $validated = $request->validate([

            // User information
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            // Location
            'city_id' => [
                'required',
                'exists:cities,id',
            ],

            'area_id' => [
                'required',
                'exists:service_areas,id',
            ],

            // Provider information
            'bio' => [
                'nullable',
                'string',
            ],

            'experience_years' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'availability_status' => [
                'required',
                Rule::in([
                    'online',
                    'busy',
                    'offline',
                ]),
            ],

            'approval_status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'suspended',
                ]),
            ],

            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'exists:services,id',
            ],
        ]);
        //check area belong to selected ciry
        $areaExists = ServiceArea::where('id', $validated['area_id'])
            ->where('city_id', $validated['city_id'])
            ->exists();

        if (!$areaExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'area_id' => 'The selected service area does not belong to the selected city.',
                ]);
        }
        //create user,provider
        DB::transaction(function () use ($validated) {

            $user = User::create([

                'name' => $validated['name'],

                'email' => $validated['email'],

                'phone' => $validated['phone'],

                'password' => $validated['password'],

                'role' => 'provider',

                'is_active' => true,
            ]);


            $provider = Provider::create([

                'user_id' => $user->id,

                'area_id' => $validated['area_id'],

                'bio' => $validated['bio'] ?? null,

                'experience_years' =>
                    $validated['experience_years'],

                'rating' => 0.00,

                'availability_status' =>
                    $validated['availability_status'],

                'approval_status' =>
                    $validated['approval_status'],
            ]);
            //attach services

            if (!empty($validated['services'])) {

                $provider->services()->sync(
                    $validated['services']
                );
            }
        });


        return redirect()
            ->route('admin.providers.index')
            ->with(
                'success',
                'Provider created successfully.'
            );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $provider = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city',
            'services',
            'approvedBy',
            'bookings',
            'locations' => function ($query) {
                $query->latest('recorded_at');
            },
        ])->findOrFail($id);


        return view(
            'admin.provider-show',
            compact('provider')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $provider = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
            'serviceArea.city',
            'services',
        ])->findOrFail($id);


        $cities = City::where('is_active', true)
            ->orderBy('name')
            ->get();

        $areas = ServiceArea::with('city')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $services = Service::orderBy('name')
            ->get();


        return view(
            'admin.providers-edit',
            compact(
                'provider',
                'cities',
                'areas',
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
        $provider = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);


        $user = $provider->user;

        if (!$user) {
            abort(404, 'Provider user account not found.');
        }
        //validation
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'city_id' => [
                'required',
                'exists:cities,id',
            ],

            'area_id' => [
                'required',
                'exists:service_areas,id',
            ],

            'bio' => [
                'nullable',
                'string',
            ],

            'experience_years' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'availability_status' => [
                'required',
                Rule::in([
                    'online',
                    'busy',
                    'offline',
                ]),
            ],

            'approval_status' => [
                'required',
                Rule::in([
                    'pending',
                    'approved',
                    'rejected',
                    'suspended',
                ]),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'exists:services,id',
            ],
        ]);
        $areaExists = ServiceArea::where('id', $validated['area_id'])
            ->where('city_id', $validated['city_id'])
            ->exists();

        if (!$areaExists) {

            return back()
                ->withInput()
                ->withErrors([
                    'area_id' => 'The selected service area does not belong to the selected city.',
                ]);
        }
       DB::transaction(function () use ($validated, $user, $provider) {

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => $validated['is_active'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $user->update($userData);

            // Provider Data ကို array အနေနဲ့ ဦးစွာပြင်ဆင်မည်
            $providerData = [
                'area_id' => $validated['area_id'],
                'bio' => $validated['bio'] ?? null,
                'experience_years' => $validated['experience_years'],
                'availability_status' => $validated['availability_status'],
                'approval_status' => $validated['approval_status'],
            ];

            // အကယ်၍ Status က approved သို့ ပြောင်းသွားလျှင် approve_by ကို Admin ID ထည့်မည်
            if ($validated['approval_status'] === 'approved' && $provider->approval_status !== 'approved') {
                $providerData['approve_by'] = auth()->user()->adminProfile->id ?? null;
            }

            $provider->update($providerData);

            $provider->services()->sync(
                $validated['services'] ?? []
            );
        });

        return redirect()
            ->route(
                'admin.providers.index',
                $provider->id
            )
            ->with(
                'success',
                'Provider updated successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $provider = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);

        $user = $provider->user;

        if (!$user) {

            return redirect()
                ->route('admin.providers.index')
                ->with(
                    'error',
                    'Provider user account not found.'
                );
        }

        DB::transaction(function () use ($user, $provider) {

            $user->update([
                'is_active' => 0,
            ]);

            $provider->update([
                'availability_status' => 'offline',
            ]);
        });

        return redirect()
            ->route('admin.providers.index')
            ->with(
                'success',
                'Provider account deactivated successfully.'
            );
    }
    public function reactivate(string $id)
    {
        $provider = Provider::with([
            'user' => function ($query) {
                $query->withTrashed();
            },
        ])->findOrFail($id);


        $user = $provider->user;

        if (!$user) {

            return redirect()
                ->route('admin.providers.index')
                ->with(
                    'error',
                    'Provider user account not found.'
                );
        }


        DB::transaction(function () use ($user) {

            $user->update([
                'is_active' => true,
            ]);

        });


        return redirect()
            ->route('admin.providers.index')
            ->with(
                'success',
                'Provider account reactivated successfully.'
            );
    }

    /**
     * Approve the specified provider.
     */
    public function approve(string $id)
    {
        $provider = Provider::findOrFail($id);

        $provider->update([
            'approval_status' => 'approved',
            'approve_by' => auth()->user()->adminProfile->id ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Provider approved successfully.');
    }

    /**
     * Reject the specified provider.
     */
    public function reject(string $id)
    {
        $provider = Provider::findOrFail($id);

        $provider->update([
            'approval_status' => 'rejected',
            'approve_by' => auth()->user()->adminProfile->id ?? null,
        ]);

        return redirect()
            ->back()
            ->with('error', 'Provider rejected successfully.');
    }
}