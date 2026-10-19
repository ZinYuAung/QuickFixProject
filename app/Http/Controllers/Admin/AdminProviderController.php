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
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'nrc_front_image' => [
    'required',
    'image',
    'mimes:jpeg,png,webp',
    'max:2048',
],

'nrc_back_image' => [
    'required',
    'image',
    'mimes:jpeg,png,webp',
    'max:2048',
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
       

// Create user and provider, including private NRC images.
$frontImagePath = null;
$backImagePath = null;

try {
    // Store NRC images on Laravel's local disk, not the public disk.
    $frontImagePath = $request->file('nrc_front_image')
        ->store('provider-nrc', 'local');

    if (!$frontImagePath) {
        throw new \RuntimeException('Failed to store NRC front image.');
    }

    $backImagePath = $request->file('nrc_back_image')
        ->store('provider-nrc', 'local');

    if (!$backImagePath) {
        throw new \RuntimeException('Failed to store NRC back image.');
    }

    DB::transaction(function () use (
        $validated,
        $frontImagePath,
        $backImagePath
    ) {
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
            'experience_years' => $validated['experience_years'],
            'rating' => 0.00,
            'availability_status' => $validated['availability_status'],
            'approval_status' => $validated['approval_status'],

            // Save NRC image paths in the providers table.
            'nrc_front_image' => $frontImagePath,
            'nrc_back_image' => $backImagePath,
        ]);

        // Keep existing service assignment behavior.
        if (!empty($validated['services'])) {
            $provider->services()->sync($validated['services']);
        }
    });
} catch (\Throwable $e) {
    // Clean up uploaded files if provider creation fails.
    Storage::disk('local')->delete(
        array_values(array_filter([
            $frontImagePath,
            $backImagePath,
        ]))
    );

    throw $e;
}


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
 * Display a provider's private NRC image to authorized admins.
 */
public function nrcImage(string $id, string $side): StreamedResponse
{
    abort_unless(
        in_array($side, ['front', 'back'], true),
        404
    );

    $provider = Provider::findOrFail($id);

    $path = $side === 'front'
        ? $provider->nrc_front_image
        : $provider->nrc_back_image;

    // Only allow files stored in the private provider-nrc directory.
    abort_unless(
        is_string($path) &&
        str_starts_with($path, 'provider-nrc/') &&
        !str_contains($path, '..') &&
        Storage::disk('local')->exists($path),
        404
    );

    $mimeType = Storage::disk('local')->mimeType($path);

    abort_unless(
        is_string($mimeType) &&
        in_array(
            $mimeType,
            ['image/jpeg', 'image/png', 'image/webp'],
            true
        ),
        404
    );

    return response()->stream(
        function () use ($path) {
            $stream = Storage::disk('local')->readStream($path);

            if ($stream === false) {
                abort(404);
            }

            try {
                fpassthru($stream);
            } finally {
                fclose($stream);
            }
        },
        200,
        [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store',
        ]
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
            'nrc_front_image' => [
    'nullable',
    'image',
    'mimes:jpeg,png,webp',
    'max:2048',
],

'nrc_back_image' => [
    'nullable',
    'image',
    'mimes:jpeg,png,webp',
    'max:2048',
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
   
        // Keep the existing NRC images unless new ones are uploaded.
        $oldFrontImagePath = $provider->nrc_front_image;
        $oldBackImagePath = $provider->nrc_back_image;

        $newFrontImagePath = null;
        $newBackImagePath = null;
        $newImagePaths = [];

        try {
            // Store each new image only once.
            if ($request->hasFile('nrc_front_image')) {
                $newFrontImagePath = $request->file('nrc_front_image')
                    ->store('provider-nrc', 'local');

                if (!$newFrontImagePath) {
                    throw new \RuntimeException(
                        'Failed to store NRC front image.'
                    );
                }

                $newImagePaths[] = $newFrontImagePath;
            }

            if ($request->hasFile('nrc_back_image')) {
                $newBackImagePath = $request->file('nrc_back_image')
                    ->store('provider-nrc', 'local');

                if (!$newBackImagePath) {
                    throw new \RuntimeException(
                        'Failed to store NRC back image.'
                    );
                }

                $newImagePaths[] = $newBackImagePath;
            }

            DB::transaction(function () use (
                $validated,
                $user,
                $provider,
                $newFrontImagePath,
                $newBackImagePath
            ) {
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

                $providerData = [
                    'area_id' => $validated['area_id'],
                    'bio' => $validated['bio'] ?? null,
                    'experience_years' => $validated['experience_years'],
                    'availability_status' => $validated['availability_status'],
                    'approval_status' => $validated['approval_status'],
                ];

                // Replace only the images that were uploaded.
                if ($newFrontImagePath !== null) {
                    $providerData['nrc_front_image'] = $newFrontImagePath;
                }

                if ($newBackImagePath !== null) {
                    $providerData['nrc_back_image'] = $newBackImagePath;
                }

                if (
                    $validated['approval_status'] === 'approved' &&
                    $provider->approval_status !== 'approved'
                ) {
                    $providerData['approve_by'] =
                        auth()->user()->adminProfile->id ?? null;
                }

                $provider->update($providerData);

                $provider->services()->sync(
                    $validated['services'] ?? []
                );
            });
        } catch (\Throwable $e) {
            // Remove newly uploaded files if the update fails.
            if (!empty($newImagePaths)) {
                Storage::disk('local')->delete($newImagePaths);
            }

            throw $e;
        }

        // Delete replaced old images only after the database update succeeds.
        $oldImagesToDelete = [];

        if (
            $newFrontImagePath !== null &&
            is_string($oldFrontImagePath) &&
            str_starts_with($oldFrontImagePath, 'provider-nrc/') &&
            !str_contains($oldFrontImagePath, '..')
        ) {
            $oldImagesToDelete[] = $oldFrontImagePath;
        }

        if (
            $newBackImagePath !== null &&
            is_string($oldBackImagePath) &&
            str_starts_with($oldBackImagePath, 'provider-nrc/') &&
            !str_contains($oldBackImagePath, '..')
        ) {
            $oldImagesToDelete[] = $oldBackImagePath;
        }

        if (!empty($oldImagesToDelete)) {
            Storage::disk('local')->delete($oldImagesToDelete);
        }

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider updated successfully.');

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