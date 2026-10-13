@extends('layouts.admin')

@section('title', 'Edit Service')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-service-edit.css') }}"
    >
@endpush

@section('content')

<div class="admin-service-edit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="admin-page-header service-edit-header">

        <div class="service-edit-header-content">

            <div class="service-breadcrumb">

                <a href="{{ route('admin.services.index') }}">
                    Services
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <a href="{{ route('admin.services.show', $service->id) }}">
                    Service Details
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>Edit Service</span>

            </div>

            <div class="service-edit-title-row">

                <div>

                    <h1>Edit Service</h1>

                    <p>
                        Update the information and availability of this QuickFix service.
                    </p>

                </div>

                <span
                    class="service-id-badge"
                >
                    #SER-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}
                </span>

            </div>

        </div>

        <div class="service-edit-header-actions">

            <a
                href="{{ route('admin.services.show', $service->id) }}"
                class="admin-service-btn admin-service-btn-secondary"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Details</span>
            </a>

        </div>

    </div>



    {{-- =====================================================
         EDIT FORM
    ====================================================== --}}
    <form
        action="{{ route('admin.services.update', $service->id) }}"
        method="POST"
        id="editServiceForm"
    >

        @csrf

        @method('PUT')


        <div class="service-edit-layout">

            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}
            <div class="service-edit-main">


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
                                    Update the main information for this service.
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
                                value="{{ old('name', $service->name) }}"
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
                                        {{ old(
                                            'service_category_id',
                                            $service->service_category_id
                                        ) == $category->id ? 'selected' : '' }}
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
                                    {{ strlen(old('description', $service->description ?? '')) }}
                                    / 1000
                                </span>

                            </div>

                            <textarea
                                id="description"
                                name="description"
                                class="service-input service-textarea @error('description') input-error @enderror"
                                rows="6"
                                maxlength="1000"
                                placeholder="Describe what this service includes..."
                            >{{ old('description', $service->description) }}</textarea>

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
                                    Update the starting price and estimated service time.
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
                                        value="{{ old('base_price', $service->base_price) }}"
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


                            {{-- ESTIMATED MINUTES --}}
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
                                        value="{{ old('estimated_minutes', $service->estimated_minutes) }}"
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
                                    Approximate time required for this service.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     SERVICE INFORMATION
                ================================================== --}}
                <div class="service-form-card">

                    <div class="service-card-header">

                        <div class="service-card-title">

                            <div class="service-card-icon">
                                <i class="fa-solid fa-chart-simple"></i>
                            </div>

                            <div>

                                <h2>Service Information</h2>

                                <p>
                                    Current information related to this service.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="service-card-body">

                        <div class="service-info-grid">

                            {{-- SERVICE ID --}}
                            <div class="service-info-item">

                                <span class="service-info-label">
                                    Service ID
                                </span>

                                <strong>
                                    #SER-{{ str_pad($service->id, 3, '0', STR_PAD_LEFT) }}
                                </strong>

                            </div>


                            {{-- CATEGORY --}}
                            <div class="service-info-item">

                                <span class="service-info-label">
                                    Current Category
                                </span>

                                <strong>
                                    {{ $service->category?->name ?? '—' }}
                                </strong>

                            </div>


                            {{-- PROVIDERS --}}
                            <div class="service-info-item">

                                <span class="service-info-label">
                                    Providers
                                </span>

                                <strong>
                                    {{ $service->providers->count() }}
                                </strong>

                            </div>


                            {{-- CREATED --}}
                            <div class="service-info-item">

                                <span class="service-info-label">
                                    Created
                                </span>

                                <strong>
                                    @if ($service->created_at)
                                        {{ $service->created_at->format('d M Y') }}
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
            <div class="service-edit-sidebar">


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

                                    @if ($service->is_active)

                                        <i class="fa-solid fa-check"></i>

                                    @else

                                        <i class="fa-solid fa-pause"></i>

                                    @endif

                                </div>

                                <div>

                                    <strong>
                                        {{ $service->is_active
                                            ? 'Active Service'
                                            : 'Inactive Service'
                                        }}
                                    </strong>

                                    <span>
                                        {{ $service->is_active
                                            ? 'Customers can see and book this service.'
                                            : 'Customers cannot book this service.'
                                        }}
                                    </span>

                                </div>

                            </div>


                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            >

                            <span class="service-custom-toggle"></span>

                        </label>

                    </div>

                </div>



                {{-- =================================================
                     LIVE PREVIEW
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
                                    Preview your changes before saving.
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
                                    {{ $service->category?->name ?? 'Service Category' }}
                                </span>

                                <h3 id="previewName">
                                    {{ $service->name }}
                                </h3>

                                <p id="previewDescription">
                                    {{ $service->description ?: 'Your service description will appear here.' }}
                                </p>

                                <div class="service-preview-meta">

                                    <div>

                                        <i class="fa-solid fa-money-bill-wave"></i>

                                        <strong id="previewPrice">
                                            {{ number_format($service->base_price) }} MMK
                                        </strong>

                                    </div>

                                    <div>

                                        <i class="fa-regular fa-clock"></i>

                                        <strong id="previewDuration">
                                            {{ $service->estimated_minutes }} min
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     UPDATE NOTICE
                ================================================== --}}
                <div class="service-update-notice">

                    <div class="service-update-notice-icon">
                        <i class="fa-solid fa-rotate"></i>
                    </div>

                    <div>

                        <h3>Update Notice</h3>

                        <p>
                            Changes will be saved immediately when you
                            click <strong>Save Changes</strong>.
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
                href="{{ route('admin.services.show', $service->id) }}"
                class="admin-service-btn admin-service-btn-secondary"
            >
                Cancel
            </a>

            <button
                type="submit"
                id="saveServiceButton"
                class="admin-service-btn admin-service-btn-primary"
            >

                <i class="fa-solid fa-floppy-disk"></i>

                <span>Save Changes</span>

            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')

    <script
        src="{{ asset('js/admin-service-edit.js') }}"
    ></script>

@endpush

