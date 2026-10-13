<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Jobs - QuickFix</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- QuickFix Global CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >

    <!-- Provider CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('css/provider.css') }}"
    >

</head>

<body>

<div
    class="provider-layout"
    id="providerLayout"
>

    <!-- =========================================================
         SIDEBAR
    ========================================================= -->

    <aside class="provider-sidebar">

        <div class="provider-sidebar-header">

            <button
                type="button"
                class="provider-logo sidebar-logo-toggle"
                aria-label="Expand sidebar"
                title="Expand sidebar"
            >

                <span class="provider-logo-icon">
                    <i class="fa-solid fa-wrench"></i>
                </span>

                <span class="provider-logo-text">
                    QuickFix
                </span>

            </button>

            <button
                type="button"
                class="sidebar-toggle"
                aria-label="Collapse sidebar"
                title="Collapse sidebar"
            >
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>


        <!-- Provider Profile -->

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


        <!-- =====================================================
             SIDEBAR MENU
        ===================================================== -->

        <nav class="provider-sidebar-menu">

            <!-- Dashboard -->

            <a
                href="{{ route('provider.dashboard') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-house"></i>

                <span class="provider-menu-text">
                    Dashboard
                </span>

            </a>


            <!-- My Bookings -->

            <a
                href="{{ route('provider.bookings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-calendar-check"></i>

                <span class="provider-menu-text">
                    My Bookings
                </span>

            </a>


            <!-- My Jobs -->

            <a
                href="{{ route('provider.jobs') }}"
                class="provider-menu-link active"
            >

                <i class="fa-solid fa-screwdriver-wrench"></i>

                <span class="provider-menu-text">
                    My Jobs
                </span>

            </a>


            <!-- Earnings -->

            <a
                href="{{ route('provider.earnings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-wallet"></i>

                <span class="provider-menu-text">
                    Earnings
                </span>

            </a>


            <!-- Payments -->

            <a
                href="{{ route('provider.payments.index') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-credit-card"></i>

                <span class="provider-menu-text">
                    Payments
                </span>

            </a>


            <!-- Chats -->

            <a
                href="{{ route('provider.chats') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-comments"></i>

                <span class="provider-menu-text">
                    Chats
                </span>

                <span
                    class="provider-chat-badge"
                    id="providerSidebarChatUnread"
                    style="display: none;"
                >
                    0
                </span>

            </a>


            <!-- Reviews & Ratings -->

            <a
                href="{{ route('provider.reviews.index') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-star"></i>

                <span class="provider-menu-text">
                    Reviews & Ratings
                </span>

            </a>


            <!-- My Profile -->

            <a
                href="{{ route('provider.profile') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-user"></i>

                <span class="provider-menu-text">
                    My Profile
                </span>

            </a>

        </nav>


        <!-- Logout -->

        <div class="provider-sidebar-bottom">

            <a
                href="#"
                class="provider-menu-link provider-logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                <span class="provider-menu-text logout-text">
                    Logout
                </span>

            </a>

        </div>

    </aside>


    <!-- Mobile Overlay -->

    <div
        class="provider-overlay"
        id="providerOverlay"
    ></div>


    <!-- =========================================================
         MAIN
    ========================================================= -->

    <main class="provider-main">


        <!-- =====================================================
             HEADER
        ===================================================== -->

        <header class="provider-header">

            <div class="provider-header-left">

                <button
                    type="button"
                    class="provider-mobile-menu"
                    aria-label="Open menu"
                    title="Open menu"
                >
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>

                    <h1>
                        My Jobs
                    </h1>

                    <p>
                        Manage your current and completed service jobs.
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

                <!-- Online Status -->

                <div class="provider-status">

                    <span class="status-dot"></span>

                    <span class="status-text">
                        Online
                    </span>

                    <label class="status-switch">

                        <input
                            type="checkbox"
                            class="status-toggle"
                            checked
                        >

                        <span class="status-slider"></span>

                    </label>

                </div>


                <!-- Theme -->

                <button
                    type="button"
                    class="provider-theme-toggle"
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


        <!-- =====================================================
             CONTENT
        ===================================================== -->

        <div class="provider-content">
<!-- =================================================
     JOB SUMMARY
================================================= -->

