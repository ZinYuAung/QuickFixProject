@extends('layouts.admin')
@section('title', 'Services')
@section('content')
    <div class="admin-dashboard admin-services-page">
        <!--PAGE HEADER -->
        <div class="admin-page-header">
            <div class="admin-page-header-content">
                <h1>Services</h1>
                <p>
                    View and manage home services available on QuickFix.
                </p>
            </div>
            <div class="admin-page-header-actions">
             
            </div>
        </div>

        
        <!-- SERVICE STATISTICS -->
        <div class="admin-stat-grid admin-service-stat-grid">
            <!-- TOTAL SERVICES -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon services">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Total Services</span>
                    <h2>
                        {{ $totalServices }}
                    </h2>
                    <div class="admin-stat-note">
                        All services
                    </div>
                </div>
            </div>

            <!-- ACTIVE SERVICES -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon users">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Active Services</span>
                    <h2>
                        {{ $activeServices }}
                    </h2>
                    <div class="admin-stat-note">
                        Currently available
                    </div>
                </div>
            </div>

            <!-- CATEGORIES -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon providers">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Categories</span>
                    <h2>
                        {{ $categoriesCount }}
                    </h2>
                    <div class="admin-stat-note">
                        Service categories
                    </div>
                </div>
            </div>

            <!-- INACTIVE -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon bookings">
                    <i class="fa-solid fa-circle-pause"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Inactive Services</span>
                    <h2>
                        {{ $inactiveServices }}
                    </h2>
                    <div class="admin-stat-note">
                        Currently unavailable
                    </div>
                </div>
            </div>
        </div>

        <!--SERVICE MANAGEMENT CARD -->
        <div class="admin-card admin-services-card">
            <!-- CARD HEADER -->
            <div class="admin-card-header">
                <div>
                    <h3>All Services</h3>
                    <p>
                        Service information and availability
                    </p>
                </div>
                <a
                    href="{{ route('admin.services.create') }}"
                    class="admin-small-btn"
                    id="addServiceBtn"
                > 
                    <i class="fa-solid fa-plus"></i> 
                    <span> Add Service </span> 
                </a>
            </div>

            <!-- TOOLBAR -->
            <div class="admin-service-toolbar">

                <form
                    action="{{ route('admin.services.index') }}"
                    method="GET"
                    class="admin-service-toolbar"
                >

                    <!-- SEARCH -->
                    <div class="admin-service-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            name="search"
                            id="serviceSearch"
                            value="{{ request('search') }}"
                            placeholder="Search services..."
                            autocomplete="off"
                        >

                    </div>


                    <!-- CATEGORY -->
                    <div class="admin-service-filter">

                        <select
                            id="serviceCategoryFilter"
                            name="category_id"
                            class="admin-filter-select"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach ($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- STATUS -->
                    <div class="admin-service-filter">

                        <select
                            id="serviceStatusFilter"
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
                        <span>Filter</span>
                    </button>


                    <!-- CLEAR FILTER -->
                    @if (
                            request()->filled('search')
                            || request()->filled('category_id')
                            || request()->filled('status')
                        )

                        <a
                            href="{{ route('admin.services.index') }}"
                            class="admin-small-btn"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            <span>Clear</span>
                        </a>

                    @endif

                </form>

            </div>



            <!--SERVICE TABLE-->
            <div class="admin-table-wrapper">
                <table
                    class="admin-bookings-table admin-services-table"
                    id="servicesTable"
                >
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Providers</th>
                            <th>Bookings</th>
                            <th>Price From</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service) 
                            @php 
                                                                                        $isActive = $service->is_active == 1;
                                $providerCount = $service->providers->count();
                                $bookingCount = $bookingCounts[$service->id] ?? 0;
                                $categoryName = $service->category?->name ?? '—';
                             @endphp 
                            <tr> 
                                <!-- services  -->
                                <td> 
                                    <div class="admin-service-cell"> 
                                        <div class="admin-service-icon services"> 
                                            <i class="fa-solid fa-screwdriver-wrench"></i> 
                                        </div> 
                                        <div class="admin-service-info"> 
                                            <strong> {{ $service->name }} </strong> 
                                            <small> #SER-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }} 

                                            </small> 
                                        </div>
                                    </div> 
                                </td> 
                                <!-- categories -->
                                <td> 
                                    {{ $categoryName }} 
                                </td> 
                                <!-- description -->
                                <td> 
                                    <span class="service-description" title="{{ $service->description }}"> 
                                        {{ $service->description ? \Illuminate\Support\Str::limit($service->description, 45) : '—' }} 
                                    </span>
                                </td> 
                                <!-- providers -->
                                <td> 
                                    {{ $providerCount }} 
                                </td>
                                <!-- bookings -->
                                <td> 
                                    {{ $bookingCount }} 
                                </td> 
                                <!-- price  -->
                                <td> 
                                    {{ number_format($service->base_price) }} MMK 
                                </td> 
                                <!-- status -->
                                <td>
                                    @if ($isActive) 
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
                                </td>
                                <!-- actions -->
                                <td> <div class="admin-service-actions">
                                        <!-- view  -->
                                        <a
                                            href="{{ route('admin.services.show', $service->id) }}"
                                            class="admin-table-action view"
                                            title="View Service"
                                        > 
                                            <i class="fa-solid fa-eye"></i> 
                                        </a> 
                                        <!-- edit  -->
                                        <a
                                            href="{{ route('admin.services.edit', $service->id) }}"
                                            class="admin-table-action edit"
                                            title="Edit Service"
                                        >
                                            <i class="fa-solid fa-pen"></i> 
                                        </a> 
                                        <!-- active/deactive  -->
                                        @if ($isActive) 
                                            <form
                                                action="{{ route('admin.services.destroy', $service->id) }}"
                                                method="POST"
                                                class="service-action-form"
                                                onsubmit="return confirm('Are you sure you want to deactivate this service?');"
                                            >
                                                @csrf 
                                                @method('DELETE') 
                                                <button type="submit" class="admin-table-action delete" title="Deactivate Service"> 
                                                    <i class="fa-solid fa-trash"></i> 
                                                </button>
                                            </form> 
                                            <!-- inactive/reactive -->
                                        @else 
                                            <form
                                                action="{{ route('admin.services.reactivate', $service->id) }}"
                                                method="POST"
                                                class="service-action-form"
                                                onsubmit="return confirm('Are you sure you want to reactivate this service?');"
                                            > 
                                                @csrf 
                                                <button type="submit" class="admin-table-action reactivate" title="Reactivate Service"> 
                                                    <i class="fa-solid fa-rotate-right"></i> 
                                                </button> 
                                            </form>
                                        @endif 
                                    </div>
                                </td> 
                            </tr> 
                        @empty 
                            <tr> 
                                <td colspan="8" class="admin-empty-state"> 
                                    <div> 
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                        <h4> No services found </h4> 
                                        <p> There are no services matching your current filters. </p> 
                                    </div> 
                                </td> 
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- footer -->

            <div class="admin-services-footer">

                <span id="serviceResultCount">

                    Showing

                    {{ $services->firstItem() ?? 0 }}

                    to

                    {{ $services->lastItem() ?? 0 }}

                    of

                    {{ $services->total() }}

                    services

                </span>


                <div class="admin-pagination">

                    {{ $services->links() }}

                </div>

            </div>

        </div>

    </div>

@endsection