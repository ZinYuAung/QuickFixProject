<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Provider Dashboard - QuickFix</title>


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- QuickFix Global CSS -->
    <link rel="stylesheet" href="{{ asset('css/quickfix.css') }}">


    <!-- Provider CSS -->
    <link rel="stylesheet" href="{{ asset('css/provider.css') }}">

</head>


<body>


    <div class="provider-layout" id="providerLayout">


        <!-- =====================================================
         SIDEBAR
    ====================================================== -->

        <aside class="provider-sidebar">


            <!-- =================================================
             SIDEBAR HEADER
        ================================================== -->

            <div class="provider-sidebar-header">


                <!-- QuickFix Logo -->

                <button type="button" class="provider-logo sidebar-logo-toggle" aria-label="Expand sidebar"
                    title="Expand sidebar">

                    <span class="provider-logo-icon">
                        <i class="fa-solid fa-wrench"></i>
                    </span>

                    <span class="provider-logo-text">
                        QuickFix
                    </span>

                </button>


                <!-- Hamburger RIGHT SIDE -->

                <button type="button" class="sidebar-toggle" aria-label="Collapse sidebar" title="Collapse sidebar">

                    <i class="fa-solid fa-bars"></i>

                </button>


            </div>



            <!-- =================================================
             PROVIDER PROFILE
        ================================================== -->

            <div class="provider-profile">


                <div class="provider-avatar">

                    <i class="fa-solid fa-user"></i>

                </div>


                <div class="provider-profile-info">

                    <h4>
                        {{ auth()->user()->name ?? 'Provider' }}
                    </h4>

                    <span>
                        Service Provider
                    </span>

                </div>


            </div>



            <!-- =================================================
             SIDEBAR MENU
        ================================================== -->

            <nav class="provider-sidebar-menu">


                <a href="{{ route('provider.dashboard') }}" class="provider-menu-link active">

                    <i class="fa-solid fa-house"></i>

                    <span class="provider-menu-text">
                        Dashboard
                    </span>

                </a>


                <a href="{{ route('provider.bookings') }}" class="provider-menu-link">

                    <i class="fa-solid fa-calendar-check"></i>

                    <span class="provider-menu-text">
                        My Bookings
                    </span>

                </a>


                <a href="{{ route('provider.jobs') }}" class="provider-menu-link">

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                    <span class="provider-menu-text">
                        My Jobs
                    </span>

                </a>


                <a href="{{ route('provider.earnings') }}" class="provider-menu-link">

                    <i class="fa-solid fa-wallet"></i>

                    <span class="provider-menu-text">
                        Earnings
                    </span>

                </a>
                <a href="{{ route('provider.payments.index') }}" class="provider-menu-link">
    <i class="fa-solid fa-credit-card"></i>
    <span class="provider-menu-text">
        Payments
    </span>
</a>


                <a href="{{ route('provider.chats') }}" class="provider-menu-link">
                    <i class="fa-solid fa-comments"></i>

                    <span class="provider-menu-text">
                        Chats
                    </span>

                    <span class="provider-chat-badge" id="providerSidebarChatUnread" style="display: none;">
                        0
                    </span>
                </a>
                <a href="{{ route('provider.reviews.index') }}" class="provider-menu-link ">
                    <i class="fa-solid fa-star"></i>

                    <span class="provider-menu-text">
                        Reviews & Ratings
                    </span>
                </a>

                <a href="{{ route('provider.profile') }}" class="provider-menu-link">

                    <i class="fa-solid fa-user"></i>

                    <span class="provider-menu-text">
                        My Profile
                    </span>

                </a>


            </nav>



            <!-- =================================================
             SIDEBAR BOTTOM
        ================================================== -->

            <div class="provider-sidebar-bottom">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="provider-menu-link provider-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="provider-menu-text logout-text">
                            Logout
                        </span>
                    </button>
                </form>

            </div>


        </aside>



        <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

        <div class="provider-overlay" id="providerOverlay"></div>



        <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

        <main class="provider-main">


            <!-- =================================================
             TOP HEADER
        ================================================== -->

            <header class="provider-header">


                <div class="provider-header-left">

                    <button type="button" class="provider-mobile-menu" aria-label="Open menu" title="Open menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div>
                        <h1>Provider Dashboard</h1>
                        <p>
                            Welcome back, {{ auth()->user()->name ?? 'Provider' }}!
                        </p>
                    </div>

                </div>


                <div class="provider-header-actions">
                    <!-- =================================================
 NOTIFICATIONS
