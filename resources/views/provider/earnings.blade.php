<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Earnings - QuickFix</title>


    {{-- Bootstrap --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Font Awesome --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- Global QuickFix CSS --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >


    {{-- Provider CSS --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/provider.css') }}"
    >


    {{-- Earnings Page CSS --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/provider-earnings.css') }}"
    >

</head>


<body>

<div
    class="provider-layout"
    id="providerLayout"
>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

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



        {{-- PROFILE --}}

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



        {{-- MENU --}}

<nav class="provider-sidebar-menu">


    {{-- Dashboard --}}

    <a
        href="{{ route('provider.dashboard') }}"
        class="provider-menu-link"
    >

        <i class="fa-solid fa-house"></i>

        <span class="provider-menu-text">
            Dashboard
        </span>

    </a>



    {{-- My Bookings --}}

    <a
        href="{{ route('provider.bookings') }}"
        class="provider-menu-link"
    >

        <i class="fa-solid fa-calendar-check"></i>

        <span class="provider-menu-text">
            My Bookings
        </span>

    </a>



    {{-- My Jobs --}}

    <a
        href="{{ route('provider.jobs') }}"
        class="provider-menu-link"
    >

        <i class="fa-solid fa-screwdriver-wrench"></i>

        <span class="provider-menu-text">
            My Jobs
        </span>

    </a>



    {{-- Earnings --}}

    <a
        href="{{ route('provider.earnings') }}"
        class="provider-menu-link active"
    >

        <i class="fa-solid fa-wallet"></i>

        <span class="provider-menu-text">
            Earnings
        </span>

    </a>



    {{-- Payments --}}

    <a
        href="{{ route('provider.payments.index') }}"
        class="provider-menu-link"
    >

        <i class="fa-solid fa-credit-card"></i>

        <span class="provider-menu-text">
            Payments
        </span>

    </a>



    {{-- Chats --}}

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



    {{-- Reviews & Ratings --}}

    <a
        href="{{ route('provider.reviews.index') }}"
        class="provider-menu-link"
    >

        <i class="fa-solid fa-star"></i>

        <span class="provider-menu-text">
            Reviews & Ratings
        </span>

    </a>



    {{-- My Profile --}}

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



        {{-- LOGOUT --}}

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



    {{-- MOBILE OVERLAY --}}

    <div
        class="provider-overlay"
        id="providerOverlay"
    ></div>



    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <main class="provider-main">


        {{-- HEADER --}}

        <header class="provider-header">


            <div class="provider-header-left">


                {{-- Mobile menu --}}

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
                        Earnings
                    </h1>

                    <p>
                        Track your income and payment history.
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


                {{-- ONLINE STATUS --}}

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



                {{-- THEME --}}

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



        {{-- =================================================
             CONTENT
        ================================================== --}}

        <div class="provider-content">


            {{-- =================================================
                 EARNING STATISTICS
            ================================================== --}}

            <div class="provider-stats-grid">


                {{-- TOTAL EARNINGS --}}

                <div class="provider-stat-card">

                    <div class="provider-stat-icon">

                        <i class="fa-solid fa-wallet"></i>

                    </div>


                    <div>

                        <span>
                            Total Earnings
                        </span>

                        <h3>
                            {{ number_format($totalEarnings) }} MMK
                        </h3>

                    </div>

                </div>



                {{-- THIS WEEK --}}

                <div class="provider-stat-card">

                    <div class="provider-stat-icon">

                        <i class="fa-solid fa-calendar-week"></i>

                    </div>


                    <div>

                        <span>
                            This Week
                        </span>

                        <h3>
                            {{ number_format($thisWeekEarnings) }} MMK
                        </h3>

                    </div>

                </div>



                {{-- THIS MONTH --}}

                <div class="provider-stat-card">

                    <div class="provider-stat-icon">

                        <i class="fa-solid fa-calendar"></i>

                    </div>


                    <div>

                        <span>
                            This Month
                        </span>

                        <h3>
                            {{ number_format($thisMonthEarnings) }} MMK
                        </h3>

                    </div>

                </div>



                {{-- COMPLETED JOBS --}}

                <div class="provider-stat-card">

                    <div class="provider-stat-icon">

                        <i class="fa-solid fa-briefcase"></i>

                    </div>


                    <div>

                        <span>
                            Completed Jobs
                        </span>

                        <h3>
                            {{ number_format($completedJobs) }}
                        </h3>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 PAYMENT HISTORY
            ================================================== --}}

            <div class="provider-bookings-section">


                {{-- SECTION HEADER --}}

                <div class="provider-section-heading">


                    <div>

                        <h3>
                            Payment History
                        </h3>

                        <p>
                            Verified payments from completed customer transactions.
                        </p>

                    </div>



                    {{-- FILTER FORM --}}

                    <form
                        method="GET"
                        action="{{ route('provider.earnings') }}"
                        class="earnings-filter-form"
                    >


                        {{-- SEARCH --}}

                        <div class="earnings-search-wrapper">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control earnings-search"
                                placeholder="Search booking or customer..."
                                value="{{ request('search') }}"
                            >

                        </div>



                        {{-- PERIOD --}}

                        <select
                            name="period"
                            class="form-select provider-job-filter"
                            onchange="this.form.submit()"
                        >

                            <option
                                value=""
                                {{ request('period') === null ? 'selected' : '' }}
                            >
                                All Time
                            </option>


                            <option
                                value="this_month"
                                {{ request('period') === 'this_month' ? 'selected' : '' }}
                            >
                                This Month
                            </option>


                            <option
                                value="last_month"
                                {{ request('period') === 'last_month' ? 'selected' : '' }}
                            >
                                Last Month
                            </option>


                            <option
                                value="this_year"
                                {{ request('period') === 'this_year' ? 'selected' : '' }}
                            >
                                This Year
                            </option>

                        </select>



                        {{-- SEARCH BUTTON --}}

                        <button
                            type="submit"
                            class="earnings-search-button"
                            title="Search"
                        >

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </button>

                    </form>

                </div>



                {{-- =================================================
                     PAYMENT TABLE
                ================================================== --}}

                <div class="earnings-table-wrapper">


                    @if($payments->count() > 0)


                        <table class="earnings-table">


                            <thead>

                                <tr>

                                    <th>
                                        Booking
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Service
                                    </th>

                                    <th>
                                        Verified Date
                                    </th>

                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Method
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Slip
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>



                            <tbody>


                                @foreach($payments as $payment)


                                    @php

                                        $booking = $payment->booking;


                                        $customerName =
                                            $booking?->customer?->user?->name
                                            ?? 'Unknown';


                                        $bookingId =
                                            'BK-' . str_pad(
                                                $booking->id,
                                                3,
                                                '0',
                                                STR_PAD_LEFT
                                            );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | Selected Services
                                        |--------------------------------------------------------------------------
                                        */

                                        $bookingServices = collect(
                                            $booking?->chosen_services ?? []
                                        )
                                            ->map(
                                                fn ($serviceId)
                                                    => $services->get($serviceId)
                                            )
                                            ->filter();

                                    @endphp



                                    <tr>


                                        {{-- BOOKING --}}

                                        <td>

                                            <span class="earning-booking-id">

                                                #{{ $bookingId }}

                                            </span>

                                        </td>



                                        {{-- CUSTOMER --}}

                                        <td>

                                            <span class="earning-customer">

                                                {{ $customerName }}

                                            </span>

                                        </td>



                                        {{-- SERVICE --}}

                                        <td>

                                            @forelse($bookingServices as $service)

                                                <div class="earning-service">

                                                    <i class="fa-solid fa-wrench"></i>

                                                    <span>
                                                        {{ $service->name }}
                                                    </span>

                                                </div>

                                            @empty

                                                <span class="earning-service-empty">
                                                    Service
                                                </span>

                                            @endforelse

                                        </td>



                                        {{-- VERIFIED DATE --}}

                                        <td>

                                            @if($payment->verified_at)

                                                <div class="earning-date">

                                                    <strong>
                                                        {{ $payment->verified_at->format('d M Y') }}
                                                    </strong>

                                                    <small>
                                                        {{ $payment->verified_at->format('h:i A') }}
                                                    </small>

                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>



                                        {{-- AMOUNT --}}

                                        <td>

                                            <span class="earning-amount">

                                                +
                                                {{ number_format($payment->service_amount) }}
                                                MMK

                                            </span>

                                        </td>



                                        {{-- PAYMENT METHOD --}}

                                        <td>

                                            @if($payment->payment_method === 'kbzpay')

                                                <span class="payment-method-badge payment-method-kbzpay">

                                                    <i class="fa-solid fa-mobile-screen-button"></i>

                                                    KBZPay

                                                </span>

                                            @else

                                                <span class="payment-method-badge payment-method-cod">

                                                    <i class="fa-solid fa-money-bill-wave"></i>

                                                    COD

                                                </span>

                                            @endif

                                        </td>



                                        {{-- STATUS --}}

                                        <td>

                                            <span class="earning-status">

                                                <i class="fa-solid fa-circle-check"></i>

                                                Verified

                                            </span>

                                        </td>



                                        {{-- SLIP --}}

                                        <td>

                                            @if($payment->slip_image)

                                                <a
                                                    href="{{ asset('storage/' . $payment->slip_image) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="slip-button"
                                                    title="View payment slip"
                                                >

                                                    <i class="fa-solid fa-eye"></i>

                                                </a>

                                            @else

                                                <span class="slip-empty">
                                                    —
                                                </span>

                                            @endif

                                        </td>



                                        {{-- ACTION --}}

                                        <td>

                                            <a
                                                href="{{ route('provider.payments.show', $payment->id) }}"
                                                class="earning-view-button"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                                View

                                            </a>

                                        </td>

                                    </tr>


                                @endforeach

                            </tbody>

                        </table>


                    @else


                        {{-- EMPTY STATE --}}

                        <div class="earnings-empty">

                            <div class="earnings-empty-icon">

                                <i class="fa-solid fa-wallet"></i>

                            </div>


                            <h5>
                                No payment history found
                            </h5>


                            <p>
                                Verified payments will appear here.
                            </p>

                        </div>


                    @endif

                </div>



                {{-- =================================================
                     PAGINATION
                ================================================== --}}

                @if($payments->hasPages())

                    <div class="earnings-pagination">

                        {{ $payments->links() }}

                    </div>

                @endif

            </div>

        </div>

    </main>

</div>



{{-- =====================================================
     BOOTSTRAP JS
====================================================== --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>



{{-- =====================================================
     EXISTING PROVIDER JS
====================================================== --}}

<script
    src="{{ asset('js/provider.js') }}"
></script>



{{-- =====================================================
     EARNINGS PAGE JS
====================================================== --}}

<script
    src="{{ asset('js/provider-earnings.js') }}"
></script>

</body>

</html>