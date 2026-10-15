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
         PROVIDER CSS
    ====================================================== -->

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


        <!-- =====================================================
             TOP HEADER
        ====================================================== -->

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
                        My Bookings
                    </h1>

                    <p>
                        Manage your customer service bookings.
                    </p>

                </div>

            </div>


            <!-- HEADER ACTIONS -->

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

                <div class="provider-status">

                    

                </div>


                <!-- Dark / Light Mode -->

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
             PAGE CONTENT
        ====================================================== -->

        <div class="provider-content">


            <!-- =====================================================
                 SEARCH & FILTER
            ====================================================== -->

            <div class="provider-booking-toolbar">


                <!-- Search -->

                <div class="provider-booking-search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        id="bookingSearch"
                        placeholder="Search bookings..."
                    >

                </div>


                <!-- Filter -->

                <div class="provider-booking-filter">

                    <select id="bookingStatusFilter">

                        <option value="all">
                            All Bookings
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="accepted">
                            Accepted
                        </option>

                        <option value="ongoing">
                            Ongoing
                        </option>

                        <option value="completed">
                            Completed
                        </option>

                        <option value="cancelled">
                            Cancelled
                        </option>

                        <option value="timeout">
                            Timeout
                        </option>

                    </select>

                </div>

            </div>


            <!-- =====================================================
                 BOOKINGS TABLE
            ====================================================== -->

            <div class="provider-bookings-table-wrapper">

                <div class="table-responsive">

                    <table class="provider-bookings-table">


                        <!-- TABLE HEADER -->

                        <thead>

                            <tr>

                                <th>
                                    Booking
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Service Area
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <!-- TABLE BODY -->

                        <tbody id="bookingTableBody">


                            @forelse($bookings as $booking)

                                @php

                                    $statusClass = match ($booking->status) {

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


                                    $statusText = match ($booking->status) {

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
                                     * First selected service
                                     */

                                    $firstServiceId = collect(
                                        $booking->chosen_services ?? []
                                    )
                                        ->flatten()
                                        ->filter()
                                        ->first();


                                    $service = $firstServiceId
                                        ? \App\Models\Service::find($firstServiceId)
                                        : null;

                                @endphp


                                <tr
                                    class="provider-booking-row"
                                    data-status="{{ $booking->status }}"
                                >


                                    <!-- BOOKING -->

                                    <td>

                                        <div class="provider-table-booking">

                                            <div class="provider-table-booking-icon">

                                                <i class="fa-solid fa-wrench"></i>

                                            </div>


                                            <div>

                                                <strong>
                                                    {{ $service?->name ?? 'Service Booking' }}
                                                </strong>

                                                <small>
                                                    #{{ $booking->id }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- CUSTOMER -->

                                    <td>

                                        <div class="provider-table-customer">

                                            <div class="provider-table-avatar">

                                                <i class="fa-solid fa-user"></i>

                                            </div>


                                            <span>

                                                {{ $booking->customer?->user?->name ?? 'Customer' }}

                                            </span>

                                        </div>

                                    </td>


                                    <!-- SERVICE AREA -->

                                    <td>

                                        <div class="provider-table-location">

                                            <i class="fa-solid fa-location-dot"></i>

                                            <span>

                                                {{ $booking->area?->name ?? 'Service Area' }}

                                                @if($booking->area?->city)

                                                    , {{ $booking->area->city->name }}

                                                @endif

                                            </span>

                                        </div>

                                    </td>


                                    <!-- DATE -->

                                    <td>

                                        @if($booking->scheduled_at)

                                            <strong>
                                                {{ $booking->scheduled_at->format('d M Y') }}
                                            </strong>

                                        @else

                                            <span class="provider-table-muted">
                                                ASAP
                                            </span>

                                        @endif

                                    </td>


                                    <!-- TIME -->

                                    <td>

                                        @if($booking->scheduled_at)

                                            <strong>
                                                {{ $booking->scheduled_at->format('h:i A') }}
                                            </strong>

                                        @else

                                            <span class="provider-table-muted">
                                                ASAP
                                            </span>

                                        @endif

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <span
                                            class="booking-status {{ $statusClass }}"
                                        >
                                            {{ $statusText }}
                                        </span>

                                    </td>


                                    <!-- ACTIONS -->

                                    <td>

                                        <div class="provider-table-actions">


                                            <!-- VIEW -->

                                            <a
                                                href="{{ route(
                                                    'provider.bookings.show',
                                                    $booking->id
                                                ) }}"
                                                class="provider-table-action view"
                                                title="View booking"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                            </a>


                                            <!-- =====================================================
                                                 PENDING
                                            ====================================================== -->

                                            @if($booking->status === 'pending')

                                                <!-- ACCEPT -->

                                                <a
                                                    href="{{ route(
                                                        'provider.bookings.show',
                                                        $booking->id
                                                    ) }}"
                                                    class="provider-table-action accept"
                                                    title="Open booking request"
                                                >

                                                    <i class="fa-solid fa-check"></i>

                                                </a>


                                                <!-- REJECT -->

                                                <button
                                                    type="button"
                                                    class="provider-table-action reject"
                                                    title="Reject booking"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectBookingModal{{ $booking->id }}"
                                                >

                                                    <i class="fa-solid fa-xmark"></i>

                                                </button>


                                                <!-- REJECT MODAL -->

                                                <div
                                                    class="modal fade"
                                                    id="rejectBookingModal{{ $booking->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="rejectBookingModalLabel{{ $booking->id }}"
                                                    aria-hidden="true"
                                                >

                                                    <div class="modal-dialog modal-dialog-centered">

                                                        <div class="modal-content">


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


                                                            <form
                                                                action="{{ route(
                                                                    'provider.bookings.reject',
                                                                    $booking->id
                                                                ) }}"
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
                                                 ACCEPTED
                                            ====================================================== -->

                                            @if($booking->status === 'accepted')

                                                <a
                                                    href="{{ route(
                                                        'provider.bookings.show',
                                                        $booking->id
                                                    ) }}"
                                                    class="provider-table-action start"
                                                    title="Open booking - ready to go"
                                                >

                                                    <i class="fa-solid fa-location-arrow"></i>

                                                </a>

                                            @endif


                                            <!-- =====================================================
                                                 ON THE WAY
                                            ====================================================== -->

                                            @if($booking->status === 'on_the_way')

                                                <a
                                                    href="{{ route(
                                                        'provider.bookings.show',
                                                        $booking->id
                                                    ) }}"
                                                    class="provider-table-action start"
                                                    title="Open booking - mark arrived"
                                                >

                                                    <i class="fa-solid fa-location-dot"></i>

                                                </a>

                                            @endif


                                            <!-- =====================================================
                                                 ARRIVED
                                            ====================================================== -->

                                            @if($booking->status === 'arrived')

                                                <a
                                                    href="{{ route(
                                                        'provider.bookings.show',
                                                        $booking->id
                                                    ) }}"
                                                    class="provider-table-action start"
                                                    title="Open booking - start service"
                                                >

                                                    <i class="fa-solid fa-play"></i>

                                                </a>

                                            @endif


                                            <!-- =====================================================
                                                 IN PROGRESS
                                            ====================================================== -->

                                            @if($booking->status === 'in_progress')

                                                <a
                                                    href="{{ route(
                                                        'provider.bookings.show',
                                                        $booking->id
                                                    ) }}"
                                                    class="provider-table-action complete"
                                                    title="Open booking - complete service"
                                                >

                                                    <i class="fa-solid fa-check-double"></i>

                                                </a>

                                            @endif


                                        </div>

                                    </td>

                                </tr>


                            @empty


                                <tr>

                                    <td
                                        colspan="7"
                                        class="provider-table-empty"
                                    >

                                        <div class="provider-empty-state">

                                            <div class="provider-empty-icon">

                                                <i class="fa-solid fa-calendar-xmark"></i>

                                            </div>


                                            <h3>
                                                No bookings found
                                            </h3>


                                            <p>
                                                You currently have no bookings.
                                            </p>

                                        </div>

                                    </td>

                                </tr>


                            @endforelse


                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </main>

</div>


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
     SEARCH / FILTER JS
====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('bookingSearch');

    const statusFilter =
        document.getElementById('bookingStatusFilter');

    const rows =
        document.querySelectorAll('.provider-booking-row');


    function filterBookings() {

        const search =
            searchInput.value
                .toLowerCase()
                .trim();

        const selectedStatus =
            statusFilter.value;


        rows.forEach(function (row) {

            const rowText =
                row.innerText.toLowerCase();

            const rowStatus =
                row.dataset.status;


            let matchesSearch =
                rowText.includes(search);


            let matchesStatus =
                selectedStatus === 'all';


            if (selectedStatus === 'ongoing') {

                matchesStatus =
                    [
                        'on_the_way',
                        'arrived',
                        'in_progress'
                    ].includes(rowStatus);

            }
            else if (selectedStatus !== 'all') {

                matchesStatus =
                    rowStatus === selectedStatus;

            }


            row.style.display =
                matchesSearch && matchesStatus
                    ? ''
                    : 'none';

        });

    }


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterBookings
        );

    }


    if (statusFilter) {

        statusFilter.addEventListener(
            'change',
            filterBookings
        );

    }

});

</script>


</body>

</html>