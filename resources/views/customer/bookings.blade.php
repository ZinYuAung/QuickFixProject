<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        QuickFix - My Bookings
    </title>


    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- =====================================================
         QUICKFIX GLOBAL CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >


    <!-- =====================================================
         CUSTOMER CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/customer.css') }}"
    >


    <!-- =====================================================
         CUSTOMER BOOKINGS CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/customer-bookings.css') }}"
    >

</head>


<body>

<div
    class="customer-layout"
    id="customerLayout"
>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside
        class="customer-sidebar"
        id="customerSidebar"
    >


        <!-- =================================================
             SIDEBAR HEADER
        ================================================== -->

        <div class="customer-sidebar-header">

            <a
                href="{{ route('customer.dashboard')}}"
                class="customer-logo"
            >

                <span class="customer-logo-icon">

                    <i class="fa-solid fa-wrench"></i>

                </span>

                <span class="customer-logo-text">
                    QuickFix
                </span>

            </a>


            <button
                type="button"
                class="customer-sidebar-toggle"
                id="customerSidebarToggle"
                aria-label="Toggle sidebar"
                title="Toggle sidebar"
            >

                <i class="fa-solid fa-bars"></i>

            </button>

        </div>


         <!-- Customer Profile -->
            <div class="customer-profile">

                <div class="customer-avatar">

    @if(auth()->user()->profile_image)

        <img
            src="{{ asset('storage/' . auth()->user()->profile_image) }}"
            alt="{{ auth()->user()->name ?? 'Customer' }}"
        >

    @else

        <i class="fa-solid fa-user"></i>

    @endif

</div>

                <div class="customer-profile-info">

                    <h4>
                        {{ auth()->user()->name ?? 'Customer' }}
                    </h4>

                    <span>
                        Customer
                    </span>

                </div>

            </div>
        <!-- =================================================
             SIDEBAR MENU
        ================================================== -->

       <nav class="customer-sidebar-menu">

    <!-- Dashboard -->

    <a
        href="{{ route('customer.dashboard') }}"
        class="customer-menu-link"
    >
        <i class="fa-solid fa-house"></i>

        <span class="customer-menu-text">
            Dashboard
        </span>
    </a>


    <!-- My Services -->

    <a
        href="{{ route('customer.services') }}"
        class="customer-menu-link"
    >
        <i class="fa-solid fa-briefcase"></i>

        <span class="customer-menu-text">
            My Services
        </span>
    </a>


    <!-- My Bookings -->

    <a
        href="{{ route('customer.bookings') }}"
        class="customer-menu-link active"
    >
        <i class="fa-solid fa-calendar-check"></i>

        <span class="customer-menu-text">
            My Bookings
        </span>
    </a>


    <!-- Payment -->

    <a
        href="{{ route('customer.payments') }}"
        class="customer-menu-link"
    >
        <i class="fa-solid fa-credit-card"></i>

        <span class="customer-menu-text">
            Payment
        </span>
    </a>


    <!-- Chat -->

    <a
        href="{{ route('customer.chats') }}"
        class="customer-menu-link"
    >
        <i class="fa-solid fa-comments"></i>

        <span class="customer-menu-text">
            Chat
        </span>

        <span
            id="customerSidebarChatUnread"
            class="customer-sidebar-chat-unread"
            style="display: none;"
        ></span>
    </a>


    <!-- My Profile -->

    <a
        href="{{ route('customer.profile') }}"
        class="customer-menu-link"
    >
        <i class="fa-solid fa-user-circle"></i>

        <span class="customer-menu-text">
            My Profile
        </span>
    </a>

</nav>

        <!-- =================================================
             LOGOUT
        ================================================== -->

        <div class="customer-sidebar-bottom">

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="customer-menu-link customer-logout"
                >

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span class="customer-menu-text">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </aside>


    <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

    <div
        class="customer-overlay"
        id="customerOverlay"
    ></div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="customer-main">


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="customer-header">

            <div class="customer-header-left">

                <button
                    type="button"
                    class="customer-mobile-menu"
                    id="customerMobileMenu"
                    aria-label="Open menu"
                    title="Open menu"
                >

                    <i class="fa-solid fa-bars"></i>

                </button>


                <div class="customer-header-title">

                    <h1>
                        My Bookings
                    </h1>

                    <p>
                        View and track your home service bookings.
                    </p>

                </div>

            </div>


           <!-- Header Actions -->

