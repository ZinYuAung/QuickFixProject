@extends('layouts.admin')

@section('title', 'Edit Category')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-category-edit.css') }}"
    >
@endpush

@section('content')

<div class="admin-category-edit-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}
    <div class="admin-page-header category-edit-header">

        <div class="category-edit-header-content">

            {{-- BREADCRUMB --}}
            <div class="category-breadcrumb">

                <a href="{{ route('admin.categories.index') }}">
                    Categories
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <a href="{{ route('admin.categories.show', $category->id) }}">
                    Category Details
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>
                    Edit Category
                </span>

            </div>


            {{-- TITLE --}}
            <div class="category-edit-title-row">

                <div>

                    <h1>
                        Edit Category
                    </h1>

                    <p>
                        Update the information and availability of this QuickFix category.
                    </p>

                </div>


                <span class="category-id-badge">

                    #CAT-{{ str_pad(
                        $category->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ) }}

                </span>

            </div>

        </div>


        {{-- HEADER ACTION --}}
        <div class="category-edit-header-actions">

            <a
                href="{{ route('admin.categories.show', $category->id) }}"
                class="admin-category-btn admin-category-btn-secondary"
            >

                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Details
                </span>

            </a>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if (session('success'))

        <div class="category-alert category-alert-success">

            <div class="category-alert-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->any())

        <div class="category-alert category-alert-danger">

            <div class="category-alert-icon">

                <i class="fa-solid fa-circle-exclamation"></i>

            </div>

            <div class="category-alert-content">

                <strong>
                    Please fix the following errors:
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


    {{-- =====================================================
         EDIT FORM
    ====================================================== --}}
    <form
        action="{{ route('admin.categories.update', $category->id) }}"
        method="POST"
        id="editCategoryForm"
    >

        @csrf

        @method('PUT')


        <div class="category-edit-layout">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}
            <div class="category-edit-main">


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}
                <div class="category-form-card">

                    <div class="category-card-header">

                        <div class="category-card-title">

                            <div class="category-card-icon">

                                <i class="fa-solid fa-layer-group"></i>

                            </div>

                            <div>

                                <h2>
                                    Category Information
                                </h2>

                                <p>
                                    Update the main information for this category.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="category-card-body">


                        {{-- CATEGORY NAME --}}
                        <div class="category-form-group">

                            <label for="name">

                                Category Name

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $category->name) }}"
                                class="category-input @error('name') input-error @enderror"
                                placeholder="e.g. Cleaning"
                                maxlength="255"
                                required
                            >

                            @error('name')

                                <div class="category-field-error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    {{ $message }}

                                </div>

                            @enderror

                            <div class="category-field-help">
                                Enter a clear and unique category name.
                            </div>

                        </div>


                        {{-- DESCRIPTION --}}
                        <div class="category-form-group">

                            <div class="category-label-row">

                                <label for="description">

                                    Description

                                </label>

                                <span id="descriptionCounter">

                                    {{ strlen(
                                        old(
                                            'description',
                                            $category->description ?? ''
                                        )
                                    ) }}

                                    / 1000

                                </span>

                            </div>


                            <textarea
                                id="description"
                                name="description"
                                class="category-input category-textarea @error('description') input-error @enderror"
                                rows="6"
                                maxlength="1000"
                                placeholder="Describe the services included in this category..."
                            >{{ old('description', $category->description) }}</textarea>


                            @error('description')

                                <div class="category-field-error">

                                    <i class="fa-solid fa-circle-exclamation"></i>

                                    {{ $message }}

                                </div>

                            @enderror


                            <div class="category-field-help">

                                Give customers a clear explanation of the services included in this category.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     CATEGORY INFORMATION
                ================================================== --}}
                <div class="category-form-card">

                    <div class="category-card-header">

                        <div class="category-card-title">

                            <div class="category-card-icon">

                                <i class="fa-solid fa-chart-simple"></i>

                            </div>

                            <div>

                                <h2>
                                    Category Information
                                </h2>

                                <p>
                                    Current information related to this category.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="category-card-body">

                        <div class="category-info-grid">


                            {{-- CATEGORY ID --}}
                            <div class="category-info-item">

                                <span class="category-info-label">
                                    Category ID
                                </span>

                                <strong>

                                    #CAT-{{ str_pad(
                                        $category->id,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </strong>

                            </div>


                            {{-- SERVICES --}}
                            <div class="category-info-item">

                                <span class="category-info-label">
                                    Services
                                </span>

                                <strong>

                                    {{ $category->services->count() }}

                                </strong>

                            </div>


                            {{-- STATUS --}}
                            <div class="category-info-item">

                                <span class="category-info-label">
                                    Current Status
                                </span>

                                <strong
                                    id="currentStatusText"
                                    class="{{ $category->is_active
                                        ? 'category-status-active'
                                        : 'category-status-inactive'
                                    }}"
                                >

                                    {{ $category->is_active
                                        ? 'Active'
                                        : 'Inactive'
                                    }}

                                </strong>

                            </div>


                            {{-- CREATED --}}
                            <div class="category-info-item">

                                <span class="category-info-label">
                                    Created
                                </span>

                                <strong>

                                    @if ($category->created_at)

                                        {{ $category->created_at->format('d M Y') }}

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
            <div class="category-edit-sidebar">


                {{-- =================================================
                     CATEGORY STATUS
                ================================================== --}}
                <div class="category-form-card">

                    <div class="category-card-header">

                        <div class="category-card-title">

                            <div class="category-card-icon">

                                <i class="fa-solid fa-toggle-on"></i>

                            </div>

                            <div>

                                <h2>
                                    Category Status
                                </h2>

                                <p>
                                    Control category availability.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="category-card-body">

                        <label
                            for="is_active"
                            class="category-status-toggle"
                        >

                            <div class="category-status-information">

                                <div class="category-status-icon">

                                    @if ($category->is_active)

                                        <i class="fa-solid fa-check"></i>

                                    @else

                                        <i class="fa-solid fa-pause"></i>

                                    @endif

                                </div>


                                <div>

                                    <strong id="statusTitle">

                                        {{ $category->is_active
                                            ? 'Active Category'
                                            : 'Inactive Category'
                                        }}

                                    </strong>

                                    <span id="statusDescription">

                                        {{ $category->is_active
                                            ? 'Active categories are available when creating services.'
                                            : 'Inactive categories are not available when creating services.'
                                        }}

                                    </span>

                                </div>

                            </div>


                            <input
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old(
                                    'is_active',
                                    $category->is_active
                                ) ? 'checked' : '' }}
                            >


                            <span class="category-custom-toggle"></span>

                        </label>

                    </div>

                </div>



                {{-- =================================================
                     LIVE PREVIEW
                ================================================== --}}
                <div class="category-form-card">

                    <div class="category-card-header">

                        <div class="category-card-title">

                            <div class="category-card-icon">

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


                    <div class="category-card-body">

                        <div class="category-preview-box">

                            <div class="category-preview-icon">

                                <i class="fa-solid fa-layer-group"></i>

                            </div>


                            <div class="category-preview-content">

                                <span class="category-preview-label">
                                    SERVICE CATEGORY
                                </span>


                                <h3 id="previewName">
                                    {{ $category->name }}
                                </h3>


                                <p id="previewDescription">

                                    {{ $category->description
                                        ?: 'Your category description will appear here.'
                                    }}

                                </p>


                                <div class="category-preview-status">

                                    <span
                                        id="previewStatusDot"
                                        class="category-preview-status-dot
                                        {{ $category->is_active
                                            ? 'active'
                                            : 'inactive'
                                        }}"
                                    ></span>


                                    <strong id="previewStatus">

                                        {{ $category->is_active
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
                <div class="category-update-notice">

                    <div class="category-update-notice-icon">

                        <i class="fa-solid fa-rotate"></i>

                    </div>


                    <div>

                        <h3>
                            Update Notice
                        </h3>

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
        <div class="category-form-actions">

            <a
                href="{{ route('admin.categories.index') }}"
                class="admin-category-btn admin-category-btn-secondary"
            >

                <i class="fa-solid fa-xmark"></i>

                <span>
                    Cancel
                </span>

            </a>


            <button
                type="submit"
                id="saveCategoryButton"
                class="admin-category-btn admin-category-btn-primary"
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
        src="{{ asset('js/admin-category-edit.js') }}"
    ></script>

@endpush