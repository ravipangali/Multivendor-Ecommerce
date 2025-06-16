@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Sale Details - ' . $sale->sale_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-success me-2" target="_blank">
                        <i data-feather="printer"></i> Print Receipt
                    </a>
                    <a href="{{ route('admin.in-house-sales.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left"></i> Back to Sales
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Sale Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Sale Information</h5>
                                <div>
                                    @php
                                        $statusClass = match($sale->payment_status) {
                                            'paid' => 'bg-success',
                                            'partial' => 'bg-warning',
                                            'pending' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} fs-6">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Sale Number:</td>
                                            <td>{{ $sale->sale_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Sale Date:</td>
                                            <td>{{ $sale->sale_date->format('M d, Y g:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Cashier:</td>
                                            <td>{{ $sale->cashier->name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Payment Method:</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Customer Name:</td>
                                            <td>{{ $sale->customer_name ?? 'Walk-in Customer' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Phone:</td>
                                            <td>{{ $sale->customer_phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email:</td>
                                            <td>{{ $sale->customer_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Address:</td>
                                            <td>{{ $sale->customer_address ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($sale->notes)
                                <div class="mt-3">
                                    <strong>Notes:</strong>
                                    <p class="text-muted mb-0">{{ $sale->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sale Items -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sale Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Variation</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->saleItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->thumbnail_img)
                                                        <img src="{{ uploaded_asset($item->product->thumbnail_img) }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="rounded me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px;">
                                                            <i data-feather="image" class="text-muted" style="width: 20px; height: 20px;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        @if($item->product)
                                                            <br><small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->variation_name)
                                                    <span class="badge bg-secondary">{{ $item->variation_name }}</span>
                                                @else
                                                    <span class="text-muted">No variation</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">Rs {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">
                                                <strong>Rs {{ number_format($item->total_price, 2) }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sale Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sale Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">Rs {{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                @if($sale->discount_amount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end text-success">- Rs {{ number_format($sale->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($sale->tax_amount > 0)
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">Rs {{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($sale->shipping_amount > 0)
                                <tr>
                                    <td>Shipping:</td>
                                    <td class="text-end">Rs {{ number_format($sale->shipping_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>Rs {{ number_format($sale->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Paid Amount:</td>
                                    <td class="text-end text-success">Rs {{ number_format($sale->paid_amount, 2) }}</td>
                                </tr>
                                @if($sale->due_amount > 0)
                                <tr>
                                    <td><strong>Due Amount:</strong></td>
                                    <td class="text-end text-danger"><strong>Rs {{ number_format($sale->due_amount, 2) }}</strong></td>
                                </tr>
                                @endif
                            </table>

                            @if($sale->payment_status !== 'paid' && $sale->due_amount > 0)
                                <div class="mt-3">
                                    <button type="button" class="btn btn-warning w-100"
                                            onclick="updatePayment({{ $sale->id }}, '{{ $sale->sale_number }}', {{ $sale->due_amount }})">
                                        <i data-feather="credit-card"></i> Update Payment
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-success" target="_blank">
                                    <i data-feather="printer"></i> Print Receipt
                                </a>
                                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                                    <i data-feather="plus"></i> New Sale
                                </a>
                                <form action="{{ route('admin.in-house-sales.destroy', $sale) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100"
                                            onclick="return confirm('Are you sure you want to delete this sale? This action cannot be undone.')">
                                        <i data-feather="trash-2"></i> Delete Sale
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="partial">Partial Payment</option>
                            <option value="paid">Fully Paid</option>
                            <option value="pending">Pending</option>
                        </select>
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
