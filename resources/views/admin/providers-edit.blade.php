@extends('layouts.admin')

@section('title', 'Edit Provider')

@section('content')

<div class="admin-dashboard admin-provider-edit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
                Edit Provider
            </h1>

            <p>
                Update provider account, service, location, and status information.
            </p>

        </div>


        <div class="admin-page-header-actions">

            <a
                href="{{ route('admin.providers.show', $provider->id) }}"
                class="admin-small-btn"
            >

                <i class="fa-solid fa-eye"></i>

                <span>
                    View Provider
                </span>

            </a>


            <a
                href="{{ route('admin.providers.index') }}"
                class="admin-small-btn"
            >

                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Providers
                </span>

            </a>

        </div>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="admin-provider-edit-alert error">

            <div class="admin-provider-edit-alert-icon">

                <i class="fa-solid fa-circle-exclamation"></i>

            </div>


            <div>

                <strong>
                    Please correct the following errors:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         EDIT FORM
    ====================================================== --}}

    <form
        method="POST"
        action="{{ route('admin.providers.update', $provider->id) }}"
        id="providerEditForm"
        class="admin-provider-edit-form"
    >

        @csrf

        @method('PUT')


        {{-- =================================================
             PROVIDER PROFILE SUMMARY
        ================================================== --}}

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

        @endphp


        <div class="admin-provider-edit-profile">

            <div class="admin-provider-edit-avatar">

                {{ $initials ?: 'PR' }}

            </div>


            <div class="admin-provider-edit-profile-info">

                <h2>
                    {{ $name }}
                </h2>

                <p>
                    Provider #PRO-{{ str_pad($provider->id, 3, '0', STR_PAD_LEFT) }}
                </p>

            </div>


            <div class="admin-provider-edit-profile-status">

                @if($user?->is_active)

                    <span class="admin-provider-edit-status active">

                        <span></span>

                        Active Account

                    </span>

                @else

                    <span class="admin-provider-edit-status inactive">

                        <span></span>

                        Inactive Account

                    </span>

                @endif

            </div>

        </div>


        {{-- =================================================
             MAIN EDIT GRID
        ================================================== --}}

        <div class="admin-provider-edit-grid">


            {{-- =================================================
                 TOP ACCOUNT SECTION
            ================================================== --}}

            <div class="admin-provider-account-section">


                {{-- =================================================
                     ACCOUNT INFORMATION
                ================================================== --}}

                <div
                    class="admin-card admin-provider-edit-card account-information-card"
                >

                    <div class="admin-card-header">

                        <div>

                            <h3>
                                Account Information
                            </h3>

                            <p>
                                Update the provider's login and contact information.
                            </p>

                        </div>

                    </div>


                    <div class="admin-provider-edit-fields">


                        {{-- NAME --}}

                        <div class="admin-provider-edit-field">

                            <label for="name">

                                Full Name

                                <span>*</span>

                            </label>


                            <div class="admin-provider-input-wrap">

                                <i class="fa-solid fa-user"></i>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user?->name) }}"
                                    placeholder="Enter provider name"
                                    required
                                >

                            </div>


                            @error('name')

                                <small class="admin-provider-edit-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- EMAIL --}}

                        <div class="admin-provider-edit-field">

                            <label for="email">

                                Email Address

                                <span>*</span>

                            </label>


                            <div class="admin-provider-input-wrap">

                                <i class="fa-solid fa-envelope"></i>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user?->email) }}"
                                    placeholder="provider@example.com"
                                    required
                                >

                            </div>


                            @error('email')

                                <small class="admin-provider-edit-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- PHONE --}}

                        <div class="admin-provider-edit-field">

                            <label for="phone">

                                Phone Number

                                <span>*</span>

                            </label>


                            <div class="admin-provider-input-wrap">

                                <i class="fa-solid fa-phone"></i>

                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $user?->phone) }}"
                                    placeholder="Enter phone number"
                                    required
                                >

                            </div>


                            @error('phone')

                                <small class="admin-provider-edit-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- PASSWORD + CONFIRM PASSWORD --}}

                        <div class="admin-provider-edit-fields two-columns">


                            {{-- PASSWORD --}}

                            <div class="admin-provider-edit-field">

                                <label for="password">
                                    New Password
                                </label>


                                <div class="admin-provider-input-wrap">

                                    <i class="fa-solid fa-lock"></i>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Leave blank to keep current password"
                                    >


                                    <button
                                        type="button"
                                        class="admin-provider-password-toggle"
                                        data-target="password"
                                        aria-label="Show password"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>


                                <small class="admin-provider-edit-help">
                                    Leave this field empty if you do not want to change the password.
                                </small>


                                @error('password')

                                    <small class="admin-provider-edit-error">
                                        {{ $message }}
                                    </small>

                                @enderror

                            </div>


                            {{-- CONFIRM PASSWORD --}}

                            <div class="admin-provider-edit-field">

                                <label for="password_confirmation">
                                    Confirm New Password
                                </label>


                                <div class="admin-provider-input-wrap">

                                    <i class="fa-solid fa-lock"></i>

                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirm new password"
                                    >


                                    <button
                                        type="button"
                                        class="admin-provider-password-toggle"
                                        data-target="password_confirmation"
                                        aria-label="Show password"
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     RIGHT STATUS SIDEBAR
                ================================================== --}}

                <aside class="admin-provider-edit-sidebar">


                    {{-- =================================================
                         ACCOUNT STATUS
                    ================================================== --}}

                    <div class="admin-card admin-provider-edit-card">

                        <div class="admin-card-header">

                            <div>

                                <h3>
                                    Account Status
                                </h3>

                                <p>
                                    Control provider account state.
                                </p>

                            </div>

                        </div>


                        <div class="admin-provider-status-options">


                            {{-- ACTIVE --}}

                            <label class="admin-provider-status-option active-option">

                                <input
                                    type="radio"
                                    name="is_active"
                                    value="1"
                                    {{ old(
                                        'is_active',
                                        $user?->is_active
                                    ) ? 'checked' : '' }}
                                >


                                <span class="admin-provider-radio"></span>


                                <span>

                                    <strong>
                                        Active
                                    </strong>

                                    <small>
                                        Provider can use the account.
                                    </small>

                                </span>

                            </label>


                            {{-- INACTIVE --}}

                            <label class="admin-provider-status-option inactive-option">

                                <input
                                    type="radio"
                                    name="is_active"
                                    value="0"
                                    {{ !old(
                                        'is_active',
                                        $user?->is_active
                                    ) ? 'checked' : '' }}
                                >


                                <span class="admin-provider-radio"></span>


                                <span>

                                    <strong>
                                        Inactive
                                    </strong>

                                    <small>
                                        Provider cannot use the account.
                                    </small>

                                </span>

                            </label>

                        </div>

                    </div>


                    {{-- =================================================
                         APPROVAL STATUS
                    ================================================== --}}

                    <div
                        class="admin-card admin-provider-edit-card admin-provider-approval-card"
                    >

                        <div class="admin-card-header">

                            <div>

                                <h3>
                                    Approval Status
                                </h3>

                                <p>
                                    Manage provider approval.
                                </p>

                            </div>

                        </div>


                        <div class="admin-provider-approval-options">

                            @foreach([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'suspended' => 'Suspended'
                            ] as $status => $label)

                                <label
                                    class="admin-provider-approval-option {{ $status }}"
                                >

                                    <input
                                        type="radio"
                                        name="approval_status"
                                        value="{{ $status }}"
                                        {{ old(
                                            'approval_status',
                                            $provider->approval_status
                                        ) === $status ? 'checked' : '' }}
                                    >


                                    <span class="admin-provider-radio"></span>


                                    <span class="admin-provider-approval-text">

                                        <strong>
                                            {{ $label }}
                                        </strong>

                                    </span>

                                </label>

                            @endforeach

                        </div>


                        @error('approval_status')

                            <small class="admin-provider-edit-error">

                                {{ $message }}

                            </small>

                        @enderror

                    </div>

                </aside>

            </div>


            {{-- =================================================
                 SERVICE LOCATION
                 FULL WIDTH
            ================================================== --}}

            <div class="admin-card admin-provider-edit-card full-width-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Service Location
                        </h3>

                        <p>
                            Select the city and service area where the provider operates.
                        </p>

                    </div>

                </div>


                <div class="admin-provider-edit-fields two-columns">


                    {{-- CITY --}}

                    <div class="admin-provider-edit-field">

                        <label for="city_id">

                            City

                            <span>*</span>

                        </label>


                        <div class="admin-provider-input-wrap select-wrap">

                            <i class="fa-solid fa-city"></i>

                            <select
                                name="city_id"
                                id="city_id"
                                required
                            >

                                <option value="">
                                    Select city
                                </option>


                                @foreach($cities as $city)

                                    <option
                                        value="{{ $city->id }}"
                                        {{ old(
                                            'city_id',
                                            $provider->serviceArea?->city_id
                                        ) == $city->id ? 'selected' : '' }}
                                    >

                                        {{ $city->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        @error('city_id')

                            <small class="admin-provider-edit-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- SERVICE AREA --}}

                    <div class="admin-provider-edit-field">

                        <label for="area_id">

                            Service Area

                            <span>*</span>

                        </label>


                        <div class="admin-provider-input-wrap select-wrap">

                            <i class="fa-solid fa-location-dot"></i>

                            <select
                                name="area_id"
                                id="area_id"
                                required
                            >

                                <option value="">
                                    Select service area
                                </option>


                                @foreach($areas as $area)

                                    <option
                                        value="{{ $area->id }}"
                                        data-city="{{ $area->city_id }}"
                                        {{ old(
                                            'area_id',
                                            $provider->area_id
                                        ) == $area->id ? 'selected' : '' }}
                                    >

                                        {{ $area->name }}

                                        @if($area->city)

                                            - {{ $area->city->name }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        @error('area_id')

                            <small class="admin-provider-edit-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PROVIDER INFORMATION
                 FULL WIDTH
            ================================================== --}}

            <div class="admin-card admin-provider-edit-card full-width-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Provider Information
                        </h3>

                        <p>
                            Manage professional information and availability.
                        </p>

                    </div>

                </div>


                <div class="admin-provider-edit-fields">


                    {{-- BIO --}}

                    <div class="admin-provider-edit-field full-width">

                        <label for="bio">
                            Provider Bio
                        </label>


                        <textarea
                            name="bio"
                            id="bio"
                            rows="5"
                            maxlength="1000"
                            placeholder="Enter provider biography..."
                        >{{ old('bio', $provider->bio) }}</textarea>


                        <div class="admin-provider-character-count">

                            <span id="bioCharacterCount">
                                0
                            </span>

                            characters

                        </div>


                        @error('bio')

                            <small class="admin-provider-edit-error">
                                {{ $message }}
                            </small>

                        @enderror

                    </div>


                    {{-- EXPERIENCE + AVAILABILITY --}}

                    <div class="admin-provider-edit-fields two-columns">


                        {{-- EXPERIENCE --}}

                        <div class="admin-provider-edit-field">

                            <label for="experience_years">

                                Experience

                                <span>*</span>

                            </label>


                            <div class="admin-provider-input-wrap">

                                <i class="fa-solid fa-briefcase"></i>

                                <input
                                    type="number"
                                    id="experience_years"
                                    name="experience_years"
                                    min="0"
                                    max="100"
                                    value="{{ old(
                                        'experience_years',
                                        $provider->experience_years
                                    ) }}"
                                    required
                                >

                            </div>


                            <small class="admin-provider-edit-help">
                                Enter years of professional experience.
                            </small>


                            @error('experience_years')

                                <small class="admin-provider-edit-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>


                        {{-- AVAILABILITY --}}

                        <div class="admin-provider-edit-field">

                            <label for="availability_status">

                                Availability Status

                                <span>*</span>

                            </label>


                            <div class="admin-provider-input-wrap select-wrap">

                                <i class="fa-solid fa-signal"></i>

                                <select
                                    name="availability_status"
                                    id="availability_status"
                                    required
                                >

                                    <option
                                        value="online"
                                        {{ old(
                                            'availability_status',
                                            $provider->availability_status
                                        ) === 'online' ? 'selected' : '' }}
                                    >
                                        Online
                                    </option>


                                    <option
                                        value="busy"
                                        {{ old(
                                            'availability_status',
                                            $provider->availability_status
                                        ) === 'busy' ? 'selected' : '' }}
                                    >
                                        Busy
                                    </option>


                                    <option
                                        value="offline"
                                        {{ old(
                                            'availability_status',
                                            $provider->availability_status
                                        ) === 'offline' ? 'selected' : '' }}
                                    >
                                        Offline
                                    </option>

                                </select>

                            </div>


                            @error('availability_status')

                                <small class="admin-provider-edit-error">
                                    {{ $message }}
                                </small>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SERVICES
                 FULL WIDTH
            ================================================== --}}

            <div class="admin-card admin-provider-edit-card full-width-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Services
                        </h3>

                        <p>
                            Select the services this provider can offer.
                        </p>

                    </div>


                    <span class="admin-provider-selected-count">

                        <span id="selectedServiceCount">
                            0
                        </span>

                        selected

                    </span>

                </div>


                @php

                    $selectedServices = old(
                        'services',
                        $provider->services->pluck('id')->toArray()
                    );

                @endphp


                <div class="admin-provider-services-edit-grid">

                    @foreach($services as $service)

                        <label
                            class="admin-provider-service-checkbox"
                            for="service_{{ $service->id }}"
                        >

                            <input
                                type="checkbox"
                                id="service_{{ $service->id }}"
                                name="services[]"
                                value="{{ $service->id }}"
                                {{ in_array(
                                    $service->id,
                                    $selectedServices
                                ) ? 'checked' : '' }}
                            >


                            <span class="admin-provider-service-checkmark">

                                <i class="fa-solid fa-check"></i>

                            </span>


                            <span class="admin-provider-service-name">

                                {{ $service->name }}

                            </span>

                        </label>

                    @endforeach

                </div>


                @error('services')

                    <small class="admin-provider-edit-error">

                        {{ $message }}

                    </small>

                @enderror

            </div>


            {{-- =================================================
                 FORM ACTIONS
                 FULL WIDTH / UNDER EVERYTHING
            ================================================== --}}

            <div class="admin-provider-edit-actions">

                <a
                    href="{{ route('admin.providers.index') }}"
                    class="admin-provider-edit-cancel"
                >

                    Cancel

                </a>


                <button
                    type="submit"
                    class="admin-primary-btn"
                    id="saveProviderBtn"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    <span>
                        Save Changes
                    </span>

                </button>

            </div>

        </div>

    </form>

</div>

@endsection


@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-provider-edit.css') }}"
    >

@endpush


@push('scripts')

    <script
        src="{{ asset('js/admin-provider-edit.js') }}"
        defer
    ></script>

@endpush