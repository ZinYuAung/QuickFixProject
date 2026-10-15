<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Payments - QuickFix</title>

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

    {{-- Global CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >

    {{-- Provider CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/provider.css') }}"
    >

    {{-- Payment CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/provider-payments.css') }}"
    >
<link rel="stylesheet" href="{{ asset('css/app-alert.css') }}">
</head>


<body>

<div
    class="provider-layout"
    id="providerLayout"
>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

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
            @if(auth()->user()?->profile_image)

            <img
                src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                alt="{{ auth()->user()->name ?? 'Provider' }}"
            >

        @else

            <i class="fa-solid fa-user"></i>

        @endif
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
            class="provider-menu-link"
        >

            <i class="fa-solid fa-wallet"></i>

            <span class="provider-menu-text">
                Earnings
            </span>

        </a>


        {{-- Payments --}}

        <a
            href="{{ route('provider.payments.index') }}"
            class="provider-menu-link active"
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
                        Payments
                    </h1>

                    <p>
                        Review and manage customer payments.
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


        {{-- =====================================================
             CONTENT
        ====================================================== --}}

        <div class="provider-content">


            {{-- FLASH MESSAGES --}}

            @if(session('success'))

                <div class="payment-alert payment-alert-success">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                    <button
                        type="button"
                        class="payment-alert-close"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            @if(session('error'))

                <div class="payment-alert payment-alert-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        {{ session('error') }}
                    </span>

                    <button
                        type="button"
                        class="payment-alert-close"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            @if(session('info'))

                <div class="payment-alert payment-alert-info">

                    <i class="fa-solid fa-circle-info"></i>

                    <span>
                        {{ session('info') }}
                    </span>

                    <button
                        type="button"
                        class="payment-alert-close"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            {{-- =================================================
                 STATISTICS
            ================================================== --}}

            <div class="payment-stats-grid">


                {{-- TOTAL --}}

                <div class="payment-stat-card">

                    <div class="payment-stat-icon">

                        <i class="fa-solid fa-credit-card"></i>

                    </div>

                    <div>

                        <span>
                            Total Payments
                        </span>

                        <h3>
                            {{ number_format($totalPayments) }}
                        </h3>

                    </div>

                </div>


                {{-- PENDING --}}

                <div class="payment-stat-card payment-stat-pending">

                    <div class="payment-stat-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <span>
                            Pending
                        </span>

                        <h3>
                            {{ number_format($pendingPayments) }}
                        </h3>

                    </div>

                </div>


                {{-- APPROVED --}}

                <div class="payment-stat-card payment-stat-approved">

                    <div class="payment-stat-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <span>
                            Approved
                        </span>

                        <h3>
                            {{ number_format($verifiedPayments) }}
                        </h3>

                    </div>

                </div>


                {{-- REJECTED --}}

                <div class="payment-stat-card payment-stat-rejected">

                    <div class="payment-stat-icon">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div>

                        <span>
                            Rejected
                        </span>

                        <h3>
                            {{ number_format($rejectedPayments) }}
                        </h3>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PAYMENT SECTION
            ================================================== --}}

            <section class="provider-payments-section">


                {{-- SECTION HEADER --}}

                <div class="payment-section-header">

                    <div>

                        <h3>
                            Payment History
                        </h3>

                        <p>
                            View, approve, and manage customer payment submissions.
                        </p>

                    </div>


                    {{-- FILTER FORM --}}

                    <form
                        method="GET"
                        action="{{ route('provider.payments.index') }}"
                        class="payment-filter-form"
                    >

                        {{-- SEARCH --}}

                        <div class="payment-search-wrapper">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control payment-search"
                                placeholder="Search booking or customer..."
                                value="{{ request('search') }}"
                            >

                        </div>


                        {{-- STATUS --}}

                        <select
                            name="status"
                            class="form-select payment-filter"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="all"
                                {{ request('status', 'all') === 'all' ? 'selected' : '' }}
                            >
                                All Status
                            </option>

                            <option
                                value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="verified"
                                {{ request('status') === 'verified' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="rejected"
                                {{ request('status') === 'rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                        </select>


                        {{-- METHOD --}}

                        <select
                            name="method"
                            class="form-select payment-filter"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="all"
                                {{ request('method', 'all') === 'all' ? 'selected' : '' }}
                            >
                                All Methods
                            </option>

                            <option
                                value="cod"
                                {{ request('method') === 'cod' ? 'selected' : '' }}
                            >
                                COD
                            </option>

                            <option
                                value="kbzpay"
                                {{ request('method') === 'kbzpay' ? 'selected' : '' }}
                            >
                                KBZPay
                            </option>

                        </select>


                       


                        {{-- SEARCH BUTTON --}}

                        <button
                            type="submit"
                            class="payment-search-button"
                            title="Search"
                        >

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </button>

                    </form>

                </div>


                {{-- =================================================
                     TABLE
                ================================================== --}}

                <div class="payment-table-wrapper">

                    @if($payments->count() > 0)

                        <table class="payment-table">

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
                                        Prepaid Amount
                                    </th>

                                    <th>
                                        Service Fee
                                    </th>

                                    <th>
                                        Total Amount
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
                                        ?? 'Unknown Customer';

                                    $bookingId = $booking
                                        ? 'BK-' . str_pad(
                                            $booking->id,
                                            3,
                                            '0',
                                            STR_PAD_LEFT
                                        )
                                        : 'N/A';

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

                                        <a
                                            href="{{ route('provider.payments.show', $payment->id) }}"
                                            class="payment-booking-link"
                                        >
                                            #{{ $bookingId }}
                                        </a>

                                    </td>


                                    {{-- CUSTOMER --}}

                                    <td>

                                        <div class="payment-customer">

                                            <div class="payment-customer-avatar">

                                                <i class="fa-solid fa-user"></i>

                                            </div>

                                            <span>
                                                {{ $customerName }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- SERVICES --}}

                                    <td>

                                        <div class="payment-services">

                                            @forelse($bookingServices as $service)

                                                <span class="payment-service-item">

                                                    <i class="fa-solid fa-wrench"></i>

                                                    {{ $service->name }}

                                                </span>

                                            @empty

                                                <span class="payment-service-empty">
                                                    No service
                                                </span>

                                            @endforelse

                                        </div>

                                    </td>


                                    {{-- PREPAID --}}

                                    <td>

                                        <span class="payment-money payment-prepaid">

                                            {{ number_format($payment->prepaid_amount ?? 0) }}
                                            MMK

                                        </span>

                                    </td>


                                    {{-- SERVICE FEE --}}

                                    <td>

                                        <span class="payment-money">

                                            {{ number_format($payment->service_amount ?? 0) }}
                                            MMK

                                        </span>

                                    </td>


                                    {{-- TOTAL --}}

                                    <td>

                                        <strong class="payment-total">

                                            {{ number_format($booking?->total_amount ?? 0) }}
                                            MMK

                                        </strong>

                                    </td>


                                    {{-- METHOD --}}

                                    <td>

                                        @if($payment->payment_method === 'kbzpay')

                                            <span class="payment-method payment-method-kbzpay">

                                                <i class="fa-solid fa-mobile-screen-button"></i>

                                                KBZPay

                                            </span>

                                        @else

                                            <span class="payment-method payment-method-cod">

                                                <i class="fa-solid fa-money-bill-wave"></i>

                                                COD

                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}

                                    <td>

                                        @if($payment->payment_status === 'pending')

                                            <span class="payment-status payment-status-pending">

                                                <i class="fa-solid fa-clock"></i>

                                                Pending

                                            </span>

                                        @elseif($payment->payment_status === 'verified')

                                            <span class="payment-status payment-status-approved">

                                                <i class="fa-solid fa-circle-check"></i>

                                                Approved

                                            </span>

                                        @else

                                            <span class="payment-status payment-status-rejected">

                                                <i class="fa-solid fa-circle-xmark"></i>

                                                Rejected

                                            </span>

                                        @endif

                                    </td>


                                    {{-- SLIP --}}

                                    <td>

                                        @if($payment->slip_image)

                                            <a
                                                href="{{ asset('storage/' . $payment->slip_image) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="payment-slip-button"
                                                title="View payment slip"
                                            >

                                                <i class="fa-solid fa-image"></i>

                                            </a>

                                        @else

                                            <span class="payment-no-slip">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}

                                    <td>

                                        <div class="payment-actions">

                                            <a
                                                href="{{ route('provider.payments.show', $payment->id) }}"
                                                class="payment-action-button payment-view-button"
                                                title="View payment"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                                <span>
                                                    View
                                                </span>

                                            </a>


                                            @if($payment->payment_status !== 'verified')

                                                <form
                                                    method="POST"
                                                    action="{{ route('provider.payments.verify', $payment->id) }}"
                                                    class="payment-inline-form payment-approve-form"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="payment-action-button payment-approve-button"
                                                        title="Approve payment"
                                                    >

                                                        <i class="fa-solid fa-check"></i>

                                                        <span>
                                                            Approve
                                                        </span>

                                                    </button>

                                                </form>

                                            @endif


                                            @if($payment->payment_status !== 'verified')

                                                <button
                                                    type="button"
                                                    class="payment-action-button payment-reject-button payment-open-reject"
                                                    data-payment-id="{{ $payment->id }}"
                                                    data-payment-name="#{{ $bookingId }}"
                                                    title="Reject payment"
                                                >

                                                    <i class="fa-solid fa-xmark"></i>

                                                    <span>
                                                        Reject
                                                    </span>

                                                </button>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    @else

                        {{-- EMPTY STATE --}}

                        <div class="payment-empty">

                            <div class="payment-empty-icon">

                      
                            <i class="fa-solid fa-credit-card"></i>

                            </div>

                            <h5>
                                No payment history found
                            </h5>

                            <p>
                                Customer payment submissions will appear here.
                            </p>

                        </div>

                    @endif

                </div>


                {{-- PAGINATION --}}

                @if($payments->hasPages())

                    <div class="payment-pagination">

                        {{ $payments->links() }}

                    </div>

                @endif

            </section>

        </div>

    </main>

</div>


{{-- =====================================================
     REJECT MODAL
====================================================== --}}

<div
    class="modal fade"
    id="rejectPaymentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content payment-reject-modal">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">
                        Reject Payment
                    </h5>

                    <p class="modal-subtitle">
                        <span id="rejectPaymentName">
                            Payment
                        </span>
                    </p>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <form
                method="POST"
                id="rejectPaymentForm"
            >

                @csrf

                <div class="modal-body">

                    <label
                        for="rejection_reason"
                        class="form-label"
                    >
                        Rejection Reason
                        <span>*</span>
                    </label>

                    <textarea
                        name="rejection_reason"
                        id="rejection_reason"
                        class="form-control"
                        rows="5"
                        maxlength="1000"
                        placeholder="Please explain why this payment is being rejected..."
                        required
                    ></textarea>

                    <div class="payment-character-count">

                        <span id="rejectionCharacterCount">
                            0
                        </span>

                        / 1000

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn payment-cancel-button"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="btn payment-confirm-reject-button"
                        id="confirmRejectButton"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Reject Payment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Bootstrap --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


{{-- Provider JS --}}

<script
    src="{{ asset('js/provider.js') }}"
></script>


{{-- Payment JS --}}
<script src="{{ asset('js/app-alert.js') }}"></script>
<script
    src="{{ asset('js/provider-payments.js') }}"
></script>

</body>

</html>