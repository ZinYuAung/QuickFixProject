@extends('layouts.admin')

@section('title', 'Edit Service Area')

@section('content')

<div class="admin-dashboard admin-service-area-edit-page">

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

                <a href="{{ route('admin.service-areas.show', $serviceArea->id) }}">
                    Area Details
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Edit
                </span>

            </div>

            <h1>
                Edit Service Area
            </h1>

            <p>
                Update service area information and available services.
            </p>

        </div>

        <div class="admin-page-header-actions">

            <a
                href="{{ route('admin.service-areas.show', $serviceArea->id) }}"
                class="admin-small-btn admin-secondary-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Details
                </span>
            </a>

        </div>

    </div>


   


    <!-- =====================================================
         EDIT GRID
    ====================================================== -->

    <div class="admin-service-area-edit-grid">


        <!-- =================================================
             MAIN FORM
        ================================================== -->

        <div class="admin-card admin-service-area-edit-card">

            <div class="admin-card-header">

                <div>

                    <div class="admin-edit-title-row">

                        <h3>
                            Service Area Information
                        </h3>

                        <span class="admin-area-id-badge">
                            #AREA-{{ str_pad($serviceArea->id, 3, '0', STR_PAD_LEFT) }}
                        </span>

                    </div>

                    <p>
                        Modify the service area details below.
                    </p>

                </div>

            </div>


            <form
                action="{{ route('admin.service-areas.update', $serviceArea->id) }}"
                method="POST"
                id="serviceAreaEditForm"
            >

                @csrf

                @method('PUT')


                <div class="admin-service-area-form-body">


                    <!-- CITY -->

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
                                    {{ old('city_id', $serviceArea->city_id) == $city->id ? 'selected' : '' }}
                                >
                                    {{ $city->name }}
                                </option>

                            @endforeach

                        </select>

                        <small>
                            Select the city for this service area.
                        </small>

                    </div>


                    <!-- NAME -->

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
                            value="{{ old('name', $serviceArea->name) }}"
                            placeholder="Enter service area name"
                            maxlength="255"
                            required
                        >

                        <small>
                            The name must be unique within the selected city.
                        </small>

                    </div>


                    <!-- SERVICES -->

                    <div class="admin-form-group">

                        <div class="admin-form-label-row">

                            <label>
                                Available Services
                            </label>

                            <span class="admin-selected-count">

                                <span id="selectedServiceCount">
                                    {{ $serviceArea->services->count() }}
                                </span>

                                selected

                            </span>

                        </div>

                        <small class="admin-form-help">
                            Update the services available in this area.
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
                                        {{ $serviceArea->services->contains('id', $service->id) ? 'checked' : '' }}
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
                                        Create an active service before assigning services.
                                    </span>

                                </div>

                            @endforelse

                        </div>

                    </div>


                    <!-- STATUS -->

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
                                {{ old('is_active', $serviceArea->is_active) ? 'checked' : '' }}
                            >

                            <span class="admin-toggle-slider"></span>

                            <span class="admin-toggle-content">

                                <strong id="statusText">
                                    {{ $serviceArea->is_active ? 'Active' : 'Inactive' }}
                                </strong>

                                <small id="statusDescription">

                                    {{ $serviceArea->is_active
                                        ? 'This service area is currently available.'
                                        : 'This service area is currently unavailable.' }}

                                </small>

                            </span>

                        </label>

                    </div>


                    <!-- EXISTING INFORMATION -->

                    <div class="admin-edit-info-grid">

                        <div class="admin-edit-info-item">

                            <small>
                                Area ID
                            </small>

                            <strong>
                                #AREA-{{ str_pad($serviceArea->id, 3, '0', STR_PAD_LEFT) }}
                            </strong>

                        </div>

                        <div class="admin-edit-info-item">

                            <small>
                                Created
                            </small>

                            <strong>
                                {{ $serviceArea->created_at?->format('d M Y') ?? 'N/A' }}
                            </strong>

                        </div>

                        <div class="admin-edit-info-item">

                            <small>
                                Providers
                            </small>

                            <strong>
                                {{ number_format($serviceArea->providers()->count()) }}
                            </strong>

                        </div>

                        <div class="admin-edit-info-item">

                            <small>
                                Customers
                            </small>

                            <strong>
                                {{ number_format($serviceArea->customers()->count()) }}
                            </strong>

                        </div>

                    </div>


                </div>


                <!-- FORM ACTIONS -->

                <div class="admin-form-actions">

                    <a
                        href="{{ route('admin.service-areas.index', $serviceArea->id) }}"
                        class="admin-form-cancel"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="admin-small-btn"
                        id="updateServiceAreaBtn"
                    >

                        <i class="fa-solid fa-save"></i>

                        <span>
                            Save Changes
                        </span>

                    </button>

                </div>

            </form>

        </div>


        <!-- =================================================
             PREVIEW
        ================================================== -->

        <div class="admin-service-area-edit-sidebar">


            <div class="admin-card admin-service-area-preview-card">

                <div class="admin-card-header">

                    <div>

                        <h3>
                            Quick Preview
                        </h3>

                        <p>
                            Current changes
                        </p>

                    </div>

                </div>

                <div class="admin-service-area-preview-body">

                    <div class="admin-area-preview-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <h4 id="previewAreaName">
                        {{ $serviceArea->name }}
                    </h4>

                    <p id="previewCity">
                        {{ $serviceArea->city->name ?? 'N/A' }}
                    </p>

                    <span
                        id="previewStatus"
                        class="admin-service-area-status {{ $serviceArea->is_active ? 'active' : 'inactive' }}"
                    >

                        <span class="status-dot"></span>

                        {{ $serviceArea->is_active ? 'Active' : 'Inactive' }}

                    </span>

                </div>

            </div>


            <div class="admin-card admin-service-area-update-card">

                <div class="admin-update-icon">

                    <i class="fa-solid fa-circle-info"></i>

                </div>

                <div>

                    <h4>
                        Update Notice
                    </h4>

                    <p>
                        Changing the city or services will affect where this service area is available.
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
    href="{{ asset('css/admin-service-area-edit.css') }}"
>
@endpush


@push('scripts')
<script src="{{ asset('js/admin-service-area-edit.js') }}"></script>
@endpush