<div class="customer-header-actions">


    <!-- Notifications -->

    <a
        href="{{ route('customer.notifications') }}"
        class="customer-notification-button"
        aria-label="Notifications"
        title="Notifications"
    >

        <i class="fa-solid fa-bell"></i>


        @php

            $unreadNotificationCount = auth()->user()
                ->unreadNotifications()
                ->count();

        @endphp


        @if($unreadNotificationCount > 0)

            <span class="customer-notification-badge">

                {{ $unreadNotificationCount > 99
                    ? '99+'
                    : $unreadNotificationCount
                }}

            </span>

        @endif

    </a>



    <!-- Theme Toggle -->

    <button
        type="button"
        class="quickfix-theme-toggle"
        id="themeToggle"
        aria-label="Toggle dark mode"
        title="Toggle dark mode"
    >

        <i
            class="fa-solid fa-moon"
            id="themeIcon"
        ></i>

    </button>


</div>

        </header>


        <!-- =================================================
             BOOKING CONTENT
        ================================================== -->

        <section class="customer-bookings-content">


            <!-- =================================================
                 SUMMARY CARDS
            ================================================== -->

            <div class="booking-summary-grid">


                <!-- Total -->

                <div class="booking-summary-card">

                    <div class="booking-summary-icon total">

                        <i class="fa-solid fa-calendar-days"></i>

                    </div>

                    <div class="booking-summary-info">

                        <span>
                            Total Bookings
                        </span>

                        <strong>
                            {{ $totalBookings }}
                        </strong>

                    </div>

                </div>


                <!-- Pending -->

                <div class="booking-summary-card">

                    <div class="booking-summary-icon pending">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div class="booking-summary-info">

                        <span>
                            Pending
                        </span>

                        <strong>
                            {{ $pendingBookings }}
                        </strong>

                    </div>

                </div>


                <!-- Completed -->

                <div class="booking-summary-card">

                    <div class="booking-summary-icon completed">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div class="booking-summary-info">

                        <span>
                            Completed
                        </span>

                        <strong>
                            {{ $completedBookings }}
                        </strong>

                    </div>

                </div>


                <!-- Cancelled -->

                <div class="booking-summary-card">

                    <div class="booking-summary-icon cancelled">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div class="booking-summary-info">

                        <span>
                            Cancelled
                        </span>

                        <strong>
                            {{ $cancelledBookings }}
                        </strong>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 BOOKINGS LIST CARD
            ================================================== -->

            <div class="customer-profile-card bookings-list-card">


                <!-- =================================================
                     CARD HEADER
                ================================================== -->

                <div class="booking-card-header">

                    <div class="booking-card-title">

                        <div class="profile-security-header-icon">

                            <i class="fa-solid fa-list-check"></i>

                        </div>

                        <div>

                            <h3>
                                My Bookings
                            </h3>

                            <p>
                                Your home service booking history and current jobs.
                            </p>

                        </div>

                    </div>


                    <a
                        href="{{ route('customer.booking.create') }}"
                        class="booking-primary-button"
                    >

                        <i class="fa-solid fa-plus"></i>

                        Book a Service

                    </a>

                </div>


                <!-- =================================================
                     FILTER + SEARCH
                ================================================== -->

                <div class="booking-toolbar">


                    <div class="booking-filters">


                        <!-- All -->

                        <button
                            type="button"
                            class="booking-filter-btn active"
                            data-filter="all"
                        >

                            All

                        </button>


                        <!-- Pending -->

                        <button
                            type="button"
                            class="booking-filter-btn"
                            data-filter="pending"
                        >

                            Pending

                        </button>


                        <!-- Active -->

                        <button
                            type="button"
                            class="booking-filter-btn"
                            data-filter="active"
                        >

                            Active

                        </button>


                        <!-- Completed -->

                        <button
                            type="button"
                            class="booking-filter-btn"
                            data-filter="completed"
                        >

                            Completed

                        </button>


                        <!-- Cancelled -->

                        <button
                            type="button"
                            class="booking-filter-btn"
                            data-filter="cancelled"
                        >

                            Cancelled

                        </button>

                    </div>


                    <!-- Search -->

                    <div class="booking-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            id="bookingSearch"
                            placeholder="Search bookings..."
                            autocomplete="off"
                        >

                    </div>

                </div>


                <!-- =================================================
                     DESKTOP TABLE
                ================================================== -->

                <div class="bookings-table-wrapper">

                    <table class="bookings-table">

                        <thead>

                            <tr>

                                <th>
                                    Booking ID
                                </th>

                                <th>
                                    Service
                                </th>

                                <th>
                                    Provider
                                </th>

                                <th>
                                    Date & Time
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


                            @forelse ($bookings as $booking)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | STATUS
                                    |--------------------------------------------------------------------------
                                    */

                                    $isActiveStatus = in_array(
                                        $booking->status,
                                        [
                                            'accepted',
                                            'on_the_way',
                                            'arrived',
                                            'in_progress',
                                        ],
                                        true
                                    );


                                    $statusClass = match (
                                        $booking->status
                                    ) {

                                        'pending'
                                            => 'pending',

                                        'accepted'
                                            => 'accepted',

                                        'on_the_way'
                                            => 'accepted',

                                        'arrived'
                                            => 'accepted',

                                        'in_progress'
                                            => 'accepted',

                                        'completed'
                                            => 'completed',

                                        'cancelled'
                                            => 'cancelled',

                                        'timeout'
                                            => 'cancelled',

                                        default
                                            => 'pending',

                                    };


                                    $filterStatus = match (
                                        true
                                    ) {

                                        $booking->status === 'pending'
                                            => 'pending',

                                        $isActiveStatus
                                            => 'active',

                                        $booking->status === 'completed'
                                            => 'completed',

                                        in_array(
                                            $booking->status,
                                            [
                                                'cancelled',
                                                'timeout',
                                            ],
                                            true
                                        )
                                            => 'cancelled',

                                        default
                                            => 'all',

                                    };


                                    $statusLabel = match (
                                        $booking->status
                                    ) {

                                        'pending'
                                            => 'Pending',

                                        'accepted'
                                            => 'Accepted',

                                        'on_the_way'
                                            => 'On The Way',

                                        'arrived'
                                            => 'Arrived',

                                        'in_progress'
                                            => 'In Progress',

                                        'completed'
                                            => 'Completed',

                                        'cancelled'
                                            => 'Cancelled',

                                        'timeout'
                                            => 'Timeout',

                                        default
                                            => ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $booking->status
                                                )
                                            ),

                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | SELECTED SERVICES
                                    |--------------------------------------------------------------------------
                                    */

                                    $selectedServices =
                                        $booking->selectedServices
                                            ?? collect();


                                    $firstService =
                                        $selectedServices->first();


                                    $serviceCount =
                                        $selectedServices->count();


                                    /*
                                    |--------------------------------------------------------------------------
                                    | SERVICE ICON
                                    |--------------------------------------------------------------------------
                                    */

                                    $serviceIcon =
                                        'fa-screwdriver-wrench';


                                    if ($firstService) {

                                        $serviceName =
                                            strtolower(
                                                $firstService->name
                                            );


                                        if (
                                            str_contains(
                                                $serviceName,
                                                'aircon'
                                            ) ||
                                            str_contains(
                                                $serviceName,
                                                'air conditioner'
                                            )
                                        ) {

                                            $serviceIcon =
                                                'fa-snowflake';

                                        }
                                        elseif (
                                            str_contains(
                                                $serviceName,
                                                'plumb'
                                            )
                                        ) {

                                            $serviceIcon =
                                                'fa-faucet-drip';

                                        }
                                        elseif (
                                            str_contains(
                                                $serviceName,
                                                'electric'
                                            )
                                        ) {

                                            $serviceIcon =
                                                'fa-bolt';

                                        }
                                        elseif (
                                            str_contains(
                                                $serviceName,
                                                'clean'
                                            )
                                        ) {

                                            $serviceIcon =
                                                'fa-broom';

                                        }

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | SERVICE DISPLAY NAME
                                    |--------------------------------------------------------------------------
                                    */

                                    $serviceDisplayName =
                                        $firstService
                                            ? $firstService->name
                                            : 'Service';


                                    if ($serviceCount > 1) {

                                        $serviceDisplayName .=
                                            ' +' .
                                            ($serviceCount - 1) .
                                            ' more';

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | SEARCH TEXT
                                    |--------------------------------------------------------------------------
                                    */

                                    $searchText = strtolower(
                                        'BK-' .
                                        str_pad(
                                            $booking->id,
                                            3,
                                            '0',
                                            STR_PAD_LEFT
                                        ) .
                                        ' ' .
                                        $serviceDisplayName .
                                        ' ' .
                                        (
                                            $booking
                                                ->provider
                                                ?->user
                                                ?->name
                                                ?? ''
                                        ) .
                                        ' ' .
                                        $statusLabel
                                    );

                                @endphp


                                <!-- =================================================
                                     BOOKING ROW
                                ================================================== -->

                              <tr
    class="booking-row"
    data-status="{{ $filterStatus }}"
    data-real-status="{{ $booking->status }}"
    data-search="{{ $searchText }}"
    data-booking-id="BK-{{
        str_pad(
            $booking->id,
            3,
            '0',
            STR_PAD_LEFT
        )
    }}"
    data-booking-id-number="{{ $booking->id }}"
    data-status-url="{{ route('customer.booking.status', $booking->id) }}"
