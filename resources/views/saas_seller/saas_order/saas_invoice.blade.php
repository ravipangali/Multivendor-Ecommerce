@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Order Invoice - ' . $order->order_number)

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Invoice - {{ $order->order_number }}</h5>
            <div>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="align-middle" data-feather="printer"></i> Print Invoice
                </button>
                <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Order
                </a>
            </div>
        </div>
        <div class="card-body" id="invoice-content">
            <!-- Invoice Header -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="text-primary">INVOICE</h2>
                    <p class="mb-1"><strong>Invoice Number:</strong> {{ $order->order_number }}</p>
                    <p class="mb-1"><strong>Invoice Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                    <p class="mb-1"><strong>Order Date:</strong> {{ $order->placed_at ? \Carbon\Carbon::parse($order->placed_at)->format('F d, Y') : $order->created_at->format('F d, Y') }}</p>
                    <p class="mb-1"><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="text-primary">{{ Auth::user()->name }}'s Store</h4>
                    @if(Auth::user()->sellerProfile && Auth::user()->sellerProfile->store_name)
                        <p class="mb-1">{{ Auth::user()->sellerProfile->store_name }}</p>
                    @endif
                    <p class="mb-1">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->sellerProfile)
                        @if(Auth::user()->sellerProfile->store_phone)
                            <p class="mb-1">{{ Auth::user()->sellerProfile->store_phone }}</p>
                        @endif
                        @if(Auth::user()->sellerProfile->store_address)
                            <p class="mb-1">{{ Auth::user()->sellerProfile->store_address }}</p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary">Bill To:</h5>
                    <div class="border p-3 rounded">
                        <p class="mb-1"><strong>{{ $order->billing_name }}</strong></p>
                        <p class="mb-1">{{ $order->billing_email }}</p>
                        <p class="mb-1">{{ $order->billing_phone }}</p>
                        <p class="mb-1">{{ $order->billing_street_address }}</p>
                        <p class="mb-1">{{ $order->billing_city }}, {{ $order->billing_state }}</p>
                        <p class="mb-1">{{ $order->billing_postal_code }}</p>
                        <p class="mb-0">{{ $order->billing_country }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary">Ship To:</h5>
                    <div class="border p-3 rounded">
                        <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_email }}</p>
                        <p class="mb-1">{{ $order->shipping_phone }}</p>
                        <p class="mb-1">{{ $order->shipping_street_address }}</p>
                        <p class="mb-1">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                        <p class="mb-1">{{ $order->shipping_postal_code }}</p>
                        <p class="mb-0">{{ $order->shipping_country }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'info') }}">
                        <strong>Order Status:</strong>
                        <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'primary') }}">
                            {{ ucwords($order->order_status) }}
                        </span>
                        <strong class="ms-3">Payment Status:</strong>
                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucwords($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->product_variation_id)
                                            <br><small class="text-muted">Variation: {{ $item->productVariation->attribute->name ?? 'N/A' }} - {{ $item->productVariation->attributeValue->value ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->product->SKU }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rs {{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">Rs {{ number_format($item->quantity * $item->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="row">
                <div class="col-md-6">
                    @if($order->order_notes)
                    <h6 class="text-primary">Order Notes:</h6>
                    <div class="border p-3 rounded bg-light">
                        {{ $order->order_notes }}
                    </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Order Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rs {{ number_format($order->subtotal, 2) }}</span>
                            </div>

                            @if($order->discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span>-Rs {{ number_format($order->discount, 2) }}</span>
                            </div>
                            @endif

                            @if($order->coupon_code)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Coupon ({{ $order->coupon_code }}):</span>
                                <span>-Rs {{ number_format($order->coupon_discount_amount, 2) }}</span>
                            </div>
                            @endif

                            @if($order->tax > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>Rs {{ number_format($order->tax, 2) }}</span>
                            </div>
                            @endif

                            @if($order->shipping_fee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Rs {{ number_format($order->shipping_fee, 2) }}</span>
                            </div>
                            @endif

                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Amount:</strong>
                                <strong class="text-primary">Rs {{ number_format($order->total, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-5">
                <div class="col-12">
                    <hr>
                    <div class="text-center text-muted">
                        <p class="mb-1">Thank you for your business!</p>
                        <p class="mb-0">This is a computer-generated invoice. No signature required.</p>
                        <small>Generated on {{ now()->format('F d, Y h:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .card-header,
        .btn,
        .sidebar,
        .navbar,
        .footer {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .card-body {
            padding: 0 !important;
        }

        body {
            background: white !important;
        }

        .container-fluid {
            padding: 0 !important;
        }

        .col-12 {
            padding: 0 !important;
        }

        #invoice-content {
            margin: 0 !important;
            padding: 20px !important;
        }

        .table {
            border-collapse: collapse !important;
        }

        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }

        .table-bordered {
            border: 2px solid #000 !important;
        }

        .text-primary {
            color: #000 !important;
        }

        .bg-primary {
            background-color: #f8f9fa !important;
            color: #000 !important;
        }

        .alert {
            border: 1px solid #000 !important;
            background-color: #f8f9fa !important;
        }

        .badge {
            border: 1px solid #000 !important;
            background-color: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Auto-focus for better printing experience
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
