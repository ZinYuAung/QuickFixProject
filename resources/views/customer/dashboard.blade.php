<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >
        <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
        >

        <title>QuickFix - Customer Dashboard</title>


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


        <!-- Customer CSS -->
        <link
            rel="stylesheet"
            href="{{ asset('css/customer.css') }}"
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

            <aside class="customer-sidebar">


                <!-- Sidebar Header -->

                <div class="customer-sidebar-header">

                    <a
                        href="{{ url('/') }}"
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


                <!-- =================================================
             Sidebar Menu
        ================================================== -->

                <nav class="customer-sidebar-menu">


                    <!-- Dashboard -->

                    <a
                        href="{{ url('/customer/dashboard') }}"
                        class="customer-menu-link active"
                    >

                        <i class="fa-solid fa-house"></i>

                        <span class="customer-menu-text">
                            Dashboard
                        </span>

                    </a>



                    <!-- My Services -->

                    <a href="{{ route('customer.services') }}" class="customer-menu-link">
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="customer-menu-text">My Services</span>
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
                    


                    <!-- Profile -->

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
             Logout
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
         MOBILE SIDEBAR OVERLAY
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


                        <!-- Mobile Menu Button -->

                        <button
                            type="button"
                            class="customer-mobile-menu"
                            id="customerMobileMenu"
                            aria-label="Open menu"
                        >

                            <i class="fa-solid fa-bars"></i>

                        </button>


                        <div class="customer-header-title">

                            <h1>
                                Customer Dashboard
                            </h1>

                            <p>
                                Find trusted professionals for your home services.
                            </p>

                        </div>

                    </div>



                    <!-- Header Actions -->

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
                                        href="{{ route('customer.notifications') }}" class="customer-view-all-btn"
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


                                        <!-- Notification Item -->

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
                        <!-- Theme Toggle -->

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
             CUSTOMER DASHBOARD CONTENT

             YOUR ORIGINAL CONTENT IS KEPT HERE
        ================================================== -->

                <section class="customer-content">


                   <div
    class="customer-search-card"
    id="customerServiceSearch"
>

    <div class="customer-search-row">

        {{-- Search --}}
        <div class="customer-search-field customer-search-main">

            <div class="customer-search-input-wrapper">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    id="serviceSearch"
                    class="form-control"
                    placeholder="Search service..."
                    autocomplete="off"
                >

            </div>

        </div>


        {{-- City --}}
        <div class="customer-search-field">

            <select
                id="cityFilter"
                class="form-select"
            >

                <option value="">
                    All Cities
                </option>

                @foreach($cities as $city)

                    <option value="{{ $city->id }}">
                        {{ $city->name }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Service Area --}}
        <div class="customer-search-field">

            <select
                id="areaFilter"
                class="form-select"
                disabled
            >

                <option value="">
                    All Areas
                </option>

            </select>

        </div>

    </div>

</div>
<script
    id="customerServiceAreas"
    type="application/json"
>
    @json($serviceAreas)
</script>

<div
    class="customer-search-results"
    id="customerSearchResults"
>

    @foreach($services as $service)

       <div
    class="customer-search-result-item"
    data-service-name="{{ strtolower($service->name) }}"
    data-service-id="{{ $service->id }}"
    data-area-ids="{{ $service->serviceAreas->pluck('id')->implode(',') }}"
>

            <div class="customer-search-result-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>

            <div class="customer-search-result-info">

                <h5>
                    {{ $service->name }}
                </h5>

                @if($service->description)
                    <p>
                        {{ $service->description }}
                    </p>
                @endif

            </div>

            <div class="customer-search-result-price">

                {{ number_format($service->base_price) }} MMK

            </div>

        </div>

    @endforeach

</div>

                    <!-- =================================================
     Popular Service Categories
================================================== -->

                    <div class="customer-section-header">

                        <div>
                            <h4 class="mb-1">
                                Popular Service Categories
                            </h4>

                        </div>

                    </div>


                    <div class="customer-category-grid">

                        @forelse($popularCategories as $category)

                            <div class="customer-category-card">

                                <div class="customer-category-card-body">

                                    <!-- Category Icon -->

                                    <div class="customer-category-icon">

                                        <i class="fa-solid fa-screwdriver-wrench"></i>

                                    </div>


                                    <!-- Category Name -->

                                    <h5>
                                        {{ $category->name }}
                                    </h5>


                                    <!-- Category Description -->

                                    <p>
                                        {{ $category->description ?: 'Professional services from trusted providers.' }}
                                    </p>


                                    <!-- Category Information -->

                                    <div class="customer-category-info">

                                        <span>
                                            <i class="fa-solid fa-briefcase"></i>
                                            {{ $category->services_count }}
                                            {{ $category->services_count == 1 ? 'Service' : 'Services' }}
                                        </span>

                                        <span>
                                            <i class="fa-solid fa-calendar-check"></i>
                                            {{ $category->booking_count }}
                                            {{ $category->booking_count == 1 ? 'Booking' : 'Bookings' }}
                                        </span>

                                    </div>


                                    <!-- View Services -->

                                    <a
                                        href="{{ route('customer.services', ['category_id' => $category->id]) }}"
                                        class="customer-category-view-btn"
                                    >
                                        View Services

                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>

                                </div>

                            </div>

                        @empty

                            <div class="customer-category-empty">

                                <i class="fa-solid fa-layer-group"></i>

                                <h5>
                                    No service categories available
                                </h5>

                                <p>
                                    Service categories will appear here when they are available.
                                </p>

                            </div>

                        @endforelse

                    </div>
                   
        <!-- =================================================
     My Active Bookings
