<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QuickFix - Explore Services</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/quickfix.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>

<body>
<div class="customer-layout" id="customerLayout">

    {{-- Customer Sidebar --}}
    <aside class="customer-sidebar">
        <div class="customer-sidebar-header">
            <a href="{{ url('/') }}" class="customer-logo">
                <span class="customer-logo-icon">
                    <i class="fa-solid fa-wrench"></i>
                </span>
                <span class="customer-logo-text">QuickFix</span>
            </a>

            <button type="button" class="customer-sidebar-toggle"
                    id="customerSidebarToggle" aria-label="Toggle sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="customer-profile">
            <div class="customer-avatar">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                         alt="{{ auth()->user()->name ?? 'Customer' }}">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>

            <div class="customer-profile-info">
                <h4>{{ auth()->user()->name ?? 'Customer' }}</h4>
                <span>Customer</span>
            </div>
        </div>

        <nav class="customer-sidebar-menu">
            <a href="{{ url('/customer/dashboard') }}" class="customer-menu-link active">
                <i class="fa-solid fa-house"></i>
                <span class="customer-menu-text">Dashboard</span>
            </a>

            <a href="{{ route('customer.services') }}" class="customer-menu-link">
                <i class="fa-solid fa-briefcase"></i>
                <span class="customer-menu-text">My Services</span>
            </a>

            <a href="{{ route('customer.bookings') }}" class="customer-menu-link">
                <i class="fa-solid fa-calendar-check"></i>
                <span class="customer-menu-text">My Bookings</span>
            </a>

            <a href="{{ route('customer.payments') }}" class="customer-menu-link">
                <i class="fa-solid fa-credit-card"></i>
                <span class="customer-menu-text">Payment</span>
            </a>

            <a href="{{ route('customer.chats') }}" class="customer-menu-link">
                <i class="fa-solid fa-comments"></i>
                <span class="customer-menu-text">Chat</span>
                <span id="customerSidebarChatUnread"
                      class="customer-sidebar-chat-unread"
                      style="display:none;"></span>
            </a>

            <a href="{{ route('customer.profile') }}" class="customer-menu-link">
                <i class="fa-solid fa-user-circle"></i>
                <span class="customer-menu-text">My Profile</span>
            </a>
        </nav>

        <div class="customer-sidebar-bottom">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="customer-menu-link customer-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="customer-menu-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="customer-overlay" id="customerOverlay"></div>

    {{-- Main Content --}}
    <main class="customer-main">

        <header class="customer-header">
            <div class="customer-header-left">
                <button type="button" class="customer-mobile-menu"
                        id="customerMobileMenu" aria-label="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="customer-header-title">
                    <h1>Explore Services</h1>
                    <p>Find trusted professionals for your home services.</p>
                </div>
            </div>

            <div class="customer-header-actions">
                <div class="customer-notification-wrapper">
                    <button type="button"
                            class="customer-notification-button"
                            id="notificationButton"
                            aria-label="Notifications"
                            title="Notifications">
                        <i class="fa-solid fa-bell"></i>

                        @php
                            $unreadNotificationCount = auth()->user()
                                ->unreadNotifications()->count();
                        @endphp

                        @if($unreadNotificationCount > 0)
                            <span class="customer-notification-badge" id="notificationBadge">
                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            </span>
                        @endif
                    </button>

                    <div class="customer-notification-dropdown" id="notificationDropdown">
                        <div class="customer-notification-header">
                            <div>
                                <h6>Notifications</h6>
                                <span id="notificationUnreadText">
                                    {{ $unreadNotificationCount }} unread
                                </span>
                            </div>

                            <a href="{{ route('customer.notifications') }}"
                               class="customer-view-all-btn">View All</a>
                        </div>

                        <div class="customer-notification-list" id="customerNotificationList">
                            @forelse(
                                auth()->user()->notifications()->latest()->take(10)->get()
                                as $notification
                            )
                                @php
                                    $data = $notification->data;
                                    $title = $data['title'] ?? 'Notification';
                                    $message = $data['message'] ?? '';
                                    $bookingId = $data['booking_id'] ?? null;
                                    $isUnread = is_null($notification->read_at);
                                @endphp

                                <div class="customer-notification-item {{ $isUnread ? 'unread' : '' }}"
                                     data-id="{{ $notification->id }}">
                                    <div class="customer-notification-item-icon">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>

                                    <div class="customer-notification-item-content">
                                        @if($bookingId)
                                            <a href="{{ route('customer.booking.detail', $bookingId) }}"
                                               class="customer-notification-link"
                                               data-notification-id="{{ $notification->id }}">
                                                <strong>{{ $title }}</strong>
                                                <p>{{ $message }}</p>
                                            </a>
                                        @else
                                            <strong>{{ $title }}</strong>
                                            <p>{{ $message }}</p>
                                        @endif

                                        <small>{{ $notification->created_at->diffForHumans() }}</small>

                                        @if($isUnread)
                                            <button type="button"
                                                    class="customer-notification-read-btn"
                                                    data-id="{{ $notification->id }}">Read</button>
                                        @else
                                            <span class="customer-notification-read-label">Read</span>
                                        @endif
                                    </div>

                                    @if($isUnread)
                                        <span class="customer-notification-unread-dot"></span>
                                    @endif
                                </div>
                            @empty
                                <div class="customer-notification-empty">
                                    <i class="fa-regular fa-bell-slash"></i>
                                    <p>No notifications</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="customer-notification-footer">
                            <a href="{{ route('customer.notifications') }}">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <button type="button" class="quickfix-theme-toggle"
                        id="themeToggle" aria-label="Toggle dark mode"
                        title="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                </button>
            </div>
        </header>

        <section class="customer-content">

            {{-- Category Filter --}}
            <div class="card border-0 shadow-sm mb-4 quickfix-browse-card">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('customer.services.browse') }}">
                        <label for="category_id" class="form-label fw-semibold">
                            Filter by category
                        </label>

                        <div class="d-flex flex-wrap gap-2">
                            <select name="category_id" id="category_id"
                                    class="form-select"
                                    style="max-width:360px;">
                                <option value="">All categories</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected((string)$categoryId === (string)$category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>

                            <a href="{{ route('customer.services.browse') }}"
                               class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Services --}}
            <div class="customer-section-header">
                <div>
                    <h4 class="mb-1">Available Services</h4>
                    <p class="text-muted mb-0">Choose a service to get started.</p>
                </div>
            </div>

            @forelse($services as $service)
                <div class="card border-0 shadow-sm mb-4 quickfix-browse-card">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-lg-row
                                    justify-content-between gap-4">

                            <div class="flex-grow-1">
                                <span class="badge bg-light text-primary mb-2">
                                    {{ $service->category->name ?? 'Service' }}
                                </span>

                                <h4 class="fw-bold mb-2">{{ $service->name }}</h4>

                                <p class="text-muted">
                                    {{ $service->description ?: 'No description available.' }}
                                </p>

                                @if($service->estimated_minutes)
                                    <p class="small text-muted">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        Estimated time: {{ $service->estimated_minutes }} minutes
                                    </p>
                                @endif

                                <h5 class="text-primary fw-bold">
                                    {{ number_format((float)$service->base_price, 0) }} MMK
                                </h5>

                                <a href="{{ route('customer.booking.create', ['service_id' => $service->id]) }}"
                                   class="btn btn-primary mt-2">
                                    <i class="fa-solid fa-calendar-check me-1"></i>
                                    Book a Service
                                </a>
                            </div>

                       
