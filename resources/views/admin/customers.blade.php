@extends('layouts.admin')
@section('title', 'Customers')
@section('content')
    <div class="admin-dashboard admin-customers-page">

        <!-- PAGE HEADER  -->

        <div class="admin-page-header">
            <div class="admin-page-header-content">
                <h1>
                    Customers
                </h1>
                <p>
                    View and manage customer accounts on QuickFix.
                </p>
            </div>

            <div class="admin-page-header-actions">
               
            </div>
        </div>

       


        <!-- CUSTOMER STATISTICS  -->
        <div class="admin-stat-grid admin-customer-stat-grid">
            <!-- TOTAL CUSTOMERS  -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon users">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="admin-stat-content">
                    <span>
                        Total Customers
                    </span>
                    <h2>
                        {{ number_format($totalCustomers) }}
                    </h2>
                    <div class="admin-stat-note"> Registered customers </div>
                </div>
            </div>

            <!-- ACTIVE CUSTOMERS -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon services">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="admin-stat-content">
                    <span>
                        Active Customers
                    </span>
                    <h2>
                        {{ number_format($activeCustomers) }}
                    </h2>
                    <div class="admin-stat-note">
                        Currently active
                    </div>
                </div>
            </div>

            <!-- NEW CUSTOMERS -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon providers">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="admin-stat-content">
                    <span>
                        New Customers
                    </span>
                    <h2>
                        {{ number_format($newCustomers) }}
                    </h2>
                    <div class="admin-stat-note">
                        Registered today
                    </div>
                </div>
            </div>

            <!-- INACTIVE CUSTOMERS -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon bookings">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div class="admin-stat-content">
                    <span>
                        Inactive Customers
                    </span>
                    <h2>
                        {{ number_format($inactiveCustomers) }}
                    </h2>
                    <div class="admin-stat-note">
                        Currently inactive
                    </div>
                </div>
            </div>
        </div>


        <!-- CUSTOMER MANAGEMENT CARD  -->
        <div class="admin-card admin-customers-card">
            <!-- CARD HEADER-->
            <div class="admin-card-header">
                <div>
                    <h3>
                        All Customers
                    </h3>
                    <p>
                        Customer account information
                    </p>
                </div>
                
            </div>

            <!-- SEARCH + FILTERS-->

            <form
                action="{{ route('admin.customers.index') }}"
                method="get"
                class="admin-customer-toolbar"
            >
                <!--SEARCH -->
                <div class="admin-customer-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        name="search"
                        id="customerSearch"
                        value="{{ request('search') }}"
                        placeholder="Search customers..."
                        autocomplete="off"
                    >
                </div>
                <!-- STATUS FILTER-->
                <div class="admin-customer-filter">
                    <select
                        name="status"
                        id="customerStatusFilter"
                        class="admin-filter-select"
                        onchange="this.form.submit()"
                    >
                        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>
                            All Status
                        </option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

                <!-- SERVICE AREA FILTER  -->
                <div class="admin-customer-filter">
                    <select
                        name="location"
                        id="customerLocationFilter"
                        class="admin-filter-select"
                        onchange="this.form.submit()"
                    >
                        <option value="">
                            All Service Areas 
                        </option>
                        @foreach($areas as $area)
                            <option
                                value="{{ $area->id }}"
                                {{ request('location') == $area->id ? 'selected' : '' }}
                            >
                                {{ $area->name }} 
                                @if($area->city) 
                                    - {{ $area->city->name }}
                                @endif </option>
                        @endforeach 
                    </select> 
                </div> 
                <!-- SEARCH BUTTON -->
                <button type="submit" class="admin-small-btn"> 
                    <i class="fa-solid fa-filter"></i> 
                    <span>Filter</span> 
                </button> 
                <!-- CLEAR FILTERS -->
                @if(request()->filled('search') || request('status') || request()->filled('location')) 
                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="admin-small-btn"
                    > 
                        <i class="fa-solid fa-xmark"></i> 
                        <span>Clear</span>
                    </a>
                @endif
            </form>

            <!-- CUSTOMER TABLE  -->
            <div class="admin-table-wrapper">

                <table
                    class="admin-bookings-table admin-customers-table"
                    id="customersTable"
                >
                    <thead>
                        <tr>
                            <th>
                                Customer
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Location
                            </th>

                            <th> Joined</th>
                            <th> Bookings </th>
                            <th> Status</th>
                            <th>Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            @php
                                $user = $customer->user;
                                $name = $user?->name ?? 'Customer';
                                $initials = collect(preg_split('/\s+/', trim($name)))
                                    ->filter()
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->take(2)
                                    ->implode(''); 
                            @endphp
                            <tr> 
                                <!-- CUSTOMER  -->
                                <td>
                                    <div class="admin-customer-cell">
                                        <div class="admin-customer-avatar">
                                            {{ $initials ?: 'CU' }}
                                        </div>
                                        <div class="admin-customer-info">
                                            <strong> {{ $name }} </strong>
                                            <small>
                                                #CUS-{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}
                                            </small>
                                        </div> 
                                    </div> 
                                </td> 

                                <!-- EMAIL  -->
                                <td> 
                                    {{ $user?->email ?? '—' }} 
                                </td> 
                                <!-- PHONE  -->
                                <td> 
                                    {{ $user?->phone ?? '—' }} 
                                </td> 
                                <!-- LOCATION  -->
                                <td> 
                                    @if($customer->serviceArea) 
                                        {{ $customer->serviceArea->name }} 
                                        @if($customer->serviceArea->city) 
                                            <small class="d-block text-muted"> 
                                                {{ $customer->serviceArea->city->name }} 
                                    </small> @endif @else — @endif 
                                </td> 
                                <!-- JOINED  -->
                                <td> 
                                    @if($user?->created_at) 
                                        {{ $user->created_at->format('M d, Y') }} 
                                    @else 
                                        — 
                                    @endif 
                                </td> 
                                <!-- BOOKINGS  -->
                                <td> 
                                    {{ number_format($customer->bookings_count) }} 
                                </td> 
                                <!-- STATUS  -->
                                <td> 
                                    @if($user && $user->is_active) 
                                        <span class="admin-customer-status active"> 
                                            <span class="status-dot"></span> 
                                            Active 
                                        </span> 
                                    @else

                                        <span class="admin-customer-status inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif 
                                </td> 
                                <!-- ACTIONS -->
                                <td> 
                                    <div class="admin-customer-actions"> 
                                        <!-- VIEW  -->
                                        <a
                                            href="{{ route('admin.customers.show', $customer->id) }}"
                                            class="admin-table-action view"
                                            title="View Customer"
                                        > 
                                            <i class="fa-solid fa-eye"></i> 
                                        </a> 
                                        <!-- EDIT  -->
                                        @if($user) 
                                            <a
                                                href="{{ route('admin.customers.edit', $customer->id) }}"
                                                class="admin-table-action edit"
                                                title="Edit Customer"
                                            > 
                                                <i class="fa-solid fa-pen"></i> 
                                            </a> 
                                        @endif 
                                        <!-- active inactive -->
                                        @if ($user?->is_active)
                                            <form
                                                action="{{ route('admin.customers.destroy', $customer->id) }}"
                                                method="POST"
                                                class="customer-action-form"
                                                onsubmit="return confirm('Are you sure you want to deactivate this customer?');"
                                            >

                                                @csrf 
                                                @method('DELETE') 
                                                <button
                                                    type="submit"
                                                    class="admin-table-action delete"
                                                    title="Deactivate Customer"
                                                > 
                                                    <i class="fa-solid fa-trash"></i> 
                                                </button>
                                            </form>
                                        @else
                                            <!-- reactivate -->
                                            <form
                                                action="{{ route('admin.customers.reactivate', $customer->id) }}"
                                                method="POST"
                                                class="customer-action-form"
                                                onsubmit="return confirm('Are you sure you want to reactivate this customer?');"
                                            >
                                                @csrf 
                                                <button
                                                    type="submit"
                                                    class="admin-table-action reactivate"
                                                    title="Reactivate Customer"
                                                > 
                                                    <i class="fa-solid fa-rotate-right"></i> 
                                                </button>
                                            </form>
                                        @endif
                                    </div> 
                                </td> 
                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >
                                    <div class="py-4">
                                        <i
                                            class="fa-solid fa-users-slash fa-2x text-muted mb-3"
                                        ></i>
                                        <h5>
                                            No customers found
                                        </h5>
                                        <p class="text-muted mb-0">
                                            Try changing your search or filters.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TABLE FOOTER -->
            <div class="admin-customers-footer">
                <span id="customerResultCount">

                    Showing
                    {{ $customers->firstItem() ?? 0 }}
                    -
                    {{ $customers->lastItem() ?? 0 }}
                    of
                    {{ $customers->total() }}
                    customers
                </span>

                <!-- LARAVEL PAGINATION  -->
                @if($customers->hasPages())
                    <div class="admin-pagination">
                        <!-- PREVIOUS  -->
                        @if($customers->onFirstPage())
                            <button
                                type="button"
                                disabled
                                aria-label="Previous page"
                            >
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>

                        @else
                            <a
                                href="{{ $customers->previousPageUrl() }}"
                                aria-label="Previous page"
                            >
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        @endif

                        <!-- PAGE NUMBERS  -->
                        @foreach($customers->getUrlRange(
                                max(1, $customers->currentPage() - 2),
                                min($customers->lastPage(), $customers->currentPage() + 2)
                            ) as $page => $url)
                            @if($page == $customers->currentPage())
                                <button
                                    type="button"
                                    class="active"
                                >
                                    {{ $page }}
                                </button>
                            @else

                                <a href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        <!-- NEXT  -->
                        @if($customers->hasMorePages())
                            <a
                                href="{{ $customers->nextPageUrl() }}"
                                aria-label="Next page"
                            >
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        @else

                            <button
                                type="button"
                                disabled
                                aria-label="Next page"
                            >
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>

                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