================================================== -->

                    <div class="provider-notification-wrapper">

                        <button type="button" class="provider-notification-button" id="providerNotificationButton"
                            aria-label="Notifications" title="Notifications">

                            <i class="fa-solid fa-bell"></i>

                            @if(
                                    auth()->user()
                                        ->unreadNotifications()
                                        ->count() > 0
                                )
                                <span class="provider-notification-badge" id="providerNotificationBadge">
                                    {{ auth()->user()->unreadNotifications()->count() }}
                                </span>
                            @endif

                        </button>


                        <!-- Notification Dropdown -->

                        <div class="provider-notification-dropdown" id="providerNotificationDropdown">

                            <!-- Dropdown Header -->

                            <div class="provider-notification-header">

                                <div>

                                    <h4>
                                        Notifications
                                    </h4>

                                    <span id="providerNotificationUnreadText">
                                        {{ auth()->user()->unreadNotifications()->count() }}
                                        unread
                                    </span>

                                </div>


                                <a href="{{ route('provider.notifications') }}">
                                    View All
                                </a>

                            </div>


                            <!-- Notification List -->

                            <div class="provider-notification-list" id="providerNotificationList">

                                @forelse(
                                                                auth()->user()
                                                                    ->unreadNotifications()
                                                                    ->latest()
                                                                    ->take(10)
                                                                    ->get()
                                                                as $notification
                                                            )

                                                            @php
                                                                $data = $notification->data;
                                                                $bookingId = $data['booking_id'] ?? null;
                                                            @endphp

                                                            <div class="provider-notification-item unread"
                                                                data-notification-id="{{ $notification->id }}">

                                                                <a href="{{ $bookingId
                                    ? route(
                                        'provider.bookings.show',
                                        $bookingId
                                    )
                                    : '#'
                                                                                                                                                                                                                                                                                                                                                    }}"
                                                                    class="provider-notification-link">

                                                                    <div class="provider-notification-content">

                                                                        <strong>
                                                                            {{ $data['title'] ?? 'Notification' }}
                                                                        </strong>

                                                                        <p>
                                                                            {{ $data['message'] ?? '' }}
                                                                        </p>

                                                                        <small>
                                                                            {{ $notification->created_at->diffForHumans() }}
                                                                        </small>

                                                                    </div>

                                                                </a>


                                                                <div class="provider-notification-actions">

                                                                    <button type="button" class="provider-mark-read-btn"
                                                                        data-id="{{ $notification->id }}">
                                                                        Read
                                                                    </button>

                                                                    <button type="button" class="provider-delete-notification-btn"
                                                                        data-id="{{ $notification->id }}">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>

                                                                </div>

                                                            </div>

                                @empty

                                    <div class="provider-notification-empty">
                                        No new notifications
                                    </div>

                                @endforelse

                            </div>


                            <!-- Dropdown Footer -->

                            <div class="provider-notification-footer">

                                <a href="{{ route('provider.notifications') }}">
                                    View All Notifications
                                </a>

                            </div>

                        </div>

                    </div>


                   
<!-- Online / Offline -->

@php
    $isProviderOnline = $provider->availability_status === 'online';
@endphp

<div class="provider-status"
     data-status-url="{{ route('provider.availability-status') }}"
     data-csrf-token="{{ csrf_token() }}">

    <span
        class="status-dot"
        style="background: {{ $isProviderOnline ? '#16a34a' : '#94a3b8' }};">
    </span>

    <span
        class="status-text"
        style="color: {{ $isProviderOnline ? '#16a34a' : '#64748b' }};">
        {{ $isProviderOnline ? 'Online' : 'Offline' }}
    </span>

    <label class="status-switch">

        <input
            type="checkbox"
            class="status-toggle"
            {{ $isProviderOnline ? 'checked' : '' }}>

        <span class="status-slider"></span>

    </label>

