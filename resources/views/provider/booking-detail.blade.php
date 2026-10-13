<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Booking #{{ $booking->id }} - QuickFix
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
         QUICKFIX CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >


    <!-- =====================================================
         PROVIDER CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/provider.css') }}"
    >


    <!-- =====================================================
         BOOKING DETAIL CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/provider-booking-detail.css') }}"
    >

</head>


<body>


<div
    class="provider-layout"
    id="providerLayout"
>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="provider-sidebar">


        <!-- Sidebar Header -->

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


        <!-- =====================================================
             PROVIDER PROFILE
        ====================================================== -->

        <div class="provider-profile">

            <div class="provider-avatar">

                @if(
                    $provider->user &&
                    $provider->user->profile_image
                )

                    <img
                        src="{{ asset('storage/' . $provider->user->profile_image) }}"
                        alt="{{ $provider->user->name }}"
                        style="
                            width:100%;
                            height:100%;
                            object-fit:cover;
                            border-radius:50%;
                        "
                    >

                @else

                    <i class="fa-solid fa-user"></i>

                @endif

            </div>


            <div class="provider-profile-info">

                <h4>
                    {{ $provider->user->name ?? 'Provider' }}
                </h4>

                <span>
                    Service Provider
                </span>

            </div>

        </div>


       <!-- =====================================================
     SIDEBAR MENU
====================================================== -->

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
        class="provider-menu-link active"
    >

        <i class="fa-solid fa-calendar-check"></i>

        <span class="provider-menu-text">
            My Bookings
        </span>

    </a>


    <!-- My Jobs -->

    <a
        href="{{ route('provider.jobs') }}"
        class="provider-menu-link"
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

        <!-- =====================================================
             SIDEBAR BOTTOM
        ====================================================== -->

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

    <div
        class="provider-overlay"
        id="providerOverlay"
    ></div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="provider-main">


        <!-- =================================================
             TOP HEADER
        ================================================== -->

        <header class="provider-header">


            <!-- Header Left -->

            <div class="provider-header-left">


                <!-- Mobile Menu -->

                <button
                    type="button"
                    class="provider-mobile-menu"
                    id="mobileMenuToggle"
                >

                    <i class="fa-solid fa-bars"></i>

                </button>


                <!-- Header Title -->

                <div>

                    <h1>

                        Booking Detail

                    </h1>

                    <p>

                        View booking information and status history.

                    </p>

                </div>


            </div>


            <!-- Header Actions -->

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
                >

                    <i
                        class="fa-solid fa-moon"
                        id="themeIcon"
                    ></i>

                </button>


                <!-- Profile -->

                


        </header>


        <!-- =================================================
             PAGE CONTENT
        ================================================== -->

        <div class="provider-content">


            <div class="provider-booking-detail-page">


                <!-- =================================================
                     PAGE HEADER
                ================================================== -->

                <div class="provider-booking-detail-header">


                    <div class="provider-booking-detail-header-left">


                        <a
                            href="{{ route('provider.bookings') }}"
                            class="provider-detail-back"
                        >

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to My Bookings

                        </a>


                        <div class="provider-detail-title">

                            <h2>

                            

                            </h2>

                            <p>

                                

                            </p>

                        </div>


                    </div>


                    <!-- =================================================
                         CURRENT STATUS
                    ================================================== -->

                    @php

                        $statusClass = match($booking->status) {

                            'pending'
                                => 'pending',

                            'accepted'
                                => 'accepted',

                            'on_the_way',
                            'arrived',
                            'in_progress'
                                => 'ongoing',

                            'completed'
                                => 'completed',

                            'cancelled',
                            'timeout'
                                => 'cancelled',

                            default
                                => 'pending',

                        };


                        $statusText = match($booking->status) {

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

                    @endphp


                    <div class="provider-detail-current-status">

                        <span class="provider-detail-status-label">

                            Current Status

                        </span>


                        <span
                            id="currentBookingStatus"
                            class="provider-booking-status status-{{ $statusClass }}"
                        >

                            {{ $statusText }}

                        </span>

                    </div>


                </div>


                <!-- =================================================
                     DETAIL GRID
                ================================================== -->

                <div class="provider-detail-grid">


                    <!-- =================================================
                         LEFT COLUMN
                    ================================================== -->

                    <div class="provider-detail-main">


                        <!-- =================================================
                             CUSTOMER INFORMATION
                        ================================================== -->

                        <div class="provider-detail-card">


                            <div class="provider-detail-card-header">

                                <div class="provider-detail-card-title">

                                    <div class="provider-detail-icon">

                                        <i class="fa-solid fa-user"></i>

                                    </div>

                                    <div>

                                        <h2>

                                            Customer Information

                                        </h2>

                                        <p>

                                            Customer details for this booking

                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="provider-detail-customer">


                                <div class="provider-detail-avatar">

                                    <i class="fa-solid fa-user"></i>

                                </div>


                                <div class="provider-detail-customer-info">

                                    <h3>

                                        {{ $booking->customer->user->name ?? 'Unknown Customer' }}

                                    </h3>


                                    @if($booking->customer?->user?->email)

                                        <p>

                                            <i class="fa-solid fa-envelope"></i>

                                            {{ $booking->customer->user->email }}

                                        </p>

                                    @endif


                                    @if($booking->customer?->user?->phone)

                                        <p>

                                            <i class="fa-solid fa-phone"></i>

                                            {{ $booking->customer->user->phone }}

                                        </p>

                                    @endif

                                </div>


                            </div>


                        </div>


                        <!-- =================================================
                             BOOKING INFORMATION
                        ================================================== -->

                        <div class="provider-detail-card">


                            <div class="provider-detail-card-header">

                                <div class="provider-detail-card-title">

                                    <div class="provider-detail-icon">

                                        <i class="fa-solid fa-calendar-days"></i>

                                    </div>

                                    <div>

                                        <h2>

                                            Booking Information

                                        </h2>

                                        <p>

                                            Date, time and service location

                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="provider-detail-info-grid">


                                <!-- Booking ID -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Booking ID

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-hashtag"></i>

                                        {{ $booking->id }}

                                    </strong>

                                </div>


                                <!-- Service Area -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Service Area

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-location-dot"></i>

                                        {{ $booking->area->name ?? 'N/A' }}

                                        @if($booking->area?->city)

                                            , {{ $booking->area->city->name }}

                                        @endif

                                    </strong>

                                </div>


                                <!-- Scheduled Date -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Scheduled Date

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-calendar"></i>

                                        @if($booking->scheduled_at)

                                            {{ $booking->scheduled_at->format('M d, Y') }}

                                        @else

                                            N/A

                                        @endif

                                    </strong>

                                </div>


                                <!-- Scheduled Time -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Scheduled Time

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-clock"></i>

                                        @if($booking->scheduled_at)

                                            {{ $booking->scheduled_at->format('h:i A') }}

                                        @else

                                            N/A

                                        @endif

                                    </strong>

                                </div>


                                <!-- Created -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Created At

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-calendar-plus"></i>

                                        {{ $booking->created_at?->format('M d, Y h:i A') ?? 'N/A' }}

                                    </strong>

                                </div>


                                <!-- Deadline -->

                                <div class="provider-detail-info-item">

                                    <span class="provider-detail-info-label">

                                        Response Deadline

                                    </span>

                                    <strong>

                                        <i class="fa-solid fa-hourglass-half"></i>

                                        {{ $booking->response_deadline?->format('M d, Y h:i A') ?? 'N/A' }}

                                    </strong>

                                </div>


                            </div>


                        </div>


                        <!-- =================================================
                             SELECTED SERVICES
                        ================================================== -->

                        <div class="provider-detail-card">


                            <div class="provider-detail-card-header">

                                <div class="provider-detail-card-title">

                                    <div class="provider-detail-icon">

                                        <i class="fa-solid fa-screwdriver-wrench"></i>

                                    </div>

                                    <div>

                                        <h2>

                                            Selected Services

                                        </h2>

                                        <p>

                                            Services requested by the customer

                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="provider-detail-services">


                                @forelse($services as $service)

                                    <div class="provider-detail-service">


                                        <div class="provider-detail-service-icon">

                                            <i class="fa-solid fa-wrench"></i>

                                        </div>


                                        <div class="provider-detail-service-info">

                                            <strong>

                                                {{ $service->name }}

                                            </strong>


                                            @if($service->description)

                                                <span>

                                                    {{ $service->description }}

                                                </span>

                                            @endif

                                        </div>


                                    </div>

                                @empty

                                    <div class="provider-detail-no-data">

                                        <i class="fa-regular fa-folder-open"></i>

                                        No selected services found.

                                    </div>

                                @endforelse


                            </div>


                        </div>


                        <!-- =================================================
                             CUSTOMER NOTES
                        ================================================== -->

                        @if($booking->customer_note)

                            <div class="provider-detail-card">


                                <div class="provider-detail-card-header">

                                    <div class="provider-detail-card-title">

                                        <div class="provider-detail-icon">

                                            <i class="fa-solid fa-note-sticky"></i>

                                        </div>

                                        <div>

                                            <h2>

                                                Customer Notes

                                            </h2>

                                            <p>

                                                Additional information

                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <div class="provider-detail-notes">

                                    {{ $booking->customer_note }}

                                </div>


                            </div>

                        @endif


                    </div>


                    <!-- =================================================
                         RIGHT COLUMN
                    ================================================== -->

                    <div class="provider-detail-sidebar">


                        <!-- =================================================
                             BOOKING SUMMARY
                        ================================================== -->

                        <div class="provider-detail-card">


                            <div class="provider-detail-card-header">

                                <div class="provider-detail-card-title">

                                    <div class="provider-detail-icon">

                                        <i class="fa-solid fa-list-check"></i>

                                    </div>

                                    <div>

                                        <h2>

                                            Booking Summary

                                        </h2>

                                        <p>

                                            Quick booking overview

                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="provider-detail-summary">


                                <div>

                                    <span>

                                        Booking ID

                                    </span>

                                    <strong>

                                        #{{ $booking->id }}

                                    </strong>

                                </div>


                                <div>

                                    <span>

                                        Customer

                                    </span>

                                    <strong>

                                        {{ $booking->customer->user->name ?? 'Unknown' }}

                                    </strong>

                                </div>


                                <div>

                                    <span>

                                        Services

                                    </span>

                                    <strong>

                                        {{ $services->count() }}

                                    </strong>

                                </div>


                                <div>

                                    <span>

                                        Service Area

                                    </span>

                                    <strong>

                                        {{ $booking->area->name ?? 'N/A' }}

                                    </strong>

                                </div>


                                <div>

                                    <span>

                                        Status

                                    </span>

                                    <strong id="summaryBookingStatus">

                                        {{ $statusText }}

                                    </strong>

                                </div>


                                <div>

                                    <span>

                                        Created

                                    </span>

                                    <strong>

                                        {{ $booking->created_at?->format('M d, Y') ?? 'N/A' }}

                                    </strong>

                                </div>


                            </div>


                        </div>
<!-- =================================================
     PAYMENT INFORMATION
================================================== -->

@if($booking->payment)
@php

    $payment = $booking->payment;

    $finalPaymentRequired =
        $booking->status === 'completed' &&
        (float) $payment->remaining_amount > 0 &&
        $payment->final_payment_status !== 'verified';

    $finalPaymentPending =
        $finalPaymentRequired &&
        $payment->final_payment_status === 'pending';

    $finalPaymentRejected =
        $finalPaymentRequired &&
        $payment->final_payment_status === 'rejected';

    $finalPaymentVerified =
        $payment->final_payment_status === 'verified';

@endphp

    <div class="provider-detail-card">


        <!-- =================================================
             CARD HEADER
        ================================================== -->

        <div class="provider-detail-card-header">

            <div class="provider-detail-card-title">

                <div class="provider-detail-icon">

                    <i class="fa-solid fa-credit-card"></i>

                </div>

                <div>

                    <h2>

                        Payment Information

                    </h2>

                    <p>

                        Prepayment and payment verification

                    </p>

                </div>

            </div>

        </div>


        <!-- =================================================
             PAYMENT DETAILS
        ================================================== -->

        <div class="provider-detail-summary">


            <!-- SERVICE AMOUNT -->

            <div>

                <span>

                    Service Amount

                </span>

                <strong>

                    {{ number_format(
                        (float) $booking->payment->service_amount
                    ) }}
                    MMK

                </strong>

            </div>


            <!-- PREPAYMENT -->

            <div>

                <span>

                    Required Prepayment

                </span>

                <strong>

                    @if(
                        (float) $booking->payment->prepaid_amount > 0
                    )

                        {{ number_format(
                            (float) $booking->payment->prepaid_amount
                        ) }}
                        MMK

                    @else

                        No Prepayment

                    @endif

                </strong>

            </div>


            <!-- REMAINING -->

            <div>

                <span>

                    Remaining Amount

                </span>

                <strong>

                    {{ number_format(
                        (float) $booking->payment->remaining_amount
                    ) }}
                    MMK

                </strong>

            </div>


            <!-- PAYMENT METHOD -->

            <div>

                <span>

                    Payment Method

                </span>

                <strong>

                    @if(
                        $booking->payment->payment_method === 'kbzpay'
                    )

                        KBZPay

                    @else

                        Cash on Delivery

                    @endif

                </strong>

            </div>


            <!-- PAYMENT STATUS -->

            <div>

                <span>

                    Prepayment Status

                </span>

                <strong>

                    @if(
                        $booking->payment->payment_status === 'verified'
                    )

                        <span class="badge bg-success">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            Verified

                        </span>

                    @elseif(
                        $booking->payment->payment_status === 'rejected'
                    )

                        <span class="badge bg-danger">

                            <i class="fa-solid fa-circle-xmark me-1"></i>

                            Rejected

                        </span>

                    @else

                        <span class="badge bg-warning text-dark">

                            <i class="fa-solid fa-clock me-1"></i>

                            Pending

                        </span>

                    @endif

                </strong>

            </div>


        </div>


        <!-- =================================================
     PAYMENT SLIP
================================================== -->

@if((float) $booking->payment->prepaid_amount > 0)

    @if($booking->payment->slip_image)

        <div class="mt-4">

            <h6 class="fw-bold mb-3">

                <i class="fa-solid fa-image me-2"></i>

                Customer Payment Slip

            </h6>


            <div
                class="border rounded p-2 bg-light"
                style="max-width: 100%;"
            >

                <a
                    href="{{ asset(
                        'storage/' . $booking->payment->slip_image
                    ) }}"
                    target="_blank"
                >

                    <img
                        src="{{ asset(
                            'storage/' . $booking->payment->slip_image
                        ) }}"
                        alt="Customer Payment Slip"
                        class="img-fluid rounded"
                        style="
                            max-height: 350px;
                            width: 100%;
                            object-fit: contain;
                            cursor: pointer;
                        "
                    >

                </a>

            </div>


            <small class="text-muted d-block mt-2">

                Click the image to open the full payment slip.

            </small>

        </div>

    @else

        <div class="alert alert-warning mt-4 mb-0">

            <i class="fa-solid fa-clock me-2"></i>

            The customer has not uploaded a payment slip yet.

        </div>

    @endif

