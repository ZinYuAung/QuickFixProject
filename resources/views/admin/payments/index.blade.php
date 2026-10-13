@extends('layouts.admin')

@section('title', 'Payments')

@section('content')

<div class="admin-dashboard admin-payments-page">

    {{-- PAGE HEADER --}}
    <div class="admin-page-header">
        <div class="admin-page-header-content">
            <h1>Payments</h1>
            <p>View and manage all transaction, prepaid, and service payment records.</p>
        </div>
    </div>

 

    {{-- STATISTICS GRID --}}
    <div class="admin-stat-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-icon services">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="admin-stat-content">
                <span>Total Service Revenue</span>
                <h2>${{ number_format($totalRevenue ?? 0, 2) }}</h2>
                <div class="admin-stat-note">Sum of all service amounts</div>
            </div>
        </div>
    </div>

    {{-- PAYMENTS TABLE CARD --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3>Payment Transactions</h3>
                <p>List of all customer payment records and statuses</p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-bookings-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Provider</th>
                        <th>Service Amount</th>
                        <th>Prepaid / Remaining</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <strong>#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                <strong>#BOOK-{{ str_pad($payment->booking_id, 4, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td>
                                {{ $payment->booking?->customer?->user?->name ?? '—' }}
                            </td>
                            <td>
                                {{ $payment->booking?->provider?->user?->name ?? '—' }}
                            </td>
                            <td>
                                ${{ number_format((float)($payment->service_amount ?? 0), 2) }}
                            </td>
                            <td>
                                <small class="text-muted">Prepaid:</small> ${{ number_format((float)($payment->prepaid_amount ?? 0), 2) }}<br>
                                <small class="text-muted">Remaining:</small> ${{ number_format((float)($payment->remaining_amount ?? 0), 2) }}
                            </td>
                            <td>
                                <span class="badge bg-secondary text-uppercase" style="font-size: 11px;">
                                    {{ $payment->payment_method ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="admin-booking-status">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_status ?? 'pending')) }}
                                </span>
                            </td>
                            <td>
                                {{ $payment->booking?->created_at?->format('M d, Y h:i A') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fa-solid fa-credit-card fa-2x text-muted mb-3"></i>
                                    <h5>No payments found</h5>
                                    <p class="text-muted mb-0">There are no transaction records available yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER / PAGINATION --}}
        @if($payments->hasPages())
            <div class="admin-providers-footer p-3">
                <span>Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments</span>
                <div class="admin-pagination">
                    {{ $payments->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

@endsection