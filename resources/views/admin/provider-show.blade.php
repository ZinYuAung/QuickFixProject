@extends('layouts.admin')

@section('title', 'Provider Details')

@section('content')

<div class="admin-dashboard admin-provider-show-page">

{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="admin-page-header">

    <div class="admin-page-header-content">

        <h1>
            Provider Details
        </h1>

        <p>
            View provider account, services, bookings, and activity information.
        </p>

    </div>

    <div class="admin-page-header-actions">

        <a
            href="{{ route('admin.providers.index') }}"
            class="admin-small-btn"
        >
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Providers</span>
        </a>

        @if($provider->user)

            <a
                href="{{ route('admin.providers.edit', $provider->id) }}"
                class="admin-primary-btn"
            >
                <i class="fa-solid fa-pen"></i>
                <span>Edit Provider</span>
            </a>

        @endif

    </div>

</div>





{{-- =====================================================
     PROVIDER PROFILE HEADER
====================================================== --}}

@php

    $user = $provider->user;

    $name = $user?->name ?? 'Deleted Provider';

    $initials = collect(
        preg_split('/\s+/', trim($name))
    )
    ->filter()
    ->map(
        fn($word) => strtoupper(substr($word, 0, 1))
    )
    ->take(2)
    ->implode('');

    $accountStatus = 'Deleted';

    if ($user && $user->is_active) {
        $accountStatus = 'Active';
    } elseif ($user) {
        $accountStatus = 'Inactive';
    }

@endphp


<div class="admin-provider-profile-card">

    <div class="admin-provider-profile-main">

        <div class="admin-provider-avatar">

            {{ $initials ?: 'PR' }}

        </div>

        <div class="admin-provider-profile-info">

            <div class="admin-provider-name-row">

                <h2>
                    {{ $name }}
                </h2>

                @if($accountStatus === 'Active')

                    <span class="admin-provider-account-status active">
                        <span class="status-dot"></span>
                        Active
                    </span>

                @elseif($accountStatus === 'Inactive')

                    <span class="admin-provider-account-status inactive">
                        <span class="status-dot"></span>
                        Inactive
                    </span>

                @else

                    <span class="admin-provider-account-status inactive">
                        <span class="status-dot"></span>
                        Deleted
                    </span>

                @endif

            </div>

            <p>
                #PRO-{{ str_pad($provider->id, 3, '0', STR_PAD_LEFT) }}
            </p>

            <div class="admin-provider-meta">

                @if($user?->email)

                    <span>
                        <i class="fa-regular fa-envelope"></i>
                        {{ $user->email }}
                    </span>

                @endif

                @if($user?->phone)

                    <span>
                        <i class="fa-solid fa-phone"></i>
                        {{ $user->phone }}
                    </span>

                @endif

            </div>

        </div>

    </div>


    {{-- APPROVAL STATUS --}}

    <div class="admin-provider-approval-box">

        <span class="admin-provider-approval-label">
            Approval Status
        </span>

        @if($provider->approval_status === 'approved')

            <span class="admin-provider-approval approved">
                <i class="fa-solid fa-circle-check"></i>
                Approved
            </span>

        @elseif($provider->approval_status === 'pending')

            <span class="admin-provider-approval pending">
                <i class="fa-solid fa-clock"></i>
                Pending
            </span>

        @elseif($provider->approval_status === 'rejected')

            <span class="admin-provider-approval rejected">
                <i class="fa-solid fa-circle-xmark"></i>
                Rejected
            </span>

        @else

            <span class="admin-provider-approval suspended">
                <i class="fa-solid fa-ban"></i>
                Suspended
            </span>

        @endif

    </div>

</div>


{{-- =====================================================
     STATISTICS
====================================================== --}}

<div class="admin-stat-grid admin-provider-detail-stat-grid">

    {{-- EXPERIENCE --}}

    <div class="admin-stat-card">

        <div class="admin-stat-icon users">
            <i class="fa-solid fa-briefcase"></i>
        </div>

        <div class="admin-stat-content">

            <span>
                Experience
            </span>

            <h2>
                {{ $provider->experience_years }}
            </h2>

            <div class="admin-stat-note">
                Years of experience
            </div>

        </div>

    </div>


    {{-- RATING --}}

    <div class="admin-stat-card">

        <div class="admin-stat-icon services">
            <i class="fa-solid fa-star"></i>
        </div>

        <div class="admin-stat-content">

            <span>
                Rating
            </span>

            <h2>
                {{ number_format((float) $provider->rating, 1) }}
            </h2>

            <div class="admin-stat-note">
                Average rating
            </div>

        </div>

    </div>


    {{-- BOOKINGS --}}

    <div class="admin-stat-card">

        <div class="admin-stat-icon providers">
            <i class="fa-solid fa-calendar-check"></i>
        </div>

        <div class="admin-stat-content">

            <span>
                Bookings
            </span>

            <h2>
                {{ number_format($provider->bookings->count()) }}
            </h2>

            <div class="admin-stat-note">
                Total bookings
            </div>

        </div>

    </div>


    {{-- SERVICES --}}

    <div class="admin-stat-card">

        <div class="admin-stat-icon bookings">
            <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>

        <div class="admin-stat-content">

            <span>
                Services
            </span>

            <h2>
                {{ number_format($provider->services->count()) }}
            </h2>

            <div class="admin-stat-note">
                Assigned services
            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     TWO COLUMN CONTENT
====================================================== --}}