@endif


       <!-- =================================================
     PAYMENT ACTIONS
================================================== -->

@if(
    (float) $booking->payment->prepaid_amount > 0 &&
    $booking->payment->payment_status === 'pending' &&
    $booking->payment->slip_image
)

    <div class="mt-4">

        <!-- VERIFY PREPAYMENT -->

        <form
            action="{{ route('provider.payments.verify', $booking->payment->id) }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-success w-100"
                onclick="return confirm('Are you sure you want to verify this payment?');"
            >

                <i class="fa-solid fa-circle-check me-1"></i>

                Verify Prepayment

            </button>

        </form>


        <!-- REJECT PAYMENT -->

        <button
            type="button"
            class="btn btn-outline-danger w-100 mt-2"
            data-bs-toggle="modal"
            data-bs-target="#rejectPaymentModal{{ $booking->id }}"
        >

            <i class="fa-solid fa-circle-xmark me-1"></i>

            Reject Payment

        </button>

    </div>

@endif

<!-- =================================================
     FINAL PAYMENT INFORMATION
================================================== -->

@if($booking->status === 'completed')

    <div class="mt-4 pt-4 border-top">

        <h5 class="fw-bold mb-3">

            <i class="fa-solid fa-money-check-dollar me-2"></i>

            Final Payment

        </h5>


        <!-- FINAL AMOUNT -->

        <div class="provider-detail-summary">

            <div>

                <span>

                    Remaining Amount

                </span>

                <strong>

                    {{ number_format(
                        (float) $payment->remaining_amount
                    ) }}
                    MMK

                </strong>

            </div>


            <!-- FINAL STATUS -->

            <div>

                <span>

                    Final Payment Status

                </span>

                <strong>

                    @if($finalPaymentVerified)

                        <span class="badge bg-success">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            Verified

                        </span>

                    @elseif($finalPaymentRejected)

                        <span class="badge bg-danger">

                            <i class="fa-solid fa-circle-xmark me-1"></i>

                            Rejected

                        </span>

                    @elseif($finalPaymentPending)

                        <span class="badge bg-warning text-dark">

                            <i class="fa-solid fa-clock me-1"></i>

                            Pending Verification

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            Not Submitted

                        </span>

                    @endif

                </strong>

            </div>

        </div>


        <!-- =================================================
             FINAL PAYMENT SLIP
        ================================================== -->

        @if($payment->final_slip_image)

            <div class="mt-4">

                <h6 class="fw-bold mb-3">

                    <i class="fa-solid fa-image me-2"></i>

                    Customer Final Payment Slip

                </h6>


                <div
                    class="border rounded p-2 bg-light"
                    style="max-width: 100%;"
                >

                    <a
                        href="{{ asset(
                            'storage/' . $payment->final_slip_image
                        ) }}"
                        target="_blank"
                    >

                        <img
                            src="{{ asset(
                                'storage/' . $payment->final_slip_image
                            ) }}"
                            alt="Customer Final Payment Slip"
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

                    Click the image to open the full final payment slip.

                </small>

            </div>

        @elseif($finalPaymentRequired)

            <div class="alert alert-warning mt-4 mb-0">

                <i class="fa-solid fa-clock me-2"></i>

                The customer has not submitted the final payment yet.

            </div>

        @endif


        <!-- =================================================
             FINAL PAYMENT ACTIONS
        ================================================== -->

        @if(
            $finalPaymentPending &&
            $payment->final_slip_image
        )

            <div class="mt-4">

                <!-- VERIFY FINAL PAYMENT -->

                <form
                    action="{{ route(
                        'provider.payments.verify',
                        $payment->id
                    ) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success w-100"
                        onclick="
                            return confirm(
                                'Are you sure you want to verify this final payment?'
                            );
                        "
                    >

                        <i class="fa-solid fa-circle-check me-1"></i>

                        Verify Final Payment

                    </button>

                </form>


                <!-- REJECT FINAL PAYMENT -->

                <button
                    type="button"
                    class="btn btn-outline-danger w-100 mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#rejectFinalPaymentModal{{ $booking->id }}"
                >

                    <i class="fa-solid fa-circle-xmark me-1"></i>

                    Reject Final Payment

                </button>

            </div>

        @endif


        <!-- =================================================
             FINAL PAYMENT VERIFIED
        ================================================== -->

        @if($finalPaymentVerified)

            <div class="alert alert-success mt-4 mb-0">

                <i class="fa-solid fa-circle-check me-2"></i>

                <strong>

                    Final Payment Verified

                </strong>

                <p class="mb-0 mt-2">

                    The customer's remaining payment has been
                    successfully verified.

                </p>

            </div>

        @endif


        <!-- =================================================
             FINAL PAYMENT REJECTED
        ================================================== -->

        @if(
            $finalPaymentRejected &&
            $payment->final_rejection_reason
        )

            <div class="alert alert-danger mt-4 mb-0">

                <strong>

                    <i class="fa-solid fa-circle-xmark me-2"></i>

                    Final Payment Rejected

                </strong>


                <p class="mb-0 mt-2">

                    {{ $payment->final_rejection_reason }}

                </p>

            </div>

        @endif

    </div>