<div class="provider-stats-grid">


    <!-- Total Jobs -->

    <div class="provider-stat-card">

        <div class="provider-stat-icon total">

            <i class="fa-solid fa-briefcase"></i>

        </div>

        <div>

            <span>
                Total Jobs
            </span>

            <h3>
                {{ $totalJobsCount }}
            </h3>

        </div>

    </div>


    <!-- Accepted -->

    <div class="provider-stat-card">

        <div class="provider-stat-icon accepted">

            <i class="fa-solid fa-circle-check"></i>

        </div>

        <div>

            <span>
                Accepted
            </span>

            <h3>
                {{ $acceptedJobsCount }}
            </h3>

        </div>

    </div>


    <!-- Ongoing -->

    <div class="provider-stat-card">

        <div class="provider-stat-icon ongoing">

            <i class="fa-solid fa-spinner"></i>

        </div>

        <div>

            <span>
                Ongoing
            </span>

            <h3>
                {{ $ongoingJobsCount }}
            </h3>

        </div>

    </div>


    <!-- Completed -->

    <div class="provider-stat-card">

        <div class="provider-stat-icon completed">

            <i class="fa-solid fa-flag-checkered"></i>

        </div>

        <div>

            <span>
                Completed
            </span>

            <h3>
                {{ $completedJobsCount }}
            </h3>

        </div>

    </div>