================================================== -->

        <div class="customer-active-bookings-section">

            <div class="customer-section-header">

                <div>
                    <h4 class="mb-1">
                        My Active Bookings
                    </h4>

                </div>

                <a
                    href="{{ route('customer.bookings') }}"
                    class="btn btn-outline-primary btn-sm"
                >
                    View All
                </a>

            </div>


            @forelse($recentBookings as $booking)

                <div class="customer-active-booking-card">

                    <!-- Booking Header -->

                    <div class="customer-active-booking-header">

                        <div>

                            <h5>
                                Booking #{{ $booking->id }}
                            </h5>

<span class="customer-booking-type">
    <i class="fas fa-calendar-check"></i>
    {{ ucfirst(str_replace('_', ' ', $booking->booking_type)) }}
</span>
                        </div>


                        <span class="customer-booking-status status-{{ $booking->status }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>

                    </div>


                    <!-- Booking Content -->

                    <div class="customer-active-booking-content">

                        <!-- Problem Photo -->

                        <div class="customer-booking-photo">

                            @if($booking->problem_photo)

                                <img
                                    src="{{ asset('storage/' . $booking->problem_photo) }}"
                                    alt="Problem Photo"
                                >

                            @else

                                <div class="customer-booking-no-photo">

                                    <i class="fa-solid fa-camera"></i>

                                    <span>No Photo</span>

                                </div>

                            @endif

                        </div>


                        <!-- Booking Information -->

                        <div class="customer-active-booking-info">

                            <!-- Selected Services -->

                            <div class="customer-booking-info-row">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                                <div>

                                    <strong>Services:</strong>

                                    <span>

                                        @if($booking->selected_services->count())

                                            {{ $booking->selected_services->take(2)->pluck('name')->implode(', ') }}

                                            @if($booking->selected_services->count() > 2)
                                                +{{ $booking->selected_services->count() - 2 }} more
                                            @endif

                                        @else

                                            No services selected

                                        @endif

                                    </span>

                                </div>

                            </div>


                            <!-- Provider -->

                            <div class="customer-booking-info-row">

                                <i class="fa-solid fa-user-tie"></i>

                                <div>

                                    <strong>Provider:</strong>

                                    <span>
                                        {{ $booking->provider?->user?->name ?? 'Not assigned' }}
                                    </span>

                                </div>

                            </div>


                            <!-- Location -->

                            <div class="customer-booking-info-row">

                                <i class="fa-solid fa-location-dot"></i>

                                <div>

                                    <strong>Location:</strong>

                                    <span>

                                        {{ $booking->area?->city?->name ?? 'Unknown City' }}

                                        @if($booking->area)
                                            • {{ $booking->area->name }}
                                        @endif

                                    </span>

                                </div>

                            </div>


                            <!-- Scheduled Date -->

                            <div class="customer-booking-info-row">

                                <i class="fa-solid fa-calendar"></i>

                                <div>

                                    <strong>Date:</strong>

                                    <span>
                                        {{ $booking->scheduled_at?->format('M d, Y • h:i A') ?? 'Not scheduled' }}
                                    </span>

                                </div>

                            </div>


                            <!-- Total -->

                            <div class="customer-booking-info-row">

                                <i class="fa-solid fa-money-bill"></i>

                                <div>

                                    <strong>Total:</strong>

                                    <span>
                                        {{ number_format($booking->total_amount, 0) }} MMK
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Booking Actions -->

                    <div class="customer-active-booking-actions">

                        <a
                            href="{{ route('customer.booking.detail', $booking->id) }}"
                            class="btn btn-primary btn-sm"
                        >
                            <i class="fa-solid fa-eye"></i>
                            View Booking
                        </a>


                        @if($booking->status === 'pending')

                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm"
                            >
                                <i class="fa-solid fa-xmark"></i>
                                Cancel
                            </button>

                        @elseif($booking->status === 'accepted')

                            <a
                                href="{{ route('customer.chats', ['provider_id' => $booking->provider?->id]) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                <i class="fa-solid fa-comments"></i>
                                Chat
                            </a>

                        @elseif(in_array($booking->status, ['on_the_way', 'arrived', 'in_progress']))

                            <a
                               href="{{ route('customer.chats', ['provider_id' => $booking->provider?->id]) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                <i class="fa-solid fa-comments"></i>
                                Chat
                            </a>

                        @endif

                    </div>

                </div>

            @empty

                <div class="customer-booking-card">

                    <div class="card-body text-center py-5">

                        <i class="fa-solid fa-calendar-check fs-1 text-primary mb-3"></i>

                        <h5>
                            No active bookings
                        </h5>

                        <p class="text-muted">
                            You don't have any active bookings right now.
                        </p>

                        <a
                            href="{{ route('customer.services') }}"
                            class="btn btn-primary"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Find a Service
                        </a>

                    </div>

                </div>

            @endforelse

        </div>


        </section>


        </main>

        </div>
        <!-- Bootstrap JS -->

        <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>
        <script src="{{ asset('js/quickfix.js') }}"></script>
        <script src="{{ asset('js/customer.js') }}"></script>


    </body>

</html>