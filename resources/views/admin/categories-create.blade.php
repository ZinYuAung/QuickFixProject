@extends('layouts.admin')

@section('title', 'Add Category')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/admin-category-create.css') }}"
    >
@endpush

@section('content')

<div class="admin-category-create-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="admin-page-header category-create-header">

        <div class="category-create-header-content">

            <div class="category-breadcrumb">

                <a href="{{ route('admin.categories.index') }}">
                    Categories
                </a>

                <i class="fa-solid fa-chevron-right"></i>

                <span>Add Category</span>

            </div>

            <h1>
                Add New Category
            </h1>

            <p>
                Create a new service category for QuickFix.
            </p>

        </div>

        <div class="category-create-header-action">

            <a
                href="{{ route('admin.categories.index') }}"
                class="admin-category-btn admin-category-btn-secondary"
            >
                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Back to Categories
                </span>
            </a>

        </div>

    </div>


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
         CREATE FORM
    ====================================================== --}}

    <form
        action="{{ route('admin.categories.store') }}"
        method="POST"
        id="createCategoryForm"
    >

        @csrf


        {{-- =================================================
             MAIN CARD
        ================================================== --}}

        <div class="category-form-card">

            {{-- CARD HEADER --}}

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
                            Enter the basic information for this category.
                        </p>

                    </div>

                </div>

            </div>


            {{-- CARD BODY --}}

            <div class="category-card-body">


                {{-- =================================================
                     CATEGORY NAME
                ================================================== --}}

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
                        value="{{ old('name') }}"
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


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}

                <div class="category-form-group">

                    <div class="category-label-row">

                        <label for="description">

                            Description

                        </label>

                        <span id="categoryDescriptionCounter">
                            0 / 1000
                        </span>

                    </div>

                    <textarea
                        id="description"
                        name="description"
                        class="category-input category-textarea @error('description') input-error @enderror"
                        rows="6"
                        maxlength="1000"
                        placeholder="Describe the services included in this category..."
                    >{{ old('description') }}</textarea>

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


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="category-form-group category-status-group">

                    <div class="category-status-box">

                        <label
                            for="is_active"
                            class="category-status-toggle"
                        >

                            <div class="category-status-information">

                                <div class="category-status-icon">
                                    <i class="fa-solid fa-check"></i>
                                </div>

                                <div>

                                    <strong>
                                        Active Category
                                    </strong>

                                    <span>
                                        Active categories are available when creating services.
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

                            <span class="category-custom-toggle"></span>

                        </label>

                    </div>

                </div>


                {{-- =================================================
                     FORM ACTIONS
                ================================================== --}}

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
                        id="createCategoryButton"
                        class="admin-category-btn admin-category-btn-primary"
                    >

                        <i class="fa-solid fa-plus"></i>

                        <span>
                            Create Category
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

    <script
        src="{{ asset('js/admin-category-create.js') }}"
    ></script>

@endpush