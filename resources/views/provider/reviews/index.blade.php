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
        QuickFix - Reviews & Ratings
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


    <!-- =====================================================
         REVIEW PAGE CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/provider-reviews.css') }}"
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
                class="provider-menu-link active"
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
                        Reviews & Ratings
                    </h1>

                    <p>
                        View customer ratings and reviews for your completed services.
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

        <div class="provider-content provider-review-content">


            <!-- =====================================================
                 FLASH MESSAGE
            ====================================================== -->

            @if(session('success'))

                <div
                    class="alert alert-success provider-review-alert"
                    id="reviewSuccessAlert"
                >

                    <i class="fa-solid fa-circle-check me-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            @if(session('error'))

                <div
                    class="alert alert-danger provider-review-alert"
                >

                    <i class="fa-solid fa-circle-xmark me-2"></i>

                    {{ session('error') }}

                </div>

            @endif


            <!-- =====================================================
                 PAGE INTRO
            ====================================================== -->

            <div class="provider-review-page-heading">

                <div>

                    <div class="provider-review-title-row">

                        <div class="provider-review-title-icon">

                            <i class="fa-solid fa-star"></i>

                        </div>

                        <div>

                            <h2>
                                Customer Reviews
                            </h2>

                            <p>
                                See who rated your service and what customers said about their experience.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 RATING SUMMARY
            ====================================================== -->

            <div class="review-summary-card">

                <div class="row align-items-center">


                    <!-- OVERALL RATING -->

                    <div class="col-lg-4 col-md-5">

                        <div class="review-overall">

                            <div class="review-summary-rating">

                                {{ number_format((float) $averageRating, 2) }}

                            </div>


                            <div class="review-stars review-summary-stars">

                                @for($i = 1; $i <= 5; $i++)

                                    @if($i <= round((float) $averageRating))

                                        <i class="fa-solid fa-star"></i>

                                    @else

                                        <i class="fa-regular fa-star"></i>

                                    @endif

                                @endfor

                            </div>


                            <div class="review-total">

                                Based on

                                <strong>
                                    {{ $totalReviews }}
                                </strong>

                                review{{ $totalReviews == 1 ? '' : 's' }}

                                @if($cancellationCount > 0)

                                    <span>
                                        and

                                        <strong>
                                            {{ $cancellationCount }}
                                        </strong>

                                        cancellation{{ $cancellationCount == 1 ? '' : 's' }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    <!-- RATING BREAKDOWN -->

                    <div class="col-lg-8 col-md-7">

                        <div class="review-rating-breakdown">

                            @for($rating = 5; $rating >= 1; $rating--)

                                @php

                                    $count = $ratingCounts[$rating] ?? 0;

                                    $percentage = $totalReviews > 0
                                        ? ($count / $totalReviews) * 100
                                        : 0;

                                @endphp


                                <div class="review-rating-row">

                                    <div class="review-rating-label">

                                        <span>
                                            {{ $rating }}
                                        </span>

                                        <i class="fa-solid fa-star"></i>

                                    </div>


                                    <div class="review-progress">

                                        <div
                                            class="review-progress-bar"
                                            style="width: {{ $percentage }}%;"
                                        ></div>

                                    </div>


                                    <span class="review-rating-count">

                                        {{ $count }}

                                    </span>

                                </div>

                            @endfor

                        </div>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 REVIEW HISTORY HEADER
            ====================================================== -->

            <div class="review-history-header">

                <div>

                    <h3>
                        Review History
                    </h3>

                    <p>
                        Customer feedback received from your completed bookings.
                    </p>

                </div>


                <div class="review-history-count">

                    <i class="fa-solid fa-comments"></i>

                    {{ $totalReviews }}

                </div>

            </div>


            <!-- =====================================================
                 REVIEW HISTORY
            ====================================================== -->

            <div class="provider-review-list">

                @forelse($reviews as $review)


                    <article
                        class="provider-review-card"
                        data-review-id="{{ $review->id }}"
                    >


                        <!-- REVIEW HEADER -->

                        <div class="review-header">


                            <!-- CUSTOMER -->

                            <div class="review-customer-section">

                                <div class="review-customer-avatar">

                                    <i class="fa-solid fa-user"></i>

                                </div>


                                <div>

                                    <div class="review-customer">

                                        {{ $review->booking?->customer?->user?->name ?? 'Customer' }}

                                    </div>


                                    <div class="review-meta">

                                        <span>
                                            Customer
                                        </span>

                                        <span class="review-meta-separator">
                                            •
                                        </span>

                                        <span class="review-direction">
                                            Customer → You
                                        </span>

                                        <span class="review-meta-separator">
                                            •
                                        </span>

                                        <span>
                                            Booking #{{ $review->booking_id }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- RATING + DELETE -->

                            <div class="review-actions">


                                <div class="review-stars">

                                    @for($i = 1; $i <= 5; $i++)

                                        @if($i <= $review->rating)

                                            <i class="fa-solid fa-star"></i>

                                        @else

                                            <i class="fa-regular fa-star"></i>

                                        @endif

                                    @endfor

                                </div>


                                <span class="review-rating-number">

                                    {{ $review->rating }}/5

                                </span>


                                <!-- HIDE FROM PROVIDER -->

                                <form
                                    method="POST"
                                    action="{{ route('provider.reviews.destroy', $review->id) }}"
                                    class="review-delete-form"
                                    onsubmit="return confirm('Hide this review from your review history? The review will not be deleted from the database.');"
                                >

                                    @csrf

                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="review-delete-button"
                                        title="Hide review from your history"
                                        aria-label="Hide review from your history"
                                    >

                                        <i class="fa-solid fa-eye-slash"></i>

                                    </button>

                                </form>

                            </div>

                        </div>


                        <!-- DATE -->

                        <div class="review-meta review-date">

                            <i class="fa-regular fa-calendar me-1"></i>

                            {{ $review->created_at->format('d M Y, h:i A') }}

                        </div>


                        <!-- COMMENT -->

                        @if($review->comment)

                            <div class="review-comment-box">

                                <i class="fa-solid fa-quote-left"></i>

                                <p class="review-comment">

                                    {{ $review->comment }}

                                </p>

                            </div>

                        @else

                            <div class="review-no-comment">

                                <i class="fa-regular fa-comment"></i>

                                <span>
                                    No written comment.
                                </span>

                            </div>

                        @endif


                    </article>


                @empty


                    <!-- =================================================
                         EMPTY STATE
                    ================================================== -->

                    <div class="empty-reviews">

                        <div class="empty-review-icon">

                            <i class="fa-regular fa-star"></i>

                        </div>


                        <h3>
                            No Reviews Yet
                        </h3>


                        <p>

                            Customer reviews will appear here after
                            completed services.

                        </p>

                    </div>

                @endforelse

            </div>


            <!-- =====================================================
                 PAGINATION
            ====================================================== -->

            @if($reviews->hasPages())

                <div class="provider-review-pagination">

                    {{ $reviews->links() }}

                </div>

            @endif


        </div>

    </main>

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


<!-- =====================================================
     REVIEW PAGE JS
====================================================== -->

<script
    src="{{ asset('js/provider-reviews.js') }}"
></script>


</body>

</html>