@endif
        <!-- =================================================
             VERIFIED INFORMATION
        ================================================== -->

        @if(
            $booking->payment->payment_status === 'verified' &&
            $booking->payment->verified_at
        )

            <div class="alert alert-success mt-4 mb-0">

                <div>

                    <i class="fa-solid fa-circle-check me-2"></i>

                    <strong>

                        Prepayment Verified

                    </strong>

                </div>


                <small class="d-block mt-2">

                    Verified on:

                    {{ $booking->payment->verified_at->format(
                        'M d, Y h:i A'
                    ) }}

                </small>


                @if($booking->payment->verifier)

                    <small class="d-block">

                        Verified by:

                        {{ $booking->payment->verifier->name }}

                    </small>

                @endif

            </div>

        @endif


        <!-- =================================================
             REJECTION REASON
        ================================================== -->

        @if(
            $booking->payment->payment_status === 'rejected' &&
            $booking->payment->rejection_reason
        )

            <div class="alert alert-danger mt-4 mb-0">

                <strong>

                    <i class="fa-solid fa-circle-xmark me-2"></i>

                    Payment Rejected

                </strong>


                <p class="mb-0 mt-2">

                    {{ $booking->payment->rejection_reason }}

                </p>

            </div>

        @endif
        <!-- =================================================
     REFUND INFORMATION
================================================== -->

@if(
    $payment &&
    (float) $payment->refunded_amount > 0
)

    <div class="mt-4 pt-4 border-top">

        <h5 class="fw-bold mb-3">

            <i class="fa-solid fa-money-bill-transfer me-2"></i>

            Refund Information

        </h5>


        <!-- REFUND AMOUNT -->

        <div class="provider-detail-summary">

            <div>

                <span>
                    Refund Amount
                </span>

                <strong>
                    {{ number_format(
                        (float) $payment->refunded_amount
                    ) }}
                    MMK
                </strong>

            </div>


            <!-- REFUND STATUS -->

            <div>

                <span>
                    Refund Status
                </span>

                <strong>

                    @if(
                        $payment->refund_customer_response ===
                        'not_received'
                    )

                        <span class="badge bg-danger">

                            <i class="fa-solid fa-circle-xmark me-1"></i>

                            Customer Reports Refund Not Received

                        </span>

                    @elseif(
                        $payment->refund_status === 'refunded'
                    )

                        <span class="badge bg-success">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            Refunded

                        </span>

                    @elseif(
                        $payment->refund_status === 'pending' &&
                        $payment->refund_submitted_at
                    )

                        <span class="badge bg-warning text-dark">

                            <i class="fa-solid fa-clock me-1"></i>

                            Waiting for Customer Verification

                        </span>

                    @elseif(
                        $payment->refund_status === 'pending'
                    )

                        <span class="badge bg-danger">

                            <i class="fa-solid fa-money-bill-transfer me-1"></i>

                            Refund Required

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            Not Required

                        </span>

                    @endif

                </strong>

            </div>

        </div>


        <!-- =================================================
             CUSTOMER SAYS REFUND NOT RECEIVED
        ================================================== -->

        @if(
            $payment->refund_customer_response ===
            'not_received'
        )

            <div class="alert alert-danger mt-4 mb-0">

                <i class="fa-solid fa-triangle-exclamation me-2"></i>

                <strong>
                    Customer Reports Refund Not Received
                </strong>

                <p class="mb-0 mt-2">

                    The customer reported that the previous refund
                    was not received. Please refund the customer again
                    and submit new refund proof.

                </p>

            </div>


            <!-- PREVIOUS REFUND PROOF -->

            @if($payment->refund_slip_image)

                <div class="mt-4">

                    <h6 class="fw-bold mb-3">

                        <i class="fa-solid fa-image me-2"></i>

                        Previous Refund Proof

                    </h6>


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
                            alt="Previous Refund Proof"
                            class="img-fluid rounded"
                            style="
                                max-height:350px;
                                width:100%;
                                object-fit:contain;
                                cursor:pointer;
                            "
                        >

                    </a>


                    @if($payment->refund_submitted_at)

                        <small class="text-muted d-block mt-2">

                            Previous proof submitted:

                            {{ $payment->refund_submitted_at->format(
                                'M d, Y h:i A'
                            ) }}

                        </small>

                    @endif

                </div>

            @endif


            <!-- SUBMIT NEW REFUND -->

            <button
                type="button"
                class="btn btn-danger w-100 mt-4"
                data-bs-toggle="modal"
                data-bs-target="#refundBookingModal{{ $booking->id }}"
            >

                <i class="fa-solid fa-rotate me-2"></i>

                Submit New Refund Proof

            </button>


        <!-- =================================================
             WAITING FOR CUSTOMER
        ================================================== -->

        @elseif(
            $payment->refund_status === 'pending' &&
            $payment->refund_submitted_at
        )

            <div class="alert alert-warning mt-4 mb-0">

                <i class="fa-solid fa-hourglass-half me-2"></i>

                <strong>
                    Refund Submitted
                </strong>

                <p class="mb-0 mt-2">

                    Your refund proof has been submitted.
                    The customer must confirm that they received
                    the refund.

                </p>

                <small class="d-block mt-2">

                    Submitted:

                    {{ $payment->refund_submitted_at->format(
                        'M d, Y h:i A'
                    ) }}

                </small>

            </div>


            <!-- REFUND PROOF -->

            @if($payment->refund_slip_image)

                <div class="mt-4">

                    <h6 class="fw-bold mb-3">

                        <i class="fa-solid fa-image me-2"></i>

                        Refund Proof

                    </h6>


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

            @endif


        <!-- =================================================
             REFUND REQUIRED
        ================================================== -->

        @elseif(
            $payment->refund_status === 'pending'
        )

            <div class="alert alert-danger mt-4 mb-0">

                <i class="fa-solid fa-money-bill-transfer me-2"></i>

                <strong>
                    Refund Required
                </strong>

                <p class="mb-0 mt-2">

                    You must refund the customer's verified
                    prepayment and submit refund proof.

                </p>

            </div>

        @endif


        <!-- =================================================
             CUSTOMER CONFIRMED REFUND
        ================================================== -->

        @if(
            $payment->refund_status === 'refunded'
        )

            <div class="alert alert-success mt-4 mb-0">

                <i class="fa-solid fa-circle-check me-2"></i>

                <strong>
                    Refund Confirmed by Customer
                </strong>

                <p class="mb-0 mt-2">

                    The customer has confirmed that the refund
                    was received successfully.

                </p>

                @if($payment->refunded_at)

                    <small class="d-block mt-2">

                        Confirmed:

                        {{ $payment->refunded_at->format(
                            'M d, Y h:i A'
                        ) }}

                    </small>

                @endif

            </div>

        @endif


        <!-- =================================================
             REFUND REASON
        ================================================== -->

        @if($payment->refund_reason)

            <div class="mt-3">

                <span class="provider-detail-info-label">

                    Refund Reason

                </span>

                <div class="mt-1">

                    {{ $payment->refund_reason }}

                </div>

            </div>

        @endif

    </div>

