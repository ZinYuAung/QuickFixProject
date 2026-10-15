@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')

<div class="admin-dashboard admin-bookings-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
                Bookings
            </h1>

            <p>
                View and manage customer service bookings on QuickFix.
            </p>

        </div>

        <div class="admin-page-header-actions">

       

        </div>

    </div>


    <!-- =====================================================
         BOOKING STATISTICS
    ====================================================== -->

    <div class="admin-stat-grid admin-booking-stat-grid">

        <!-- Total -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon bookings">

                <i class="fa-solid fa-calendar-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Total Bookings
                </span>

                <h2>
                    {{ $totalBookings }}
                </h2>

                <div class="admin-stat-positive">

                    <i class="fa-solid fa-arrow-up"></i>

                    <span>
                        All bookings
                    </span>

                </div>

            </div>

        </div>


        <!-- Pending -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon providers">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Pending
                </span>

                <h2>
                    {{ $pendingBookings }}
                </h2>

                <div class="admin-stat-note">
                    Waiting for provider
                </div>

            </div>

        </div>


        <!-- In Progress -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon services">

                <i class="fa-solid fa-spinner"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    In Progress
                </span>

                <h2>
                    {{ $inProgressBookings }}
                </h2>

                <div class="admin-stat-note">
                    Currently processing
                </div>

            </div>

        </div>


        <!-- Completed -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon users">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Completed
                </span>

                <h2>
                    {{ $completedBookings }}
                </h2>

                <div class="admin-stat-note">
                    Successfully completed
                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         ALL BOOKINGS
    ====================================================== -->

    <div class="admin-card admin-bookings-card">

        <!-- Card Header -->

        <div class="admin-card-header">

            <div>

                <h3>
                    All Bookings
                </h3>

                <p>
                    Customer booking information
                </p>

            </div>

        </div>


        <!-- =================================================
             TOOLBAR
        ================================================== -->

        <div class="admin-booking-toolbar">

            <!-- Search -->

            <div class="admin-booking-search">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="bookingSearch"
                    placeholder="Search bookings..."
                    autocomplete="off"
                >

            </div>


            <!-- Status -->

            <div class="admin-booking-filter">

                <select
                    id="bookingStatusFilter"
                    class="admin-filter-select"
                >

                    <option value="all">
                        All Status
                    </option>

                    <option value="pending">
                        Pending
                    </option>

                    <option value="accepted">
                        Accepted
                    </option>

                    <option value="on_the_way">
                        On The Way
                    </option>

                    <option value="arrived">
                        Arrived
                    </option>

                    <option value="in_progress">
                        In Progress
                    </option>

                    <option value="completed">
                        Completed
                    </option>

                    <option value="cancelled">
                        Cancelled
                    </option>

                    <option value="timeout">
                        Timeout
                    </option>

                </select>

            </div>


            <!-- Service -->

            <div class="admin-booking-filter">

                <select
                    id="bookingServiceFilter"
                    class="admin-filter-select"
                >

                    <option value="all">
                        All Services
                    </option>

                   
@php
    $serviceOptions = collect();

    foreach ($bookings as $booking) {
        foreach ($booking->chosen_services ?? [] as $service) {
            if (is_array($service)) {
                $name = $service['name'] ?? null;
                $serviceId = $service['service_id']
                    ?? $service['id']
                    ?? null;
            } elseif (is_object($service)) {
                $name = $service->name ?? null;
                $serviceId = $service->service_id
                    ?? $service->id
                    ?? null;
            } else {
                $name = null;
                $serviceId = is_numeric($service)
                    ? $service
                    : null;
            }

            $name = $name
                ?: ($serviceNamesMap[$serviceId] ?? null);

            if ($name) {
                $serviceOptions->push($name);
            }
        }
    }
@endphp

@foreach($serviceOptions->unique()->sort() as $serviceName)
    <option value="{{ $serviceName }}">
        {{ $serviceName }}
    </option>
