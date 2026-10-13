@extends('layouts.admin')

@section('title', 'City Details')

@section('content')

    <div class="admin-dashboard admin-cities-page">

        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div class="admin-page-header-content">

                <h1>
                    {{ $city->name }}
                </h1>

                <p>
                    City details and service area information.
                </p>

            </div>

            <div class="admin-page-header-actions">

                <a
                    href="{{ route('admin.cities.edit', $city->id) }}"
                    class="admin-small-btn"
                >

                    <i class="fa-solid fa-pen"></i>

                    <span>
                        Edit City
                    </span>

                </a>

                <a
                    href="{{ route('admin.cities.index') }}"
                    class="admin-small-btn"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    <span>
                        Back
                    </span>

                </a>

            </div>

        </div>


       


        <!-- STATISTICS -->

        <div class="admin-stat-grid">

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
                        {{ number_format($city->service_areas_count) }}
                    </h2>

                    <div class="admin-stat-note">
                        In this city
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
                        Providers
                    </span>

                    <h2>
                        {{ number_format($city->providers_count) }}
                    </h2>

                    <div class="admin-stat-note">
                        In this city
                    </div>

                </div>

            </div>


            <!-- CUSTOMERS -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon users">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Customers
                    </span>

                    <h2>
                        {{ number_format($city->customers_count) }}
                    </h2>

                    <div class="admin-stat-note">
                        In this city
                    </div>

                </div>

            </div>


            <!-- BOOKINGS -->

            <div class="admin-stat-card">

                <div class="admin-stat-icon services">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>

                <div class="admin-stat-content">

                    <span>
                        Bookings
                    </span>

                    <h2>
                        {{ number_format($city->bookings_count) }}
                    </h2>

                    <div class="admin-stat-note">
                        In this city
                    </div>

                </div>

            </div>

        </div>


        <!-- CITY INFORMATION -->

        <div class="admin-card admin-city-detail-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        City Information
                    </h3>

                    <p>
                        Basic information about this QuickFix city.
                    </p>

                </div>

                <div>

                    @if ($city->is_active)

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

                </div>

            </div>


            <div class="admin-city-detail-body">

                <div class="row g-4">


                    <!-- CITY ID -->

                    <div class="col-md-6">

                        <div class="admin-city-info-item">

                            <small>
                                City ID
                            </small>

                            <strong>
                                #CITY-{{ str_pad($city->id, 3, '0', STR_PAD_LEFT) }}
                            </strong>

                        </div>

                    </div>


                    <!-- CITY NAME -->

                    <div class="col-md-6">

                        <div class="admin-city-info-item">

                            <small>
                                City Name
                            </small>

                            <strong>
                                {{ $city->name }}
                            </strong>

                        </div>

                    </div>


                    <!-- CREATED -->

                    <div class="col-md-6">

                        <div class="admin-city-info-item">

                            <small>
                                Created
                            </small>

                            <strong>

                                {{ $city->created_at?->format('d M Y, h:i A') ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>


                    <!-- UPDATED -->

                    <div class="col-md-6">

                        <div class="admin-city-info-item">

                            <small>
                                Last Updated
                            </small>

                            <strong>

                                {{ $city->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- SERVICE AREAS -->

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Service Areas
                    </h3>

                    <p>
                        Service areas registered under {{ $city->name }}.
                    </p>

                </div>

            </div>


            <div class="admin-table-wrapper">

                <table class="admin-bookings-table">

                    <thead>

                        <tr>

                            <th>
                                Area ID
                            </th>

                            <th>
                                Area Name
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($city->serviceAreas as $area)

                            <tr>

                                <td>
                                    #AREA-{{ str_pad($area->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td>
                                    {{ $area->name }}
                                </td>

                                <td>

                                    @if ($area->is_active)

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

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="admin-empty-state"
                                >

                                    <div>

                                        <i class="fa-solid fa-location-dot"></i>

                                        <h4>
                                            No service areas found
                                        </h4>

                                        <p>
                                            There are no service areas registered for this city.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- CITY STATUS ACTION -->

        <div class="admin-card admin-city-actions-card">

            <div>

                <h3>
                    City Status
                </h3>

                @if ($city->is_active)

                    <p>
                        This city is currently active on QuickFix.
                    </p>

                @else

                    <p>
                        This city is currently inactive on QuickFix.
                    </p>

                @endif

            </div>


            <div>

                @if ($city->is_active)

                    <form
                        action="{{ route('admin.cities.destroy', $city->id) }}"
                        method="POST"
                        class="admin-city-action-form"
                        data-confirm-message="Are you sure you want to deactivate this city?"
                    >

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="admin-small-btn admin-danger-btn"
                        >

                            <i class="fa-solid fa-trash"></i>

                            <span>
                                Deactivate City
                            </span>

                        </button>

                    </form>

                @else

                    <form
                        action="{{ route('admin.cities.reactivate', $city->id) }}"
                        method="POST"
                        class="admin-city-action-form"
                        data-confirm-message="Are you sure you want to reactivate this city?"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="admin-small-btn admin-reactivate-btn"
                        >

                            <i class="fa-solid fa-rotate-right"></i>

                            <span>
                                Reactivate City
                            </span>

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endsection


@push('scripts')

    <script src="{{ asset('js/admin-city-show.js') }}"></script>

@endpush