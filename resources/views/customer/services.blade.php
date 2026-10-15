<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>QuickFix - My Services</title>


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
         CUSTOMER SERVICES CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/customer-services.css') }}"
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
                href="{{ route('customer.dashboard') }}"
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


        <!-- =================================================
             CUSTOMER PROFILE
        ================================================== -->

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
                class="customer-menu-link active"
            >

                <i class="fa-solid fa-briefcase"></i>

                <span class="customer-menu-text">
                    My Services
                </span>

            </a>


            <!-- My Bookings -->

            <a
                href="{{ route('customer.bookings') }}"
                class="customer-menu-link"
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
         MAIN CONTENT
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
                    title="Open menu"
                >

                    <i class="fa-solid fa-bars"></i>

                </button>


                <!-- Page Title -->

                <div class="customer-header-title">

                    <h1>
                        My Services
                    </h1>

                    <p>
                        View the services you have used with QuickFix.
                    </p>

                </div>


            </div>


            <!-- =================================================
                 HEADER ACTIONS
            ================================================== -->

            <div class="customer-header-actions">

    {{-- Notifications --}}
    <div class="customer-notification-wrapper">

        <button
            type="button"
            class="customer-notification-button"
            id="notificationButton"
            aria-label="Notifications"
            title="Notifications"
        >

            <i class="fa-solid fa-bell"></i>

            @php
                $unreadNotifications =
                    auth()->user()->unreadNotifications()->count();
            @endphp

            @if($unreadNotifications > 0)

                <span
                    class="customer-notification-badge"
                    id="notificationBadge"
                >
                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                </span>

            @endif

        </button>

        <div
            class="customer-notification-dropdown"
            id="notificationDropdown"
        >

            <div class="customer-notification-header">

                <h4>
                    Notifications
                </h4>

                <a
                    href="{{ route('customer.notifications') }}"
                    class="customer-notification-view-all"
                >
                    View All
                </a>

            </div>

            <div
                class="customer-notification-list"
                id="notificationList"
            >

                @forelse(
                    auth()->user()->notifications()->latest()->take(10)->get()
                    as $notification
                )

                    <div
                        class="customer-notification-item {{ $notification->read_at ? '' : 'unread' }}"
                        data-notification-id="{{ $notification->id }}"
                    >

                        <div class="customer-notification-icon">

                            <i class="fa-solid fa-bell"></i>

                        </div>

                        <div class="customer-notification-content">

                            <p>
                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                            </p>

                            <small>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>

                        </div>

                        @if(!$notification->read_at)

                            <button
                                type="button"
                                class="customer-notification-read-btn"
                                data-notification-id="{{ $notification->id }}"
                                title="Mark as read"
                            >
                                <i class="fa-solid fa-check"></i>
                            </button>

                        @endif

                    </div>

                @empty

                    <div class="customer-notification-empty">

                        <i class="fa-regular fa-bell-slash"></i>

                        <p>
                            No notifications
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- Theme --}}
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
             PAGE CONTENT
        ================================================== -->

        <section class="customer-services-content">


            <div class="container-fluid">


                <!-- =================================================
                     SUMMARY CARDS
                ================================================== -->

                <div class="row g-3 mb-4">


                    <!-- Total Services -->

                    <div class="col-xl-4 col-md-4">

                        <div class="service-summary-card">

                            <div class="service-summary-icon">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                            </div>


                            <div>

                                <span>
                                    Total Services
                                </span>

                                <strong>
                                    {{ $totalServices }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- Completed -->

                    <div class="col-xl-4 col-md-4">

                        <div class="service-summary-card">

                            <div class="service-summary-icon completed">

                                <i class="fa-solid fa-circle-check"></i>

                            </div>


                            <div>

                                <span>
                                    Completed
                                </span>

                                <strong>
                                    {{ $completedServices }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <!-- Cancelled -->

                    <div class="col-xl-4 col-md-4">

                        <div class="service-summary-card">

                            <div class="service-summary-icon cancelled">

                                <i class="fa-solid fa-circle-xmark"></i>

                            </div>


                            <div>

                                <span>
                                    Cancelled
                                </span>

                                <strong>
                                    {{ $cancelledServices }}
                                </strong>

                            </div>

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
                     SERVICE HISTORY CARD
                ================================================== -->

                <div class="customer-services-card">


                    <!-- =================================================
                         CARD HEADER
                    ================================================== -->

                    <div class="services-card-header">



                        <div>

                            <h4>
                                Service History
                            </h4>

                            <p>
                                Your previous QuickFix services
                            </p>
                            

                        </div>
                        


                        <!-- Filter -->

                        <div class="services-filter">

                            <select
                                class="form-select"
                                id="serviceFilter"
                            >

                                <option
                                    value="all"
                                    selected
                                >
                                    All Services
                                </option>

                                <option value="completed">
                                    Completed
                                </option>

                                <option value="cancelled">
                                    Cancelled
                                </option>

                            </select>

                        </div>


                    </div>


                    <!-- =================================================
                         SERVICE HISTORY LIST
                    ================================================== -->

                    <div
                        class="service-history-list"
                        id="serviceHistoryList"
                    >


                        @forelse($serviceHistory as $history)

                            @php

                                $booking = $history['booking'];

                                $service = $history['service'];

                                $status = $booking->status;


                                /*
                                 * Status CSS class
                                 */

                                $statusClass = match ($status) {

                                    'completed'
                                        => 'completed-status',

                                    'cancelled'
                                        => 'cancelled-status',

                                    default
                                        => 'ongoing-status',

                                };


                                /*
                                 * Status display text
                                 */

                                $statusText = match ($status) {

                                    'completed'
                                        => 'Completed',

                                    'cancelled'
                                        => 'Cancelled',

                                    'on_the_way'
                                        => 'On The Way',

                                    'arrived'
                                        => 'Arrived',

                                    'in_progress'
                                        => 'In Progress',

                                    'accepted'
                                        => 'Accepted',

                                    'pending'
                                        => 'Pending',

                                    'timeout'
                                        => 'Timeout',

                                    default
                                        => ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        ),

                                };

                            @endphp


                            <!-- =================================================
                                 SERVICE ITEM
                            ================================================== -->

                            <div
                                class="service-history-item"
                                data-status="{{ $status }}"
                            >


                                <!-- Service Icon -->

                                <div class="service-history-icon">

                                    <i class="fa-solid fa-screwdriver-wrench"></i>

                                </div>


                                <!-- Service Information -->

                                <div class="service-history-info">


                                    <div class="service-history-title-row">

                                        <h5>
                                            {{ $service->name }}
                                        </h5>


                                        <span
                                            class="service-status {{ $statusClass }}"
                                        >
                                            {{ $statusText }}
                                        </span>

                                    </div>


                                    <!-- Provider -->

                                    <p class="service-provider">

                                        <i class="fa-solid fa-user"></i>

                                        {{ $booking->provider?->user?->name ?? 'Provider' }}

                                    </p>


                                    <!-- Service Meta -->

                                    <div class="service-meta">


                                        <!-- Date -->

                                        <span>

                                            <i class="fa-regular fa-calendar"></i>

                                            @if($booking->scheduled_at)

                                                {{ \Carbon\Carbon::parse($booking->scheduled_at)->format('M d, Y') }}

                                            @else

                                                Date unavailable

                                            @endif

                                        </span>


                                        <!-- Location -->

                                        <span>

                                            <i class="fa-solid fa-location-dot"></i>

                                            {{ $booking->area?->city?->name ?? 'Location unavailable' }}

                                        </span>


                                    </div>

                                </div>


                                <!-- Price -->

                                <div class="service-history-price">


                                    <span>
                                        Total
                                    </span>


                                    @if($status === 'cancelled')

                                        <strong>
                                            —
                                        </strong>

                                    @else

                                        <strong>
                                            {{ number_format($booking->total_amount ?? 0) }}
                                            MMK
                                        </strong>

                                    @endif


                                    <!-- View Details -->

                                    <a
                                        href="{{ route('customer.booking.detail', $booking->id) }}"
                                        class="service-details-btn"
                                    >

                                        View Details

                                        <i class="fa-solid fa-arrow-right"></i>

                                    </a>


                                </div>


                            </div>


                        @empty


                            <!-- =================================================
                                 EMPTY SERVICE HISTORY
                            ================================================== -->

                            <div class="text-center py-5">


                                <i
                                    class="fa-solid fa-screwdriver-wrench mb-3"
                                    style="font-size: 40px; color: #9ca3af;"
                                ></i>


                                <h5>
                                    No service history yet.
                                </h5>


                                <p class="text-muted mb-0">

                                    Your QuickFix services will appear here
                                    after you make a booking.

                                </p>


                            </div>


                        @endforelse


                    </div>


                    <!-- =================================================
                         PAGINATION
                    ================================================== -->

                    <div class="services-pagination">


                        <button
                            type="button"
                            class="pagination-btn disabled"
                            disabled
                        >

                            <i class="fa-solid fa-chevron-left"></i>

                        </button>


                        <button
                            type="button"
                            class="pagination-number active"
                        >
                            1
                        </button>


                        <button
                            type="button"
                            class="pagination-number"
                        >
                            2
                        </button>


                        <button
                            type="button"
                            class="pagination-number"
                        >
                            3
                        </button>


                        <button
                            type="button"
                            class="pagination-btn"
                        >

                            <i class="fa-solid fa-chevron-right"></i>

                        </button>


                    </div>


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
     QUICKFIX GLOBAL JS
====================================================== -->

<script
    src="{{ asset('js/quickfix.js') }}"
></script>


<!-- =====================================================
     CUSTOMER SHARED JS
====================================================== -->

<script
    src="{{ asset('js/customer.js') }}"
></script>


<!-- =====================================================
     CUSTOMER SERVICES JS
====================================================== -->

<script
    src="{{ asset('js/customer-services.js') }}"
></script>


</body>

</html>