@endforeach

                </select>

            </div>


            <!-- Date -->

            <div class="admin-booking-filter">

                <select
                    id="bookingDateFilter"
                    class="admin-filter-select"
                >

                    <option value="all">
                        All Dates
                    </option>

                    <option value="today">
                        Today
                    </option>

                    <option value="week">
                        This Week
                    </option>

                    <option value="month">
                        This Month
                    </option>

                </select>

            </div>

        </div>


        <!-- =================================================
             BOOKING TABLE
        ================================================== -->

        <div class="admin-table-wrapper">

            <table
                class="admin-bookings-table admin-bookings-management-table"
                id="bookingsTable"
            >

                <thead>

                    <tr>

                        <th>
                            Booking
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Provider
                        </th>

                        <th>
                            Service
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($bookings as $booking)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | STATUS
                            |--------------------------------------------------------------------------
                            */

                            $status = strtolower(
                                $booking->status ?? 'pending'
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | SERVICE
                            |--------------------------------------------------------------------------
                            |
                            | Services are stored in chosen_services JSON.
                            |
                            */

                           
$chosenServices = $booking->chosen_services ?? [];

$serviceNames = collect($chosenServices)
    ->map(function ($service) use ($serviceNamesMap) {
        if (is_array($service)) {
            $name = $service['name'] ?? null;
            $serviceId = $service['service_id']
                ?? $service['id']
                ?? null;
        } elseif (is_object($service)) {
            $name = $service->name ?? null;
            $serviceId = $service->service_id
                ?? $service->id
                ?? null;
        } else {
            $name = null;
            $serviceId = is_numeric($service)
                ? $service
                : null;
        }

        return $name
            ?: ($serviceNamesMap[$serviceId] ?? null);
    })
    ->filter()
    ->unique()
    ->implode(', ');

$serviceNames = $serviceNames ?: 'No service';



                            /*
                            |--------------------------------------------------------------------------
                            | DATE
                            |--------------------------------------------------------------------------
                            */

                            $bookingDate =
                                $booking->scheduled_at;


                            if (
                                $bookingDate &&
                                $bookingDate->isToday()
                            ) {

                                $dateFilter = 'today';

                            } elseif (
                                $bookingDate &&
                                $bookingDate->isCurrentWeek()
                            ) {

                                $dateFilter = 'week';

                            } elseif (
                                $bookingDate &&
                                $bookingDate->isCurrentMonth()
                            ) {

                                $dateFilter = 'month';

                            } else {

                                $dateFilter = 'all';

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | CUSTOMER
                            |--------------------------------------------------------------------------
                            */

                            $customerName =
                                $booking->customer?->user?->name
                                ?? 'Unknown Customer';


                            /*
                            |--------------------------------------------------------------------------
                            | PROVIDER
                            |--------------------------------------------------------------------------
                            */

                            $providerName =
                                $booking->provider?->user?->name
                                ?? 'Unassigned';


                            /*
                            |--------------------------------------------------------------------------
                            | INITIALS
                            |--------------------------------------------------------------------------
                            */

                            $customerInitials =
                                collect(
                                    preg_split(
                                        '/\s+/',
                                        trim($customerName)
                                    )
                                )
                                    ->filter()
                                    ->map(
                                        fn ($word) =>
                                            strtoupper(
                                                substr($word, 0, 1)
                                            )
                                    )
                                    ->take(2)
                                    ->implode('');


                            $providerInitials =
                                collect(
                                    preg_split(
                                        '/\s+/',
                                        trim($providerName)
                                    )
                                )
                                    ->filter()
                                    ->map(
                                        fn ($word) =>
                                            strtoupper(
                                                substr($word, 0, 1)
                                            )
                                    )
                                    ->take(2)
                                    ->implode('');


                            /*
                            |--------------------------------------------------------------------------
                            | TOTAL AMOUNT
                            |--------------------------------------------------------------------------
                            */

                            $totalAmount =
                                $booking->total_amount ?? 0;

                        @endphp


                        <tr
                            data-status="{{ $status }}"
                            data-service="{{ $serviceNames }}"
                            data-date="{{ $dateFilter }}"
                        >

                            <!-- =====================================
                                 BOOKING
                            ====================================== -->

                            <td>

                                <div class="admin-booking-id">

                                    <strong>
                                        #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                                    </strong>

                                    <span>
                                        Home service
                                    </span>

                                </div>

                            </td>


                            <!-- =====================================
                                 CUSTOMER
                            ====================================== -->

                            <td>

                                <div class="admin-booking-person">

                                    <div class="admin-booking-avatar">

                                        {{ $customerInitials ?: '?' }}

                                    </div>

                                    <div>

                                        <strong>
                                            {{ $customerName }}
                                        </strong>

                                        <span>
                                            #CUS-{{ $booking->customer_id }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <!-- =====================================
                                 PROVIDER
                            ====================================== -->

                            <td>

                                <div class="admin-booking-person">

                                    <div class="admin-booking-avatar provider">

                                        {{ $providerInitials ?: '?' }}

                                    </div>

                                    <div>

                                        <strong>
                                            {{ $providerName }}
                                        </strong>

                                        <span>
                                            #PRO-{{ $booking->provider_id }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <!-- =====================================
                                 SERVICE
                            ====================================== -->

                            <td>

                                <div class="admin-booking-service">

                                    <i class="fa-solid fa-screwdriver-wrench"></i>

                                    <span>
                                        {{ $serviceNames }}
                                    </span>

                                </div>

                            </td>


                            <!-- =====================================
                                 DATE
                            ====================================== -->

                            <td>

                                <div class="admin-booking-date">

                                    @if($bookingDate)

                                        <strong>
                                            {{ $bookingDate->format('M d, Y') }}
                                        </strong>

                                        <span>
                                            {{ $bookingDate->format('h:i A') }}
                                        </span>

                                    @else

                                        <strong>
                                            No scheduled date
                                        </strong>

                                    @endif

                                </div>

                            </td>


                            <!-- =====================================
                                 AMOUNT
                            ====================================== -->

                            <td>

                                <strong>

                                    {{ number_format((float) $totalAmount) }}
                                    MMK

                                </strong>

                            </td>


                            <!-- =====================================
                                 STATUS
                            ====================================== -->

                            <td>

                                <span
                                    class="admin-booking-status {{ $status }}"
                                >

                                    <span class="status-dot"></span>

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $status
                                        )
                                    ) }}

                                </span>

                            </td>


                            <!-- =====================================
                                 ACTION
                            ====================================== -->

                            <td>

                                <div class="admin-booking-actions">

                                    <a
                                        href="{{ route(
                                            'admin.bookings.show',
                                            $booking->id
                                        ) }}"
                                        class="view-booking"
                                        title="View Booking"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                    </a>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center"
                            >

                                No bookings found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="admin-bookings-footer">

            <span id="bookingResultCount">

                Showing

                {{ $bookings->firstItem() ?? 0 }}

                to

                {{ $bookings->lastItem() ?? 0 }}

                of

                {{ $bookings->total() }}

                bookings

            </span>


            <div class="admin-pagination">

                {{ $bookings->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
