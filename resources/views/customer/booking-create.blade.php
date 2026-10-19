<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Book a Service - QuickFix
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
         QUICKFIX CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/quickfix.css') }}"
    >


    <!-- =====================================================
         CUSTOMER CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/customer.css') }}"
    >


    <!-- =====================================================
         BOOKING CREATE CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/customer-booking-create.css') }}"
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

<aside class="customer-sidebar" id="customerSidebar">

    <!-- Sidebar Header -->
    <div class="customer-sidebar-header">

        <a href="{{ url('/') }}" class="customer-logo">
            <span class="customer-logo-icon">
                <i class="fa-solid fa-wrench"></i>
            </span>

            <span class="customer-logo-text">QuickFix</span>
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
            <h4>{{ auth()->user()->name ?? 'Customer' }}</h4>
            <span>Customer</span>
        </div>

    </div>

    <!-- Sidebar Menu -->
    <nav class="customer-sidebar-menu">

        <!-- Dashboard -->
        <a
            href="{{ route('customer.dashboard') }}"
            class="customer-menu-link"
        >
            <i class="fa-solid fa-house"></i>
            <span class="customer-menu-text">Dashboard</span>
        </a>

        <!-- My Services -->
        <a
            href="{{ route('customer.services') }}"
            class="customer-menu-link active"
        >
            <i class="fa-solid fa-briefcase"></i>
            <span class="customer-menu-text">My Services</span>
        </a>

        <!-- My Bookings -->
        <a
            href="{{ route('customer.bookings') }}"
            class="customer-menu-link"
        >
            <i class="fa-solid fa-calendar-check"></i>
            <span class="customer-menu-text">My Bookings</span>
        </a>

        <!-- Payment -->
        <a
            href="{{ route('customer.payments') }}"
            class="customer-menu-link"
        >
            <i class="fa-solid fa-credit-card"></i>
            <span class="customer-menu-text">Payment</span>
        </a>

        <!-- Chat -->
        <a
            href="{{ route('customer.chats') }}"
            class="customer-menu-link"
        >
            <i class="fa-solid fa-comments"></i>
            <span class="customer-menu-text">Chat</span>

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
            <span class="customer-menu-text">My Profile</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="customer-sidebar-bottom">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="customer-menu-link customer-logout"
            >
                <i class="fa-solid fa-right-from-bracket"></i>
                <span class="customer-menu-text">Logout</span>
            </button>
        </form>

    </div>

