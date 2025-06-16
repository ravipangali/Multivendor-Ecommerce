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
                        <i class="align-middle" data-feather="edit"></i> Edit Status
                    </a>
                    <button type="button" class="btn btn-success" onclick="printOrder()">
                        <i class="align-middle" data-feather="printer"></i> Print
                    </button>
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
                            <h5 class="card-title mb-0">
                                <i class="align-middle" data-feather="user"></i> Customer Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                            @if($order->customer_id)
                                <p>
                                    <a href="{{ route('admin.customers.show', $order->customer_id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="align-middle" data-feather="external-link"></i> View Customer Profile
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="align-middle" data-feather="shopping-cart"></i> Order Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Order Date:</strong> {{ $order->placed_at ? $order->placed_at->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</p>
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
                            @if($order->seller_id)
                                <p>
                                    <strong>Seller:</strong>
                                    <a href="{{ route('admin.sellers.show', $order->seller_id) }}" class="text-decoration-none">
                                        {{ $order->seller->name ?? 'N/A' }}
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="align-middle" data-feather="map-pin"></i> Shipping Address
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($order->shipping_name || $order->shipping_email || $order->shipping_street_address)
                                <div class="address-details">
                                    @if($order->shipping_name)<strong>{{ $order->shipping_name }}</strong><br>@endif
                                    @if($order->shipping_email)<i class="align-middle" data-feather="mail"></i> {{ $order->shipping_email }}<br>@endif
                                    @if($order->shipping_phone)<i class="align-middle" data-feather="phone"></i> {{ $order->shipping_phone }}<br>@endif
                                    @if($order->shipping_street_address){{ $order->shipping_street_address }}<br>@endif
                                    @if($order->shipping_city || $order->shipping_state || $order->shipping_postal_code)
                                        {{ $order->shipping_city }}@if($order->shipping_city && $order->shipping_state), @endif{{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                    @endif
                                    @if($order->shipping_country){{ $order->shipping_country }}@endif
                                </div>
                            @else
                                <p class="text-muted">No shipping address provided.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="align-middle" data-feather="credit-card"></i> Billing Address
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($order->billing_name || $order->billing_email || $order->billing_street_address)
                                <div class="address-details">
                                    @if($order->billing_name)<strong>{{ $order->billing_name }}</strong><br>@endif
                                    @if($order->billing_email)<i class="align-middle" data-feather="mail"></i> {{ $order->billing_email }}<br>@endif
                                    @if($order->billing_phone)<i class="align-middle" data-feather="phone"></i> {{ $order->billing_phone }}<br>@endif
                                    @if($order->billing_street_address){{ $order->billing_street_address }}<br>@endif
                                    @if($order->billing_city || $order->billing_state || $order->billing_postal_code)
                                        {{ $order->billing_city }}@if($order->billing_city && $order->billing_state), @endif{{ $order->billing_state }} {{ $order->billing_postal_code }}<br>
                                    @endif
                                    @if($order->billing_country){{ $order->billing_country }}@endif
                                </div>
                            @else
                                <p class="text-muted">Same as shipping address</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="align-middle" data-feather="package"></i> Order Items
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    @if($order->hasTax())
                                        <th>Tax</th>
                                    @endif
                                    @if($order->discount > 0)
                                        <th>Discount</th>
                                    @endif
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && isset($item->product->images) && $item->product->images->count() > 0)
                                                    <img src="{{ $item->product->images->first()->image_url }}"
                                                         alt="{{ $item->product->name }}"
                                                         width="40"
                                                         class="img-thumbnail me-2 rounded">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center me-2 rounded"
                                                         style="width: 40px; height: 40px; min-width: 40px;">
                                                        <i data-feather="image" class="text-muted" style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->product->name ?? 'Product Name' }}</div>
                                                    @if($item->productVariation)
                                                        <small class="text-muted">
                                                            {{ $item->productVariation->attribute->name ?? '' }}: {{ $item->productVariation->attributeValue->value ?? '' }}
                                                        </small>
                                                    @endif
                                                    @if($item->product)
                                                        <div class="mt-1">
                                                            <small class="text-muted">SKU: {{ $item->product->SKU ?? 'N/A' }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                                        @if($order->hasTax())
                                            <td>Rs. {{ number_format($item->tax ?? 0, 2) }}</td>
                                        @endif
                                        @if($order->discount > 0)
                                            <td>Rs. {{ number_format($item->discount ?? 0, 2) }}</td>
                                        @endif
                                        <td class="text-end">Rs. {{ number_format(($item->price * $item->quantity) + ($item->tax ?? 0) - ($item->discount ?? 0), 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="row mb-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="align-middle" data-feather="calculator"></i> Order Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end">Rs. {{ number_format($order->subtotal ?? 0, 2) }}</td>
                                </tr>
                                @if(($order->shipping_fee ?? 0) > 0)
                                <tr>
                                    <td><strong>Shipping:</strong></td>
                                    <td class="text-end">Rs. {{ number_format($order->shipping_fee, 2) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td><strong>Shipping:</strong></td>
                                    <td class="text-end text-success">Free</td>
                                </tr>
                                @endif
                                @if($order->hasTax())
                                <tr>
                                    <td><strong>Tax ({{ $order->tax_percentage }}%):</strong></td>
                                    <td class="text-end">Rs. {{ number_format($order->tax, 2) }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td><strong>Tax:</strong></td>
                                    <td class="text-end">Rs. 0.00</td>
                                </tr>
                                @endif
                                @if(($order->discount ?? 0) > 0)
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-end text-success">-Rs. {{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif

                                @if($order->hasCoupon() && $couponData)
                                <tr>
                                    <td><strong>Coupon Applied:</strong></td>
                                    <td class="text-end">
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-primary me-2">{{ $couponData['code'] }}</span>
                                                @if($couponData['exists'])
                                                    <span class="badge {{ app(App\Services\SaasCouponService::class)->getStatusBadgeClass($couponData['status']) }}">
                                                        {{ app(App\Services\SaasCouponService::class)->getStatusDisplayText($couponData['status']) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">Coupon Deleted</span>
                                                @endif
                                            </div>
                                            <div class="text-muted small text-end">
                                                <div><strong>Discount:</strong> {{ $couponData['formatted_discount'] }}</div>
                                                <div><strong>Savings:</strong> {{ $couponData['formatted_savings'] }}</div>
                                                @if($couponData['exists'] && $couponData['description'])
                                                    <div class="mt-1"><em>{{ $couponData['description'] }}</em></div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @elseif($order->coupon_code)
                                <tr>
                                    <td colspan="2" class="text-muted small">
                                        <i class="fas fa-tag me-1"></i>Coupon: {{ $order->coupon_code }}
                                        <span class="badge bg-warning ms-2">Limited Info</span>
                                    </td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Grand Total:</strong></td>
                                    <td class="text-end"><strong>Rs. {{ number_format($order->total ?? $order->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->hasCoupon() && $couponData)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="align-middle" data-feather="tag"></i> Coupon Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Code:</th>
                                        <td>
                                            <span class="badge bg-primary">{{ $couponData['code'] }}</span>
                                            @if($couponData['exists'])
                                                <span class="badge {{ app(App\Services\SaasCouponService::class)->getStatusBadgeClass($couponData['status']) }} ms-2">
                                                    {{ app(App\Services\SaasCouponService::class)->getStatusDisplayText($couponData['status']) }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning ms-2">Coupon Deleted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($couponData['exists'] && $couponData['description'])
                                    <tr>
                                        <th>Description:</th>
                                        <td>{{ $couponData['description'] }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Discount:</th>
                                        <td>
                                            @if($couponData['exists'])
                                                {{ $couponData['formatted_original_discount'] }}
                                            @else
                                                {{ $couponData['formatted_discount'] }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Applied Amount:</th>
                                        <td class="text-success"><strong>{{ $couponData['formatted_savings'] }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($couponData['exists'])
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="120">Valid Period:</th>
                                        <td>{{ $couponData['start_date']->format('M d, Y') }} - {{ $couponData['end_date']->format('M d, Y') }}</td>
                                    </tr>
                                    @if($couponData['usage_limit'])
                                    <tr>
                                        <th>Usage:</th>
                                        <td>
                                            {{ $couponData['used_count'] }} / {{ $couponData['usage_limit'] }} used
                                            @php
                                                $usageStats = app(App\Services\SaasCouponService::class)->getCouponUsageStats(App\Models\SaasCoupon::where('code', $couponData['code'])->first());
                                            @endphp
                                            <div class="progress mt-1" style="height: 4px;">
                                                <div class="progress-bar" style="width: {{ $usageStats['usage_percentage'] }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($couponData['seller'])
                                    <tr>
                                        <th>Seller:</th>
                                        <td>
                                            <a href="{{ route('admin.users.show', $couponData['seller']->id) }}" class="text-decoration-none">
                                                {{ $couponData['seller']->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                                @endif
                            </div>
                        </div>

                        @if($couponData['exists'])
                        <div class="mt-3">
                            <a href="{{ route('admin.coupons.show', App\Models\SaasCoupon::where('code', $couponData['code'])->first()->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="align-middle" data-feather="eye"></i> View Coupon Details
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($order->order_notes)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="align-middle" data-feather="message-square"></i> Order Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->order_notes }}</p>
                    </div>
                </div>
            @endif

            @if($order->admin_note)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="align-middle" data-feather="user-check"></i> Admin Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $order->admin_note }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, nav, .sidebar, .breadcrumb {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    body {
        font-size: 12px;
    }
}
</style>

<script>
function printOrder() {
    window.print();
}
</script>
@endsection
