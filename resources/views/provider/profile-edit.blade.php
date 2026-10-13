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

    <title>
        Edit Profile - QuickFix Provider
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
         PROVIDER CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="{{ asset('css/provider.css') }}"
    >

</head>


<body>


    <!-- =====================================================
         PROVIDER LAYOUT
    ====================================================== -->

    <div
        class="provider-layout"
        id="providerLayout"
    >


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <aside class="provider-sidebar">


            <!-- =================================================
                 SIDEBAR HEADER
            ================================================== -->

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



            <!-- =================================================
                 PROVIDER PROFILE
            ================================================== -->

            <div class="provider-profile">


                <div class="provider-avatar">

                    @if($provider->user?->profile_image)

                        <img
                            src="{{ asset('storage/' . $provider->user->profile_image) }}"
                            alt="{{ $provider->user?->name ?? 'Provider' }}"
                        >

                    @else

                        <i class="fa-solid fa-user"></i>

                    @endif

                </div>


                <div class="provider-profile-info">

                    <h4>

                        {{ $provider->user?->name ?? 'Provider' }}

                    </h4>


                    <span>
                        Service Provider
                    </span>

                </div>


            </div>



            <!-- =================================================
                 SIDEBAR MENU
            ================================================== -->

            <nav class="provider-sidebar-menu">


                <!-- Dashboard -->

                <a
                    href="{{ route('provider.dashboard') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-house"></i>

                    <span class="provider-menu-text">
                        Dashboard
                    </span>

                </a>


                <!-- My Bookings -->

                <a
                    href="{{ route('provider.bookings') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-calendar-check"></i>

                    <span class="provider-menu-text">
                        My Bookings
                    </span>

                </a>


                <!-- My Jobs -->

                <a
                    href="{{ route('provider.jobs') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                    <span class="provider-menu-text">
                        My Jobs
                    </span>

                </a>


                <!-- Earnings -->

                <a
                    href="{{ route('provider.earnings') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-wallet"></i>

                    <span class="provider-menu-text">
                        Earnings
                    </span>

                </a>


                <!-- Payments -->

                <a
                    href="{{ route('provider.payments.index') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-credit-card"></i>

                    <span class="provider-menu-text">
                        Payments
                    </span>

                </a>


                <!-- Chats -->

                <a
                    href="{{ route('provider.chats') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-comments"></i>

                    <span class="provider-menu-text">
                        Chats
                    </span>


                    <span
                        class="provider-chat-badge"
                        id="providerSidebarChatUnread"
                        style="display:none;"
                    >
                        0
                    </span>

                </a>


                <!-- Reviews -->

                <a
                    href="{{ route('provider.reviews.index') }}"
                    class="provider-menu-link"
                >

                    <i class="fa-solid fa-star"></i>

                    <span class="provider-menu-text">
                        Reviews & Ratings
                    </span>

                </a>


                <!-- My Profile -->

                <a
                    href="{{ route('provider.profile') }}"
                    class="provider-menu-link active"
                >

                    <i class="fa-solid fa-user"></i>

                    <span class="provider-menu-text">
                        My Profile
                    </span>

                </a>


            </nav>



            <!-- =================================================
                 SIDEBAR BOTTOM
            ================================================== -->

            <div class="provider-sidebar-bottom">


                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf


                    <button
                        type="submit"
                        class="provider-menu-link provider-logout"
                        style="width:100%; border:0; background:none;"
                    >

                        <i class="fa-solid fa-right-from-bracket"></i>

                        <span class="provider-menu-text logout-text">
                            Logout
                        </span>

                    </button>

                </form>


            </div>


        </aside>



        <!-- =================================================
             MOBILE OVERLAY
        ================================================== -->

        <div
            class="provider-overlay"
            id="providerOverlay"
        ></div>



        <!-- =================================================
             MAIN CONTENT
        ================================================== -->

        <main class="provider-main">


            <!-- =================================================
                 HEADER
            ================================================== -->

            <header class="provider-header">


                <div class="provider-header-left">


                    <button
                        type="button"
                        class="provider-mobile-menu"
                        id="providerMobileMenu"
                        aria-label="Open menu"
                        title="Open menu"
                    >

                        <i class="fa-solid fa-bars"></i>

                    </button>


                    <div>

                        <h1>
                            Edit Profile
                        </h1>

                        <p>
                            Update your provider information and services.
                        </p>

                    </div>


                </div>



                <!-- Header Actions -->

                <div class="provider-header-actions">


                    <!-- Provider Status -->

                    <div class="provider-status">


                        <span class="status-dot"></span>


                        <span class="status-text">

                            {{ ucfirst($provider->availability_status ?? 'offline') }}

                        </span>


                        <label class="status-switch">

                            <input
                                type="checkbox"
                                class="status-toggle"
                                {{ ($provider->availability_status ?? 'offline') === 'online' ? 'checked' : '' }}
                                disabled
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



            <!-- =================================================
                 CONTENT
            ================================================== -->

            <div class="provider-content">


                <!-- =================================================
                     PAGE HEADING
                ================================================== -->

                <div class="provider-page-heading">


                    <div>

                        <span class="provider-section-label">
                            
                        </span>

                        <h2>
                            
                        </h2>

                        <p>
                           
                        </p>

                    </div>


                    <a
                        href="{{ route('provider.profile') }}"
                        class="provider-secondary-btn"
                    >

                        <i class="fa-solid fa-arrow-left"></i>

                        Back to Profile

                    </a>


                </div>



                <!-- =================================================
                     SUCCESS MESSAGE
                ================================================== -->

                @if(session('success'))

                    <div
                        class="alert alert-success alert-dismissible fade show"
                        role="alert"
                    >

                        <i class="fa-solid fa-circle-check me-2"></i>

                        {{ session('success') }}


                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"
                        ></button>

                    </div>

                @endif



                <!-- =================================================
                     VALIDATION ERRORS
                ================================================== -->

                @if($errors->any())

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >

                        <div class="fw-bold mb-2">

                            <i class="fa-solid fa-triangle-exclamation me-2"></i>

                            Please correct the following errors:

                        </div>


                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                <!-- =================================================
                     PROFILE CARD
                ================================================== -->

                <div class="provider-profile-card">


                    <!-- =================================================
                         PROFILE HEADER
                    ================================================== -->

                    <div class="provider-profile-card-top">


                        <div class="provider-profile-large-avatar">

                            @if($provider->user?->profile_image)

                                <img
                                    src="{{ asset('storage/' . $provider->user->profile_image) }}"
                                    alt="{{ $provider->user?->name ?? 'Provider' }}"
                                >

                            @else

                                <i class="fa-solid fa-user"></i>

                            @endif

                        </div>


                        <div class="provider-profile-card-info">

                            <h3>

                                {{ $provider->user?->name ?? 'Provider' }}

                            </h3>


                            <p>
                                Service Provider
                            </p>

                        </div>


                    </div>



                    <!-- =================================================
                         EDIT FORM
                    ================================================== -->

                    <form
    action="{{ route('provider.profile.update') }}"
    method="POST"
    class="provider-profile-form"
    data-profile-url="{{ route('provider.profile') }}"
    enctype="multipart/form-data"
