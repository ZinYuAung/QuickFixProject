<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Payment #{{ $payment->id }} - QuickFix
    </title>


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


    {{-- Payment Detail CSS --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/provider-payment-show.css') }}"
    >

</head>


<body>
    @php
    $prepaymentPending =
        $payment->payment_status === 'pending';

    $prepaymentVerified =
        $payment->payment_status === 'verified';

    $prepaymentRejected =
        $payment->payment_status === 'rejected';

    $finalPaymentPending =
        $payment->final_payment_status === 'pending'
        && !empty($payment->final_slip_image);

    $finalPaymentVerified =
        $payment->final_payment_status === 'verified';

    $finalPaymentRejected =
        $payment->final_payment_status === 'rejected';
@endphp

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


            <a
                href="{{ route('provider.dashboard') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-house"></i>

                <span class="provider-menu-text">
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('provider.bookings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-calendar-check"></i>

                <span class="provider-menu-text">
                    My Bookings
                </span>

            </a>


            <a
                href="{{ route('provider.jobs') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-screwdriver-wrench"></i>

                <span class="provider-menu-text">
                    My Jobs
                </span>

            </a>


            <a
                href="{{ route('provider.earnings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-wallet"></i>

                <span class="provider-menu-text">
                    Earnings
                </span>

            </a>


            <a
                href="{{ route('provider.payments.index') }}"
                class="provider-menu-link active"
            >

                <i class="fa-solid fa-credit-card"></i>

                <span class="provider-menu-text">
                    Payments
                </span>

            </a>


            <a
                href="{{ route('provider.chats') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-comments"></i>

                <span class="provider-menu-text">
                    Chats
                </span>

                <span class="provider-chat-badge">
                    3
                </span>

            </a>


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
                        Payment Details
                    </h1>

                    <p>
                        Review customer payment information.
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


        {{-- CONTENT --}}

        <div class="provider-content">


            {{-- FLASH --}}

            @if(session('success'))

                <div class="payment-detail-alert payment-detail-success">

                    <i class="fa-solid fa-circle-check"></i>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div class="payment-detail-alert payment-detail-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    {{ session('error') }}

                </div>

            @endif


            @if(session('info'))

                <div class="payment-detail-alert payment-detail-info">

                    <i class="fa-solid fa-circle-info"></i>

                    {{ session('info') }}

                </div>

            @endif


            {{-- =================================================
                 TOP BAR
            ================================================== --}}

            <div class="payment-detail-topbar">

                <a
                    href="{{ route('provider.payments.index') }}"
                    class="payment-back-button"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Back to Payments

                </a>


                <div class="payment-detail-status-area">

                   @if($finalPaymentPending)

    <span class="payment-detail-status payment-detail-pending">

        <i class="fa-solid fa-clock"></i>

        Final Payment Pending

    </span>

@elseif($finalPaymentRejected)

    <span class="payment-detail-status payment-detail-rejected">

        <i class="fa-solid fa-circle-xmark"></i>

        Final Payment Rejected

    </span>

@elseif($finalPaymentVerified)

    <span class="payment-detail-status payment-detail-approved">

        <i class="fa-solid fa-circle-check"></i>

        Fully Paid

    </span>

@elseif($prepaymentPending)

    <span class="payment-detail-status payment-detail-pending">

        <i class="fa-solid fa-clock"></i>

        Prepayment Pending

    </span>

@elseif($prepaymentRejected)

    <span class="payment-detail-status payment-detail-rejected">

        <i class="fa-solid fa-circle-xmark"></i>

        Prepayment Rejected

    </span>

@else

    <span class="payment-detail-status payment-detail-approved">

        <i class="fa-solid fa-circle-check"></i>

        Prepayment Approved

    </span>

@endif

                </div>

            </div>


            {{-- =================================================
                 PAYMENT SUMMARY
            ================================================== --}}

            <div class="payment-detail-grid">


                {{-- PAYMENT INFORMATION --}}

                <section class="payment-detail-card">

                    <div class="payment-detail-card-header">

                        <div>

                            <h3>
                                Payment Information
                            </h3>

                            <p>
                                Transaction details
                            </p>

                        </div>

                        <div class="payment-detail-card-icon">

                            <i class="fa-solid fa-credit-card"></i>

                        </div>

                    </div>


                    <div class="payment-detail-list">


                        <div class="payment-detail-row">

                            <span>
                                Payment ID
                            </span>

                            <strong>
                                #{{ $payment->id }}
                            </strong>

                        </div>


                        <div class="payment-detail-row">

                            <span>
                                Booking
                            </span>

                            <strong>

                                @if($payment->booking)

                                    #BK-{{ str_pad(
                                        $payment->booking->id,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                @else

                                    —

                                @endif

                            </strong>

                        </div>


                        <div class="payment-detail-row">

                            <span>
                                Payment Method
                            </span>

                            <strong>

                                @if($payment->payment_method === 'kbzpay')

                                    <span class="payment-detail-method payment-detail-kbzpay">

                                        <i class="fa-solid fa-mobile-screen-button"></i>

                                        KBZPay

                                    </span>

                                @else

                                    <span class="payment-detail-method payment-detail-cod">

                                        <i class="fa-solid fa-money-bill-wave"></i>

                                        COD

                                    </span>

                                @endif

                            </strong>

                        </div>


                        <div class="payment-detail-row">

                            <span>
                                Submitted
                            </span>

                            <strong>

                                {{ $payment->created_at?->format('d M Y, h:i A') ?? '—' }}

                            </strong>

                        </div>


                        @if($payment->verified_at)

                            <div class="payment-detail-row">

                                <span>

                                    @if($payment->payment_status === 'rejected')
                                        Reviewed
                                    @else
                                        Approved
                                    @endif

                                </span>

                                <strong>

                                    {{ $payment->verified_at->format('d M Y, h:i A') }}

                                </strong>

                            </div>

                        @endif

                    </div>

                </section>


                {{-- CUSTOMER --}}

                <section class="payment-detail-card">

                    <div class="payment-detail-card-header">

                        <div>

                            <h3>
                                Customer
                            </h3>

                            <p>
                                Customer information
                            </p>

                        </div>

                        <div class="payment-detail-card-icon">

                            <i class="fa-solid fa-user"></i>

                        </div>

                    </div>


                    <div class="payment-customer-detail">

                        <div class="payment-customer-large-avatar">

                            <i class="fa-solid fa-user"></i>

                        </div>


                        <div>

                            <h4>

                                {{ $payment->booking?->customer?->user?->name
                                    ?? 'Unknown Customer' }}

                            </h4>

                            <p>
                                Customer
                            </p>

                        </div>

                    </div>


                    <div class="payment-detail-list">

                        <div class="payment-detail-row">

                            <span>
                                Service Area
                            </span>

                            <strong>

                                {{ $payment->booking?->area?->name
                                    ?? '—' }}

                            </strong>

                        </div>


                        <div class="payment-detail-row">

                            <span>
                                City
                            </span>

                            <strong>

                                {{ $payment->booking?->area?->city?->name
                                    ?? '—' }}

                            </strong>

                        </div>

                    </div>

                </section>

            </div>


            {{-- =================================================
                 AMOUNT SUMMARY
            ================================================== --}}

            <section class="payment-detail-card payment-amount-card">

                <div class="payment-detail-card-header">

                    <div>

                        <h3>
                            Amount Summary
                        </h3>

                        <p>
                            Payment and booking amounts
                        </p>

                    </div>

                    <div class="payment-detail-card-icon">

                        <i class="fa-solid fa-money-bill"></i>

                    </div>

                </div>


                <div class="payment-amount-grid">


                    <div class="payment-amount-item">

                        <span>
                            Prepaid Amount
                        </span>

                        <strong>

                            MMK
                            {{ number_format($payment->prepaid_amount ?? 0) }}

                        </strong>

                    </div>
<div class="payment-amount-item">

    <span>
        Remaining Amount
    </span>

    <strong>

        MMK
        {{ number_format(
            $payment->remaining_amount ?? 0
        ) }}

    </strong>

</div>
                    <div class="payment-amount-item">

                        <span>
                            Service Fee
                        </span>

                        <strong>

                            MMK
                            {{ number_format($payment->service_amount ?? 0) }}

                        </strong>

                    </div>


                    <div class="payment-amount-item payment-amount-total">

                        <span>
                            Total Amount
                        </span>

                        <strong>

                            MMK
                            {{ number_format(
                                $payment->booking?->total_amount ?? 0
                            ) }}

                        </strong>

                    </div>

                </div>

            </section>


            {{-- =================================================
                 SERVICES
            ================================================== --}}

            <section class="payment-detail-card">

                <div class="payment-detail-card-header">

                    <div>

                        <h3>
                            Selected Services
                        </h3>

                        <p>
                            Services included in this booking
                        </p>

                    </div>

                    <div class="payment-detail-card-icon">

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                    </div>

                </div>


                @if($services->count() > 0)

                    <div class="payment-services-detail">

                        @foreach($services as $service)

                            <div class="payment-service-detail-item">

                                <div class="payment-service-detail-icon">

                                    <i class="fa-solid fa-wrench"></i>

                                </div>


                                <div class="payment-service-detail-info">

                                    <strong>
                                        {{ $service->name }}
                                    </strong>

                                    <span>

                                        {{ $service->category?->name
                                            ?? 'Home Service' }}

                                    </span>

                                </div>


                                <div class="payment-service-detail-price">

                                    MMK
                                    {{ number_format($service->base_price ?? 0) }}

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="payment-detail-empty">

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                        <span>
                            No service information available.
                        </span>

                    </div>

                @endif

            </section>


            {{-- =================================================
                 PAYMENT SLIP
            ================================================== --}}

            <section class="payment-detail-card">

    <div class="payment-detail-card-header">

        <div>

            <h3>
                Payment Slip
            </h3>

            <p>
                Customer payment proof
            </p>

        </div>

        <div class="payment-detail-card-icon">

            <i class="fa-solid fa-image"></i>

        </div>

    </div>


    @if(
        $finalPaymentPending &&
        $payment->final_slip_image
    )

        <div class="payment-slip-detail">

            <div class="payment-slip-stage-label">

                <i class="fa-solid fa-money-bill-wave me-2"></i>

                Final Payment Slip

            </div>


            <a
                href="{{ asset('storage/' . $payment->final_slip_image) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="payment-slip-image-link"
            >

                <img
                    src="{{ asset('storage/' . $payment->final_slip_image) }}"
                    alt="Final Payment Slip"
                    class="payment-slip-image"
                >

                <span class="payment-slip-overlay">

                    <i class="fa-solid fa-expand"></i>

                    Open Full Image

                </span>

            </a>

        </div>


    @elseif($payment->slip_image)

        <div class="payment-slip-detail">

            <div class="payment-slip-stage-label">

                <i class="fa-solid fa-mobile-screen-button me-2"></i>

                Prepayment Slip

            </div>


            <a
                href="{{ asset('storage/' . $payment->slip_image) }}"
                target="_blank"
                rel="noopener noreferrer"
                class="payment-slip-image-link"
            >

                <img
                    src="{{ asset('storage/' . $payment->slip_image) }}"
                    alt="Prepayment Slip"
                    class="payment-slip-image"
                >

                <span class="payment-slip-overlay">

                    <i class="fa-solid fa-expand"></i>

                    Open Full Image

                </span>

            </a>

        </div>


    @else

        <div class="payment-no-slip-detail">

            <div>

                <i class="fa-solid fa-receipt"></i>

            </div>

            <h5>
                No payment slip
            </h5>

            <p>
                This payment was submitted without a payment slip.
            </p>

        </div>

    @endif

</section>

          {{-- =================================================
     REJECTION REASON
================================================== --}}

@if(
    $finalPaymentRejected &&
    $payment->final_rejection_reason
)

    <section class="payment-rejection-card">

        <div class="payment-rejection-icon">

            <i class="fa-solid fa-circle-exclamation"></i>

        </div>

        <div>

            <h4>
                Final Payment Rejection Reason
            </h4>

            <p>
                {{ $payment->final_rejection_reason }}
            </p>

        </div>

    </section>

@elseif(
    $prepaymentRejected &&
    $payment->rejection_reason
)

    <section class="payment-rejection-card">

        <div class="payment-rejection-icon">

            <i class="fa-solid fa-circle-exclamation"></i>

        </div>

        <div>

            <h4>
                Prepayment Rejection Reason
            </h4>

            <p>
                {{ $payment->rejection_reason }}
            </p>

        </div>

    </section>

@endif

            {{-- =================================================
     ACTIONS
================================================== --}}

@if(
    $finalPaymentPending &&
    $payment->final_slip_image
)

    {{-- =================================================
         FINAL PAYMENT ACTIONS
    ================================================== --}}

    <section class="payment-detail-actions">

        <form
            method="POST"
            action="{{ route('provider.payments.verify', $payment->id) }}"
            class="payment-detail-approve-form"
        >

            @csrf

            <button
                type="submit"
                class="payment-detail-approve-button"
            >

                <i class="fa-solid fa-circle-check"></i>

                Verify Final Payment

            </button>

        </form>


        <button
            type="button"
            class="payment-detail-reject-button"
            id="openDetailReject"
        >

            <i class="fa-solid fa-circle-xmark"></i>

            Reject Final Payment

        </button>

    </section>


@elseif($prepaymentPending)

    {{-- =================================================
         PREPAYMENT ACTIONS
    ================================================== --}}

    <section class="payment-detail-actions">

        <form
            method="POST"
            action="{{ route('provider.payments.verify', $payment->id) }}"
            class="payment-detail-approve-form"
        >

            @csrf

            <button
                type="submit"
                class="payment-detail-approve-button"
            >

                <i class="fa-solid fa-circle-check"></i>

                Approve Payment

            </button>

        </form>


        <button
            type="button"
            class="payment-detail-reject-button"
            id="openDetailReject"
        >

            <i class="fa-solid fa-circle-xmark"></i>

            Reject Payment

        </button>

    </section>


@elseif($finalPaymentRejected)

    {{-- =================================================
         FINAL PAYMENT REJECTED
    ================================================== --}}

    <section class="payment-rejected-message">

        <i class="fa-solid fa-circle-xmark"></i>

        <div>

            <strong>
                Final Payment Rejected
            </strong>

            <span>

                {{ $payment->final_rejection_reason
                    ?? 'No rejection reason provided.' }}

            </span>

        </div>

    </section>


@elseif($prepaymentRejected)

    {{-- =================================================
         PREPAYMENT REJECTED
    ================================================== --}}

    <section class="payment-rejected-message">

        <i class="fa-solid fa-circle-xmark"></i>

        <div>

            <strong>
                Prepayment Rejected
            </strong>

            <span>

                {{ $payment->rejection_reason
                    ?? 'No rejection reason provided.' }}

            </span>

        </div>

    </section>


@elseif($finalPaymentVerified)

    {{-- =================================================
         FULLY PAID
    ================================================== --}}

    <section class="payment-approved-message">

        <i class="fa-solid fa-circle-check"></i>

        <div>

            <strong>
                Payment Fully Verified
            </strong>

            <span>
                The customer has completed the final payment.
            </span>

        </div>

    </section>


@elseif($prepaymentVerified)

    {{-- =================================================
         PREPAYMENT VERIFIED
    ================================================== --}}

    <section class="payment-approved-message">

        <i class="fa-solid fa-circle-check"></i>

        <div>

            <strong>
                Prepayment Approved
            </strong>

            <span>
                The prepayment has already been verified.
            </span>

        </div>

    </section>

@endif


{{-- =====================================================
     REJECT MODAL
====================================================== --}}

<div
    class="modal fade"
    id="detailRejectModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content payment-detail-reject-modal">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title">
                        Reject Payment
                    </h5>

                    <p>
                        Please provide a reason for rejection.
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
                action="{{ route('provider.payments.reject', $payment->id) }}"
                id="detailRejectForm"
            >

                @csrf

                <div class="modal-body">

                    <textarea
                        name="rejection_reason"
                        id="detailRejectionReason"
                        class="form-control"
                        rows="5"
                        maxlength="1000"
                        placeholder="Please explain why this payment is being rejected..."
                        required
                    ></textarea>


                    <div class="payment-detail-character-count">

                        <span id="detailCharacterCount">
                            0
                        </span>

                        / 1000

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn payment-detail-cancel"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="btn payment-detail-confirm-reject"
                        id="detailConfirmReject"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Reject Payment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Bootstrap JS --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


{{-- Provider JS --}}

<script
    src="{{ asset('js/provider.js') }}"
></script>


{{-- Payment Detail JS --}}

<script
    src="{{ asset('js/provider-payment-show.js') }}"
></script>

</body>

</html>