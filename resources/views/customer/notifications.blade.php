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

    <title>QuickFix - Notifications</title>


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

    <aside class="customer-sidebar"  id="customerSidebar">


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



        <!-- =================================================
             CUSTOMER PROFILE
        ================================================== -->

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
             SIDEBAR MENU
        ================================================== -->

        
<nav class="customer-sidebar-menu">

    <a href="{{ route('customer.dashboard') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-house"></i>
        <span class="customer-menu-text">Dashboard</span>
    </a>

    <a href="{{ route('customer.services') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-briefcase"></i>
        <span class="customer-menu-text">My Services</span>
    </a>

    <a href="{{ route('customer.bookings') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-calendar-check"></i>
        <span class="customer-menu-text">My Bookings</span>
    </a>

    <a href="{{ route('customer.payments') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-credit-card"></i>
        <span class="customer-menu-text">Payment</span>
    </a>

    <a href="{{ route('customer.chats') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-comments"></i>
        <span class="customer-menu-text">Chat</span>

        <span
            id="customerSidebarChatUnread"
            class="customer-sidebar-chat-unread"
            style="display: none;"
        ></span>
    </a>

    <a href="{{ route('customer.profile') }}"
       class="customer-menu-link">
        <i class="fa-solid fa-user-circle"></i>
        <span class="customer-menu-text">My Profile</span>
    </a>

</nav>




        <!-- =================================================
             LOGOUT
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
                        Notifications
                    </h1>

                    <p>
                        Stay updated with your bookings.
                    </p>

                </div>

            </div>



            <!-- Header Actions -->

            <!-- Header Actions -->

<div class="customer-header-actions">


    <!-- =================================================
         NOTIFICATION BELL
    ================================================== -->

    <div class="customer-notification-wrapper">

        <button
            type="button"
            class="customer-notification-button"
            id="notificationButton"
            aria-label="Notifications"
            title="Notifications"
        >

            <i class="fa-solid fa-bell"></i>

            @if($unreadCount > 0)

                <span
                    class="customer-notification-badge"
                    id="notificationBadge"
                >
                    {{ $unreadCount > 99
                        ? '99+'
                        : $unreadCount }}
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
                        {{ $unreadCount }} unread
                    </span>

                </div>


                <a
                    href="{{ route('customer.notifications') }}"
                >
                    View All
                </a>

            </div>


            <!-- Notification List -->

            <div class="customer-notification-list">

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
                            is_null(
                                $notification->read_at
                            );

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

                        </div>


                        <!-- Read -->

                       <!-- ACTIONS -->

<div class="customer-notification-actions">

    @if($isUnread)

        <button
    type="button"
    class="btn btn-sm btn-light mark-read-btn"
    data-id="{{ $notification->id }}"
    title="Mark as read"
>
    <i class="fa-solid fa-check"></i>
    Read
</button>

    @else

        <span class="text-muted small">
            Read
        </span>

    @endif


    <!-- DELETE -->

    <button
        type="button"
        class="btn btn-sm btn-outline-danger delete-notification-btn"
        data-id="{{ $notification->id }}"
        title="Delete notification"
    >
        <i class="fa-solid fa-trash"></i>
    </button>

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


    <!-- =================================================
         DARK / LIGHT MODE
    ================================================== -->

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
             NOTIFICATION CONTENT
        ================================================== -->

        <section class="customer-content">


            <!-- Page Header -->

            <div class="d-flex justify-content-between align-items-center mb-4">

              


                @if($unreadCount > 0)

                    <span
                        class="badge bg-primary"
                        id="unreadBadge"
                    >
                        {{ $unreadCount }} Unread
                    </span>

                @endif

            </div>



            <!-- Mark All -->

            @if($unreadCount > 0)

                <div class="text-end mb-3">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        id="markAllReadBtn"
                    >

                        <i class="fa-solid fa-check-double me-1"></i>

                        Mark all as read

                    </button>

                </div>

            @endif



            <!-- Notification List -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-0">


                    @forelse($notifications as $notification)

                        @php

                            $data = $notification->data;

                            $isUnread =
                                is_null($notification->read_at);

                            $bookingId =
                                $data['booking_id'] ?? null;

                            $title =
                                $data['title'] ?? 'Notification';

                            $message =
                                $data['message'] ?? '';

                            $type =
                                $data['type'] ?? 'booking_update';

                        @endphp


                        <div
                            class="notification-item
                                {{ $isUnread ? 'unread' : '' }}"
                            data-id="{{ $notification->id }}"
                        >

                            <div class="d-flex align-items-start p-3">


                                <!-- ICON -->

                                <div class="notification-icon me-3">

                                    @if($type === 'booking_created')

                                        <i class="fa-solid fa-calendar-plus"></i>

                                    @elseif($type === 'booking_accepted')

                                        <i class="fa-solid fa-circle-check"></i>

                                    @elseif($type === 'booking_rejected')

                                        <i class="fa-solid fa-circle-xmark"></i>

                                    @elseif($type === 'on_the_way')

                                        <i class="fa-solid fa-car"></i>

                                    @elseif($type === 'arrived')

                                        <i class="fa-solid fa-location-dot"></i>

                                    @elseif($type === 'in_progress')

                                        <i class="fa-solid fa-screwdriver-wrench"></i>

                                    @elseif($type === 'completed')

                                        <i class="fa-solid fa-check"></i>

                                    @elseif($type === 'timeout')

                                        <i class="fa-solid fa-clock"></i>

                                    @else

                                        <i class="fa-solid fa-bell"></i>

                                    @endif

                                </div>



                                <!-- CONTENT -->

                                <div class="flex-grow-1">

                                    <div class="d-flex justify-content-between">

                                        <h6 class="fw-bold mb-1">
                                            {{ $title }}
                                        </h6>


                                        @if($isUnread)

                                            <span
                                                class="unread-dot"
                                                title="Unread"
                                            ></span>

                                        @endif

                                    </div>


                                    <p class="mb-1 text-muted">
                                        {{ $message }}
                                    </p>


                                    <small class="text-muted">

                                        {{ $notification->created_at->diffForHumans() }}

                                    </small>

                                </div>



                              <!-- ACTION -->

