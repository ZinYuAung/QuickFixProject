@extends('layouts.admin')

@section('title', 'Cities')

@section('content')

    <div class="admin-dashboard admin-cities-page">

        <!-- =====================================================
             PAGE HEADER
        ====================================================== -->

        <div class="admin-page-header">

            <div class="admin-page-header-content">

                <h1>
                    Cities
                </h1>

                <p>
                    View and manage cities available on QuickFix.
                </p>

            </div>

            <div class="admin-page-header-actions">

               

            </div>

        </div>


       
        <!-- =====================================================
             CITY STATISTICS
        ====================================================== -->

        <div class="admin-stat-grid admin-city-stat-grid">

            <!-- TOTAL CITIES -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon services">

                    <i class="fa-solid fa-city"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Total Cities
                    </span>

                    <h2>
                        {{ number_format($totalCities) }}
                    </h2>

                    <div class="admin-stat-note">

                        {{ number_format($newCitiesThisMonth) }}
                        new this month

                    </div>

                </div>

            </div>


            <!-- ACTIVE CITIES -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon users">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Active Cities
                    </span>

                    <h2>
                        {{ number_format($activeCities) }}
                    </h2>

                    <div class="admin-stat-note">

                        Currently available

                    </div>

                </div>

            </div>


            <!-- SERVICE AREAS -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon providers">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Service Areas
                    </span>

                    <h2>
                        {{ number_format($totalServiceAreas) }}
                    </h2>

                    <div class="admin-stat-note">

                        Across all cities

                    </div>

                </div>

            </div>


            <!-- PROVIDERS -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon bookings">

                    <i class="fa-solid fa-user-tie"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Available Providers
                    </span>

                    <h2>
                        {{ number_format($totalProviders) }}
                    </h2>

                    <div class="admin-stat-note">

                        Across all cities

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             CITY MANAGEMENT CARD
        ====================================================== -->

        <div class="admin-card admin-cities-card">


            <!-- =================================================
                 CARD HEADER
            ================================================== -->

            <div class="admin-card-header">

                <div>

                    <h3>
                        All Cities
                    </h3>

                    <p>
                        City information and service availability
                    </p>

                </div>

                <a
                    href="{{ route('admin.cities.create') }}"
                    class="admin-small-btn"
                >

                    <i class="fa-solid fa-plus"></i>

                    <span>
                        Add City
                    </span>

                </a>

            </div>


            <!-- =================================================
                 TOOLBAR
            ================================================== -->

            <div class="admin-city-toolbar">

                <form
                    action="{{ route('admin.cities.index') }}"
                    method="GET"
                    class="admin-city-filter-form"
                >

                    <!-- SEARCH -->

                    <div class="admin-city-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            name="search"
                            id="citySearch"
                            value="{{ request('search') }}"
                            placeholder="Search cities..."
                            autocomplete="off"
                        >

                    </div>


                    <!-- STATUS -->

                    <div class="admin-city-filter">

                        <select
                            id="cityStatusFilter"
                            name="status"
                            class="admin-filter-select"
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
                        || request()->filled('status')
                    )

                        <a
                            href="{{ route('admin.cities.index') }}"
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
                 CITY TABLE
            ================================================== -->

            <div class="admin-table-wrapper">

                <table
                    class="admin-bookings-table admin-cities-table"
                    id="citiesTable"
                >

                    <thead>

                        <tr>

                            <th>
                                City
                            </th>

                            <th>
                                City ID
                            </th>

                            <th>
                                Service Areas
                            </th>

                            <th>
                                Providers
                            </th>

                            <th>
                                Customers
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

                        @forelse ($cities as $city)

                            @php

                                $isActive = $city->is_active == 1;

                            @endphp

                            <tr>


                                <!-- CITY -->

                                <td>

                                    <div class="admin-city-cell">

                                        <div class="admin-city-icon services">

                                            <i class="fa-solid fa-city"></i>

                                        </div>

                                        <div class="admin-city-info">

                                            <strong>
                                                {{ $city->name }}
                                            </strong>

                                            <small>
                                                #CITY-{{ str_pad($city->id, 3, '0', STR_PAD_LEFT) }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- CITY ID -->

                                <td>

                                    #CITY-{{ str_pad($city->id, 3, '0', STR_PAD_LEFT) }}

                                </td>


                                <!-- SERVICE AREAS -->

                                <td>

                                    {{ number_format($city->service_areas_count) }}

                                </td>


                                <!-- PROVIDERS -->

                                <td>

                                    {{ number_format($city->providers_count) }}

                                </td>


                                <!-- CUSTOMERS -->

                                <td>

                                    {{ number_format($city->customers_count) }}

                                </td>


                                <!-- BOOKINGS -->

                                <td>

                                    {{ number_format($city->bookings_count) }}

                                </td>


                                <!-- STATUS -->

                                <td>

                                    @if ($isActive)

                                        <span class="admin-city-status active">

                                            <span class="status-dot"></span>

                                            Active

                                        </span>

                                    @else

                                        <span class="admin-city-status inactive">

                                            <span class="status-dot"></span>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="admin-city-actions">


                                        <!-- VIEW -->

                                        <a
                                            href="{{ route('admin.cities.show', $city->id) }}"
                                            class="admin-table-action view"
                                            title="View City"
                                        >

                                            <i class="fa-solid fa-eye"></i>

                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('admin.cities.edit', $city->id) }}"
                                            class="admin-table-action edit"
                                            title="Edit City"
                                        >

                                            <i class="fa-solid fa-pen"></i>

                                        </a>


                                        <!-- DEACTIVATE -->

                                        @if ($isActive)

                                            <form
                                                action="{{ route('admin.cities.destroy', $city->id) }}"
                                                method="POST"
                                                class="city-action-form"
                                                onsubmit="return confirm('Are you sure you want to deactivate this city?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="admin-table-action delete"
                                                    title="Deactivate City"
                                                >

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>


                                        <!-- REACTIVATE -->

                                        @else

                                            <form
                                                action="{{ route('admin.cities.reactivate', $city->id) }}"
                                                method="POST"
                                                class="city-action-form"
                                                onsubmit="return confirm('Are you sure you want to reactivate this city?');"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="admin-table-action reactivate"
                                                    title="Reactivate City"
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
                                    colspan="8"
                                    class="admin-empty-state"
                                >

                                    <div>

                                        <i class="fa-solid fa-city"></i>

                                        <h4>
                                            No cities found
                                        </h4>

                                        <p>
                                            There are no cities matching your current filters.
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

            <div class="admin-cities-footer">

                <span id="cityResultCount">

                    Showing

                    {{ $cities->firstItem() ?? 0 }}

                    to

                    {{ $cities->lastItem() ?? 0 }}

                    of

                    {{ $cities->total() }}

                    cities

                </span>


                <!-- PAGINATION -->

                <div class="admin-pagination">

                    {{ $cities->links() }}

                </div>

            </div>


        </div>

    </div>

@endsection