>

                                    <!-- Booking ID -->

                                    <td>

                                        <span class="booking-id">

                                            BK-{{
                                                str_pad(
                                                    $booking->id,
                                                    3,
                                                    '0',
                                                    STR_PAD_LEFT
                                                )
                                            }}

                                        </span>

                                    </td>


                                    <!-- Service -->

                                    <td>

                                        <div class="booking-service">

                                            <div class="booking-service-icon">

                                                <i
                                                    class="fa-solid {{ $serviceIcon }}"
                                                ></i>

                                            </div>


                                            <div>

                                                <strong>
                                                    {{ $serviceDisplayName }}
                                                </strong>

                                                <span>

                                                    {{
                                                        $firstService
                                                            ?->category
                                                            ?->name
                                                            ?? 'Home Service'
                                                    }}

                                                </span>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Provider -->

                                    <td>

                                        @if ($booking->provider)

                                            {{
                                                $booking
                                                    ->provider
                                                    ->user
                                                    ?->name
                                                    ?? 'Provider'
                                            }}

                                        @else

                                            <span class="text-muted">

                                                Waiting for provider

                                            </span>

                                        @endif

                                    </td>


                                    <!-- Date & Time -->

                                    <td>

                                        <div class="booking-date">

                                            <strong>

                                                {{
                                                    $booking->scheduled_at
                                                        ? $booking
                                                            ->scheduled_at
                                                            ->format(
                                                                'M d, Y'
                                                            )
                                                        : 'Not scheduled'
                                                }}

                                            </strong>

                                            @if ($booking->scheduled_at)

                                                <span>

                                                    {{
                                                        $booking
                                                            ->scheduled_at
                                                            ->format(
                                                                'h:i A'
                                                            )
                                                    }}

                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    <!-- Amount -->

                                    <td>

                                        <strong class="booking-amount">

                                            MMK
                                            {{
                                                number_format(
                                                    $booking->total_amount
                                                )
                                            }}

                                        </strong>

                                    </td>


                                    <!-- Status -->

                                    <td>

                                        <span
                                            class="booking-status {{ $statusClass }}"
                                        >

                                            <span></span>

                                            {{ $statusLabel }}

                                        </span>

                                    </td>


                                    <!-- Action -->

                                    <td>

                                        <a
                                            href="{{ route(
                                                'customer.booking.detail',
                                                $booking->id
                                            ) }}"
                                            class="booking-view-button"
                                        >

                                            <i class="fa-solid fa-eye"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>


                            @empty


                                <!-- =================================================
                                     NO BOOKINGS
                                ================================================== -->

                                <tr class="database-empty-row">

                                    <td
                                        colspan="7"
                                        class="text-center"
                                    >

                                        <div class="booking-empty">

                                            <div class="booking-empty-icon">

                                                <i
                                                    class="fa-solid fa-calendar-xmark"
                                                ></i>

                                            </div>

                                            <h4>
                                                No bookings yet
                                            </h4>

                                            <p>
                                                You have not created any
                                                service bookings yet.
                                            </p>

                                            <a
                                                href="{{
                                                    route(
                                                        'customer.booking.create'
                                                    )
                                                }}"
                                                class="booking-primary-button"
                                            >

                                                <i class="fa-solid fa-plus"></i>

                                                Book a Service

                                            </a>

                                        </div>

                                    </td>

                                </tr>


                            @endforelse


                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     MOBILE BOOKING CARDS
                ================================================== -->

                <div class="mobile-bookings-list">


                    @forelse ($bookings as $booking)

                        @php

                            $selectedServices =
                                $booking->selectedServices
                                    ?? collect();


                            $firstService =
                                $selectedServices->first();


                            $serviceCount =
                                $selectedServices->count();


                            $mobileServiceName =
                                $firstService
                                    ? $firstService->name
                                    : 'Service';


                            if ($serviceCount > 1) {

                                $mobileServiceName .=
                                    ' +' .
                                    ($serviceCount - 1) .
                                    ' more';

                            }


                            $mobileStatusClass = match (
                                $booking->status
                            ) {

                                'pending'
                                    => 'pending',

                                'accepted',
                                'on_the_way',
                                'arrived',
                                'in_progress'
                                    => 'accepted',

                                'completed'
                                    => 'completed',

                                'cancelled',
                                'timeout'
                                    => 'cancelled',

                                default
                                    => 'pending',

                            };


                            $mobileFilterStatus = match (
                                true
                            ) {

                                $booking->status === 'pending'
                                    => 'pending',

                                in_array(
                                    $booking->status,
                                    [
                                        'accepted',
                                        'on_the_way',
                                        'arrived',
                                        'in_progress',
                                    ],
                                    true
                                )
                                    => 'active',

                                $booking->status === 'completed'
                                    => 'completed',

                                in_array(
                                    $booking->status,
                                    [
                                        'cancelled',
                                        'timeout',
                                    ],
                                    true
                                )
                                    => 'cancelled',

                                default
                                    => 'all',

                            };


                            $mobileStatusLabel = match (
                                $booking->status
                            ) {

                                'pending'
                                    => 'Pending',

                                'accepted'
                                    => 'Accepted',

                                'on_the_way'
                                    => 'On The Way',

                                'arrived'
                                    => 'Arrived',

                                'in_progress'
                                    => 'In Progress',

                                'completed'
                                    => 'Completed',

                                'cancelled'
                                    => 'Cancelled',

                                'timeout'
                                    => 'Timeout',

                                default
                                    => ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $booking->status
                                        )
                                    ),

                            };


                            $mobileSearchText = strtolower(
                                'BK-' .
                                str_pad(
                                    $booking->id,
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                ) .
                                ' ' .
                                $mobileServiceName .
                                ' ' .
                                (
                                    $booking
                                        ->provider
                                        ?->user
                                        ?->name
                                        ?? ''
                                ) .
                                ' ' .
                                $mobileStatusLabel
                            );

                        @endphp


                        <!-- =================================================
                             MOBILE CARD
                        ================================================== -->

                       <div
    class="booking-row"
    data-status="{{ $mobileFilterStatus }}"
    data-real-status="{{ $booking->status }}"
    data-search="{{ $mobileSearchText }}"
    data-booking-id="BK-{{
        str_pad(
            $booking->id,
            3,
            '0',
            STR_PAD_LEFT
        )
    }}"
    data-booking-id-number="{{ $booking->id }}"
    data-status-url="{{ route('customer.booking.status', $booking->id) }}"