@endif

    </div>

@endif

                        <!-- =================================================
                             BOOKING ACTION AREA
                        ================================================== -->

                        <div id="bookingActionArea">


                            <!-- =================================================
                                 PENDING
                            ================================================== -->

                            @if($booking->status === 'pending')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-hourglass-half"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Booking Request

                                                </h2>

                                                <p>

                                                    Review this booking request and choose whether to accept it.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="d-flex gap-2">


                                        <!-- ACCEPT -->

                                        <button
                                            type="button"
                                            class="btn btn-primary w-100 ajax-status-btn"
                                            data-url="{{ route('provider.bookings.accept', $booking->id) }}"
                                        >

                                            <i class="fa-solid fa-check me-2"></i>

                                            Accept

                                        </button>


                                        <!-- REJECT -->

                                        <button
                                            type="button"
                                            class="btn btn-danger w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectBookingModal{{ $booking->id }}"
                                        >

                                            <i class="fa-solid fa-xmark me-2"></i>

                                            Reject

                                        </button>


                                    </div>


                                </div>


                            <!-- =================================================
                                 ACCEPTED
                            ================================================== -->

                            @elseif($booking->status === 'accepted')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-location-arrow"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Ready to Go

                                                </h2>

                                                <p>

                                                    You accepted this booking.
                                                    Start travelling to the customer's location.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="d-flex gap-2">


                                        <!-- ON THE WAY -->

                                        <button
                                            type="button"
                                            class="btn btn-primary w-100 ajax-status-btn"
                                            data-url="{{ route('provider.bookings.go-to-site', $booking->id) }}"
                                        >

                                            <i class="fa-solid fa-location-arrow me-2"></i>

                                            I'm On The Way

                                        </button>


                                        <!-- CANCEL -->

                                        <button
                                            type="button"
                                            class="btn btn-danger w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelBookingModal{{ $booking->id }}"
                                        >

                                            <i class="fa-solid fa-xmark me-2"></i>

                                            Cancel Booking

                                        </button>


                                    </div>


                                </div>


                            <!-- =================================================
                                 ON THE WAY
                            ================================================== -->

                            @elseif($booking->status === 'on_the_way')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-location-dot"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    On The Way

                                                </h2>

                                                <p>

                                                    Mark the booking as arrived when you reach
                                                    the customer's location.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="d-flex gap-2">


                                        <!-- ARRIVED -->

                                        <button
                                            type="button"
                                            class="btn btn-primary w-100 ajax-status-btn"
                                            data-url="{{ route('provider.bookings.arrive', $booking->id) }}"
                                        >

                                            <i class="fa-solid fa-location-dot me-2"></i>

                                            Mark Arrived

                                        </button>


                                        <!-- CANCEL -->

                                        <button
                                            type="button"
                                            class="btn btn-danger w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelBookingModal{{ $booking->id }}"
                                        >

                                            <i class="fa-solid fa-xmark me-2"></i>

                                            Cancel Booking

                                        </button>


                                    </div>


                                </div>


                            <!-- =================================================
                                 ARRIVED
                            ================================================== -->

                            @elseif($booking->status === 'arrived')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-play"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Ready to Start

                                                </h2>

                                                <p>

                                                    You have arrived at the customer's location.
                                                    Start the service when ready.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="d-flex gap-2">


                                        <!-- START SERVICE -->

                                        <button
                                            type="button"
                                            class="btn btn-primary w-100 ajax-status-btn"
                                            data-url="{{ route('provider.bookings.start', $booking->id) }}"
                                        >

                                            <i class="fa-solid fa-play me-2"></i>

                                            Start Service

                                        </button>


                                        <!-- CANCEL -->

                                        <button
                                            type="button"
                                            class="btn btn-danger w-100"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelBookingModal{{ $booking->id }}"
                                        >

                                            <i class="fa-solid fa-xmark me-2"></i>

                                            Cancel Booking

                                        </button>


                                    </div>


                                </div>


                            <!-- =================================================
                                 IN PROGRESS
                            ================================================== -->

                            @elseif($booking->status === 'in_progress')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-screwdriver-wrench"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Service In Progress

                                                </h2>

                                                <p>

                                                    Mark the service as completed when the job is finished.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <button
                                        type="button"
                                        class="btn btn-success w-100 ajax-status-btn"
                                        data-url="{{ route('provider.bookings.complete', $booking->id) }}"
                                    >

                                        <i class="fa-solid fa-check me-2"></i>

                                        Complete Service

                                    </button>


                                </div>

<!-- =================================================
     COMPLETED
================================================== -->

