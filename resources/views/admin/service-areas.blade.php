@extends('layouts.admin')

@section('title', 'Service Areas')

@section('content')
<div class="admin-dashboard admin-service-areas-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
                Service Areas
            </h1>

            <p>
                Manage service areas within QuickFix cities.
            </p>

        </div>

        <div class="admin-page-header-actions">

            

        </div>

    </div>


   
    <!-- =====================================================
         SERVICE AREA STATISTICS
    ====================================================== -->

    <div class="admin-stat-grid">

        <!-- TOTAL SERVICE AREAS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon blue">

                <i class="fa-solid fa-location-dot"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Total Service Areas
                </span>

                <h2>
                    {{ number_format($totalServiceAreas) }}
                </h2>

                <div class="admin-stat-note">

                    {{ number_format($newServiceAreasThisMonth) }}
                    new this month

                </div>

            </div>

        </div>


        <!-- ACTIVE AREAS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon green">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Active Areas
                </span>

                <h2>
                    {{ number_format($activeServiceAreas) }}
                </h2>

                <div class="admin-stat-note">

                    Currently available

                </div>

            </div>

        </div>


        <!-- CITIES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon purple">

                <i class="fa-solid fa-city"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Cities Covered
                </span>

                <h2>
                    {{ number_format($citiesCovered) }}
                </h2>

                <div class="admin-stat-note">

                    Cities with service areas

                </div>

            </div>

        </div>


        <!-- PROVIDERS -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon orange">

                <i class="fa-solid fa-user-tie"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Available Providers
                </span>

                <h2>
                    {{ number_format($availableProviders) }}
                </h2>

                <div class="admin-stat-note">

                    Across active service areas

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SERVICE AREA MANAGEMENT CARD
    ====================================================== -->

    <div class="admin-card admin-service-areas-card">


        <!-- =================================================
             CARD HEADER
        ================================================== -->

        <div class="admin-card-header">

            <div>

                <h3>
                    All Service Areas
                </h3>

                <p>
                    Service area information and availability
                </p>

            </div>

            <a
                href="{{ route('admin.service-areas.create') }}"
                class="admin-small-btn"
            >

                <i class="fa-solid fa-plus"></i>

                <span>
                    Add Service Area
                </span>

            </a>

        </div>


        <!-- =================================================
             TOOLBAR
        ================================================== -->

        <div class="admin-service-area-toolbar">

            <form
                action="{{ route('admin.service-areas.index') }}"
                method="GET"
                class="admin-service-area-filter-form"
            >


                <!-- SEARCH -->

                <div class="admin-service-area-search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        id="serviceAreaSearch"
                        value="{{ request('search') }}"
                        placeholder="Search service areas..."
                        autocomplete="off"
                    >

                </div>


                <!-- CITY FILTER -->

                <div class="admin-service-area-filter">

                    <select
                        id="serviceAreaCityFilter"
                        name="city_id"
                    >

                        <option value="">
                            All Cities
                        </option>

                        @foreach ($cities as $city)

                            <option
                                value="{{ $city->id }}"
                                {{ request('city_id') == $city->id ? 'selected' : '' }}
                            >
                                {{ $city->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <!-- STATUS FILTER -->

                <div class="admin-service-area-filter">

                    <select
                        id="serviceAreaStatusFilter"
                        name="status"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- FILTER BUTTON -->

                <button
                    type="submit"
                    class="admin-small-btn"
                >

                    <i class="fa-solid fa-filter"></i>

                    <span>
                        Filter
                    </span>

                </button>


                <!-- CLEAR -->

                @if (
                    request()->filled('search')
                    || request()->filled('city_id')
                    || request()->filled('status')
                )

                    <a
                        href="{{ route('admin.service-areas.index') }}"
                        class="admin-small-btn"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        <span>
                            Clear
                        </span>

                    </a>

                @endif

            </form>

        </div>


        <!-- =================================================
             SERVICE AREA TABLE
        ================================================== -->

        <div class="admin-table-wrapper">

            <table
                class="admin-bookings-table admin-service-areas-table"
                id="serviceAreasTable"
            >

                <thead>

                    <tr>

                        <th>
                            City
                        </th>

                        <th>
                            Service Area
                        </th>

                        <th>
                            Area ID
                        </th>

                        <th>
                            Providers
                        </th>

                        <th>
                            Customers
                        </th>

                        <th>
                            Services
                        </th>

                        <th>
                            Bookings
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($serviceAreas as $serviceArea)

                        @php

                            $isActive = $serviceArea->is_active == 1;

                        @endphp

                        <tr>


                            <!-- CITY -->

                            <td>

                                <div class="admin-service-area-city">

                                    <i class="fa-solid fa-city"></i>

                                    <strong>
                                        {{ $serviceArea->city->name ?? 'N/A' }}
                                    </strong>

                                </div>

                            </td>


                            <!-- SERVICE AREA -->

                            <td>

                                <div class="admin-area-info">

                                    <strong>
                                        {{ $serviceArea->name }}
                                    </strong>

                                    <span>
                                        {{ $serviceArea->city->name ?? 'N/A' }}
                                        service area
                                    </span>

                                </div>

                            </td>


                            <!-- AREA ID -->

                            <td>

                                #AREA-{{ str_pad($serviceArea->id, 3, '0', STR_PAD_LEFT) }}

                            </td>


                            <!-- PROVIDERS -->

                            <td>

                                {{ number_format($serviceArea->providers_count) }}

                            </td>


                            <!-- CUSTOMERS -->

                            <td>

                                {{ number_format($serviceArea->customers_count) }}

                            </td>


                            <!-- SERVICES -->

                            <td>

                                {{ number_format($serviceArea->services_count) }}

                            </td>


                            <!-- BOOKINGS -->

                            <td>

                                {{ number_format($serviceArea->bookings_count) }}

                            </td>


                            <!-- STATUS -->

                            <td>

                                @if ($isActive)

                                    <span class="admin-service-area-status active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="admin-service-area-status inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="admin-service-area-actions">


                                    <!-- VIEW -->

                                    <a
                                        href="{{ route('admin.service-areas.show', $serviceArea->id) }}"
                                        class="admin-table-action view"
                                        title="View Service Area"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                    </a>


                                    <!-- EDIT -->

                                    <a
                                        href="{{ route('admin.service-areas.edit', $serviceArea->id) }}"
                                        class="admin-table-action edit"
                                        title="Edit Service Area"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    <!-- DEACTIVATE -->

                                    @if ($isActive)

                                        <form
                                            action="{{ route('admin.service-areas.destroy', $serviceArea->id) }}"
                                            method="POST"
                                            class="service-area-action-form"
                                            onsubmit="return confirm('Are you sure you want to deactivate this service area?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="admin-table-action delete"
                                                title="Deactivate Service Area"
                                            >

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>


                                    <!-- REACTIVATE -->

                                    @else

                                        <form
                                            action="{{ route('admin.service-areas.reactivate', $serviceArea->id) }}"
                                            method="POST"
                                            class="service-area-action-form"
                                            onsubmit="return confirm('Are you sure you want to reactivate this service area?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="admin-table-action reactivate"
                                                title="Reactivate Service Area"
                                            >

                                                <i class="fa-solid fa-rotate-right"></i>

                                            </button>

                                        </form>

                                    @endif


                                </div>

                            </td>

                        </tr>


                    @empty


                        <!-- EMPTY STATE -->

                        <tr>

                            <td
                                colspan="9"
                                class="admin-empty-state"
                            >

                                <div>

                                    <i class="fa-solid fa-location-dot"></i>

                                    <h4>
                                        No service areas found
                                    </h4>

                                    <p>

                                        There are no service areas
                                        matching your current filters.

                                    </p>

                                </div>

                            </td>

                        </tr>


                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="admin-service-areas-footer">

            <span id="serviceAreaResultCount">

                Showing

                {{ $serviceAreas->firstItem() ?? 0 }}

                to

                {{ $serviceAreas->lastItem() ?? 0 }}

                of

                {{ $serviceAreas->total() }}

                service areas

            </span>


            <!-- PAGINATION -->

            <div class="admin-pagination">

                {{ $serviceAreas->onEachSide(1)->links() }}

            </div>

        </div>


    </div>

</div>


@endsection
