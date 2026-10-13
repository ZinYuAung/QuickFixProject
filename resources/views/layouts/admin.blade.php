<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >

        <title>
            @yield('title', 'Admin') - QuickFix
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
         ADMIN CSS
    ====================================================== -->

        <link
            rel="stylesheet"
            href="{{ asset('css/admin.css') }}"
        >
        @stack('styles')

    </head>


    <body class="admin-body">


        <!-- =====================================================
         ADMIN NAVBAR
    ====================================================== -->

        <nav class="admin-navbar">

            <div class="admin-navbar-container">


                <!-- =================================================
                 LOGO
                ================================================== -->

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="admin-logo"
                >

                    <span class="admin-logo-icon">

                        <i class="fa-solid fa-wrench"></i>

                    </span>

                    <span class="admin-logo-text">
                        QuickFix
                    </span>

                    <span class="admin-logo-badge">
                        ADMIN
                    </span>

                </a>


                <!-- =================================================
                 MOBILE MENU BUTTON
                ================================================== -->

                <button
                    type="button"
                    class="admin-mobile-toggle"
                    aria-label="Toggle navigation"
                    aria-expanded="false"
                >

                    <i class="fa-solid fa-bars"></i>

                </button>


                <!-- =================================================
                 ADMIN NAVIGATION
                ================================================== -->

                <div class="admin-navigation">


                    <!-- Dashboard -->

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-chart-line"></i>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    <!-- Customers -->

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-users"></i>

                        <span>
                            Customers
                        </span>

                    </a>


                   <!-- Providers -->

                    <a
                        href="{{ route('admin.providers.index') }}"
                        class="{{ request()->routeIs('admin.providers.*') && request('approval_status') !== 'pending' ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-user-tie"></i>
                        <span>Providers</span>
                    </a>


               <!-- New Provider Request -->
                    @php
                        $pendingProviders = \App\Models\Provider::where('approval_status', 'pending')->count();
                    @endphp

                    <a
                        href="{{ route('admin.providers.index', ['approval_status' => 'pending']) }}"
                        class="{{ request()->fullUrlIs(route('admin.providers.index', ['approval_status' => 'pending'])) ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-user-clock"></i>
                        <span>New Provider Request</span>
                        
                        @if($pendingProviders > 0)
                            <span class="badge bg-danger ms-auto" style="font-size: 10px;">{{ $pendingProviders }}</span>
                        @endif
                    </a>

                    <!-- Services -->

                    <a
                        href="{{ route('admin.services.index') }}"
                        class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                        <span>
                            Services
                        </span>

                    </a>

                    <!-- Categories -->
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Categories</span>
                    </a>


                    <!-- Bookings -->
                    <a
                        href="{{ route('admin.bookings.index') }}"
                        class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-calendar-check"></i>

                        <span>
                            Bookings
                        </span>

                    </a>

                    <a
                        href="{{ route('admin.payments.index') }}"
                        class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Payments</span>
                    </a>

                    <!-- Cities & Service Areas -->
                    <a
                        href="{{ route('admin.cities.index') }}"
                        class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-city"></i>
                        <span>Cities</span>
                    </a>

                    <a
                        href="{{ route('admin.service-areas.index') }}"
                        class="{{ request()->routeIs('admin.service-areas.*') ? 'active' : '' }}"
                    >
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Service Areas</span>
                    </a>
                </div>


                <!-- =================================================
                 RIGHT SIDE
                ================================================== -->

                <div class="admin-navbar-right">


                    <!-- Notification -->

                    <button
                        type="button"
                        class="admin-notification-btn"
                        id="adminNotificationBtn"
                        aria-label="Notifications"
                    >

                        <i class="fa-regular fa-bell"></i>

                        <span class="admin-notification-badge">
                            3
                        </span>

                    </button>


                    <!-- Profile -->
                    <div class="admin-profile" data-bs-toggle="modal" data-bs-target="#adminProfileModal" style="cursor: pointer;" title="View Profile">

                        <div class="admin-profile-avatar">
                            @if(auth()->user()?->profile_image)
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}
                            @endif
                        </div>

                        <div class="admin-profile-info">
                            <strong>
                                {{ auth()->user()->name ?? 'Admin' }}
                            </strong>
                            <span>
                                Administrator
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </nav>


        <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

        <main class="admin-main">
               @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert" style="font-size: 11px;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
                        {{-- Success Alert inside modal if any --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert" style="font-size: 11px;">
                                <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

            @yield('content')

        </main>


        <!-- =====================================================
         BOOTSTRAP JS
    ====================================================== -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


        <!-- =====================================================
         QUICKFIX JS
    ====================================================== -->

        <script src="{{ asset('js/quickfix.js') }}"></script>


        <!-- =====================================================
         ADMIN JS
    ====================================================== -->

        <script src="{{ asset('js/admin.js') }}"></script>
        @stack('scripts')

        {{-- =====================================================
             ADMIN PROFILE & EDIT MODAL
        ====================================================== --}}
        <div class="modal fade" id="adminProfileModal" tabindex="-1" aria-labelledby="adminProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content admin-profile-modal-content">
                    
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="adminProfileModalLabel">Admin Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body py-4">
                     

                        {{-- VIEW MODE --}}
                        <div id="profileViewMode" class="text-center">
                            <div class="mb-3">
                                @if(auth()->user()?->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle mx-auto shadow-sm" width="84" height="84" style="object-fit: cover;">
                                @else
                                    <div class="admin-profile-modal-avatar mx-auto">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-1" style="color: var(--navy); font-size: 18px;">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </h4>
                            <p class="text-muted mb-4" style="font-size: 12px;">
                                {{ auth()->user()->email ?? '—' }}
                            </p>

                            <div class="text-start bg-light p-3 rounded-3 mb-4" style="border: 1px solid var(--border);">
                                <div class="d-flex justify-content-between mb-2" style="font-size: 12px;">
                                    <span class="text-muted">Role:</span>
                                    <strong style="color: var(--navy);">Administrator</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2" style="font-size: 12px;">
                                    <span class="text-muted">Phone:</span>
                                    <strong style="color: var(--navy);">{{ auth()->user()->phone ?? '—' }}</strong>
                                </div>
                                <div class="d-flex justify-content-between" style="font-size: 12px;">
                                    <span class="text-muted">Joined Date:</span>
                                    <strong style="color: var(--navy);">{{ auth()->user()?->created_at?->format('M d, Y') ?? '—' }}</strong>
                                </div>
                            </div>

                            <button type="button" class="admin-primary-btn w-100 justify-content-center" onclick="toggleEditMode(true)">
                                <i class="fa-solid fa-pen"></i> Edit Profile
                            </button>
                        </div>

                       {{-- EDIT MODE FORM --}}
<div id="profileEditMode" class="d-none text-start">
    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3 text-center">
            <label for="profileImageInput" class="form-label d-block text-muted" style="font-size: 11px; cursor: pointer;">
                <div class="position-relative d-inline-block">
                    @if(auth()->user()?->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle shadow-sm" width="76" height="76" style="object-fit: cover;">
                    @else
                        <div class="admin-profile-modal-avatar mx-auto" style="width: 76px; height: 76px; font-size: 24px;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}
                        </div>
                    @endif
                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1" style="font-size: 10px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                </div>
                <span class="d-block mt-1 text-primary fw-semibold" style="font-size: 10px;">Change Avatar</span>
            </label>
            
            
            <input type="file" name="profile_image" id="profileImageInput" class="form-control form-control-sm mt-2" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label text-muted" style="font-size: 11px;">Name</label>
            <input type="text" name="name" class="form-control form-control-sm" value="{{ auth()->user()->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label text-muted" style="font-size: 11px;">Email</label>
            <input type="email" name="email" class="form-control form-control-sm" value="{{ auth()->user()->email }}" required>
        </div>

        <div class="mb-4">
            <label class="form-label text-muted" style="font-size: 11px;">Phone</label>
            <input type="text" name="phone" class="form-control form-control-sm" value="{{ auth()->user()->phone }}">
        </div>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary w-50 btn-sm" onclick="toggleEditMode(false)">Cancel</button>
            <button type="submit" class="admin-primary-btn w-50 justify-content-center btn-sm">Save Changes</button>
        </div>
    </form>
</div>
                    </div>

                </div>
            </div>
        </div>

      {{-- Toggle Script for Modal View/Edit & Auto Reopen on Profile Error/Success Only --}}
<script>
    function toggleEditMode(isEdit) {
        const viewMode = document.getElementById('profileViewMode');
        const editMode = document.getElementById('profileEditMode');
        if (isEdit) {
            viewMode.classList.add('d-none');
            editMode.classList.remove('d-none');
        } else {
            editMode.classList.add('d-none');
            viewMode.classList.remove('d-none');
        }
    }

    // Profile Modal Profile  Success/Error 
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('profile_success') || session('profile_error') || $errors->has('current_password') || $errors->has('name'))
            var adminProfileModal = new bootstrap.Modal(document.getElementById('adminProfileModal'));
            adminProfileModal.show();
            @if ($errors->any())
                toggleEditMode(true);
            @endif
        @endif
    });
</script>
    </body>

</html>