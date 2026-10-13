<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>QuickFix - Payments</title>


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
         QUICKFIX CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/customer.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/customer-payment.css') }}"
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


        <!-- Sidebar Header -->

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

        <!-- Sidebar Menu -->

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
        class="customer-menu-link active"
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


        <!-- Logout -->

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
        Payments
    </h1>

    <p>
        Manage your booking payments.
    </p>

</div>

            </div>


            <div class="customer-header-actions">

    <!-- Notifications -->
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
                $unreadNotificationCount = auth()->user()
                    ->unreadNotifications()
                    ->count();
            @endphp

            @if($unreadNotificationCount > 0)

                <span
                    class="customer-notification-badge"
                    id="notificationBadge"
                >
                    {{ $unreadNotificationCount > 99
                        ? '99+'
                        : $unreadNotificationCount }}
                </span>

            @endif

        </button>


        <!-- Notification Dropdown -->
        <div
            class="customer-notification-dropdown"
            id="notificationDropdown"
        >

            <!-- Dropdown Header -->
            <div class="customer-notification-header">

                <div>

                    <h6>
                        Notifications
                    </h6>

                    <span id="notificationUnreadText">
                        {{ $unreadNotificationCount }} unread
                    </span>

                </div>

                <a
                    href="{{ route('customer.notifications') }}"
                    class="customer-view-all-btn"
                >
                    View All
                </a>

            </div>


            <!-- Notification List -->
            <div
                class="customer-notification-list"
                id="customerNotificationList"
            >

                @forelse(
                    auth()->user()
                        ->notifications()
                        ->latest()
                        ->take(10)
                        ->get()
                    as $notification
                )

                    @php

                        $data = $notification->data;

                        $title =
                            $data['title']
                            ?? 'Notification';

                        $message =
                            $data['message']
                            ?? '';

                        $bookingId =
                            $data['booking_id']
                            ?? null;

                        $isUnread =
                            is_null($notification->read_at);

                    @endphp


                    <div
                        class="customer-notification-item
                            {{ $isUnread ? 'unread' : '' }}"
                        data-id="{{ $notification->id }}"
                    >

                        <!-- Icon -->
                        <div class="customer-notification-item-icon">

                            <i class="fa-solid fa-bell"></i>

                        </div>


                        <!-- Content -->
                        <div class="customer-notification-item-content">

                            @if($bookingId)

                                <a
                                    href="{{ route(
                                        'customer.booking.detail',
                                        $bookingId
                                    ) }}"
                                    class="customer-notification-link"
                                    data-notification-id="{{ $notification->id }}"
                                >

                                    <strong>
                                        {{ $title }}
                                    </strong>

                                    <p>
                                        {{ $message }}
                                    </p>

                                </a>

                            @else

                                <strong>
                                    {{ $title }}
                                </strong>

                                <p>
                                    {{ $message }}
                                </p>

                            @endif


                            <small>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>


                            @if($isUnread)

                                <button
                                    type="button"
                                    class="customer-notification-read-btn"
                                    data-id="{{ $notification->id }}"
                                >
                                    Read
                                </button>

                            @else

                                <span
                                    class="customer-notification-read-label"
                                >
                                    Read
                                </span>

                            @endif

                        </div>


                        <!-- Unread Dot -->
                        @if($isUnread)

                            <span
                                class="customer-notification-unread-dot"
                            ></span>

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


            <!-- Footer -->
            <div class="customer-notification-footer">

                <a
                    href="{{ route('customer.notifications') }}"
                >
                    View all notifications
                </a>

            </div>

        </div>

    </div>


    <!-- Dark / Light Mode -->
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
             CONTENT
        ================================================== -->

        <section class="customer-payment-content">


            <!-- Flash Message -->

            @if(session('success'))

                <div class="payment-alert payment-alert-success">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            @if($errors->any())

                <div class="payment-alert payment-alert-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <div>

                        @foreach($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                </div>

            @endif


            <!-- =================================================
                 SUMMARY
            ================================================== -->

            <div class="payment-summary-grid">


                <div class="payment-summary-card">

                    <div class="payment-summary-icon">

                        <i class="fa-solid fa-receipt"></i>

                    </div>

                    <div>

                        <span>
                            Total Bookings
                        </span>

                        <strong>
                            {{ $bookings->total() }}
                        </strong>

                    </div>

                </div>


                <div class="payment-summary-card">

                    <div class="payment-summary-icon pending">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <span>
                            Pending
                        </span>

                        <strong>
                            {{ $bookings->getCollection()->where('payment.payment_status', 'pending')->count() }}
                        </strong>

                    </div>

                </div>


                <div class="payment-summary-card">

                    <div class="payment-summary-icon verified">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <span>
                            Verified
                        </span>

                        <strong>
                            {{ $bookings->getCollection()->where('payment.payment_status', 'verified')->count() }}
                        </strong>

                    </div>

                </div>


                <div class="payment-summary-card">

                    <div class="payment-summary-icon unpaid">

                        <i class="fa-solid fa-wallet"></i>

                    </div>

                    <div>

                        <span>
                            Unpaid
                        </span>

                        <strong>
                            {{ $bookings->getCollection()->whereNull('payment')->count() }}
                        </strong>

                    </div>

                </div>


            </div>


            <!-- =================================================
                 PAYMENT TABLE CARD
            ================================================== -->

            <div class="payment-card">


                <div class="payment-card-header">

                    <div>

                        <h2>
                            Payment History
                        </h2>

                        <p>
                            View and manage payments for your bookings.
                        </p>

                    </div>


                    <div class="payment-header-icon">

                        <i class="fa-solid fa-credit-card"></i>

                    </div>

                </div>


                @if($bookings->count())


                    <!-- Desktop Table -->

                    <div class="payments-table-wrapper">

                        <table class="payments-table">

                            <thead>

                                <tr>

                                    <th>
                                        Booking
                                    </th>

                                    <th>
                                        Service
                                    </th>

                                    <th>
                                        Provider
                                    </th>

                                    <th>
                                        Date
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

                                @foreach($bookings as $booking)

                                    @php
                                        $payment = $booking->payment;
                                    @endphp

                                    <tr>


                                        <!-- Booking -->

                                        <td>

                                            <div class="payment-booking">

                                                <strong>
                                                    #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                                                </strong>

                                                <span>
                                                    Booking
                                                </span>

                                            </div>

                                        </td>


                                        <!-- Service -->

                                        <td>

                                            <div class="payment-service">

                                                <i class="fa-solid fa-screwdriver-wrench"></i>

                                                <span>
                                                    {{ $booking->service_name ?? 'Service Booking' }}
                                                </span>

                                            </div>

                                        </td>


                                        <!-- Provider -->

                                        <td>

                                            {{ $booking->provider?->user?->name ?? 'Not Assigned' }}

                                        </td>


                                        <!-- Date -->

                                        <td>

                                            <div class="payment-date">

                                                <strong>
                                                    {{ $booking->scheduled_at?->format('d M Y') ?? '—' }}
                                                </strong>

                                                <span>
                                                    {{ $booking->scheduled_at?->format('h:i A') ?? '' }}
                                                </span>

                                            </div>

                                        </td>


                                        <!-- Amount -->

                                        <td>

                                            <strong class="payment-amount">

                                                {{ number_format((float) $booking->total_amount, 2) }}

                                            </strong>

                                        </td>


                                        <!-- Method -->

                                        <td>

                                            @if($payment)

                                                <span class="payment-method-badge">

                                                    @if($payment->payment_method === 'kbzpay')

                                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                                        KBZPay

                                                    @else

                                                        <i class="fa-solid fa-money-bill"></i>
                                                        COD

                                                    @endif

                                                </span>

                                            @else

                                                <span class="payment-muted">
                                                    Not Selected
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Status -->

                                        <td>

                                            @if(!$payment)

                                                <span class="payment-status-badge unpaid">

                                                    <i class="fa-solid fa-circle-exclamation"></i>

                                                    Unpaid

                                                </span>

                                            @elseif($payment->payment_status === 'verified')

                                                <span class="payment-status-badge verified">

                                                    <i class="fa-solid fa-circle-check"></i>

                                                    Verified

                                                </span>

                                            @elseif($payment->payment_status === 'rejected')

                                                <span class="payment-status-badge rejected">

                                                    <i class="fa-solid fa-circle-xmark"></i>

                                                    Rejected

                                                </span>

                                            @else

                                                <span class="payment-status-badge pending">

                                                    <i class="fa-solid fa-clock"></i>

                                                    Pending

                                                </span>

                                            @endif

                                        </td>


                                        <!-- Slip -->

                                        <td>

                                            @if($payment && $payment->slip_image)

                                                <button
                                                    type="button"
                                                    class="payment-slip-button"
                                                    data-slip-url="{{ asset('storage/' . $payment->slip_image) }}"
                                                    data-booking-id="{{ $booking->id }}"
                                                >

                                                    <img
                                                        src="{{ asset('storage/' . $payment->slip_image) }}"
                                                        alt="Payment Slip"
                                                        class="payment-slip-thumbnail"
                                                    >

                                                    <span>
                                                        View Slip
                                                    </span>

                                                </button>

                                            @else

                                                <span class="payment-no-slip">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <!-- Action -->

                                        <td>

                                            <a
                                                href="{{ route('customer.payment.show', $booking->id) }}"
                                                class="payment-view-button"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                                View

                                            </a>

                                        </td>


                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    <!-- =================================================
                         MOBILE PAYMENT CARDS
                    ================================================== -->

                    <div class="mobile-payment-list">

                        @foreach($bookings as $booking)

                            @php
                                $payment = $booking->payment;
                            @endphp

                            <article class="mobile-payment-card">


                                <div class="mobile-payment-top">

                                    <div>

                                        <span class="mobile-payment-label">
                                            Booking
                                        </span>

                                        <strong>
                                            #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                                        </strong>

                                    </div>


                                    @if(!$payment)

                                        <span class="payment-status-badge unpaid">
                                            Unpaid
                                        </span>

                                    @elseif($payment->payment_status === 'verified')

                                        <span class="payment-status-badge verified">
                                            Verified
                                        </span>

                                    @elseif($payment->payment_status === 'rejected')

                                        <span class="payment-status-badge rejected">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="payment-status-badge pending">
                                            Pending
                                        </span>

                                    @endif

                                </div>


                                <div class="mobile-payment-details">


                                    <div>

                                        <span>
                                            Service
                                        </span>

                                        <strong>
                                            {{ $booking->service_name ?? 'Service Booking' }}
                                        </strong>

                                    </div>


                                    <div>

                                        <span>
                                            Provider
                                        </span>

                                        <strong>
                                            {{ $booking->provider?->user?->name ?? 'Not Assigned' }}
                                        </strong>

                                    </div>


                                    <div>

                                        <span>
                                            Date
                                        </span>

                                        <strong>
                                            {{ $booking->scheduled_at?->format('d M Y, h:i A') ?? '—' }}
                                        </strong>

                                    </div>


                                    <div>

                                        <span>
                                            Amount
                                        </span>

                                        <strong class="mobile-payment-amount">
                                            {{ number_format((float) $booking->total_amount, 2) }}
                                        </strong>

                                    </div>


                                    <div>

                                        <span>
                                            Payment Method
                                        </span>

                                        <strong>

                                            @if($payment)

                                                {{ strtoupper($payment->payment_method) }}

                                            @else

                                                Not Selected

                                            @endif

                                        </strong>

                                    </div>


                                </div>


                                @if($payment && $payment->slip_image)

                                    <div class="mobile-slip-section">

                                        <span>
                                            Payment Slip
                                        </span>

                                        <button
                                            type="button"
                                            class="payment-slip-button"
                                            data-slip-url="{{ asset('storage/' . $payment->slip_image) }}"
                                            data-booking-id="{{ $booking->id }}"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $payment->slip_image) }}"
                                                alt="Payment Slip"
                                                class="payment-slip-thumbnail"
                                            >

                                            <span>
                                                View Slip
                                            </span>

                                        </button>

                                    </div>

                                @endif


                                <div class="mobile-payment-actions">

                                    <a
                                        href="{{ route('customer.payment.show', $booking->id) }}"
                                        class="payment-view-button"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                        View Payment

                                    </a>

                                </div>


                            </article>

                        @endforeach

                    </div>


                    <!-- Pagination -->

                    @if($bookings->hasPages())

                        <div class="payment-pagination">

                            {{ $bookings->links() }}

                        </div>

                    @endif


                @else


                    <!-- Empty State -->

                    <div class="payment-empty-state">

                        <div class="payment-empty-icon">

                            <i class="fa-solid fa-credit-card"></i>

                        </div>

                        <h3>
                            No Payment Records
                        </h3>

                        <p>
                            You do not have any booking payment records yet.
                        </p>

                        <a
                            href="{{ route('customer.services') }}"
                            class="payment-primary-button"
                        >

                            <i class="fa-solid fa-plus"></i>

                            Browse Services

                        </a>

                    </div>

                @endif


            </div>


        </section>


    </main>