@elseif($booking->status === 'completed')

    @php

        $payment = $booking->payment;

        $finalPaymentRequired =
            $payment &&
            (float) $payment->remaining_amount > 0 &&
            $payment->final_payment_status !== 'verified';

        $finalPaymentPending =
            $payment &&
            $payment->final_payment_status === 'pending' &&
            !empty($payment->final_slip_image);

        $finalPaymentRejected =
            $payment &&
            $payment->final_payment_status === 'rejected';

        $finalPaymentVerified =
            $payment &&
            $payment->final_payment_status === 'verified';

    @endphp


    <div class="provider-detail-card">


        <!-- HEADER -->

        <div class="provider-detail-card-header">

            <div class="provider-detail-card-title">


                <div class="provider-detail-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>


                <div>

                    <h2>

                        Service Completed

                    </h2>


                    <p>

                        The service has been completed successfully.

                    </p>

                </div>


            </div>

        </div>


        <!-- FINAL PAYMENT PENDING -->

        @if($finalPaymentPending)

            <div class="alert alert-warning mb-0">

                <div>

                    <i class="fa-solid fa-clock me-2"></i>

                    <strong>

                        Final Payment Pending Verification

                    </strong>

                </div>


                <p class="mb-2 mt-2">

                    The customer has submitted the final payment of

                    <strong>

                        {{ number_format(
                            (float) $payment->remaining_amount
                        ) }}
                        MMK

                    </strong>

                    and is waiting for provider verification.

                </p>


                <a
                    href="{{ route(
                        'provider.payments.show',
                        $payment->id
                    ) }}"
                    class="btn btn-primary w-100 mt-2"
                >

                    <i class="fa-solid fa-money-check-dollar me-1"></i>

                    Review Final Payment

                </a>

            </div>


        <!-- FINAL PAYMENT REJECTED -->

        @elseif($finalPaymentRejected)

            <div class="alert alert-danger mb-0">

                <div>

                    <i class="fa-solid fa-circle-xmark me-2"></i>

                    <strong>

                        Final Payment Rejected

                    </strong>

                </div>


                <p class="mb-2 mt-2">

                    The customer's final payment was rejected.

                </p>


                @if($payment->final_rejection_reason)

                    <p class="mb-2">

                        <strong>

                            Reason:

                        </strong>

                        {{ $payment->final_rejection_reason }}

                    </p>

                @endif


                <a
                    href="{{ route(
                        'provider.payments.show',
                        $payment->id
                    ) }}"
                    class="btn btn-outline-danger w-100 mt-2"
                >

                    <i class="fa-solid fa-money-check-dollar me-1"></i>

                    Review Final Payment

                </a>

            </div>


        <!-- FINAL PAYMENT REQUIRED -->

        @elseif($finalPaymentRequired)

            <div class="alert alert-warning mb-0">

                <div>

                    <i class="fa-solid fa-clock me-2"></i>

                    <strong>

                        Final Payment Required

                    </strong>

                </div>


                <p class="mb-0 mt-2">

                    The service is completed.

                    The customer still needs to submit the remaining payment of

                    <strong>

                        {{ number_format(
                            (float) $payment->remaining_amount
                        ) }}
                        MMK

                    </strong>.

                </p>

            </div>


        <!-- FULLY PAID -->

        @elseif($finalPaymentVerified)

            <div class="alert alert-success mb-0">

                <i class="fa-solid fa-circle-check me-2"></i>

                <strong>

                    Service and Payment Completed

                </strong>


                <p class="mb-0 mt-2">

                    The service has been completed and the final payment
                    has been successfully verified.

                </p>

            </div>


        <!-- NO REMAINING PAYMENT -->

        @else

            <div class="alert alert-success mb-0">

                <i class="fa-solid fa-check-circle me-2"></i>

                The service has been completed successfully.

                No further payment action is required.

            </div>

        @endif


    </div>

                            <!-- =================================================
                                 CANCELLED
                            ================================================== -->

                            @elseif($booking->status === 'cancelled')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-circle-xmark"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Booking Cancelled

                                                </h2>

                                                <p>

                                                    This booking is no longer active.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="alert alert-danger mb-0">

                                        <i class="fa-solid fa-circle-xmark me-2"></i>

                                        This booking has been cancelled.

                                    </div>


                                </div>


                            <!-- =================================================
                                 TIMEOUT
                            ================================================== -->

                            @elseif($booking->status === 'timeout')

                                <div class="provider-detail-card">


                                    <div class="provider-detail-card-header">

                                        <div class="provider-detail-card-title">

                                            <div class="provider-detail-icon">

                                                <i class="fa-solid fa-hourglass-end"></i>

                                            </div>

                                            <div>

                                                <h2>

                                                    Booking Expired

                                                </h2>

                                                <p>

                                                    The response deadline for this booking has passed.

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="alert alert-warning mb-0">

                                        <i class="fa-solid fa-hourglass-end me-2"></i>

                                        This booking has expired because the response
                                        time was exceeded.

                                    </div>


                                </div>

                            @endif


                        </div>


                        <!-- =================================================
                             STATUS HISTORY
                        ================================================== -->

                        <div class="provider-detail-card">


                            <div class="provider-detail-card-header">

                                <div class="provider-detail-card-title">

                                    <div class="provider-detail-icon">

                                        <i class="fa-solid fa-clock-rotate-left"></i>

                                    </div>

                                    <div>

                                        <h2>

                                            Status History

                                        </h2>

                                        <p>

                                            Booking status changes

                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="provider-status-timeline">


                                @forelse(
                                    $booking->statusHistory->sortBy('created_at')
                                    as $history
                                )


                                    @php

                                        $historyClass = match($history->status) {

                                            'pending'
                                                => 'pending',

                                            'accepted'
                                                => 'accepted',

                                            'on_the_way',
                                            'arrived',
                                            'in_progress'
                                                => 'ongoing',

                                            'completed'
                                                => 'completed',

                                            'cancelled',
                                            'timeout'
                                                => 'cancelled',

                                            default
                                                => 'pending',

                                        };


                                        $historyText = match($history->status) {

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
                                                        $history->status
                                                    )
                                                ),

                                        };

                                    @endphp


                                    <div class="provider-status-history-item">


                                        <div class="provider-status-history-line">

                                            <span
                                                class="provider-status-history-dot {{ $historyClass }}"
                                            ></span>

                                        </div>


                                        <div class="provider-status-history-content">


                                            <div class="provider-status-history-top">

                                                <strong>

                                                    {{ $historyText }}

                                                </strong>

                                                <span>

                                                    {{ $history->created_at?->format('M d, h:i A') }}

                                                </span>

                                            </div>


                                            <p>

                                                @switch($history->status)

                                                    @case('pending')

                                                        Booking request created.

                                                        @break


                                                    @case('accepted')

                                                        Provider accepted the booking.

                                                        @break


                                                    @case('on_the_way')

                                                        Provider is on the way.

                                                        @break


                                                    @case('arrived')

                                                        Provider has arrived.

                                                        @break


                                                    @case('in_progress')

                                                        Service is currently in progress.

                                                        @break


                                                    @case('completed')

                                                        Service has been completed.

                                                        @break


                                                    @case('cancelled')

                                                        Booking was cancelled.

                                                        @if($history->note)

                                                            <br>

                                                            <strong>

                                                                Reason:

                                                            </strong>

                                                            {{ $history->note }}

                                                        @endif

                                                        @break


                                                    @case('timeout')

                                                        Provider response time expired.

                                                        @break


                                                    @default

                                                        Booking status was updated.

                                                @endswitch

                                            </p>


                                            <small>

                                                <i class="fa-solid fa-user-pen"></i>

                                                Updated by User #{{ $history->updated_by }}

                                            </small>


                                        </div>


                                    </div>


                                @empty


                                    <div class="provider-detail-no-data">

                                        <i class="fa-regular fa-clock"></i>

                                        No status history available.

                                    </div>


                                @endforelse


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </main>


</div>

<!-- =====================================================
     REJECT PAYMENT MODAL
====================================================== -->

@if(
    $booking->payment &&
    (float) $booking->payment->prepaid_amount > 0 &&
    $booking->payment->payment_status === 'pending' &&
    $booking->payment->slip_image
)

    <div
        class="modal fade"
        id="rejectPaymentModal{{ $booking->id }}"
        tabindex="-1"
        aria-labelledby="rejectPaymentModalLabel{{ $booking->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- MODAL HEADER -->

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="rejectPaymentModalLabel{{ $booking->id }}"
                    >

                        <i class="fa-solid fa-circle-xmark me-2"></i>

                        Reject Payment

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <!-- FORM -->

                <form
                    action="{{ route('provider.payments.reject', $booking->payment->id) }}"
                    method="POST"
                >

                    @csrf


                    <div class="modal-body">


                        <p class="mb-3">

                            Are you sure you want to reject the payment
                            for booking

                            <strong>

                                #{{ $booking->id }}

                            </strong>?

                        </p>


                        <label
                            for="paymentRejectionReason{{ $booking->id }}"
                            class="form-label"
                        >

                            Rejection Reason

                            <span class="text-danger">

                                *

                            </span>

                        </label>


                        <textarea
                            name="rejection_reason"
                            id="paymentRejectionReason{{ $booking->id }}"
                            class="form-control"
                            rows="4"
                            maxlength="1000"
                            required
                            placeholder="Please explain why the payment slip is invalid or cannot be accepted..."
                        ></textarea>


                        <small class="text-muted">

                            The customer will be notified and can upload
                            a new payment slip.

                        </small>


                    </div>


                    <!-- MODAL FOOTER -->

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >

                            Keep Payment

                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="fa-solid fa-xmark me-1"></i>

                            Reject Payment

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

