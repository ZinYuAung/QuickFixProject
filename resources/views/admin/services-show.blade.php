@extends('layouts.admin')

@section('title', 'Service Details')

@push('styles') <link
     rel="stylesheet"
     href="{{ asset('css/admin-service-show.css') }}"
 >
@endpush

@section('content')

<div class="admin-dashboard admin-service-show-page">

{{-- =========================================================
     PAGE HEADER
========================================================== --}}

<div class="admin-page-header">

    <div class="admin-page-header-content">

        <div class="admin-service-breadcrumb">

            <a href="{{ route('admin.services.index') }}">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                Services
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <span>
                Service Details
            </span>

        </div>


        <h1>
           
        </h1>


        <p>
           
        </p>

    </div>


    <div class="admin-page-header-actions">

        <a
            href="{{ route('admin.services.edit', $service->id) }}"
            class="admin-primary-btn"
        >
            <i class="fa-solid fa-pen"></i>

            <span>
                Edit Service
            </span>
        </a>

    </div>

</div>





{{-- =========================================================
     SERVICE HERO / OVERVIEW
========================================================== --}}

<div class="admin-service-detail-grid">


    {{-- =====================================================
         SERVICE INFORMATION CARD
    ====================================================== --}}

    <div class="admin-card admin-service-main-card">

        <div class="admin-service-main-top">


            {{-- SERVICE ICON --}}

            <div class="admin-service-large-icon">

                <i class="fa-solid fa-screwdriver-wrench"></i>

            </div>


            {{-- SERVICE TITLE --}}

            <div class="admin-service-main-title">

                <span class="admin-service-id">

                    #SER-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}

                </span>


                <h2>
                    {{ $service->name }}
                </h2>


                {{-- STATUS --}}

                @if ($service->is_active)

                    <span class="admin-service-status active">

                        <span class="status-dot"></span>

                        Active

                    </span>

                @else

                    <span class="admin-service-status inactive">

                        <span class="status-dot"></span>

                        Inactive

                    </span>

                @endif

            </div>

        </div>



        {{-- DESCRIPTION --}}

        <div class="admin-service-description">

            <h3>
                Description
            </h3>

            @if ($service->description)

                <p>
                    {{ $service->description }}
                </p>

            @else

                <p class="admin-service-no-description">
                    No description has been added for this service.
                </p>

            @endif

        </div>

    </div>



    {{-- =====================================================
         QUICK STATISTICS
    ====================================================== --}}

    <div class="admin-card admin-service-stats-card">

        <div class="admin-card-header">

            <div>

                <h3>
                    Service Overview
                </h3>

                <p>
                    Current service statistics
                </p>

            </div>

        </div>


        <div class="admin-service-stat-list">


            {{-- CATEGORY --}}

            <div class="admin-service-stat-item">

                <div class="admin-service-stat-icon category">

                    <i class="fa-solid fa-layer-group"></i>

                </div>

                <div class="admin-service-stat-info">

                    <span>
                        Category
                    </span>

                    <strong>
                        {{ $service->category?->name ?? '—' }}
                    </strong>

                </div>

            </div>


            {{-- PRICE --}}

            <div class="admin-service-stat-item">

                <div class="admin-service-stat-icon price">

                    <i class="fa-solid fa-money-bill-wave"></i>

                </div>

                <div class="admin-service-stat-info">

                    <span>
                        Base Price
                    </span>

                    <strong>
                        {{ number_format($service->base_price) }} MMK
                    </strong>

                </div>

            </div>


            {{-- TIME --}}

            <div class="admin-service-stat-item">

                <div class="admin-service-stat-icon time">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div class="admin-service-stat-info">

                    <span>
                        Estimated Time
                    </span>

                    <strong>
                        {{ $service->estimated_minutes }} minutes
                    </strong>

                </div>

            </div>


            {{-- PROVIDERS --}}

            <div class="admin-service-stat-item">

                <div class="admin-service-stat-icon providers">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div class="admin-service-stat-info">

                    <span>
                        Providers
                    </span>

                    <strong>
                        {{ $service->providers->count() }}
                    </strong>

                </div>

            </div>


            {{-- BOOKINGS --}}

            <div class="admin-service-stat-item">

                <div class="admin-service-stat-icon bookings">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>

                <div class="admin-service-stat-info">

                    <span>
                        Bookings
                    </span>

                    <strong>
                        {{ $bookingCount }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     ASSIGNED PROVIDERS
========================================================== --}}

