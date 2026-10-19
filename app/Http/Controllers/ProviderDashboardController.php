<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ProviderDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // =====================================================
        // CURRENT PROVIDER
        // =====================================================

        $provider = Provider::where('user_id', auth()->id())
            ->firstOrFail();


        // =====================================================
        // BOOKING REQUESTS
        // New pending bookings for this provider
        // =====================================================

        $bookingRequests = Booking::with([
            'customer.user',
            'area.city',
        ])
            ->where('provider_id', $provider->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();


        // =====================================================
        // LOAD CHOSEN SERVICES
        // =====================================================

        foreach ($bookingRequests as $booking) {

            $serviceIds = collect(
                $booking->chosen_services ?? []
            )
                ->flatten()
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            $booking->dashboardServices = Service::whereIn(
                'id',
                $serviceIds
            )->get();
        }


        // =====================================================
        // TODAY'S JOBS
        // Only jobs scheduled for TODAY
        // =====================================================

        $todayJobs = Booking::with([
            'customer.user',
            'area.city',
        ])
            ->where('provider_id', $provider->id)
            ->whereDate('scheduled_at', today())
            ->whereIn('status', [
                'accepted',
                'on_the_way',
                'arrived',
                'in_progress',
            ])
            ->orderBy('scheduled_at', 'asc')
            ->get();


        // =====================================================
        // LOAD CHOSEN SERVICES FOR TODAY'S JOBS
        // =====================================================

        foreach ($todayJobs as $booking) {

            $serviceIds = collect(
                $booking->chosen_services ?? []
            )
                ->flatten()
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            $booking->dashboardServices = Service::whereIn(
                'id',
                $serviceIds
            )->get();
        }


        // =====================================================
        // EARNING MONTH
        // Default = current month
        // =====================================================

        $earningDate = $request->input(
            'earning_date',
            now()->format('Y-m')
        );


        try {

            $earningMonth = Carbon::createFromFormat(
                'Y-m',
                $earningDate
            );

        } catch (\Exception $e) {

            $earningMonth = now()->startOfMonth();

            $earningDate = $earningMonth->format('Y-m');
        }


        // =====================================================
        // VERIFIED EARNINGS
        // Only verified payments belonging to this provider
        // =====================================================

        /*
     |--------------------------------------------------------------------------
     | MONTHLY VERIFIED EARNINGS
     |--------------------------------------------------------------------------
     |
     | Earnings can be verified in two stages:
     |
     | 1. Prepayment
     |    prepaid_amount + verified_at
     |
     | 2. Final payment
     |    remaining_amount + final_verified_at
     |
     */

        $monthStart = $earningMonth->copy()->startOfMonth();
        $monthEnd = $earningMonth->copy()->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | Verified Prepayments
        |--------------------------------------------------------------------------
        */

        $verifiedPrepayment = Payment::where(
            'payment_status',
            'verified'
        )
            ->whereHas('booking', function ($query) use ($provider) {
                $query->where(
                    'provider_id',
                    $provider->id
                );
            })
            ->whereBetween('verified_at', [
                $monthStart,
                $monthEnd,
            ])
            ->sum('prepaid_amount');


        /*
        |--------------------------------------------------------------------------
        | Verified Final Payments
        |--------------------------------------------------------------------------
        */

        $verifiedFinalPayment = Payment::where(
            'final_payment_status',
            'verified'
        )
            ->whereHas('booking', function ($query) use ($provider) {
                $query->where(
                    'provider_id',
                    $provider->id
                );
            })
            ->whereBetween('final_verified_at', [
                $monthStart,
                $monthEnd,
            ])
            ->sum('remaining_amount');


        /*
        |--------------------------------------------------------------------------
        | Total Monthly Earnings
        |--------------------------------------------------------------------------
        */

        $verifiedEarnings =
            (float) $verifiedPrepayment +
            (float) $verifiedFinalPayment;
        // =====================================================
// DAILY EARNINGS DATA FOR CHART
// =====================================================

        $dailyEarnings = [];

        $daysInMonth = $monthStart->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = $monthStart->copy()->day($day);

            $prepayment = Payment::where(
                'payment_status',
                'verified'
            )
                ->whereHas('booking', function ($query) use ($provider) {
                    $query->where(
                        'provider_id',
                        $provider->id
                    );
                })
                ->whereDate('verified_at', $date)
                ->sum('prepaid_amount');


            $finalPayment = Payment::where(
                'final_payment_status',
                'verified'
            )
                ->whereHas('booking', function ($query) use ($provider) {
                    $query->where(
                        'provider_id',
                        $provider->id
                    );
                })
                ->whereDate('final_verified_at', $date)
                ->sum('remaining_amount');


            $dailyEarnings[] = [
                'date' => $date->format('M d'),
                'prepayment' => (float) $prepayment,
                'final_payment' => (float) $finalPayment,
                'total' => (float) $prepayment + (float) $finalPayment,
            ];
        }


        // =====================================================
