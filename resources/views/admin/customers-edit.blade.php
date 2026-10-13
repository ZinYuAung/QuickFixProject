@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')

    @php
        $user = $customer->user;
    @endphp

    <div class="customer-edit-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="customer-edit-header">

            <div class="customer-edit-header-content">

                <div class="customer-edit-breadcrumb">

                    <a href="{{ route('admin.customers.index') }}">
                        Customers
                    </a>

                    <i class="fa-solid fa-chevron-right"></i>

                    <span>
                        Edit Customer
                    </span>

                </div>

                <div class="customer-edit-title">

                    <div class="customer-edit-title-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>

                    <div>
                        <h1>
                            Edit Customer
                        </h1>

                        <p>
                            Update customer account and location information.
                        </p>
                    </div>

                </div>

            </div>

            <div class="customer-edit-header-actions">

                <a
                    href="{{ route('admin.customers.show', $customer->id) }}"
                    class="customer-edit-btn customer-edit-btn-light"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back</span>
                </a>

            </div>

        </div>


        


        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            action="{{ route('admin.customers.update', $customer->id) }}"
            method="POST"
            class="customer-edit-form"
            id="customerEditForm"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                 ACCOUNT INFORMATION
            ================================================== --}}

            <div class="customer-edit-card">

                <div class="customer-edit-card-header">

                    <div class="customer-edit-card-title">

                        <div class="customer-edit-card-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>

                            <h2>
                                Account Information
                            </h2>

                            <p>
                                Update the customer's basic account details.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="customer-edit-card-body">

                    <div class="customer-edit-form-grid">

                        <div class="customer-edit-form-group">

                            <label for="name">
                                Full Name
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-regular fa-user"></i>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user?->name) }}"
                                    placeholder="Enter customer name"
                                    required
                                >

                            </div>

                            @error('name')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="customer-edit-form-group">

                            <label for="email">
                                Email Address
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-regular fa-envelope"></i>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user?->email) }}"
                                    placeholder="Enter email address"
                                    required
                                >

                            </div>

                            @error('email')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="customer-edit-form-group">

                            <label for="phone">
                                Phone Number
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

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
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="customer-edit-form-group">

                            <label for="is_active">
                                Account Status
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-solid fa-circle-check"></i>

                                <select
                                    id="is_active"
                                    name="is_active"
                                    required
                                >

                                    <option
                                        value="1"
                                        {{ old('is_active', $user?->is_active) == 1 ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ old('is_active', $user?->is_active) == 0 ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            @error('is_active')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 PASSWORD
            ================================================== --}}

            <div class="customer-edit-card">

                <div class="customer-edit-card-header">

                    <div class="customer-edit-card-title">

                        <div class="customer-edit-card-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <div>

                            <h2>
                                Password
                            </h2>

                            <p>
                                Leave these fields empty to keep the current password.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="customer-edit-card-body">

                    <div class="customer-edit-form-grid">
                        <div class="customer-edit-form-group">

                            <label for="password">
                                New Password
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-solid fa-lock"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Enter new password"
                                >

                                <button
                                    type="button"
                                    class="customer-edit-password-toggle"
                                    data-target="password"
                                    aria-label="Show password"
                                >
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                            </div>

                            @error('password')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>
                        <div class="customer-edit-form-group">

                            <label for="password_confirmation">
                                Confirm New Password
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-solid fa-lock"></i>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm new password"
                                >

                                <button
                                    type="button"
                                    class="customer-edit-password-toggle"
                                    data-target="password_confirmation"
                                    aria-label="Show password"
                                >
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="customer-edit-password-note">

                        <i class="fa-solid fa-circle-info"></i>

                        <span>
                            Leave the password fields empty if you do not want to change
                            the current password.
                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 LOCATION INFORMATION
            ================================================== --}}

            <div class="customer-edit-card">

                <div class="customer-edit-card-header">

                    <div class="customer-edit-card-title">

                        <div class="customer-edit-card-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>

                            <h2>
                                Location Information
                            </h2>

                            <p>
                                Update the customer's service location.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="customer-edit-card-body">

                    <div class="customer-edit-form-grid">
                        <div class="customer-edit-form-group">

                            <label for="city_id">
                                City
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-solid fa-city"></i>

                                <select
                                    id="city_id"
                                    name="city_id"
                                    required
                                >

                                    <option value="">
                                        Select city
                                    </option>

                                    @foreach ($cities as $city)

                                        <option
                                            value="{{ $city->id }}"
                                            {{ old('city_id', $customer->serviceArea?->city_id) == $city->id ? 'selected' : '' }}
                                        >
                                            {{ $city->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            @error('city_id')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>
                        <div class="customer-edit-form-group">

                            <label for="area_id">
                                Township
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper">

                                <i class="fa-solid fa-map-location-dot"></i>

                                <select
                                    id="area_id"
                                    name="area_id"
                                    required
                                    data-selected-area="{{ old('area_id', $customer->area_id) }}"
                                >

                                    <option value="">
                                        Select township
                                    </option>

                                    @foreach ($areas as $area)

                                        <option
                                            value="{{ $area->id }}"
                                            data-city-id="{{ $area->city_id }}"
                                        >
                                            {{ $area->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            @error('area_id')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>
                        <div class="customer-edit-form-group customer-edit-form-group-full">

                            <label for="address">
                                Address
                                <span>*</span>
                            </label>

                            <div class="customer-edit-input-wrapper customer-edit-textarea-wrapper">

                                <i class="fa-solid fa-location-dot"></i>

                                <textarea
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Enter customer's full address"
                                    required
                                >{{ old('address', $customer->address) }}</textarea>

                            </div>

                            @error('address')
                                <small class="customer-edit-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 FORM ACTIONS
            ================================================== --}}

            <div class="customer-edit-actions">

                <a
                    href="{{ route('admin.customers.show', $customer->id) }}"
                    class="customer-edit-btn customer-edit-btn-light"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="customer-edit-btn customer-edit-btn-primary"
                >
                    <i class="fa-solid fa-check"></i>
                    Update Customer
                </button>

            </div>

        </form>

    </div>

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-customer-edit.css') }}"
    >
    <script
        src="{{ asset('js/admin-customer-edit.js') }}"
    ></script>

@endsection

