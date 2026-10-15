<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Provider;
use App\Models\Service;
use App\Notifications\PaymentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Payment History
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | All payments belonging to this provider
        |--------------------------------------------------------------------------
        */

        $query = Payment::with([
            'booking.customer.user',
            'booking.area.city',
        ])
            ->whereHas('booking', function ($query) use ($provider) {
                $query->where(
                    'provider_id',
                    $provider->id
                );
            })
            ->orderByDesc('id');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                if (is_numeric($search)) {

                    $q->whereHas(
                        'booking',
                        function ($bookingQuery) use ($search) {

                            $bookingQuery->where(
                                'id',
                                $search
                            );
                        }
                    );
                }

                $q->orWhereHas(
                    'booking.customer.user',
                    function ($userQuery) use ($search) {

                        $userQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS FILTER
        |--------------------------------------------------------------------------
        |
        | This filter checks BOTH:
        |
        | - prepayment status
        | - final payment status
        |
        */

        if (
            $request->filled('status') &&
            $request->status !== 'all'
        ) {

            $status = $request->status;

            $query->where(function ($q) use ($status) {

                $q->where(
                    'payment_status',
                    $status
                )
                    ->orWhere(
                        'final_payment_status',
                        $status
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('method') &&
            $request->input('method') !== 'all'
        ) {

            $query->where(
                'payment_method',
                $request->input('method')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $payments = $query
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Load Services
        |--------------------------------------------------------------------------
        */

        $serviceIds = $payments
            ->getCollection()
            ->flatMap(function ($payment) {

                return $payment
                    ->booking
                        ?->chosen_services ?? [];

            })
            ->filter()
            ->unique()
            ->values();


        $services = Service::with('category')
            ->whereIn(
                'id',
                $serviceIds
            )
            ->get()
            ->keyBy('id');


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $baseQuery = Payment::whereHas(
            'booking',
            function ($query) use ($provider) {

                $query->where(
                    'provider_id',
                    $provider->id
                );
            }
        );


        $totalPayments =
            (clone $baseQuery)->count();


        /*
        |--------------------------------------------------------------------------
        | Pending
        |--------------------------------------------------------------------------
        |
        | Pending means either:
        |
        | prepayment pending
        | OR
        | final payment pending
        |
        */

        $pendingPayments =
            (clone $baseQuery)
                ->where(function ($query) {

                    $query->where(
                        'payment_status',
                        'pending'
                    )
                        ->orWhere(
                            'final_payment_status',
                            'pending'
                        );
                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Verified
        |--------------------------------------------------------------------------
        */

        $verifiedPayments =
            (clone $baseQuery)
                ->where(function ($query) {

                    $query->where(
                        'payment_status',
                        'verified'
                    )
                        ->orWhere(
                            'final_payment_status',
                            'verified'
                        );
                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Rejected
        |--------------------------------------------------------------------------
        */

        $rejectedPayments =
            (clone $baseQuery)
                ->where(function ($query) {

                    $query->where(
                        'payment_status',
                        'rejected'
                    )
                        ->orWhere(
                            'final_payment_status',
                            'rejected'
                        );
                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'provider.payments.index',
            compact(
                'payments',
                'services',
                'totalPayments',
                'pendingPayments',
                'verifiedPayments',
                'rejectedPayments'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Detail
    |--------------------------------------------------------------------------
    */

    public function show(Payment $payment)
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $payment->load([
            'booking.customer.user',
            'booking.area.city',
            'booking.statusHistory',
            'verifier',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $payment->booking &&
            $payment->booking->provider_id ==
            $provider->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Load Services
        |--------------------------------------------------------------------------
        */

        $serviceIds = collect(
            $payment
                ->booking
                ->chosen_services ?? []
        )
            ->filter()
            ->unique()
            ->values();


        $services = Service::with('category')
            ->whereIn(
                'id',
                $serviceIds
            )
            ->get()
            ->keyBy('id');


        /*
        |--------------------------------------------------------------------------
        | Determine Payment Stage
        |--------------------------------------------------------------------------
        */

        $paymentStage = 'none';


        /*
        |--------------------------------------------------------------------------
        | Prepayment Pending
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_status ===
            'pending'
        ) {

            $paymentStage = 'prepayment';
        }


        /*
        |--------------------------------------------------------------------------
        | Prepayment Rejected
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_status ===
            'rejected'
        ) {

            $paymentStage = 'prepayment';
        }


        /*
        |--------------------------------------------------------------------------
        | Final Payment Pending
        |--------------------------------------------------------------------------
        */

       if (
    $payment->final_payment_status === 'pending' &&
    !empty($payment->final_payment_submitted_at)
) {
    $paymentStage = 'final';
}


        /*
        |--------------------------------------------------------------------------
        | Final Payment Rejected
        |--------------------------------------------------------------------------
        */

        if (
            $payment->final_payment_status ===
            'rejected'
        ) {

            $paymentStage = 'final';
        }


        /*
        |--------------------------------------------------------------------------
        | Fully Paid
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_status === 'verified' &&
            $payment->final_payment_status === 'verified'
        ) {

            $paymentStage = 'completed';
        }


        return view(
            'provider.payments.show',
            compact(
                'payment',
                'services',
                'paymentStage'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY PAYMENT
    |--------------------------------------------------------------------------
    |
    | This method automatically determines whether the provider
    | is verifying:
    |
    | 1. Prepayment
    | 2. Final payment
    |
    */

    public function verify(Payment $payment)
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $payment->booking &&
            $payment->booking->provider_id ==
            $provider->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT
        |--------------------------------------------------------------------------
        |
        | Final payment takes priority when it is pending.
        |
        */

        if (
            $payment->final_payment_status === 'pending' &&
            empty($payment->final_payment_submitted_at)
        ) {
            return back()->with(
                'error',
                'The customer has not submitted the final payment yet.'
            );
        }

        if (
            $payment->final_payment_status ===
            'pending'
        ) {

            DB::transaction(
                function () use ($payment) {

                    $payment->update([
                        'final_payment_status' =>
                            'verified',

                        'final_verified_at' =>
                            now(),

                        'final_rejection_reason' =>
                            null,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Notify Customer
                    |--------------------------------------------------------------------------
                    */

                    $customerUser =
                        $payment
                            ->booking
                            ?->customer
                                ?->user;


                    if ($customerUser) {

                        $customerUser->notify(
                            new PaymentNotification(
                                $payment,
                                'payment_verified'
                            )
                        );
                    }
                }
            );


            return redirect()
                ->route(
                    'provider.payments.show',
                    $payment->id
                )
                ->with(
                    'success',
                    'Final payment has been verified successfully.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PREPAYMENT
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_status ===
            'pending'
        ) {

            DB::transaction(
                function () use ($payment) {

                    $payment->update([
                        'payment_status' =>
                            'verified',

                        'verified_by' =>
                            auth()->id(),

                        'verified_at' =>
                            now(),

                        'rejection_reason' =>
                            null,
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Notify Customer
                    |--------------------------------------------------------------------------
                    */

                    $customerUser =
                        $payment
                            ->booking
                            ?->customer
                                ?->user;


                    if ($customerUser) {

                        $customerUser->notify(
                            new PaymentNotification(
                                $payment,
                                'payment_verified'
                            )
                        );
                    }
                }
            );


            return redirect()
                ->route(
                    'provider.payments.show',
                    $payment->id
                )
                ->with(
                    'success',
                    'Prepayment has been verified successfully.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Already Verified
        |--------------------------------------------------------------------------
        */

        if (
            $payment->final_payment_status ===
            'verified'
        ) {

            return back()->with(
                'info',
                'The final payment has already been verified.'
            );
        }


        if (
            $payment->payment_status ===
            'verified'
        ) {

            return back()->with(
                'info',
                'The prepayment has already been verified.'
            );
        }


        return back()->with(
            'error',
            'There is no payment waiting for verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT PAYMENT
    |--------------------------------------------------------------------------
    |
    | Automatically determines whether the rejection is:
    |
    | - prepayment rejection
    | - final payment rejection
    |
    */

    public function reject(
        Request $request,
        Payment $payment
    ) {

        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $payment->booking &&
            $payment->booking->provider_id ==
            $provider->id,
            403
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'rejection_reason' => [
                    'required',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'rejection_reason.required' =>
                    'Please provide a reason for rejecting the payment.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYMENT REJECTION
        |--------------------------------------------------------------------------
        */

        if (
            $payment->final_payment_status === 'pending' &&
            empty($payment->final_payment_submitted_at)
        ) {
            return back()->with(
                'error',
                'The customer has not submitted the final payment yet.'
            );
        }

        if (
            $payment->final_payment_status ===
            'pending'
        ) {

            DB::transaction(
                function () use ($payment, $validated) {

                    $payment->update([
                        'final_payment_status' =>
                            'rejected',

                        'final_rejection_reason' =>
                            $validated[
                                'rejection_reason'
                            ],
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Notify Customer
                    |--------------------------------------------------------------------------
                    */

                    $customerUser =
                        $payment
                            ->booking
                            ?->customer
                                ?->user;


                    if ($customerUser) {

                        $customerUser->notify(
                            new PaymentNotification(
                                $payment,
                                'payment_rejected',
                                $validated[
                                    'rejection_reason'
                                ]
                            )
                        );
                    }
                }
            );


            return redirect()
                ->route(
                    'provider.payments.show',
                    $payment->id
                )
                ->with(
                    'success',
                    'Final payment has been rejected and the customer has been notified.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PREPAYMENT REJECTION
        |--------------------------------------------------------------------------
        */

        if (
            $payment->payment_status ===
            'pending'
        ) {

            DB::transaction(
                function () use ($payment, $validated) {

                    $payment->update([
                        'payment_status' =>
                            'rejected',

                        'rejection_reason' =>
                            $validated[
                                'rejection_reason'
                            ],

                        'verified_by' =>
                            auth()->id(),

                        'verified_at' =>
                            now(),
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | Notify Customer
                    |--------------------------------------------------------------------------
                    */

                    $customerUser =
                        $payment
                            ->booking
                            ?->customer
                                ?->user;


                    if ($customerUser) {

                        $customerUser->notify(
                            new PaymentNotification(
                                $payment,
                                'payment_rejected',
                                $validated[
                                    'rejection_reason'
                                ]
                            )
                        );
                    }
                }
            );


            return redirect()
                ->route(
                    'provider.payments.show',
                    $payment->id
                )
                ->with(
                    'success',
                    'Prepayment has been rejected and the customer has been notified.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ALREADY VERIFIED
        |--------------------------------------------------------------------------
        */

        if (
            $payment->final_payment_status ===
            'verified'
        ) {

            return back()->with(
                'error',
                'An approved final payment cannot be rejected.'
            );
        }


        if (
            $payment->payment_status ===
            'verified'
        ) {

            return back()->with(
                'error',
                'An approved prepayment cannot be rejected.'
            );
        }


        return back()->with(
            'error',
            'There is no payment waiting for verification.'
        );
    }
    public function checkUpdates(Request $request)
    {
        $provider = Provider::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $query = Payment::whereHas(
            'booking',
            function ($query) use ($provider) {
                $query->where(
                    'provider_id',
                    $provider->id
                );
            }
        );

        $pendingCount = (clone $query)
            ->where(function ($query) {
                $query
                    ->where('payment_status', 'pending')
                    ->orWhere(
                        'final_payment_status',
                        'pending'
                    );
            })
            ->count();

        $latestPendingPayment = (clone $query)
            ->where(function ($query) {
                $query
                    ->where('payment_status', 'pending')
                    ->orWhere(
                        'final_payment_status',
                        'pending'
                    );
            })
            ->latest('updated_at')
            ->first();

        return response()->json([
            'success' => true,

            'pending_count' =>
                $pendingCount,

            'latest_pending_id' =>
                $latestPendingPayment?->id,

            'latest_pending_updated_at' =>
                $latestPendingPayment?->updated_at?->timestamp,
        ]);
    }
}