</div>



                    <!-- Dark / Light Mode -->

                    <button type="button" class="provider-theme-toggle" id="themeToggle" aria-label="Toggle dark mode"
                        title="Toggle dark mode">
                        <i class="fa-solid fa-moon" id="themeIcon"></i>
                    </button>


                </div>


            </header>



            <!-- =================================================
             DASHBOARD CONTENT
        ================================================== -->

            <div class="provider-content">


                <!-- =================================================
                 STATS
            ================================================== -->

                <div class="provider-stats-grid">


                    <!-- All Bookings -->

<div class="provider-stat-card">

    <div class="provider-stat-icon blue">
        <i class="fa-solid fa-calendar-check"></i>
    </div>

    <div>
        <span>All Bookings</span>
        <h2 data-stat="all-bookings">
            {{ $allBookingsCount }}
        </h2>
    </div>

</div>


                    <!-- Pending -->

                    <div class="provider-stat-card">

                        <div class="provider-stat-icon orange">

                            <i class="fa-solid fa-clock"></i>

                        </div>

                        <div>

                            <span>
                                Pending Requests
                            </span>

                            <h2 data-stat="pending-requests">
    {{ $pendingRequestsCount }}
</h2>

                        </div>

                    </div>


                    <!-- Completed -->

                    <div class="provider-stat-card">

                        <div class="provider-stat-icon green">

                            <i class="fa-solid fa-circle-check"></i>

                        </div>

                        <div>

                            <span>
                                Completed Jobs
                            </span>

                            <h2>
                                {{ $completedJobsCount }}
                            </h2>

                        </div>

                    </div>


                  <!-- Rejected / Cancelled -->

<div class="provider-stat-card">

    <div class="provider-stat-icon purple">

        <i class="fa-solid fa-ban"></i>

    </div>

    <div>

        <span>
            Rejected / Cancelled
        </span>

        <h2 data-stat="rejected-cancelled">
            {{ $rejectedCancelledCount }}
        </h2>

    </div>

</div>


</div> {{-- CLOSE provider-stats-grid --}}



<!-- =================================================
 TWO COLUMN SECTION
================================================== -->

<div class="provider-dashboard-grid">


                    <!-- =================================================
                     BOOKING REQUESTS
                ================================================== -->

                    <section class="provider-card">


                        <div class="provider-card-header">

                            <div>

                                <h3>
                                    Booking Requests
                                </h3>

                                <p>
                                    New service requests
                                </p>

                            </div>


                            <a href="{{ route('provider.bookings') }}">
                                View All
                            </a>

                        </div>
                        <div id="providerBookingRequests">

                            @forelse($bookingRequests as $booking)

                                <div class="booking-request" id="booking-request-{{ $booking->id }}"
                                    data-booking-id="{{ $booking->id }}">

                                    <!-- Customer Information -->

                                    <div class="booking-user">

                                        <div class="booking-avatar">

                                            @if($booking->customer?->user?->profile_image)

                                                <img src="{{ asset('storage/' . $booking->customer->user->profile_image) }}"
                                                    alt="Customer" class="booking-customer-image">

                                            @else

                                                                                {{ strtoupper(
                                                    substr(
                                                        $booking->customer?->user?->name ?? 'C',
                                                        0,
                                                        1
                                                    )
                                                ) }}

                                            @endif

                                        </div>


                                        <div>

                                            <h4>
                                                {{ $booking->customer?->user?->name ?? 'Customer' }}
                                            </h4>


                                            <!-- Chosen Services -->

                                            <p>

                                                @forelse($booking->dashboardServices as $service)

                                                    {{ $service->name }}@if(!$loop->last), @endif

                                                @empty

                                                    No service selected

                                                @endforelse

                                            </p>


                                            <!-- Location -->

                                            <small>

                                                <i class="fa-solid fa-location-dot"></i>

                                                {{ $booking->area?->city?->name ?? 'N/A' }}

                                                @if($booking->area?->name)

                                                    · {{ $booking->area->name }}

                                                @endif

                                            </small>

                                        </div>

                                    </div>


                                    <!-- Amount + Actions -->

                                    <div class="booking-right">

                                        <strong>
                                            {{ number_format($booking->total_amount ?? 0, 0) }} MMK
                                        </strong>


                                        <div class="booking-actions">


                                            <!-- ACCEPT -->

                                            <form action="{{ route('provider.bookings.accept', $booking->id) }}"
                                                method="POST" class="provider-booking-action-form" data-action="accept"
                                                data-booking-id="{{ $booking->id }}">

                                                @csrf

                                                <button type="submit" class="provider-action-btn provider-btn-success"
                                                    title="Accept Booking">

                                                    <i class="fa-solid fa-check"></i>

                                                </button>

                                            </form>


                                            <!-- REJECT -->

                                            <form action="{{ route('provider.bookings.reject', $booking->id) }}"
                                                method="POST" class="provider-booking-action-form" data-action="reject"
                                                data-booking-id="{{ $booking->id }}">

                                                @csrf

                                                <button type="submit" class="provider-action-btn provider-btn-danger"
                                                    title="Reject Booking">

                                                    <i class="fa-solid fa-xmark"></i>

                                                </button>

                                            </form>


                                            <!-- VIEW -->

                                            <a href="{{ route('provider.bookings.show', $booking->id) }}"
                                                class="provider-action-btn" title="View Booking">

                                                <i class="fa-solid fa-eye"></i>

                                            </a>

                                        </div>

                                    </div>

                                </div>


                            @empty

                                <div class="text-center py-4" id="no-provider-booking-requests">

                                    <i class="fa-solid fa-calendar-check mb-2" style="font-size: 28px; opacity: 0.5;"></i>

                                    <p class="mb-0">
                                        No pending booking requests.
                                    </p>

                                </div>

                            @endforelse

                        </div>


                    </section>



                    <!-- =================================================
     MONTHLY EARNINGS
