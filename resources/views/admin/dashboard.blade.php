@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

    <div class="admin-dashboard">

        <!-- PAGE HEADER -->
        <div class="admin-page-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Manage and monitor your QuickFix platform.</p>
            </div>

            <div class="admin-page-header-actions">
               
            </div>
        </div>

        <!-- STAT CARDS (Responsive CSS မှ အလိုအလျောက် ပုံစံချပေးမည်) -->
        <div class="admin-stat-grid">
            <!-- Total Customers -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon users">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Total Customer</span>
                    <h2>{{ number_format($totalCustomers) }}</h2>
                    <small class="admin-stat-positive">
                        <i class="fa-solid fa-arrow-up"></i>
                        Active platform users
                    </small>
                </div>
            </div>

            <!-- Total Providers -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon providers">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Total Providers</span>
                    <h2>{{ number_format($totalProviders) }}</h2>
                    <div class="admin-stat-note">
                        {{ $pendingProviders }} waiting approval
                    </div>
                </div>
            </div>

            <!-- PENDING PROVIDER REQUESTS (NEW CARD) -->
            <a href="{{ route('admin.providers.index', ['approval_status' => 'pending']) }}" class="admin-stat-card text-decoration-none" style="cursor: pointer;">
                <div class="admin-stat-icon" style="background-color: #fff3cd; color: #ffc107;">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Pending Requests</span>
                    <h2 class="text-warning">{{ number_format($pendingProviders) }}</h2>
                    <small class="admin-stat-positive text-warning">
                        <i class="fa-solid fa-arrow-right"></i>
                        Review & Approve
                    </small>
                </div>
            </a>

            <!-- Total Services -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon services">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Total Services</span>
                    <h2>{{ number_format($totalServices) }}</h2>
                    <div class="admin-stat-note">
                        Across {{ $totalCategories }} categories
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="admin-stat-card">
                <div class="admin-stat-icon bookings">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="admin-stat-content">
                    <span>Total Bookings</span>
                    <h2>{{ number_format($totalBookings) }}</h2>
                    <div class="admin-stat-note">
                        {{ $todaysBookings }} new bookings today
                    </div>
                </div>
            </div>
        </div>

        <!-- =====================================================
            CHART ROW
        ====================================================== -->
        <div class="admin-chart-grid">

            <!-- PROVIDER STATUS -->
            <div class="admin-card admin-provider-status-card">
                <div class="admin-card-header">
                    <div>
                        <h3>Provider Status</h3>
                        <p>Current provider account status</p>
                    </div>
                </div>

                <div class="admin-provider-status-content">
                    <!-- Pie Chart -->
                    <div class="admin-pie-chart" style="background: {{ $pieGradient }};">
                        <div class="admin-pie-center">
                            <strong>{{ $totalProviderCount }}</strong>
                            <span>Providers</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="admin-pie-legend">
                        <!-- Approved -->
                        <div class="admin-legend-item">
                            <span class="legend-dot approved"></span>
                            <div>
                                <strong>{{ $providerStats['approved'] }}</strong>
                                <span>Approved</span>
                            </div>
                        </div>

                        <!-- Pending -->
                        <div class="admin-legend-item">
                            <span class="legend-dot pending"></span>
                            <div>
                                <strong>{{ $providerStats['pending'] }}</strong>
                                <span>Pending</span>
                            </div>
                        </div>

                        <!-- Rejected -->
                        <div class="admin-legend-item">
                            <span class="legend-dot rejected"></span>
                            <div>
                                <strong>{{ $providerStats['rejected'] }}</strong>
                                <span>Rejected</span>
                            </div>
                        </div>

                        <!-- Suspended -->
                        <div class="admin-legend-item">
                            <span class="legend-dot suspended"></span>
                            <div>
                                <strong>{{ $providerStats['suspended'] }}</strong>
                                <span>Suspended</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approval Rate -->
                <div class="admin-chart-footer">
                    <span>Approval Rate</span>
                    <strong>{{ $approvalRate }}%</strong>
                </div>
            </div>

   <!-- SERVICE OVERVIEW -->
            <div class="admin-card admin-booking-chart-card">
                <div class="admin-card-header">
                    <div>
                        <h3>Service Categories Booking Rate</h3>
                        <p>{{ $serviceOverviewDateRange ?? 'All-time statistics' }}</p>
                    </div>
                </div>

                <div class="admin-booking-chart" style="position: relative;">
                    @php
                        $totalPoints = count($serviceOverviewData);
                        $coords = [];
                        if ($totalPoints > 0) {
                            foreach ($serviceOverviewData as $index => $data) {
                                $x = 50 + ($index * (600 / max($totalPoints - 1, 1)));
                                $y = 230 - (($data['count'] / max($maxServiceBookings, 1)) * 200);
                                $coords[] = "{$x},{$y}";
                            }
                        }
                        $pointsString = implode(' ', $coords);
                    @endphp

                    @if($totalPoints > 0)
                        <!-- LINE CHART -->
                        <svg viewBox="0 0 700 260" preserveAspectRatio="none" class="admin-line-chart" style="width: 100%; height: auto;">
                            <!-- Grid Lines -->
                            <line x1="50" y1="30" x2="680" y2="30" class="chart-grid-line" />
                            <line x1="50" y1="80" x2="680" y2="80" class="chart-grid-line" />
                            <line x1="50" y1="130" x2="680" y2="130" class="chart-grid-line" />
                            <line x1="50" y1="180" x2="680" y2="180" class="chart-grid-line" />
                            <line x1="50" y1="230" x2="680" y2="230" class="chart-grid-line" />

                            <!-- Category Trend Line -->
                            <polyline points="{{ $pointsString }}" class="booking-chart-line" />

                            <!-- Category Points -->
                            @foreach($serviceOverviewData as $index => $data)
                                @php
                                    $x = 50 + ($index * (600 / max($totalPoints - 1, 1)));
                                    $y = 230 - (($data['count'] / max($maxServiceBookings, 1)) * 200);
                                @endphp

                                <circle cx="{{ $x }}" cy="{{ $y }}" r="5" class="booking-chart-point">
                                    <title>{{ $data['service_name'] }}: {{ $data['count'] }} bookings</title>
                                </circle>
                            @endforeach
                        </svg>

                        <!-- PERFECTLY ALIGNED SERVICE CATEGORY LABELS (RESPONSIVE) -->
                        <div style="position: relative; width: 100%; height: 35px; margin-top: 5px;">
                            @foreach($serviceOverviewData as $index => $data)
                                @php
                                    $x = 50 + ($index * (600 / max($totalPoints - 1, 1)));
                                    $leftPercent = ($x / 700) * 100;
                                @endphp
                                <span style="position: absolute; left: {{ $leftPercent }}%; transform: translateX(-50%); white-space: nowrap; font-size: 11px; font-weight: 500; color: var(--text-muted, #6c757d);">
                                    {{ $data['service_name'] }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <!-- EMPTY STATE -->
                        <div class="admin-chart-empty">
                            <i class="fa-solid fa-chart-line"></i>
                            <p>No category booking data available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- END CHART ROW -->

        <!-- =====================================================
            RECENT BOOKINGS
        ====================================================== -->
        <div class="admin-card admin-recent-bookings-card">
            <div class="admin-card-header">
                <div>
                    <h3>Recent Bookings</h3>
                    <p>Latest booking activities</p>
                </div>

                <a href="#" class="admin-view-all">
                    View All
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="admin-table-wrapper">
                <table class="admin-bookings-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Provider</th>
                            <th>Date & Time</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <!-- Booking ID -->
                                <td>
                                    <strong>BK-00{{ $booking->id }}</strong>
                                </td>

                                <!-- Customer -->
                                <td>
                                    {{ $booking->customer->name ?? 'N/A' }}
                                </td>

                                <!-- Service -->
                                <td>
                                    {{ $booking->service->name ?? 'N/A' }}
                                </td>

                                <!-- Provider -->
                                <td>
                                    {{ $booking->provider->name ?? 'N/A' }}
                                </td>

                                <!-- Date -->
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y h:i A') }}
                                </td>

                                <!-- Amount -->
                                <td>
                                    MMK {{ number_format($booking->amount ?? 0) }}
                                </td>

                                <!-- Status -->
                                <td>
                                    <span class="booking-status {{ strtolower($booking->status) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center;">
                                    No recent bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =====================================================
            QUICK ACTIONS
        ====================================================== -->
        <div class="admin-quick-actions">
            <div class="admin-section-heading">
                <div>
                    <h3>Quick Actions</h3>
                    <p>Frequently used admin functions</p>
                </div>
            </div>

            <div class="admin-action-grid">
                <!-- Manage Providers -->
                <a href="{{ route('admin.providers.index') }}" class="admin-action-card">
                    <div class="admin-action-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h4>Manage Providers</h4>
                        <p>Review and manage provider accounts.</p>
                    </div>
                    <i class="fa-solid fa-arrow-right admin-action-arrow"></i>
                </a>

                <!-- Manage Customers -->
                <a href="{{ route('admin.customers.index') }}" class="admin-action-card">
                    <div class="admin-action-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h4>Manage Customers</h4>
                        <p>View and manage customer accounts.</p>
                    </div>
                    <i class="fa-solid fa-arrow-right admin-action-arrow"></i>
                </a>

                <!-- Review Bookings -->
                <a href="{{ route('admin.bookings.index') }}" class="admin-action-card">
                    <div class="admin-action-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div>
                        <h4>Review Bookings</h4>
                        <p>Monitor current booking activities.</p>
                    </div>
                    <i class="fa-solid fa-arrow-right admin-action-arrow"></i>
                </a>

                <!-- Verify Payments -->
                <a href="{{ route('admin.payments.index') }}" class="admin-action-card">
                    <div class="admin-action-icon">
                        <i class="fa-solid fa-money-check-dollar"></i>
                    </div>
                    <div>
                        <h4>Verify Payments</h4>
                        <p>Check and verify payment records.</p>
                    </div>
                    <i class="fa-solid fa-arrow-right admin-action-arrow"></i>
                </a>
            </div>
        </div>

    </div>

@endsection