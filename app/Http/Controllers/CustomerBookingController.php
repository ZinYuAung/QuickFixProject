<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\City;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\ServiceCategory;
use DB;
use Illuminate\Http\Request;
use App\Notifications\PaymentNotification;

class CustomerBookingController extends Controller
{
    /**
     * Display a listing of the customer's bookings.
     */
    public function index()
    {
        // =====================================================
        // CURRENT CUSTOMER
        // =====================================================

        $customer = auth()->user()
            ->customerProfile()
            ->firstOrFail();


        // =====================================================
        // GET CUSTOMER BOOKINGS
        // =====================================================

        $bookings = Booking::with([
            'provider.user',
            'area.city',
            'statusHistory.updater',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->latest()
            ->get();


        // =====================================================
        // GET ALL SERVICE IDs FROM BOOKINGS
        // =====================================================

        $serviceIds = $bookings
            ->flatMap(function ($booking) {

                return collect(
                    $booking->chosen_services ?? []
                )
                    ->flatten()
                    ->filter()
                    ->map(
                        fn($id) => (int) $id
                    );

            })
            ->unique()
            ->values()
            ->all();


        // =====================================================
        // GET REAL SERVICES FROM DATABASE
        // =====================================================

        $services = Service::with('category')
            ->whereIn(
                'id',
                $serviceIds
            )
            ->get()
            ->keyBy('id');


        // =====================================================
        // ATTACH SELECTED SERVICES TO EACH BOOKING
        // =====================================================

        foreach ($bookings as $booking) {

            $bookingServices = collect(
                $booking->chosen_services ?? []
            )
                ->flatten()
                ->map(
                    function ($serviceId) use ($services) {

                        return $services->get(
                            (int) $serviceId
                        );

                    }
                )
                ->filter()
                ->values();


            $booking->setRelation(
                'selectedServices',
                $bookingServices
            );
        }


        // =====================================================
        // SUMMARY COUNTS
        // =====================================================

        $totalBookings =
            $bookings->count();


        $pendingBookings = $bookings
            ->where(
                'status',
                'pending'
            )
            ->count();


        $completedBookings = $bookings
            ->where(
                'status',
                'completed'
            )
            ->count();


        $cancelledBookings = $bookings
            ->where(
                'status',
                'cancelled'
            )
            ->count();


        // =====================================================
        // RETURN VIEW
        // =====================================================

        return view(
            'customer.bookings',
            compact(
                'bookings',
                'totalBookings',
                'pendingBookings',
                'completedBookings',
                'cancelledBookings'
            )
        );
    }


    /**
     * Show the booking creation form.
     */
    public function create(Request $request)
    {
        // =====================================================
        // CURRENT CUSTOMER
        // =====================================================

        $customer = Customer::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // SELECTED SERVICE
        // =====================================================

        $selectedService = null;


        if ($request->filled('service_id')) {

            $selectedService = Service::with('category')
                ->where(
                    'id',
                    $request->service_id
                )
                ->where(
                    'is_active',
                    true
                )
                ->firstOrFail();
        }


        // =====================================================
        // CATEGORIES + SERVICES
        // =====================================================

        $categories = ServiceCategory::where(
            'is_active',
            true
        )
            ->with([
                'services' => function ($query) {

                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->orderBy('name');

                }
            ])
            ->orderBy('name')
            ->get();


        // =====================================================
        // CITIES
        // =====================================================

        $cities = City::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();


        // =====================================================
        // SERVICE AREAS
        // =====================================================

        $serviceAreas = ServiceArea::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();


        // =====================================================
        // AVAILABLE PROVIDERS
        // =====================================================

        $providers = Provider::with([
            'user',
            'services',
            'serviceArea',
        ])
            ->where(
                'approval_status',
                'approved'
            )
            ->where(
                'availability_status',
                'online'
            )
            ->get();


        // =====================================================
        // PREPARE BOOKING SERVICES
        // =====================================================

        $bookingServices = $categories->map(
            function ($category) {

                return [

                    'id' =>
                        $category->id,

                    'name' =>
                        $category->name,

                    'services' =>
                        $category->services
                            ->map(
                                function ($service) use ($category) {

                                    return [

                                        'id' =>
                                            $service->id,

                                        'name' =>
                                            $service->name,

                                        'category_id' =>
                                            $category->id,

                                        'category_name' =>
                                            $category->name,

                                    ];

                                }
                            )
                            ->values(),

                ];

            }
        )
            ->values();


        // =====================================================
        // SELECTED SERVICE ID
        // =====================================================

        $selectedServiceId =
            $selectedService?->id;


        // =====================================================
        // RETURN VIEW
        // =====================================================

        return view(
            'customer.booking-create',
            compact(
                'customer',
                'categories',
                'bookingServices',
                'selectedService',
                'selectedServiceId',
                'cities',
                'serviceAreas',
                'providers'
            )
        );
    }


    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        // =====================================================
        // CURRENT CUSTOMER
        // =====================================================

        $customer = Customer::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // VALIDATION
        // =====================================================

        $validated = $request->validate([

            'service_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'service_ids.*' => [
                'integer',
                'exists:services,id',
                'distinct',
            ],

            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],

            'area_id' => [
                'required',
                'integer',
                'exists:service_areas,id',
            ],

            'provider_id' => [
                'required',
                'integer',
                'exists:providers,id',
            ],

            'booking_type' => [
                'required',
                'in:asap,scheduled',
            ],

            'scheduled_at' => [
                'nullable',
                'required_if:booking_type,scheduled',
                'date',
                'after:now',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'problem_photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,webp',
                'max:5120',
            ],

        ]);


