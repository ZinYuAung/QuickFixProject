<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QuickFix - Chats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/quickfix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-chat.css') }}">
</head>
<body>

<div class="customer-layout" id="customerLayout">
    <!-- SIDEBAR -->
    <aside class="customer-sidebar" id="customerSidebar">
        <div class="customer-sidebar-header">
            <a href="{{ route('customer.dashboard')}}" class="customer-logo">
                <span class="customer-logo-icon"><i class="fa-solid fa-wrench"></i></span>
                <span class="customer-logo-text">QuickFix</span>
            </a>
            <button type="button" class="customer-sidebar-toggle" id="customerSidebarToggle" aria-label="Toggle sidebar">
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
        class="customer-menu-link active"
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
        <div class="customer-sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="customer-menu-link customer-logout">
                    <i class="fa-solid fa-right-from-bracket"></i><span class="customer-menu-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="customer-overlay" id="customerOverlay"></div>

    <!-- MAIN CONTENT -->
    <main class="customer-main">
        <header class="customer-header">
            <div class="customer-header-left">
                <button type="button" class="customer-mobile-menu" id="customerMobileMenu" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
                <div class="customer-header-title">
                    <h1>Chats</h1>
                    <p>Communicate with your service providers.</p>
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

        <section class="customer-content">
            <div class="customer-chat-container">
                
                @if($providers->isEmpty())
                    <!-- NEW CUSTOMER EMPTY STATE -->
                    <div class="col-12 d-flex flex-column align-items-center justify-content-center p-5 text-center bg-white" style="grid-column: 1 / -1;">
                        <div class="p-4 rounded-circle bg-light mb-3 text-primary">
                            <i class="fa-solid fa-comments fa-3x"></i>
                        </div>
                        <h4 class="fw-bold text-navy">No Conversations Yet</h4>
                        <p class="text-muted small">You can chat with a service provider before or after making a booking.</p>
                        <a href="{{ route('customer.services') }}" class="btn btn-primary btn-sm mt-2">Find Services</a>
                    </div>
                @else
                    <!-- CHAT LIST (DYNAMIC FROM DB) -->
                    <aside class="customer-chat-list">
                        <div class="customer-chat-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="customerChatSearch" placeholder="Search conversations..." autocomplete="off">
                        </div>

 @foreach($providers as $provider)
    @php
        $providerUserId = $provider->user_id;

        $latestChat = \App\Models\Chat::where(function ($query) use ($providerUserId) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $providerUserId);
            })
            ->orWhere(function ($query) use ($providerUserId) {
                $query->where('sender_id', $providerUserId)
                      ->where('receiver_id', auth()->id());
            })
            ->latest()
            ->first();

        $unreadCount = \App\Models\Chat::where('sender_id', $providerUserId)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();
    @endphp

<a href="{{ route('customer.chats', ['provider_id' => $provider->id]) }}"
   class="customer-chat-user text-decoration-none {{ $activeProvider && $activeProvider->id === $provider->id ? 'active' : '' }}"
   data-name="{{ $provider->user->name ?? 'Provider' }}"
   data-provider-user-id="{{ $provider->user_id }}">

<div class="customer-chat-avatar">
    @if($provider->user && $provider->user->profile_image)
        <img
            src="{{ asset('storage/' . $provider->user->profile_image) }}"
            alt="{{ $provider->user->name ?? 'Provider' }}"
        >
    @else
        <i class="fa-solid fa-user"></i>
    @endif
</div>


        <div class="customer-chat-user-info">
            <div>
                <h4>{{ $provider->user->name ?? 'Service Provider' }}</h4>

                <span>
                    {{ $latestChat ? $latestChat->created_at->format('g:i A') : '' }}
                </span>
            </div>

            <p>
                {{ $latestChat ? $latestChat->message : 'Start a conversation' }}
            </p>
        </div>

        @if($unreadCount > 0)
            <span class="customer-chat-unread">
                {{ $unreadCount }}
            </span>
        @endif

    </a>
@endforeach
                    </aside>

                    <!-- CHAT WINDOW -->
                    <section class="customer-chat-window">
                        @if($activeProvider)
                            <div class="customer-chat-header">
                                
<div class="customer-chat-avatar">
    @if($activeProvider->user && $activeProvider->user->profile_image)
        <img
            src="{{ asset('storage/' . $activeProvider->user->profile_image) }}"
            alt="{{ $activeProvider->user->name ?? 'Provider' }}"
        >
    @else
        <i class="fa-solid fa-user"></i>
    @endif
</div>

                                <div>
                                    <h4>{{ $activeProvider->user->name ?? 'Provider' }}</h4>
                                    @if($activeProvider->availability_status === 'online')
    <span class="provider-status-online">
        <i class="fa-solid fa-circle"></i> Online
    </span>
@else
    <span class="provider-status-offline">
        <i class="fa-solid fa-circle"></i> Offline
    </span>
@endif
                                </div>
                            </div>

                            <div
    class="customer-chat-messages"
    id="customerChatMessages"
    data-fetch-url="{{ route('customer.chats.messages', $activeProvider->id) }}"
>
                                @foreach($messages as $msg)
    <div
        class="customer-message {{ $msg->sender_id === auth()->id() ? 'customer' : 'provider' }}"
        data-message-id="{{ $msg->id }}"
    >
        <div class="customer-message-bubble">
            {{ $msg->message }}
        </div>

        <span>
            {{ $msg->created_at->format('g:i A') }}
        </span>
    </div>
@endforeach
                            </div>

                            <form action="{{ route('customer.chats.store', $activeProvider->id) }}" method="POST" class="customer-chat-input">
                                @csrf
                                <button type="button" class="customer-chat-attach" title="Attach file"><i class="fa-solid fa-paperclip"></i></button>
                                <input type="text" name="message" id="customerMessageInput" placeholder="Type a message..." autocomplete="off" required>
                                <button type="submit" class="customer-chat-send" id="customerChatSend" title="Send message"><i class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                Select a conversation to start chatting.
                            </div>
                        @endif
                    </section>
                @endif

            </div>
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/quickfix.js') }}"></script>
<script src="{{ asset('js/customer.js') }}"></script>

<script>
    window.customerUserId = {{ auth()->id() }};
</script>

<script src="{{ asset('js/customer-chats.js') }}"></script>

<script>
    const chatMsgBox = document.getElementById('customerChatMessages');

    if (chatMsgBox) {
        chatMsgBox.scrollTop = chatMsgBox.scrollHeight;
    }
</script>
</body>
</html>