>


                            <!-- Top -->

                            <div class="mobile-booking-top">

                                <span class="booking-id">

                                    BK-{{
                                        str_pad(
                                            $booking->id,
                                            3,
                                            '0',
                                            STR_PAD_LEFT
                                        )
                                    }}

                                </span>


                                <span
                                    class="booking-status {{ $mobileStatusClass }}"
                                >

                                    <span></span>

                                    {{ $mobileStatusLabel }}

                                </span>

                            </div>


                            <!-- Service -->

                            <div class="mobile-booking-service">

                                <div class="booking-service-icon">

                                    <i class="fa-solid fa-screwdriver-wrench"></i>

                                </div>


                                <div>

                                    <strong>
                                        {{ $mobileServiceName }}
                                    </strong>


                                    <span>

                                        {{
                                            $booking
                                                ->provider
                                                ?->user
                                                ?->name
                                                ?? 'Waiting for provider'
                                        }}

                                    </span>

                                </div>

                            </div>


                            <!-- Details -->

                            <div class="mobile-booking-details">


                                <!-- Date -->

                                <div>

                                    <i class="fa-regular fa-calendar"></i>

                                    <span>

                                        {{
                                            $booking->scheduled_at
                                                ? $booking
                                                    ->scheduled_at
                                                    ->format(
                                                        'M d, Y'
                                                    )
                                                : 'Not scheduled'
                                        }}

                                    </span>

                                </div>


                                <!-- Time -->

                                <div>

                                    <i class="fa-regular fa-clock"></i>

                                    <span>

                                        {{
                                            $booking->scheduled_at
                                                ? $booking
                                                    ->scheduled_at
                                                    ->format(
                                                        'h:i A'
                                                    )
                                                : ''
                                        }}

                                    </span>

                                </div>


                                <!-- Amount -->

                                <div>

                                    <i class="fa-solid fa-money-bill"></i>

                                    <strong>

                                        MMK
                                        {{
                                            number_format(
                                                $booking->total_amount
                                            )
                                        }}

                                    </strong>

                                </div>

                            </div>


                            <!-- View -->

                            <a
                                href="{{ route(
                                    'customer.booking.detail',
                                    $booking->id
                                ) }}"
                                class="booking-view-button mobile-view-button"
                            >

                                <i class="fa-solid fa-eye"></i>

                                View Booking

                            </a>

                        </div>


                    @empty


                        <div class="booking-empty">

                            <div class="booking-empty-icon">

                                <i
                                    class="fa-solid fa-calendar-xmark"
                                ></i>

                            </div>

                            <h4>
                                No bookings yet
                            </h4>

                            <p>
                                You have not created any service bookings yet.
                            </p>

                            <a
                                href="{{
                                    route(
                                        'customer.booking.create'
                                    )
                                }}"
                                class="booking-primary-button"
                            >

                                <i class="fa-solid fa-plus"></i>

                                Book a Service

                            </a>

                        </div>

                    @endforelse


                </div>


                <!-- =================================================
                     JAVASCRIPT EMPTY MESSAGE
                ================================================== -->

                <div
                    class="booking-empty"
                    id="bookingEmpty"
                    style="display: none;"
                >

                    <div class="booking-empty-icon">

                        <i class="fa-solid fa-calendar-xmark"></i>

                    </div>

                    <h4>
                        No bookings found
                    </h4>

                    <p>
                        Try changing your filter or search keyword.
                    </p>

                </div>


            </div>

        </section>

    </main>

</div>


<!-- =====================================================
     BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     QUICKFIX JS
====================================================== -->

<script
    src="{{ asset('js/quickfix.js') }}"
></script>


<!-- =====================================================
     CUSTOMER JS
====================================================== -->

<script
    src="{{ asset('js/customer.js') }}"
></script>


<!-- =====================================================
     CUSTOMER BOOKINGS JS
====================================================== -->

<script
    src="{{ asset('js/customer-bookings.js') }}"
></script>


</body>

</html>