<div class="quickfix-browse-providers">
    <h6 class="fw-bold mb-3">Available Providers</h6>

    @if($service->providers->isNotEmpty())
        <div class="quickfix-browse-providers-list">
            @foreach($service->providers as $provider)
                <div class="border rounded-3 p-3 mb-2">
                    <div class="fw-semibold">
                        {{ $provider->user->name ?? 'Provider' }}
                    </div>

                    <div class="small text-muted mb-2">
                        {{ $provider->serviceArea->name ?? 'Service area not specified' }}
                    </div>

                    <a href="{{ route('customer.providers.show', $provider->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        View Provider
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p class="small text-muted">
            No approved, online providers are available
            for this service right now.
        </p>
    @endif
</div>

                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm quickfix-browse-card">
                    <div class="card-body text-center py-5">
                        <i class="fa-solid fa-magnifying-glass fs-1 text-primary mb-3"></i>
                        <h5>No services found</h5>
                        <p class="text-muted">Try selecting another category.</p>
                        <a href="{{ route('customer.services.browse') }}"
                           class="btn btn-outline-primary">Show All Services</a>
                    </div>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $services->links() }}
            </div>

        </section>
    </main>
</div>


<style>
.quickfix-browse-card {
    border-radius: 14px;
}

.quickfix-browse-providers {
    width: 100%;
    max-width: 360px;
    min-width: 0;
}

.quickfix-browse-providers-list {
    max-height: 290px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;

    /* Firefox scrollbar */
    scrollbar-width: thin;
    scrollbar-color: #adb5bd transparent;
}

/* Chrome, Edge, Safari scrollbar */
.quickfix-browse-providers-list::-webkit-scrollbar {
    width: 6px;
}

.quickfix-browse-providers-list::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 10px;
}

.quickfix-browse-providers-list::-webkit-scrollbar-track {
    background: transparent;
}

.quickfix-browse-providers-list .border {
    border-color: var(--bs-border-color) !important;
}

@media (max-width: 767.98px) {
    .quickfix-browse-providers {
        max-width: 100%;
    }

    .quickfix-browse-card .card-body {
        padding: 1rem !important;
    }
}
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/quickfix.js') }}"></script>
<script src="{{ asset('js/customer.js') }}"></script>
</body>
</html>
