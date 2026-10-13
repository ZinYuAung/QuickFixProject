@extends('layouts.admin')

@section('title', 'Add Provider')

{{-- =====================================================
PROVIDER CREATE CSS
====================================================== --}}

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/admin-provider-create.css') }}"
>

@endpush

{{-- =====================================================
PROVIDER CREATE JS
====================================================== --}}

@push('scripts')


<script
    src="{{ asset('js/admin-provider-create.js') }}"
></script>


@endpush

@section('content')

<div class="admin-page">

{{-- =================================================
     PAGE HEADER
================================================== --}}

<div class="admin-page-header">

    <div>

        <h1>
            Add New Provider
        </h1>

        <p>
            Create a provider account and assign their service area and services.
        </p>

    </div>


    <div class="admin-page-header-actions">

        <a
            href="{{ route('admin.providers.index') }}"
            class="admin-secondary-btn"
        >

            <i class="fa-solid fa-arrow-left"></i>

            <span>
                Back to Providers
            </span>

        </a>

    </div>

</div>


{{-- =================================================
     VALIDATION ERRORS
================================================== --}}

@if ($errors->any())

    <div class="admin-alert admin-alert-danger">

        <div class="admin-alert-icon">

            <i class="fa-solid fa-circle-exclamation"></i>

        </div>

        <div>

            <strong>
                Please check the following errors:
            </strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =================================================
     CREATE PROVIDER FORM
================================================== --}}

<form
    action="{{ route('admin.providers.store') }}"
    method="POST"
    class="admin-form"