<div class="admin-provider-detail-grid">


    {{-- =================================================
         LEFT COLUMN
    ================================================== --}}

    <div class="admin-provider-detail-left">


        {{-- PROVIDER INFORMATION --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Provider Information
                    </h3>

                    <p>
                        Basic provider profile information
                    </p>

                </div>

            </div>


            <div class="admin-provider-info-grid">

                <div class="admin-provider-info-item">

                    <span>
                        Full Name
                    </span>

                    <strong>
                        {{ $name }}
                    </strong>

                </div>


                <div class="admin-provider-info-item">

                    <span>
                        Email
                    </span>

                    <strong>
                        {{ $user?->email ?? '—' }}
                    </strong>

                </div>


                <div class="admin-provider-info-item">

                    <span>
                        Phone
                    </span>

                    <strong>
                        {{ $user?->phone ?? '—' }}
                    </strong>

                </div>


                <div class="admin-provider-info-item">

                    <span>
                        Joined
                    </span>

                    <strong>
                        {{ $user?->created_at?->format('M d, Y') ?? '—' }}
                    </strong>

                </div>


                <div class="admin-provider-info-item">

                    <span>
                        Availability
                    </span>

                    <strong class="availability-{{ $provider->availability_status }}">

                        @if($provider->availability_status === 'online')

                            <i class="fa-solid fa-circle"></i>

                        @elseif($provider->availability_status === 'busy')

                            <i class="fa-solid fa-circle"></i>

                        @else

                            <i class="fa-solid fa-circle"></i>

                        @endif

                        {{ ucfirst($provider->availability_status) }}

                    </strong>

                </div>


                <div class="admin-provider-info-item">

                    <span>
                        Experience
                    </span>

                    <strong>
                        {{ $provider->experience_years }} years
                    </strong>

                </div>

            </div>

        </div>


        {{-- SERVICE LOCATION --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Service Location
                    </h3>

                    <p>
                        Provider's assigned service area
                    </p>

                </div>

            </div>


            <div class="admin-provider-location-detail">

                <div class="admin-provider-location-icon">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <div>

                    <strong>
                        {{ $provider->serviceArea?->name ?? 'No Service Area' }}
                    </strong>

                    <span>
                        {{ $provider->serviceArea?->city?->name ?? '—' }}
                    </span>

                </div>

            </div>

        </div>


        {{-- SERVICES --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Services
                    </h3>

                    <p>
                        Services offered by this provider
                    </p>

                </div>

            </div>


            @if($provider->services->count())

                <div class="admin-provider-services-grid">

                    @foreach($provider->services as $service)

                        <div class="admin-provider-service-item">

                            <div class="admin-provider-service-icon">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                            </div>

                            <span>
                                {{ $service->name }}
                            </span>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="admin-provider-empty-state">

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                    <p>
                        No services assigned.
                    </p>

                </div>

            @endif

        </div>


        {{-- BIO --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        About Provider
                    </h3>

                    <p>
                        Provider profile description
                    </p>

                </div>

            </div>


            <div class="admin-provider-bio">

                @if($provider->bio)

                    <p>
                        {{ $provider->bio }}
                    </p>

                @else

                    <p class="text-muted">
                        No provider biography has been added.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =================================================
         RIGHT COLUMN
    ================================================== --}}

    <div class="admin-provider-detail-right">


        {{-- ACCOUNT STATUS --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Account Status
                    </h3>

                    <p>
                        Current provider account state
                    </p>

                </div>

            </div>


            <div class="admin-provider-status-list">

                <div class="admin-provider-status-row">

                    <span>
                        Account
                    </span>

                    @if($accountStatus === 'Active')

                        <strong class="status-active">
                            Active
                        </strong>

                    @elseif($accountStatus === 'Inactive')

                        <strong class="status-inactive">
                            Inactive
                        </strong>

                    @else

                        <strong class="status-inactive">
                            Deleted
                        </strong>

                    @endif

                </div>


                <div class="admin-provider-status-row">

                    <span>
                        Approval
                    </span>

                    <strong>
                        {{ ucfirst($provider->approval_status) }}
                    </strong>

                </div>


                <div class="admin-provider-status-row">

                    <span>
                        Availability
                    </span>

                    <strong>
                        {{ ucfirst($provider->availability_status) }}
                    </strong>

                </div>


                <div class="admin-provider-status-row">

                    <span>
                        Approved By
                    </span>

                    <strong>
                        {{ $provider->approvedBy?->user?->name ?? '—' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- ACCOUNT DATES --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Account Timeline
                    </h3>

                    <p>
                        Important account dates
                    </p>

                </div>

            </div>


            <div class="admin-provider-timeline">

                <div class="admin-provider-timeline-item">

                    <div class="timeline-icon">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>

                    <div>

                        <strong>
                            Provider Created
                        </strong>

                        <span>
                            {{ $provider->created_at?->format('M d, Y h:i A') ?? '—' }}
                        </span>

                    </div>

                </div>


                <div class="admin-provider-timeline-item">

                    <div class="timeline-icon">
                        <i class="fa-solid fa-rotate"></i>
                    </div>

                    <div>

                        <strong>
                            Last Updated
                        </strong>

                        <span>
                            {{ $provider->updated_at?->format('M d, Y h:i A') ?? '—' }}
                        </span>

                    </div>

                </div>

                @if($user?->deleted_at)

                    <div class="admin-provider-timeline-item">

                        <div class="timeline-icon">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>

                        <div>

                            <strong>
                                Account Deleted
                            </strong>

                            <span>
                                {{ $user->deleted_at->format('M d, Y h:i A') }}
                            </span>

                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- QUICK ACTIONS --}}

        <div class="admin-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Quick Actions
                    </h3>

                    <p>
                        Manage this provider account
                    </p>

                </div>

            </div>


            <div class="admin-provider-quick-actions">
                
                @if($provider->approval_status === 'pending')

                    <form
                        action="{{ route('admin.providers.approve', $provider->id) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to approve this provider?');"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="admin-small-btn success"
                        >
                            <i class="fa-solid fa-check"></i>
                            Approve Provider
                        </button>
                    </form>

                    <form
                        action="{{ route('admin.providers.reject', $provider->id) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to reject this provider?');"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="admin-small-btn danger"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            Reject Provider
                        </button>
                    </form>

                @endif

                @if($user)

                    <a
                        href="{{ route('admin.providers.edit', $provider->id) }}"
                        class="admin-small-btn"
                    >
                        <i class="fa-solid fa-pen"></i>
                        Edit Provider
                    </a>

                    @if($user->is_active)

                        <form
                            action="{{ route('admin.providers.destroy', $provider->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to deactivate this provider?');"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="admin-small-btn danger"
                            >
                                <i class="fa-solid fa-user-slash"></i>
                                Deactivate
                            </button>

                        </form>

                    @else

                        <form
                            action="{{ route('admin.providers.reactivate', $provider->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to reactivate this provider?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="admin-small-btn success"
                            >
                                <i class="fa-solid fa-rotate-right"></i>
                                Reactivate
                            </button>

                        </form>

                    @endif

                @endif

            </div>

        </div>

    </div>

</div>
{{-- =====================================================
     NRC DOCUMENTS
====================================================== --}}

<div class="admin-card admin-provider-nrc-card">

    <div class="admin-card-header">
        <div>
            <h3>NRC Documents</h3>
            <p>Identity documents submitted by this provider</p>
        </div>
    </div>

    <div class="admin-provider-nrc-grid">

        {{-- NRC FRONT --}}

        <div class="admin-provider-nrc-item">

            <h4>NRC Front</h4>

            @if($provider->nrc_front_image)

                <a
                    href="{{ route('admin.providers.nrc-image', [
                        'id' => $provider->id,
                        'side' => 'front'
                    ]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-provider-nrc-image-link"
                >
                    <img
                        src="{{ route('admin.providers.nrc-image', [
                            'id' => $provider->id,
                            'side' => 'front'
                        ]) }}"
                        alt="Provider NRC front"
                        class="admin-provider-nrc-image"
                        loading="lazy"
                    >
                </a>

                <a
                    href="{{ route('admin.providers.nrc-image', [
                        'id' => $provider->id,
                        'side' => 'front'
                    ]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-small-btn"
                >
                    <i class="fa-solid fa-up-right-from-square"></i>
                    View Full Image
                </a>

            @else

                <div class="admin-provider-nrc-empty">
                    <i class="fa-regular fa-image"></i>
                    <p>Front image not uploaded</p>
                </div>

            @endif

        </div>


        {{-- NRC BACK --}}

        <div class="admin-provider-nrc-item">

            <h4>NRC Back</h4>

            @if($provider->nrc_back_image)

                <a
                    href="{{ route('admin.providers.nrc-image', [
                        'id' => $provider->id,
                        'side' => 'back'
                    ]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-provider-nrc-image-link"
                >
                    <img
                        src="{{ route('admin.providers.nrc-image', [
                            'id' => $provider->id,
                            'side' => 'back'
                        ]) }}"
                        alt="Provider NRC back"
                        class="admin-provider-nrc-image"
                        loading="lazy"
                    >
                </a>

                <a
                    href="{{ route('admin.providers.nrc-image', [
                        'id' => $provider->id,
                        'side' => 'back'
                    ]) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-small-btn"
                >
                    <i class="fa-solid fa-up-right-from-square"></i>
                    View Full Image
                </a>

            @else

                <div class="admin-provider-nrc-empty">
                    <i class="fa-regular fa-image"></i>
                    <p>Back image not uploaded</p>
                </div>

            @endif

        </div>

    </div>

    <p class="admin-provider-nrc-note">
        <i class="fa-solid fa-lock"></i>
        These identity documents are confidential. Only authorized
        administrators should access them.
    </p>

