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

    <title>
        My Profile - QuickFix
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


@php

    $user = $provider->user;

    /*
    |--------------------------------------------------------------------------
    | Provider Online / Offline Status
    |--------------------------------------------------------------------------
    */

    $isOnline = ($provider->availability_status ?? 'offline') === 'online';


    /*
    |--------------------------------------------------------------------------
    | Provider Account Status
    |--------------------------------------------------------------------------
    */

    $isActive = (bool) ($user?->is_active ?? false);


    /*
    |--------------------------------------------------------------------------
    | Profile Image
    |--------------------------------------------------------------------------
    */

    $profileImage = $user?->profile_image
        ? asset('storage/' . $user->profile_image)
        : null;


    /*
    |--------------------------------------------------------------------------
    | Provider Services
    |--------------------------------------------------------------------------
    */

    $services = $provider->services ?? collect();

@endphp


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

                @if($profileImage)

                    <img
                        src="{{ $profileImage }}"
                        alt="{{ $user?->name ?? 'Provider' }}"
                    >

                @else

                    <i class="fa-solid fa-user"></i>

                @endif

            </div>


            <div class="provider-profile-info">

                <h4>
                    {{ $user?->name ?? 'Provider' }}
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


            <!-- My Profile - ACTIVE -->

            <a
                href="{{ route('provider.profile') }}"
                class="provider-menu-link active"
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

            <a
                href="{{ route('logout') }}"
                class="provider-menu-link provider-logout"
                onclick="
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                "
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                <span class="provider-menu-text logout-text">
                    Logout
                </span>

            </a>


            <form
                id="logout-form"
                action="{{ route('logout') }}"
                method="POST"
                style="display:none;"
            >

                @csrf

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
             HEADER
        ====================================================== -->

        <header class="provider-header">


            <div class="provider-header-left">


                <!-- Mobile Menu -->

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
                        My Profile
                    </h1>

                    <p>
                        Manage your provider profile and account information.
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


                <!-- =================================================
                     ONLINE STATUS
                ================================================== -->

                <div class="provider-status">

                    

                </div>


                <!-- =================================================
                     THEME TOGGLE
                ================================================== -->

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
        ====================================================== -->

        <div class="provider-content">
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </div>
@endif



            <!-- =====================================================
                 PROFILE CARD
            ====================================================== -->

            <div class="provider-profile-card">


                <!-- Profile Header -->

                <div class="provider-profile-card-top">


                    <!-- PROFILE IMAGE -->

                    <div class="provider-profile-large-avatar">

                        @if($profileImage)

                            <img
                                src="{{ $profileImage }}"
                                alt="{{ $user?->name ?? 'Provider' }}"
                            >

                        @else

                            <i class="fa-solid fa-user"></i>

                        @endif

                    </div>


                    <!-- PROFILE INFORMATION -->

                    <div class="provider-profile-card-info">

                        <h3>
                            {{ $user?->name ?? 'Provider' }}
                        </h3>

                        <p>
                            Service Provider
                        </p>


                        <!-- ACCOUNT STATUS -->

                        <span
                            class="provider-service-status {{ $isActive ? 'active' : 'inactive' }}"
                        >

                            <span class="provider-status-dot"></span>

                            {{ $isActive ? 'Active' : 'Inactive' }}

                        </span>

                    </div>

                </div>


                <!-- =================================================
                     PROFILE INFORMATION
                ================================================== -->

                <div class="provider-profile-form">


                    <!-- Full Name -->

                    <div class="provider-form-group">

                        <label>
                            Full Name
                        </label>

                        <input
                            type="text"
                            value="{{ $user?->name ?? 'Provider' }}"
                            readonly
                        >

                    </div>


                    <!-- Email -->

                    <div class="provider-form-group">

                        <label>
                            Email Address
                        </label>

                        <input
                            type="email"
                            value="{{ $user?->email ?? 'No email provided' }}"
                            readonly
                        >

                    </div>


                    <!-- Phone -->

                    <div class="provider-form-group">

                        <label>
                            Phone Number
                        </label>

                        <input
                            type="text"
                            value="{{ $user?->phone ?? 'No phone provided' }}"
                            readonly
                        >

                    </div>

