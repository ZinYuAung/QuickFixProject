<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>QuickFix - My Profile</title>

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

    <!-- Customer Profile CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('css/customer-profile.css') }}"
    >
</head>

<body>

    <div
        class="customer-layout"
        id="customerLayout"
    >

        <!-- =========================================
             CUSTOMER SIDEBAR
        ========================================== -->
        <aside class="customer-sidebar">

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
                    class="customer-menu-link active"
                >
                    <i class="fa-solid fa-user-circle"></i>

                    <span class="customer-menu-text">
                        My Profile
                    </span>
                </a>

            </nav>

            <!-- Sidebar Bottom -->
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


        <!-- =========================================
             MOBILE OVERLAY
        ========================================== -->
        <div
            class="customer-overlay"
            id="customerOverlay"
        ></div>


        <!-- =========================================
             MAIN CONTENT
        ========================================== -->
        <main class="customer-main">

            <!-- Header -->
            <header class="customer-header">

                <div class="customer-header-left">

                    <!-- Mobile Menu -->
                    <button
                        type="button"
                        class="customer-mobile-menu"
                        id="customerMobileMenu"
                        aria-label="Open menu"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <!-- Header Title -->
                    <div class="customer-header-title">

                        <h1>
                            My Profile
                        </h1>

                        <p>
                            Manage your personal information and account settings.
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


            <!-- =========================================
                 PROFILE CONTENT
            ========================================== -->
            <section class="customer-profile-content">


                <!-- =====================================
                     PROFILE CARD
                ====================================== -->
                <div class="customer-profile-card">

                    <!-- Cover -->
                    <div class="profile-cover">

                        <div
                            class="profile-cover-decoration decoration-one"
                        ></div>

                        <div
                            class="profile-cover-decoration decoration-two"
                        ></div>

                        <div
                            class="profile-cover-decoration decoration-three"
                        ></div>

                    </div>


                    <!-- Profile Summary -->
                    <div class="profile-summary-body">

                        <div class="profile-summary-header">

                      <div class="profile-large-avatar">

    @if(auth()->user()->profile_image)

        <img
            src="{{ asset('storage/' . auth()->user()->profile_image) }}"
            alt="{{ auth()->user()->name ?? 'Customer' }}"
        >

    @else

        <i class="fa-solid fa-user"></i>

    @endif

</div>

                            <!-- User Info -->
                            <div class="profile-summary-info">

                                <h2>
                                    {{ auth()->user()->name ?? 'Customer' }}
                                </h2>

                                <p>
                                    Customer Account
                                </p>

                                <span class="profile-status">

                                    <span class="status-dot"></span>

                                    Active

                                </span>

                            </div>


                            <!-- Edit Button -->
                            <a
                                href="{{ route('customer.profile.edit') }}"
                                class="profile-top-edit"
                            >
                                <i class="fa-solid fa-pen"></i>

                                <span>
                                    Edit Profile
                                </span>
                            </a>

                        </div>


                        <!-- =================================
                             CONTACT INFORMATION
                        ================================== -->
                        <div class="profile-contact-list">


                            <!-- Email -->
                            <div class="profile-contact-item">

                                <div class="profile-contact-icon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>

                                <div class="profile-contact-text">

                                    <span>
                                        Email Address
                                    </span>

                                    <strong>
                                        {{ auth()->user()->email ?? 'Not provided' }}
                                    </strong>

                                </div>

                            </div>


                            <!-- Phone -->
                            <div class="profile-contact-item">

                                <div class="profile-contact-icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>

                                <div class="profile-contact-text">

                                    <span>
                                        Phone Number
                                    </span>

                                    <strong>
                                        {{ auth()->user()->phone ?? 'Not provided' }}
                                    </strong>

                                </div>

                            </div>


                            <!-- City -->
                            <div class="profile-contact-item">

                                <div class="profile-contact-icon">
                                    <i class="fa-solid fa-city"></i>
                                </div>

                                <div class="profile-contact-text">

                                    <span>
                                        City
                                    </span>

                                    <strong>
                                        {{ auth()->user()->city ?? 'Not provided' }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        <!-- =================================
                             ADDRESS
                        ================================== -->
                        <div class="profile-address-section">

                            <div class="profile-address-label">

                                <i class="fa-solid fa-location-dot"></i>

                                <span>
                                    Address
                                </span>

                            </div>

                            <div class="profile-address-value">

                                {{ auth()->user()->address ?? 'No address provided' }}

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =====================================
                     ACCOUNT SECURITY
                ====================================== -->
                <div class="profile-security-card">

                    <div class="profile-card-header">

                        <div class="profile-security-header-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>

                        <div>

                            <h3>
                                Account Security
                            </h3>

                            <p>
                                Keep your account secure and protected.
                            </p>

                        </div>

                    </div>


                    <div class="profile-security-item">

                        <div class="security-info">

                            <div class="security-icon">
                                <i class="fa-solid fa-lock"></i>
                            </div>

                            <div>

                                <strong>
                                    Password
                                </strong>

                                <span>
                                    Your password is securely protected.
                                </span>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="security-button"
                        >
                            Change Password
                        </button>

                    </div>

                </div>


                <!-- =====================================
                     INFORMATION NOTE
                ====================================== -->
                <div class="profile-edit-note">

                    <div class="profile-note-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>

                        <strong>
                            Keep your information up to date
                        </strong>

                        <p>
                            Accurate contact information helps QuickFix providers
                            contact you about your service bookings.
                        </p>

                    </div>

                </div>

            </section>

        </main>

    </div>


    <!-- =========================================
         JAVASCRIPT
    ========================================== -->

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

    <!-- Global JS -->
    <script src="{{ asset('js/quickfix.js') }}"></script>

    <!-- Customer Shared JS -->
    <script src="{{ asset('js/customer.js') }}"></script>

    <!-- Profile Specific JS -->
    <script src="{{ asset('js/customer-profile.js') }}"></script>

</body>

</html>