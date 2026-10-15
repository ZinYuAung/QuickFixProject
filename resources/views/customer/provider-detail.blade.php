<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>QuickFix - Provider Details</title>

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
                    <h1>Provider Details</h1>
                    <p>Learn more about your service provider.</p>
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

            {{-- Back to services --}}
            <div class="mb-3">
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back
                </a>
            </div>

            {{-- Provider Profile --}}
            <div class="card border-0 shadow-sm mb-4 quickfix-provider-card">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row gap-4 align-items-md-start">

                        <div class="quickfix-provider-avatar">
                            @if($provider->user?->profile_image)
                                <img src="{{ asset('storage/' . $provider->user->profile_image) }}"
                                     alt="{{ $provider->user->name ?? 'Provider' }}">
                            @else
                                <i class="fa-solid fa-user-tie"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-2">
                                {{ $provider->user->name ?? 'Provider' }}
                            </h2>

                            <p class="text-muted mb-3">
                                {{ $provider->bio ?: 'No provider description available.' }}
                            </p>

                            <div class="quickfix-provider-facts">
                                <div>
                                    <i class="fa-solid fa-briefcase text-primary me-2"></i>
                                    <strong>Experience:</strong>
                                    {{ $provider->experience_years ?? 0 }} years
                                </div>

                                <div>
                                    <i class="fa-solid fa-location-dot text-primary me-2"></i>
                                    <strong>Service Area:</strong>
                                    {{ $provider->serviceArea->name ?? 'Not specified' }}
                                </div>

                                <div>
                                    <i class="fa-solid fa-star text-warning me-2"></i>
                                    <strong>Rating:</strong>

                                    @if($totalReviews > 0)
                                        {{ number_format((float)$averageRating, 1) }}/5
                                        <span class="text-muted">
                                            ({{ $totalReviews }} reviews)
                                        </span>
                                    @else
                                        <span class="text-muted">No reviews yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Services Offered --}}
            <div class="customer-section-header">
                <div>
                    <h4 class="mb-1">Services Offered</h4>
                    <p class="text-muted mb-0">
                        Select a service to continue to booking.
                    </p>
                </div>
            </div>

            @forelse($provider->services as $service)
                <div class="card border-0 shadow-sm mb-3 quickfix-provider-card">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row
                                    justify-content-between gap-3">

                            <div class="flex-grow-1">
                                <span class="badge bg-light text-primary mb-2">
                                    {{ $service->category->name ?? 'Service' }}
                                </span>

                                <h5 class="fw-bold">{{ $service->name }}</h5>

                                <p class="text-muted mb-2">
                                    {{ $service->description ?: 'No description available.' }}
                                </p>

                                @if($service->estimated_minutes)
                                    <small class="text-muted">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        {{ $service->estimated_minutes }} minutes
                                    </small>
                                @endif
                            </div>

                            <div class="quickfix-provider-service-action">
                                <h5 class="text-primary fw-bold">
                                    {{ number_format((float)$service->base_price, 0) }} MMK
                                </h5>

                                <a href="{{ route('customer.booking.create', ['service_id' => $service->id]) }}"
                                   class="btn btn-primary">
                                    <i class="fa-solid fa-calendar-check me-1"></i>
                                    Book a Service
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm mb-4 quickfix-provider-card">
                    <div class="card-body text-center py-4">
                        <i class="fa-solid fa-screwdriver-wrench fs-2 text-muted mb-3"></i>
                        <p class="text-muted mb-0">
                            This provider has no active services listed.
                        </p>
                    </div>
                </div>
            @endforelse

            {{-- Customer Reviews --}}
            <div class="customer-section-header mt-4">
                <div>
                    <h4 class="mb-1">Customer Reviews</h4>
                    <p class="text-muted mb-0">
                        Feedback from customers who reviewed this provider.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm quickfix-provider-card">
                <div class="card-body p-4">

                    @if($totalReviews > 0)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div>
                                <div class="display-6 fw-bold">
                                    {{ number_format((float)$averageRating, 1) }}
                                </div>
                                <div class="text-warning">
                                    @for($star = 1; $star <= 5; $star++)
                                        <i class="{{ $star <= round((float)$averageRating)
                                            ? 'fa-solid fa-star'
                                            : 'fa-regular fa-star' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    Based on {{ $totalReviews }}
                                    {{ $totalReviews === 1 ? 'review' : 'reviews' }}
                                </small>
                            </div>
                        </div>
                    @endif

                    @forelse($reviews as $review)
                        <div class="quickfix-provider-review">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <strong>
    {{ auth()->check() && auth()->id() == $review->customer?->user?->id
        ? 'You'
        : ($review->customer?->user?->name ?? 'Customer') }}
</strong>

                                <span class="text-warning">
                                    @for($star = 1; $star <= 5; $star++)
                                        <i class="{{ $star <= $review->rating
                                            ? 'fa-solid fa-star'
                                            : 'fa-regular fa-star' }}"></i>
                                    @endfor
                                    <span class="text-muted">
                                        {{ $review->rating }}/5
                                    </span>
                                </span>
                            </div>

                            <p class="mt-2 mb-1">
                                {{ $review->comment ?: 'No written comment.' }}
                            </p>

                            <small class="text-muted">
                                {{ $review->created_at?->format('M d, Y') }}
                            </small>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fa-regular fa-comment-dots fs-2 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No customer reviews yet.</p>
                        </div>
                    @endforelse

                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>

                </div>
            </div>

        </section>
    </main>
</div>

<style>
.quickfix-provider-card { border-radius: 14px; }
.quickfix-provider-avatar {
    width: 100px;
    height: 100px;
    min-width: 100px;
    border-radius: 50%;
    overflow: hidden;
    background: var(--bs-tertiary-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--bs-secondary-color);
}
.quickfix-provider-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.quickfix-provider-facts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.quickfix-provider-service-action {
    min-width: 190px;
}
.quickfix-provider-review {
    padding: 18px 0;
    border-bottom: 1px solid var(--bs-border-color);
}
.quickfix-provider-review:last-child { border-bottom: 0; }
@media (max-width: 767.98px) {
    .quickfix-provider-card .card-body { padding: 1rem !important; }
    .quickfix-provider-service-action { min-width: 0; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/quickfix.js') }}"></script>
<script src="{{ asset('js/customer.js') }}"></script>
</body>
</html>