@endif
<!-- =====================================================
     REJECT FINAL PAYMENT MODAL
====================================================== -->

@if(
    $booking->payment &&
    $booking->status === 'completed' &&
    $booking->payment->final_payment_status === 'pending' &&
    $booking->payment->final_slip_image
)

    <div
        class="modal fade"
        id="rejectFinalPaymentModal{{ $booking->id }}"
        tabindex="-1"
        aria-labelledby="rejectFinalPaymentModalLabel{{ $booking->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- MODAL HEADER -->

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="rejectFinalPaymentModalLabel{{ $booking->id }}"
                    >

                        <i class="fa-solid fa-circle-xmark me-2"></i>

                        Reject Final Payment

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <!-- FORM -->

                <form
                    action="{{ route(
                        'provider.payments.reject',
                        $booking->payment->id
                    ) }}"
                    method="POST"
                >

                    @csrf


                    <div class="modal-body">


                        <p class="mb-3">

                            Are you sure you want to reject the
                            final payment for booking

                            <strong>

                                #{{ $booking->id }}

                            </strong>?

                        </p>


                        <label
                            for="finalPaymentRejectionReason{{ $booking->id }}"
                            class="form-label"
                        >

                            Rejection Reason

                            <span class="text-danger">

                                *

                            </span>

                        </label>


                        <textarea
                            name="rejection_reason"
                            id="finalPaymentRejectionReason{{ $booking->id }}"
                            class="form-control"
                            rows="4"
                            maxlength="1000"
                            required
                            placeholder="Please explain why the final payment slip is invalid..."
                        ></textarea>


                        <small class="text-muted">

                            The customer will be notified and can upload
                            a new final payment slip.

                        </small>


                    </div>


                    <!-- MODAL FOOTER -->

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >

                            Keep Payment

                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="fa-solid fa-xmark me-1"></i>

                            Reject Final Payment

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

@endif
<!-- =====================================================
     REJECT BOOKING MODAL
====================================================== -->

@if($booking->status === 'pending')

    <div
        class="modal fade"
        id="rejectBookingModal{{ $booking->id }}"
        tabindex="-1"
        aria-labelledby="rejectBookingModalLabel{{ $booking->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- Modal Header -->

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="rejectBookingModalLabel{{ $booking->id }}"
                    >

                        <i class="fa-solid fa-circle-xmark me-2"></i>

                        Reject Booking

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <!-- Form -->

                <form
                    action="{{ route('provider.bookings.reject', $booking->id) }}"
                    method="POST"
                >

                    @csrf


                    <div class="modal-body">


                        <p class="mb-3">

                            Are you sure you want to reject booking

                            <strong>

                                #{{ $booking->id }}

                            </strong>?

                        </p>


                        <label
                            for="rejectionNote{{ $booking->id }}"
                            class="form-label"
                        >

                            Rejection Note

                            <span class="text-muted">

                                (Optional)

                            </span>

                        </label>


                        <textarea
                            name="rejection_note"
                            id="rejectionNote{{ $booking->id }}"
                            class="form-control"
                            rows="4"
                            maxlength="1000"
                            placeholder="Please tell the customer why you are rejecting this booking..."
                        >{{ old('rejection_note') }}</textarea>


                        <small class="text-muted">

                            Leave this blank if you do not want to provide
                            a reason.

                        </small>


                    </div>


                    <div class="modal-footer">


                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >

                            Cancel

                        </button>


                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            <i class="fa-solid fa-xmark me-1"></i>

                            Reject Booking

                        </button>


                    </div>


                </form>


            </div>

        </div>

    </div>

@endif

<!-- =====================================================
     PROVIDER CANCEL BOOKING MODAL
====================================================== -->

<div
    class="modal fade"
    id="cancelBookingModal{{ $booking->id }}"
    tabindex="-1"
    aria-labelledby="cancelBookingModalLabel{{ $booking->id }}"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <h5
                    class="modal-title fw-bold"
                    id="cancelBookingModalLabel{{ $booking->id }}"
                >

                    <i
                        class="fa-solid fa-triangle-exclamation text-danger me-2"
                    ></i>

                    Cancel this booking?

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <!-- FORM -->

            <form
                method="POST"
                action="{{ route(
                    'provider.bookings.cancel',
                    $booking->id
                ) }}"
            >

                @csrf


                <!-- BODY -->

                <div class="modal-body">


                    <p class="mb-3">

                        Are you sure you want to cancel this booking?

                    </p>


                    <div class="alert alert-warning">

                        <div class="fw-bold mb-2">

                            Cancelling this booking may:

                        </div>


                        <ul class="mb-0 ps-3">

                            <li>
                                Affect your provider rating
                            </li>

                            <li>
                                Remove the rating for this job
                            </li>

                            @if(
                                $booking->payment &&
                                $booking->payment->payment_status === 'verified' &&
                                (float) $booking->payment->prepaid_amount > 0
                            )

                                <li>
                                    Require you to refund the customer's
                                    prepayment
                                </li>

                            @endif

                        </ul>

                    </div>


                    <div class="alert alert-danger mb-3">

                        <i
                            class="fa-solid fa-circle-exclamation me-2"
                        ></i>

                        <strong>
                            This action cannot be undone.
                        </strong>

                    </div>


                    <!-- CANCELLATION REASON -->

                    <div class="mb-3">

                        <label
                            for="cancellationReason{{ $booking->id }}"
                            class="form-label fw-semibold"
                        >

                            Cancellation Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <textarea
                            name="cancellation_reason"
                            id="cancellationReason{{ $booking->id }}"
                            class="form-control"
                            rows="3"
                            maxlength="1000"
                            placeholder="Please explain why you need to cancel this booking..."
                            required
                        ></textarea>

                    </div>

                </div>


                <!-- FOOTER -->

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
                    >

                        <i class="fa-solid fa-xmark me-1"></i>

                        Yes, Cancel

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

<!-- =====================================================
     PROVIDER REFUND MODAL
====================================================== -->

@if(
    (
        session('open_refund_modal') ||
        $booking->payment?->refund_customer_response === 'not_received'
    ) &&
    $booking->payment &&
    $booking->payment->payment_status === 'verified' &&
    (float) $booking->payment->prepaid_amount > 0 &&
    $booking->payment->refund_status === 'pending'
)

    @php
        $isReRefund =
            $booking->payment->refund_customer_response ===
            'not_received';
    @endphp

    <div
        class="modal fade"
        id="refundBookingModal{{ $booking->id }}"
        tabindex="-1"
        aria-labelledby="refundBookingModalLabel{{ $booking->id }}"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">


                <!-- HEADER -->

                <div class="modal-header">

                    <h5
                        class="modal-title fw-bold"
                        id="refundBookingModalLabel{{ $booking->id }}"
                    >

                        <i
                            class="fa-solid fa-money-bill-transfer text-warning me-2"
                        ></i>

                       {{ $isReRefund ? 'Refund Customer Again' : 'Refund Customer' }}

                    </h5>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <!-- FORM -->

                <form
                    method="POST"
                    action="{{ route(
                        'provider.bookings.refund',
                        $booking->id
                    ) }}"
                    enctype="multipart/form-data"
                >

                    @csrf


                    <div class="modal-body">


                        <!-- WARNING -->

                        <div class="alert alert-warning">

                            <i
                                class="fa-solid fa-triangle-exclamation me-2"
                            ></i>

                        <strong>

    {{ $isReRefund
        ? 'New Refund Required'
        : 'Refund Required'
    }}

</strong>

<div class="mt-2">

    @if($isReRefund)

        The customer reported that the previous refund
        was not received. Please refund the customer again
        and upload the new refund proof.

    @else

        You cancelled this booking after the
        customer's prepayment was verified.
        You must refund the customer.

    @endif

