@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Order Status')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Order #{{ $order->order_number }}</h5>
                <div>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Order
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Order Information</h6>
                            <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Total Amount:</strong> Rs. {{ number_format($order->total, 2) }}</p>
                            <p>
                                <strong>Current Status:</strong>
                                @if($order->order_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->order_status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->order_status == 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                @elseif($order->order_status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif($order->order_status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif($order->order_status == 'refunded')
                                    <span class="badge bg-secondary">Refunded</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Order Summary</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">Rs. {{ number_format($order->subtotal ?? 0, 2) }}</td>
                                </tr>
                                @if($order->hasTax())
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">Rs. {{ number_format($order->tax, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Shipping:</td>
                                    <td class="text-end">Rs. {{ number_format($order->shipping_fee ?? 0, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end">-Rs. {{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="fw-bold">
                                    <td>Total:</td>
                                    <td class="text-end">Rs. {{ number_format($order->total, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="order_status" name="order_status" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ $order->order_status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Update the order status to reflect current fulfillment state.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status (Read Only)</label>
                            <input type="text" class="form-control" value="{{ ucfirst($order->payment_status) }}" readonly>
                            <small class="text-muted">Payment status cannot be changed from here.</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="admin_note" class="form-label">Admin Notes</label>
                    <textarea class="form-control" id="admin_note" name="admin_note" rows="4" placeholder="Add internal notes about this order...">{{ old('admin_note', $order->admin_note) }}</textarea>
                    <small class="text-muted">These notes are only visible to admin users.</small>
                </div>

                <!-- Status Change Information -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0">Status Change Guidelines</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success">Forward Progression</h6>
                                <ul class="small text-muted">
                                    <li><strong>Pending → Processing:</strong> Order confirmed and being prepared</li>
                                    <li><strong>Processing → Shipped:</strong> Order dispatched for delivery</li>
                                    <li><strong>Shipped → Delivered:</strong> Order received by customer</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Special Actions</h6>
                                <ul class="small text-muted">
                                    <li><strong>Any → Cancelled:</strong> Order cancelled (stock restored)</li>
                                    <li><strong>Delivered → Refunded:</strong> Refund processed</li>
                                    <li><strong>Note:</strong> Status changes trigger customer notifications</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('order_status').addEventListener('change', function() {
    const status = this.value;
    const adminNote = document.getElementById('admin_note');

    // Suggest appropriate admin notes based on status
    if (status === 'shipped' && !adminNote.value) {
        adminNote.placeholder = 'e.g., Shipped via [Courier Name], Tracking: [Tracking Number]';
    } else if (status === 'delivered' && !adminNote.value) {
        adminNote.placeholder = 'e.g., Delivered successfully, confirmed with customer';
    } else if (status === 'cancelled' && !adminNote.value) {
        adminNote.placeholder = 'e.g., Cancelled due to [reason], stock restored';
    } else if (status === 'refunded' && !adminNote.value) {
        adminNote.placeholder = 'e.g., Refund processed to original payment method';
    } else {
        adminNote.placeholder = 'Add internal notes about this order...';
    }
});
</script>
@endsection
