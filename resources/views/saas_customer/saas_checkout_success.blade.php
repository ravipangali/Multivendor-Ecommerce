@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<!-- Success Banner -->
<div class="container-fluid" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); padding: 3rem 0; margin-bottom: 2rem;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--white);"></i>
                </div>
                <h1 class="text-white mb-3">Order Placed Successfully!</h1>
                <p class="lead text-white mb-4">Thank you for your order. We've received your payment and will process your order shortly.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-user me-2"></i>View My Orders
                    </a>
                    <a href="{{ route('customer.home') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-home me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details -->
<div class="container mb-5">
    @if(session('created_orders'))
        @php
            $orderIds = session('created_orders');
            $orders = \App\Models\SaasOrder::whereIn('id', $orderIds)->with(['items.product.images', 'items.productVariation', 'customer', 'seller'])->get();
        @endphp

        @foreach($orders as $order)
        <div class="card shadow-lg border-0 mb-4" style="border-radius: var(--radius-lg);">
            <!-- Order Header -->
            <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light)); color: var(--white); border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-1">
                            <i class="fas fa-receipt me-2"></i>Order #{{ $order->order_number }}
                        </h4>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('F d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <span class="badge badge-light px-3 py-2" style="background: var(--white); color: var(--secondary-color); font-size: 0.9rem;">
                            <i class="fas fa-clock me-1"></i>{{ ucfirst($order->order_status) }}
                        </span>
                        <div class="mt-2">
                            <button class="btn btn-outline-light btn-sm" onclick="printInvoice('invoice-{{ $order->id }}')">
                                <i class="fas fa-print me-1"></i>Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Content -->
            <div id="invoice-{{ $order->id }}" class="card-body p-4">
                <!-- Seller Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="seller-info p-3" style="background: var(--accent-color); border-radius: var(--radius-md);">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-store me-2"></i>Sold By
                            </h6>
                            <h5 class="mb-1">{{ $order->seller->name ?? 'AllSewa Store' }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-1"></i>{{ $order->seller->email ?? 'store@allsewa.com' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="customer-info p-3" style="background: var(--accent-color); border-radius: var(--radius-md);">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-user me-2"></i>Customer Details
                            </h6>
                            <h5 class="mb-1">{{ $order->customer->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-1"></i>{{ $order->customer->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Payment Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="address-card p-3 border" style="border-radius: var(--radius-md);">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shipping-fast me-2"></i>Shipping Address
                            </h6>
                            @if($order->shipping_name)
                                <div class="address-details">
                                    <div class="fw-bold mb-1">{{ $order->shipping_name }}</div>
                                    @if($order->shipping_email)
                                        <div class="text-muted mb-1">
                                            <i class="fas fa-envelope me-1"></i>{{ $order->shipping_email }}
                                        </div>
                                    @endif
                                    @if($order->shipping_phone)
                                        <div class="text-muted mb-2">
                                            <i class="fas fa-phone me-1"></i>{{ $order->shipping_phone }}
                                        </div>
                                    @endif
                                    <div class="address-text">
                                        @if($order->shipping_street_address){{ $order->shipping_street_address }}<br>@endif
                                        @if($order->shipping_city || $order->shipping_state || $order->shipping_postal_code)
                                            {{ $order->shipping_city }}@if($order->shipping_city && $order->shipping_state), @endif{{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                        @endif
                                        @if($order->shipping_country){{ $order->shipping_country }}@endif
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No shipping address provided</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="payment-card p-3 border" style="border-radius: var(--radius-md);">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-credit-card me-2"></i>Payment Information
                            </h6>
                            <div class="payment-details">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Method:</span>
                                    <span class="fw-bold">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Status:</span>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Order Status:</span>
                                    <span class="badge
                                        @if($order->order_status === 'pending') bg-warning
                                        @elseif($order->order_status === 'processing') bg-info
                                        @elseif($order->order_status === 'shipped') bg-primary
                                        @elseif($order->order_status === 'delivered') bg-success
                                        @elseif($order->order_status === 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-section mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-shopping-bag me-2"></i>Items Ordered
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background: var(--accent-color);">
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Qty</th>
                                    <th width="120">Price</th>
                                    <th width="120">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-image me-3">
                                                @if($item->product && $item->product->images && $item->product->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                                         alt="{{ $item->product->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);">
                                                @else
                                                    <div style="width: 60px; height: 60px; background: var(--border-light); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-box text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="product-details">
                                                <h6 class="mb-1">{{ $item->product->name ?? 'Product Name' }}</h6>
                                                @if($item->productVariation)
                                                    <small class="text-muted">
                                                        {{ $item->productVariation->attribute->name ?? '' }}: {{ $item->productVariation->attributeValue->value ?? '' }}
                                                    </small>
                                                @endif
                                                @if($item->product && $item->product->SKU)
                                                    <div><small class="text-muted">SKU: {{ $item->product->SKU }}</small></div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rs. {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end fw-bold">Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="order-summary p-3" style="background: var(--accent-color); border-radius: var(--radius-md);">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-calculator me-2"></i>Order Summary
                            </h6>
                            <div class="summary-table">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                                </div>

                                @if($order->shipping_fee > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>Rs. {{ number_format($order->shipping_fee, 2) }}</span>
                                </div>
                                @else
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span class="text-success">Free</span>
                                </div>
                                @endif

                                @if($order->tax > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>Rs. {{ number_format($order->tax, 2) }}</span>
                                </div>
                                @endif

                                @if($order->hasCoupon())
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>
                                        Coupon Discount
                                        <small class="text-muted">({{ $order->coupon_code }})</small>
                                    </span>
                                    <span>-Rs. {{ number_format($order->coupon_discount_amount, 2) }}</span>
                                </div>
                                @endif

                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span style="color: var(--primary-color);">Rs. {{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($order->order_notes)
                <div class="order-notes mt-4 p-3 border" style="border-radius: var(--radius-md);">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-sticky-note me-2"></i>Order Notes
                    </h6>
                    <p class="mb-0">{{ $order->order_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Order Footer -->
            <div class="card-footer text-center" style="background: var(--accent-color); border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-start">
                        <p class="mb-0 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            You will receive email updates about your order status
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-eye me-1"></i>View Details
                        </a>
                        <button class="btn btn-outline-secondary" onclick="printInvoice('invoice-{{ $order->id }}')">
                            <i class="fas fa-download me-1"></i>Download Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No order information found. Please check your order history.
        </div>
    @endif
</div>

<!-- Next Steps -->
<div class="container mb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="next-steps-card p-4 text-center" style="background: linear-gradient(135deg, var(--accent-color), #f8fafc); border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
                <h4 class="mb-3" style="color: var(--text-dark);">What's Next?</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <i class="fas fa-box-open" style="font-size: 2rem; color: var(--primary-color);"></i>
                            </div>
                            <h6>Order Processing</h6>
                            <p class="text-muted small">Your order is being prepared by our sellers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <i class="fas fa-truck" style="font-size: 2rem; color: var(--secondary-color);"></i>
                            </div>
                            <h6>Shipping</h6>
                            <p class="text-muted small">Track your package when it's shipped</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <i class="fas fa-star" style="font-size: 2rem; color: var(--warning);"></i>
                            </div>
                            <h6>Review</h6>
                            <p class="text-muted small">Share your experience after delivery</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }

    .invoice-content, .invoice-content * {
        visibility: visible;
    }

    .invoice-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .btn, .no-print {
        display: none !important;
    }

    .card-header, .card-footer {
        background: white !important;
        color: black !important;
    }

    .badge {
        border: 1px solid #ccc !important;
        background: white !important;
        color: black !important;
    }
}

.step-card {
    transition: transform 0.3s ease;
}

.step-card:hover {
    transform: translateY(-5px);
}

.order-summary .summary-table > div {
    padding: 0.25rem 0;
}

.address-card, .payment-card {
    transition: box-shadow 0.3s ease;
}

.address-card:hover, .payment-card:hover {
    box-shadow: var(--shadow-md);
}

.product-image img {
    transition: transform 0.3s ease;
}

.product-image img:hover {
    transform: scale(1.05);
}
</style>

<script>
function printInvoice(elementId) {
    const printContent = document.getElementById(elementId);
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = '<div class="invoice-content">' + printContent.innerHTML + '</div>';
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>
@endsection