</div>


{{-- =====================================================
     BOOKINGS
====================================================== --}}

<div class="admin-card admin-provider-bookings-card">

    <div class="admin-card-header">

        <div>

            <h3>
                Booking History
            </h3>

            <p>
                Recent bookings associated with this provider
            </p>

        </div>

    </div>


    <div class="admin-table-wrapper">

        <table class="admin-bookings-table admin-provider-bookings-table">

            <thead>

                <tr>

                    <th>
                        Booking ID
                    </th>

                    <th>
                        Customer
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Booking Date
                    </th>

                    <th>
                        Amount
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($provider->bookings->sortByDesc('created_at')->take(10) as $booking)

                    <tr>

                        <td>

                            <strong>
                                #BOOK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                            </strong>

                        </td>


                        <td>

                            @if($booking->customer)

                                {{ $booking->customer->user?->name ?? 'Unknown Customer' }}

                            @else

                                —

                            @endif

                        </td>


                        <td>

                            <span class="admin-booking-status">

                                {{ ucfirst(str_replace('_', ' ', $booking->status ?? 'unknown')) }}

                            </span>

                        </td>


                        <td>

                            {{ $booking->created_at?->format('M d, Y') ?? '—' }}

                        </td>


                        <td>

                            @if(isset($booking->total_amount))

                                {{ number_format((float) $booking->total_amount, 2) }}

                            @elseif(isset($booking->amount))

                                {{ number_format((float) $booking->amount, 2) }}

                            @else

                                —

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-5"
                        >

                            <div class="py-4">

                                <i class="fa-solid fa-calendar-xmark fa-2x text-muted mb-3"></i>

                                <h5>
                                    No bookings found
                                </h5>

                                <p class="text-muted mb-0">
                                    This provider has no booking history yet.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- =====================================================
     PROVIDER LOCATION HISTORY