<!-- City -->

<div class="provider-form-group">

    <label>
        City
    </label>

    <input
        type="text"
        value="{{ $provider->serviceArea?->city?->name ?? 'No city assigned' }}"
        readonly
    >

</div>


<!-- Service Area -->

<div class="provider-form-group">

    <label>
        Service Area
    </label>

    <input
        type="text"
        value="{{ $provider->serviceArea?->name ?? 'No service area assigned' }}"
        readonly
    >

</div>


                    <!-- Main Service -->

                    <div class="provider-form-group">

                        <label>
                            Main Services
                        </label>

                        <div class="provider-profile-services-list">

                            @forelse($services as $service)

                                <span class="provider-profile-service-tag">

                                    <i class="fa-solid fa-check me-1"></i>

                                    {{ $service->name }}

                                </span>

                            @empty

                                <span class="provider-form-help">

                                    <i class="fa-solid fa-circle-info me-1"></i>

                                    No services selected.

                                </span>

                            @endforelse

                        </div>

                    </div>


                    <!-- Experience -->

                    <div class="provider-form-group">

                        <label>
                            Experience
                        </label>

                        <input
                            type="text"
                            value="{{ $provider->experience_years ?? 0 }} Years"
                            readonly
                        >

                    </div>


                    <!-- About Me -->

                    <div class="provider-form-group provider-form-full">

                        <label>
                            About Me
                        </label>

                        <textarea
                            rows="4"
                            readonly
                        >{{ $provider->bio ?? 'No information provided.' }}</textarea>

                    </div>
                    

                </div>
                


                <!-- =================================================
                     EDIT BUTTON
                ================================================== -->

                <div class="provider-profile-form-actions">

                    <a
                        href="{{ route('provider.profile.edit') }}"
                        class="provider-primary-btn profile-edit-main-btn"
                    >

                        <i class="fa-solid fa-pen"></i>

                        Edit Profile

                    </a>

                </div>

            </div>
            <!-- END PROFILE CARD -->


            <!-- =====================================================
     NRC DOCUMENTS - VIEW ONLY
====================================================== -->

<div class="provider-profile-section provider-nrc-section">

    <!-- NRC Section Header -->

    <div class="provider-section-heading">

        <div>

            <span class="provider-section-label">
                IDENTITY VERIFICATION
            </span>

            <h3>
                NRC Documents
            </h3>

            <p>
                View your submitted NRC documents.
                Only an administrator can update these documents.
            </p>

        </div>

        <span class="badge bg-light text-dark border">
            <i class="fa-solid fa-lock me-1"></i>
            View Only
        </span>

    </div>


    <!-- NRC Documents Card -->

    <div class="provider-nrc-card">

        <div class="row g-4">

            <!-- NRC FRONT -->

            <div class="col-12 col-md-6">

                <div class="provider-nrc-item">

                    <h5>
                        <i class="fa-regular fa-id-card me-2"></i>
                        NRC Front
                    </h5>

                    @if ($provider->nrc_front_image)

                        <a
                            href="{{ route('provider.profile.nrc-image', ['side' => 'front']) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="View NRC front image"
                        >
                            <img
                                src="{{ route('provider.profile.nrc-image', ['side' => 'front']) }}"
                                alt="NRC Front"
                                class="provider-nrc-image"
                            >
                        </a>

                        <p class="provider-nrc-hint">
                            <i class="fa-solid fa-magnifying-glass-plus me-1"></i>
                            Click the image to view it larger.
                        </p>

                    @else

                        <div class="provider-nrc-unavailable">
                            <i class="fa-regular fa-image"></i>
                            <p>NRC front image is not available.</p>
                        </div>

                    @endif

                </div>

            </div>


            <!-- NRC BACK -->

            <div class="col-12 col-md-6">

                <div class="provider-nrc-item">

                    <h5>
                        <i class="fa-regular fa-id-card me-2"></i>
                        NRC Back
                    </h5>

                    @if ($provider->nrc_back_image)

                        <a
                            href="{{ route('provider.profile.nrc-image', ['side' => 'back']) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="View NRC back image"
                        >
                            <img
                                src="{{ route('provider.profile.nrc-image', ['side' => 'back']) }}"
                                alt="NRC Back"
                                class="provider-nrc-image"
                            >
                        </a>

                        <p class="provider-nrc-hint">
                            <i class="fa-solid fa-magnifying-glass-plus me-1"></i>
                            Click the image to view it larger.
                        </p>

                    @else

                        <div class="provider-nrc-unavailable">
                            <i class="fa-regular fa-image"></i>
                            <p>NRC back image is not available.</p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
