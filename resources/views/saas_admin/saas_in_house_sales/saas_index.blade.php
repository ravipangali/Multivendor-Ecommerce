@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'In-House Sales')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">In-House Sales Management</h5>
                        <div>
                            <a href="{{ route('admin.in-house-sales.reports') }}" class="btn btn-success me-2">
                                <i data-feather="bar-chart-2"></i> Sales Reports
                            </a>
                            <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                                <i data-feather="plus"></i> New Sale (POS)
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Total Sales</h6>
                                            <h3 class="text-white mb-0">{{ $totalSales }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="shopping-cart" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Revenue</h6>
                                            <h3 class="text-white mb-0">Rs {{ number_format($totalRevenue, 2) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="dollar-sign" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Pending Payments</h6>
                                            <h3 class="text-white mb-0">{{ $pendingPayments }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="clock" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Today's Sales</h6>
                                            <h3 class="text-white mb-0">{{ $todaySales }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="calendar" class="icon-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.in-house-sales.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search by sale number, customer..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}" placeholder="From Date">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}" placeholder="To Date">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="payment_status">
                                        <option value="">All Payments</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-1">
                                    <a href="{{ route('admin.in-house-sales.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sale #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Subtotal</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                <tr>
                                    <td>
                                        <strong>{{ $sale->sale_number }}</strong>
                                        <br><small class="text-muted">{{ $sale->created_at->format('g:i A') }}</small>
                                    </td>
                                    <td>{{ $sale->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div>
                                            @if($sale->customer)
                                                <a href="{{ route('admin.customers.show', $sale->customer->id) }}" class="text-decoration-none">
                                                    <strong>{{ $sale->customer->name }}</strong>
                                                    <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.75em;"></i>
                                                </a>
                                                @if($sale->customer->phone)
                                                    <br><small class="text-muted">{{ $sale->customer->phone }}</small>
                                                @endif
                                            @else
                                                <strong>Walk-in Customer</strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $sale->saleItems->count() }} items</span>
                                        <br><small class="text-muted">{{ $sale->total_quantity }} qty</small>
                                    </td>
                                    <td>Rs {{ number_format($sale->subtotal, 2) }}</td>
                                    <td>Rs {{ number_format($sale->tax_amount, 2) }}</td>
                                    <td>
                                        <strong>Rs {{ number_format($sale->total_amount, 2) }}</strong>
                                        @if($sale->discount_amount > 0)
                                            <br><small class="text-success">Discount: Rs {{ number_format($sale->discount_amount, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($sale->payment_status) {
                                                'paid' => 'bg-success',
                                                'partial' => 'bg-warning',
                                                'pending' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.in-house-sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-sm btn-outline-success" title="Print Receipt" target="_blank">
                                                <i data-feather="printer"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-sale" title="Delete"
                                                    data-id="{{ $sale->id }}"
                                                    data-number="{{ $sale->sale_number }}">
                                                <i data-feather="trash-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i data-feather="shopping-cart" class="icon-lg mb-2"></i>
                                            <p class="mb-0">No sales records found</p>
                                            <a href="{{ route('admin.pos.index') }}" class="btn btn-primary btn-sm mt-2">
                                                Make Your First Sale
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($sales->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $sales->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    // Delete sale confirmation with SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-sale').forEach(button => {
            button.addEventListener('click', function() {
                const saleId = this.getAttribute('data-id');
                const saleNumber = this.getAttribute('data-number');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete sale "${saleNumber}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `{{ route('admin.in-house-sales.index') }}/${saleId}`;
                        form.submit();
                    }
                });
            });
        });

        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