<div class="ms-3 d-flex align-items-center gap-2">

    @if($isUnread)

        <button
            type="button"
            class="btn btn-sm btn-light mark-read-btn"
            data-id="{{ $notification->id }}"
        >

            <i class="fa-solid fa-check"></i>

            Read

        </button>

    @else

        <span class="text-muted small">
            Read
        </span>

    @endif


    <!-- DELETE -->

    <button
        type="button"
        class="btn btn-sm btn-outline-danger delete-notification-btn"
        data-id="{{ $notification->id }}"
        title="Delete notification"
    >

        <i class="fa-solid fa-trash"></i>

    </button>

</div>

                            </div>

                        </div>

                    @empty


                        <!-- EMPTY -->

                        <div class="text-center py-5">

                            <i
                                class="fa-regular fa-bell-slash fa-2x text-muted mb-3"
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



            <!-- Pagination -->

            @if($notifications->hasPages())

                <div class="mt-4">

                    {{ $notifications->links() }}

                </div>

            @endif


        </section>

    </main>

</div>



<!-- =====================================================
     BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- QuickFix Global JS -->

<script src="{{ asset('js/quickfix.js') }}"></script>


<!-- Customer JS -->

<script src="{{ asset('js/customer.js') }}"></script>



<!-- =====================================================
     NOTIFICATION JS
====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | MARK ONE NOTIFICATION AS READ
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.mark-read-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const notificationId =
                    this.dataset.id;

                const notification =
                    document.querySelector(
                        `.notification-item[data-id="${notificationId}"]`
                    );


                fetch(
                    `/customer/notifications/${notificationId}/read`,
                    {
                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute('content'),

                            'Accept':
                                'application/json'

                        }

                    }
                )

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {
                        return;
                    }


                    if (notification) {

                        notification
                            .classList
                            .remove('unread');


                        const readButton =
                            notification
                                .querySelector(
                                    '.mark-read-btn'
                                );


                        if (readButton) {

                            readButton.outerHTML =
                                `<span class="text-muted small">
                                    Read
                                </span>`;

                        }


                        const unreadDot =
                            notification
                                .querySelector(
                                    '.unread-dot'
                                );


                        if (unreadDot) {
                            unreadDot.remove();
                        }

                    }


                    updateUnreadCount();

                })

                .catch(error => {

                    console.error(
                        'Notification error:',
                        error
                    );

                });

            });

        });


/*
    |--------------------------------------------------------------------------
    | DELETE ONE NOTIFICATION
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-notification-btn')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const notificationId =
                    this.dataset.id;

                const notification =
                    document.querySelector(
                        `.notification-item[data-id="${notificationId}"]`
                    );

                if (!notificationId) {
                    return;
                }

                if (!confirm('Delete this notification?')) {
                    return;
                }

                fetch(
                    `/customer/notifications/${notificationId}`,
                    {
                        method: 'DELETE',

                        headers: {

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute('content'),

                            'Accept':
                                'application/json'

                        }
                    }
                )

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {
                        return;
                    }

                    if (notification) {

                        const wasUnread =
                            notification.classList
                                .contains('unread');

                        notification.remove();

                        if (wasUnread) {
                            updateUnreadCount();
                        }

                    }

                })

                .catch(error => {

                    console.error(
                        'Delete notification error:',
                        error
                    );

                });

            });

        });

    /*
    |--------------------------------------------------------------------------
    | MARK ALL AS READ
    |--------------------------------------------------------------------------
    */

    const markAllButton =
        document.getElementById(
            'markAllReadBtn'
        );


    if (markAllButton) {

        markAllButton.addEventListener(
            'click',
            function () {

                fetch(
                    '/customer/notifications/read-all',
                    {
                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute('content'),

                            'Accept':
                                'application/json'

                        }

                    }
                )

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {
                        return;
                    }


                    document
                        .querySelectorAll(
                            '.notification-item.unread'
                        )
                        .forEach(
                            function (notification) {

                                notification
                                    .classList
                                    .remove('unread');


                                const button =
                                    notification
                                        .querySelector(
                                            '.mark-read-btn'
                                        );


                                if (button) {

                                    button.outerHTML =
                                        `<span class="text-muted small">
                                            Read
                                        </span>`;

                                }


                                const dot =
                                    notification
                                        .querySelector(
                                            '.unread-dot'
                                        );


                                if (dot) {
                                    dot.remove();
                                }

                            }
                        );


                    updateUnreadCount();

                    markAllButton.remove();

                })

                .catch(error => {

                    console.error(
                        'Notification error:',
                        error
                    );

                });

            }
        );

    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE UNREAD COUNT
    |--------------------------------------------------------------------------
    */

    function updateUnreadCount()
    {

        const unreadItems =
            document.querySelectorAll(
                '.notification-item.unread'
            );


        const count =
            unreadItems.length;


        const badge =
            document.getElementById(
                'unreadBadge'
            );


        if (badge) {

            if (count > 0) {

                badge.textContent =
                    count + ' Unread';

            } else {

                badge.remove();

            }

        }


        if (count === 0) {

            const markAllButton =
                document.getElementById(
                    'markAllReadBtn'
                );

            if (markAllButton) {
                markAllButton.remove();
            }

        }

    }

});

</script>


</body>

</html>