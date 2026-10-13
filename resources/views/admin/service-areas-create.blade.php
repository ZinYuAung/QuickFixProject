@extends('layouts.admin')

@section('title', 'Add Service Area')

@section('content')

<div class="admin-dashboard admin-service-area-create-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <div class="admin-breadcrumb">

                <a href="{{ route('admin.service-areas.index') }}">
                    Service Areas
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Add Service Area
                </span>

            </div>

            <h1>
                Add New Service Area
            </h1>

            <p>
                Create a service area within a QuickFix city.
            </p>

        </div>

        <div class="admin-page-header-actions">

            <a
                href="{{ route('admin.service-areas.index') }}"
                class="admin-small-btn admin-secondary-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Service Areas
                </span>
            </a>

        </div>

    </div>


   

    <!-- =====================================================
         CREATE CARD
    ====================================================== -->

    <div class="admin-service-area-create-grid">


        <!-- =================================================
             MAIN FORM
        ================================================== -->

        <div class="admin-card admin-service-area-form-card">

            <div class="admin-card-header">

                <div>

                    <h3>
                        Service Area Information
                    </h3>

                    <p>
                        Enter the basic information and available services.
                    </p>

                </div>

            </div>


            <form
                action="{{ route('admin.service-areas.store') }}"
                method="POST"
                id="serviceAreaCreateForm"
            >

                @csrf


                <div class="admin-service-area-form-body">


                    <!-- =====================================
                         CITY
                    ====================================== -->

                    <div class="admin-form-group">

                        <label for="city_id">
                            City
                            <span>*</span>
                        </label>

                        <select
                            name="city_id"
                            id="city_id"
                            class="admin-form-control"
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

                        <small>
                            Select the city where this service area belongs.
                        </small>

                    </div>


                    <!-- =====================================
                         SERVICE AREA NAME
                    ====================================== -->

                    <div class="admin-form-group">

                        <label for="name">
                            Service Area Name
                            <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="admin-form-control"
                            value="{{ old('name') }}"
                            placeholder="e.g. Sanchaung Township"
                            maxlength="255"
                            required
                        >

                        <small>
                            Enter a unique service area name within the selected city.
                        </small>

                    </div>


                    <!-- =====================================
                         AVAILABLE SERVICES
                    ====================================== -->

                    <div class="admin-form-group">

                        <div class="admin-form-label-row">

                            <label>
                                Available Services
                            </label>

                            <span class="admin-selected-count">
                                <span id="selectedServiceCount">0</span>
                                selected
                            </span>

                        </div>

                        <small class="admin-form-help">
                            Select the services that are available in this area.
                        </small>


                        <div class="admin-service-selection">

                            @forelse ($services as $service)

                                <label
                                    class="admin-service-option"
                                    for="service_{{ $service->id }}"
                                >

                                    <input
                                        type="checkbox"
                                        name="services[]"
                                        value="{{ $service->id }}"
                                        id="service_{{ $service->id }}"
                                        class="service-checkbox"
                                        {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                                    >

                                    <span class="admin-service-checkmark">
                                        <i class="fa-solid fa-check"></i>
                                    </span>

                                    <span class="admin-service-option-content">

                                        <strong>
                                            {{ $service->name }}
                                        </strong>

                                        <small>
                                            {{ $service->category->name ?? 'Uncategorized' }}
                                        </small>

                                    </span>

                                </label>

                            @empty

                                <div class="admin-service-empty">

                                    <i class="fa-solid fa-wrench"></i>

                                    <strong>
                                        No active services available
                                    </strong>

                                    <span>
                                        Create an active service before assigning services to this area.
                                    </span>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    <!-- =====================================
                         STATUS
                    ====================================== -->

                    <div class="admin-form-group">

                        <label>
                            Status
                        </label>

                        <input
                            type="hidden"
                            name="is_active"
                            value="0"
                        >

                        <label
                            class="admin-status-toggle"
                            for="is_active"
                        >

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                id="is_active"
                                {{ old('is_active', true) ? 'checked' : '' }}
                            >

                            <span class="admin-toggle-slider"></span>

                            <span class="admin-toggle-content">

                                <strong id="statusText">
                                    Active
                                </strong>

                                <small id="statusDescription">
                                    This service area will be available for QuickFix services.
                                </small>

                            </span>

                        </label>

                    </div>


                </div>


                <!-- =========================================
                     FORM ACTIONS
                ========================================== -->

                <div class="admin-form-actions">

                    <a
                        href="{{ route('admin.service-areas.index') }}"
                        class="admin-form-cancel"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="admin-small-btn"
                        id="createServiceAreaBtn"
                    >

                        <i class="fa-solid fa-plus"></i>

                        <span>
                            Create Service Area
                        </span>

                    </button>

                </div>

            </form>

        </div>


        <!-- =================================================
             PREVIEW / HELP
        ================================================== -->

        <div class="admin-service-area-create-sidebar">


            <!-- QUICK PREVIEW -->

            <div class="admin-card admin-service-area-preview-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Quick Preview
                        </h3>

                        <p>
                            Service area summary
                        </p>

                    </div>

                </div>

                <div class="admin-service-area-preview-body">

                    <div class="admin-area-preview-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <h4 id="previewAreaName">
                        Service Area Name
                    </h4>

                    <p id="previewCity">
                        Select a city
                    </p>

                    <span
                        class="admin-service-area-status active"
                        id="previewStatus"
                    >

                        <span class="status-dot"></span>

                        Active

                    </span>

                </div>

            </div>


            <!-- QUICK TIP -->

            <div class="admin-card admin-service-area-tip-card">

                <div class="admin-service-area-tip-icon">

                    <i class="fa-solid fa-lightbulb"></i>

                </div>

                <div>

                    <h4>
                        Quick Tip
                    </h4>

                    <p>
                        A service area belongs to one city. You can assign multiple services to the area so customers can see only services available in their location.
                    </p>

                </div>

            </div>


        </div>

    </div>

</div>

@endsection


@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/admin-service-area-create.css') }}"
>
@endpush


@push('scripts')
<script src="{{ asset('js/admin-service-area-create.js') }}"></script>
@endpush