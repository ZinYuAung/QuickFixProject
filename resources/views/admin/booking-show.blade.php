@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')

<div class="admin-dashboard admin-booking-show-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="admin-booking-show-header">

        <div class="admin-booking-show-header-left">

            <div class="admin-booking-breadcrumb">

                <a href="{{ route('admin.bookings.index') }}">
                    <i class="fa-solid fa-calendar-check"></i>
                    Bookings
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Booking Details
                </span>

            </div>

            <h1>
                Booking Details
            </h1>

            <p>
                View complete information and status history for this booking.
            </p>

        </div>


        <div class="admin-booking-show-header-actions">

            <a
                href="{{ route('admin.bookings.index') }}"
                class="admin-booking-back-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Bookings</span>
            </a>

        </div>

    </div>


    {{-- =====================================================
         BOOKING SUMMARY
    ====================================================== --}}

    <div class="admin-booking-summary-card">

        <div class="admin-booking-summary-left">

            <div class="admin-booking-summary-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>

            <div class="admin-booking-summary-content">

                <span class="admin-booking-summary-label">
                    Booking ID
                </span>

                <h2>
                    #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}
                </h2>

                <p>
                    Created
                    {{ $booking->created_at?->format('M d, Y h:i A') ?? 'N/A' }}
                </p>

            </div>

        </div>


        @php
            $status = strtolower($booking->status ?? 'pending');

            $statusLabel = ucwords(
                str_replace(
                    '_',
                    ' ',
                    $status
                )
            );
        @endphp


        <div class="admin-booking-summary-right">

            <span class="admin-booking-status {{ $status }}">

                <span class="status-dot"></span>

                {{ $statusLabel }}

            </span>

        </div>

    </div>


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <div class="admin-booking-show-layout">


        {{-- =================================================
             LEFT COLUMN
        ================================================== --}}

        <div class="admin-booking-show-main">


            {{-- =============================================
                 CUSTOMER INFORMATION
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-user"></i>
                            Customer Information
                        </h3>

                        <p>
                            Customer who created this booking
                        </p>

                    </div>

                </div>


                <div class="admin-booking-customer-profile">

                    <div class="admin-booking-large-avatar">

                        @php
                            $customerName =
                                $booking->customer?->user?->name
                                ?? 'Unknown Customer';

                            $customerInitials =
                                strtoupper(
                                    substr(
                                        $customerName,
                                        0,
                                        2
                                    )
                                );
                        @endphp

                        {{ $customerInitials }}

                    </div>


                    <div class="admin-booking-profile-details">

                        <h4>
                            {{ $customerName }}
                        </h4>

                        <span class="admin-booking-profile-id">
                            #CUS-{{ $booking->customer_id }}
                        </span>


                        <div class="admin-booking-contact-list">

                            @if($booking->customer?->user?->email)

                                <div class="admin-booking-contact-item">

                                    <i class="fa-solid fa-envelope"></i>

                                    <span>
                                        {{ $booking->customer->user->email }}
                                    </span>

                                </div>

                            @endif


                            @if($booking->customer?->user?->phone)

                                <div class="admin-booking-contact-item">

                                    <i class="fa-solid fa-phone"></i>

                                    <span>
                                        {{ $booking->customer->user->phone }}
                                    </span>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =============================================
                 PROVIDER INFORMATION
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-user-gear"></i>
                            Provider Information
                        </h3>

                        <p>
                            Provider assigned to this booking
                        </p>

                    </div>

                </div>


                @if($booking->provider)

                    @php
                        $providerName =
                            $booking->provider?->user?->name
                            ?? 'Unknown Provider';

                        $providerInitials =
                            strtoupper(
                                substr(
                                    $providerName,
                                    0,
                                    2
                                )
                            );
                    @endphp


                    <div class="admin-booking-customer-profile">

                        <div class="admin-booking-large-avatar provider">

                            {{ $providerInitials }}

                        </div>


                        <div class="admin-booking-profile-details">

                            <h4>
                                {{ $providerName }}
                            </h4>

                            <span class="admin-booking-profile-id">
                                #PRO-{{ $booking->provider_id }}
                            </span>


                            <div class="admin-booking-contact-list">

                                @if($booking->provider?->user?->email)

                                    <div class="admin-booking-contact-item">

                                        <i class="fa-solid fa-envelope"></i>

                                        <span>
                                            {{ $booking->provider->user->email }}
                                        </span>

                                    </div>

                                @endif


                                @if($booking->provider?->user?->phone)

                                    <div class="admin-booking-contact-item">

                                        <i class="fa-solid fa-phone"></i>

                                        <span>
                                            {{ $booking->provider->user->phone }}
                                        </span>

                                    </div>

                                @endif


                                @if($booking->provider?->availability_status)

                                    <div class="admin-booking-contact-item">

                                        <i class="fa-solid fa-circle"></i>

                                        <span>
                                            {{ ucfirst($booking->provider->availability_status) }}
                                        </span>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                @else

                    <div class="admin-booking-unassigned">

                        <div class="admin-booking-unassigned-icon">
                            <i class="fa-solid fa-user-slash"></i>
                        </div>

                        <div>

                            <strong>
                                No Provider Assigned
                            </strong>

                            <span>
                                This booking has not been assigned to a provider yet.
                            </span>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =============================================
                 SERVICE INFORMATION
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                            Service Information
                        </h3>

                        <p>
                            Services selected for this booking
                        </p>

                    </div>

                </div>


                @php
                    $chosenServices = $booking->chosen_services ?? [];
                @endphp


                @if(count($chosenServices) > 0)

                    <div class="admin-booking-service-list">

                        @foreach($chosenServices as $service)

                            <div class="admin-booking-service-item">

                                <div class="admin-booking-service-icon">

                                    <i class="fa-solid fa-screwdriver-wrench"></i>

                                </div>


                                <div class="admin-booking-service-details">

                                    <strong>
                                        {{ $service['name'] ?? 'Unknown Service' }}
                                    </strong>

                                    <span>
                                        Quantity:
                                        {{ $service['quantity'] ?? 1 }}
                                    </span>

                                </div>


                                <div class="admin-booking-service-price">

                                    {{ number_format((float) ($service['price'] ?? 0)) }}
                                    MMK

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="admin-booking-empty-state">

                        <i class="fa-solid fa-screwdriver-wrench"></i>

                        <span>
                            No service information available.
                        </span>

                    </div>

                @endif


                <div class="admin-booking-total-row">

                    <span>
                        Total Amount
                    </span>

                    <strong>
                        {{ number_format((float) ($booking->total_amount ?? 0)) }}
                        MMK
                    </strong>

                </div>

            </div>


            {{-- =============================================
                 CUSTOMER NOTE
            ============================================== --}}

            @if($booking->customer_note)

                <div class="admin-booking-detail-card">

                    <div class="admin-booking-detail-card-header">

                        <div>

                            <h3>
                                <i class="fa-solid fa-note-sticky"></i>
                                Customer Note
                            </h3>

                            <p>
                                Additional information provided by the customer
                            </p>

                        </div>

                    </div>


                    <div class="admin-booking-note">

                        <i class="fa-solid fa-quote-left"></i>

                        <p>
                            {{ $booking->customer_note }}
                        </p>

                    </div>

                </div>

            @endif


            {{-- =============================================
                 BOOKING STATUS HISTORY
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            Booking Status History
                        </h3>

                        <p>
                            Timeline of status changes for this booking
                        </p>

                    </div>

                    <span class="admin-booking-history-count">

                        {{ $booking->statusHistory->count() }}

                        {{ $booking->statusHistory->count() === 1 ? 'Update' : 'Updates' }}

                    </span>

                </div>


                @if($booking->statusHistory->count() > 0)

                    @php
                        $historyItems =
                            $booking->statusHistory
                                ->sortBy('created_at');
                    @endphp


                    <div class="admin-booking-timeline">

                        @foreach($historyItems as $history)

                            @php

                                $historyStatus =
                                    strtolower(
                                        $history->status ?? 'pending'
                                    );

                                $historyStatusLabel =
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $historyStatus
                                        )
                                    );

                            @endphp


                            <div class="admin-booking-timeline-item">

                                <div class="admin-booking-timeline-marker">

                                    <span>
                                        <i class="fa-solid fa-check"></i>
                                    </span>

                                </div>


                                <div class="admin-booking-timeline-content">

                                    <div class="admin-booking-timeline-top">

                                        <div>

                                            <span class="admin-booking-status small {{ $historyStatus }}">

                                                <span class="status-dot"></span>

                                                {{ $historyStatusLabel }}

                                            </span>

                                        </div>


                                        <time>

                                            {{ $history->created_at?->format('M d, Y h:i A') ?? 'N/A' }}

                                        </time>

                                    </div>


                                    @if($history->note)

                                        <p>
                                            {{ $history->note }}
                                        </p>

                                    @endif


                                    <div class="admin-booking-timeline-user">

                                        <i class="fa-solid fa-user"></i>

                                        <span>

                                            Updated by

                                            <strong>
                                                {{ $history->updater?->name ?? 'Unknown User' }}
                                            </strong>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="admin-booking-empty-state">

                        <i class="fa-solid fa-clock-rotate-left"></i>

                        <span>
                            No status history available.
                        </span>

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}

        <div class="admin-booking-show-sidebar">


            {{-- =============================================
                 BOOKING INFORMATION
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            Booking Information
                        </h3>

                    </div>

                </div>


                <div class="admin-booking-info-list">


                    {{-- Booking Type --}}

                    <div class="admin-booking-info-item">

                        <div class="admin-booking-info-icon">

                            <i class="fa-solid fa-bolt"></i>

                        </div>

                        <div>

                            <span>
                                Booking Type
                            </span>

                            <strong>

                                {{ strtoupper($booking->booking_type ?? 'N/A') }}

                            </strong>

                        </div>

                    </div>


                    {{-- Scheduled Date --}}

                    <div class="admin-booking-info-item">

                        <div class="admin-booking-info-icon">

                            <i class="fa-solid fa-calendar-days"></i>

                        </div>

                        <div>

                            <span>
                                Scheduled Date
                            </span>

                            <strong>

                                {{ $booking->scheduled_at?->format('M d, Y') ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>


                    {{-- Scheduled Time --}}

                    <div class="admin-booking-info-item">

                        <div class="admin-booking-info-icon">

                            <i class="fa-solid fa-clock"></i>

                        </div>

                        <div>

                            <span>
                                Scheduled Time
                            </span>

                            <strong>

                                {{ $booking->scheduled_at?->format('h:i A') ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>


                    {{-- Response Deadline --}}

                    <div class="admin-booking-info-item">

                        <div class="admin-booking-info-icon">

                            <i class="fa-solid fa-hourglass-half"></i>

                        </div>

                        <div>

                            <span>
                                Response Deadline
                            </span>

                            <strong>

                                {{ $booking->response_deadline?->format('M d, Y h:i A') ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>


                    {{-- Area --}}

                    <div class="admin-booking-info-item">

                        <div class="admin-booking-info-icon">

                            <i class="fa-solid fa-location-dot"></i>

                        </div>

                        <div>

                            <span>
                                Service Area
                            </span>

                            <strong>

                                {{ $booking->area?->name ?? 'N/A' }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =============================================
                 SERVICE LOCATION
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-location-dot"></i>
                            Service Location
                        </h3>

                    </div>

                </div>


                <div class="admin-booking-location">

                    <div class="admin-booking-location-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>


                    <div class="admin-booking-location-content">

                        <span>
                            Customer Address
                        </span>

                        <p>
                            {{ $booking->address ?? 'No address available.' }}
                        </p>

                    </div>

                </div>


                @if($booking->latitude && $booking->longitude)

                    <div class="admin-booking-coordinates">

                        <div>

                            <span>
                                Latitude
                            </span>

                            <strong>
                                {{ $booking->latitude }}
                            </strong>

                        </div>


                        <div>

                            <span>
                                Longitude
                            </span>

                            <strong>
                                {{ $booking->longitude }}
                            </strong>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =============================================
                 PAYMENT SUMMARY
            ============================================== --}}

            <div class="admin-booking-detail-card">

                <div class="admin-booking-detail-card-header">

                    <div>

                        <h3>
                            <i class="fa-solid fa-wallet"></i>
                            Payment
                        </h3>

                    </div>

                </div>


                @if($booking->payment)

                    <div class="admin-booking-payment-exists">

                        <div class="admin-booking-payment-icon">

                            <i class="fa-solid fa-circle-check"></i>

                        </div>

                        <div>

                            <strong>
                                Payment Record Available
                            </strong>

                            <span>
                                A payment record is associated with this booking.
                            </span>

                        </div>

                    </div>

                @else

                    <div class="admin-booking-payment-empty">

                        <div class="admin-booking-payment-icon">

                            <i class="fa-solid fa-wallet"></i>

                        </div>

                        <div>

                            <strong>
                                No Payment Record
                            </strong>

                            <span>
                                No payment record has been created for this booking.
                            </span>

                        </div>

                    </div>

                @endif


                <div class="admin-booking-payment-total">

                    <span>
                        Booking Total
                    </span>

                    <strong>
                        {{ number_format((float) ($booking->total_amount ?? 0)) }}
                        MMK
                    </strong>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection


{{-- =====================================================
     BOOKING SHOW PAGE CSS
====================================================== --}}

@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-booking-show.css') }}"
    >

@endpush


{{-- =====================================================
     BOOKING SHOW PAGE JS
====================================================== --}}

@push('scripts')

    <script
        src="{{ asset('js/admin-booking-show.js') }}"
    ></script>

@endpush