</aside>





    <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

    <div
        class="customer-overlay"
        id="customerOverlay"
    ></div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <main class="customer-main">


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="customer-header">

            <div class="customer-header-left">

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
                        Book a Service
                    </h1>

                    <p>
                        Create a new home service booking.
                    </p>

                </div>

            </div>


            <!-- Theme -->

            <div class="customer-header-actions">

                <button
                    type="button"
                    class="quickfix-theme-toggle"
                    id="themeToggle"
                    aria-label="Toggle dark mode"
                >

                    <i
                        class="fa-solid fa-moon"
                        id="themeIcon"
                    ></i>

                </button>

            </div>

        </header>


        <!-- =================================================
             CONTENT
        ================================================== -->

        <section class="customer-bookings-content">


            <div class="customer-booking-create-page">


                <!-- =================================================
                     PAGE ACTION
                ================================================== -->

                <div class="customer-page-header">

                    <a
                        href="{{ route('customer.services') }}"
                        class="btn btn-outline-primary"
                    >

                        <i class="fa-solid fa-arrow-left"></i>

                        Back to Services

                    </a>

                </div>


                <!-- =================================================
                     BOOKING CARD
                ================================================== -->

                <div class="customer-profile-card">


                    <!-- Card Header -->

                    <div class="booking-detail-card-header">

                        <div class="booking-detail-heading">

                            <div class="booking-detail-icon">

                                <i class="fa-solid fa-calendar-plus"></i>

                            </div>


                            <div>

                                <h3>
                                    Booking Information
                                </h3>

                                <p>
                                    Select one or more services for your booking.
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         FORM
                    ================================================== -->

                    <form
                        action="{{ route('customer.booking.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="bookingForm"
                    >

                        @csrf


                        <!-- =================================================
                             SERVICES
                        ================================================== -->

                        <div class="mb-4">


                            <label
                                for="category_select"
                                class="form-label"
                            >
                                Select Services
                            </label>


                            <p class="booking-help-text">

                                Select a category to view its available
                                services. You can choose services from
                                different categories.

                            </p>


                            <!-- Category -->

                            <select
                                id="category_select"
                                class="form-select"
                            >

                                <option value="">
                                    Select a category
                                </option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>


                            <!-- Service List -->

                            <div
                                class="booking-services-container"
                                id="bookingServicesContainer"
                            >

                                <div class="booking-services-empty">

                                    <i class="fa-solid fa-list-check"></i>

                                    <p>
                                        Select a category to view
                                        available services.
                                    </p>

                                </div>

                            </div>


                            <!-- Selected Services -->

                            <div
                                class="selected-services-box"
                                id="selectedServicesBox"
                            >

                                <div class="selected-services-header">

                                    <strong>
                                        Selected Services
                                    </strong>

                                    <span
                                        id="selectedServicesCount"
                                        class="selected-services-count"
                                    >
                                        0
                                    </span>

                                </div>


                                <div
                                    id="selectedServicesList"
                                    class="selected-services-list"
                                >

                                    <span class="selected-services-empty">

                                        No services selected yet.

                                    </span>

                                </div>

                            </div>


                            @error('service_ids')

                                <div class="invalid-feedback d-block">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =====================================================
                             SELECT CITY
                        ====================================================== -->

                        <div class="mb-4">

                            <label
                                for="city_id"
                                class="form-label"
                            >
                                Select City
                            </label>

                            <p class="booking-help-text">

                                Select the city where you need the service.

                            </p>


                            <select
                                name="city_id"
                                id="city_id"
                                class="form-select @error('city_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select a city
                                </option>

                                @foreach($cities as $city)

                                    <option
                                        value="{{ $city->id }}"
                                        {{ old('city_id') == $city->id ? 'selected' : '' }}
                                    >
                                        {{ $city->name }}
                                    </option>

                                @endforeach

                            </select>


                            @error('city_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =====================================================
                             SELECT SERVICE AREA
                        ====================================================== -->

                        <div class="mb-4">

                            <label
                                for="area_id"
                                class="form-label"
                            >
                                Select Service Area
                            </label>

                            <p class="booking-help-text">

                                Select the service area within your selected city.

                            </p>


                            <select
                                name="area_id"
                                id="area_id"
                                class="form-select @error('area_id') is-invalid @enderror"
                                required
                                disabled
                            >

                                <option value="">
                                    Select a city first
                                </option>

                            </select>


                            @error('area_id')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =====================================================
                             SELECT PROVIDER
                        ====================================================== -->

                        <div class="mb-4">

                            <label class="form-label">
                                Select Provider
                            </label>


                            <p class="booking-help-text">

                                Select your services, city and service area first.
                                QuickFix will show providers who are approved,
                                online and provide all selected services.

                            </p>


                            <!-- Provider instruction -->

                            <div
                                class="booking-provider-message"
                                id="bookingProviderMessage"
                            >

                                <i class="fa-solid fa-user-check"></i>

                                <p>
                                    Select your services, city and service area
                                    to see available providers.
                                </p>

                            </div>


                            <!-- Provider List -->

                            <div
                                class="booking-provider-container"
                                id="bookingProviderContainer"
                            >

                                @forelse($providers as $provider)

                                    <label
                                        class="booking-provider-option"
                                        data-provider-id="{{ $provider->id }}"
                                        data-service-ids="{{ $provider->services->pluck('id')->implode(',') }}"
                                        data-area-id="{{ $provider->area_id }}"
                                        data-city-id="{{ $provider->serviceArea?->city_id }}"
                                    >

                                        <input
                                            type="radio"
                                            name="provider_id"
                                            value="{{ $provider->id }}"
                                            class="booking-provider-radio"
                                            {{ old('provider_id') == $provider->id ? 'checked' : '' }}
                                        >


                                        <!-- Avatar -->

                                        <div class="booking-provider-avatar">

                                            @if($provider->user?->profile_image)

                                                <img
                                                    src="{{ asset('storage/' . $provider->user->profile_image) }}"
                                                    alt="{{ $provider->user->name }}"
                                                >

                                            @else

                                                <i class="fa-solid fa-user"></i>

                                            @endif

                                        </div>


                                        <!-- Information -->

                                        <div class="booking-provider-info">

                                            <strong>
                                                {{ $provider->user?->name ?? 'Provider' }}
                                            </strong>


                                            <span>
                                                {{ $provider->experience_years ?? 0 }}
                                                years experience
                                            </span>


                                            <div class="booking-provider-rating">

                                                <i class="fa-solid fa-star"></i>

                                                <span>
                                                    {{ number_format($provider->rating ?? 0, 1) }}
                                                </span>

                                            </div>

                                        </div>


                                        <!-- Check -->

                                        <div class="booking-provider-check">

                                            <i class="fa-solid fa-check"></i>

                                        </div>

                                    </label>

                                @empty

                                    <div class="booking-services-empty">

                                        <i class="fa-solid fa-user-slash"></i>

                                        <p>
                                            No approved and online providers
                                            are currently available.
                                        </p>

                                    </div>

                                @endforelse

                            </div>


                            <!-- No matching provider -->

                            <div
                                class="booking-provider-no-results"
                                id="bookingProviderNoResults"
                            >

                                <i class="fa-solid fa-user-slash"></i>

                                <strong>
                                    No matching providers
                                </strong>

                                <p>
                                    No approved and online provider matches
                                    all of your selected services and
                                    service area.
                                </p>

                            </div>


                            @error('provider_id')

                                <div class="invalid-feedback d-block">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             BOOKING TYPE
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                for="booking_type"
                                class="form-label"
                            >
                                Booking Type
                            </label>


                            <select
                                name="booking_type"
                                id="booking_type"
                                class="form-select @error('booking_type') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Booking Type
                                </option>


                                <option
                                    value="asap"
                                    {{ old('booking_type') === 'asap' ? 'selected' : '' }}
                                >
                                    ASAP
                                </option>


                                <option
                                    value="scheduled"
                                    {{ old('booking_type') === 'scheduled' ? 'selected' : '' }}
                                >
                                    Schedule for Later
                                </option>

                            </select>


                            @error('booking_type')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             DATE & TIME
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                for="scheduled_at"
                                class="form-label"
                            >
                                Date & Time
                            </label>


                            <input
                                type="datetime-local"
                                name="scheduled_at"
                                id="scheduled_at"
                                class="form-control @error('scheduled_at') is-invalid @enderror"
                                value="{{ old('scheduled_at') }}"
                            >


                            @error('scheduled_at')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             ADDRESS
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                for="address"
                                class="form-label"
                            >
                                Service Address
                            </label>


                            <textarea
                                name="address"
                                id="address"
                                class="form-control @error('address') is-invalid @enderror"
                                rows="3"
                                placeholder="Enter the address where the service is needed..."
                                required
                            >{{ old('address', $customer->address) }}</textarea>


                            @error('address')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             CUSTOMER NOTE
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                for="customer_note"
                                class="form-label"
                            >

                                Additional Note

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>


                            <textarea
                                name="customer_note"
                                id="customer_note"
                                class="form-control @error('customer_note') is-invalid @enderror"
                                rows="3"
                                placeholder="Tell the provider anything important about the service..."
                            >{{ old('customer_note') }}</textarea>


                            @error('customer_note')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             PROBLEM PHOTO
                        ================================================== -->

                        <div class="mb-4">

                            <label
                                for="problem_photo"
                                class="form-label"
                            >

                                Problem Photo

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>


                            <input
                                type="file"
                                name="problem_photo"
                                id="problem_photo"
                                class="form-control @error('problem_photo') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                            >


                            <small class="text-muted">

                                Upload a photo showing the problem.
                                JPG, PNG or WebP. Maximum 5 MB.

                            </small>


                            <!-- Preview -->

                            <div
                                class="booking-photo-preview"
                                id="bookingPhotoPreview"
                            >

                                <img
                                    src=""
                                    alt="Problem photo preview"
                                    id="bookingPhotoPreviewImage"
                                >


                                <div class="booking-photo-preview-info">

                                    <strong id="bookingPhotoName">
                                        Selected image
                                    </strong>


                                    <span id="bookingPhotoSize">
                                        Image preview
                                    </span>

                                </div>

                            </div>


                            @error('problem_photo')

                                <div class="invalid-feedback d-block">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        <!-- =================================================
                             ACTIONS
                        ================================================== -->

                        <div class="booking-form-actions">

                            <a
                                href="{{ route('customer.services') }}"
                                class="btn btn-secondary"
                            >

                                <i class="fa-solid fa-xmark"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                                id="continueBookingButton"
                                disabled
                            >

                                <i class="fa-solid fa-calendar-check"></i>

                                Continue Booking

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </section>

    </main>

</div>


<!-- =====================================================
     BOOKING DATA FROM CONTROLLER
====================================================== -->

<script>

    window.quickFixBookingData = {

        services: @json($bookingServices),

        selectedServiceId: @json($selectedServiceId),

        serviceAreas: @json($serviceAreas),

        providers: @json($providers),

        oldCityId: @json(old('city_id')),

        oldAreaId: @json(old('area_id')),

        oldProviderId: @json(old('provider_id'))

    };

</script>


<!-- =====================================================
     BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     QUICKFIX JS
====================================================== -->

<script src="{{ asset('js/quickfix.js') }}"></script>


<!-- =====================================================
     CUSTOMER JS
====================================================== -->

<script src="{{ asset('js/customer.js') }}"></script>


<!-- =====================================================
     BOOKING CREATE JS
====================================================== -->

<script src="{{ asset('js/customer-booking-create.js') }}"></script>


</body>

</html>