</div>

            <!-- =================================================
                 JOB LIST
            ================================================= -->

            <div class="provider-bookings-section">


                <!-- Section Heading -->

                <div class="provider-section-heading">

                    <div>

                        

                        <p>
                            Your latest assigned service jobs.
                        </p>

                    </div>


                    <!-- =================================================
                         FILTERS
                    ================================================= -->

                    <form
                        method="GET"
                        action="{{ route('provider.jobs') }}"
                        class="provider-jobs-filters"
                    >

                        <!-- Status Filter -->

                        <select
                            name="status"
                            class="provider-job-filter"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="all"
                                {{ $statusFilter === 'all' ? 'selected' : '' }}
                            >
                                All Jobs
                            </option>

                            <option
                                value="accepted"
                                {{ $statusFilter === 'accepted' ? 'selected' : '' }}
                            >
                                Accepted
                            </option>

                            <option
                                value="ongoing"
                                {{ $statusFilter === 'ongoing' ? 'selected' : '' }}
                            >
                                Ongoing
                            </option>

                        </select>


                        <!-- Service Area Filter -->

                        <select
                            name="area_id"
                            class="provider-job-filter"
                            onchange="this.form.submit()"
                        >

                            <option value="">
                                All Service Areas
                            </option>

                            @if (isset($areas))

                                @foreach ($areas as $area)

                                    <option
                                        value="{{ $area->id }}"
                                        {{ (string) request('area_id') === (string) $area->id ? 'selected' : '' }}
                                    >
                                        {{ $area->name }}

                                        @if ($area->city?->name)
                                            , {{ $area->city->name }}
                                        @endif

                                    </option>

                                @endforeach

                            @endif

                        </select>


                        <!-- Date Filter -->

                        <input
                            type="date"
                            name="date"
                            class="provider-job-filter"
                            value="{{ request('date') }}"
                            onchange="this.form.submit()"
                        >

                    </form>

                </div>


                {{-- =====================================================
                     REAL JOBS FROM DATABASE
                ===================================================== --}}

                @if ($recentJobs->count() > 0)

                    @foreach ($recentJobs as $booking)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | SERVICE NAME
                            |--------------------------------------------------------------------------
                            */

                            $serviceIds = collect(
                                $booking->chosen_services ?? []
                            )
                                ->flatten()
                                ->filter()
                                ->map(fn($id) => (int) $id)
                                ->unique()
                                ->values();


                            $services = \App\Models\Service::whereIn(
                                'id',
                                $serviceIds
                            )->get();


                            $serviceName = $services
                                ->pluck('name')
                                ->join(', ');


                            if (!$serviceName) {

                                $serviceName = 'Service Job';

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | STATUS
                            |--------------------------------------------------------------------------
                            */

                            $statusClass = match ($booking->status) {

                                'accepted'
                                    => 'accepted',

                                'on_the_way'
                                    => 'on-the-way',

                                'arrived'
                                    => 'arrived',

                                'in_progress'
                                    => 'in-progress',

                                default
                                    => 'unknown',

                            };


                            $statusText = match ($booking->status) {

                                'accepted'
                                    => 'Accepted',

                                'on_the_way'
                                    => 'On The Way',

                                'arrived'
                                    => 'Arrived',

                                'in_progress'
                                    => 'In Progress',

                                default
                                    => ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $booking->status
                                        )
                                    ),

                            };


                            $statusIcon = match ($booking->status) {

                                'accepted'
                                    => 'fa-circle-check',

                                'on_the_way'
                                    => 'fa-truck',

                                'arrived'
                                    => 'fa-location-dot',

                                'in_progress'
                                    => 'fa-spinner',

                                default
                                    => 'fa-circle-info',

                            };


                            /*
                            |--------------------------------------------------------------------------
                            | SERVICE ICON
                            |--------------------------------------------------------------------------
                            */

                            $serviceIcon = match (true) {

                                str_contains(
                                    strtolower($serviceName),
                                    'plumb'
                                )
                                    => 'fa-faucet-drip',

                                str_contains(
                                    strtolower($serviceName),
                                    'electric'
                                )
                                    => 'fa-bolt',

                                str_contains(
                                    strtolower($serviceName),
                                    'aircon'
                                ),

                                str_contains(
                                    strtolower($serviceName),
                                    'air con'
                                ),

                                str_contains(
                                    strtolower($serviceName),
                                    'air-condition'
                                )
                                    => 'fa-snowflake',

                                default
                                    => 'fa-screwdriver-wrench',

                            };

                        @endphp


                        <!-- =================================================
                             JOB CARD
                        ================================================= -->

                        <div class="provider-booking-card">

                            <div class="provider-booking-main">


                                <!-- Service Icon -->

                                <div class="provider-booking-icon">

                                    <i
                                        class="fa-solid {{ $serviceIcon }}"
                                    ></i>

                                </div>


                                <!-- Job Information -->

                                <div class="provider-booking-info">


                                    <!-- Title + Status -->

                                    <div class="provider-booking-title">

                                        <div>

                                            <h4>
                                                {{ $serviceName }}
                                            </h4>

                                            <!-- Booking ID -->

                                            <small class="booking-id">

                                                Booking #{{ $booking->id }}

                                            </small>

                                        </div>


                                        <!-- Status -->

                                        <span
                                            class="booking-status {{ $statusClass }}"
                                        >

                                            <i
                                                class="fa-solid {{ $statusIcon }}"
                                            ></i>

                                            {{ $statusText }}

                                        </span>

                                    </div>


                                    <!-- Customer -->

                                    <p>

                                        <i class="fa-solid fa-user"></i>

                                        {{ $booking->customer?->user?->name ?? 'Customer' }}

                                    </p>


                                    <!-- Service Area -->

                                    <p>

                                        <i class="fa-solid fa-location-dot"></i>

                                        {{ $booking->area?->name ?? 'Area N/A' }}

                                        @if ($booking->area?->city?->name)

                                            ,
                                            {{ $booking->area->city->name }}

                                        @endif

                                    </p>


                                    <!-- Scheduled Date / Time -->

                                    <p>

                                        <i class="fa-regular fa-calendar"></i>

                                        @if ($booking->scheduled_at)

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $booking->scheduled_at
                                                )->format(
                                                    'd M Y · h:i A'
                                                )
                                            }}

                                        @else

                                            Date / Time N/A

                                        @endif

                                    </p>

                                </div>

                            </div>


                            <!-- =================================================
                                 ACTIONS
                            ================================================= -->

                            <div class="provider-booking-actions">

                                <a
                                    href="{{ route('provider.bookings.show', $booking->id) }}"
                                    class="provider-booking-btn secondary"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                    View

                                </a>

                            </div>

                        </div>

                    @endforeach


                @else


                    <!-- =================================================
                         NO JOBS
                    ================================================= -->

                    <div class="provider-empty-state">

                        <i class="fa-solid fa-screwdriver-wrench"></i>


                        @if ($statusFilter === 'accepted')

                            <h4>
                                No accepted jobs this month
                            </h4>

                            <p>
                                You don't have any accepted jobs for
                                {{ now()->format('F Y') }}.
                            </p>


                        @elseif ($statusFilter === 'ongoing')

                            <h4>
                                No ongoing jobs this month
                            </h4>

                            <p>
                                You don't have any ongoing jobs for
                                {{ now()->format('F Y') }}.
                            </p>


                        @else

                            <h4>
                                No jobs this month
                            </h4>

                            <p>
                                You don't have any service jobs for
                                {{ now()->format('F Y') }}.
                            </p>

                        @endif

                    </div>

                @endif


            </div>

        </div>

    </main>

</div>


<!-- =========================================================
     BOOTSTRAP
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =========================================================
     PROVIDER JS
========================================================= -->

<script
    src="{{ asset('js/provider.js') }}"
></script>

</body>

</html>