// DASHBOARD COUNTS
// CURRENT MONTH ONLY
// =====================================================

        $dashboardMonthStart = now()->startOfMonth();
        $dashboardMonthEnd = now()->endOfMonth();


        // =====================================================
// ALL BOOKINGS
// Current month only
// =====================================================

        $allBookingsCount = Booking::where(
            'provider_id',
            $provider->id
        )
            ->whereBetween('created_at', [
                $dashboardMonthStart,
                $dashboardMonthEnd,
            ])
            ->count();


        // =====================================================
// PENDING REQUESTS
// Current month only
// =====================================================

        $pendingRequestsCount = Booking::where(
            'provider_id',
            $provider->id
        )
            ->where('status', 'pending')
            ->whereBetween('created_at', [
                $dashboardMonthStart,
                $dashboardMonthEnd,
            ])
            ->count();


        // =====================================================
// COMPLETED JOBS
// Current month only
// =====================================================

        $completedJobsCount = Booking::where(
            'provider_id',
            $provider->id
        )
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                $dashboardMonthStart,
                $dashboardMonthEnd,
            ])
            ->count();


        // =====================================================
// REJECTED / CANCELLED
// Current month only
// =====================================================
//
// Your current BookingController changes a provider rejection
// from pending -> cancelled, so "cancelled" covers both
// rejected requests and normal provider cancellations.
//

        $rejectedCancelledCount = Booking::where(
            'provider_id',
            $provider->id
        )
            ->where('status', 'cancelled')
            ->whereBetween('created_at', [
                $dashboardMonthStart,
                $dashboardMonthEnd,
            ])
            ->count();


        // =====================================================
        // DASHBOARD VIEW
        // =====================================================

        return view(
            'provider.dashboard',
            compact(
                'provider',
                'bookingRequests',
                'todayJobs',
                'verifiedEarnings',
                'dailyEarnings',
                'earningDate',
                'allBookingsCount',
                'pendingRequestsCount',
                'completedJobsCount',
                'rejectedCancelledCount'
            )
        );
    }
    /**
     * Get latest pending booking requests for dashboard AJAX polling.
     */
    public function bookingRequests()
    {
        $provider = Provider::where('user_id', auth()->id())->firstOrFail();

        $bookingRequests = Booking::with([
            'customer.user',
            'area.city',
        ])
            ->where('provider_id', $provider->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        foreach ($bookingRequests as $booking) {
            $serviceIds = collect($booking->chosen_services ?? [])
                ->flatten()
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            $booking->dashboardServices = Service::whereIn('id', $serviceIds)->get();
        }

        return response()->json([
            'success' => true,

            'bookingRequests' => $bookingRequests->map(function ($booking) {
                return [
                    'id' => $booking->id,

                    'customer_name' => $booking->customer?->user?->name ?? 'Customer',

                    'profile_image' => $booking->customer?->user?->profile_image
                        ? asset('storage/' . $booking->customer->user->profile_image)
                        : null,

                    'customer_initial' => strtoupper(
                        substr(
                            $booking->customer?->user?->name ?? 'C',
                            0,
                            1
                        )
                    ),

                    'services' => $booking->dashboardServices
                        ->pluck('name')
                        ->values(),

                    'city' => $booking->area?->city?->name ?? 'N/A',

                    'area' => $booking->area?->name,

                    'total_amount' => (float) ($booking->total_amount ?? 0),

                    'accept_url' => route(
                        'provider.bookings.accept',
                        $booking->id
                    ),

                    'reject_url' => route(
                        'provider.bookings.reject',
                        $booking->id
                    ),

                    'show_url' => route(
                        'provider.bookings.show',
                        $booking->id
                    ),
                ];
            })->values(),

            'pending_count' => Booking::where(
                'provider_id',
                $provider->id
            )
                ->where('status', 'pending')
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ])
                ->count(),
        ]);
    }
    /**
     * Get today's accepted/ongoing jobs for dashboard AJAX.
     */
    public function todayJobs()
    {
        $provider = Provider::where('user_id', auth()->id())->firstOrFail();

        $todayJobs = Booking::with([
            'customer.user',
            'area.city',
        ])
            ->where('provider_id', $provider->id)
            ->whereDate('scheduled_at', today())
            ->whereIn('status', [
                'accepted',
                'on_the_way',
                'arrived',
                'in_progress',
            ])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        foreach ($todayJobs as $booking) {
            $serviceIds = collect($booking->chosen_services ?? [])
                ->flatten()
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            $booking->dashboardServices = Service::whereIn('id', $serviceIds)->get();
        }

        return response()->json([
            'success' => true,

            'todayJobs' => $todayJobs->map(function ($booking) {
                return [
                    'id' => $booking->id,

                    'scheduled_at' => $booking->scheduled_at
                        ? $booking->scheduled_at->format('h:i A')
                        : 'Time N/A',

                    'services' => $booking->dashboardServices
                        ->pluck('name')
                        ->values(),

                    'customer_name' => $booking->customer?->user?->name
                        ?? 'Customer',

                    'city' => $booking->area?->city?->name
                        ?? 'N/A',

                    'area' => $booking->area?->name,

                    'status' => $booking->status,

                    'show_url' => route(
                        'provider.bookings.show',
                        $booking->id
                    ),
                ];
            })->values(),

            'today_count' => $todayJobs->count(),
            'today_bookings_count' => Booking::where(
                'provider_id',
                $provider->id
            )
                ->whereDate('scheduled_at', today())
                ->count(),
        ]);
    }
    /** * Display provider profile. */


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





    //chats
    public function chats(Request $request)
    {
        $providerUserId = auth()->id();

        // Get customers who have chatted with this provider
        $customerIds = \App\Models\Chat::where(function ($query) use ($providerUserId) {
            $query->where('sender_id', $providerUserId);
        })
            ->orWhere(function ($query) use ($providerUserId) {
                $query->where('receiver_id', $providerUserId);
            })
            ->get()
            ->map(function ($chat) use ($providerUserId) {
                return $chat->sender_id == $providerUserId
                    ? $chat->receiver_id
                    : $chat->sender_id;
            })
            ->unique()
            ->values();

        $customers = \App\Models\User::whereIn('id', $customerIds)->get();

        $activeCustomerId = $request->query('customer_id');

        $activeCustomer = null;
        $messages = collect();

        if ($activeCustomerId && $customerIds->contains((int) $activeCustomerId)) {

            $activeCustomer = \App\Models\User::find($activeCustomerId);

            if ($activeCustomer) {

                // Mark customer's unread messages as read
                \App\Models\Chat::where('sender_id', $activeCustomerId)
                    ->where('receiver_id', $providerUserId)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true
                    ]);

                // Load conversation
                $messages = \App\Models\Chat::where(function ($query) use ($providerUserId, $activeCustomerId) {
                    $query->where('sender_id', $providerUserId)
                        ->where('receiver_id', $activeCustomerId);
                })
                    ->orWhere(function ($query) use ($providerUserId, $activeCustomerId) {
                        $query->where('sender_id', $activeCustomerId)
                            ->where('receiver_id', $providerUserId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('provider.chats', compact(
            'customers',
            'activeCustomer',
            'messages'
        ));
    }
    public function sendChat(Request $request, $customer)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $customerUser = \App\Models\User::findOrFail($customer);

        $chat = \App\Models\Chat::create([
            'booking_id' => null,
            'sender_id' => auth()->id(),
            'receiver_id' => $customerUser->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chat->id,
                'message' => $chat->message,
                'sender_id' => $chat->sender_id,
                'receiver_id' => $chat->receiver_id,
                'time' => $chat->created_at->format('g:i A'),
            ],
        ]);
    }

    public function fetchMessages(Request $request, $customer)
    {
        $providerUserId = auth()->id();

        $customerUser = \App\Models\User::findOrFail($customer);

        $messages = \App\Models\Chat::where(function ($query) use ($providerUserId, $customerUser) {
            $query->where('sender_id', $providerUserId)
                ->where('receiver_id', $customerUser->id);
        })
            ->orWhere(function ($query) use ($providerUserId, $customerUser) {
                $query->where('sender_id', $customerUser->id)
                    ->where('receiver_id', $providerUserId);
            })
            ->orderBy('id', 'asc')
            ->get();

        // Mark customer's messages as read
        \App\Models\Chat::where('sender_id', $customerUser->id)
            ->where('receiver_id', $providerUserId)
            ->where('is_read', false)
            ->update([
                'is_read' => true
            ]);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'sender_id' => $chat->sender_id,
                    'receiver_id' => $chat->receiver_id,
                    'message' => $chat->message,
                    'time' => $chat->created_at->format('g:i A'),
                ];
            })->values(),
        ]);
    }

    public function unreadCounts()
    {
        $providerUserId = auth()->id();

        $counts = \App\Models\Chat::where('receiver_id', $providerUserId)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        return response()->json([
            'success' => true,
            'counts' => $counts,
            'total' => $counts->sum(),
        ]);
    }


    //jobs