</div>


<!-- =========================================================
     PAYMENT SLIP MODAL
========================================================== -->

<div
    class="modal fade"
    id="paymentSlipModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content payment-slip-modal">


            <div class="modal-header">

                <div>

                    <h5 class="modal-title">

                        <i class="fa-solid fa-image"></i>

                        Payment Slip

                    </h5>

                    <span id="paymentSlipBookingNumber">
                        Payment Slip
                    </span>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">


                <div
                    class="payment-slip-loading"
                    id="paymentSlipLoading"
                >

                    <i class="fa-solid fa-spinner fa-spin"></i>

                    Loading image...

                </div>


                <div
                    class="payment-slip-modal-preview"
                    id="paymentSlipPreview"
                >

                    <img
                        id="paymentSlipImage"
                        src=""
                        alt="Payment Slip"
                    >

                </div>


                <div
                    class="payment-slip-error"
                    id="paymentSlipError"
                >

                    <i class="fa-solid fa-circle-exclamation"></i>

                    Unable to load payment slip.

                </div>

            </div>


            <div class="modal-footer">

                <a
                    href="#"
                    target="_blank"
                    class="payment-slip-open-button"
                    id="paymentSlipOpenNew"
                >

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    Open Full Image

                </a>


                <button
                    type="button"
                    class="payment-modal-close-button"
                    data-bs-dismiss="modal"
                >

                    Close

                </button>

            </div>


        </div>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script src="{{ asset('js/quickfix.js') }}"></script>

<script src="{{ asset('js/customer.js') }}"></script>

<script src="{{ asset('js/customer-payment.js') }}"></script>


</body>

</html>