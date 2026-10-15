<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>QuickFix - Edit Profile</title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Global CSS -->
        <link rel="stylesheet" href="{{ asset('css/quickfix.css') }}">

        <!-- Customer CSS -->
        <link rel="stylesheet" href="{{ asset('css/customer.css') }}">

        <!-- Profile CSS -->
        <link rel="stylesheet" href="{{ asset('css/customer-profile.css') }}">

    </head>

    <body>

        <div class="customer-layout" id="customerLayout">


            <!--  SIDEBAR -->

            <aside class="customer-sidebar">


                <!-- Sidebar Header -->

                <div class="customer-sidebar-header">

                    <a href="{{ url('/') }}" class="customer-logo">

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


                <!-- Sidebar Menu -->

                <nav class="customer-sidebar-menu">


                    <!-- Dashboard -->

                    <a href="{{ url('/customer/dashboard') }}" class="customer-menu-link">

                        <i class="fa-solid fa-house"></i>

                        <span class="customer-menu-text">
                            Dashboard
                        </span>

                    </a>


                    <!-- My Services -->

                    <a href="{{ route('customer.services') }}" class="customer-menu-link">
                        <i class="fa-solid fa-briefcase"></i>
                        <span class="customer-menu-text">My Services</span>
                    </a>


                    <!-- My Bookings -->

                    <a href="{{ route('customer.bookings') }}" class="customer-menu-link">

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
                        class="customer-menu-link"
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


                    <!-- Profile -->

                    <a href="{{ route('customer.profile') }}" class="customer-menu-link active">

                        <i class="fa-solid fa-user-circle"></i>

                        <span class="customer-menu-text">
                            My Profile
                        </span>

                    </a>

                </nav>


                <!-- Sidebar Bottom -->

                <div class="customer-sidebar-bottom">

                    <form action="{{ route('logout') }}" method="POST">

                        @csrf

                        <button type="submit" class="customer-menu-link customer-logout">

                            <i class="fa-solid fa-right-from-bracket"></i>

                            <span class="customer-menu-text">
                                Logout
                            </span>

                        </button>

                    </form>

                </div>

            </aside>


            <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

            <div class="customer-overlay" id="customerOverlay"></div>

            <main class="customer-main">


                <!--  HEADER -->

                <header class="customer-header">

                    <div class="customer-header-left">


                        <!-- Mobile Menu -->

                        <button type="button" class="customer-mobile-menu" id="customerMobileMenu" aria-label="Open menu">

                            <i class="fa-solid fa-bars"></i>

                        </button>


                        <!-- Header Title -->

                        <div class="customer-header-title">

                            <h1>
                                Edit Profile
                            </h1>

                            <p>
                                Update your personal information.
                            </p>

                        </div>

                    </div>


                    <!-- Header Actions -->

                    <div class="customer-header-actions">

                        <button
                            type="button"
                            class="quickfix-theme-toggle"
                            id="themeToggle"
                            aria-label="Toggle dark mode"
                            title="Toggle dark mode"
                        >

                            <i class="fa-solid fa-moon" id="themeIcon"></i>

                        </button>

                    </div>

                </header>


                <!--  EDIT PROFILE CONTENT -->

                <section class="customer-profile-content">


                    <!-- Page Intro -->

                    <div class="profile-page-intro">

                        <div class="profile-intro-icon">

                            <i class="fa-solid fa-user-pen"></i>

                        </div>

                        <div>

                            <h3>
                                Edit Personal Information
                            </h3>

                            <p>
                                Update your account information below.
                            </p>

                        </div>

                    </div>

                    <!--  EDIT FORM CARD-->
                    <div class="profile-edit-card">

                        <form
                            action="{{ route('customer.profile.update') }}"
                            method="POST"
                            class="profile-edit-form"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            @method('PUT')
                            <!-- Profile Image -->
                            <div class="profile-form-group profile-image-group">

                                <label for="profile_image">
                                    Profile Image
                                </label>

                                <div class="profile-image-upload">

                                    <div class="profile-image-preview" id="profileImagePreview">

                                        @if(auth()->user()->profile_image)

                                            <img
                                                src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                                                alt="{{ auth()->user()->name ?? 'Customer' }}"
                                            >

                                        @else

                                            <i class="fa-solid fa-user"></i>

                                        @endif

                                    </div>

                                    <div class="profile-image-upload-content">

                                        <label for="profile_image" class="profile-image-upload-button">
                                            <i class="fa-solid fa-camera"></i>
                                            Choose Image
                                        </label>

                                        <input
                                            type="file"
                                            id="profile_image"
                                            name="profile_image"
                                            accept=".jpg,.jpeg,.png,.webp"
                                            hidden
                                        >

                                        <p>
                                            JPG, JPEG, PNG or WEBP. Maximum size: 2 MB.
                                        </p>

                                        @error('profile_image')
                                            <span class="profile-error-message">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                    </div>

                                </div>

                            </div>
                            <div class="profile-form-row">
                                <!-- Name -->
                                <div class="profile-form-group">
                                    <label for="name">
                                        Full Name
                                    </label>
                                    <div class="profile-input-wrapper">
                                        <i class="fa-solid fa-user"></i>
                                        <input
                                            type="text"
                                            id="name"
                                            name="name"
                                            value="{{ old('name', auth()->user()->name ?? '') }}"
                                            placeholder="Enter your full name"
                                            required
                                        >
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="profile-form-group">
                                    <label for="email">
                                        Email Address
                                    </label>

                                    <div class="profile-input-wrapper">

                                        <i class="fa-solid fa-envelope"></i>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old('email', auth()->user()->email ?? '') }}"
                                            placeholder="Enter your email"
                                            required
                                        >

                                    </div>

                                </div>

                            </div>

                            <div class="profile-form-row">
                                <!-- Phone -->

                                <div class="profile-form-group">

                                    <label for="phone">

                                        Phone Number

                                    </label>

                                    <div class="profile-input-wrapper">

                                        <i class="fa-solid fa-phone"></i>

                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                            placeholder="Enter your phone number"
                                        >

                                    </div>

                                </div>


                                <!-- City -->

                                <div class="profile-form-group">

                                    <label for="city">

                                        City

                                    </label>

                                    <div class="profile-input-wrapper">

                                        <i class="fa-solid fa-city"></i>

                                        <input
                                            type="text"
                                            id="city"
                                            name="city"
                                            value="{{ old('city', auth()->user()->city ?? '') }}"
                                            placeholder="Enter your city"
                                        >

                                    </div>

                                </div>

                            </div>

                            <div class="profile-form-group">

                                <label for="address">

                                    Address

                                </label>

                                <div class="profile-textarea-wrapper">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <textarea id="address" name="address" rows="4"
                                    placeholder="Enter your full address">{{ old('address', auth()->user()->address ?? '') }}</textarea>

                                </div>

                            </div>
                            <!--   NOTE-->

                            <div class="profile-edit-note">

                                <div class="profile-note-icon">

                                    <i class="fa-solid fa-circle-info"></i>

                                </div>

                                <div>

                                    <strong>
                                        Profile information
                                    </strong>

                                    <p>
                                        Please make sure your contact information
                                        is accurate so providers can reach you
                                        about your bookings.
                                    </p>

                                </div>

                            </div>
                            <!--  ACTION BUTTONS-->
                            <div class="profile-edit-actions">

                                <a href="{{ route('customer.profile') }}" class="profile-cancel-button">

                                    <i class="fa-solid fa-xmark"></i>

                                    Cancel

                                </a>


                                <!-- Save -->

                                <button type="submit" class="profile-save-button">

                                    <i class="fa-solid fa-check"></i>

                                    Save Changes

                                </button>

                            </div>

                        </form>

                    </div>

                </section>

            </main>

        </div>


        <!--  JAVASCRIPT -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Global JS -->
        <script src="{{ asset('js/quickfix.js') }}"></script>

        <!-- Customer Shared JS -->
        <script src="{{ asset('js/customer.js') }}"></script>

        <!-- Profile Specific JS -->
        <script src="{{ asset('js/customer-profile.js') }}"></script>

    </body>

</html>