public function jobs(Request $request)
{
    // =====================================================
    // CURRENT PROVIDER
    // =====================================================

    $provider = Provider::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

// =====================================================
// SERVICE AREAS
// Only active areas belonging to provider's city
// =====================================================

$providerCityId = $provider->serviceArea?->city_id;

$areas = ServiceArea::with('city')
    ->where('city_id', $providerCityId)
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

    // =====================================================
    // FILTERS
    // =====================================================

    $statusFilter = $request->input(
        'status',
        'all'
    );

    $dateFilter = $request->input(
        'date'
    );
$areaFilter = $request->input(
    'area_id'
);

    // =====================================================
    // CURRENT MONTH
    // =====================================================

    $monthStart = now()->startOfMonth();

    $monthEnd = now()->endOfMonth();


    // =====================================================
    // TOTAL JOBS
    //
    // Current month
    // Exclude cancelled
    // Exclude timeout
    // =====================================================

    $totalJobsCount = Booking::where(
        'provider_id',
        $provider->id
    )
        ->whereNotIn('status', [
            'cancelled',
            'timeout',
        ])
        ->whereBetween('created_at', [
            $monthStart,
            $monthEnd,
        ])
        ->count();


    // =====================================================
    // ACCEPTED JOBS
    //
    // Current month
    // Status = accepted
    // =====================================================

    $acceptedJobsCount = Booking::where(
        'provider_id',
        $provider->id
    )
        ->where(
            'status',
            'accepted'
        )
        ->whereBetween('created_at', [
            $monthStart,
            $monthEnd,
        ])
        ->count();


    // =====================================================
    // ONGOING JOBS
    //
    // Current month
    // accepted is NOT included
    // =====================================================

    $ongoingJobsCount = Booking::where(
        'provider_id',
        $provider->id
    )
        ->whereIn('status', [
            'on_the_way',
            'arrived',
            'in_progress',
        ])
        ->whereBetween('created_at', [
            $monthStart,
            $monthEnd,
        ])
        ->count();


    // =====================================================
    // COMPLETED JOBS
    //
    // Current month
    // Status = completed
    // =====================================================

    $completedJobsCount = Booking::where(
        'provider_id',
        $provider->id
    )
        ->where(
            'status',
            'completed'
        )
        ->whereBetween('created_at', [
            $monthStart,
            $monthEnd,
        ])
        ->count();


    // =====================================================
    // RECENT JOBS QUERY
    //
    // Only active jobs:
    //
    // accepted
    // on_the_way
    // arrived
    // in_progress
    //
    // No:
    // pending
    // completed
    // cancelled
    // timeout
    // =====================================================

    $recentJobsQuery = Booking::with([
        'customer.user',
        'area.city',
    ])
        ->where(
            'provider_id',
            $provider->id
        )
        ->whereIn('status', [
            'accepted',
            'on_the_way',
            'arrived',
            'in_progress',
        ])
        ->whereBetween('created_at', [
            $monthStart,
            $monthEnd,
        ]);


    // =====================================================
    // STATUS FILTER
    // =====================================================

    if ($statusFilter === 'accepted') {

        $recentJobsQuery->where(
            'status',
            'accepted'
        );

    } elseif ($statusFilter === 'ongoing') {

        $recentJobsQuery->whereIn(
            'status',
            [
                'on_the_way',
                'arrived',
                'in_progress',
            ]
        );
    }


    // =====================================================
    // DATE FILTER
    //
    // Filter by the actual scheduled job date
    // =====================================================

    if ($dateFilter) {

        $recentJobsQuery->whereDate(
            'scheduled_at',
            $dateFilter
        );
    }
    // =====================================================
// SERVICE AREA FILTER
// Only allow areas from provider's city
// =====================================================

if ($areaFilter) {

    $recentJobsQuery->whereIn(
        'area_id',
        $areas->pluck('id')
    )->where(
        'area_id',
        $areaFilter
    );
}


    // =====================================================
    // GET RECENT JOBS
    // =====================================================

    $recentJobs = $recentJobsQuery
    ->latest()
    ->paginate(10)
    ->withQueryString();


    // =====================================================
    // VIEW
    // =====================================================

    return view(
        'provider.jobs',
        compact(
            'provider',
            'areas',
            'totalJobsCount',
            'acceptedJobsCount',
            'ongoingJobsCount',
            'completedJobsCount',
            'recentJobs',
            'statusFilter',
            'dateFilter',
            'areaFilter'
        )
    );
}
    // =====================================================
