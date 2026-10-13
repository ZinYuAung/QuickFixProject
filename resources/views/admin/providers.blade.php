@extends('layouts.admin')

@section('title', 'Providers')

@section('content')

<div class="admin-dashboard admin-providers-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
                Providers
            </h1>

            <p>
                View and manage service provider accounts on QuickFix.
            </p>

        </div>

        <div class="admin-page-header-actions">

           

        </div>

    </div>


  

    {{-- =====================================================
         PROVIDER STATISTICS
    ====================================================== --}}

    <div class="admin-stat-grid admin-provider-stat-grid">


        {{-- TOTAL PROVIDERS --}}

        <div class="admin-stat-card">

            <div class="admin-stat-icon providers">
                <i class="fa-solid fa-user-tie"></i>
            </div>

            <div class="admin-stat-content">

                <span>
                    Total Providers
                </span>

                <h2>
                    {{ $totalProviders }}
                </h2>

                <div class="admin-stat-note">
                    All provider accounts
                </div>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="admin-stat-card">

            <div class="admin-stat-icon services">
                <i class="fa-solid fa-user-check"></i>
            </div>

            <div class="admin-stat-content">

                <span>
                    Approved
                </span>

                <h2>
                    {{ $approvedProviders }}
                </h2>

                <div class="admin-stat-note">
                    Approved providers
                </div>

            </div>

        </div>


        {{-- PENDING --}}

        <div class="admin-stat-card">

            <div class="admin-stat-icon bookings">
                <i class="fa-solid fa-user-clock"></i>
            </div>

            <div class="admin-stat-content">

                <span>
                    Pending Approval
                </span>

                <h2>
                    {{ $pendingProviders }}
                </h2>

                <div class="admin-stat-note">
                    Waiting for review
                </div>

            </div>

        </div>


        {{-- SUSPENDED --}}

        <div class="admin-stat-card">

            <div class="admin-stat-icon users">
                <i class="fa-solid fa-user-slash"></i>
            </div>

            <div class="admin-stat-content">

                <span>
                    Suspended
                </span>

                <h2>
                    {{ $suspendedProviders }}
                </h2>

                <div class="admin-stat-note">
                    Account restrictions
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PROVIDER MANAGEMENT
    ====================================================== --}}

    <div class="admin-card admin-providers-card">


        {{-- =================================================
             CARD HEADER
        ================================================== --}}

        <div class="admin-card-header">

            <div>

                <h3>
                    All Providers
                </h3>

                <p>
                    Provider account and service information
                </p>

            </div>


            <a
                href="{{ route('admin.providers.create') }}"
                class="admin-small-btn"
                id="addProviderBtn"
            >

                <i class="fa-solid fa-user-plus"></i>

                <span>
                    Add Provider
                </span>

            </a>

        </div>


        {{-- =================================================
             TOOLBAR
        ================================================== --}}

        <form
            action="{{ route('admin.providers.index') }}"
            method="GET"
            class="admin-provider-toolbar"
        >


            {{-- SEARCH --}}

            <div class="admin-provider-search">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="search"
                    id="providerSearch"
                    value="{{ request('search') }}"
                    placeholder="Search providers..."
                    autocomplete="off"
                >

            </div>


            {{-- APPROVAL STATUS FILTER --}}

            <div class="admin-provider-filter">

                <select
                    name="approval_status"
                    id="providerStatusFilter"
                    class="admin-filter-select"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        All Approval Status
                    </option>

                    <option
                        value="approved"
                        {{ request('approval_status') === 'approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="pending"
                        {{ request('approval_status') === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="rejected"
                        {{ request('approval_status') === 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                    <option
                        value="suspended"
                        {{ request('approval_status') === 'suspended' ? 'selected' : '' }}
                    >
                        Suspended
                    </option>

                </select>

            </div>


            {{-- LOCATION FILTER --}}

            <div class="admin-provider-filter">

                <select
                    name="area_id"
                    id="providerLocationFilter"
                    class="admin-filter-select"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        All Locations
                    </option>

                    @foreach ($areas as $area)

                        <option
                            value="{{ $area->id }}"
                            {{ (string) request('area_id') === (string) $area->id ? 'selected' : '' }}
                        >

                            {{ $area->city?->name }}

                            -

                            {{ $area->name }}

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- ACCOUNT STATUS FILTER --}}

            <div class="admin-provider-filter">

                <select
                    name="account_status"
                    id="providerAccountStatusFilter"
                    class="admin-filter-select"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        All Accounts
                    </option>

                    <option
                        value="active"
                        {{ request('account_status') === 'active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="inactive"
                        {{ request('account_status') === 'inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

            </div>


            {{-- FILTER BUTTON --}}

            <button
                type="submit"
                class="admin-small-btn"
            >

                <i class="fa-solid fa-filter"></i>

                <span>
                    Filter
                </span>

            </button>


            {{-- CLEAR FILTERS --}}

            @if (
                request()->filled('search') ||
                request()->filled('approval_status') ||
                request()->filled('area_id') ||
                request()->filled('account_status')
            )

                <a
                    href="{{ route('admin.providers.index') }}"
                    class="admin-small-btn"
                >

                    <i class="fa-solid fa-xmark"></i>

                    <span>
                        Clear
                    </span>

                </a>

            @endif

        </form>


        {{-- =================================================
             TABLE
        ================================================== --}}

        <div class="admin-table-wrapper">

            <table
                class="admin-bookings-table admin-providers-table"
                id="providersTable"
            >

                <thead>

                    <tr>

                        <th>
                            Provider
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Service
                        </th>

                        <th>
                            Location
                        </th>

                        <th>
                            Joined
                        </th>

                        {{-- APPROVAL STATUS --}}

                        <th>
                            Approval Status
                        </th>

                        {{-- ACCOUNT STATUS --}}

                        <th>
                            Account Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($providers as $provider)

                        @php

                            $user = $provider->user;

                            $name = $user?->name ?? 'Unknown Provider';

                            $parts = preg_split('/\s+/', trim($name));

                            if (count($parts) >= 2) {

                                $initials = strtoupper(
                                    substr($parts[0], 0, 1) .
                                    substr($parts[count($parts) - 1], 0, 1)
                                );

                            } else {

                                $initials = strtoupper(
                                    substr($name, 0, 2)
                                );

                            }

                            $approvalStatus =
                                $provider->approval_status ?? 'pending';

                            $approvalLabel =
                                ucfirst($approvalStatus);

                            $cityName =
                                $provider->serviceArea?->city?->name ?? '—';

                            $areaName =
                                $provider->serviceArea?->name ?? '—';

                            $isActive =
                                $user?->is_active == 1;

                        @endphp


                        <tr>


                            {{-- =================================
                                 PROVIDER
                            ================================== --}}

                            <td>

                                <div class="admin-provider-cell">

                                    <div class="admin-provider-avatar">

                                        {{ $initials }}

                                    </div>

                                    <div class="admin-provider-info">

                                        <strong>
                                            {{ $name }}
                                        </strong>

                                        <small>
                                            #PRO-{{ str_pad($provider->id, 3, '0', STR_PAD_LEFT) }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================
                                 EMAIL
                            ================================== --}}

                            <td>

                                {{ $user?->email ?? '—' }}

                            </td>


                            {{-- =================================
                                 PHONE
                            ================================== --}}

                            <td>

                                {{ $user?->phone ?? '—' }}

                            </td>


                            {{-- =================================
                                 SERVICES
                            ================================== --}}

                            <td>

                                @if ($provider->services->count())

                                    <div class="provider-service-list">

                                        @foreach ($provider->services->take(2) as $service)

                                            <span>
                                                {{ $service->name }}
                                            </span>

                                        @endforeach


                                        @if ($provider->services->count() > 2)

                                            <small>

                                                +{{ $provider->services->count() - 2 }}

                                                more

                                            </small>

                                        @endif

                                    </div>

                                @else

                                    <span>
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================
                                 LOCATION
                            ================================== --}}

                            <td>

                                <div class="provider-location">

                                    <strong>
                                        {{ $cityName }}
                                    </strong>

                                    @if ($areaName !== '—')

                                        <small>
                                            {{ $areaName }}
                                        </small>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================
                                 JOINED
                            ================================== --}}

                            <td>

                                {{ $provider->created_at?->format('M d, Y') ?? '—' }}

                            </td>


                            {{-- =================================
                                 APPROVAL STATUS
                            ================================== --}}

                            <td>

                                <span
                                    class="admin-provider-status {{ $approvalStatus }}"
                                >

                                    <span class="status-dot"></span>

                                    {{ $approvalLabel }}

                                </span>

                            </td>


                            {{-- =================================
                                 ACCOUNT STATUS
                            ================================== --}}

                            <td>

                                @if ($isActive)

                                    <span
                                        class="admin-provider-account-status active"
                                    >

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span
                                        class="admin-provider-account-status inactive"
                                    >

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                           {{-- =================================
                                 ACTIONS
                            ================================== --}}

                            <td>

                                <div class="admin-provider-actions">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('admin.providers.show', $provider->id) }}"
                                        class="admin-table-action view"
                                        title="View Provider"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('admin.providers.edit', $provider->id) }}"
                                        class="admin-table-action edit"
                                        title="Edit Provider"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                    </a>


                                    {{-- =================================
                                         APPROVE & REJECT (Pending ဖြစ်မှ ပေါ်မည်)
                                    ================================== --}}
                                    @if ($approvalStatus === 'pending')

                                        {{-- APPROVE --}}
                                        <form
                                            action="{{ route('admin.providers.approve', $provider->id) }}"
                                            method="POST"
                                            class="provider-action-form"
                                            onsubmit="return confirm('Are you sure you want to approve this provider?');"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="admin-table-action text-success"
                                                title="Approve Provider"
                                            >
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        {{-- REJECT --}}
                                        <form
                                            action="{{ route('admin.providers.reject', $provider->id) }}"
                                            method="POST"
                                            class="provider-action-form"
                                            onsubmit="return confirm('Are you sure you want to reject this provider?');"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="admin-table-action text-danger"
                                                title="Reject Provider"
                                            >
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>

                                    @endif


                                    {{-- =================================
                                         ACTIVE → DELETE / DEACTIVATE
                                    ================================== --}}


                                    {{-- =================================
                                         ACTIVE → DELETE / DEACTIVATE
                                    ================================== --}}

                                    @if ($isActive)

                                        <form
                                            action="{{ route('admin.providers.destroy', $provider->id) }}"
                                            method="POST"
                                            class="provider-action-form"
                                            onsubmit="return confirm('Are you sure you want to deactivate this provider?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="admin-table-action delete"
                                                title="Deactivate Provider"
                                            >

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>


                                    {{-- =================================
                                         INACTIVE → REACTIVATE
                                    ================================== --}}

                                    @else

                                        <form
                                            action="{{ route('admin.providers.reactivate', $provider->id) }}"
                                            method="POST"
                                            class="provider-action-form"
                                            onsubmit="return confirm('Are you sure you want to reactivate this provider?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="admin-table-action reactivate"
                                                title="Reactivate Provider"
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
                                colspan="9"
                                class="admin-empty-state"
                            >

                                <div>

                                    <i class="fa-solid fa-user-tie"></i>

                                    <h4>
                                        No providers found
                                    </h4>

                                    <p>
                                        There are no providers matching your current filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =================================================
             FOOTER
        ================================================== --}}

        <div class="admin-providers-footer">

            <span id="providerResultCount">

                Showing

                {{ $providers->firstItem() ?? 0 }}

                to

                {{ $providers->lastItem() ?? 0 }}

                of

                {{ $providers->total() }}

                providers

            </span>


            <div class="admin-pagination">

                {{ $providers->links() }}

            </div>

        </div>

    </div>

</div>


@endsection