<div class="admin-card admin-service-section-card">


    {{-- CARD HEADER --}}

    <div class="admin-card-header">

        <div>

            <h3>
                Assigned Providers
            </h3>

            <p>
                Providers currently assigned to this service.
            </p>

        </div>


        <div class="admin-service-count-badge">

            {{ $service->providers->count() }}

            {{ $service->providers->count() === 1 ? 'Provider' : 'Providers' }}

        </div>

    </div>



    {{-- PROVIDER LIST --}}

    @if ($service->providers->count() > 0)

        <div class="admin-service-provider-list">

            @foreach ($service->providers as $provider)

                <div class="admin-service-provider-item">


                    {{-- PROVIDER AVATAR --}}

                    <div class="admin-service-provider-avatar">

                        @if (!empty($provider->profile_image))

                            <img
                                src="{{ asset('storage/' . $provider->profile_image) }}"
                                alt="{{ $provider->name ?? 'Provider' }}"
                            >

                        @else

                            <i class="fa-solid fa-user"></i>

                        @endif

                    </div>



                    {{-- PROVIDER INFORMATION --}}

                    <div class="admin-service-provider-info">

                        <strong>
                            {{ $provider?->user?->name ?? 'Provider' }}
                                
                        </strong>

                        <span>
                            Provider #{{ $provider->id }}
                        </span>

                    </div>



                    {{-- PROVIDER STATUS --}}

                    <div class="admin-service-provider-status">

                        @if ($provider->is_active ?? false)

                            <span class="provider-status active">

                                <span class="provider-status-dot"></span>

                                Active

                            </span>

                        @else

                            <span class="provider-status inactive">

                                <span class="provider-status-dot"></span>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- EMPTY PROVIDER STATE --}}

        <div class="admin-service-empty">

            <div class="admin-service-empty-icon">

                <i class="fa-solid fa-user-slash"></i>

            </div>

            <h4>
                No Providers Assigned
            </h4>

            <p>
                There are currently no providers assigned to this service.
            </p>

        </div>

    @endif

</div>



{{-- =========================================================
     SERVICE DETAILS
========================================================== --}}

<div class="admin-card admin-service-section-card">


    <div class="admin-card-header">

        <div>

            <h3>
                Service Information
            </h3>

            <p>
                Basic information and configuration for this service.
            </p>

        </div>

    </div>



    <div class="admin-service-information-grid">


        {{-- SERVICE ID --}}

        <div class="admin-service-information-item">

            <span>
                Service ID
            </span>

            <strong>
                #SER-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}
            </strong>

        </div>


        {{-- SERVICE NAME --}}

        <div class="admin-service-information-item">

            <span>
                Service Name
            </span>

            <strong>
                {{ $service->name }}
            </strong>

        </div>


        {{-- CATEGORY --}}

        <div class="admin-service-information-item">

            <span>
                Category
            </span>

            <strong>
                {{ $service->category?->name ?? '—' }}
            </strong>

        </div>


        {{-- BASE PRICE --}}

        <div class="admin-service-information-item">

            <span>
                Base Price
            </span>

            <strong>
                {{ number_format($service->base_price) }} MMK
            </strong>

        </div>


        {{-- ESTIMATED TIME --}}

        <div class="admin-service-information-item">

            <span>
                Estimated Time
            </span>

            <strong>
                {{ $service->estimated_minutes }} minutes
            </strong>

        </div>


        {{-- PROVIDERS --}}

        <div class="admin-service-information-item">

            <span>
                Assigned Providers
            </span>

            <strong>
                {{ $service->providers->count() }}
            </strong>

        </div>


        {{-- BOOKINGS --}}

        <div class="admin-service-information-item">

            <span>
                Total Bookings
            </span>

            <strong>
                {{ $bookingCount }}
            </strong>

        </div>


        {{-- STATUS --}}

        <div class="admin-service-information-item">

            <span>
                Current Status
            </span>

            <strong
                class="{{ $service->is_active ? 'text-success' : 'text-danger' }}"
            >
                {{ $service->is_active ? 'Active' : 'Inactive' }}
            </strong>

        </div>

    </div>

</div>



{{-- =========================================================
     BOTTOM ACTIONS
========================================================== --}}

<div class="admin-service-bottom-actions">


    <a
        href="{{ route('admin.services.index') }}"
        class="admin-secondary-btn"
    >

        <i class="fa-solid fa-arrow-left"></i>

        <span>
            Back to Services
        </span>

    </a>


    <div class="admin-service-bottom-right">


        {{-- EDIT --}}

        <a
            href="{{ route('admin.services.edit', $service->id) }}"
            class="admin-primary-btn"
        >

            <i class="fa-solid fa-pen"></i>

            <span>
                Edit Service
            </span>

        </a>


        {{-- DEACTIVATE / REACTIVATE --}}

        @if ($service->is_active)

            <form
                action="{{ route('admin.services.destroy', $service->id) }}"
                method="POST"
                class="admin-service-action-form"
                data-confirm-message="Are you sure you want to deactivate this service?"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="admin-danger-btn"
                >

                    <i class="fa-solid fa-trash"></i>

                    <span>
                        Deactivate
                    </span>

                </button>

            </form>

        @else

            <form
                action="{{ route('admin.services.reactivate', $service->id) }}"
                method="POST"
                class="admin-service-action-form"
                data-confirm-message="Are you sure you want to reactivate this service?"
            >

                @csrf

                <button
                    type="submit"
                    class="admin-success-btn"
                >

                    <i class="fa-solid fa-rotate-right"></i>

                    <span>
                        Reactivate
                    </span>

                </button>

            </form>

        @endif

    </div>

</div>

</div>

@endsection

@push('scripts')


<script src="{{ asset('js/admin-service-show.js') }}"></script>


@endpush
