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
                                            <h3 class="text-white mb-0">Rs {{ number_format($totalSales, 2) }}</h3>
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
                                            <strong>{{ $sale->customer_name ?? 'Walk-in Customer' }}</strong>
                                            @if($sale->customer_phone)
                                                <br><small class="text-muted">{{ $sale->customer_phone }}</small>
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
                                        @if($sale->due_amount > 0)
                                            <br><small class="text-danger">Due: Rs {{ number_format($sale->due_amount, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.in-house-sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-sm btn-outline-success" title="Print Receipt" target="_blank">
                                                <i data-feather="printer"></i>
                                            </a>
                                            @if($sale->payment_status !== 'paid')
                                                <button type="button" class="btn btn-sm btn-outline-warning" title="Update Payment"
                                                        onclick="updatePayment({{ $sale->id }}, '{{ $sale->sale_number }}', {{ $sale->due_amount }})">
                                                    <i data-feather="credit-card"></i>
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.in-house-sales.destroy', $sale) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this sale?')">
                                                    <i data-feather="trash-2"></i>
                                                </button>
                                            </form>
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

<!-- Payment Update Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Update Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paid_amount" class="form-label">Payment Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rs</span>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" step="0.01" min="0" required>
                        </div>
                        <small class="text-muted">Due Amount: Rs <span id="due_amount_display"></span></small>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_note" class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="payment_note" name="payment_note" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updatePayment(saleId, saleNumber, dueAmount) {
        document.getElementById('paymentModalLabel').textContent = 'Update Payment for ' + saleNumber;
                                document.getElementById('paymentForm').action = '{{ route("admin.in-house-sales.index") }}/' + saleId + '/payment-status';
        document.getElementById('paid_amount').max = dueAmount;
        document.getElementById('paid_amount').value = dueAmount;
        document.getElementById('due_amount_display').textContent = dueAmount.toFixed(2);

        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }
</script>
@endsection
