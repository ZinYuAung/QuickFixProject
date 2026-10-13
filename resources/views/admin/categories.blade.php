@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="admin-dashboard admin-categories-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
                Categories
            </h1>

            <p>
                View and manage service categories available on QuickFix.
            </p>

        </div>

        <div class="admin-page-header-actions">


        </div>

    </div>





    <!-- =====================================================
         CATEGORY STATISTICS
    ====================================================== -->

    <div class="admin-stat-grid admin-category-stat-grid">


        <!-- TOTAL CATEGORIES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon services">

                <i class="fa-solid fa-layer-group"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Total Categories
                </span>

                <h2>
                    {{ $totalCategories }}
                </h2>

                <div class="admin-stat-note">
                    All categories
                </div>

            </div>

        </div>


        <!-- ACTIVE CATEGORIES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon users">

                <i class="fa-solid fa-circle-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Active Categories
                </span>

                <h2>
                    {{ $activeCategories }}
                </h2>

                <div class="admin-stat-note">
                    Currently available
                </div>

            </div>

        </div>


        <!-- TOTAL SERVICES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon providers">

                <i class="fa-solid fa-screwdriver-wrench"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Total Services
                </span>

                <h2>
                    {{ $totalServices }}
                </h2>

                <div class="admin-stat-note">
                    Services in categories
                </div>

            </div>

        </div>


        <!-- INACTIVE CATEGORIES -->

        <div class="admin-stat-card">

            <div class="admin-stat-icon bookings">

                <i class="fa-solid fa-circle-pause"></i>

            </div>

            <div class="admin-stat-content">

                <span>
                    Inactive Categories
                </span>

                <h2>
                    {{ $inactiveCategories }}
                </h2>

                <div class="admin-stat-note">
                    Currently unavailable
                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         CATEGORY MANAGEMENT CARD
    ====================================================== -->

    <div class="admin-card admin-categories-card">


        <!-- CARD HEADER -->

        <div class="admin-card-header">

            <div>

                <h3>
                    All Categories
                </h3>

                <p>
                    Service category information and availability
                </p>

            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                class="admin-small-btn"
                id="addCategoryBtn"
            >
                <i class="fa-solid fa-plus"></i>
                <span>
                    Add Category
                </span>
            </a>

        </div>


        <!-- =================================================
             TOOLBAR
        ================================================== -->

        <div class="admin-category-toolbar">

            <form
                action="{{ route('admin.categories.index') }}"
                method="GET"
                class="admin-category-toolbar"
            >


                <!-- SEARCH -->

                <div class="admin-category-search">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        id="categorySearch"
                        value="{{ request('search') }}"
                        placeholder="Search categories..."
                        autocomplete="off"
                    >

                </div>


                <!-- STATUS -->

                <div class="admin-category-filter">

                    <select
                        id="categoryStatusFilter"
                        name="status"
                        class="admin-filter-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            {{ request('status') === 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <!-- FILTER BUTTON -->

                <button
                    type="submit"
                    class="admin-small-btn"
                >
                    <i class="fa-solid fa-filter"></i>
                    <span>
                        Filter
                    </span>
                </button>


                <!-- CLEAR FILTER -->

                @if (
                    request()->filled('search')
                    || request()->filled('status')
                )

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="admin-small-btn"
                    >
                        <i class="fa-solid fa-xmark"></i>
                        <span>
                            Clear
                        </span>
                    </a>

                @endif

            </form>

        </div>


        <!-- =================================================
             CATEGORY TABLE
        ================================================== -->

        <div class="admin-table-wrapper">

            <table
                class="admin-bookings-table admin-categories-table"
                id="categoriesTable"
            >

                <thead>

                    <tr>

                        <th>
                            Category
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Services
                        </th>

                        <th>
                            Providers
                        </th>

                        <th>
                            Bookings
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($categories as $category)

                        @php

                            $isActive = $category->is_active == 1;

                            $stats = $categoryStats[$category->id]
                                ?? [
                                    'services' => 0,
                                    'providers' => 0,
                                    'bookings' => 0
                                ];

                            $categoryName = strtolower($category->name);

                            if (str_contains($categoryName, 'clean')) {

                                $categoryIcon = 'fa-broom';

                            } elseif (str_contains($categoryName, 'plumb')) {

                                $categoryIcon = 'fa-faucet-drip';

                            } elseif (str_contains($categoryName, 'electric')) {

                                $categoryIcon = 'fa-bolt';

                            } elseif (str_contains($categoryName, 'air')) {

                                $categoryIcon = 'fa-snowflake';

                            } elseif (str_contains($categoryName, 'appliance')) {

                                $categoryIcon = 'fa-plug';

                            } elseif (str_contains($categoryName, 'pest')) {

                                $categoryIcon = 'fa-bug';

                            } elseif (str_contains($categoryName, 'moving')) {

                                $categoryIcon = 'fa-truck-moving';

                            } elseif (str_contains($categoryName, 'garden')) {

                                $categoryIcon = 'fa-seedling';

                            } else {

                                $categoryIcon = 'fa-layer-group';

                            }

                        @endphp


                        <tr>


                            <!-- CATEGORY -->

                            <td>

                                <div class="admin-category-cell">

                                    <div class="admin-category-icon">

                                        <i class="fa-solid {{ $categoryIcon }}"></i>

                                    </div>

                                    <div class="admin-category-info">

                                        <strong>
                                            {{ $category->name }}
                                        </strong>

                                        <small>
                                            #CAT-{{ str_pad($category->id, 3, '0', STR_PAD_LEFT) }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- DESCRIPTION -->

                            <td>

                                <span
                                    class="category-description"
                                    title="{{ $category->description }}"
                                >

                                    {{ $category->description
                                        ? \Illuminate\Support\Str::limit($category->description, 45)
                                        : '—'
                                    }}

                                </span>

                            </td>


                            <!-- SERVICES -->

                            <td>
                                {{ $stats['services'] }}
                            </td>


                            <!-- PROVIDERS -->

                            <td>
                                {{ $stats['providers'] }}
                            </td>


                            <!-- BOOKINGS -->

                            <td>
                                {{ $stats['bookings'] }}
                            </td>


                            <!-- STATUS -->

                            <td>

                                @if ($isActive)

                                    <span class="admin-category-status active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="admin-category-status inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            <!-- ACTIONS -->

                            <td>

                                <div class="admin-category-actions">


                                    <!-- VIEW -->

                                    <a
                                        href="{{ route('admin.categories.show', $category->id) }}"
                                        class="admin-table-action view"
                                        title="View Category"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>


                                    <!-- EDIT -->

                                    <a
                                        href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="admin-table-action edit"
                                        title="Edit Category"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>


                                    <!-- DEACTIVATE / REACTIVATE -->

                                    @if ($isActive)

                                        <form
                                            action="{{ route('admin.categories.destroy', $category->id) }}"
                                            method="POST"
                                            class="category-action-form"
                                            onsubmit="return confirm('Are you sure you want to deactivate this category?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="admin-table-action delete"
                                                title="Deactivate Category"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>

                                    @else

                                        <form
                                            action="{{ route('admin.categories.reactivate', $category->id) }}"
                                            method="POST"
                                            class="category-action-form"
                                            onsubmit="return confirm('Are you sure you want to reactivate this category?');"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="admin-table-action reactivate"
                                                title="Reactivate Category"
                                            >
                                                <i class="fa-solid fa-rotate-right"></i>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="admin-empty-state"
                            >

                                <div>

                                    <i class="fa-solid fa-layer-group"></i>

                                    <h4>
                                        No categories found
                                    </h4>

                                    <p>
                                        There are no categories matching your current filters.
                                    </p>

                                    <a
                                        href="{{ route('admin.categories.create') }}"
                                        class="admin-small-btn"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                        Add Category
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <div class="admin-categories-footer">

            <span id="categoryResultCount">

                Showing

                {{ $categories->firstItem() ?? 0 }}

                to

                {{ $categories->lastItem() ?? 0 }}

                of

                {{ $categories->total() }}

                categories

            </span>


            <div class="admin-pagination">

                {{ $categories->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
