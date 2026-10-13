
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

        <title>Chats - QuickFix</title>


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


        <!-- QuickFix CSS -->
        <link
            rel="stylesheet"
            href="{{ asset('css/quickfix.css') }}"
        >


        <!-- Provider CSS -->
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


            {{-- =========================================================
     SIDEBAR
========================================================== --}}

<aside class="provider-sidebar">

    {{-- Sidebar Header --}}

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


    {{-- Provider Profile --}}

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


    {{-- Sidebar Menu --}}

    <nav class="provider-sidebar-menu">


        {{-- Dashboard --}}

        <a
            href="{{ route('provider.dashboard') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-house"></i>

            <span class="provider-menu-text">
                Dashboard
            </span>

        </a>


        {{-- My Bookings --}}

        <a
            href="{{ route('provider.bookings') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-calendar-check"></i>

            <span class="provider-menu-text">
                My Bookings
            </span>

        </a>


        {{-- My Jobs --}}

        <a
            href="{{ route('provider.jobs') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-screwdriver-wrench"></i>

            <span class="provider-menu-text">
                My Jobs
            </span>

        </a>


        {{-- Earnings --}}

        <a
            href="{{ route('provider.earnings') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-wallet"></i>

            <span class="provider-menu-text">
                Earnings
            </span>

        </a>


        {{-- Payments --}}

        <a
            href="{{ route('provider.payments.index') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-credit-card"></i>

            <span class="provider-menu-text">
                Payments
            </span>

        </a>


        {{-- Chats - ACTIVE --}}

        <a
            href="{{ route('provider.chats') }}"
            class="provider-menu-link active"
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


        {{-- Reviews & Ratings --}}

        <a
            href="{{ route('provider.reviews.index') }}"
            class="provider-menu-link"
        >

            <i class="fa-solid fa-star"></i>

            <span class="provider-menu-text">
                Reviews & Ratings
            </span>

        </a>


        {{-- My Profile --}}

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


    {{-- Sidebar Bottom --}}

   <div class="provider-sidebar-bottom">

                <form action="{{ route('logout') }}" method="POST" class="w-100 m-0 p-0">
                    @csrf
                    <button type="submit" class="provider-menu-link provider-logout border-0 bg-transparent w-100 text-start d-flex align-items-center" style="cursor: pointer;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="provider-menu-text logout-text">
                            Logout
                        </span>
                    </button>
                </form>

            </div>