// PROVIDER PROFILE
// =====================================================

public function profile()
{
    $provider = Provider::with([
        'user',
        'serviceArea.city',
        'services.category',
    ])
        ->where('user_id', auth()->id())
        ->firstOrFail();

    return view(
        'provider.profile',
        compact('provider')
    );
}
public function nrcImage(string $side): StreamedResponse
{
    abort_unless(in_array($side, ['front', 'back'], true), 404);

    // Only access the currently logged-in provider's own record.
    $provider = Provider::where('user_id', auth()->id())
        ->firstOrFail();

    $path = $side === 'front'
        ? $provider->nrc_front_image
        : $provider->nrc_back_image;

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
        in_array($mimeType, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true),
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
// =====================================================
// PROVIDER CHANGE PASSWORD
// =====================================================

public function changePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => [
            'required',
            'string',
        ],

        'new_password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],
    ]);

    // =====================================================
    // CURRENT USER
    // =====================================================

    $user = auth()->user();


    // =====================================================
    // CHECK CURRENT PASSWORD
    // =====================================================

    if (!Hash::check(
        $validated['current_password'],
        $user->password
    )) {

        return response()->json([
            'success' => false,
            'message' => 'Current password is incorrect.',
        ], 422);
    }


    // =====================================================
    // UPDATE PASSWORD
    // =====================================================

    $user->password = Hash::make(
        $validated['new_password']
    );

    $user->save();


    // =====================================================
    // RESPONSE
    // =====================================================

    return response()->json([
        'success' => true,
        'message' => 'Your password has been changed successfully.',
    ]);
}
// =====================================================
// PROVIDER ACCOUNT ACTIVE / INACTIVE
// =====================================================