>

                        @csrf

                        @method('PUT')



                        <!-- =================================================
                             PERSONAL INFORMATION
                        ================================================== -->

                        <div class="provider-profile-section provider-form-full">


                            <div class="provider-section-heading">

                                <h3>
                                    Personal Information
                                </h3>

                                <p>
                                    Update your personal account information.
                                </p>

                            </div>


                        </div>



                        <!-- Full Name -->

                        <div class="provider-form-group">

                            <label for="name">
                                Full Name
                            </label>


                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $provider->user?->name) }}"
                                maxlength="255"
                                required
                            >

                        </div>



                        <!-- Email -->

                        <div class="provider-form-group">

                            <label for="email">
                                Email
                            </label>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $provider->user?->email) }}"
                                maxlength="255"
                                required
                            >

                        </div>



                        <!-- Phone -->

                        <div class="provider-form-group">

                            <label for="phone">
                                Phone
                            </label>


                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $provider->user?->phone) }}"
                                maxlength="30"
                                required
                            >

                        </div>



                        <!-- =================================================
                             CITY
                        ================================================== -->

                        <div class="provider-form-group">

                            <label for="city_id">
                                City
                            </label>


                            <select
                                id="city_id"
                                name="city_id"
                                required
                            >

                                <option value="">
                                    Select City
                                </option>


                                @foreach($cities as $city)

                                    <option
                                        value="{{ $city->id }}"
                                        {{ old(
                                            'city_id',
                                            $provider->serviceArea?->city_id
                                        ) == $city->id ? 'selected' : '' }}
                                    >

                                        {{ $city->name }}

                                    </option>

                                @endforeach


                            </select>
                            <!-- Error container -->
    <div class="invalid-feedback text-danger small mt-1" id="error-city_id"></div>


                            <small class="provider-form-help">

                                Select the city where you provide services.

                            </small>

                        </div>
                        