</aside>


            <!-- Mobile Overlay -->

            <div
                class="provider-overlay"
                id="providerOverlay"
            ></div>


            <!-- =========================================================
             MAIN
        ========================================================== -->

            <main class="provider-main">


                <!-- =====================================================
                 HEADER
            ====================================================== -->

                <header class="provider-header">


                    <!-- Header Left -->

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
                                Chats
                            </h1>

                            <p>
                                Communicate with your customers.
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
                                    class="status-toggle"
                                    checked
                                >

                                <span class="status-slider"></span>

                            </label>

                        </div>


                        <!-- Theme Toggle -->

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


                    <!-- =================================================
                     CHAT AREA
                ================================================== -->

                    <div class="provider-chat-container">


                        <!-- =================================================
                         CONVERSATION LIST
                    ================================================== -->

                        <div class="provider-chat-list">


                            <!-- Search -->

                            <div class="provider-chat-search">

                                <i class="fa-solid fa-magnifying-glass"></i>

                                <input
                                    type="text"
                                    placeholder="Search conversations..."
                                >

                            </div>


                            <!-- Customers -->

                            @forelse($customers as $customer)

                                @php
                                    $customerUserId = $customer->id;

                                    $latestChat = \App\Models\Chat::where(function ($query) use ($customerUserId) {
                                        $query->where('sender_id', auth()->id())
                                            ->where('receiver_id', $customerUserId);
                                    })
                                        ->orWhere(function ($query) use ($customerUserId) {
                                            $query->where('sender_id', $customerUserId)
                                                ->where('receiver_id', auth()->id());
                                        })
                                        ->latest()
                                        ->first();

                                    $unreadCount = \App\Models\Chat::where('sender_id', $customerUserId)
                                        ->where('receiver_id', auth()->id())
                                        ->where('is_read', false)
                                        ->count();
                                @endphp


                                <a
                                    href="{{ route('provider.chats', ['customer_id' => $customer->id]) }}"
                                    class="provider-chat-user {{ $activeCustomer && $activeCustomer->id == $customer->id ? 'active' : '' }}"
                                    data-customer-id="{{ $customer->id }}"
                                >

                                    <div class="provider-chat-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </div>


                                    <div class="provider-chat-user-info">

                                        <div>

                                            <h4>
                                                {{ $customer->name }}
                                            </h4>

                                            <span>
                                                {{ $latestChat ? $latestChat->created_at->format('g:i A') : '' }}
                                            </span>

                                        </div>


                                        <p>
                                            {{ $latestChat ? $latestChat->message : 'Start a conversation' }}
                                        </p>

                                    </div>


                                    <span
                                        class="provider-customer-chat-unread"
                                        data-customer-id="{{ $customer->id }}"
                                        style="{{ $unreadCount > 0 ? '' : 'display: none;' }}"
                                    >
                                        {{ $unreadCount > 0 ? ($unreadCount > 99 ? '99+' : $unreadCount) : '' }}
                                    </span>

                                </a>


                            @empty

                                <p>
                                    No conversations yet.
                                </p>

                            @endforelse
                        </div>


                        <!-- =================================================
                         CHAT WINDOW
                    ================================================== -->

                        <div class="provider-chat-window">


                            <!-- =================================================
                             CHAT HEADER
                        ================================================== -->

                            @if($activeCustomer)

                                <div class="provider-chat-header">


                                    <div class="provider-chat-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </div>


                                    <div>

                                        <h4>
                                            {{ $activeCustomer->name }}
                                        </h4>


                                        <span>

                                            <i class="fa-solid fa-circle"></i>

                                            Customer

                                        </span>

                                    </div>

                                </div>

                            @endif


                            <!-- =================================================
                             MESSAGES
                        ================================================== -->

                            <div
                                class="provider-chat-messages"
                                id="providerChatMessages"
                                @if($activeCustomer)
                                    data-fetch-url="{{ route('provider.chats.messages', $activeCustomer->id) }}"
                                @endif
                            >


                                @forelse($messages as $message)

                                    <div
                                        class="provider-message {{ $message->sender_id == auth()->id() ? 'provider' : 'customer' }}"
                                        data-message-id="{{ $message->id }}"
                                    >


                                        <div class="provider-message-bubble">

                                            {{ $message->message }}

                                        </div>


                                        <span>

                                            {{ $message->created_at->format('g:i A') }}

                                        </span>

                                    </div>

                                @empty

                                    <div
                                        class="provider-chat-empty text-center text-muted p-4"
                                    >

                                        No messages yet.
                                        Start the conversation.

                                    </div>

                                @endforelse

                            </div>


                            <!-- =================================================
                             MESSAGE INPUT
                        ================================================== -->

                            @if($activeCustomer)

                                <form
                                    action="{{ route('provider.chats.send', $activeCustomer->id) }}"
                                    method="POST"
                                    class="provider-chat-input"
                                    id="providerChatForm"
                                >

                                    @csrf


                                    <!-- Attachment Button -->

                                    <button
                                        type="button"
                                        title="Attach file"
                                    >

                                        <i class="fa-solid fa-paperclip"></i>

                                    </button>


                                    <!-- Message Input -->

                                    <input
                                        type="text"
                                        name="message"
                                        id="providerMessageInput"
                                        placeholder="Type a message..."
                                        autocomplete="off"
                                        required
                                    >


                                    <!-- Send Button -->

                                    <button
                                        type="submit"
                                        class="provider-chat-send"
                                        id="providerChatSend"
                                        title="Send message"
                                    >

                                        <i class="fa-solid fa-paper-plane"></i>

                                    </button>

                                </form>

                            @endif

                        </div>

                    </div>

                </div>

            </main>

        </div>


        <!-- =============================================================
         JAVASCRIPT
    ============================================================== -->


        <!-- Bootstrap JS -->

        <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


        <!-- Existing Provider JavaScript -->

        <script
        src="{{ asset('js/provider.js') }}"
    ></script>


        <!-- Current Logged-in Provider User ID -->

        <script>
        window.providerUserId = {{ auth()->id() }};
    </script>


        <!-- Provider Chat JavaScript -->

        <script
        src="{{ asset('js/provider-chat.js') }}"
    ></script>


    </body>

</html>