====================================================== --}}

<div class="admin-card admin-provider-location-history-card">

    <div class="admin-card-header">

        <div>

            <h3>
                Location History
            </h3>

            <p>
                Provider location records
            </p>

        </div>

    </div>


    <div class="admin-table-wrapper">

        <table class="admin-bookings-table admin-provider-location-table">

            <thead>

                <tr>

                    <th>
                        Address
                    </th>

                    <th>
                        Latitude
                    </th>

                    <th>
                        Longitude
                    </th>

                    <th>
                        Booking
                    </th>

                    <th>
                        Recorded At
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($provider->locations->take(10) as $location)

                    <tr>

                        <td>

                            {{ $location->address }}

                        </td>

                        <td>

                            {{ $location->latitude ?? '—' }}

                        </td>

                        <td>

                            {{ $location->longitude ?? '—' }}

                        </td>

                        <td>

                            @if($location->booking_id)

                                #BOOK-{{ str_pad($location->booking_id, 4, '0', STR_PAD_LEFT) }}

                            @else

                                —

                            @endif

                        </td>

                        <td>

                            {{ $location->recorded_at?->format('M d, Y h:i A') ?? '—' }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center py-5"
                        >

                            <div class="py-4">

                                <i class="fa-solid fa-location-dot fa-2x text-muted mb-3"></i>

                                <h5>
                                    No location history
                                </h5>

                                <p class="text-muted mb-0">
                                    No provider location records are available.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/admin-provider-show.css') }}"
>

@endpush
@push('scripts')

<script
    src="{{ asset('js/admin-provider-show.js') }}"
></script>

@endpush
