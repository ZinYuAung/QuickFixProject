@extends('layouts.admin')
@section('title', 'Add Service')
@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-service-create.css') }}"
    >
@endpush

@section('content')

<div class="admin-service-create-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="admin-page-header service-create-header">

        <div class="service-create-header-content">

            <div class="service-breadcrumb">

                <a href="{{ route('admin.services.index') }}">
                    Services
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>Add Service</span>

            </div>

            <h1>Add New Service</h1>

            <p>
                Create a new service that customers can book through QuickFix.
            </p>

        </div>

        <div class="service-create-header-action">

            <a
                href="{{ route('admin.services.index') }}"
                class="admin-service-btn admin-service-btn-secondary"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Services</span>
            </a>

        </div>

    </div>


   

    {{-- =====================================================
         CREATE FORM
    ====================================================== --}}
    <form
        action="{{ route('admin.services.store') }}"
        method="POST"
        id="createServiceForm"
    >

        @csrf


        <div class="service-create-layout">

            {{-- =================================================
                 LEFT SIDE
            ================================================== --}}
            <div class="service-create-main">


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}
                <div class="service-form-card">

                    <div class="service-card-header">

                        <div class="service-card-title">

                            <div class="service-card-icon">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>

                            <div>

                                <h2>Basic Information</h2>

                                <p>
                                    Enter the basic information about this service.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="service-card-body">


                        {{-- SERVICE NAME --}}
                        <div class="service-form-group">

                            <label for="name">
                                Service Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                class="service-input @error('name') input-error @enderror"
                                placeholder="e.g. Air Conditioner Repair"
                                maxlength="255"
                                required
                            >

                            @error('name')

                                <div class="service-field-error">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- CATEGORY --}}
                        <div class="service-form-group">

                            <label for="service_category_id">
                                Service Category
                                <span class="required">*</span>
                            </label>

                            <select
                                id="service_category_id"
                                name="service_category_id"
                                class="service-input @error('service_category_id') input-error @enderror"
                                required
                            >

                                <option value="">
                                    Select a category
                                </option>

                                @foreach ($categories as $category)

                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('service_category_id') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('service_category_id')

                                <div class="service-field-error">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>

                            @enderror

                            @if ($categories->isEmpty())

                                <div class="service-field-warning">

                                    <i class="fa-solid fa-triangle-exclamation"></i>

                                    <span>
                                        No active categories are available.
                                        Please create or activate a category first.
                                    </span>

                                </div>

                            @else

                                <div class="service-field-help">
                                    Only active categories are available for selection.
                                </div>

                            @endif

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="service-form-group">

                            <div class="service-label-row">

                                <label for="description">
                                    Description
                                </label>

                                <span id="descriptionCounter">
                                    0 / 1000
                                </span>

                            </div>

                            <textarea
                                id="description"
                                name="description"
                                class="service-input service-textarea @error('description') input-error @enderror"
                                rows="6"
                                maxlength="1000"
                                placeholder="Describe what this service includes..."
                            >{{ old('description') }}</textarea>

                            @error('description')

                                <div class="service-field-error">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="service-field-help">
                                Give customers a clear explanation of what they will receive.
                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     PRICING & DURATION
                ================================================== --}}
                <div class="service-form-card">

                    <div class="service-card-header">

                        <div class="service-card-title">

                            <div class="service-card-icon">
                                <i class="fa-solid fa-tags"></i>
                            </div>

                            <div>

                                <h2>Pricing & Duration</h2>

                                <p>
                                    Set the starting price and estimated service time.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="service-card-body">

                        <div class="service-form-grid">


                            {{-- BASE PRICE --}}
                            <div class="service-form-group">

                                <label for="base_price">
                                    Base Price
                                    <span class="required">*</span>
                                </label>

                                <div class="service-input-suffix">

                                    <input
                                        type="number"
                                        id="base_price"
                                        name="base_price"
                                        value="{{ old('base_price') }}"
                                        class="service-input @error('base_price') input-error @enderror"
                                        placeholder="0"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    <span>MMK</span>

                                </div>

                                @error('base_price')

                                    <div class="service-field-error">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="service-field-help">
                                    Starting price shown to customers.
                                </div>

                            </div>


                            {{-- DURATION --}}
                            <div class="service-form-group">

                                <label for="estimated_minutes">
                                    Estimated Duration
                                    <span class="required">*</span>
                                </label>

                                <div class="service-input-suffix">

                                    <input
                                        type="number"
                                        id="estimated_minutes"
                                        name="estimated_minutes"
                                        value="{{ old('estimated_minutes') }}"
                                        class="service-input @error('estimated_minutes') input-error @enderror"
                                        placeholder="60"
                                        min="1"
                                        required
                                    >

                                    <span>min</span>

                                </div>

                                @error('estimated_minutes')

                                    <div class="service-field-error">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>

                                @enderror

                                <div class="service-field-help">
                                    Approximate time required for the service.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 RIGHT SIDE
            ================================================== --}}
            <div class="service-create-sidebar">


                {{-- =================================================
                     STATUS
                ================================================== --}}
                <div class="service-form-card">

                    <div class="service-card-header">

                        <div class="service-card-title">

                            <div class="service-card-icon">
                                <i class="fa-solid fa-toggle-on"></i>
                            </div>

                            <div>

                                <h2>Service Status</h2>

                                <p>
                                    Control service availability.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="service-card-body">

                        <label
                            for="is_active"
                            class="service-status-toggle"
                        >

                            <div class="service-status-information">

                                <div class="service-status-icon">
                                    <i class="fa-solid fa-check"></i>
                                </div>

                                <div>

                                    <strong>
                                        Active Service
                                    </strong>

                                    <span>
                                        Customers can see and book this service.
                                    </span>

                                </div>

                            </div>


                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', 1) ? 'checked' : '' }}
                            >

                            <span class="service-custom-toggle"></span>

                        </label>

                    </div>

                </div>



                {{-- =================================================
                     PREVIEW
                ================================================== --}}
                <div class="service-form-card">

                    <div class="service-card-header">

                        <div class="service-card-title">

                            <div class="service-card-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>

                            <div>

                                <h2>Quick Preview</h2>

                                <p>
                                    Preview the service information.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="service-card-body">

                        <div class="service-preview-box">

                            <div class="service-preview-icon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>

                            <div class="service-preview-content">

                                <span
                                    id="previewCategory"
                                    class="service-preview-category"
                                >
                                    Service Category
                                </span>

                                <h3 id="previewName">
                                    New Service
                                </h3>

                                <p id="previewDescription">
                                    Your service description will appear here.
                                </p>

                                <div class="service-preview-meta">

                                    <div>

                                        <i class="fa-solid fa-money-bill-wave"></i>

                                        <strong id="previewPrice">
                                            0 MMK
                                        </strong>

                                    </div>

                                    <div>

                                        <i class="fa-regular fa-clock"></i>

                                        <strong id="previewDuration">
                                            0 min
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
                <div class="service-tip-box">

                    <div class="service-tip-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>

                    <div>

                        <h3>Quick Tip</h3>

                        <p>
                            Use a clear service name and description
                            so customers can easily understand what
                            they are booking.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             FORM ACTIONS
        ====================================================== --}}
        <div class="service-form-actions">

            <a
                href="{{ route('admin.services.index') }}"
                class="admin-service-btn admin-service-btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                id="createServiceButton"
                class="admin-service-btn admin-service-btn-primary"
            >

                <i class="fa-solid fa-plus"></i>

                <span>Create Service</span>

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

    <script
        src="{{ asset('js/admin-service-create.js') }}"
    ></script>

@endpush