        // =====================================================
        // CHECK CITY
        // =====================================================

        $city = City::where(
            'id',
            $validated['city_id']
        )
            ->where(
                'is_active',
                true
            )
            ->first();


        if (!$city) {

            return back()
                ->withInput()
                ->withErrors([
                    'city_id' =>
                        'The selected city is not available.',
                ]);
        }


        // =====================================================
        // CHECK SERVICE AREA
        // =====================================================

        $serviceArea = ServiceArea::where(
            'id',
            $validated['area_id']
        )
            ->where(
                'city_id',
                $validated['city_id']
            )
            ->where(
                'is_active',
                true
            )
            ->first();


        if (!$serviceArea) {

            return back()
                ->withInput()
                ->withErrors([
                    'area_id' =>
                        'The selected service area is not available in the selected city.',
                ]);
        }


        // =====================================================
        // GET SELECTED SERVICES
        // =====================================================

        $serviceIds =
            $validated['service_ids'];


        $services = Service::whereIn(
            'id',
            $serviceIds
        )
            ->where(
                'is_active',
                true
            )
            ->get();


        // =====================================================
        // CHECK ALL SERVICES ARE ACTIVE
        // =====================================================

        if (
            $services->count() !==
            count($serviceIds)
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'service_ids' =>
                        'One or more selected services are no longer available.',
                ]);
        }


        // =====================================================
        // CHECK SERVICES ARE AVAILABLE
        // IN SELECTED SERVICE AREA
        // =====================================================

        $availableServiceIds = DB::table(
            'service_area_service'
        )
            ->where(
                'service_area_id',
                $serviceArea->id
            )
            ->whereIn(
                'service_id',
                $serviceIds
            )
            ->pluck('service_id')
            ->map(
                fn($id) => (int) $id
            )
            ->unique();


        $missingServiceIds = collect(
            $serviceIds
        )
            ->map(
                fn($id) => (int) $id
            )
            ->diff(
                $availableServiceIds
            );


        if ($missingServiceIds->isNotEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'service_ids' =>
                        'One or more selected services are not available in this service area.',
                ]);
        }


        // =====================================================
        // CHECK PROVIDER
        // =====================================================

        $provider = Provider::with([
            'user',
            'services',
            'serviceArea',
        ])
            ->where(
                'id',
                $validated['provider_id']
            )
            ->where(
                'approval_status',
                'approved'
            )
            ->where(
                'availability_status',
                'online'
            )
            ->first();


        if (!$provider) {

            return back()
                ->withInput()
                ->withErrors([
                    'provider_id' =>
                        'The selected provider is no longer available.',
                ]);
        }


        // =====================================================
        // CHECK PROVIDER CITY
        // =====================================================

        if (
            !$provider->serviceArea ||
            (int) $provider->serviceArea->city_id !==
            (int) $validated['city_id']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'provider_id' =>
                        'The selected provider is not available in the selected city.',
                ]);
        }


        // =====================================================
        // CHECK PROVIDER SERVICE AREA
        // =====================================================

        if (
            (int) $provider->area_id !==
            (int) $validated['area_id']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'provider_id' =>
                        'The selected provider is not available in the selected service area.',
                ]);
        }


        // =====================================================
        // CHECK PROVIDER SERVICES
        // =====================================================

        $providerServiceIds = $provider->services
            ->pluck('id')
            ->map(
                fn($id) => (int) $id
            );


        $missingProviderServices = collect(
            $serviceIds
        )
            ->map(
                fn($id) => (int) $id
            )
            ->diff(
                $providerServiceIds
            );


        if ($missingProviderServices->isNotEmpty()) {

            return back()
                ->withInput()
                ->withErrors([
                    'provider_id' =>
                        'The selected provider does not provide all selected services.',
                ]);
        }


        // =====================================================
        // CALCULATE TOTAL SERVICE AMOUNT
        // =====================================================

        $totalAmount =
            (float) $services->sum('base_price');


        // =====================================================
        // PREPAYMENT RULE
        //
        // Total < 30,000 MMK
        //     → No prepayment
        //
        // Total >= 30,000 MMK
        //     → 10% prepayment
        //
        // Remaining amount
        //     → Total - prepayment
        // =====================================================

        $prepaymentThreshold = 30000;

        $prepaymentRate = 0.10;

        $prepaidAmount = 0;


        if (
            $totalAmount >=
            $prepaymentThreshold
        ) {

            $prepaidAmount =
                round(
                    $totalAmount * $prepaymentRate,
                    2
                );
        }


        // =====================================================
        // CALCULATE REMAINING AMOUNT
        // =====================================================

        $remainingAmount =
            round(
                $totalAmount - $prepaidAmount,
                2
            );


        $prepaymentRequired =
            $prepaidAmount > 0;


        // =====================================================
        // PROBLEM PHOTO
        // =====================================================

        $problemPhoto = null;


        if ($request->hasFile('problem_photo')) {

            $problemPhoto =
                $request
                    ->file('problem_photo')
                    ->store(
                        'booking-problems',
                        'public'
                    );
        }


        // =====================================================
        // SCHEDULE TIME
        // =====================================================

        if (
            $validated['booking_type'] === 'asap'
        ) {

            $scheduledAt = now();

        } else {

            $scheduledAt =
                $validated['scheduled_at'];
        }


        // =====================================================
        // CREATE BOOKING + PAYMENT + STATUS HISTORY
        // =====================================================

        $booking = null;

        $payment = null;


        DB::transaction(function () use (&$booking, &$payment, $customer, $provider, $serviceArea, $validated, $serviceIds, $totalAmount, $prepaidAmount, $remainingAmount, $scheduledAt, $problemPhoto) {

            // =================================================
            // CREATE BOOKING
            // =================================================

            $booking = Booking::create([

                'customer_id' =>
                    $customer->id,

                'provider_id' =>
                    $provider->id,

                'area_id' =>
                    $serviceArea->id,

                'booking_type' =>
                    $validated['booking_type'],

                'scheduled_at' =>
                    $scheduledAt,

                'address' =>
                    $validated['address'],

                'latitude' =>
                    null,

                'longitude' =>
                    null,

                'chosen_services' =>
                    $serviceIds,

                'total_amount' =>
                    $totalAmount,

                'response_deadline' =>
                    $validated['booking_type'] === 'asap'
                    ? now()->addMinutes(5)
                    : now()->addDay(),

                'status' =>
                    'pending',

                'customer_note' =>
                    $validated['customer_note'] ?? null,

                'problem_photo' =>
                    $problemPhoto,

            ]);


            // =================================================
            // CREATE PAYMENT RECORD
            //
            // Every booking gets one payment record.
            //
            // Total < 30,000:
            //     prepaid_amount = 0
            //     payment_status = verified
            //     remaining_amount = total
            //
            // Total >= 30,000:
            //     prepaid_amount = 10%
            //     payment_status = pending
            //     remaining_amount = 90%
            //
            // Final payment becomes available after
            // provider completes the booking.
            // =================================================

            $payment = Payment::create([

                'booking_id' =>
                    $booking->id,

                'service_amount' =>
                    $totalAmount,

                'prepaid_amount' =>
                    $prepaidAmount,

                'remaining_amount' =>
                    $remainingAmount,

                'payment_method' =>
                    $prepaidAmount > 0
                    ? 'kbzpay'
                    : 'cod',

                'payment_status' =>
                    $prepaidAmount > 0
                    ? 'pending'
                    : 'verified',

                // Final payment is not available yet.
                'final_payment_status' =>
                    'not_required',

                'final_slip_image' =>
                    null,

                'final_rejection_reason' =>
                    null,

                'slip_image' =>
                    null,

                'verified_by' =>
                    null,

                'verified_at' =>
                    null,

                'rejection_reason' =>
                    null,

                // Refund
                'refund_status' =>
                    'not_required',

                'refunded_amount' =>
                    0,

                'refunded_at' =>
                    null,

                'refund_reason' =>
                    null,
            ]);


            // =================================================
            // INITIAL STATUS HISTORY
            // =================================================

            $booking->statusHistory()->create([

                'status' =>
                    'pending',

                'updated_by' =>
                    auth()->id(),

                'note' =>
                    'Booking created by customer.',

            ]);

        });


        // =====================================================
        // NOTIFY PROVIDER
        //
        // The notification will use the payment record
        // to tell the provider whether prepaid is required.
        // =====================================================

        if ($provider->user) {

            $provider->user->notify(
                new \App\Notifications\BookingNotification(
                    $booking,
                    'booking_created'
                )
            );
        }


        // =====================================================
        // REDIRECT TO BOOKING DETAIL
        // =====================================================

        return redirect()
            ->route(
                'customer.booking.detail',
                $booking->id
            )
            ->with(
                'success',
                $prepaymentRequired
                ? 'Your booking has been created. A 10% prepayment is required.'
                : 'Your booking has been created successfully. No prepayment is required.'
            );
    }


    /**
     * Display the specified booking.
     */
    public function show(string $id)
    {
        // =====================================================
        // CURRENT CUSTOMER
        // =====================================================

        $customer = Customer::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET CUSTOMER'S BOOKING
        // =====================================================

        $booking = Booking::with([

            'provider.user',

            'provider.services.category',

            'area.city',

            'statusHistory.updater',

            'chats',

            'payment',
            'review',

        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->findOrFail($id);


        // =====================================================
        // GET SELECTED SERVICE IDs
        // =====================================================

        $serviceIds = collect(
            $booking->chosen_services ?? []
        )
            ->flatten()
            ->filter()
            ->map(
                fn($id) => (int) $id
            )
            ->unique()
            ->values()
            ->all();


        // =====================================================
        // GET REAL SERVICES
        // =====================================================

        $services = Service::with('category')
            ->whereIn(
                'id',
                $serviceIds
            )
            ->get();


        // =====================================================
        // RETURN BOOKING DETAIL
        // =====================================================

        return view(
            'customer.booking-detail',
            compact(
                'customer',
                'booking',
                'services'
            )
        );
    }


    /**
     * Check the current booking status.
     *
     * This endpoint is used by JavaScript to check
     * whether the provider/customer has changed
     * the booking status.
     */
    public function status(string $id)
    {
        // =====================================================
        // CURRENT CUSTOMER
        // =====================================================

        $customer = auth()->user()
            ->customerProfile()
            ->firstOrFail();


        // =====================================================
        // GET CUSTOMER'S BOOKING
        //
        // Security:
        // The booking must belong to this customer.
        // =====================================================

        $booking = Booking::with([
            'provider.user',
            'statusHistory.updater',
            'payment',
            'review',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->findOrFail($id);

        // =====================================================
        // AUTOMATIC PROVIDER RESPONSE TIMEOUT
        // Runs when the customer requests booking status.
        // No Laravel Scheduler required.
        // =====================================================

        $responseTimedOut = false;

        if (
            $booking->status === 'pending' &&
            $booking->response_deadline &&
            now()->greaterThanOrEqualTo($booking->response_deadline)
        ) {
            DB::transaction(function () use ($booking, &$responseTimedOut) {
                // Lock and recheck to prevent duplicate timeout updates.
                $lockedBooking = Booking::whereKey($booking->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$lockedBooking ||
                    $lockedBooking->status !== 'pending' ||
                    !$lockedBooking->response_deadline ||
                    now()->lessThan($lockedBooking->response_deadline)
                ) {
                    return;
                }

                // Provider did not respond before the deadline.
                $lockedBooking->update([
                    'status' => 'timeout',
                ]);

                // Record the status change.
                $lockedBooking->statusHistory()->create([
                    'status' => 'timeout',
                    'updated_by' => null,
                    'note' => 'Provider response time expired.',
                ]);

                $responseTimedOut = true;
            });

            // Notify only when this request changed the status.
            if ($responseTimedOut) {
                $booking->refresh();

                auth()->user()->notify(
                    new \App\Notifications\BookingNotification(
                        $booking,
                        'timeout'
                    )
                );

                // Reload the latest status and history.
                $booking->load([
                    'provider.user',
                    'statusHistory.updater',
                    'payment',
                    'review',
                ]);
            }
        }




        // =====================================================
        // AUTOMATIC PREPAYMENT TIMEOUT
        // Scheduled bookings only
        // =====================================================

        $prepaymentTimedOut = false;

        if (
            $booking->booking_type === 'scheduled' &&
            $booking->status === 'accepted' &&
            $booking->scheduled_at &&
            now()->greaterThanOrEqualTo($booking->scheduled_at)
        ) {
            DB::transaction(function () use ($booking, &$prepaymentTimedOut) {
                // Recheck the booking to avoid duplicate updates.
                $lockedBooking = Booking::whereKey($booking->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$lockedBooking ||
                    $lockedBooking->booking_type !== 'scheduled' ||
                    $lockedBooking->status !== 'accepted' ||
                    !$lockedBooking->scheduled_at ||
                    now()->lessThan($lockedBooking->scheduled_at)
                ) {
                    return;
                }

                // Lock and check the payment record.
                $payment = Payment::where(
                    'booking_id',
                    $lockedBooking->id
                )
                    ->lockForUpdate()
                    ->first();

                // Do not timeout if payment information is missing,
                // prepayment is not required, or a slip was uploaded.
                if (
                    !$payment ||
                    (float) $payment->prepaid_amount <= 0 ||
                    !blank($payment->slip_image)
                ) {
                    return;
                }

                // Update booking status.
                $lockedBooking->update([
                    'status' => 'timeout',
                ]);

                // Record the status change.
                $lockedBooking->statusHistory()->create([
                    'status' => 'timeout',
                    'updated_by' => auth()->id(),
                    'note' => 'Automatically timed out because the required prepayment slip was not submitted by the scheduled service time.',
                ]);

                $prepaymentTimedOut = true;
            });

            // Notify the customer only if this request
            // actually changed the booking to timeout.
            if ($prepaymentTimedOut) {
                $booking->refresh();

                auth()->user()->notify(
                    new \App\Notifications\BookingNotification(
                        $booking,
                        'prepayment_timeout'
                    )
                );

                // Reload relations so the JSON response
                // contains the latest status and history.
                $booking->load([
                    'provider.user',
                    'statusHistory.updater',
                    'payment',
                    'review',
                ]);
            }
        }

        // =====================================================
        // STATUS LABELS
        // =====================================================

        $statusLabels = [

            'pending' =>
                'Pending',

            'accepted' =>
                'Accepted',

            'on_the_way' =>
                'On the Way',

            'arrived' =>
                'Arrived',

            'in_progress' =>
                'In Progress',

            'completed' =>
                'Completed',

            'cancelled' =>
                'Cancelled',

            'timeout' =>
                'Timeout',

        ];


        // =====================================================
        // STATUS HISTORY
        // =====================================================

        $history = $booking->statusHistory
            ->sortBy('created_at')
            ->values()
            ->map(function ($item) use ($statusLabels) {

                return [

                    'status' =>
                        $item->status,

                    'label' =>
                        $statusLabels[$item->status]
                        ?? ucwords(
                            str_replace(
                                '_',
                                ' ',
                                $item->status
                            )
                        ),

                    'created_at' =>
                        $item->created_at
                        ? $item->created_at->format(
                            'M d, Y - h:i A'
                        )
                        : null,

                    'updated_by' =>
                        $item->updater?->name,

                    'note' =>
                        $item->note,

                ];

            })
            ->values();


        // =====================================================
        // PROVIDER INFORMATION
        // =====================================================

        $provider = null;


        if ($booking->provider) {

            $provider = [

                'name' =>
                    $booking->provider->user?->name
                    ?? 'Provider',

                'profile_image' =>
                    $booking->provider->user?->profile_image
                    ? asset(
                        'storage/' .
                        $booking->provider->user->profile_image
                    )
                    : null,

                'rating' =>
                    $booking->provider->rating,

                'experience_years' =>
                    $booking->provider->experience_years,

            ];
        }


        // =====================================================
        // CANCELLATION DEADLINE
        //
        // Only scheduled bookings have a customer
        // cancellation deadline.
        //
        // Deadline:
        // scheduled_at - 1 hour
        // =====================================================


        // =====================================================
// CUSTOMER CANCELLATION DEADLINE
// =====================================================

        $cancellationDeadline = $booking->response_deadline;

        // =====================================================
// CUSTOMER CAN CANCEL?
// =====================================================

        $customerCancellableStatuses =
            $booking->booking_type === 'asap'
            ? ['pending']
            : (
                $booking->booking_type === 'scheduled'
                ? ['pending', 'accepted']
                : []
            );

        $canCancel =
            in_array(
                $booking->status,
                $customerCancellableStatuses,
                true
            ) &&
            $cancellationDeadline &&
            now()->lessThan($cancellationDeadline);

        // =====================================================
// PAYMENT / REFUND INFORMATION
// =====================================================

        $payment = $booking->payment;


        // =====================================================
// REFUND
// =====================================================

        $refundStatus =
            $payment?->refund_status
            ?? 'not_required';

        $refundedAmount =
            $payment
            ? (float) $payment->refunded_amount
            : 0;


        // =====================================================
// PAYMENT AMOUNTS
// =====================================================

        $serviceAmount =
            $payment
            ? (float) $payment->service_amount
            : (float) $booking->total_amount;

        $prepaidAmount =
            $payment
            ? (float) $payment->prepaid_amount
            : 0;

        $remainingAmount =
            $payment
            ? (float) $payment->remaining_amount
            : 0;


        // =====================================================
// PREPAYMENT
// =====================================================

        $paymentStatus =
            $payment?->payment_status
            ?? null;

        $prepaymentSlipUploaded =
            !empty($payment?->slip_image);

        $prepaymentRejected =
            $paymentStatus === 'rejected';

        $prepaymentVerified =
            $paymentStatus === 'verified';


        // =====================================================
// FINAL PAYMENT
// =====================================================

        $finalPaymentStatus =
            $payment?->final_payment_status
            ?? 'not_required';

        $finalSlipUploaded =
            !empty($payment?->final_slip_image);

        $finalPaymentRequired =
            $booking->status === 'completed' &&
            $remainingAmount > 0 &&
            $finalPaymentStatus !== 'verified';

        $finalPaymentPending =
            $booking->status === 'completed' &&
            $finalPaymentStatus === 'pending' &&
            !empty($payment?->final_payment_submitted_at);

        $finalPaymentRejected =
            $booking->status === 'completed' &&
            $finalPaymentStatus === 'rejected';

        $finalPaymentVerified =
            $booking->status === 'completed' &&
            $finalPaymentStatus === 'verified';
        // =====================================================
// REVIEW
// =====================================================

        $reviewExists =
            $booking->review !== null;

        $reviewEligible =
            $booking->status === 'completed'
            && (
                !$payment
                || $remainingAmount <= 0
                || $finalPaymentStatus === 'verified'
            );

        // =====================================================
        // RETURN AJAX RESPONSE
        // =====================================================

        return response()->json([

            'success' =>
                true,

            'booking_id' =>
                $booking->id,

            // ---------------------------------------------
            // BOOKING STATUS
            // ---------------------------------------------

            'status' =>
                $booking->status,

            'status_label' =>
                $statusLabels[$booking->status]
                ?? ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $booking->status
                    )
                ),

            'booking_type' =>
                $booking->booking_type,


            // ---------------------------------------------
            // STATUS HISTORY
            // ---------------------------------------------

            'history' =>
                $history,


            // ---------------------------------------------
            // PROVIDER
            // ---------------------------------------------

            'provider' =>
                $provider,


            // ---------------------------------------------
            // CANCELLATION
            // ---------------------------------------------

            'can_cancel' =>
                $canCancel,

            'cancellation_deadline' =>
                $cancellationDeadline
                ? $cancellationDeadline->toIso8601String()
                : null,

            'cancelled_by' =>
                $booking->cancelled_by,

            'cancellation_reason' =>
                $booking->cancellation_reason,


            // ---------------------------------------------
// REFUND
// ---------------------------------------------

            'refund_status' =>
                $refundStatus,

            'refunded_amount' =>
                $refundedAmount,


            // ---------------------------------------------
// PAYMENT
// ---------------------------------------------

            'payment' => [

                'id' =>
                    $payment?->id,

                'service_amount' =>
                    $serviceAmount,

                'prepaid_amount' =>
                    $prepaidAmount,

                'remaining_amount' =>
                    $remainingAmount,

                'payment_method' =>
                    $payment?->payment_method,

                // PREPAYMENT
                'payment_status' =>
                    $paymentStatus,

                'prepayment_slip_uploaded' =>
                    $prepaymentSlipUploaded,

                'prepayment_rejection_reason' =>
                    $payment?->rejection_reason,

                'prepayment_verified' =>
                    $prepaymentVerified,

                'prepayment_rejected' =>
                    $prepaymentRejected,

                // FINAL PAYMENT
                'final_payment_status' =>
                    $finalPaymentStatus,

                'final_slip_uploaded' =>
                    $finalSlipUploaded,

                'final_slip_image' =>
                    $payment?->final_slip_image,

                'final_rejection_reason' =>
                    $payment?->final_rejection_reason,

                'final_payment_required' =>
                    $finalPaymentRequired,

                'final_payment_pending' =>
                    $finalPaymentPending,

                'final_payment_rejected' =>
                    $finalPaymentRejected,

                'final_payment_verified' =>
                    $finalPaymentVerified,

                'payment_url' =>
                    $payment
                    ? route(
                        'customer.payment.show',
                        $booking->id
                    )
                    : null,
            ],
            // ---------------------------------------------
// REVIEW
// ---------------------------------------------

            'review' => [

                'exists' =>
                    $reviewExists,

                'eligible' =>
                    $reviewEligible,

                'provider_name' =>
                    $booking->provider?->user?->name,

            ],

        ]);
    }


    /**
     * Cancel the customer's booking.
     *
     * Customer cancellation reason is optional.
     */
    /**
     * Cancel the customer's booking.
     *
     * Customer cancellation reason is optional.
     */

    /**
     * Cancel the customer's booking.
     *
     * Customer cancellation reason is optional.
     */
    public function cancel(Request $request, string $id)
    {
        // Get the current customer.
        $customer = auth()->user()
            ->customerProfile()
            ->firstOrFail();

        // Validate the optional cancellation reason.
        $validated = $request->validate([
            'cancellation_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // Only allow the customer to cancel their own booking.
        $booking = Booking::with('payment')
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        // Determine allowed cancellation statuses by booking type.
        $customerCancellableStatuses =
            $booking->booking_type === 'asap'
            ? ['pending']
            : (
                $booking->booking_type === 'scheduled'
                ? ['pending', 'accepted']
                : []
            );

        // Check whether the current status permits cancellation.
        if (
            !in_array(
                $booking->status,
                $customerCancellableStatuses,
                true
            )
        ) {
            return back()->with(
                'error',
                'This booking can no longer be cancelled.'
            );
        }

        // Cancellation must happen before the response deadline.
        if (
            !$booking->response_deadline ||
            now()->greaterThanOrEqualTo($booking->response_deadline)
        ) {
            return back()->with(
                'error',
                'The cancellation deadline has passed. You can no longer cancel this booking.'
            );
        }

    

        // Cancel the booking and record its history.
       
DB::transaction(function () use ($booking, $validated) {

            // Lock the booking and recheck its current state.
            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Recalculate allowed statuses from the locked record.
            $lockedBookingStatuses =
                $lockedBooking->booking_type === 'asap'
                ? ['pending']
                : (
                    $lockedBooking->booking_type === 'scheduled'
                    ? ['pending', 'accepted']
                    : []
                );

            // Prevent cancellation if the status or deadline changed.
            if (
                !in_array(
                    $lockedBooking->status,
                    $lockedBookingStatuses,
                    true
                ) ||
                !$lockedBooking->response_deadline ||
                now()->greaterThanOrEqualTo(
                    $lockedBooking->response_deadline
                )
            ) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'booking' => 'This booking can no longer be cancelled.',
                ]);
            }

            // Update booking cancellation details.
            $lockedBooking->update([
                'status' => 'cancelled',
                'cancelled_by' => 'customer',
                'cancellation_reason' =>
                    $validated['cancellation_reason'] ?? null,
                'cancelled_at' => now(),
            ]);

            // Record booking status history.
            $lockedBooking->statusHistory()->create([
                'status' => 'cancelled',
                'updated_by' => auth()->id(),
                'note' => 'Booking cancelled by customer.',
            ]);

            
        });

        // Refresh booking data before sending the notification.
        $booking->refresh();
        $booking->load('provider.user');

        // Notify the provider.
        if ($booking->provider?->user) {
            $booking->provider->user->notify(
                new \App\Notifications\BookingNotification(
                    $booking,
                    'customer_cancelled'
                )
            );
        }

      // Return the success message.
return redirect()
    ->route('customer.booking.detail', $booking->id)
    ->with(
        'success',
        'Your booking has been cancelled successfully.'
    );
    }


    /**
     * =====================================================
     * CUSTOMER - VERIFY REFUND
     * =====================================================
     *
     * received:
     *     refund_status = refunded
     *
     * not_received:
     *     refund_status = pending
     *     refund_customer_response = not_received
     *
     */
    public function verifyRefund(
        Request $request,
        string $id
    ) {
        // =====================================================
        // GET CURRENT CUSTOMER
        // =====================================================

        $customer = \App\Models\Customer::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        // =====================================================
        // GET CUSTOMER BOOKING
        // =====================================================

        $booking = Booking::with('payment')
            ->where(
                'customer_id',
                $customer->id
            )
            ->findOrFail($id);


        // =====================================================
        // GET PAYMENT
        // =====================================================

        $payment = $booking->payment;


        // =====================================================
        // PAYMENT MUST EXIST
        // =====================================================

        if (!$payment) {

            return back()->with(
                'error',
                'Payment information was not found.'
            );
        }


        // =====================================================
        // REFUND MUST BE PENDING
        // =====================================================

        if (
            $payment->refund_status !== 'pending'
        ) {

            return back()->with(
                'error',
                'This refund is no longer waiting for verification.'
            );
        }


        // =====================================================
        // PROVIDER MUST HAVE SUBMITTED REFUND PROOF
        // =====================================================

        if (
            empty($payment->refund_slip_image) ||
            !$payment->refund_submitted_at
        ) {

            return back()->with(
                'error',
                'The provider has not submitted refund proof yet.'
            );
        }


        // =====================================================
        // VALIDATE CUSTOMER RESPONSE
        // =====================================================

        $validated = $request->validate([
            'refund_response' => [
                'required',
                'in:received,not_received',
            ],
        ]);


        // =====================================================
        // CUSTOMER RECEIVED REFUND
        // =====================================================

        if (
            $validated['refund_response'] === 'received'
        ) {

            $payment->update([

                'refund_status' =>
                    'refunded',

                'refund_customer_response' =>
                    'received',

                'refund_customer_verified_at' =>
                    now(),

                'refunded_at' =>
                    now(),

            ]);

            // =====================================================
// NOTIFY PROVIDER
// Customer confirmed refund
// =====================================================

            $providerUser = $booking->provider?->user;

            if ($providerUser) {

                $providerUser->notify(
                    new PaymentNotification(
                        $payment,
                        'refund_received'
                    )
                );
            }
            return redirect()
                ->route(
                    'customer.booking.detail',
                    $booking->id
                )
                ->with(
                    'success',
                    'Refund has been confirmed successfully.'
                );
        }


        // =====================================================
        // CUSTOMER DID NOT RECEIVE REFUND
        // =====================================================

        $payment->update([

            'refund_status' =>
                'pending',

            'refund_customer_response' =>
                'not_received',

            'refund_customer_verified_at' =>
                null,

            'refunded_at' =>
                null,

        ]);
        // =====================================================
// NOTIFY PROVIDER
// Customer reported refund not received
// =====================================================

        $providerUser = $booking->provider?->user;

        if ($providerUser) {

            $providerUser->notify(
                new PaymentNotification(
                    $payment,
                    'refund_not_received'
                )
            );
        }


        return redirect()
            ->route(
                'customer.booking.detail',
                $booking->id
            )
            ->with(
                'error',
                'You reported that the refund was not received. The provider needs to review the refund.'
            );
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
    public function update(
        Request $request,
        string $id
    ) {
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