================================================== -->

                    <section class="provider-card earnings-card">

                        <div class="provider-card-header">

                            <div>

                                <h3>
                                     Earnings
                                </h3>

                                <p>
                                    Your verified earnings overview
                                </p>

                            </div>

                            
                        </div>


                        <!-- Month Selector -->

                        <form method="GET" action="{{ route('provider.dashboard') }}" class="mb-4">

                            <label for="earning_date" class="form-label">
                                Select Month
                            </label>

                            <div class="d-flex gap-2">

                                <input type="month" id="earning_date" name="earning_date" value="{{ $earningDate }}"
                                    class="form-control">

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-filter"></i>
                                    View
                                </button>

                            </div>

                        </form>

                        <!-- Earnings Total -->

                        <div class="earnings-total">

                            <span>
                                Verified Earnings
                            </span>

                            <h2>
                                {{ number_format($verifiedEarnings, 0) }} MMK
                            </h2>

                            <small>
                                <i class="fa-solid fa-circle-check"></i>

                                {{ \Carbon\Carbon::createFromFormat('Y-m', $earningDate)->format('F Y') }}

                            </small>

                        </div>


                        <!-- Earnings Chart -->

                        <div class="earnings-chart-wrapper">

                            <div class="earnings-chart-header">

                                <div>
                                    <strong>
                                        Daily Earnings
                                    </strong>

                                    <small>
                                        Verified payment earnings
                                    </small>
                                </div>

                                <i class="fa-solid fa-chart-line"></i>

                            </div>


                            <div class="earnings-chart">

                                <canvas id="providerEarningsChart"></canvas>

                            </div>

                        </div>


                    </section>


                </div>



                <!-- =================================================
                 TODAY'S JOBS
            ================================================== -->

                <section class="provider-card">


                    <div class="provider-card-header">

                        <div>

                            <h3>
                                Today's Jobs
                            </h3>

                            <p>
                                Your upcoming service jobs
                            </p>

                        </div>


                        <a href="{{ route('provider.jobs') }}">
                            View All
                        </a>

                    </div>


                    <div class="jobs-list" id="providerTodayJobs"
                        data-today-jobs-url="{{ route('provider.dashboard.today-jobs') }}">

                        @forelse($todayJobs as $booking)

                            <div class="job-item">

                                <!-- Time -->

                                <div class="job-time">

                                    <strong>
                                        {{ $booking->scheduled_at?->format('h:i A') ?? 'Time N/A' }}
                                    </strong>

                                    <span>
                                        Today
                                    </span>

                                </div>


                                <!-- Job Icon -->

                                <div class="job-icon blue">

                                    <i class="fa-solid fa-screwdriver-wrench"></i>

                                </div>


                                <!-- Job Information -->

                                <div class="job-info">

                                    <h4>

                                        @forelse($booking->dashboardServices as $service)

                                            {{ $service->name }}@if(!$loop->last), @endif

                                        @empty

                                            No service selected

                                        @endforelse

                                    </h4>


                                    <p>
                                        {{ $booking->customer?->user?->name ?? 'Customer' }}
                                    </p>


                                    <small>

                                        <i class="fa-solid fa-location-dot"></i>

                                        {{ $booking->area?->city?->name ?? 'N/A' }}

                                        @if($booking->area?->name)

                                            · {{ $booking->area->name }}

                                        @endif

                                    </small>

                                </div>


                                <!-- Status -->

                                <span class="job-status
                                                            @if($booking->status === 'accepted')
                                                                confirmed
                                                            @elseif($booking->status === 'in_progress')
                                                                confirmed
                                                            @else
                                                                pending
                                                            @endif
                                                        ">

                                    {{ ucwords(str_replace('_', ' ', $booking->status)) }}

                                </span>


                                <!-- View -->

                                <a href="{{ route('provider.bookings.show', $booking->id) }}" class="provider-action-btn"
                                    title="View Booking">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                            </div>

                        @empty

                            <div class="text-center py-4">

                                <i class="fa-solid fa-calendar-check mb-2" style="font-size: 28px; opacity: 0.5;"></i>

                                <p class="mb-0">
                                    No jobs scheduled for today.
                                </p>

                            </div>

                        @endforelse

                    </div>


                </section>



                <!-- =================================================
                 QUICK ACTIONS
            ================================================== -->

                <section class="provider-card">

                    <div class="provider-card-header">

                        <div>

                            <h3>
                                Quick Actions
                            </h3>

                            <p>
                                Manage your account quickly
                            </p>

                        </div>

                    </div>


                    <div class="quick-actions-grid">


                        <!-- View Bookings -->

                        <a href="{{ route('provider.bookings') }}" class="quick-action">

                            <i class="fa-solid fa-calendar-check"></i>

                            <span>
                                View Bookings
                            </span>

                        </a>


                        <!-- My Jobs -->

                        <a href="{{ route('provider.jobs') }}" class="quick-action">

                            <i class="fa-solid fa-screwdriver-wrench"></i>

                            <span>
                                My Jobs
                            </span>

                        </a>


                        <!-- Earnings -->

                        <a href="{{ route('provider.earnings') }}" class="quick-action">

                            <i class="fa-solid fa-wallet"></i>

                            <span>
                                Earnings
                            </span>

                        </a>


                        <!-- Edit Profile -->

                        <a href="{{ route('provider.profile.edit') }}" class="quick-action">

                            <i class="fa-solid fa-user-pen"></i>

                            <span>
                                Edit Profile
                            </span>

                        </a>


                    </div>

                </section>


            </div>


        </main>


    </div>



    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Chart.js -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Provider Earnings Chart -->

    <script>

        const earningsData = @json($dailyEarnings);

        const earningsChartElement =
            document.getElementById(
                'providerEarningsChart'
            );

        if (earningsChartElement) {

            new Chart(
                earningsChartElement,
                {
                    type: 'line',

                    data: {

                        labels: earningsData.map(
                            item => item.date
                        ),

                        datasets: [
                            {
                                label: 'Daily Earnings',

                                data: earningsData.map(
                                    item => item.total
                                ),

                                tension: 0.35,

                                fill: true,

                                pointRadius: 3,

                                pointHoverRadius: 6
                            }
                        ]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {
                                display: false
                            },

                            tooltip: {

                                callbacks: {

                                    label: function (context) {

                                        return (
                                            Number(
                                                context.raw
                                            ).toLocaleString()
                                            + ' MMK'
                                        );

                                    }

                                }

                            }

                        },

                        scales: {

                            x: {

                                grid: {
                                    display: false
                                }

                            },

                            y: {

                                beginAtZero: true,

                                ticks: {

                                    callback: function (value) {

                                        return Number(
                                            value
                                        ).toLocaleString()
                                            + ' MMK';

                                    }

                                }

                            }

                        }

                    }

                }
            );

        }

    </script>
    <!-- ==========================================
     PROVIDER BOOKING REQUEST AJAX