</div>

                        </div>


                        <!-- REFUND AMOUNT -->

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Refund Amount

                            </label>


                            <div class="form-control bg-light fw-bold">

                                {{ number_format(
                                    (float) session(
                                        'refund_amount',
                                        $booking->payment->prepaid_amount
                                    ),
                                    0
                                ) }}

                                MMK

                            </div>

                        </div>


                        <!-- REFUND PROOF -->

                        <div class="mb-3">

                            <label
                                for="refundSlipImage{{ $booking->id }}"
                                class="form-label fw-semibold"
                            >

                                Refund Proof

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                class="form-control"
                                id="refundSlipImage{{ $booking->id }}"
                                name="refund_slip_image"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >


                            <div class="form-text">

                                Upload a screenshot or receipt proving
                                that you refunded the customer.

                                Maximum size: 5 MB.

                            </div>

                        </div>


                        <!-- INFO -->

                        <div class="alert alert-info mb-0">

                            <i
                                class="fa-solid fa-circle-info me-2"
                            ></i>

                            After submitting the proof, the refund will
                            remain pending until the customer confirms
                            that they received the money.

                        </div>

                    </div>


                    <!-- FOOTER -->

                    <div class="modal-footer">


                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >

                            Cancel

                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i
                                class="fa-solid fa-money-bill-transfer me-1"
                            ></i>

                            {{ $isReRefund
    ? 'I Have Refunded Customer Again'
    : 'I Have Refunded Customer'
}}

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

@endif



<!-- =====================================================
     JQUERY
====================================================== -->

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
></script>


<!-- =====================================================
     BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     PROVIDER JS
====================================================== -->

<script
    src="{{ asset('js/provider.js') }}"
></script>


<!-- =====================================================
     BOOKING DETAIL JS
====================================================== -->

<script
    src="{{ asset('js/provider-booking-detail.js') }}"
></script>


<!-- =====================================================
     BOOKING STATUS AJAX
====================================================== -->

<script>

$(document).on('click', '.ajax-status-btn', function () {


    // =====================================================
    // GET BUTTON
    // =====================================================

    const button = $(this);

    const url = button.data('url');


    // =====================================================
    // PREVENT DOUBLE CLICK
    // =====================================================

    if (button.prop('disabled')) {

        return;

    }


    // =====================================================
    // SAVE ORIGINAL BUTTON
    // =====================================================

    const originalButton = button.html();


    // =====================================================
    // DISABLE BUTTON
    // =====================================================

    button.prop('disabled', true);


    button.html(`
        <i class="fa-solid fa-spinner fa-spin me-2"></i>
        Processing...
    `);


    // =====================================================
    // AJAX REQUEST
    // =====================================================

    $.ajax({

        url: url,

        type: 'POST',

        data: {

            _token: '{{ csrf_token() }}'

        },


        // =================================================
        // SUCCESS
        // =================================================

        success: function (response) {


            console.log(
                'AJAX SUCCESS:',
                response
            );


            const status =
                response.status;


            // =================================================
            // ACCEPTED
            // =================================================

            if (status === 'accepted') {


                updateBookingStatus(
                    'Accepted',
                    'status-accepted'
                );


                updateSummaryStatus(
                    'Accepted'
                );


                showAcceptedAction();


                addStatusHistory(
                    'accepted',
                    'Accepted',
                    'Provider accepted the booking.'
                );


            }


            // =================================================
            // ON THE WAY
            // =================================================

            else if (status === 'on_the_way') {


                updateBookingStatus(
                    'On The Way',
                    'status-ongoing'
                );


                updateSummaryStatus(
                    'On The Way'
                );


                showOnTheWayAction();


                addStatusHistory(
                    'on_the_way',
                    'On The Way',
                    'Provider is on the way to the customer.'
                );


            }


            // =================================================
            // ARRIVED
            // =================================================

            else if (status === 'arrived') {


                updateBookingStatus(
                    'Arrived',
                    'status-ongoing'
                );


                updateSummaryStatus(
                    'Arrived'
                );


                showArrivedAction();


                addStatusHistory(
                    'arrived',
                    'Arrived',
                    'Provider has arrived at the customer location.'
                );


            }


            // =================================================
            // IN PROGRESS
            // =================================================

            else if (status === 'in_progress') {


                updateBookingStatus(
                    'In Progress',
                    'status-ongoing'
                );


                updateSummaryStatus(
                    'In Progress'
                );


                showInProgressAction();


                addStatusHistory(
                    'in_progress',
                    'In Progress',
                    'Service is currently in progress.'
                );


            }


           // =================================================
// COMPLETED
// =================================================

else if (status === 'completed') {

    updateBookingStatus(
        'Completed',
        'status-completed'
    );

    updateSummaryStatus(
        'Completed'
    );

    addStatusHistory(
        'completed',
        'Completed',
        'Service has been completed.'
    );

    showCompletedAction(
        response.payment || null
    );
}

        },


        // =================================================
        // ERROR
        // =================================================

        error: function (xhr) {


            console.log(
                'AJAX ERROR:',
                xhr
            );


            let message =
                'Something went wrong. Please try again.';


            // =================================================
            // SERVER MESSAGE
            // =================================================

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;

            }


            // =================================================
            // TIMEOUT
            // =================================================

            if (
                xhr.responseJSON &&
                xhr.responseJSON.status === 'timeout'
            ) {


                updateBookingStatus(
                    'Timeout',
                    'status-cancelled'
                );


                updateSummaryStatus(
                    'Timeout'
                );


                showTimeoutAction();


                addStatusHistory(
                    'timeout',
                    'Timeout',
                    'Provider response time expired.'
                );


            }


            // =================================================
            // SHOW MESSAGE
            // =================================================

            alert(message);


            // =================================================
            // RESTORE BUTTON
            // =================================================

            if (
                !xhr.responseJSON ||
                xhr.responseJSON.status !== 'timeout'
            ) {

                button.prop(
                    'disabled',
                    false
                );


                button.html(
                    originalButton
                );

            }

        }

    });

});


// =============================================================
// UPDATE MAIN STATUS
// =============================================================

function updateBookingStatus(
    text,
    cssClass
) {


    $('#currentBookingStatus')

        .text(text)

        .removeClass(
            'status-pending ' +
            'status-accepted ' +
            'status-ongoing ' +
            'status-completed ' +
            'status-cancelled'
        )

        .addClass(cssClass);

}


// =============================================================
// UPDATE SUMMARY STATUS
// =============================================================

function updateSummaryStatus(text)
{

    $('#summaryBookingStatus')
        .text(text);

}


// =============================================================
// ACCEPTED ACTION
// =============================================================

function showAcceptedAction()
{

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">


            <div class="provider-detail-card-header">

                <div class="provider-detail-card-title">


                    <div class="provider-detail-icon">

                        <i class="fa-solid fa-location-arrow"></i>

                    </div>


                    <div>

                        <h2>

                            Ready to Go

                        </h2>


                        <p>

                            You accepted this booking.
                            Start travelling to the customer's location.

                        </p>

                    </div>


                </div>

            </div>


            <div class="d-flex gap-2">


                <!-- ON THE WAY -->

                <button
                    type="button"
                    class="btn btn-primary w-100 ajax-status-btn"
                    data-url="{{ route('provider.bookings.go-to-site', $booking->id) }}"
                >

                    <i class="fa-solid fa-location-arrow me-2"></i>

                    I'm On The Way

                </button>


                <!-- CANCEL -->

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelBookingModal{{ $booking->id }}"
                >

                    <i class="fa-solid fa-xmark me-2"></i>

                    Cancel Booking

                </button>


            </div>


        </div>

    `);

}


// =============================================================
// ON THE WAY ACTION
// =============================================================

function showOnTheWayAction()
{

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">


            <div class="provider-detail-card-header">

                <div class="provider-detail-card-title">


                    <div class="provider-detail-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>


                    <div>

                        <h2>

                            On The Way

                        </h2>


                        <p>

                            Mark the booking as arrived when you reach
                            the customer's location.

                        </p>

                    </div>


                </div>

            </div>


            <div class="d-flex gap-2">


                <!-- ARRIVED -->

                <button
                    type="button"
                    class="btn btn-primary w-100 ajax-status-btn"
                    data-url="{{ route(
                        'provider.bookings.arrive',
                        $booking->id
                    ) }}"
                >

                    <i class="fa-solid fa-location-dot me-2"></i>

                    Mark Arrived

                </button>


                <!-- CANCEL -->

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelBookingModal{{ $booking->id }}"
                >

                    <i class="fa-solid fa-xmark me-2"></i>

                    Cancel Booking

                </button>


            </div>


        </div>

    `);

}


// =============================================================
// ARRIVED ACTION
// =============================================================

