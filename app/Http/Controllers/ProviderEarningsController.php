<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class ProviderEarningsController extends Controller
{
    public function index(Request $request)
    {
        $provider = Provider::where('user_id', auth()->id())
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Base Payment Query
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

        /*
        |--------------------------------------------------------------------------
        | Earnings Summary
        |--------------------------------------------------------------------------
        */

        $totalEarnings = (clone $baseQuery)
            ->where('payment_status', 'verified')
            ->sum('service_amount');

        $thisWeekEarnings = (clone $baseQuery)
            ->where('payment_status', 'verified')
            ->whereBetween(
                'verified_at',
                [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]
            )
            ->sum('service_amount');

        $thisMonthEarnings = (clone $baseQuery)
            ->where('payment_status', 'verified')
            ->whereBetween(
                'verified_at',
                [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ]
            )
            ->sum('service_amount');

        $completedJobs = (clone $baseQuery)
            ->where('payment_status', 'verified')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Verified Payment History
        |--------------------------------------------------------------------------
        */

        $query = Payment::with([
            'booking.customer.user',
            'booking.area.city',
        ])
        ->whereHas(
            'booking',
            function ($query) use ($provider) {
                $query->where(
                    'provider_id',
                    $provider->id
                );
            }
        )
        ->where(
            'payment_status',
            'verified'
        )
        ->latest('verified_at');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas(
                    'booking',
                    function ($bookingQuery) use ($search) {

                        $bookingQuery->where(
                            'id',
                            $search
                        );
                    }
                );

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
        | Period Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('period')) {

            switch ($request->period) {

                case 'this_month':

                    $query->whereBetween(
                        'verified_at',
                        [
                            now()->startOfMonth(),
                            now()->endOfMonth(),
                        ]
                    );

                    break;

                case 'last_month':

                    $query->whereBetween(
                        'verified_at',
                        [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth(),
                        ]
                    );

                    break;

                case 'this_year':

                    $query->whereBetween(
                        'verified_at',
                        [
                            now()->startOfYear(),
                            now()->endOfYear(),
                        ]
                    );

                    break;
            }
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
        |
        | Booking does not have a service() relationship.
        | Services are stored as IDs inside chosen_services.
        |
        */

        $serviceIds = $payments->getCollection()
            ->flatMap(function ($payment) {

                return $payment->booking?->chosen_services ?? [];

            })
            ->filter()
            ->unique()
            ->values();

        $services = Service::with('category')
            ->whereIn('id', $serviceIds)
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'provider.earnings',
            compact(
                'totalEarnings',
                'thisWeekEarnings',
                'thisMonthEarnings',
                'completedJobs',
                'payments',
                'services'
            )
        );
    }
}