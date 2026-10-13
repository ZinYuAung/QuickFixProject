@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')

@php
$user = $customer->user;
$name = $user?->name ?? 'Customer';


$initials = collect(
    preg_split('/\s+/', trim($name))
)
->filter()
->map(function ($word) {
    return strtoupper(substr($word, 0, 1));
})
->take(2)
->implode('');


@endphp

<link
    rel="stylesheet"
    href="{{ asset('css/admin-customer-show.css') }}"
>

<div class="customer-show-page">


{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="customer-show-header">

    <div class="customer-show-header-content">

        {{-- Breadcrumb --}}
        <div class="customer-show-breadcrumb">

            <a href="{{ route('admin.customers.index') }}">
                Customers
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <span>
                Customer Details
            </span>

        </div>

        {{-- Page Title --}}
        <div class="customer-show-title">

            <div class="customer-show-title-icon">
                <i class="fa-solid fa-user"></i>
            </div>

            <div>

                <h1>
                    Customer Details
                </h1>

                <p>
                    View customer account and activity information.
                </p>

            </div>

        </div>

    </div>

    {{-- Header Actions --}}
    <div class="customer-show-header-actions">

        <a
            href="{{ route('admin.customers.index') }}"
            class="customer-show-btn customer-show-btn-light"
        >
            <i class="fa-solid fa-arrow-left"></i>

            <span>
                Back
            </span>
        </a>

        <a
            href="{{ route('admin.customers.edit', $customer->id) }}"
            class="customer-show-btn customer-show-btn-primary"
        >
            <i class="fa-solid fa-pen"></i>

            <span>
                Edit Customer
            </span>
        </a>

    </div>

</div>


{{-- =====================================================
     CUSTOMER PROFILE CARD
====================================================== --}}

<div class="customer-show-profile-card">

    {{-- Profile Main --}}
    <div class="customer-show-profile-main">

        {{-- Avatar --}}
        <div class="customer-show-avatar">

            @if ($user?->profile_image)

                <img
                    src="{{ asset('storage/' . $user->profile_image) }}"
                    alt="{{ $name }}"
                >

            @else

                <span>
                    {{ $initials ?: 'CU' }}
                </span>

            @endif

        </div>

        {{-- Profile Information --}}
        <div class="customer-show-profile-info">

            <div class="customer-show-name-row">

                <h2>
                    {{ $name }}
                </h2>

                @if ($user?->is_active)

                    <span class="customer-show-status active">
                        <span class="status-dot"></span>
                        Active
                    </span>
                @else

                    <span class="customer-show-status inactive">
                        <span class="status-dot"></span>
                        Inactive
                    </span>

                @endif

            </div>

            {{-- Email --}}
            <div class="customer-show-profile-meta">

                <i class="fa-regular fa-envelope"></i>

                <span>
                    {{ $user?->email ?? 'Email unavailable' }}
                </span>

            </div>

            {{-- Joined Date --}}
            <div class="customer-show-profile-meta">

                <i class="fa-regular fa-calendar"></i>

                <span>

                    Member since

                    @if ($user?->created_at)
                        {{ $user->created_at->format('d M Y') }}
                    @else
                        -
                    @endif

                </span>

            </div>

        </div>

    </div>


    {{-- Profile Booking Stat --}}
    <div class="customer-show-profile-stat">

        <div class="customer-show-stat-icon">
            <i class="fa-solid fa-calendar-check"></i>
        </div>

        <div>

            <span>
                Total Bookings
            </span>

            <strong>
                {{ $customer->bookings_count ?? 0 }}
            </strong>

        </div>

    </div>

</div>


{{-- =====================================================
     MAIN INFORMATION GRID
====================================================== --}}

<div class="customer-show-grid">

    {{-- =================================================
         CONTACT INFORMATION
    ================================================== --}}

    <div class="customer-show-card">

        <div class="customer-show-card-header">

            <div class="customer-show-card-title">

                <div class="customer-show-card-icon">
                    <i class="fa-solid fa-address-card"></i>
                </div>

                <div>

                    <h3>
                        Contact Information
                    </h3>

                    <p>
                        Customer contact details
                    </p>

                </div>

            </div>

        </div>


        <div class="customer-show-info-list">

            {{-- Full Name --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-regular fa-user"></i>

                    <span>
                        Full Name
                    </span>

                </div>

                <div class="customer-show-info-value">
                    {{ $user?->name ?? '-' }}
                </div>

            </div>


            {{-- Email --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-regular fa-envelope"></i>

                    <span>
                        Email
                    </span>

                </div>

                <div class="customer-show-info-value">

                    @if ($user?->email)

                        <div class="customer-show-copy-wrapper">

                            <a href="mailto:{{ $user->email }}">
                                {{ $user->email }}
                            </a>

                            <button
                                type="button"
                                class="customer-show-copy-btn"
                                data-copy="{{ $user->email }}"
                                title="Copy email"
                            >
                                <i class="fa-regular fa-copy"></i>
                            </button>

                        </div>

                    @else

                        -

                    @endif

                </div>

            </div>


            {{-- Phone --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-phone"></i>

                    <span>
                        Phone
                    </span>

                </div>

                <div class="customer-show-info-value">

                    @if ($user?->phone)

                        <div class="customer-show-copy-wrapper">

                            <a href="tel:{{ $user->phone }}">
                                {{ $user->phone }}
                            </a>

                            <button
                                type="button"
                                class="customer-show-copy-btn"
                                data-copy="{{ $user->phone }}"
                                title="Copy phone number"
                            >
                                <i class="fa-regular fa-copy"></i>
                            </button>

                        </div>

                    @else

                        -

                    @endif

                </div>

            </div>


            {{-- Role --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-user-tag"></i>

                    <span>
                        Account Role
                    </span>

                </div>

                <div class="customer-show-info-value">

                    <span class="customer-show-role">
                        Customer
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         LOCATION INFORMATION
    ================================================== --}}

    <div class="customer-show-card">

        <div class="customer-show-card-header">

            <div class="customer-show-card-title">

                <div class="customer-show-card-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>

                <div>

                    <h3>
                        Location Information
                    </h3>

                    <p>
                        Customer service location
                    </p>

                </div>

            </div>

        </div>


        <div class="customer-show-info-list">

            {{-- City --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-city"></i>

                    <span>
                        City
                    </span>

                </div>

                <div class="customer-show-info-value">
                    {{ $customer->serviceArea?->city?->name ?? '-' }}
                </div>

            </div>


            {{-- Service Area --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-map-location-dot"></i>

                    <span>
                        Service Area
                    </span>

                </div>

                <div class="customer-show-info-value">
                    {{ $customer->serviceArea?->name ?? '-' }}
                </div>

            </div>


            {{-- Address --}}
            <div class="customer-show-info-item customer-show-info-item-column">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        Address
                    </span>

                </div>

                <div class="customer-show-info-value customer-show-address">
                    {{ $customer->address ?? '-' }}
                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         ACCOUNT INFORMATION
    ================================================== --}}

    <div class="customer-show-card">

        <div class="customer-show-card-header">

            <div class="customer-show-card-title">

                <div class="customer-show-card-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>

                <div>

                    <h3>
                        Account Information
                    </h3>

                    <p>
                        Account status and dates
                    </p>

                </div>

            </div>

        </div>


        <div class="customer-show-info-list">

            {{-- Account Status --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        Account Status
                    </span>

                </div>

                <div class="customer-show-info-value">

                    @if ($user?->is_active)

                        <span class="customer-show-status active">
                            <span class="status-dot"></span>
                            Active
                    @else

                        <span class="customer-show-status inactive">
                            <span class="status-dot"></span>
                            inactive
                        </span>

                    @endif

                </div>

            </div>


            {{-- Joined --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-regular fa-calendar-plus"></i>

                    <span>
                        Joined
                    </span>

                </div>

                <div class="customer-show-info-value">

                    @if ($user?->created_at)
                        {{ $user->created_at->format('d M Y, h:i A') }}
                    @else
                        -
                    @endif

                </div>

            </div>


            {{-- Last Updated --}}
            <div class="customer-show-info-item">

                <div class="customer-show-info-label">

                    <i class="fa-regular fa-clock"></i>

                    <span>
                        Last Updated
                    </span>

                </div>

                <div class="customer-show-info-value">

                    @if ($user?->updated_at)
                        {{ $user->updated_at->format('d M Y, h:i A') }}
                    @else
                        -
                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         BOOKING SUMMARY
    ================================================== --}}

    <div class="customer-show-card">

        <div class="customer-show-card-header">

            <div class="customer-show-card-title">

                <div class="customer-show-card-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>

                <div>

                    <h3>
                        Booking Summary
                    </h3>

                    <p>
                        Customer booking activity
                    </p>

                </div>

            </div>

            <a
                href="{{ route('admin.bookings.index') }}"
                class="customer-show-view-link"
            >
                View Bookings
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>


        <div class="customer-show-booking-summary">

            <div class="customer-show-booking-icon">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <div>

                <strong>
                    {{ $customer->bookings_count ?? 0 }}
                </strong>

                <span>
                    Total Bookings
                </span>

            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     BOTTOM ACTIONS
====================================================== --}}

<div class="customer-show-bottom-actions">

    <a
        href="{{ route('admin.customers.index') }}"
        class="customer-show-btn customer-show-btn-light"
    >
        <i class="fa-solid fa-arrow-left"></i>
        Back to Customers
    </a>

    <a
        href="{{ route('admin.customers.edit', $customer->id) }}"
        class="customer-show-btn customer-show-btn-primary"
    >
        <i class="fa-solid fa-pen"></i>
        Edit Customer
    </a>

</div>

</div>

{{-- Page-specific JavaScript --}}
<script src="{{ asset('js/admin-customer-show.js') }}"></script>
@endsection