>

    @csrf


    {{-- =================================================
         ACCOUNT INFORMATION
    ================================================== --}}

    <div class="admin-form-card">

        <div class="admin-form-card-header">

            <div class="admin-form-card-icon">

                <i class="fa-solid fa-user"></i>

            </div>

            <div>

                <h2>
                    Account Information
                </h2>

                <p>
                    Basic information for the provider account.
                </p>

            </div>

        </div>


        <div class="admin-form-grid">


            {{-- Full Name --}}

            <div class="admin-form-group">

                <label for="name">

                    Full Name

                    <span>*</span>

                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter provider name"
                    required
                >

                @error('name')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Email --}}

            <div class="admin-form-group">

                <label for="email">

                    Email Address

                    <span>*</span>

                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="provider@example.com"
                    required
                >

                @error('email')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Phone --}}

            <div class="admin-form-group">

                <label for="phone">

                    Phone Number

                    <span>*</span>

                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="09xxxxxxxxx"
                    required
                >

                @error('phone')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Password --}}

            <div class="admin-form-group">

                <label for="password">

                    Password

                    <span>*</span>

                </label>

                <div class="admin-password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 8 characters"
                        required
                    >

                    <button
                        type="button"
                        class="admin-password-toggle"
                        data-target="password"
                        title="Show password"
                        aria-label="Show password"
                    >

                        <i class="fa-solid fa-eye"></i>

                    </button>

                </div>

                @error('password')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Confirm Password --}}

            <div class="admin-form-group">

                <label for="password_confirmation">

                    Confirm Password

                    <span>*</span>

                </label>

                <div class="admin-password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm password"
                        required
                    >

                    <button
                        type="button"
                        class="admin-password-toggle"
                        data-target="password_confirmation"
                        title="Show password"
                        aria-label="Show password"
                    >

                        <i class="fa-solid fa-eye"></i>

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =================================================
         SERVICE LOCATION
    ================================================== --}}

    <div class="admin-form-card">

        <div class="admin-form-card-header">

            <div class="admin-form-card-icon">

                <i class="fa-solid fa-location-dot"></i>

            </div>

            <div>

                <h2>
                    Service Location
                </h2>

                <p>
                    Select the city and service area where the provider operates.
                </p>

            </div>

        </div>


        <div class="admin-form-grid">


            {{-- City --}}

            <div class="admin-form-group">

                <label for="city_id">

                    City

                    <span>*</span>

                </label>

                <select
                    id="city_id"
                    name="city_id"
                    required
                >

                    <option value="">
                        Select City
                    </option>

                    @foreach ($cities as $city)

                        <option
                            value="{{ $city->id }}"
                            {{ old('city_id') == $city->id ? 'selected' : '' }}
                        >

                            {{ $city->name }}

                        </option>

                    @endforeach

                </select>

                @error('city_id')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Service Area --}}

            <div class="admin-form-group">

                <label for="area_id">

                    Service Area

                    <span>*</span>

                </label>

                <select
                    id="area_id"
                    name="area_id"
                    required
                >

                    <option value="">
                        Select Service Area
                    </option>

                    @foreach ($areas as $area)

                        <option
                            value="{{ $area->id }}"
                            data-city="{{ $area->city_id }}"
                            {{ old('area_id') == $area->id ? 'selected' : '' }}
                        >

                            {{ $area->name }}

                            @if ($area->city)

                                — {{ $area->city->name }}

                            @endif

                        </option>

                    @endforeach

                </select>

                @error('area_id')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

        </div>

    </div>


    {{-- =================================================
         PROVIDER INFORMATION
    ================================================== --}}

    <div class="admin-form-card">

        <div class="admin-form-card-header">

            <div class="admin-form-card-icon">

                <i class="fa-solid fa-briefcase"></i>

            </div>

            <div>

                <h2>
                    Provider Information
                </h2>

                <p>
                    Add professional information about the provider.
                </p>

            </div>

        </div>


        <div class="admin-form-grid">


            {{-- Experience --}}

            <div class="admin-form-group">

                <label for="experience_years">

                    Experience

                    <span>*</span>

                </label>

                <div class="admin-input-with-text">

                    <input
                        type="number"
                        id="experience_years"
                        name="experience_years"
                        value="{{ old('experience_years', 0) }}"
                        min="0"
                        max="100"
                        required
                    >

                    <span>
                        Years
                    </span>

                </div>

                @error('experience_years')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Availability --}}

            <div class="admin-form-group">

                <label for="availability_status">

                    Availability Status

                    <span>*</span>

                </label>

                <select
                    id="availability_status"
                    name="availability_status"
                    required
                >

                    <option
                        value="online"
                        {{ old('availability_status', 'online') === 'online' ? 'selected' : '' }}
                    >
                        Online
                    </option>

                    <option
                        value="busy"
                        {{ old('availability_status') === 'busy' ? 'selected' : '' }}
                    >
                        Busy
                    </option>

                    <option
                        value="offline"
                        {{ old('availability_status') === 'offline' ? 'selected' : '' }}
                    >
                        Offline
                    </option>

                </select>

                @error('availability_status')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Approval Status --}}

            <div class="admin-form-group">

                <label for="approval_status">

                    Approval Status

                    <span>*</span>

                </label>

                <select
                    id="approval_status"
                    name="approval_status"
                    required
                >

                    <option
                        value="pending"
                        {{ old('approval_status', 'pending') === 'pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="approved"
                        {{ old('approval_status') === 'approved' ? 'selected' : '' }}
                    >
                        Approved
                    </option>

                    <option
                        value="rejected"
                        {{ old('approval_status') === 'rejected' ? 'selected' : '' }}
                    >
                        Rejected
                    </option>

                    <option
                        value="suspended"
                        {{ old('approval_status') === 'suspended' ? 'selected' : '' }}
                    >
                        Suspended
                    </option>

                </select>

                @error('approval_status')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- Professional Bio --}}

            <div class="admin-form-group admin-form-group-full">

                <label for="bio">
                    Professional Bio
                </label>

                <textarea
                    id="bio"
                    name="bio"
                    rows="5"
                    placeholder="Write a short description about the provider's experience and expertise..."
                >{{ old('bio') }}</textarea>

                @error('bio')

                    <small class="admin-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

        </div>

    </div>


    {{-- =================================================
         SERVICES
    ================================================== --}}

    <div class="admin-form-card">

        <div class="admin-form-card-header">

            <div class="admin-form-card-icon">

                <i class="fa-solid fa-screwdriver-wrench"></i>

            </div>

            <div>

                <h2>
                    Services
                </h2>

                <p>
                    Select the services this provider can offer.
                </p>

            </div>

        </div>


        <div class="admin-service-checkbox-grid">

            @forelse ($services as $service)

                <label class="admin-service-checkbox">

                    <input
                        type="checkbox"
                        name="services[]"
                        value="{{ $service->id }}"
                        {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                    >

                    <span class="admin-checkbox-box">

                        <i class="fa-solid fa-check"></i>

                    </span>

                    <span class="admin-service-name">
                        {{ $service->name }}
                    </span>

                </label>

            @empty

                <div class="admin-empty-services">

                    <i class="fa-solid fa-circle-info"></i>

                    <span>
                        No services are available yet.
                    </span>

                </div>

            @endforelse

        </div>


        @error('services')

            <small class="admin-form-error">
                {{ $message }}
            </small>

        @enderror

    </div>


    {{-- =================================================
         FORM ACTIONS
    ================================================== --}}

    <div class="admin-form-actions">

        <a
            href="{{ route('admin.providers.index') }}"
            class="admin-secondary-btn"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="admin-primary-btn"
        >

            <i class="fa-solid fa-user-plus"></i>

            <span>
                Create Provider
            </span>

        </button>

    </div>

</form>

</div>

@endsection