public function updateAccountStatus(Request $request)
{
    $validated = $request->validate([
        'is_active' => [
            'required',
            'boolean',
        ],
    ]);

    // =====================================================
    // CURRENT USER
    // =====================================================

    $user = auth()->user();


    // =====================================================
    // UPDATE ACCOUNT STATUS
    // =====================================================

    $user->is_active = $validated['is_active'];

    $user->save();


    // =====================================================
    // RESPONSE
    // =====================================================

    return response()->json([
        'success' => true,

        'message' => $user->is_active
            ? 'Your account is now active.'
            : 'Your account has been deactivated.',

        'is_active' => (bool) $user->is_active,
    ]);
}

/**
 * Update provider Online / Offline availability.
 */
public function updateAvailability(Request $request)
{
    // Accept only online or offline.
    // "busy" is reserved for a future feature.
    $validated = $request->validate([
        'availability_status' => [
            'required',
            'in:online,offline',
        ],
    ]);

    // Find the provider belonging to the logged-in user.
    $provider = Provider::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

    // Save the availability status in the providers table.
    $provider->availability_status =
        $validated['availability_status'];

    $provider->save();

    // Return the saved status to JavaScript.
    return response()->json([
        'success' => true,
        'message' => 'Your availability status has been updated.',
        'availability_status' => $provider->availability_status,
    ]);
}

// =====================================================
// PROVIDER PROFILE EDIT
// =====================================================

