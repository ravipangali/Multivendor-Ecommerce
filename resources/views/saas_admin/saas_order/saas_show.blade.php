@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Order Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Order #{{ $order->order_number }}</h5>
                <div>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Order
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Order Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Order Date:</strong> {{ $order->placed_at ? $order->placed_at->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                            <p>
                                <strong>Payment Status:</strong>
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </p>
                            <p>
                                <strong>Order Status:</strong>
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
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            @if($order->shipping_address)
                                <p>{{ $order->shipping_address }}</p>
                            @else
                                <p>No shipping address provided.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Billing Address</h5>
                        </div>
                        <div class="card-body">
                            @if($order->billing_address)
                                <p>{{ $order->billing_address }}</p>
                            @else
                                <p>No billing address provided.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product && isset($item->product->images) && $item->product->images->count() > 0)
                                                <img src="{{ asset('storage/'. $item->product->images->first()->image_url) }}" alt="{{ $item->product->name }}" width="40" class="img-thumbnail me-2">
                                            @endif
                                            {{ $item->product->name ?? 'Product Name' }}
                                            @if($item->variation)
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->variation->attribute->name ?? '' }}: {{ $item->variation->attributeValue->value ?? '' }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rs{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">Rs{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">Rs{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td class="text-end">Rs{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                    <td class="text-end">Rs{{ number_format($order->shipping_fee, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                    <td class="text-end">-Rs{{ number_format($order->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                    <td class="text-end"><strong>Rs{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->admin_note)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">Admin Notes</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->admin_note }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