========================================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const bookingContainer =
                document.getElementById('providerBookingRequests');

            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');


            if (!bookingContainer || !csrfToken) {
                return;
            }


            // ==========================================
            // LOAD BOOKING REQUESTS
            // ==========================================

            async function loadBookingRequests() {

                try {

                    const response = await fetch(
                        "{{ route('provider.dashboard.booking-requests') }}",
                        {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },

                            cache: 'no-store'
                        }
                    );


                    if (!response.ok) {
                        throw new Error(
                            'Failed to load booking requests.'
                        );
                    }


                    const data = await response.json();


                    if (!data.success) {
                        return;
                    }


                    updateBookingRequests(
                        data.bookingRequests || []
                    );


                    updatePendingCount(
                        data.pending_count ?? 0
                    );


                } catch (error) {

                    console.error(
                        'Booking request polling error:',
                        error
                    );

                }

            }


            // ==========================================
            // UPDATE BOOKING REQUESTS
            // ==========================================

            function updateBookingRequests(bookings) {

                // --------------------------------------
                // No pending bookings
                // --------------------------------------

                if (bookings.length === 0) {

                    bookingContainer.innerHTML = `
                <div
                    class="text-center py-4"
                    id="no-provider-booking-requests"
                >

                    <i
                        class="fa-solid fa-calendar-check mb-2"
                        style="font-size: 28px; opacity: 0.5;"
                    ></i>

                    <p class="mb-0">
                        No pending booking requests.
                    </p>

                </div>
            `;

                    return;
                }


                // --------------------------------------
                // Remove empty message
                // --------------------------------------

                const emptyMessage =
                    document.getElementById(
                        'no-provider-booking-requests'
                    );


                if (emptyMessage) {
                    emptyMessage.remove();
                }


                // --------------------------------------
                // Existing booking IDs
                // --------------------------------------

                const currentIds = new Set(
                    bookings.map(
                        booking => String(booking.id)
                    )
                );


                // --------------------------------------
                // Remove bookings no longer pending
                // --------------------------------------

                bookingContainer
                    .querySelectorAll('.booking-request')
                    .forEach(card => {

                        const bookingId =
                            String(card.dataset.bookingId);


                        if (!currentIds.has(bookingId)) {
                            card.remove();
                        }

                    });


                // --------------------------------------
                // Add NEW bookings
                // --------------------------------------

                bookings.forEach(booking => {

                    const existingCard =
                        document.getElementById(
                            `booking-request-${booking.id}`
                        );


                    if (existingCard) {
                        return;
                    }


                    bookingContainer.insertAdjacentHTML(
                        'beforeend',
                        createBookingRequestHTML(booking)
                    );

                });

            }


            // ==========================================
            // CREATE BOOKING CARD
            // ==========================================

            function createBookingRequestHTML(booking) {

                const services =
                    Array.isArray(booking.services) &&
                        booking.services.length > 0
                        ? booking.services.join(', ')
                        : 'No service selected';


                const location =
                    booking.area
                        ? `${escapeHTML(booking.city)} · ${escapeHTML(booking.area)}`
                        : escapeHTML(booking.city);


                const customerName =
                    escapeHTML(
                        booking.customer_name || 'Customer'
                    );


                const profileHTML =
                    booking.profile_image
                        ? `
                    <img
                        src="${escapeAttribute(booking.profile_image)}"
                        alt="Customer"
                        class="booking-customer-image"
                    >
                `
                        : `
                    ${escapeHTML(
                            booking.customer_initial || 'C'
                        )}
                `;


                return `
            <div
                class="booking-request"
                id="booking-request-${booking.id}"
                data-booking-id="${booking.id}"
            >

                <div class="booking-user">

                    <div class="booking-avatar">
                        ${profileHTML}
                    </div>


                    <div>

                        <h4>
                            ${customerName}
                        </h4>


                        <p>
                            ${escapeHTML(services)}
                        </p>


                        <small>

                            <i class="fa-solid fa-location-dot"></i>

                            ${location}

                        </small>

                    </div>

                </div>


                <div class="booking-right">

                    <strong>
                        ${Number(
                    booking.total_amount || 0
                ).toLocaleString()} MMK
                    </strong>


                    <div class="booking-actions">

                        <!-- ACCEPT -->

                        <form
                            action="${escapeAttribute(booking.accept_url)}"
                            method="POST"
                            class="provider-booking-action-form"
                            data-action="accept"
                            data-booking-id="${booking.id}"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="${escapeAttribute(csrfToken)}"
                            >

                            <button
                                type="submit"
                                class="provider-action-btn provider-btn-success"
                                title="Accept Booking"
                            >

                                <i class="fa-solid fa-check"></i>

                            </button>

                        </form>


                        <!-- REJECT -->

                        <form
                            action="${escapeAttribute(booking.reject_url)}"
                            method="POST"
                            class="provider-booking-action-form"
                            data-action="reject"
                            data-booking-id="${booking.id}"
                        >

                            <input
                                type="hidden"
                                name="_token"
                                value="${escapeAttribute(csrfToken)}"
                            >

                            <button
                                type="submit"
                                class="provider-action-btn provider-btn-danger"
                                title="Reject Booking"
                            >

                                <i class="fa-solid fa-xmark"></i>

                            </button>

                        </form>


                        <!-- VIEW -->

                        <a
                            href="${escapeAttribute(booking.show_url)}"
                            class="provider-action-btn"
                            title="View Booking"
                        >

                            <i class="fa-solid fa-eye"></i>

                        </a>

                    </div>

                </div>

            </div>
        `;

            }


            // ==========================================
            // ACCEPT / REJECT
            // ==========================================

            bookingContainer.addEventListener(
                'submit',
                async function (event) {

                    const form =
                        event.target.closest(
                            '.provider-booking-action-form'
                        );


                    if (!form) {
                        return;
                    }


                    event.preventDefault();


                    const bookingId =
                        form.dataset.bookingId;


                    const card =
                        document.getElementById(
                            `booking-request-${bookingId}`
                        );


                    const button =
                        form.querySelector(
                            'button[type="submit"]'
                        );


                    if (button) {
                        button.disabled = true;
                    }


                    try {

                        const response =
                            await fetch(
                                form.action,
                                {
                                    method: 'POST',

                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken
                                    },

                                    body: new FormData(form)
                                }
                            );


                        const data =
                            await response.json();


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Booking action failed.'
                            );

                        }


                        if (data.success) {

                            // Remove immediately

                            if (card) {
                                card.remove();
                            }


                            // Show empty message

                            if (
                                !bookingContainer.querySelector(
                                    '.booking-request'
                                )
                            ) {

                                bookingContainer.innerHTML = `
                            <div
                                class="text-center py-4"
                                id="no-provider-booking-requests"
                            >

                                <i
                                    class="fa-solid fa-calendar-check mb-2"
                                    style="font-size: 28px; opacity: 0.5;"
                                ></i>

                                <p class="mb-0">
                                    No pending booking requests.
                                </p>

                            </div>
                        `;

                            }


                            // Reload from server

                            setTimeout(
                                loadBookingRequests,
                                500
                            );

                        }

                    } catch (error) {

                        console.error(
                            'Booking action error:',
                            error
                        );


                        alert(
                            error.message ||
                            'Unable to process booking request.'
                        );


                        if (button) {
                            button.disabled = false;
                        }

                    }

                }
            );


            // ==========================================
            // UPDATE PENDING COUNT
            // ==========================================

            function updatePendingCount(count) {

                const statCards =
                    document.querySelectorAll(
                        '.provider-stat-card'
                    );


                statCards.forEach(card => {

                    const label =
                        card.querySelector('span');


                    if (
                        label &&
                        label.textContent.trim() ===
                        'Pending Requests'
                    ) {

                        const number =
                            card.querySelector('h2');


                        if (number) {
                            number.textContent = count;
                        }

                    }

                });

            }


            // ==========================================
            // SECURITY
            // ==========================================

            function escapeHTML(value) {

                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

            }


            function escapeAttribute(value) {

                return escapeHTML(value);

            }


            // ==========================================
            // FIRST LOAD
            // ==========================================

            loadBookingRequests();


            // ==========================================
            // AUTO CHECK EVERY 3 SECONDS
            // ==========================================

            setInterval(
                loadBookingRequests,
                3000
            );

        });

    </script>

    <!-- ==========================================
     PROVIDER TODAY'S JOBS AJAX
