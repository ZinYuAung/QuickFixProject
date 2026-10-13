<header class="qf-topbar">

    <button
        id="mobileMenuButton"
        class="btn qf-mobile-menu">

        <i class="fa-solid fa-bars"></i>

    </button>

    <div>
        <i class="fa-regular fa-bell"></i>
    </div>

    <div class="qf-user">

        <div class="qf-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'User', 0, 1)) }}
        </div>

        <div>

            <strong>
                {{ auth()->user()->name ?? 'Guest' }}
            </strong>

            <div style="font-size:11px;color:#718096;">
                {{ ucfirst(auth()->user()->role ?? 'User') }}
            </div>

        </div>

    </div>

</header>