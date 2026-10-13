<aside class="qf-sidebar" id="quickfixSidebar">

    <div class="qf-sidebar-logo">
        <i class="fa-solid fa-wrench"></i>
        QuickFix
    </div>

    <nav class="qf-menu">

        @if($role === 'customer')

            <a href="/customer/dashboard"
               class="{{ request()->is('customer/dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="#">
                <i class="fa-solid fa-calendar-check"></i>
                My Bookings
            </a>

            <a href="#">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                Services
            </a>

            <a href="#">
                <i class="fa-solid fa-credit-card"></i>
                Payments
            </a>

            <a href="#">
                <i class="fa-solid fa-star"></i>
                Reviews
            </a>

            <a href="#">
                <i class="fa-solid fa-bell"></i>
                Notifications
            </a>

            <a href="#">
                <i class="fa-solid fa-user"></i>
                Profile
            </a>

        @elseif($role === 'provider')

            <a href="/provider/dashboard">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="#">
                <i class="fa-solid fa-list-check"></i>
                My Jobs
            </a>

            <a href="#">
                <i class="fa-solid fa-wallet"></i>
                Earnings
            </a>

            <a href="#">
                <i class="fa-solid fa-money-bill-transfer"></i>
                Payouts
            </a>

            <a href="#">
                <i class="fa-solid fa-user"></i>
                Profile
            </a>

            <a href="#">
                <i class="fa-solid fa-circle-check"></i>
                Availability
            </a>

            <a href="#">
                <i class="fa-solid fa-location-dot"></i>
                Location Tracking
            </a>

            <a href="#">
                <i class="fa-solid fa-star"></i>
                Reviews
            </a>

        @elseif($role === 'admin' || $role === 'superadmin')

            <a href="/admin/dashboard">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="/admin/users">
                <i class="fa-solid fa-users"></i>
                Users
            </a>

            <a href="#">
                <i class="fa-solid fa-user-gear"></i>
                Providers
            </a>

            <a href="#">
                <i class="fa-solid fa-calendar-check"></i>
                Bookings
            </a>

            <a href="#">
                <i class="fa-solid fa-credit-card"></i>
                Payments
            </a>

            <a href="#">
                <i class="fa-solid fa-money-bill-transfer"></i>
                Earnings & Payouts
            </a>

            <a href="#">
                <i class="fa-solid fa-chart-column"></i>
                Reports
            </a>

            <a href="#">
                <i class="fa-solid fa-bell"></i>
                Notifications
            </a>

            <a href="#">
                <i class="fa-solid fa-gear"></i>
                Settings
            </a>

        @endif

        <hr>

        <a href="#" class="qf-logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </nav>

</aside>