public function editProfile()
{
    // =====================================================
    // CURRENT PROVIDER
    // =====================================================

    $provider = Provider::with([
        'user',
        'serviceArea.city',
        'services.category',
    ])
        ->where('user_id', auth()->id())
        ->firstOrFail();


    // =====================================================
    // ACTIVE CITIES FROM DATABASE
    // =====================================================

    $cities = City::where('is_active', true)
        ->orderBy('name')
        ->get();
$serviceAreas = ServiceArea::with('city')
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

    // =====================================================
    // ACTIVE SERVICE CATEGORIES
    // + ACTIVE SERVICES UNDER EACH CATEGORY
    // =====================================================

    $categories = ServiceCategory::with([
        'services' => function ($query) {

            $query
                ->where('is_active', true)
                ->orderBy('name');

        }
    ])
        ->where('is_active', true)
        ->orderBy('name')
        ->get();


    // =====================================================
    // CURRENT PROVIDER'S SELECTED SERVICES
    // FROM provider_services TABLE
    // =====================================================

    $selectedServiceIds = $provider->services
        ->pluck('id')
        ->map(fn ($id) => (int) $id)
        ->values()
        ->toArray();


    // =====================================================
    // PROFILE EDIT VIEW
    // =====================================================

    return view(
        'provider.profile-edit',
        compact(
            'provider',
            'cities',
            'serviceAreas',
            'categories',
            'selectedServiceIds'
        )
    );
}

public function updateProfile(Request $request)
{
    // =====================================================
    // CURRENT PROVIDER
    // =====================================================

   $provider = Provider::with([
    'user',
    'serviceArea.city',
    'serviceArea'
])
        ->where('user_id', auth()->id())
        ->firstOrFail();


    // =====================================================
    // VALIDATION
    // =====================================================

    $validated = $request->validate([

        // User information
        'name' => [
            'required',
            'string',
            'max:255',
        ],

       
        'email' => [
    'required',
    'email:rfc',
    'max:255',
    'unique:users,email,' . $provider->user_id,
],

       
        'phone' => [
            'required',
            'string',
            'regex:/^([0-9\s\-\+\(\)]*)$/',
            'min:8',
            'max:30',
        ],

        'city_id' => [
    'required',
    'integer',
    'exists:cities,id,is_active,1',
],

'area_id' => [
    'required',
    'integer',
    'exists:service_areas,id,is_active,1',
],

        'experience_years' => [
            'required',
            'integer',
            'min:0',
            'max:100',
        ],

        'bio' => [
            'nullable',
            'string',
            'max:2000',
        ],

        'services' => [
            'required',
            'array',
            'min:1',
        ],

        'services.*' => [
            'integer',
            'exists:services,id',
        ],

        'profile_image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],
    ], [
        'email.email' => 'enter correct email format',
        'phone.regex' => 'please enter a valid phone number format',
    ]);

    // =====================================================
// FIND AND VALIDATE SERVICE AREA
// The selected area must belong to the selected city.
// =====================================================

$serviceArea = ServiceArea::with('city')
    ->where('id', $validated['area_id'])
    ->where('city_id', $validated['city_id'])
    ->where('is_active', true)
    ->first();

if (!$serviceArea) {
    return response()->json([
        'success' => false,
        'message' => 'Please select a valid service area for the selected city.',
        'errors' => [
            'area_id' => [
                'The selected service area does not belong to the selected city.'
            ],
        ],
    ], 422);
}

    // =====================================================
    // UPDATE USER
    // =====================================================

    $user = $provider->user;

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'];


    // =====================================================
    // PROFILE IMAGE
    // =====================================================

    if ($request->hasFile('profile_image')) {

        $image = $request->file('profile_image');

        /*
        |--------------------------------------------------------------------------
        | Delete old profile image
        |--------------------------------------------------------------------------
        */

        if (
            $user->profile_image &&
            \Illuminate\Support\Facades\Storage::disk('public')
                ->exists($user->profile_image)
        ) {

            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($user->profile_image);
        }


        /*
        |--------------------------------------------------------------------------
        | Store new profile image
        |--------------------------------------------------------------------------
        */

        $user->profile_image = $image->store(
            'profile-images',
            'public'
        );
    }


    $user->save();


    // =====================================================
    // UPDATE PROVIDER
    // =====================================================

    $provider->area_id = $serviceArea->id;
    $provider->experience_years = $validated['experience_years'];
    $provider->bio = $validated['bio'] ?? null;

    $provider->save();


    // =====================================================
    // UPDATE PROVIDER SERVICES
    // =====================================================

    $provider->services()->sync(
        $validated['services']
    );


    // =====================================================
    // RETURN UPDATED DATA
    // =====================================================

    
return response()->json([
    'success' => true,
    'message' => 'Your profile has been updated successfully.',
    'redirect_url' => route('provider.profile'),
]);
}

}