<!-- SERVICE AREA -->
<div class="mb-3">
    <label for="area_id" class="form-label">
        Service Area <span class="text-danger">*</span>
    </label>

    <select
        name="area_id"
        id="area_id"
        class="form-select"
        required
    >
        <option value="">Select Service Area</option>

        @foreach ($serviceAreas as $area)
            <option
                value="{{ $area->id }}"
                data-city-id="{{ $area->city_id }}"
                {{ old('area_id', $provider->area_id) == $area->id ? 'selected' : '' }}
            >
                {{ $area->name }} — {{ $area->city?->name }}
            </option>
        @endforeach
    </select>

    <div id="error-area_id" class="text-danger small mt-1"></div>
</div>


                        <!-- =================================================
                             PROFILE IMAGE
                        ================================================== -->

                        <div class="provider-form-group provider-form-full">


                            <label for="profile_image">
                                Profile Image
                            </label>


                            <input
                                type="file"
                                id="profile_image"
                                name="profile_image"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                            >


                            <small class="provider-form-help">

                                Upload JPG, PNG, or WebP image.
                                Maximum size: 2 MB.

                            </small>


                        </div>



                        <!-- =================================================
                             SERVICES
                        ================================================== -->

                        <div class="provider-profile-section provider-form-full">


                            <div class="provider-section-heading">

                                <h3>
                                    Services
                                </h3>

                                <p>
                                    Select the categories and services you provide.
                                </p>

                            </div>


                        </div>



                        <!-- =================================================
                             SERVICE CATEGORY
                        ================================================== -->

                        <div class="provider-form-group provider-form-full">


                            <label for="service_category_id">
                                Service Category
                            </label>


                            <select
                                id="service_category_id"
                                class="provider-service-category-select"
                            >

                                <option value="">
                                    Select Service Category
                                </option>


                                @foreach($categories as $category)

                                    @if($category->services->count() > 0)

                                        <option
                                            value="{{ $category->id }}"
                                        >

                                            {{ $category->name }}

                                        </option>

                                    @endif

                                @endforeach


                            </select>


                            <small class="provider-form-help">

                                First select a category, then select
                                the services you provide.

                            </small>


                        </div>



                        <!-- =================================================
                             SELECTED CATEGORIES
                        ================================================== -->

                        <div class="provider-form-group provider-form-full">


                            <label>
                                Selected Categories
                            </label>


                            <div
                                id="providerSelectedCategories"
                                class="provider-selected-categories"
                            >

                                <p class="provider-form-help">

                                    No category selected yet.

                                </p>

                            </div>


                        </div>



                        <!-- =================================================
                             AVAILABLE SERVICES
                        ================================================== -->

                        <div class="provider-form-group provider-form-full">


                            <label>
                                Available Services
                            </label>


                            <p class="provider-form-help">

                                Select all services you provide.
                                Services are loaded from the database.

                            </p>


                            <div
                                id="providerServicesContainer"
                                class="provider-edit-services"
                            >

                                <p class="provider-form-help">

                                    Please select a service category first.

                                </p>

                            </div>


                            <!--
                                IMPORTANT:

                                Selected services are stored here as hidden
                                inputs so selections from multiple categories
                                are submitted together.
                            -->

                            <div
                                id="providerSelectedServicesInputs"
                            ></div>


                        </div>



                        <!-- =================================================
                             PROFESSIONAL INFORMATION
                        ================================================== -->

                        <div class="provider-profile-section provider-form-full">


                            <div class="provider-section-heading">

                                <h3>
                                    Professional Information
                                </h3>

                                <p>
                                    Tell customers about your experience.
                                </p>

                            </div>


                        </div>



                        <!-- Experience -->

                        <div class="provider-form-group">

                            <label for="experience_years">
                                Experience (Years)
                            </label>


                            <input
                                type="number"
                                id="experience_years"
                                name="experience_years"
                                value="{{ old('experience_years', $provider->experience_years) }}"
                                min="0"
                                max="100"
                                required
                            >

                        </div>



                        <!-- About Me -->

                        <div class="provider-form-group provider-form-full">

                            <label for="bio">
                                About Me
                            </label>


                            <textarea
                                id="bio"
                                name="bio"
                                rows="5"
                                maxlength="2000"
                            >{{ old('bio', $provider->bio) }}</textarea>


                            <small class="provider-form-help">

                                Tell customers about your experience, skills,
                                and the services you provide.

                            </small>

                        </div>



                        <!-- =================================================
                             ACTION BUTTONS
                        ================================================== -->

                        <div class="provider-profile-form-actions provider-form-full">


                            <a
                                href="{{ route('provider.profile') }}"
                                class="profile-cancel-btn"
                            >

                                <i class="fa-solid fa-xmark"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="provider-primary-btn"
                            >

                                <i class="fa-solid fa-floppy-disk"></i>

                                Save Changes

                            </button>


                        </div>


                    </form>


                </div>


            </div>


        </main>


    </div>



    <!-- =====================================================
         BOOTSTRAP JS
    ====================================================== -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>



    <!-- =====================================================
         PROFILE IMAGE PREVIEW
    ====================================================== -->

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const imageInput =
                    document.getElementById(
                        'profile_image'
                    );


                const profileAvatars =
                    document.querySelectorAll(
                        '.provider-profile-large-avatar, .provider-avatar'
                    );


                if (
                    !imageInput ||
                    profileAvatars.length === 0
                ) {
                    return;
                }


                imageInput.addEventListener(
                    'change',
                    function () {

                        const file =
                            this.files?.[0];


                        if (!file) {
                            return;
                        }


                        if (
                            !file.type.startsWith('image/')
                        ) {

                            alert(
                                'Please select a valid image file.'
                            );

                            this.value = '';

                            return;
                        }


                        const reader =
                            new FileReader();


                        reader.onload =
                            function (event) {

                                profileAvatars.forEach(
                                    function (avatar) {

                                        avatar.innerHTML = `
                                            <img
                                                src="${event.target.result}"
                                                alt="Profile Preview"
                                            >
                                        `;

                                    }
                                );

                            };


                        reader.readAsDataURL(file);

                    }
                );

            }
        );

    </script>



    <!-- =====================================================
         CATEGORY → SERVICES
         DATABASE DRIVEN
         MULTIPLE CATEGORY SUPPORT
    ====================================================== -->

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const categorySelect =
                document.getElementById(
                    'service_category_id'
                );

            const servicesContainer =
                document.getElementById(
                    'providerServicesContainer'
                );

            const selectedCategoriesContainer =
                document.getElementById(
                    'providerSelectedCategories'
                );

            const selectedServicesInputs =
                document.getElementById(
                    'providerSelectedServicesInputs'
                );


            if (
                !categorySelect ||
                !servicesContainer ||
                !selectedCategoriesContainer ||
                !selectedServicesInputs
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | DATABASE DATA
            |--------------------------------------------------------------------------
            |
            | $categories comes from:
            |
            | service_categories
            |        ↓
            | services
            |
            */

            const categories = @json($categories);


            /*
            |--------------------------------------------------------------------------
            | PROVIDER'S EXISTING SERVICES
            |--------------------------------------------------------------------------
            |
            | These IDs come from:
            |
            | provider_services
            |
            */

            const selectedServiceIds =
                new Set(
                    @json($selectedServiceIds)
                        .map(Number)
                );


            /*
            |--------------------------------------------------------------------------
            | SELECTED CATEGORIES
            |--------------------------------------------------------------------------
            |
            | Categories are derived from selected services.
            |
            */

            const selectedCategoryIds =
                new Set();


            /*
            |--------------------------------------------------------------------------
            | FIND CATEGORIES FROM EXISTING SERVICES
            |--------------------------------------------------------------------------
            */

            function updateSelectedCategories() {

                selectedCategoryIds.clear();


                categories.forEach(
                    function (category) {

                        if (!category.services) {
                            return;
                        }


                        const hasSelectedService =
                            category.services.some(
                                function (service) {

                                    return selectedServiceIds.has(
                                        Number(service.id)
                                    );

                                }
                            );


                        if (hasSelectedService) {

                            selectedCategoryIds.add(
                                Number(category.id)
                            );

                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CREATE HIDDEN SERVICE INPUTS
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | The visible checkbox does NOT have name="services[]".
            |
            | Instead, all selected services are stored here.
            |
            | This allows the provider to select services from
            | multiple categories.
            |
            */

            function syncSelectedServiceInputs() {

                selectedServicesInputs.innerHTML = '';


                Array.from(selectedServiceIds)
                    .sort(function (a, b) {
                        return a - b;
                    })
                    .forEach(
                        function (serviceId) {

                            const input =
                                document.createElement(
                                    'input'
                                );


                            input.type = 'hidden';

                            input.name = 'services[]';

                            input.value = serviceId;


                            selectedServicesInputs.appendChild(
                                input
                            );

                        }
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | RENDER SELECTED CATEGORY BUTTONS
            |--------------------------------------------------------------------------
            */

            function renderSelectedCategories() {

                selectedCategoriesContainer.innerHTML = '';


                if (
                    selectedCategoryIds.size === 0
                ) {

                    selectedCategoriesContainer.innerHTML = `

                        <p class="provider-form-help">

                            No category selected yet.

                        </p>

                    `;

                    return;

                }


                selectedCategoryIds.forEach(
                    function (categoryId) {

                        const category =
                            categories.find(
                                function (item) {

                                    return Number(item.id) ===
                                        Number(categoryId);

                                }
                            );


                        if (!category) {
                            return;
                        }


                        const button =
                            document.createElement(
                                'button'
                            );


                        button.type = 'button';

                        button.className =
                            'provider-selected-category';

                        button.dataset.categoryId =
                            categoryId;


                        button.innerHTML = `

                            <span>

                                <i class="fa-solid fa-layer-group"></i>

                                ${category.name}

                            </span>

                            <i class="fa-solid fa-chevron-right"></i>

                        `;


                        button.addEventListener(
                            'click',
                            function () {

                                categorySelect.value =
                                    categoryId;


                                showCategoryServices(
                                    categoryId
                                );

                            }
                        );


                        selectedCategoriesContainer.appendChild(
                            button
                        );

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ESCAPE HTML
            |--------------------------------------------------------------------------
            |
            | Prevent database text from being inserted as raw HTML.
            |
            */

            function escapeHtml(value) {

                const div =
                    document.createElement('div');

                div.textContent =
                    value ?? '';

                return div.innerHTML;

            }


            /*
            |--------------------------------------------------------------------------
            | SHOW SERVICES FOR SELECTED CATEGORY
            |--------------------------------------------------------------------------
            */

            function showCategoryServices(categoryId) {

                servicesContainer.innerHTML = '';


                if (!categoryId) {

                    servicesContainer.innerHTML = `

                        <p class="provider-form-help">

                            Please select a service category first.

                        </p>

                    `;

                    return;

                }


                const category =
                    categories.find(
                        function (item) {

                            return Number(item.id) ===
                                Number(categoryId);

                        }
                    );


                if (
                    !category ||
                    !category.services ||
                    category.services.length === 0
                ) {

                    servicesContainer.innerHTML = `

                        <p class="provider-form-help">

                            No active services are available
                            in this category.

                        </p>

                    `;

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CATEGORY BOX
                |--------------------------------------------------------------------------
                */

                const categoryBox =
                    document.createElement(
                        'div'
                    );


                categoryBox.className =
                    'provider-service-category';


                /*
                |--------------------------------------------------------------------------
                | CATEGORY TITLE
                |--------------------------------------------------------------------------
                */

                const categoryTitle =
                    document.createElement(
                        'div'
                    );


                categoryTitle.className =
                    'provider-service-category-title';


                categoryTitle.innerHTML = `

                    <i class="fa-solid fa-layer-group"></i>

                    <span>
                        ${escapeHtml(category.name)}
                    </span>

                `;


                /*
                |--------------------------------------------------------------------------
                | SERVICE GRID
                |--------------------------------------------------------------------------
                */

                const serviceGrid =
                    document.createElement(
                        'div'
                    );


                serviceGrid.className =
                    'provider-service-checkbox-grid';


                /*
                |--------------------------------------------------------------------------
                | SERVICES FROM DATABASE
                |--------------------------------------------------------------------------
                */

                category.services.forEach(
                    function (service) {

                        const serviceId =
                            Number(service.id);


                        const label =
                            document.createElement(
                                'label'
                            );


                        label.className =
                            'provider-service-checkbox';


                        const isChecked =
                            selectedServiceIds.has(
                                serviceId
                            );


                        label.innerHTML = `

                            <input
                                type="checkbox"
                                value="${serviceId}"
                                ${isChecked ? 'checked' : ''}
                            >

                            <span class="provider-service-checkmark">

                                <i class="fa-solid fa-check"></i>

                            </span>

                            <span class="provider-service-checkbox-info">

                                <strong>
                                    ${escapeHtml(service.name)}
                                </strong>

                                ${
                                    service.description
                                        ? `
                                            <small>
                                                ${escapeHtml(service.description)}
                                            </small>
                                          `
                                        : ''
                                }

                            </span>

                        `;


                        const checkbox =
                            label.querySelector(
                                'input'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | SERVICE CHECKBOX CHANGE
                        |--------------------------------------------------------------------------
                        */

                        checkbox.addEventListener(
                            'change',
                            function () {

                                if (this.checked) {

                                    /*
                                    | Add service
                                    */

                                    selectedServiceIds.add(
                                        serviceId
                                    );

                                } else {

                                    /*
                                    | Remove service
                                    */

                                    selectedServiceIds.delete(
                                        serviceId
                                    );

                                }


                                /*
                                | Recalculate categories
                                */

                                updateSelectedCategories();


                                /*
                                | Update category buttons
                                */

                                renderSelectedCategories();


                                /*
                                | Update hidden form inputs
                                */

                                syncSelectedServiceInputs();

                            }
                        );


                        serviceGrid.appendChild(
                            label
                        );

                    }
                );


                categoryBox.appendChild(
                    categoryTitle
                );


                categoryBox.appendChild(
                    serviceGrid
                );


                servicesContainer.appendChild(
                    categoryBox
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CATEGORY DROPDOWN CHANGE
            |--------------------------------------------------------------------------
            */

            categorySelect.addEventListener(
                'change',
                function () {

                    const categoryId =
                        this.value;


                    showCategoryServices(
                        categoryId
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INITIALIZE DATABASE DATA
            |--------------------------------------------------------------------------
            */

            updateSelectedCategories();

            renderSelectedCategories();

            syncSelectedServiceInputs();


            /*
            |--------------------------------------------------------------------------
            | OPEN FIRST EXISTING CATEGORY
            |--------------------------------------------------------------------------
            */

            if (
                selectedCategoryIds.size > 0
            ) {

                const firstCategoryId =
                    Array.from(
                        selectedCategoryIds
                    )[0];


                categorySelect.value =
                    firstCategoryId;


                showCategoryServices(
                    firstCategoryId
                );

            }

        }
    );

</script>


    <!-- =====================================================
         PROVIDER SIDEBAR + THEME
    ====================================================== -->

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {


                const layout =
                    document.getElementById(
                        'providerLayout'
                    );


                const sidebar =
                    document.querySelector(
                        '.provider-sidebar'
                    );


                const sidebarToggle =
                    document.querySelector(
                        '.sidebar-toggle'
                    );


                const mobileMenu =
                    document.getElementById(
                        'providerMobileMenu'
                    );


                const overlay =
                    document.getElementById(
                        'providerOverlay'
                    );


                const logoToggle =
                    document.querySelector(
                        '.sidebar-logo-toggle'
                    );


                const themeToggle =
                    document.getElementById(
                        'themeToggle'
                    );


                const themeIcon =
                    document.getElementById(
                        'themeIcon'
                    );



                /* =================================================
                   DESKTOP SIDEBAR COLLAPSE
                ================================================== */

                if (sidebarToggle) {

                    sidebarToggle.addEventListener(
                        'click',
                        function () {

                            if (layout) {

                                layout.classList.toggle(
                                    'sidebar-collapsed'
                                );

                            }

                        }
                    );

                }



                /* =================================================
                   LOGO CLICK
                ================================================== */

                if (logoToggle) {

                    logoToggle.addEventListener(
                        'click',
                        function () {

                            if (layout) {

                                layout.classList.toggle(
                                    'sidebar-collapsed'
                                );

                            }

                        }
                    );

                }



                /* =================================================
                   MOBILE SIDEBAR OPEN
                ================================================== */

                if (mobileMenu) {

                    mobileMenu.addEventListener(
                        'click',
                        function () {

                            if (layout) {

                                layout.classList.add(
                                    'mobile-sidebar-open'
                                );

                            }

                        }
                    );

                }



                /* =================================================
                   CLOSE MOBILE SIDEBAR
                ================================================== */

                if (overlay) {

                    overlay.addEventListener(
                        'click',
                        function () {

                            if (layout) {

                                layout.classList.remove(
                                    'mobile-sidebar-open'
                                );

                            }

                        }
                    );

                }



                /* =================================================
                   CLOSE SIDEBAR WHEN CLICKING MENU
                ================================================== */

                document
                    .querySelectorAll(
                        '.provider-menu-link'
                    )
                    .forEach(
                        function (link) {

                            link.addEventListener(
                                'click',
                                function () {

                                    if (
                                        window.innerWidth <= 767
                                    ) {

                                        if (layout) {

                                            layout.classList.remove(
                                                'mobile-sidebar-open'
                                            );

                                        }

                                    }

                                }
                            );

                        }
                    );



                /* =================================================
                   THEME TOGGLE
                ================================================== */

                function updateThemeIcon() {

                    if (!themeIcon) {
                        return;
                    }


                    if (
                        document.body.classList.contains(
                            'dark-mode'
                        )
                    ) {

                        themeIcon.className =
                            'fa-solid fa-sun';

                    } else {

                        themeIcon.className =
                            'fa-solid fa-moon';

                    }

                }



                if (themeToggle) {

                    themeToggle.addEventListener(
                        'click',
                        function () {

                            document.body.classList.toggle(
                                'dark-mode'
                            );


                            const darkMode =
                                document.body.classList.contains(
                                    'dark-mode'
                                );


                            localStorage.setItem(
                                'provider-theme',
                                darkMode
                                    ? 'dark'
                                    : 'light'
                            );


                            updateThemeIcon();

                        }
                    );

                }



                /* =================================================
                   LOAD SAVED THEME
                ================================================== */

                const savedTheme =
                    localStorage.getItem(
                        'provider-theme'
                    );


                if (savedTheme === 'dark') {

                    document.body.classList.add(
                        'dark-mode'
                    );

                }


                updateThemeIcon();


            }
        );

    </script>



    <!-- =====================================================
         EXISTING PROVIDER JS
    ====================================================== -->

    <script
        src="{{ asset('js/provider.js') }}"
    ></script>


</body>

</html>