function showArrivedAction()
{

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">


            <div class="provider-detail-card-header">

                <div class="provider-detail-card-title">


                    <div class="provider-detail-icon">

                        <i class="fa-solid fa-play"></i>

                    </div>


                    <div>

                        <h2>

                            Ready to Start

                        </h2>


                        <p>

                            You have arrived at the customer's location.
                            Start the service when ready.

                        </p>

                    </div>


                </div>

            </div>


            <div class="d-flex gap-2">


                <!-- START SERVICE -->

                <button
                    type="button"
                    class="btn btn-primary w-100 ajax-status-btn"
                    data-url="{{ route(
                        'provider.bookings.start',
                        $booking->id
                    ) }}"
                >

                    <i class="fa-solid fa-play me-2"></i>

                    Start Service

                </button>


                <!-- CANCEL -->

                <button
                    type="button"
                    class="btn btn-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#cancelBookingModal{{ $booking->id }}"
                >

                    <i class="fa-solid fa-xmark me-2"></i>

                    Cancel Booking

                </button>


            </div>


        </div>

    `);

}


// =============================================================
// IN PROGRESS ACTION
// =============================================================

function showInProgressAction()
{

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">


            <div class="provider-detail-card-header">

                <div class="provider-detail-card-title">


                    <div class="provider-detail-icon">

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                    </div>


                    <div>

                        <h2>

                            Service In Progress

                        </h2>


                        <p>

                            Mark the service as completed when the job is finished.

                        </p>

                    </div>


                </div>

            </div>


            <button
                type="button"
                class="btn btn-success w-100 ajax-status-btn"
                data-url="{{ route(
                    'provider.bookings.complete',
                    $booking->id
                ) }}"
            >

                <i class="fa-solid fa-check me-2"></i>

                Complete Service

            </button>


        </div>

    `);

}


// =============================================================
// COMPLETED ACTION
// =============================================================

function showCompletedAction(payment)
{
    const remainingAmount = Number(
        payment?.remaining_amount ?? 0
    );

    const finalPaymentStatus =
        payment?.final_payment_status ?? 'not_required';

    const finalSlipImage =
        payment?.final_slip_image ?? null;

    const finalRejectionReason =
        payment?.final_rejection_reason ?? null;

    const reviewUrl =
        payment?.review_url ??
        "{{ route('provider.payments.index') }}";

    const formattedAmount =
        remainingAmount.toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }
        );

    let paymentMessage = '';

    // =====================================================
    // FINAL PAYMENT VERIFIED
    // =====================================================

    if (
        finalPaymentStatus === 'verified'
    ) {

        paymentMessage = `
            <div class="alert alert-success mb-0">

                <i class="fa-solid fa-circle-check me-2"></i>

                <strong>
                    Service and Payment Completed
                </strong>

                <div class="mt-2">
                    The service and all required payments
                    have been completed successfully.
                </div>

            </div>
        `;
    }

    // =====================================================
    // FINAL PAYMENT REJECTED
    // =====================================================

    else if (
        finalPaymentStatus === 'rejected'
    ) {

        paymentMessage = `
            <div class="alert alert-danger mb-0">

                <i class="fa-solid fa-circle-xmark me-2"></i>

                <strong>
                    Final Payment Rejected
                </strong>

                <div class="mt-2">
                    The customer's final payment was rejected.
                </div>

                ${
                    finalRejectionReason
                    ? `
                        <div class="mt-2">
                            <strong>
                                Reason:
                            </strong>
                            ${finalRejectionReason}
                        </div>
                    `
                    : ''
                }

                <div class="mt-3">

                    <a
                        href="${reviewUrl}"
                        class="btn btn-sm btn-outline-danger"
                    >
                        <i class="fa-solid fa-credit-card me-1"></i>
                        Review Final Payment
                    </a>

                </div>

            </div>
        `;
    }

    // =====================================================
    // FINAL PAYMENT PENDING
    // =====================================================

    else if (
        remainingAmount > 0 &&
        finalPaymentStatus === 'pending'
    ) {

        // -------------------------------------------------
        // Customer has already uploaded final slip
        // -------------------------------------------------

        if (finalSlipImage) {

            paymentMessage = `
                <div class="alert alert-warning mb-0">

                    <i class="fa-solid fa-clock me-2"></i>

                    <strong>
                        Final Payment Pending Verification
                    </strong>

                    <div class="mt-2">
                        Remaining payment:
                        <strong>
                            ${formattedAmount} MMK
                        </strong>
                    </div>

                    <div class="mt-2">
                        The customer has submitted the
                        final payment. Please review and
                        verify the payment.
                    </div>

                    <div class="mt-3">

                        <a
                            href="${reviewUrl}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            <i class="fa-solid fa-credit-card me-1"></i>
                            Review Final Payment
                        </a>

                    </div>

                </div>
            `;

        }

        // -------------------------------------------------
        // Customer has not uploaded final slip yet
        // -------------------------------------------------

        else {

            paymentMessage = `
                <div class="alert alert-warning mb-0">

                    <i class="fa-solid fa-money-bill-wave me-2"></i>

                    <strong>
                        Final Payment Required
                    </strong>

                    <div class="mt-2">
                        Remaining payment:
                        <strong>
                            ${formattedAmount} MMK
                        </strong>
                    </div>

                    <div class="mt-2">
                        The customer must submit the
                        final payment for this booking.
                    </div>

                    <div class="mt-3">

                        <a
                            href="${reviewUrl}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            <i class="fa-solid fa-credit-card me-1"></i>
                            Review Payments
                        </a>

                    </div>

                </div>
            `;
        }
    }

    // =====================================================
    // NO FINAL PAYMENT REQUIRED
    // =====================================================

    else {

        paymentMessage = `
            <div class="alert alert-success mb-0">

                <i class="fa-solid fa-circle-check me-2"></i>

                <strong>
                    Service Completed
                </strong>

                <div class="mt-2">
                    No further payment is required.
                </div>

            </div>
        `;
    }


    // =====================================================
    // UPDATE ACTION AREA WITHOUT REFRESH
    // =====================================================

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">

            <div class="text-center py-3">

                <div class="mb-3">

                    <i
                        class="fa-solid fa-circle-check"
                        style="font-size: 50px;"
                    ></i>

                </div>

                <h4 class="fw-bold mb-2">
                    Service Completed
                </h4>

                <p class="text-muted mb-4">
                    This booking has been successfully completed.
                </p>

                ${paymentMessage}

            </div>

        </div>

    `);
}

// =============================================================
// TIMEOUT ACTION
// =============================================================

function showTimeoutAction()
{

    $('#bookingActionArea').html(`

        <div class="provider-detail-card">


            <div class="provider-detail-card-header">

                <div class="provider-detail-card-title">


                    <div class="provider-detail-icon">

                        <i class="fa-solid fa-hourglass-end"></i>

                    </div>


                    <div>

                        <h2>

                            Booking Expired

                        </h2>


                        <p>

                            The response deadline for this booking has passed.

                        </p>

                    </div>


                </div>

            </div>


            <div class="alert alert-warning mb-0">

                <i class="fa-solid fa-hourglass-end me-2"></i>

                This booking has expired because the response
                time was exceeded.

            </div>


        </div>

    `);

}


// =============================================================
// ADD STATUS HISTORY
// =============================================================

function addStatusHistory(
    statusClass,
    statusText,
    description
) {


    const dotClass =

        (
            statusClass === 'on_the_way' ||
            statusClass === 'arrived' ||
            statusClass === 'in_progress'
        )

        ? 'ongoing'

        : statusClass;


    $('.provider-status-timeline').append(`


        <div class="provider-status-history-item">


            <div class="provider-status-history-line">

                <span
                    class="provider-status-history-dot ${dotClass}"
                ></span>

            </div>


            <div class="provider-status-history-content">


                <div class="provider-status-history-top">

                    <strong>

                        ${statusText}

                    </strong>


                    <span>

                        Just now

                    </span>

                </div>


                <p>

                    ${description}

                </p>


                <small>

                    <i class="fa-solid fa-user-pen"></i>

                    Updated by You

                </small>


            </div>


        </div>


    `);

}
// =============================================================
// AUTO OPEN REFUND MODAL
// =============================================================

$(document).ready(function () {

    @if(session('open_refund_modal'))

        const refundModalElement =
            document.getElementById(
                'refundBookingModal{{ $booking->id }}'
            );

        if (refundModalElement) {

            const refundModal =
                new bootstrap.Modal(
                    refundModalElement
                );

            refundModal.show();

        }

    @endif

});


</script>


</body>

</html>