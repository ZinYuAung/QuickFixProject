@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')

<div class="admin-dashboard admin-categories-page">

    <!-- HEADER -->

    <div class="admin-page-header">

        <div class="admin-page-header-content">

            <h1>
               
            </h1>

            <p>
               
            </p>

        </div>

        <div class="admin-page-header-actions">

            <a
                href="{{ route(
                    'admin.categories.edit',
                    $category->id
                ) }}"
                class="admin-primary-btn"
            >

                <i class="fa-solid fa-pen"></i>

                Edit Category

            </a>

            <a
                href="{{ route('admin.categories.index') }}"
                class="admin-small-btn"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>

    </div>


    

    <!-- CATEGORY INFORMATION -->

    <div class="admin-card mb-4">

        <div class="admin-card-header">

            <div>

                <h3>
                    Category Information
                </h3>

                <p>
                    #CAT-{{ str_pad(
                        $category->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    ) }}
                </p>

            </div>

            <div>

                @if ($category->is_active)

                    <span
                        class="admin-category-status active"
                    >

                        <span class="status-dot"></span>

                        Active

                    </span>

                @else

                    <span
                        class="admin-category-status inactive"
                    >

                        <span class="status-dot"></span>

                        Inactive

                    </span>

                @endif

            </div>

        </div>


        <div class="row g-4 p-4">

            <div class="col-md-6">

                <small class="text-muted">
                    Category Name
                </small>

                <h4>
                    {{ $category->name }}
                </h4>

            </div>


            <div class="col-md-6">

                <small class="text-muted">
                    Description
                </small>

                <p>
                    {{ $category->description ?: 'No description provided.' }}
                </p>

            </div>

        </div>

    </div>


    <!-- STATISTICS -->

    <div class="admin-stat-grid">

        <div class="admin-stat-card">

            <div class="admin-stat-icon services">

                <i class="fa-solid fa-screwdriver-wrench"></i>

            </div>

            <div class="admin-stat-content">

                <span>Services</span>

                <h2>
                    {{ $category->services->count() }}
                </h2>

            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-icon providers">

                <i class="fa-solid fa-user-gear"></i>

            </div>

            <div class="admin-stat-content">

                <span>Providers</span>

                <h2>
                    {{ $providerCount }}
                </h2>

            </div>

        </div>


        <div class="admin-stat-card">

            <div class="admin-stat-icon bookings">

                <i class="fa-solid fa-calendar-check"></i>

            </div>

            <div class="admin-stat-content">

                <span>Bookings</span>

                <h2>
                    {{ $bookingCount }}
                </h2>

            </div>

        </div>

    </div>


    <!-- SERVICES -->

    <div class="admin-card mt-4">

        <div class="admin-card-header">

            <div>

                <h3>
                    Services in this Category
                </h3>

                <p>
                    Services currently assigned to this category.
                </p>

            </div>

        </div>


        <div class="admin-table-wrapper">

            <table class="admin-bookings-table">

                <thead>

                    <tr>

                        <th>Service</th>

                        <th>Price</th>

                        <th>Duration</th>

                        <th>Providers</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse (
                        $category->services as $service
                    )

                        <tr>

                            <td>

                                <strong>
                                    {{ $service->name }}
                                </strong>

                                <small class="d-block text-muted">
                                    #SER-{{ str_pad(
                                        $service->id,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}
                                </small>

                            </td>

                            <td>
                                {{ number_format(
                                    $service->base_price
                                ) }}
                                MMK
                            </td>

                            <td>
                                {{ $service->estimated_minutes }}
                                min
                            </td>

                            <td>
                                {{ $service->providers->count() }}
                            </td>

                            <td>

                                @if ($service->is_active)

                                    <span
                                        class="admin-category-status active"
                                    >

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span
                                        class="admin-category-status inactive"
                                    >

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5"
                            >

                                <i
                                    class="fa-solid fa-screwdriver-wrench mb-2"
                                ></i>

                                <p class="mb-0">
                                    No services are assigned
                                    to this category.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <!-- STATUS ACTION -->

    <div class="admin-card mt-4">

        <div class="p-4">

            @if ($category->is_active)

                <form
                    action="{{ route(
                        'admin.categories.destroy',
                        $category->id
                    ) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to deactivate this category?');"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="fa-solid fa-trash"></i>

                        Deactivate Category

                    </button>

                </form>

            @else

                <form
                    action="{{ route(
                        'admin.categories.reactivate',
                        $category->id
                    ) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to reactivate this category?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa-solid fa-rotate-right"></i>

                        Reactivate Category

                    </button>

                </form>

            @endif

        </div>

    </div>

</div>

@endsection