<!-- END NRC DOCUMENTS -->



            <!-- =================================================
                 ACCOUNT SETTINGS
            ================================================== -->

            <div class="provider-profile-section">


                <div class="provider-section-heading">

                    <div>

                        <span class="provider-section-label">
                            ACCOUNT
                        </span>

                        <h3>
                            Account Settings
                        </h3>

                        <p>
                            Manage your account preferences.
                        </p>

                    </div>

                </div>


                <div class="provider-settings-list">


                    <!-- =================================================
                         CHANGE PASSWORD
                    ================================================== -->

                    <div class="provider-setting-item">

                        <div class="provider-setting-icon">

                            <i class="fa-solid fa-lock"></i>

                        </div>


                        <div class="provider-setting-info">

                            <h4>
                                Change Password
                            </h4>

                            <p>
                                Update your account password.
                            </p>

                        </div>


                       <button
    type="button"
    class="provider-setting-btn"
    data-bs-toggle="modal"
    data-bs-target="#changePasswordModal"
>
    Change
</button>

                    </div>


                    <!-- =================================================
                         NOTIFICATIONS
                    ================================================== -->

                    <div class="provider-setting-item">

                        <div class="provider-setting-icon">

                            <i class="fa-solid fa-bell"></i>

                        </div>


                        <div class="provider-setting-info">

                            <h4>
                                Notifications
                            </h4>

                            <p>
                                Receive booking and customer notifications.
                            </p>

                        </div>


                        <label class="provider-setting-switch">

                            <input
                                type="checkbox"
                                checked
                            >

                            <span></span>

                        </label>

                    </div>


                    <!-- =================================================
                         ACCOUNT STATUS
                    ================================================== -->

                    <div class="provider-setting-item">

                        <div class="provider-setting-icon">

                            <i class="fa-solid fa-circle-check"></i>

                        </div>


                        <div class="provider-setting-info">

                            <h4>
                                Account Status
                            </h4>

                            <p>

                                {{ $isActive
                                    ? 'Your provider account is currently active.'
                                    : 'Your provider account is currently inactive.'
                                }}

                            </p>

                        </div>


                        <span
                            class="provider-service-status {{ $isActive ? 'active' : 'inactive' }}"
                        >

                            <span class="provider-status-dot"></span>

                            {{ $isActive ? 'Active' : 'Inactive' }}

                        </span>

                    </div>

                </div>

            </div>
            <!-- END ACCOUNT SETTINGS -->


        </div>
        <!-- END PROVIDER CONTENT -->


    </main>

</div>

<!-- =====================================================
     CHANGE PASSWORD MODAL
====================================================== -->

<div
    class="modal fade"
    id="changePasswordModal"
    tabindex="-1"
    aria-labelledby="changePasswordModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="changePasswordModalLabel"
                >
                    Change Password
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


           <form
    id="changePasswordForm"
    data-url="{{ route('provider.profile.password') }}"
>

                @csrf

                <div class="modal-body">

                    <div class="mb-3">

                        <label
                            for="current_password"
                            class="form-label"
                        >
                            Current Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="current_password"
                            name="current_password"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="new_password"
                            class="form-label"
                        >
                            New Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="new_password"
                            name="new_password"
                            minlength="8"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="new_password_confirmation"
                            class="form-label"
                        >
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            minlength="8"
                            required
                        >

                    </div>


                    <div
                        id="changePasswordMessage"
                        class="alert d-none"
                    ></div>

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
                        class="btn btn-primary"
                        id="changePasswordSubmit"
                    >
                        Change Password
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
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


</body>

</html>