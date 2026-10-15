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
        QuickFix - Provider Notifications
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


        <!-- =================================================
             SIDEBAR HEADER
        ================================================== -->

        <div class="provider-sidebar-header">


            <!-- QUICKFIX LOGO -->

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


            <!-- HAMBURGER -->

            <button
                type="button"
                class="sidebar-toggle"
                aria-label="Collapse sidebar"
                title="Collapse sidebar"
            >

                <i class="fa-solid fa-bars"></i>

            </button>

        </div>



        <!-- =================================================
             PROVIDER PROFILE
        ================================================== -->

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



        <!-- =================================================
             SIDEBAR MENU
        ================================================== -->

        <nav class="provider-sidebar-menu">


            <!-- DASHBOARD -->

            <a
                href="{{ route('provider.dashboard') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-house"></i>

                <span class="provider-menu-text">

                    Dashboard

                </span>

            </a>



            <!-- MY BOOKINGS -->

            <a
                href="{{ route('provider.bookings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-calendar-check"></i>

                <span class="provider-menu-text">

                    My Bookings

                </span>

            </a>



            <!-- MY JOBS -->

            <a
                href="{{ route('provider.jobs') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-screwdriver-wrench"></i>

                <span class="provider-menu-text">

                    My Jobs

                </span>

            </a>



            <!-- EARNINGS -->

            <a
                href="{{ route('provider.earnings') }}"
                class="provider-menu-link"
            >

                <i class="fa-solid fa-wallet"></i>

                <span class="provider-menu-text">

                    Earnings

                </span>

            </a>



            <a href="{{ route('provider.chats') }}" class="provider-menu-link">
                    <i class="fa-solid fa-comments"></i>

                    <span class="provider-menu-text">
                        Chats
                    </span>

                    <span class="provider-chat-badge" id="providerSidebarChatUnread" style="display: none;">
                        0
                    </span>
                </a>


            <!-- PROFILE -->

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



        <!-- =================================================
             SIDEBAR BOTTOM
        ================================================== -->

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
         MAIN CONTENT
    ====================================================== -->

    <main class="provider-main">


        <!-- =================================================
             TOP HEADER
        ================================================== -->

        <header class="provider-header">


            <div class="provider-header-left">


                <!-- MOBILE MENU -->

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
                        Notifications
                    </h1>

                    <p>
                        Stay updated with your customer bookings.
                    </p>

                </div>

            </div>



            <!-- =================================================
                 HEADER ACTIONS
            ================================================== -->

            <div class="provider-header-actions">


                <!-- =================================================
                     NOTIFICATION BELL
                ================================================== -->

                <div class="provider-notification-wrapper">


                    <button
                        type="button"
                        class="provider-notification-button"
                        id="providerNotificationButton"
                        aria-label="Notifications"
                        title="Notifications"
                    >

                        <i class="fa-solid fa-bell"></i>


                        @if(
                            auth()->user()
                                ->unreadNotifications()
                                ->count() > 0
                        )

                            <span
                                class="provider-notification-badge"
                                id="providerNotificationBadge"
                            >

                                {{
                                    auth()->user()
                                        ->unreadNotifications()
                                        ->count() > 99
                                        ? '99+'
                                        : auth()->user()
                                            ->unreadNotifications()
                                            ->count()
                                }}

                            </span>

                        @endif

                    </button>



                    <!-- =================================================
                         NOTIFICATION DROPDOWN
                    ================================================== -->

                    <div
                        class="provider-notification-dropdown"
                        id="providerNotificationDropdown"
                    >


                        <!-- DROPDOWN HEADER -->

                        <div class="provider-notification-header">


                            <div>

                                <h4>
                                    Notifications
                                </h4>


                                <span
                                    id="providerNotificationUnreadText"
                                >

                                    {{
                                        auth()->user()
                                            ->unreadNotifications()
                                            ->count()
                                    }}
                                    unread

                                </span>

                            </div>


                            <a
                                href="{{ route('provider.notifications') }}"
                            >

                                View All

                            </a>

                        </div>



                        <!-- =================================================
                             NOTIFICATION LIST
                        ================================================== -->

                        <div
                            class="provider-notification-list"
                            id="providerNotificationList"
                        >


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

                                    $bookingId =
                                        $data['booking_id']
                                        ?? null;

                                @endphp


                                <div
                                    class="provider-notification-item unread"
                                    data-notification-id="{{ $notification->id }}"
                                >


                                    <!-- CONTENT -->

                                    <a
                                        href="{{
                                            $bookingId
                                                ? route(
                                                    'provider.bookings.show',
                                                    $bookingId
                                                )
                                                : '#'
                                        }}"
                                        class="provider-notification-link"
                                    >

                                        <div
                                            class="provider-notification-content"
                                        >

                                            <strong>

                                                {{
                                                    $data['title']
                                                    ?? 'Notification'
                                                }}

                                            </strong>


                                            <p>

                                                {{
                                                    $data['message']
                                                    ?? ''
                                                }}

                                            </p>


                                            <small>

                                                {{
                                                    $notification
                                                        ->created_at
                                                        ->diffForHumans()
                                                }}

                                            </small>

                                        </div>

                                    </a>



                                    <!-- ACTIONS -->

                                    <div
                                        class="provider-notification-actions"
                                    >

                                        <button
                                            type="button"
                                            class="provider-mark-read-btn"
                                            data-id="{{ $notification->id }}"
                                        >

                                            Read

                                        </button>


                                        <button
                                            type="button"
                                            class="provider-delete-notification-btn"
                                            data-id="{{ $notification->id }}"
                                            aria-label="Delete notification"
                                            title="Delete notification"
                                        >

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </div>


                                </div>


                            @empty


                                <div
                                    class="provider-notification-empty"
                                >

                                    No new notifications

                                </div>


                            @endforelse

                        </div>



                        <!-- =================================================
                             DROPDOWN FOOTER
                        ================================================== -->

                        <div
                            class="provider-notification-footer"
                        >

                            <a
                                href="{{ route('provider.notifications') }}"
                            >

                                View All Notifications

                            </a>

                        </div>


                    </div>

                </div>



                <!-- =================================================
                     ONLINE / OFFLINE
                ================================================== -->

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



                <!-- =================================================
                     DARK / LIGHT MODE
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



        <!-- =================================================
             NOTIFICATIONS CONTENT
        ================================================== -->

        <div class="provider-content">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div
                class="d-flex justify-content-between align-items-center mb-4"
            >


                <div>

                    <h2>
                        All Notifications
                    </h2>


                    <p class="text-muted mb-0">

                        Booking updates and customer requests.

                    </p>

                </div>


                @if($unreadCount > 0)

                    <span
                        class="badge bg-primary"
                        id="providerUnreadBadge"
                    >

                        {{ $unreadCount }}
                        Unread

                    </span>

                @endif

            </div>



            <!-- =================================================
                 MARK ALL AS READ
            ================================================== -->

            @if($unreadCount > 0)

                <div class="text-end mb-3">


                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="providerMarkAllReadBtn"
                    >

                        <i
                            class="fa-solid fa-check-double me-1"
                        ></i>

                        Mark all as read

                    </button>

                </div>

            @endif



            <!-- =================================================
                 ALL NOTIFICATIONS
            ================================================== -->

            <div class="card border-0 shadow-sm">


                <div class="card-body p-0">


                    @forelse($notifications as $notification)


                        @php

                            $data =
                                $notification->data;

                            $isUnread =
                                is_null(
                                    $notification->read_at
                                );

                            $bookingId =
                                $data['booking_id']
                                ?? null;

                            $title =
                                $data['title']
                                ?? 'Notification';

                            $message =
                                $data['message']
                                ?? '';

                            $type =
                                $data['type']
                                ?? 'booking_update';

                        @endphp


                        <div
                            class="
                                provider-notification-page-item
                                {{ $isUnread ? 'unread' : '' }}
                            "
                            data-id="{{ $notification->id }}"
                        >


                            <div
                                class="d-flex align-items-start p-3"
                            >


                                <!-- =================================================
                                     ICON
                                ================================================== -->

                                <div
                                    class="
                                        provider-notification-page-icon
                                        me-3
                                    "
                                >


                                    @if($type === 'booking_created')

                                        <i
                                            class="fa-solid fa-calendar-plus"
                                        ></i>


                                    @elseif($type === 'booking_rejected')

                                        <i
                                            class="fa-solid fa-circle-xmark"
                                        ></i>


                                    @elseif($type === 'booking_accepted')

                                        <i
                                            class="fa-solid fa-circle-check"
                                        ></i>


                                    @elseif($type === 'on_the_way')

                                        <i
                                            class="fa-solid fa-car"
                                        ></i>


                                    @elseif($type === 'arrived')

                                        <i
                                            class="fa-solid fa-location-dot"
                                        ></i>


                                    @elseif($type === 'in_progress')

                                        <i
                                            class="fa-solid fa-screwdriver-wrench"
                                        ></i>


                                    @elseif($type === 'completed')

                                        <i
                                            class="fa-solid fa-check"
                                        ></i>


                                    @elseif($type === 'timeout')

                                        <i
                                            class="fa-solid fa-clock"
                                        ></i>


                                    @else

                                        <i
                                            class="fa-solid fa-bell"
                                        ></i>

                                    @endif

                                </div>



                                <!-- =================================================
                                     CONTENT
                                ================================================== -->

                                <div class="flex-grow-1">


                                    <div
                                        class="
                                            d-flex
                                            justify-content-between
                                        "
                                    >


                                        <h6 class="fw-bold mb-1">

                                            {{ $title }}

                                        </h6>


                                        @if($isUnread)

                                            <span
                                                class="provider-unread-dot"
                                            ></span>

                                        @endif

                                    </div>



                                    @if($bookingId)

                                        <a
                                            href="{{
                                                route(
                                                    'provider.bookings.show',
                                                    $bookingId
                                                )
                                            }}"
                                            class="provider-notification-link"
                                            data-notification-id="{{ $notification->id }}"
                                        >

                                            <p
                                                class="
                                                    mb-1
                                                    text-muted
                                                "
                                            >

                                                {{ $message }}

                                            </p>

                                        </a>

                                    @else

                                        <p
                                            class="
                                                mb-1
                                                text-muted
                                            "
                                        >

                                            {{ $message }}

                                        </p>

                                    @endif


                                    <small class="text-muted">

                                        {{
                                            $notification
                                                ->created_at
                                                ->diffForHumans()
                                        }}

                                    </small>

                                </div>



                                <!-- =================================================
                                     ACTIONS
                                ================================================== -->

                                <div
                                    class="
                                        ms-3
                                        d-flex
                                        align-items-center
                                        gap-2
                                    "
                                >


                                    @if($isUnread)

                                        <button
                                            type="button"
                                            class="
                                                btn
                                                btn-sm
                                                btn-light
                                                provider-page-mark-read-btn
                                            "
                                            data-id="{{ $notification->id }}"
                                        >

                                            <i
                                                class="fa-solid fa-check"
                                            ></i>

                                            Read

                                        </button>


                                    @else

                                        <span
                                            class="text-muted small"
                                        >

                                            Read

                                        </span>

                                    @endif



                                    <button
                                        type="button"
                                        class="
                                            btn
                                            btn-sm
                                            btn-outline-danger
                                            provider-page-delete-btn
                                        "
                                        data-id="{{ $notification->id }}"
                                        aria-label="Delete notification"
                                        title="Delete notification"
                                    >

                                        <i
                                            class="fa-solid fa-trash"
                                        ></i>

                                    </button>


                                </div>

                            </div>

                        </div>


                    @empty


                        <!-- =================================================
                             EMPTY STATE
                        ================================================== -->

                        <div class="text-center py-5">


                            <i
                                class="
                                    fa-regular
                                    fa-bell-slash
                                    fa-2x
                                    text-muted
                                    mb-3
                                "
                            ></i>


                            <h6>

                                No notifications

                            </h6>


                            <p class="text-muted mb-0">

                                You don't have any notifications yet.

                            </p>

                        </div>


                    @endforelse

                </div>

            </div>



            <!-- =================================================
                 PAGINATION
            ================================================== -->

            @if($notifications->hasPages())

                <div class="mt-4">

                    {{ $notifications->links() }}

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
     QUICKFIX JS
====================================================== -->

<script
    src="{{ asset('js/quickfix.js') }}"
></script>



<!-- =====================================================
     PROVIDER JS
====================================================== -->

<script
    src="{{ asset('js/provider.js') }}"
></script>


</body>

</html>