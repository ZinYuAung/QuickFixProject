@extends('layouts.admin')

@section('title', 'Service Area Details')

@section('content')

<div class="admin-dashboard admin-service-area-show-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <div class="admin-breadcrumb">

                <a href="{{ route('admin.service-areas.index') }}">
                    Service Areas
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Area Details
                </span>

            </div>

            <div class="admin-detail-title-row">

                <div>

                    <h1>
                        {{ $serviceArea->name }}
                    </h1>

                    <p>
                        {{ $serviceArea->city->name ?? 'N/A' }} service area
                    </p>

                </div>

                <span
                    class="admin-service-area-status {{ $serviceArea->is_active ? 'active' : 'inactive' }}"
                >

                    <span class="status-dot"></span>

                    {{ $serviceArea->is_active ? 'Active' : 'Inactive' }}

                </span>

            </div>

        </div>

        <div class="admin-page-header-actions">

            <a
                href="{{ route('admin.service-areas.index') }}"
                class="admin-small-btn admin-secondary-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back
                </span>
            </a>

            <a
                href="{{ route('admin.service-areas.edit', $serviceArea->id) }}"
                class="admin-small-btn"
            >
                <i class="fa-solid fa-pen"></i>

                <span>
                    Edit
                </span>
            </a>

        </div>

    </div>


    <!-- =====================================================
         OVERVIEW
    ====================================================== -->

    <div class="admin-card admin-service-area-overview-card">

        <div class="admin-card-header">

            <div>

                <h3>
                    Service Area Overview
                </h3>

                <p>
                    Basic information about this service area.
                </p>

            </div>

        </div>


        <div class="admin-service-area-overview-grid">


            <div class="admin-service-area-info-item">

                <div class="admin-service-area-info-icon blue">

                    <i class="fa-solid fa-city"></i>

                </div>

                <div>

                    <small>
                        City
                    </small>

                    <strong>
                        {{ $serviceArea->city->name ?? 'N/A' }}
                    </strong>

                </div>

            </div>


            <div class="admin-service-area-info-item">

                <div class="admin-service-area-info-icon purple">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <div>

                    <small>
                        Service Area
                    </small>

                    <strong>
                        {{ $serviceArea->name }}
                    </strong>

                </div>

            </div>


            <div class="admin-service-area-info-item">

                <div class="admin-service-area-info-icon orange">

                    <i class="fa-solid fa-hashtag"></i>

                </div>

                <div>

                    <small>
                        Area ID
                    </small>

                    <strong>
                        #AREA-{{ str_pad($serviceArea->id, 3, '0', STR_PAD_LEFT) }}
                    </strong>

                </div>

            </div>


            <div class="admin-service-area-info-item">

                <div class="admin-service-area-info-icon green">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div>

                    <small>
                        Status
                    </small>

                    <strong>
                        {{ $serviceArea->is_active ? 'Active' : 'Inactive' }}
                    </strong>

                </div>

            </div>


        </div>

    </div>


    <!-- =====================================================
         STATISTICS
    ====================================================== -->

    <div class="admin-stat-grid admin-service-area-detail-stats">


        <!-- PROVIDERS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon orange">

                <i class="fa-solid fa-user-tie"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Providers
                </span>

                <h2>
                    {{ number_format($serviceArea->providers_count) }}
                </h2>

                <div class="admin-stat-note">
                    Providers assigned to this area
                </div>

            </div>

        </div>


        <!-- CUSTOMERS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon blue">

                <i class="fa-solid fa-users"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Customers
                </span>

                <h2>
                    {{ number_format($serviceArea->customers_count) }}
                </h2>

                <div class="admin-stat-note">
                    Customers in this area
                </div>

            </div>

        </div>


        <!-- SERVICES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon purple">

                <i class="fa-solid fa-screwdriver-wrench"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Services
                </span>

                <h2>
                    {{ number_format($serviceArea->services_count) }}
                </h2>

                <div class="admin-stat-note">
                    Services available here
                </div>

            </div>

        </div>


        <!-- BOOKINGS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon green">

                <i class="fa-solid fa-calendar-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Bookings
                </span>

                <h2>
                    {{ number_format($serviceArea->bookings_count) }}
                </h2>

                <div class="admin-stat-note">
                    Bookings in this area
                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         LOWER GRID
    ====================================================== -->

    <div class="admin-service-area-show-grid">


        <!-- =================================================
             SERVICES
        ================================================== -->

        <div class="admin-card admin-service-area-services-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Available Services
                    </h3>

                    <p>
                        Services currently assigned to this area.
                    </p>

                </div>

            </div>


            <div class="admin-service-area-services-list">

                @forelse ($serviceArea->services as $service)

                    <div class="admin-service-detail-item">

                        <div class="admin-service-detail-icon">

                            <i class="fa-solid fa-wrench"></i>

                        </div>

                        <div class="admin-service-detail-content">

                            <strong>
                                {{ $service->name }}
                            </strong>

                            <span>
                                {{ $service->category->name ?? 'Uncategorized' }}
                            </span>

                        </div>

                        <div class="admin-service-detail-price">

                            <strong>
                                {{ number_format($service->base_price, 2) }}
                            </strong>

                            <small>
                                Base price
                            </small>

                        </div>

                    </div>

                @empty

                    <div class="admin-detail-empty">

                        <i class="fa-solid fa-wrench"></i>

                        <h4>
                            No services assigned
                        </h4>

                        <p>
                            No services are currently available in this service area.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>


        <!-- =================================================
             AREA INFORMATION
        ================================================== -->

        <div class="admin-service-area-side-column">


            <!-- CITY CARD -->

            <div class="admin-card admin-city-info-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            City Information
                        </h3>

                        <p>
                            Parent city
                        </p>

                    </div>

                </div>

                <div class="admin-city-info-body">

                    <div class="admin-city-detail-icon">

                        <i class="fa-solid fa-city"></i>

                    </div>

                    <h4>
                        {{ $serviceArea->city->name ?? 'N/A' }}
                    </h4>

                    <p>
                        Parent city for this service area
                    </p>

                    @if ($serviceArea->city)

                        <a
                            href="{{ route('admin.cities.show', $serviceArea->city->id) }}"
                            class="admin-detail-link"
                        >
                            View City
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    @endif

                </div>

            </div>


            <!-- DATES -->

            <div class="admin-card admin-service-area-dates-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Information
                        </h3>

                    </div>

                </div>

                <div class="admin-detail-dates">

                    <div>

                        <small>
                            Created
                        </small>

                        <strong>
                            {{ $serviceArea->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </strong>

                    </div>

                    <div>

                        <small>
                            Last Updated
                        </small>

                        <strong>
                            {{ $serviceArea->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </strong>

                    </div>

                </div>

            </div>


        </div>

    </div>


    <!-- =====================================================
         STATUS ACTION
    ====================================================== -->

    <div class="admin-card admin-service-area-action-card">

        <div>

            <h3>
                {{ $serviceArea->is_active
                    ? 'Deactivate Service Area'
                    : 'Reactivate Service Area' }}
            </h3>

            <p>
                {{ $serviceArea->is_active
                    ? 'Deactivating this area will make it unavailable for new QuickFix services.'
                    : 'Reactivating this area will make it available again.' }}
            </p>

        </div>


        @if ($serviceArea->is_active)

            <form
                action="{{ route('admin.service-areas.destroy', $serviceArea->id) }}"
                method="POST"
                id="deactivateServiceAreaForm"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="admin-danger-btn"
                >

                    <i class="fa-solid fa-trash"></i>

                    Deactivate

                </button>

            </form>

        @else

            <form
                action="{{ route('admin.service-areas.reactivate', $serviceArea->id) }}"
                method="POST"
                id="reactivateServiceAreaForm"
            >

                @csrf

                <button
                    type="submit"
                    class="admin-success-btn"
                >

                    <i class="fa-solid fa-rotate-right"></i>

                    Reactivate

                </button>

            </form>

        @endif

    </div>

</div>

@endsection


@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/admin-service-area-show.css') }}"
>
@endpush


@push('scripts')
<script src="{{ asset('js/admin-service-area-show.js') }}"></script>
@endpush