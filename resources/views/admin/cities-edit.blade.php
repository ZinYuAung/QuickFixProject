@extends('layouts.admin')

@section('title', 'Edit City')

@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/admin-city-edit.css') }}"
    >

@endpush


@section('content')

<div class="admin-city-edit-page">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="admin-page-header city-edit-header">

        <div class="city-edit-header-content">


            {{-- BREADCRUMB --}}

            <div class="city-breadcrumb">

                <a
                    href="{{ route('admin.cities.index') }}"
                >
                    Cities
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <a
                    href="{{ route('admin.cities.show', $city->id) }}"
                >
                    City Details
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Edit City
                </span>

            </div>


            {{-- TITLE --}}

            <div class="city-edit-title-row">

                <div>

                    <h1>
                        Edit City
                    </h1>

                    <p>
                        Update the information and availability
                        of this QuickFix city.
                    </p>

                </div>


                {{-- CITY ID --}}

                <span class="city-id-badge">

                    #CITY-{{ str_pad(
                        $city->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </span>

            </div>

        </div>


        {{-- HEADER ACTION --}}

        <div class="city-edit-header-actions">

            <a
                href="{{ route('admin.cities.show', $city->id) }}"
                class="admin-city-edit-btn admin-city-edit-btn-secondary"
            >

                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Details
                </span>

            </a>

        </div>

    </div>


    


    {{-- =====================================================
         EDIT FORM
    ====================================================== --}}

    <form
        action="{{ route('admin.cities.update', $city->id) }}"
        method="POST"
        id="editCityForm"
    >

        @csrf

        @method('PUT')


        <div class="city-edit-layout">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

            <div class="city-edit-main">


                {{-- =================================================
                     CITY INFORMATION
                ================================================== --}}

                <div class="city-edit-form-card">

                    <div class="city-card-header">

                        <div class="city-card-title">

                            <div class="city-card-icon">

                                <i class="fa-solid fa-city"></i>

                            </div>

                            <div>

                                <h2>
                                    City Information
                                </h2>

                                <p>
                                    Update the main information
                                    for this city.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="city-card-body">


                        {{-- CITY NAME --}}

                        <div class="city-form-group">

                            <label for="name">

                                City Name

                                <span class="city-required">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $city->name) }}"
                                class="city-edit-input @error('name') input-error @enderror"
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

                                Enter a clear and unique city name
                                where QuickFix services are available.

                            </div>

                        </div>


                        {{-- SERVICE AREA INFORMATION --}}

                        <div class="city-form-group">

                            <div class="city-service-area-box">

                                <div class="city-service-area-icon">

                                    <i class="fa-solid fa-map-location-dot"></i>

                                </div>


                                <div class="city-service-area-content">

                                    <h3>
                                        Service Areas
                                    </h3>

                                    <p>

                                        This city currently has
                                        {{ $city->serviceAreas->count() }}
                                        service area(s).

                                        Service areas such as townships
                                        or neighborhoods can be managed
                                        separately.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     CITY INFORMATION
                ================================================== --}}

                <div class="city-edit-form-card">

                    <div class="city-card-header">

                        <div class="city-card-title">

                            <div class="city-card-icon">

                                <i class="fa-solid fa-chart-simple"></i>

                            </div>

                            <div>

                                <h2>
                                    City Information
                                </h2>

                                <p>
                                    Current information related
                                    to this city.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="city-card-body">

                        <div class="city-info-grid">


                            {{-- CITY ID --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    City ID
                                </span>

                                <strong>

                                    #CITY-{{ str_pad(
                                        $city->id,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </strong>

                            </div>


                            {{-- SERVICE AREAS --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    Service Areas
                                </span>

                                <strong>

                                    {{ $city->serviceAreas->count() }}

                                </strong>

                            </div>


                            {{-- PROVIDERS --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    Providers
                                </span>

                                <strong>

                                    {{ $city->providers->count() }}

                                </strong>

                            </div>


                            {{-- CUSTOMERS --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    Customers
                                </span>

                                <strong>

                                    {{ $city->customers->count() }}

                                </strong>

                            </div>


                            {{-- STATUS --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    Current Status
                                </span>

                                <strong
                                    id="currentStatusText"
                                    class="{{
                                        $city->is_active
                                            ? 'city-status-active'
                                            : 'city-status-inactive'
                                    }}"
                                >

                                    {{
                                        $city->is_active
                                            ? 'Active'
                                            : 'Inactive'
                                    }}

                                </strong>

                            </div>


                            {{-- CREATED --}}

                            <div class="city-info-item">

                                <span class="city-info-label">
                                    Created
                                </span>

                                <strong>

                                    @if ($city->created_at)

                                        {{ $city->created_at->format('d M Y') }}

                                    @else

                                        —

                                    @endif

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div class="city-edit-sidebar">


                {{-- =================================================
                     CITY STATUS
                ================================================== --}}

                <div class="city-edit-form-card">

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

                        <label
                            for="is_active"
                            class="city-status-toggle"
                        >

                            <div class="city-status-information">


                                {{-- STATUS ICON --}}

                                <div class="city-status-icon">

                                    @if ($city->is_active)

                                        <i class="fa-solid fa-check"></i>

                                    @else

                                        <i class="fa-solid fa-pause"></i>

                                    @endif

                                </div>


                                {{-- STATUS TEXT --}}

                                <div>

                                    <strong id="statusTitle">

                                        {{
                                            $city->is_active
                                                ? 'Active City'
                                                : 'Inactive City'
                                        }}

                                    </strong>


                                    <span id="statusDescription">

                                        {{
                                            $city->is_active
                                                ? 'Customers can use QuickFix services in this city.'
                                                : 'Customers cannot use QuickFix services in this city.'
                                        }}

                                    </span>

                                </div>

                            </div>


                            {{-- CHECKBOX --}}

                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"

                                {{
                                    old(
                                        'is_active',
                                        $city->is_active
                                    )
                                        ? 'checked'
                                        : ''
                                }}
                            >


                            <span class="city-custom-toggle"></span>

                        </label>

                    </div>

                </div>



                {{-- =================================================
                     QUICK PREVIEW
                ================================================== --}}

                <div class="city-edit-form-card">

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
                                    Preview your changes before saving.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="city-card-body">

                        <div class="city-preview-box">


                            {{-- ICON --}}

                            <div class="city-preview-icon">

                                <i class="fa-solid fa-city"></i>

                            </div>


                            {{-- CONTENT --}}

                            <div class="city-preview-content">

                                <span class="city-preview-label">
                                    QUICKFIX CITY
                                </span>


                                <h3 id="previewName">

                                    {{ $city->name }}

                                </h3>


                                <p>

                                    This city is available for
                                    QuickFix home services.

                                </p>


                                <div class="city-preview-status">

                                    <span
                                        id="previewStatusDot"
                                        class="city-preview-status-dot
                                        {{
                                            $city->is_active
                                                ? 'active'
                                                : 'inactive'
                                        }}"
                                    ></span>


                                    <strong id="previewStatus">

                                        {{
                                            $city->is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     UPDATE NOTICE
                ================================================== --}}

                <div class="city-update-notice">

                    <div class="city-update-notice-icon">

                        <i class="fa-solid fa-rotate"></i>

                    </div>


                    <div>

                        <h3>
                            Update Notice
                        </h3>

                        <p>

                            Changes will be saved immediately
                            when you click
                            <strong>Save Changes</strong>.

                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             FORM ACTIONS
        ====================================================== --}}

        <div class="city-edit-form-actions">

            <a
                href="{{ route('admin.cities.index') }}"
                class="admin-city-edit-btn admin-city-edit-btn-secondary"
            >

                <i class="fa-solid fa-xmark"></i>

                <span>
                    Cancel
                </span>

            </a>


            <button
                type="submit"
                id="saveCityButton"
                class="admin-city-edit-btn admin-city-edit-btn-primary"
            >

                <i class="fa-solid fa-floppy-disk"></i>

                <span>
                    Save Changes
                </span>

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

    <script
        src="{{ asset('js/admin-city-edit.js') }}"
    ></script>

@endpush