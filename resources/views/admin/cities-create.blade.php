@extends('layouts.admin')

@section('title', 'Add City')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-city-create.css') }}">
@endpush

@section('content')

<div class="admin-city-create-page">
{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="admin-page-header city-create-header">

    <div class="city-create-header-content">

        <div class="city-breadcrumb">

            <a href="{{ route('admin.cities.index') }}">
                Cities
            </a>

            <i class="fa-solid fa-chevron-right"></i>

            <span>
                Add City
            </span>

        </div>

        <h1>
            Add New City
        </h1>

        <p>
            Create a new city where QuickFix home services are available.
        </p>

    </div>

    <div class="city-create-header-action">

        <a
            href="{{ route('admin.cities.index') }}"
            class="admin-city-btn admin-city-btn-secondary"
        >
            <i class="fa-solid fa-arrow-left"></i>

            <span>
                Back to Cities
            </span>
        </a>

    </div>

</div>




{{-- =====================================================
     CREATE FORM
====================================================== --}}

<form
    action="{{ route('admin.cities.store') }}"
    method="POST"
    id="createCityForm"
>

    @csrf

    <div class="city-create-grid">

        {{-- =================================================
             LEFT COLUMN
        ================================================== --}}

        <div class="city-create-main">

            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}

            <div class="city-form-card">

                <div class="city-card-header">

                    <div class="city-card-title">

                        <div class="city-card-icon">
                            <i class="fa-solid fa-city"></i>
                        </div>

                        <div>

                            <h2>
                                Basic Information
                            </h2>

                            <p>
                                Enter the basic information about this city.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="city-card-body">

                    <div class="city-form-group">

                        <label for="name">

                            City Name

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="city-input @error('name') input-error @enderror"
                            placeholder="e.g. Yangon"
                            maxlength="255"
                            autocomplete="off"
                            required
                        >

                        @error('name')

                            <div class="city-field-error">

                                <i class="fa-solid fa-circle-exclamation"></i>

                                <span>
                                    {{ $message }}
                                </span>

                            </div>

                        @enderror

                        <div class="city-field-help">
                            Enter the name of the city where QuickFix
                            will provide services.
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 SERVICE AREA
            ================================================== --}}

            <div class="city-form-card">

                <div class="city-card-header">

                    <div class="city-card-title">

                        <div class="city-card-icon">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>

                        <div>

                            <h2>
                                Service Area
                            </h2>

                            <p>
                                Manage service availability for this city.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="city-card-body">

                    <div class="city-info-box">

                        <div class="city-info-icon">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>

                        <div class="city-info-content">

                            <strong>
                                Service Areas
                            </strong>

                            <span>
                                After creating the city, you can add
                                service areas such as townships or
                                neighborhoods from the Service Areas
                                management page.
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT COLUMN
        ================================================== --}}

        <div class="city-create-sidebar">

            {{-- =================================================
                 CITY STATUS
            ================================================== --}}

            <div class="city-form-card">

                <div class="city-card-header">

                    <div class="city-card-title">

                        <div class="city-card-icon">
                            <i class="fa-solid fa-toggle-on"></i>
                        </div>

                        <div>

                            <h2>
                                City Status
                            </h2>

                            <p>
                                Control city availability.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="city-card-body">

                    <div class="city-status-box">

                        <label
                            for="is_active"
                            class="city-status-toggle"
                        >

                            <div class="city-status-information">

                                <div class="city-status-icon">
                                    <i class="fa-solid fa-check"></i>
                                </div>

                                <div>

                                    <strong>
                                        Active City
                                    </strong>

                                    <span>
                                        Customers can use QuickFix
                                        services in this city.
                                    </span>

                                </div>

                            </div>

                            {{-- Send 0 when unchecked --}}
                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >

                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', 1))
                            >

                            <span class="city-custom-toggle"></span>

                        </label>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 QUICK PREVIEW
            ================================================== --}}

            <div class="city-form-card">

                <div class="city-card-header">

                    <div class="city-card-title">

                        <div class="city-card-icon">
                            <i class="fa-solid fa-eye"></i>
                        </div>

                        <div>

                            <h2>
                                Quick Preview
                            </h2>

                            <p>
                                Preview the city information.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="city-card-body">

                    <div class="city-preview">

                        <div class="city-preview-icon">
                            <i class="fa-solid fa-city"></i>
                        </div>

                        <div class="city-preview-content">

                            <span class="city-preview-label">
                                QUICKFIX CITY
                            </span>

                            <h4 id="previewName">
                                {{ old('name', 'New City') }}
                            </h4>

                            <p>
                                This city will be available for
                                QuickFix services.
                            </p>

                            <div class="city-preview-meta">

                                <div>

                                    <i class="fa-solid fa-location-dot"></i>

                                    <strong id="previewStatus">
                                        {{ old('is_active', 1) ? 'Active' : 'Inactive' }}
                                    </strong>

                                </div>

                                <div>

                                    <i class="fa-solid fa-map"></i>

                                    <strong>
                                        Service Area
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 QUICK TIP
            ================================================== --}}

            <div class="city-tip">

                <div class="city-tip-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>

                <div>

                    <h4>
                        Quick Tip
                    </h4>

                    <p>
                        Create a city first, then add its service
                        areas so customers and providers can select
                        the correct location when using QuickFix.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FORM ACTIONS
    ====================================================== --}}

    <div class="city-form-actions">

        <a
            href="{{ route('admin.cities.index') }}"
            class="admin-city-btn admin-city-btn-secondary"
        >
            <i class="fa-solid fa-xmark"></i>

            <span>
                Cancel
            </span>
        </a>

        <button
            type="submit"
            id="createCityButton"
            class="admin-city-btn admin-city-btn-primary"
        >
            <i class="fa-solid fa-plus"></i>

            <span>
                Create City
            </span>
        </button>

    </div>

</form>

</div>

@endsection

@push('scripts')

<script
    src="{{ asset('js/admin-city-create.js') }}"
></script>


@endpush
