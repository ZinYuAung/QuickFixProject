<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            QuickFix - Booking Details
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


        <!-- =====================================================
         BOOKING DETAIL CSS
    ====================================================== -->

        <link
            rel="stylesheet"
            href="{{ asset('css/customer-booking-detail.css') }}"
        >
        <link
            rel="stylesheet"
            href="{{ asset('css/customer-review.css') }}"
        >

    </head>


    <body>

        <div
            class="customer-layout"
            id="customerLayout"
        >

            <!-- =====================================================
     CUSTOMER SIDEBAR
====================================================== -->

            <aside class="customer-sidebar" id="customerSidebar">

                <!-- Sidebar Header -->
                <div class="customer-sidebar-header">

                    <a href="{{ url('/') }}" class="customer-logo">
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
                        <h4>{{ auth()->user()->name ?? 'Customer' }}</h4>
                        <span>Customer</span>
                    </div>

                </div>

                <!-- Sidebar Menu -->
                <nav class="customer-sidebar-menu">

                    <!-- Dashboard -->
                    <a
                        href="{{ url('/customer/dashboard') }}"
                        class="customer-menu-link"
                    >
                        <i class="fa-solid fa-house"></i>
                        <span class="customer-menu-text">Dashboard</span>
                    </a>

                    <!-- My Services -->
                    <a
                        href="{{ route('customer.services') }}"
                        class="customer-menu-link"
                    >
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="customer-menu-text">My Services</span>
                    </a>

                    <!-- My Bookings -->
                    <a
                        href="{{ route('customer.bookings') }}"
                        class="customer-menu-link active"
                    >
                        <i class="fa-solid fa-calendar-check"></i>
                        <span class="customer-menu-text">My Bookings</span>
                    </a>

                    <!-- Payment -->
                    <a
                        href="{{ route('customer.payments') }}"
                        class="customer-menu-link"
                    >
                        <i class="fa-solid fa-credit-card"></i>
                        <span class="customer-menu-text">Payment</span>
                    </a>

                    <!-- Chat -->
                    <a
                        href="{{ route('customer.chats') }}"
                        class="customer-menu-link"
                    >
                        <i class="fa-solid fa-comments"></i>
                        <span class="customer-menu-text">Chat</span>

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
                        <span class="customer-menu-text">My Profile</span>
                    </a>

                </nav>

                <!-- Logout -->
                <div class="customer-sidebar-bottom">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="customer-menu-link customer-logout"
                        >
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="customer-menu-text">Logout</span>
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


                        <!-- Mobile Menu -->

                        <button
                            type="button"
                            class="customer-mobile-menu"
                            id="customerMobileMenu"
                            aria-label="Open menu"
                        >

                            <i class="fa-solid fa-bars"></i>

                        </button>


                        <!-- Page Title -->

                        <div class="customer-header-title">

                            <h1>
                                Booking Details
                            </h1>

                            <p>
                                View your booking information and service status.
                            </p>

                        </div>

                    </div>


                    <!-- Header Actions -->

                    <div class="customer-header-actions">

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
                 BOOKING DETAIL CONTENT
            ================================================== -->

                @php
                    $cancellationDeadline = null;

                    if (
                        $booking->booking_type === 'scheduled' &&
                        $booking->scheduled_at
                    ) {
                        $cancellationDeadline = $booking->scheduled_at->copy()->subHour();
                    }

                    $canCustomerCancel =
                        $booking->booking_type === 'scheduled' &&
                        $booking->scheduled_at &&
                        in_array(
                            $booking->status,
                            [
                                'pending',
                                'accepted',
                                'on_the_way',
                                'arrived',
                            ],
                            true
                        ) &&
                        now()->lessThanOrEqualTo($cancellationDeadline);
                @endphp

                <section
                    class="customer-booking-detail-content"
                    data-provider-id="{{ $booking->provider_id }}"
                    data-booking-id="{{ $booking->id }}"
                    data-booking-status="{{ $booking->status }}"
                    data-booking-type="{{ $booking->booking_type }}"
                    data-status-url="{{ route('customer.booking.status', $booking->id) }}"
                    data-cancel-url="{{ route('customer.booking.cancel', $booking->id) }}"
                    data-cancellation-deadline="{{ $cancellationDeadline?->toIso8601String() }}"
                    data-review-store-url="{{ route('customer.review.store', $booking->id) }}"
                >


                    <!-- =================================================
                     SUCCESS MESSAGE
                ================================================== -->

                    @if (session('success'))

                        <div class="alert alert-success mb-4">

                            <i class="fa-solid fa-circle-check me-2"></i>

                            {{ session('success') }}

                        </div>

                    @endif


                    <!-- =================================================
                     ERROR MESSAGE
                ================================================== -->

                    @if (session('error'))

                        <div class="alert alert-danger mb-4">

                            <i class="fa-solid fa-circle-exclamation me-2"></i>

                            {{ session('error') }}

                        </div>

                    @endif


                    <!-- =================================================
                     VALIDATION ERRORS
                ================================================== -->

                    @if ($errors->any())

                        <div class="alert alert-danger mb-4">

                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <!-- =================================================
                     DETAIL TOP BAR
                ================================================== -->

                    <div class="booking-detail-topbar">

                        <a
                            href="{{ route('customer.bookings') }}"
                            class="booking-all-button"
                        >

                            <i class="fa-solid fa-list"></i>

                            <span>
                                View All Bookings
                            </span>

                        </a>

                    </div>


                    <!-- =================================================
                     MAIN DETAIL GRID
                ================================================== -->

                    <div class="booking-detail-grid">


                        <!-- =================================================
                         LEFT COLUMN
                    ================================================== -->

                        <div class="booking-detail-left">


                            <!-- =================================================
                             BOOKING INFORMATION CARD
                        ================================================== -->

                            <div class="booking-detail-card">

                                <div class="booking-detail-card-header">


                                    <div class="booking-detail-heading">

                                        <div class="booking-detail-icon">

                                            <i class="fa-solid fa-calendar-check"></i>

                                        </div>

                                        <div>

                                            <h3>
                                                Booking Information
                                            </h3>

                                            <p>
                                                Details about your service request.
                                            </p>

                                        </div>

                                    </div>


                                    <!-- =================================================
                                     REAL BOOKING STATUS
                                ================================================== -->

                                    @php

                                        $statusClass = match ($booking->status) {

                                            'pending' =>
                                                'pending',

                                            'accepted' =>
                                                'accepted',

                                            'on_the_way' =>
                                                'on-the-way',

                                            'arrived' =>
                                                'arrived',

                                            'in_progress' =>
                                                'in-progress',

                                            'completed' =>
                                                'completed',

                                            'cancelled' =>
                                                'cancelled',

                                            'timeout' =>
                                                'timeout',

                                            default =>
                                                'pending',

                                        };


                                        $statusLabel = match ($booking->status) {

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

                                            default =>
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $booking->status
                                                    )
                                                ),

                                        };

                                    @endphp


                                    <span
                                        id="currentBookingStatus"
                                        class="booking-detail-status {{ $statusClass }}"
                                    >

                                        <span></span>

                                        {{ $statusLabel }}

                                    </span>

                                </div>


                                <!-- =================================================
                                 BOOKING INFORMATION
                            ================================================== -->

                                <div class="booking-info-section">


                                    <!-- Booking ID -->

                                    <div class="booking-info-row">

                                        <span>
                                            Booking ID
                                        </span>

                                        <strong>

                                            BK-{{
    str_pad(
        $booking->id,
        3,
        '0',
        STR_PAD_LEFT
    )
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Services -->

                                    <div class="booking-info-row">

                                        <span>
                                            Services
                                        </span>

                                        <strong>

                                            {{ $services->count() }}

                                            {{
    $services->count() === 1
    ? 'Service'
    : 'Services'
                                        }}

                                        </strong>

                                    </div>


                                    <!-- City -->

                                    <div class="booking-info-row">

                                        <span>
                                            City
                                        </span>

                                        <strong>

                                            {{
    $booking->area?->city?->name
    ?? 'N/A'
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Service Area -->

                                    <div class="booking-info-row">

                                        <span>
                                            Service Area
                                        </span>

                                        <strong>

                                            {{
    $booking->area?->name
    ?? 'N/A'
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Booking Type -->

                                    <div class="booking-info-row">

                                        <span>
                                            Booking Type
                                        </span>

                                        <strong>

                                            {{
    $booking->booking_type === 'asap'
    ? 'ASAP'
    : 'Scheduled'
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Date & Time -->

                                    <div class="booking-info-row">

                                        <span>
                                            Date & Time
                                        </span>

                                        <strong>

                                            @if ($booking->scheduled_at)

                                                                                    {{
                                                $booking->scheduled_at->format(
                                                    'M d, Y - h:i A'
                                                )
                                                                                                                                                                                                                                                                                                                                                                            }}

                                            @else

                                                Not scheduled

                                            @endif

                                        </strong>

                                    </div>


                                    <!-- Address -->

                                    <div class="booking-info-row">

                                        <span>
                                            Address
                                        </span>

                                        <strong>

                                            {{ $booking->address }}

                                        </strong>

                                    </div>


                                    <!-- Provider -->

                                    <div class="booking-info-row">

                                        <span>
                                            Provider
                                        </span>

                                        <strong class="booking-provider-name">

                                            <span class="provider-small-avatar">

                                                <i class="fa-solid fa-user"></i>

                                            </span>

                                            {{
    $booking->provider?->user?->name
    ?? 'Waiting for provider'
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Total -->

                                    <div class="booking-info-row">

                                        <span>
                                            Total Amount
                                        </span>

                                        <strong class="booking-total-amount">

                                            MMK
                                            {{
    number_format(
        $booking->total_amount
    )
                                        }}

                                        </strong>

                                    </div>


                                    <!-- Customer Note -->

                                    @if ($booking->customer_note)

                                        <div class="booking-info-row">

                                            <span>
                                                Customer Note
                                            </span>

                                            <strong>
                                                {{ $booking->customer_note }}
                                            </strong>

                                        </div>

                                    @endif


                                </div>

                            </div>
                            <!-- =================================================
                             CANCELLATION CARD
                        ================================================== -->

                            @if (
                                    $booking->status !== 'completed' &&
                                    $booking->status !== 'cancelled' &&
                                    $booking->status !== 'timeout'
                                )

                                <div
                                    class="booking-detail-card cancellation-detail-card"
                                    id="customerCancellationCard"
                                >

                                    <div class="booking-detail-card-header">

                                        <div class="booking-detail-heading">

                                            <div class="booking-detail-icon">

                                                <i class="fa-solid fa-calendar-xmark"></i>

                                            </div>

                                            <div>

                                                <h3>
                                                    Booking Cancellation
                                                </h3>

                                                <p>
                                                    Manage cancellation for this booking.
                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="cancellation-detail-body">

                                        @if ($booking->booking_type === 'asap')

                                            <!-- ASAP CANNOT BE CANCELLED -->

                                            <div class="booking-provider-message">

                                                <i class="fa-solid fa-circle-info"></i>

                                                <span>

                                                    ASAP bookings cannot be cancelled
                                                    by the customer.

                                                </span>

                                            </div>


                                        @elseif ($cancellationDeadline)

                                            @if ($canCustomerCancel)

                                                <!-- CANCELLATION DEADLINE -->

                                                <div class="payment-detail-row">

                                                    <span>
                                                        Cancellation Deadline
                                                    </span>

                                                    <strong>

                                                        {{ $cancellationDeadline->format('M d, Y - h:i A') }}

                                                    </strong>

                                                </div>


                                                <div class="booking-provider-message">

                                                    <i class="fa-solid fa-clock"></i>

                                                    <span>

                                                        You can cancel this scheduled
                                                        booking before the cancellation
                                                        deadline.

                                                    </span>

                                                </div>


                                                <!-- CANCEL BUTTON -->

                                                <button
                                                    type="button"
                                                    class="btn btn-danger mt-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#cancelBookingModal"
                                                    id="cancelBookingButton"
                                                >

                                                    <i class="fa-solid fa-ban me-2"></i>

                                                    Cancel Booking

                                                </button>


                                            @else

                                                <!-- DEADLINE PASSED -->

                                                <div class="booking-provider-message">

                                                    <i class="fa-solid fa-lock"></i>

                                                    <span>

                                                        The cancellation deadline has
                                                        passed. This booking can no
                                                        longer be cancelled by the
                                                        customer.

                                                    </span>

                                                </div>

                                            @endif

                                        @endif

                                    </div>

                                </div>

                            @endif


                            <!-- =================================================
                             SERVICE ITEMS CARD
                        ================================================== -->

                            <div class="booking-detail-card service-items-card">


                                <div class="booking-detail-card-header">

                                    <div class="booking-detail-heading">

                                        <div class="booking-detail-icon">

                                            <i class="fa-solid fa-list-check"></i>

                                        </div>

                                        <div>

                                            <h3>
                                                Service Items
                                            </h3>

                                            <p>
                                                Services included in this booking.
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <!-- =================================================
                                 DESKTOP TABLE
                            ================================================== -->

                                <div class="service-items-table-wrapper">

                                    <table class="service-items-table">

                                        <thead>

                                            <tr>

                                                <th>
                                                    #
                                                </th>

                                                <th>
                                                    Service
                                                </th>


                                                <th>
                                                    Price
                                                </th>

                                                <th>
                                                    Subtotal
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @forelse (
                                                                                        $services as $index => $service
                                                                                    )

                                                                                    <tr>

                                                                                        <td>
                                                                                            {{ $index + 1 }}
                                                                                        </td>


                                                                                        <td>

                                                                                            <div class="detail-service-name">

                                                                                                <div class="detail-service-icon">

                                                                                                    <i class="fa-solid fa-wrench"></i>

                                                                                                </div>

                                                                                                <div>

                                                                                                    <strong>
                                                                                                        {{ $service->name }}
                                                                                                    </strong>

                                                                                                    <small>

                                                                                                        {{
                                                $service->category?->name
                                                ?? 'Home Service'
                                                                                                                                                                                                                                                                                                                                                                                                }}

                                                                                                    </small>

                                                                                                </div>

                                                                                            </div>

                                                                                        </td>


                                                                                        


                                                                                        <td>

                                                                                            MMK
                                                                                            {{
                                                number_format(
                                                    $service->base_price
                                                )
                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                                                        </td>


                                                                                        <td>

                                                                                            <strong>

                                                                                                MMK
                                                                                                {{
                                                number_format(
                                                    $service->base_price
                                                )
                                                                                                                                                                                                                                                                                                                                                                                        }}

                                                                                            </strong>

                                                                                        </td>

                                                                                    </tr>

                                            @empty

                                                <tr>

                                                    <td
                                                        colspan="5"
                                                        class="text-center"
                                                    >

                                                        No services found for this
                                                        booking.

                                                    </td>

                                                </tr>

                                            @endforelse

                                        </tbody>

                                    </table>

                                </div>


                                <!-- =================================================
                                 MOBILE SERVICE ITEMS
                            ================================================== -->

                                @forelse (
                                                                $services as $service
                                                            )

                                                            <div class="mobile-service-item">


                                                                <div class="mobile-service-item-top">

                                                                    <div class="detail-service-name">

                                                                        <div class="detail-service-icon">

                                                                            <i class="fa-solid fa-wrench"></i>

                                                                        </div>

                                                                        <div>

                                                                            <strong>
                                                                                {{ $service->name }}
                                                                            </strong>

                                                                            <span>

                                                                                {{
                                    $service->category?->name
                                    ?? 'Home Service'
                                                                                                                                                                                                                                                                                    }}

                                                                            </span>

                                                                        </div>

                                                                    </div>

                                                                </div>


                                                                <div class="mobile-service-item-info">


                                                                    

                                                                    <div>

                                                                        <span>
                                                                            Price
                                                                        </span>

                                                                        <strong>

                                                                            MMK
                                                                            {{
                                    number_format(
                                        $service->base_price
                                    )
                                                                                                                                                                                                                                                                                }}

                                                                        </strong>

                                                                    </div>


                                                                    <div>

                                                                        <span>
                                                                            Subtotal
                                                                        </span>

                                                                        <strong>

                                                                            MMK
                                                                            {{
                                    number_format(
                                        $service->base_price
                                    )
                                                                                                                                                                                                                                                                                }}

                                                                        </strong>

                                                                    </div>

                                                                </div>

                                                            </div>

                                @empty

                                    <div class="p-3 text-center">

                                        No services found for this booking.

                                    </div>

                                @endforelse


                                <!-- =================================================
                                 TOTAL
                            ================================================== -->

                                <div class="service-total-row">

                                    <span>
                                        Total Amount
                                    </span>

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


                        </div>


                        <!-- =================================================
                         RIGHT COLUMN
                    ================================================== -->

                        <div class="booking-detail-right">


                            <!-- =================================================
                             BOOKING STATUS CARD
                        ================================================== -->

                            <div class="booking-detail-card booking-status-card">


                                <div class="booking-detail-card-header">

                                    <div class="booking-detail-heading">

                                        <div class="booking-detail-icon">

                                            <i class="fa-solid fa-route"></i>

                                        </div>

                                        <div>

                                            <h3>
                                                Booking Status
                                            </h3>

                                            <p>
                                                Track your service request.
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <!-- =================================================
                                 STATUS HISTORY DATA
                            ================================================== -->

                                @php

                                    $statusHistory = $booking->statusHistory
                                        ->sortBy('created_at')
                                        ->values();


                                    $history = $statusHistory
                                        ->keyBy('status');


                                    $normalStatuses = [

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

                                    ];


                                    $normalStatusKeys =
                                        array_keys(
                                            $normalStatuses
                                        );


                                    $currentStatus =
                                        $booking->status;


                                    $currentNormalIndex =
                                        array_search(
                                            $currentStatus,
                                            $normalStatusKeys,
                                            true
                                        );


                                    $isAlternativeEnd =
                                        in_array(
                                            $currentStatus,
                                            [
                                                'cancelled',
                                                'timeout',
                                            ],
                                            true
                                        );

                                @endphp


                                <!-- =================================================
                                 AJAX STATUS TIMELINE
                            ================================================== -->

                                <div id="bookingStatusTimeline">


                                    <!-- =================================================
                                     ALTERNATIVE ENDING
                                     CANCELLED / TIMEOUT
                                ================================================== -->

                                    @if ($isAlternativeEnd)

                                        @php

                                            $pendingHistory =
                                                $history->get(
                                                    'pending'
                                                );


                                            $alternativeLabel =
                                                $currentStatus === 'cancelled'
                                                ? 'Cancelled'
                                                : 'Timeout';


                                            $alternativeHistory =
                                                $history->get(
                                                    $currentStatus
                                                );

                                        @endphp


                                        <!-- PENDING -->

                                        <div class="timeline-item completed">

                                            <div class="timeline-marker">

                                                <i class="fa-solid fa-check"></i>

                                            </div>


                                            <div class="timeline-content">

                                                <strong>
                                                    Pending
                                                </strong>

                                                <span>

                                                    @if ($pendingHistory)

                                                                                            {{
                                                        $pendingHistory
                                                            ->created_at
                                                            ->format(
                                                                'M d, Y - h:i A'
                                                            )
                                                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                    @else

                                                        Booking created

                                                    @endif

                                                </span>


                                                @if ($pendingHistory?->updater)

                                                                                    <small class="d-block mt-1 text-muted">

                                                                                        <i
                                                                                            class="fa-solid fa-user-pen me-1"
                                                                                        ></i>

                                                                                        Updated by

                                                                                        {{
                                                    $pendingHistory
                                                        ->updater
                                                        ->name
                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                                                    </small>

                                                @endif


                                                @if ($pendingHistory?->note)

                                                                                    <small class="d-block mt-1 text-muted">

                                                                                        <i
                                                                                            class="fa-solid fa-note-sticky me-1"
                                                                                        ></i>

                                                                                        {{
                                                    $pendingHistory->note
                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                                                    </small>

                                                @endif

                                            </div>

                                        </div>


                                        <!-- CANCELLED / TIMEOUT -->

                                        <div class="timeline-item current completed">

                                            <div class="timeline-marker">

                                                <i class="fa-solid fa-check"></i>

                                            </div>


                                            <div class="timeline-content">

                                                <strong>
                                                    {{ $alternativeLabel }}
                                                </strong>

                                                <span>

                                                    @if ($alternativeHistory)

                                                                                            {{
                                                        $alternativeHistory
                                                            ->created_at
                                                            ->format(
                                                                'M d, Y - h:i A'
                                                            )
                                                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                    @else

                                                        Current status

                                                    @endif

                                                </span>


                                                @if ($alternativeHistory?->updater)

                                                                                    <small class="d-block mt-1 text-muted">

                                                                                        <i
                                                                                            class="fa-solid fa-user-pen me-1"
                                                                                        ></i>

                                                                                        Updated by

                                                                                        {{
                                                    $alternativeHistory
                                                        ->updater
                                                        ->name
                                                                                                                                                                                                                                                                                                                                                                                    }}

                                                                                    </small>

                                                @endif


                                                @if ($alternativeHistory?->note)

                                                                                    <div class="mt-2">

                                                                                        <strong>
                                                                                            Reason:
                                                                                        </strong>

                                                                                        <span>
                                                                                            {{
                                                    $alternativeHistory
                                                        ->note
                                                                                                                                                                                                                                                                                                                                                                                        }}
                                                                                        </span>

                                                                                    </div>

                                                @endif

                                            </div>

                                        </div>


                                    @else


                                        <!-- =================================================
                                                                         NORMAL BOOKING FLOW
                                                                    ================================================== -->

                                        @foreach (
                                                $normalStatuses
                                                as $statusKey => $statusLabel
                                            )

                                            @php

                                                $statusIndex =
                                                    array_search(
                                                        $statusKey,
                                                        $normalStatusKeys,
                                                        true
                                                    );


                                                $historyItem =
                                                    $history->get(
                                                        $statusKey
                                                    );


                                                $isCompleted =
                                                    $currentNormalIndex !== false &&
                                                    $statusIndex <
                                                    $currentNormalIndex;


                                                $isCurrent =
                                                    $currentStatus ===
                                                    $statusKey;

                                            @endphp


                                            <div
                                                class="
                                                                                                                timeline-item
                                                                                                                {{ $isCompleted ? 'completed' : '' }}
                                                                                                                {{ $isCurrent ? 'current completed' : '' }}
                                                                                                            "
                                            >

                                                <div class="timeline-marker">

                                                    @if (
                                                            $isCompleted ||
                                                            $isCurrent
                                                        )

                                                        <i class="fa-solid fa-check"></i>

                                                    @endif

                                                </div>


                                                <div class="timeline-content">

                                                    <strong>
                                                        {{ $statusLabel }}
                                                    </strong>


                                                    <span>

                                                        @if ($historyItem)

                                                                                            {{
                                                            $historyItem
                                                                ->created_at
                                                                ->format(
                                                                    'M d, Y - h:i A'
                                                                )
                                                                                                                                                                                                                                                                                                                                                                                                                        }}

                                                        @elseif ($isCurrent)

                                                            Current status

                                                        @else

                                                            Waiting

                                                        @endif

                                                    </span>


                                                    @if ($historyItem?->updater)

                                                                                    <small class="d-block mt-1 text-muted">

                                                                                        <i
                                                                                            class="fa-solid fa-user-pen me-1"
                                                                                        ></i>

                                                                                        Updated by

                                                                                        {{
                                                        $historyItem
                                                            ->updater
                                                            ->name
                                                                                                                                                                                                                                                                                                                                                                                        }}

                                                                                    </small>

                                                    @endif


                                                    @if ($historyItem?->note)

                                                                                    <small class="d-block mt-1 text-muted">

                                                                                        <i
                                                                                            class="fa-solid fa-note-sticky me-1"
                                                                                        ></i>

                                                                                        {{
                                                        $historyItem->note
                                                                                                                                                                                                                                                                                                                                                                                        }}

                                                                                    </small>

                                                    @endif

                                                </div>

                                            </div>

                                        @endforeach

                                    @endif

                                </div>


                            </div>


                            <!-- =================================================
                             PROVIDER CARD
                        ================================================== -->

                            <div class="booking-detail-card provider-detail-card">


                                <div class="booking-detail-card-header">

                                    <div class="booking-detail-heading">

                                        <div class="booking-detail-icon">

                                            <i class="fa-solid fa-user"></i>

                                        </div>

                                        <div>

                                            <h3>
                                                Service Provider
                                            </h3>

                                            <p>
                                                Your assigned professional.
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <!-- =================================================
                                 AJAX PROVIDER STATUS SECTION
                                 IMPORTANT:
                                 This wrapper contains ALL provider status
                                 content so JavaScript can replace it later.
                            ================================================== -->

                                <div id="providerStatusSection">


                                    <!-- =================================================
                                     PENDING
                                ================================================== -->

                                    @if ($booking->status === 'pending')


                                                                    <div class="provider-detail-body">


                                                                        <div class="provider-large-avatar">

                                                                            <i class="fa-solid fa-user-clock"></i>

                                                                        </div>


                                                                        <div class="provider-detail-info">

                                                                            <h4>

                                                                                {{
                                        $booking
                                            ->provider
                                            ?->user
                                                ?->name
                                        ?? 'Waiting for Provider'
                                                                                                                                                                                                                                                                                                                }}

                                                                            </h4>


                                                                            <span>
                                                                                Waiting for provider response
                                                                            </span>


                                                                            <div class="provider-rating">

                                                                                <i class="fa-solid fa-clock"></i>

                                                                                <strong>
                                                                                    Pending
                                                                                </strong>

                                                                                <span>
                                                                                    Awaiting Acceptance
                                                                                </span>

                                                                            </div>

                                                                        </div>

                                                                    </div>


                                                                    <!-- Pending Message -->

                                                                    <div class="booking-provider-message">

                                                                        <i class="fa-solid fa-hourglass-half"></i>

                                                                        <span>

                                                                            Your booking request has been sent
                                                                            to the selected provider. Please wait
                                                                            for the provider to accept your request.

                                                                        </span>

                                                                    </div>


                                                                    <!-- =================================================
                                                                                                                                                                                                                                                                                                     ACCEPTED / ACTIVE / COMPLETED
                                                                                                                                                                                                                                                                                                ================================================== -->

                                    @elseif (
                                            $booking->status === 'accepted' ||
                                            $booking->status === 'on_the_way' ||
                                            $booking->status === 'arrived' ||
                                            $booking->status === 'in_progress' ||
                                            $booking->status === 'completed'
                                        )


                                        @if ($booking->provider)


                                                                    <div class="provider-detail-body">


                                                                        <!-- Provider Avatar -->

                                                                        <div class="provider-large-avatar">

                                                                            @if (
                                                                                                                    $booking
                                                                                                                        ->provider
                                                                                                                        ->user
                                                                                                                            ?->profile_image
                                                                                                                )

                                                                                                                <img
                                                                                                                    src="{{ asset(
                                                                                    'storage/' .
                                                                                    $booking
                                                                                        ->provider
                                                                                        ->user
                                                                                        ->profile_image
                                                                                ) }}"
                                                                                                                    alt="{{
                                                                                    $booking
                                                                                        ->provider
                                                                                        ->user
                                                                                        ->name
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            }}"
                                                                                                                >

                                                                            @else

                                                                                <i class="fa-solid fa-user"></i>

                                                                            @endif

                                                                        </div>


                                                                        <!-- Provider Information -->

                                                                        <div class="provider-detail-info">

                                                                            <h4>

                                                                                {{
                                            $booking
                                                ->provider
                                                ->user
                                                    ?->name
                                            ?? 'Provider'
                                                                                                                                                                                                                                                                                                                        }}

                                                                            </h4>


                                                                            <span>
                                                                                Home Service Provider
                                                                            </span>


                                                                            <!-- Rating -->

                                                                            <div class="provider-rating">

                                                                                <i class="fa-solid fa-star"></i>

                                                                                <strong>

                                                                                    {{
                                            $booking
                                                ->provider
                                                ->rating
                                            ?? 'N/A'
                                                                                                                                                                                                                                                                                                                            }}

                                                                                </strong>

                                                                                <span>
                                                                                    Professional
                                                                                </span>

                                                                            </div>


                                                                            <!-- Experience -->

                                                                            @if (
                                                                                                                $booking
                                                                                                                    ->provider
                                                                                                                    ->experience_years
                                                                                                                !== null
                                                                                                            )

                                                                                                            <div class="mt-2">

                                                                                                                <small>

                                                                                                                    <i
                                                                                                                        class="fa-solid fa-briefcase me-1"
                                                                                                                    ></i>

                                                                                                                    {{
                                                                                $booking
                                                                                    ->provider
                                                                                    ->experience_years
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}

                                                                                                                    {{
                                                                                $booking
                                                                                    ->provider
                                                                                    ->experience_years == 1
                                                                                ? 'year'
                                                                                : 'years'
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }}

                                                                                                                    experience

                                                                                                                </small>

                                                                                                            </div>

                                                                            @endif

                                                                        </div>

                                                                    </div>


                                                                    <!-- =================================================
                                                                                                                                                                                                                                                                                                                 PROVIDER STATUS MESSAGE
                                                                                                                                                                                                                                                                                                            ================================================== -->

                                                                    <div class="booking-provider-message">

                                                                        @if ($booking->status === 'accepted')

                                                                            <i class="fa-solid fa-circle-check"></i>

                                                                            <span>

                                                                                Your booking has been accepted
                                                                                by the provider.

                                                                            </span>


                                                                        @elseif ($booking->status === 'on_the_way')

                                                                            <i class="fa-solid fa-car-side"></i>

                                                                            <span>

                                                                                Your provider is on the way
                                                                                to your location.

                                                                            </span>


                                                                        @elseif ($booking->status === 'arrived')

                                                                            <i class="fa-solid fa-location-dot"></i>

                                                                            <span>

                                                                                Your provider has arrived
                                                                                at your location.

                                                                            </span>


                                                                        @elseif ($booking->status === 'in_progress')

                                                                            <i class="fa-solid fa-screwdriver-wrench"></i>

                                                                            <span>

                                                                                Your service is currently
                                                                                in progress.

                                                                            </span>


                                                                        @elseif ($booking->status === 'completed')

                                                                            <i class="fa-solid fa-circle-check"></i>

                                                                            <span>

                                                                                Your service has been
                                                                                completed successfully.

                                                                            </span>

                                                                        @endif

                                                                    </div>


                                                                    <!-- =================================================
                                                                                                                                                                                                                                                                                                                 CHAT
                                                                                                                                                                                                                                                                                                            ================================================== -->

                                                                    <a
                                                                        href="{{ route('customer.chats', ['provider_id' => $booking->provider_id]) }}"
                                                                        class="booking-chat-button"
                                                                    >

                                                                        <i class="fa-solid fa-comments"></i>

                                                                        Chat with Provider

                                                                    </a>


                                        @else


                                            <!-- Provider missing -->

                                            <div class="provider-detail-body">

                                                <div class="provider-large-avatar">

                                                    <i class="fa-solid fa-user-clock"></i>

                                                </div>


                                                <div class="provider-detail-info">

                                                    <h4>
                                                        Provider Not Available
                                                    </h4>

                                                    <span>

                                                        No provider is currently
                                                        assigned to this booking.

                                                    </span>

                                                </div>

                                            </div>


                                        @endif


                                        <!-- =================================================
                                                                         CANCELLED
                                                                    ================================================== -->

                                    @elseif ($booking->status === 'cancelled')


                                        @php

                                            $cancelledHistory =
                                                $booking
                                                    ->statusHistory
                                                    ->where(
                                                        'status',
                                                        'cancelled'
                                                    )
                                                    ->sortByDesc(
                                                        'created_at'
                                                    )
                                                    ->first();

                                        @endphp


                                        <div class="provider-detail-body">


                                            <div class="provider-large-avatar">

                                                <i class="fa-solid fa-ban"></i>

                                            </div>


                                            <div class="provider-detail-info">

                                                <h4>
                                                    Booking Cancelled
                                                </h4>

                                                @if ($booking->cancelled_by === 'customer')

                                                    <span>
                                                        You cancelled this booking.
                                                    </span>

                                                @elseif ($booking->cancelled_by === 'provider')

                                                    <span>
                                                        The provider cancelled this booking.
                                                    </span>

                                                @else

                                                    <span>
                                                        This booking was cancelled.
                                                    </span>

                                                @endif


                                                @if ($booking->cancelled_by)

                                                    <small class="d-block mt-2 text-muted">

                                                        <i
                                                            class="fa-solid fa-user-pen me-1"
                                                        ></i>

                                                        Cancelled by

                                                        {{ ucfirst($booking->cancelled_by) }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>


                                        <!-- Provider Rejection Reason -->
                                        @if ($booking->cancellation_reason)

                                            <div class="booking-provider-message">

                                                <i class="fa-solid fa-circle-info"></i>

                                                <span>

                                                    <strong>
                                                        Cancellation Reason:
                                                    </strong>

                                                    {{ $booking->cancellation_reason }}

                                                </span>

                                            </div>

                                        @endif


                                        <!-- =================================================
                                                                         TIMEOUT
                                                                    ================================================== -->

                                    @elseif ($booking->status === 'timeout')


                                                                    @php

                                                                        $timeoutHistory =
                                                                            $booking
                                                                                ->statusHistory
                                                                                ->where(
                                                                                    'status',
                                                                                    'timeout'
                                                                                )
                                                                                ->sortByDesc(
                                                                                    'created_at'
                                                                                )
                                                                                ->first();

                                                                        $isPrepaymentTimeout = str_contains(
                                                                            strtolower($timeoutHistory?->note ?? ''),
                                                                            'prepayment slip'
                                                                        );

                                                                    @endphp


                                                                    <div class="provider-detail-body">


                                                                        <div class="provider-large-avatar">

                                                                            <i class="fa-solid fa-clock"></i>

                                                                        </div>


                                                                        <div class="provider-detail-info">

                                                                            <h4>
                                                                                {{ $isPrepaymentTimeout
                                        ? 'Prepayment Deadline Expired'
                                        : 'Provider Did Not Respond' }}
                                                                            </h4>

                                                                            <span>

                                                                                {{ $isPrepaymentTimeout
                                        ? 'The required prepayment slip was not submitted by the scheduled service time.'
                                        : 'The provider did not respond within the required time.' }}

                                                                            </span>


                                                                            @if ($timeoutHistory?->updater)

                                                                                                                <small class="d-block mt-2 text-muted">

                                                                                                                    <i
                                                                                                                        class="fa-solid fa-user-pen me-1"
                                                                                                                    ></i>

                                                                                                                    Updated by

                                                                                                                    {{
                                                                                $timeoutHistory
                                                                                    ->updater
                                                                                    ->name
                                                                                                                                                                                                                                                                                                                                                                                                                }}

                                                                                                                </small>

                                                                            @endif

                                                                        </div>

                                                                    </div>


                                                                    <div class="booking-provider-message">

                                                                        <i class="fa-solid fa-hourglass-end"></i>

                                                                        <span>
                                                                            {{ $isPrepaymentTimeout
                                        ? 'Your booking timed out because the required prepayment slip was not submitted before the scheduled service time.'
                                        : 'The response deadline has expired and this booking can no longer be accepted.' }}
                                                                        </span>

                                                                    </div>


                                    @endif


                                </div>
                                <!-- END #providerStatusSection -->


                            </div>
                         
                            <!-- =================================================
 PAYMENT CARD
================================================== -->

                            @if ($booking->payment)

                                                            @php
                                                                $payment = $booking->payment;

                                                                $serviceAmount = (float) $payment->service_amount;
                                                                $prepaidAmount = (float) $payment->prepaid_amount;
                                                                $remainingAmount = (float) $payment->remaining_amount;

                                                                $prepaymentRequired = $prepaidAmount > 0;

                                                                $prepaymentVerified =
                                                                    $payment->payment_status === 'verified';

                                                                $prepaymentPending =
                                                                    $payment->payment_status === 'pending';

                                                                $prepaymentRejected =
                                                                    $payment->payment_status === 'rejected';

                                                                $finalPaymentRequired =
                                                                    $remainingAmount > 0;

                                                                $finalPaymentPending =
                                                                    $payment->final_payment_status === 'pending';

                                                                $finalPaymentRejected =
                                                                    $payment->final_payment_status === 'rejected';

                                                                $finalPaymentVerified =
                                                                    $payment->final_payment_status === 'verified';
                                                            @endphp


                                                            <div class="booking-detail-card payment-detail-card">

                                                                <div class="booking-detail-card-header">

                                                                    <div class="booking-detail-heading">

                                                                        <div class="booking-detail-icon">

                                                                            <i class="fa-solid fa-credit-card"></i>

                                                                        </div>

                                                                        <div>

                                                                            <h3>
                                                                                Payment
                                                                            </h3>

                                                                            <p>
                                                                                Payment details for this booking.
                                                                            </p>

                                                                        </div>

                                                                    </div>

                                                                </div>


                                                                <div class="payment-detail-body">


                                                                    <!-- =================================================
                                             SERVICE FEE
                                            ================================================== -->

                                                                    <div class="payment-detail-row">

                                                                        <span>
                                                                            Service Fee
                                                                        </span>

                                                                        <strong>
                                                                            MMK {{ number_format($serviceAmount) }}
                                                                        </strong>

                                                                    </div>


                                                                    <!-- =================================================
                                             PREPAID AMOUNT
                                            ================================================== -->

                                                                    @if ($prepaymentRequired)

                                                                        <div class="payment-detail-row">

                                                                            <span>
                                                                                Prepaid Amount
                                                                            </span>

                                                                            <strong>
                                                                                MMK {{ number_format($prepaidAmount) }}
                                                                            </strong>

                                                                        </div>

                                                                    @endif


                                                                    <!-- =================================================
                                             REMAINING AMOUNT
                                            ================================================== -->

                                                                    <div class="payment-detail-row">

                                                                        <span>
                                                                            Remaining Amount
                                                                        </span>

                                                                        <strong>
                                                                            MMK {{ number_format($remainingAmount) }}
                                                                        </strong>

                                                                    </div>


                                                                    <!-- =================================================
                                             TOTAL
                                            ================================================== -->

                                                                    <div class="payment-detail-row">

                                                                        <span>
                                                                            Total Amount
                                                                        </span>

                                                                        <strong>
                                                                            MMK {{ number_format($serviceAmount) }}
                                                                        </strong>

                                                                    </div>


                                                                    <!-- =================================================
                                             PREPAYMENT PENDING
                                            ================================================== -->

                                                                    @if (
                                                                                                    $prepaymentRequired &&
                                                                                                    $prepaymentPending
                                                                                                )

                                                                                                <div class="booking-provider-message">

                                                                                                    <i class="fa-solid fa-circle-exclamation"></i>

                                                                                                    <span>

                                                                                                        <strong>
                                                                                                            Prepayment Required
                                                                                                        </strong>

                                                                                                        <br>

                                                                                                        Please pay the required prepayment
                                                                                                        before the provider can start the service.

                                                                                                    </span>

                                                                                                </div>


                                                                                                <div class="payment-detail-row">

                                                                                                    <span>
                                                                                                        Payment Method
                                                                                                    </span>

                                                                                                    <strong>

                                                                                                        <i class="fa-solid fa-mobile-screen-button me-1"></i>

                                                                                                        KBZPay

                                                                                                    </strong>

                                                                                                </div>


                                                                                                <a
                                                                                                    href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                    class="payment-now-button"
                                                                                                >

                                                                                                    <i class="fa-solid fa-credit-card"></i>

                                                                                                    Update Payment

                                                                                                </a>


                                                                                                <!-- =================================================
                                                                         PREPAYMENT REJECTED
                                                                        ================================================== -->

                                                                    @elseif (
                                                                                                    $prepaymentRequired &&
                                                                                                    $prepaymentRejected
                                                                                                )

                                                                                                <div class="booking-provider-message text-danger">

                                                                                                    <i class="fa-solid fa-circle-xmark"></i>

                                                                                                    <span>

                                                                                                        <strong>
                                                                                                            Payment Rejected
                                                                                                        </strong>

                                                                                                        @if ($payment->rejection_reason)

                                                                                                            <br>

                                                                                                            {{ $payment->rejection_reason }}

                                                                                                        @endif

                                                                                                    </span>

                                                                                                </div>


                                                                                                <a
                                                                                                    href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                    class="payment-now-button"
                                                                                                >

                                                                                                    <i class="fa-solid fa-rotate"></i>

                                                                                                    Update Payment

                                                                                                </a>


                                                                                                <!-- =================================================
                                                                         PREPAYMENT VERIFIED
                                                                        ================================================== -->

                                                                    @elseif (
                                                                                                    $prepaymentRequired &&
                                                                                                    $prepaymentVerified
                                                                                                )

                                                                                                <div class="payment-success-message">

                                                                                                    <i class="fa-solid fa-circle-check"></i>

                                                                                                    <span>

                                                                                                        Your prepayment has been verified
                                                                                                        successfully.

                                                                                                    </span>

                                                                                                </div>


                                                                                                @if ($payment->verified_at)

                                                                                                    <small class="d-block mt-2 text-muted">

                                                                                                        <i class="fa-solid fa-clock me-1"></i>

                                                                                                        Verified on

                                                                                                        {{ $payment->verified_at->format('M d, Y - h:i A') }}

                                                                                                    </small>

                                                                                                @endif


                                                                                                <!-- =================================================
                                                                             FINAL PAYMENT SECTION
                                                                            ================================================== -->

                                                                                                @if (
                                                                                                        $booking->status === 'completed' &&
                                                                                                        $finalPaymentRequired
                                                                                                    )

                                                                                                    <hr class="my-4">


                                                                                                    <div class="payment-detail-row">

                                                                                                        <span>
                                                                                                            Final Payment
                                                                                                        </span>

                                                                                                        <strong>
                                                                                                            MMK {{ number_format($remainingAmount) }}
                                                                                                        </strong>

                                                                                                    </div>


                                                                                                    <!-- FINAL PAYMENT PENDING -->

                                                                                                    @if ($finalPaymentPending)

                                                                                                        <div class="booking-provider-message">

                                                                                                            <i class="fa-solid fa-circle-exclamation"></i>

                                                                                                            <span>

                                                                                                                <strong>
                                                                                                                    Final Payment Required
                                                                                                                </strong>

                                                                                                                <br>

                                                                                                                Your service is completed.
                                                                                                                Please submit the remaining payment.

                                                                                                            </span>

                                                                                                        </div>


                                                                                                        <a
                                                                                                            href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                            class="payment-now-button"
                                                                                                        >

                                                                                                            <i class="fa-solid fa-money-bill-wave"></i>

                                                                                                            Pay Remaining Amount

                                                                                                        </a>


                                                                                                        <!-- FINAL PAYMENT REJECTED -->

                                                                                                    @elseif ($finalPaymentRejected)

                                                                                                        <div class="booking-provider-message text-danger">

                                                                                                            <i class="fa-solid fa-circle-xmark"></i>

                                                                                                            <span>

                                                                                                                <strong>
                                                                                                                    Final Payment Rejected
                                                                                                                </strong>

                                                                                                                @if ($payment->final_rejection_reason)

                                                                                                                    <br>

                                                                                                                    {{ $payment->final_rejection_reason }}

                                                                                                                @endif

                                                                                                            </span>

                                                                                                        </div>


                                                                                                        <a
                                                                                                            href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                            class="payment-now-button"
                                                                                                        >

                                                                                                            <i class="fa-solid fa-rotate"></i>

                                                                                                            Update Final Payment

                                                                                                        </a>


                                                                                                        <!-- FINAL PAYMENT VERIFIED -->

                                                                                                    @elseif ($finalPaymentVerified)

                                                                                                        <div class="payment-success-message">

                                                                                                            <i class="fa-solid fa-circle-check"></i>

                                                                                                            <span>

                                                                                                                Final payment has been verified
                                                                                                                successfully.

                                                                                                            </span>

                                                                                                        </div>

                                                                                                    @endif

                                                                                                @endif


                                                                                                <!-- =================================================
                                                                         NO PREPAYMENT
                                                                        ================================================== -->

                                                                    @else

                                                                        @if (
                                                                                                $booking->status === 'completed' &&
                                                                                                $finalPaymentRequired
                                                                                            )

                                                                                            <!-- =================================================
                                                                             FINAL PAYMENT FOR NO-PREPAYMENT BOOKING
                                                                            ================================================== -->

                                                                                            <hr class="my-4">


                                                                                            <div class="payment-detail-row">

                                                                                                <span>
                                                                                                    Final Payment
                                                                                                </span>

                                                                                                <strong>
                                                                                                    MMK {{ number_format($remainingAmount) }}
                                                                                                </strong>

                                                                                            </div>


                                                                                            @if ($finalPaymentPending)

                                                                                                <div class="booking-provider-message">

                                                                                                    <i class="fa-solid fa-circle-exclamation"></i>

                                                                                                    <span>

                                                                                                        <strong>
                                                                                                            Final Payment Required
                                                                                                        </strong>

                                                                                                        <br>

                                                                                                        Please submit the remaining payment.

                                                                                                    </span>

                                                                                                </div>


                                                                                                <a
                                                                                                    href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                    class="payment-now-button"
                                                                                                >

                                                                                                    <i class="fa-solid fa-money-bill-wave"></i>

                                                                                                    Pay Remaining Amount

                                                                                                </a>


                                                                                            @elseif ($finalPaymentRejected)

                                                                                                <div class="booking-provider-message text-danger">

                                                                                                    <i class="fa-solid fa-circle-xmark"></i>

                                                                                                    <span>

                                                                                                        <strong>
                                                                                                            Final Payment Rejected
                                                                                                        </strong>

                                                                                                        @if ($payment->final_rejection_reason)

                                                                                                            <br>

                                                                                                            {{ $payment->final_rejection_reason }}

                                                                                                        @endif

                                                                                                    </span>

                                                                                                </div>


                                                                                                <a
                                                                                                    href="{{ route('customer.payment.show', $booking->id) }}"
                                                                                                    class="payment-now-button"
                                                                                                >

                                                                                                    <i class="fa-solid fa-rotate"></i>

                                                                                                    Update Final Payment

                                                                                                </a>


                                                                                            @elseif ($finalPaymentVerified)

                                                                                                <div class="payment-success-message">

                                                                                                    <i class="fa-solid fa-circle-check"></i>

                                                                                                    <span>

                                                                                                        Final payment has been verified
                                                                                                        successfully.

                                                                                                    </span>

                                                                                                </div>

                                                                                            @endif


                                                                        @else

                                                                            <div class="payment-success-message">

                                                                                <i class="fa-solid fa-circle-check"></i>

                                                                                <span>

                                                                                    No prepayment is required for this booking.

                                                                                </span>

                                                                            </div>

                                                                        @endif

                                                                    @endif


                                                                    <!-- =================================================
                                     REFUND INFORMATION
                                ================================================== -->


                                                                    @if(
                                                                                                        $payment &&
                                                                                                        $booking->status === 'cancelled' &&
                                                                                                        $booking->cancelled_by === 'provider' &&
                                                                                                        $payment->payment_status === 'verified' &&
                                                                                                        (float) $payment->prepaid_amount > 0 &&
                                                                                                        $payment->refund_status === 'pending'
                                                                                                    )

                                                                                                    <hr class="my-4">


                                                                                                    <div class="refund-detail-section">


                                                                                                        <!-- =================================================
                                                                             HEADER
                                                                        ================================================== -->


                                                                                                        <div class="payment-detail-row">
                                                                                                            <span>
                                                                                                                Refund Amount
                                                                                                            </span>

                                                                                                            <strong>
                                                                                                                MMK {{ number_format(
                                                                            (float) (
                                                                                $payment->refunded_amount > 0
                                                                                ? $payment->refunded_amount
                                                                                : $payment->prepaid_amount
                                                                            )
                                                                        ) }}
                                                                                                            </strong>
                                                                                                        </div>




                                                                                                        @if (!$payment->refund_slip_image || !$payment->refund_submitted_at)

                                                                                                            <div class="booking-provider-message mt-3">
                                                                                                                <i class="fa-solid fa-clock"></i>

                                                                                                                <span>
                                                                                                                    <strong>Refund Pending</strong>
                                                                                                                    <br>
                                                                                                                    The provider cancelled this booking.
                                                                                                                    Your provider needs to refund your verified
                                                                                                                    prepayment of
                                                                                                                    <strong>
                                                                                                                        MMK {{ number_format((float) $payment->prepaid_amount) }}
                                                                                                                    </strong>.
                                                                                                                </span>
                                                                                                            </div>

                                                                                                        @elseif ($payment->refund_customer_response === 'not_received')

                                                                                                            <div class="booking-provider-message text-danger mt-3">
                                                                                                                <i class="fa-solid fa-circle-xmark"></i>

                                                                                                                <span>
                                                                                                                    <strong>Refund Not Received</strong>
                                                                                                                    <br>
                                                                                                                    You reported that you did not receive
                                                                                                                    the refund. The provider needs to review
                                                                                                                    the refund and take further action.
                                                                                                                </span>
                                                                                                            </div>

                                                                                                        @else

                                                                                                            <div class="booking-provider-message mt-3">
                                                                                                                <i class="fa-solid fa-clock"></i>

                                                                                                                <span>
                                                                                                                    <strong>Refund Waiting for Your Confirmation</strong>
                                                                                                                    <br>
                                                                                                                    The provider has submitted refund proof.
                                                                                                                    Please confirm whether you received the refund.
                                                                                                                </span>
                                                                                                            </div>

                                                                                                        @endif



                                                                                                        <!-- REFUND SUBMITTED TIME -->

                                                                                                        @if ($payment->refund_submitted_at)

                                                                                                                                        <div class="mt-3">
                                                                                                                                            <small class="text-muted">
                                                                                                                                                <i class="fa-solid fa-clock me-1"></i>

                                                                                                                                                Refund submitted on

                                                                                                                                                {{ $payment->refund_submitted_at->format(
                                                                                                                'M d, Y - h:i A'
                                                                                                            ) }}
                                                                                                                                            </small>
                                                                                                                                        </div>

                                                                                                        @endif

                                                                                                        @if ($payment->refund_slip_image && $payment->refund_submitted_at)

                                                                                                                                            <!-- =================================================
                                                                                                                 REFUND PROOF
                                                                                                            ================================================== -->

                                                                                                                                            <div class="mt-4">

                                                                                                                                                <h6 class="fw-bold mb-3">

                                                                                                                                                    <i class="fa-solid fa-image me-2"></i>

                                                                                                                                                    Refund Proof

                                                                                                                                                </h6>


                                                                                                                                                <div
                                                                                                                                                    class="border rounded p-2 bg-light"
                                                                                                                                                >

                                                                                                                                                    <a
                                                                                                                                                        href="{{ asset(
                                                                                                                    'storage/' .
                                                                                                                    $payment->refund_slip_image
                                                                                                                ) }}"
                                                                                                                                                        target="_blank"
                                                                                                                                                    >

                                                                                                                                                        <img
                                                                                                                                                            src="{{ asset(
                                                                                                                    'storage/' .
                                                                                                                    $payment->refund_slip_image
                                                                                                                ) }}"
                                                                                                                                                            alt="Refund Proof"
                                                                                                                                                            class="img-fluid rounded"
                                                                                                                                                            style="
                                                                                                                                max-height:350px;
                                                                                                                                width:100%;
                                                                                                                                object-fit:contain;
                                                                                                                                cursor:pointer;
                                                                                                                            "
                                                                                                                                                        >

                                                                                                                                                    </a>

                                                                                                                                                </div>


                                                                                                                                                <small class="text-muted d-block mt-2">

                                                                                                                                                    Click the image to view the full refund proof.

                                                                                                                                                </small>

                                                                                                                                            </div>


                                                                                                                                            <!-- =================================================
                                                                                                                 CUSTOMER VERIFICATION BUTTONS
                                                                                                            ================================================== -->

                                                                                                                                            @if(
                                                                                                                                                                            $payment->refund_customer_response !==
                                                                                                                                                                            'not_received'
                                                                                                                                                                        )

                                                                                                                                                                        <div class="mt-4">

                                                                                                                                                                            <div class="row g-2">


                                                                                                                                                                                <!-- RECEIVED -->

                                                                                                                                                                                <div class="col-12 col-md-6">

                                                                                                                                                                                    <form
                                                                                                                                                                                        action="{{ route(
                                                                                                                                                    'customer.booking.refund.verify',
                                                                                                                                                    $booking->id
                                                                                                                                                ) }}"
                                                                                                                                                                                        method="POST"
                                                                                                                                                                                    >

                                                                                                                                                                                        @csrf

                                                                                                                                                                                        <input
                                                                                                                                                                                            type="hidden"
                                                                                                                                                                                            name="refund_response"
                                                                                                                                                                                            value="received"
                                                                                                                                                                                        >


                                                                                                                                                                                        <button
                                                                                                                                                                                            type="submit"
                                                                                                                                                                                            class="btn btn-success w-100"
                                                                                                                                                                                            onclick="
                                                                                                                                                                    return confirm(
                                                                                                                                                                        'Have you received the refund successfully?'
                                                                                                                                                                    );
                                                                                                                                                                "
                                                                                                                                                                                        >

                                                                                                                                                                                            <i
                                                                                                                                                                                                class="fa-solid fa-circle-check me-1"
                                                                                                                                                                                            ></i>

                                                                                                                                                                                            I Received the Refund

                                                                                                                                                                                        </button>

                                                                                                                                                                                    </form>

                                                                                                                                                                                </div>


                                                                                                                                                                                <!-- NOT RECEIVED -->

                                                                                                                                                                                <div class="col-12 col-md-6">

                                                                                                                                                                                    <form
                                                                                                                                                                                        action="{{ route(
                                                                                                                                                    'customer.booking.refund.verify',
                                                                                                                                                    $booking->id
                                                                                                                                                ) }}"
                                                                                                                                                                                        method="POST"
                                                                                                                                                                                    >

                                                                                                                                                                                        @csrf

                                                                                                                                                                                        <input
                                                                                                                                                                                            type="hidden"
                                                                                                                                                                                            name="refund_response"
                                                                                                                                                                                            value="not_received"
                                                                                                                                                                                        >


                                                                                                                                                                                        <button
                                                                                                                                                                                            type="submit"
                                                                                                                                                                                            class="btn btn-outline-danger w-100"
                                                                                                                                                                                            onclick="
                                                                                                                                                                    return confirm(
                                                                                                                                                                        'Are you sure you did not receive the refund?'
                                                                                                                                                                    );
                                                                                                                                                                "
                                                                                                                                                                                        >

                                                                                                                                                                                            <i
                                                                                                                                                                                                class="fa-solid fa-circle-xmark me-1"
                                                                                                                                                                                            ></i>

                                                                                                                                                                                            I Did Not Receive the Refund

                                                                                                                                                                                        </button>

                                                                                                                                                                                    </form>

                                                                                                                                                                                </div>

                                                                                                                                                                            </div>

                                                                                                                                                                        </div>

                                                                                                                                            @endif


                                                                                                                                        </div>

                                                                                                        @endif


                                                                    @endif

                                                                </div>
                                                                <!-- END RIGHT COLUMN -->


                                                            </div>
                                                            <!-- END MAIN DETAIL GRID -->


                                                </section>
                                                <!-- END BOOKING DETAIL CONTENT -->



                            @endif



        
                <!-- =====================================================
 CUSTOMER CANCELLATION MODAL
====================================================== -->

                @if ($canCustomerCancel)

                    <div
                        class="modal fade"
                        id="cancelBookingModal"
                        tabindex="-1"
                        aria-labelledby="cancelBookingModalLabel"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h5
                                        class="modal-title"
                                        id="cancelBookingModalLabel"
                                    >

                                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>

                                        Cancel Booking

                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>

                                </div>


                                <form
                                    action="{{ route('customer.booking.cancel', $booking->id) }}"
                                    method="POST"
                                >

                                    @csrf

                                    <div class="modal-body">

                                        <p class="mb-3">

                                            Are you sure you want to cancel this
                                            scheduled booking?

                                        </p>





                                        <div class="mb-3">

                                            <label
                                                for="cancellation_reason"
                                                class="form-label"
                                            >

                                                Cancellation Reason

                                                <span class="text-muted">
                                                    (Optional)
                                                </span>

                                            </label>

                                            <textarea
                                                        name="cancellation_reason"
                                                        id="cancellation_reason"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="1000"
                                                        placeholder="Please tell us why you want to cancel..."
                                                    ></textarea>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >

                                            Keep Booking

                                        </button>


                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                            id="confirmCancelBookingButton"
                                        >

                                            <i class="fa-solid fa-ban me-2"></i>

                                            Confirm Cancellation

                                        </button>

                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>

                @endif

                <!-- =====================================================
 CUSTOMER REVIEW MODAL
====================================================== -->

                <div
                    class="modal fade"
                    id="customerReviewModal"
                    tabindex="-1"
                    aria-labelledby="customerReviewModalLabel"
                    aria-hidden="true"
                >
                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content customer-review-modal">

                            <!-- HEADER -->

                            <div class="modal-header">

                                <div>

                                    <h5
                                        class="modal-title"
                                        id="customerReviewModalLabel"
                                    >
                                        <i class="fa-solid fa-star me-2"></i>
                                        Rate Your Service
                                    </h5>

                                    <p class="customer-review-subtitle mb-0">
                                        Share your experience with
                                        <strong id="reviewProviderName">
                                            your provider
                                        </strong>
                                    </p>

                                </div>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                ></button>

                            </div>


                            <!-- BODY -->

                            <div class="modal-body">

                                <!-- ERROR -->

                                <div
                                    id="reviewError"
                                    class="alert alert-danger d-none"
                                    role="alert"
                                ></div>


                                <!-- RATING -->

                                <div class="customer-review-rating-section">

                                    <label class="customer-review-label">
                                        Your Rating
                                    </label>


                                    <div
                                        class="customer-review-stars"
                                        id="customerReviewStars"
                                    >

                                        <button
                                            type="button"
                                            class="review-star"
                                            data-rating="1"
                                            aria-label="1 star"
                                        >
                                            <i class="fa-regular fa-star"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="review-star"
                                            data-rating="2"
                                            aria-label="2 stars"
                                        >
                                            <i class="fa-regular fa-star"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="review-star"
                                            data-rating="3"
                                            aria-label="3 stars"
                                        >
                                            <i class="fa-regular fa-star"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="review-star"
                                            data-rating="4"
                                            aria-label="4 stars"
                                        >
                                            <i class="fa-regular fa-star"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="review-star"
                                            data-rating="5"
                                            aria-label="5 stars"
                                        >
                                            <i class="fa-regular fa-star"></i>
                                        </button>

                                    </div>


                                    <div
                                        id="reviewRatingText"
                                        class="customer-review-rating-text"
                                    >
                                        Select a rating
                                    </div>


                                    <input
                                        type="hidden"
                                        id="reviewRating"
                                        value=""
                                    >

                                </div>


                                <!-- COMMENT -->

                                <div class="customer-review-comment-section">

                                    <label
                                        for="reviewComment"
                                        class="customer-review-label"
                                    >
                                        Comment
                                        <span>
                                            (Optional)
                                        </span>
                                    </label>


                                    <textarea
                        id="reviewComment"
                        class="form-control"
                        rows="5"
                        maxlength="1000"
                        placeholder="Tell us about your experience..."
                    ></textarea>


                                    <div class="customer-review-character-count">

                                        <span id="reviewCharacterCount">
                                            0
                                        </span>

                                        / 1000

                                    </div>

                                </div>

                            </div>


                            <!-- FOOTER -->

                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal"
                                >
                                    Maybe Later
                                </button>


                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    id="submitCustomerReview"
                                >

                                    <i class="fa-solid fa-paper-plane me-2"></i>

                                    Submit Review

                                </button>

                            </div>

                        </div>

                    </div>
                </div>
            </main>
        </div>


        <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>
        <script
        src="{{ asset('js/quickfix.js') }}"
    ></script>
        <script
        src="{{ asset('js/customer.js') }}"
    ></script>
        <script>
            window.customerChatUrl = "{{ route('customer.chats') }}";
        </script>

        <script
        src="{{ asset('js/customer-booking-detail.js') }}"
    ></script>
        <script
    src="{{ asset('js/customer-review.js') }}"
></script>


    </body>

</html>