========================================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const todayJobsContainer =
                document.getElementById('providerTodayJobs');


            if (!todayJobsContainer) {
                return;
            }


            // ==========================================
            // LOAD TODAY'S JOBS
            // ==========================================

            async function loadTodayJobs() {

                try {

                    const response =
                        await fetch(
                            todayJobsContainer.dataset.todayJobsUrl,
                            {
                                method: 'GET',

                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                cache: 'no-store'
                            }
                        );


                    if (!response.ok) {
                        return;
                    }


                    const data =
                        await response.json();


                    if (!data.success) {
                        return;
                    }


                    

                    // ======================================
                    // NO JOBS
                    // ======================================

                    if (
                        !data.todayJobs ||
                        data.todayJobs.length === 0
                    ) {

                        todayJobsContainer.innerHTML = `
                    <div class="text-center py-4">

                        <i
                            class="fa-solid fa-calendar-check mb-2"
                            style="font-size: 28px; opacity: 0.5;"
                        ></i>

                        <p class="mb-0">
                            No jobs scheduled for today.
                        </p>

                    </div>
                `;

                        return;

                    }


                    // ======================================
                    // BUILD JOBS
                    // ======================================

                    todayJobsContainer.innerHTML = '';


                    data.todayJobs.forEach(function (job) {

                        const item =
                            document.createElement('div');


                        item.className =
                            'job-item';


                        // ----------------------------------
                        // SERVICES
                        // ----------------------------------

                        const services =
                            Array.isArray(job.services) &&
                                job.services.length > 0
                                ? job.services
                                    .map(service => escapeHTML(service))
                                    .join(', ')
                                : 'No service selected';


                        // ----------------------------------
                        // LOCATION
                        // ----------------------------------

                        let location =
                            escapeHTML(
                                job.city || 'N/A'
                            );


                        if (job.area) {

                            location +=
                                ' · ' +
                                escapeHTML(job.area);

                        }


                        // ----------------------------------
                        // CUSTOMER
                        // ----------------------------------

                        const customerName =
                            escapeHTML(
                                job.customer_name ||
                                'Customer'
                            );


                        // ----------------------------------
                        // STATUS CLASS
                        // ----------------------------------

                        let statusClass =
                            'pending';


                        if (
                            job.status === 'accepted' ||
                            job.status === 'in_progress'
                        ) {

                            statusClass =
                                'confirmed';

                        }


                        // ----------------------------------
                        // HTML
                        // ----------------------------------

                        item.innerHTML = `

                    <div class="job-time">

                        <strong>
                            ${escapeHTML(
                            job.scheduled_at ||
                            'Time N/A'
                        )}
                        </strong>

                        <span>
                            Today
                        </span>

                    </div>


                    <div class="job-icon blue">

                        <i
                            class="fa-solid fa-screwdriver-wrench"
                        ></i>

                    </div>


                    <div class="job-info">

                        <h4>
                            ${services}
                        </h4>

                        <p>
                            ${customerName}
                        </p>

                        <small>

                            <i
                                class="fa-solid fa-location-dot"
                            ></i>

                            ${location}

                        </small>

                    </div>


                    <span
                        class="job-status ${statusClass}"
                    >
                        ${formatProviderJobStatus(
                            job.status
                        )}
                    </span>


                    <a
                        href="${escapeAttribute(
                            job.show_url
                        )}"
                        class="provider-action-btn"
                        title="View Booking"
                    >

                        <i
                            class="fa-solid fa-eye"
                        ></i>

                    </a>

                `;


                        todayJobsContainer.appendChild(item);

                    });

                } catch (error) {

                    console.error(
                        'Today jobs polling error:',
                        error
                    );

                }

            }


            // ==========================================
            // FORMAT STATUS
            // ==========================================

            function formatProviderJobStatus(status) {

                return String(status || '')
                    .replace(/_/g, ' ')
                    .replace(
                        /\b\w/g,
                        function (letter) {
                            return letter.toUpperCase();
                        }
                    );

            }


            // ==========================================
            // SECURITY
            // ==========================================

            function escapeHTML(value) {

                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

            }


            function escapeAttribute(value) {

                return escapeHTML(value);

            }


            // ==========================================
            // FIRST LOAD
            // ==========================================

            loadTodayJobs();


            // ==========================================
            // AUTO CHECK EVERY 3 SECONDS
            // ==========================================

            setInterval(
                loadTodayJobs,
                3000
            );

        });

    </script>


    <!-- Provider JS -->

    <script src="{{ asset('js/provider.js') }}"></script>

</body>

</html>