@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Order Details - {{ $order->order_number }}</h5>
            <div>
                <a href="{{ route('seller.orders.edit', $order->id) }}" class="btn btn-warning">
                    <i class="align-middle" data-feather="edit"></i> Edit Order
                </a>
                <a href="{{ route('seller.orders.invoice', $order->id) }}" class="btn btn-info">
                    <i class="align-middle" data-feather="printer"></i> Print Invoice
                </a>
                <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Order Status Badge -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'info') }} text-center">
                        <h4 class="mb-0">
                            Order Status: 
                            <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'primary') }} fs-6">
                                {{ ucwords($order->order_status) }}
                            </span>
                        </h4>
                        @if($order->order_status === 'pending')
                            <small class="text-muted">This order is waiting for your confirmation</small>
                        @elseif($order->order_status === 'processing')
                            <small class="text-muted">This order is being prepared</small>
                        @elseif($order->order_status === 'shipped')
                            <small class="text-muted">This order has been shipped to the customer</small>
                        @elseif($order->order_status === 'delivered')
                            <small class="text-muted">This order has been successfully delivered</small>
                        @elseif($order->order_status === 'cancelled')
                            <small class="text-muted">This order has been cancelled</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Order Information</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Order Number:</dt>
                                <dd class="col-sm-8"><strong>{{ $order->order_number }}</strong></dd>

                                <dt class="col-sm-4">Order Date:</dt>
                                <dd class="col-sm-8">{{ $order->placed_at ? \Carbon\Carbon::parse($order->placed_at)->format('F d, Y h:i A') : $order->created_at->format('F d, Y h:i A') }}</dd>

                                <dt class="col-sm-4">Payment Method:</dt>
                                <dd class="col-sm-8">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</dd>

                                <dt class="col-sm-4">Payment Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucwords($order->payment_status) }}
                                    </span>
                                </dd>

                                @if($order->order_notes)
                                <dt class="col-sm-4">Order Notes:</dt>
                                <dd class="col-sm-8">{{ $order->order_notes }}</dd>
                                @endif

                                @if($order->cancelled_at)
                                <dt class="col-sm-4">Cancelled At:</dt>
                                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('F d, Y h:i A') }}</dd>
                                @endif

                                @if($order->cancellation_reason)
                                <dt class="col-sm-4">Cancellation Reason:</dt>
                                <dd class="col-sm-8">{{ $order->cancellation_reason }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Customer Information</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Customer:</dt>
                                <dd class="col-sm-8"><strong>{{ $order->customer->name }}</strong></dd>

                                <dt class="col-sm-4">Email:</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $order->customer->email }}">{{ $order->customer->email }}</a>
                                </dd>

                                <dt class="col-sm-4">Phone:</dt>
                                <dd class="col-sm-8">
                                    <a href="tel:{{ $order->shipping_phone }}">{{ $order->shipping_phone }}</a>
                                </dd>

                                <dt class="col-sm-4">Customer Since:</dt>
                                <dd class="col-sm-8">{{ $order->customer->created_at->format('F Y') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping & Billing Addresses -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Shipping Address</h6>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->shipping_name }}</strong><br>
                                {{ $order->shipping_street_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                                {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}<br>
                                <abbr title="Phone">P:</abbr> {{ $order->shipping_phone }}<br>
                                <abbr title="Email">E:</abbr> {{ $order->shipping_email }}
                            </address>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Billing Address</h6>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->billing_name }}</strong><br>
                                {{ $order->billing_street_address }}<br>
                                {{ $order->billing_city }}, {{ $order->billing_state }}<br>
                                {{ $order->billing_postal_code }}<br>
                                {{ $order->billing_country }}<br>
                                <abbr title="Phone">P:</abbr> {{ $order->billing_phone }}<br>
                                <abbr title="Email">E:</abbr> {{ $order->billing_email }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Order Items</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Image</th>
                                            <th>SKU</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    @if($item->product_variation_id && $item->productVariation)
                                                        <br><small class="text-muted">
                                                            Variation: {{ $item->productVariation->attribute->name ?? 'N/A' }} - {{ $item->productVariation->attributeValue->value ?? 'N/A' }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->product->images && $item->product->images->count() > 0)
                                                    <img src="{{ $item->product->images->first()->image_url }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i data-feather="image" class="text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->product->SKU }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">Rs {{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">Rs {{ number_format($item->quantity * $item->price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-end">Subtotal:</th>
                                            <th class="text-end">Rs {{ number_format($order->subtotal, 2) }}</th>
                                        </tr>
                                        @if($order->discount > 0)
                                        <tr>
                                            <th colspan="5" class="text-end text-success">Discount:</th>
                                            <th class="text-end text-success">-Rs {{ number_format($order->discount, 2) }}</th>
                                        </tr>
                                        @endif
                                        @if($order->coupon_code)
                                        <tr>
                                            <th colspan="5" class="text-end text-success">Coupon ({{ $order->coupon_code }}):</th>
                                            <th class="text-end text-success">-Rs {{ number_format($order->coupon_discount_amount, 2) }}</th>
                                        </tr>
                                        @endif
                                        @if($order->tax > 0)
                                        <tr>
                                            <th colspan="5" class="text-end">Tax:</th>
                                            <th class="text-end">Rs {{ number_format($order->tax, 2) }}</th>
                                        </tr>
                                        @endif
                                        @if($order->shipping_fee > 0)
                                        <tr>
                                            <th colspan="5" class="text-end">Shipping Fee:</th>
                                            <th class="text-end">Rs {{ number_format($order->shipping_fee, 2) }}</th>
                                        </tr>
                                        @endif
                                        <tr class="table-primary">
                                            <th colspan="5" class="text-end">Total Amount:</th>
                                            <th class="text-end">Rs {{ number_format($order->total, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Order Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Order Placed</h6>
                                        <p class="timeline-text">{{ $order->placed_at ? \Carbon\Carbon::parse($order->placed_at)->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A') }}</p>
                                        <small class="text-muted">Customer placed the order</small>
                                    </div>
                                </div>

                                @if($order->order_status !== 'pending')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-{{ $order->order_status === 'cancelled' ? 'danger' : 'primary' }}"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Status Updated: {{ ucwords($order->order_status) }}</h6>
                                        <p class="timeline-text">{{ $order->updated_at->format('M d, Y h:i A') }}</p>
                                        <small class="text-muted">Order status changed to {{ $order->order_status }}</small>
                                    </div>
                                </div>
                                @endif

                                @if($order->cancelled_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Order Cancelled</h6>
                                        <p class="timeline-text">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('M d, Y h:i A') }}</p>
                                        @if($order->cancellation_reason)
                                        <small class="text-muted">Reason: {{ $order->cancellation_reason }}</small>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-content {
        padding-left: 10px;
    }

    .timeline-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .timeline-text {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 0;
    }

    address {
        line-height: 1.6;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
