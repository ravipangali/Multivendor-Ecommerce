@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .order-detail-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .breadcrumb-modern {
    background: linear-gradient(135deg, var(--white), var(--accent-color));
    padding: 1.5rem 0;
    margin-bottom: 0;
    border-bottom: 1px solid var(--border-light);
  }

  .breadcrumb-modern .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
  }

  .breadcrumb-modern .breadcrumb-item a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .breadcrumb-modern .breadcrumb-item a:hover {
    color: var(--primary-color);
  }

  .breadcrumb-modern .breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
  }

  .order-header-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
  }

  .order-header-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .order-header-section h4 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
  }

  .order-status {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: var(--shadow-sm);
  }

  .badge-success {
    background: linear-gradient(135deg, var(--success), #27ae60);
    color: var(--white);
  }

  .badge-warning {
    background: linear-gradient(135deg, var(--warning), #f39c12);
    color: var(--white);
  }

  .badge-danger {
    background: linear-gradient(135deg, var(--danger), #e74c3c);
    color: var(--white);
  }

  .order-total h5 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
  }

  .cancel-order {
    background: linear-gradient(135deg, var(--danger), #dc3545);
    border: none;
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .cancel-order:hover {
    background: linear-gradient(135deg, #c82333, #a71e2a);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: var(--white);
  }

  .order-progress-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .progress-card h6 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    position: relative;
  }

  .progress-card h6::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .order-progress {
    display: flex;
    justify-content: space-between;
    position: relative;
  }

  .order-progress::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 25px;
    right: 25px;
    height: 2px;
    background: var(--border-light);
    z-index: 1;
  }

  .progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 2;
    background: var(--white);
    padding: 0 1rem;
    min-width: 120px;
  }

  .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    margin-bottom: 0.75rem;
    font-size: 1.125rem;
    transition: all 0.3s ease;
  }

  .progress-step.completed .step-icon {
    background: linear-gradient(135deg, var(--success), #27ae60);
    box-shadow: var(--shadow-md);
  }

  .step-content h6 {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
  }

  .step-content p {
    color: var(--text-medium);
    font-size: 0.75rem;
    margin: 0;
  }

  .order-items-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .items-card h6 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    position: relative;
  }

  .items-card h6::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .item-row {
    padding: 1.5rem;
    background: var(--accent-color);
    border-radius: var(--radius-md);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    border: 1px solid transparent;
  }

  .item-row:hover {
    background: rgba(171, 207, 55, 0.15);
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
  }

  .item-image img {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-md);
    object-fit: cover;
    box-shadow: var(--shadow-sm);
  }

  .item-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1rem;
  }

  .item-variation {
    color: var(--text-medium);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
  }

  .item-seller {
    color: var(--text-light);
    font-size: 0.75rem;
  }

  .item-price span {
    font-weight: 600;
    color: var(--secondary-color);
    font-size: 1rem;
  }

  .item-quantity span {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 0.875rem;
  }

  .item-total strong {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.125rem;
  }

  .write-review {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .write-review:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
    color: var(--white);
  }

  .info-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
  }

  .info-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .info-card h6 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    position: relative;
  }

  .info-card h6::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .shipping-address {
    background: var(--accent-color);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    line-height: 1.6;
    color: var(--text-dark);
  }

  .payment-details {
    background: var(--accent-color);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
  }

  .payment-method {
    margin-bottom: 1rem;
  }

  .payment-status .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
  }

  .order-summary-details {
    background: var(--accent-color);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-light);
  }

  .summary-row:last-child {
    border-bottom: none;
    font-weight: 600;
    font-size: 1.125rem;
    padding-top: 1rem;
    margin-top: 0.5rem;
    border-top: 2px solid var(--primary-color);
  }

  @media (max-width: 768px) {
    .order-detail-container {
      padding: 1rem 0;
    }

    .order-header-section {
      padding: 1.5rem;
    }

    .order-progress {
      flex-direction: column;
      gap: 1rem;
    }

    .order-progress::before {
      display: none;
    }

    .progress-step {
      flex-direction: row;
      text-align: left;
      min-width: auto;
    }

    .step-icon {
      margin-right: 1rem;
      margin-bottom: 0;
    }

    .item-row .row {
      text-align: center;
    }

    .item-row .col-md-2,
    .item-row .col-md-5,
    .item-row .col-md-1 {
      margin-bottom: 1rem;
    }
  }

  .back-button {
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    color: var(--white);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
  }

  .back-button:hover {
    background: linear-gradient(135deg, var(--secondary-dark), #075985);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--white);
    text-decoration: none;
  }

  .order-actions-bottom {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    text-align: center;
    margin-top: 2rem;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    color: var(--white);
    padding: 0.75rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin: 0 0.5rem;
  }

  .btn-primary-custom:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: var(--white);
    text-decoration: none;
  }

  /* Digital Download Styles */
  .digital-download-section {
    margin-top: 8px;
  }

  .digital-download-section .btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }

  .digital-products-section {
    margin: 2rem 0;
  }

  .digital-products-section .card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }

  .digital-products-section .card-header {
    border-bottom: none;
  }

  .digital-products-section .btn {
    font-size: 0.875rem;
  }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-modern">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">My Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Order Detail Container -->
<section class="order-detail-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('customer.orders') }}" class="back-button">
            <i class="fa fa-arrow-left"></i>
            Back to My Orders
        </a>

        <div class="row">
            <!-- Order Information -->
            <div class="col-lg-8">
                <!-- Order Header -->
                <div class="order-header-section">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4>Order #{{ $order->order_number }}</h4>
                            <p class="text-muted mb-3">
                                <i class="fa fa-calendar me-2"></i>
                                Placed on {{ $order->created_at->format('M d, Y h:i A') }}
                            </p>
                            <span class="order-status badge-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'cancelled' ? 'danger' : 'warning') }}">
                                @switch($order->order_status)
                                    @case('pending')
                                        <i class="fa fa-clock-o me-1"></i>
                                        @break
                                    @case('processing')
                                        <i class="fa fa-cog me-1"></i>
                                        @break
                                    @case('shipped')
                                        <i class="fa fa-truck me-1"></i>
                                        @break
                                    @case('delivered')
                                        <i class="fa fa-check-circle me-1"></i>
                                        @break
                                    @case('cancelled')
                                        <i class="fa fa-times-circle me-1"></i>
                                        @break
                                @endswitch
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="order-total">
                                <h5>
                                    <i class="fa fa-money me-2"></i>
                                    Total: Rs. {{ number_format($order->total, 2) }}
                                </h5>
                                @if($order->order_status == 'pending')
                                    <button class="cancel-order" data-order-id="{{ $order->id }}">
                                        <i class="fa fa-times me-1"></i>
                                        Cancel Order
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Progress -->
                <div class="order-progress-section">
                    <div class="progress-card">
                        <h6>
                            <i class="fa fa-tasks me-2 text-primary"></i>
                            Order Progress
                        </h6>
                        <div class="order-progress">
                            <div class="progress-step {{ in_array($order->order_status, ['pending', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Order Placed</h6>
                                    <p>{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <div class="progress-step {{ in_array($order->order_status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="fa fa-cog"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Processing</h6>
                                    <p>{{ $order->order_status == 'processing' ? 'Currently processing' : ($order->order_status == 'pending' ? 'Waiting to process' : 'Processed') }}</p>
                                </div>
                            </div>

                            <div class="progress-step {{ in_array($order->order_status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="fa fa-truck"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Shipped</h6>
                                    <p>{{ $order->order_status == 'shipped' ? 'On the way' : ($order->order_status == 'delivered' ? 'Shipped' : 'Not shipped yet') }}</p>
                                </div>
                            </div>

                            <div class="progress-step {{ $order->order_status == 'delivered' ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Delivered</h6>
                                    <p>{{ $order->order_status == 'delivered' ? 'Order delivered' : 'Not delivered yet' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-section">
                    <div class="items-card">
                        <h6>
                            <i class="fa fa-list me-2 text-primary"></i>
                            Order Items ({{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }})
                        </h6>

                        <div class="items-list">
                            @foreach($order->items as $item)
                                <div class="item-row">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="item-image">
                                                @if($item->product && $item->product->images->count() > 0)
                                                    <img src="{{ $item->product->images->first()->image_url }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="img-fluid rounded">
                                                @else
                                                    <img src="{{ asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="img-fluid rounded">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="item-info">
                                                <h6 class="item-name">{{ $item->product->name }}</h6>
                                                @if($item->variation)
                                                    <p class="item-variation">
                                                        <i class="fa fa-tag me-1"></i>
                                                        {{ $item->variation->attribute->name }}: {{ $item->variation->attributeValue->value }}
                                                    </p>
                                                @endif
                                                <p class="item-seller">
                                                    <i class="fa fa-store me-1"></i>
                                                    Sold by: {{ $item->seller->name ?? 'Store' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="item-price">
                                                <span>Rs. {{ number_format($item->price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <div class="item-quantity">
                                                <span>{{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="item-total">
                                                <strong>Rs. {{ number_format($item->total, 2) }}</strong>
                                            </div>

                                            <!-- Digital Product Download Section -->
                                            @if($item->product->product_type == 'Digital' && $order->canDownloadDigitalProducts() && $item->product->file)
                                                <div class="digital-download-section mt-2">
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('customer.digital-product.download', ['orderId' => $order->id, 'productId' => $item->product->id]) }}"
                                                           class="btn btn-sm btn-success">
                                                            <i class="fa fa-download me-1"></i>
                                                        </a>
                                                        <a href="{{ route('customer.digital-product.preview', ['orderId' => $order->id, 'productId' => $item->product->id]) }}"
                                                           class="btn btn-sm btn-outline-primary"
                                                           target="_blank">
                                                            <i class="fa fa-eye me-1"></i>
                                                        </a>
                                                    </div>
                                                    <small class="text-success d-block mt-1">
                                                        <i class="fa fa-check-circle me-1"></i>Ready for download
                                                    </small>
                                                </div>
                                            @elseif($item->product->product_type == 'Digital' && !$order->canDownloadDigitalProducts())
                                                <div class="digital-download-section mt-2">
                                                    <small class="text-muted d-block">
                                                        <i class="fa fa-clock me-1"></i>
                                                        @if($order->order_status != 'delivered')
                                                            Download available after delivery
                                                        @elseif($order->payment_status != 'paid')
                                                            Download available after payment confirmation
                                                        @else
                                                            Download not available
                                                        @endif
                                                    </small>
                                                </div>
                                            @elseif($item->product->product_type == 'Digital' && !$item->product->file)
                                                <div class="digital-download-section mt-2">
                                                    <small class="text-warning d-block">
                                                        <i class="fa fa-exclamation-triangle me-1"></i>Digital file not available
                                                    </small>
                                                </div>
                                            @endif

                                            @if($order->order_status == 'delivered')
                                                <div class="review-action mt-2">
                                                    @php
                                                        $existingReview = $item->product->reviews->where('customer_id', auth()->id())->first();
                                                    @endphp
                                                    @if($existingReview)
                                                        <small class="text-success">
                                                            <i class="fa fa-star me-1"></i>Reviewed
                                                        </small>
                                                    @else
                                                        <button class="write-review"
                                                                data-product-id="{{ $item->product->id }}"
                                                                data-product-name="{{ $item->product->name }}">
                                                            <i class="fa fa-star me-1"></i>
                                                            Write Review
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Digital Products Download Section -->
                @if($order->hasDigitalProducts() && $order->canDownloadDigitalProducts())
                    <div class="digital-products-section" id="digital-downloads">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fa fa-download me-2"></i>
                                    Digital Products Ready for Download
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-success mb-3">
                                    <i class="fa fa-check-circle me-1"></i>
                                    Your order has been delivered and payment confirmed. You can now download your digital products.
                                </p>
                                <div class="row">
                                    @foreach($order->getDownloadableDigitalProducts() as $product)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-light">
                                                <div class="card-body p-3">
                                                    <h6 class="card-title">{{ $product->name }}</h6>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('customer.digital-product.download', ['orderId' => $order->id, 'productId' => $product->id]) }}"
                                                           class="btn btn-success btn-sm">
                                                            <i class="fa fa-download me-1"></i>Download
                                                        </a>
                                                        <a href="{{ route('customer.digital-product.preview', ['orderId' => $order->id, 'productId' => $product->id]) }}"
                                                           class="btn btn-outline-primary btn-sm"
                                                           target="_blank">
                                                            <i class="fa fa-eye me-1"></i>Preview
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($order->hasDigitalProducts() && !$order->canDownloadDigitalProducts())
                    <div class="digital-products-section">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fa fa-clock me-2"></i>
                                    Digital Products Pending
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">
                                    <i class="fa fa-info-circle me-1"></i>
                                    @if($order->order_status != 'delivered')
                                        Your digital products will be available for download once your order is delivered.
                                    @elseif($order->payment_status != 'paid')
                                        Your digital products will be available for download once payment is confirmed.
                                    @else
                                        Digital products are not available for download at this time.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($order->order_status == 'delivered')
                    <!-- Order Actions -->
                    <div class="order-actions-bottom">
                        <h6 class="mb-3">Order Actions</h6>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('customer.orders') }}" class="btn-primary-custom">
                                <i class="fa fa-list"></i>
                                View All Orders
                            </a>
                            <a href="{{ route('customer.products') }}" class="btn-primary-custom">
                                <i class="fa fa-shopping-cart"></i>
                                Shop Again
                            </a>
                            @if($order->items->where('product.reviews.customer_id', '!=', auth()->id())->count() > 0)
                                <button class="btn-primary-custom" onclick="showReviewModal()">
                                    <i class="fa fa-star"></i>
                                    Rate Products
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <!-- Shipping Information -->
                <div class="info-card">
                    <h6>
                        <i class="fa fa-truck me-2 text-primary"></i>
                        Shipping Information
                    </h6>
                    <div class="shipping-address">
                        @if($order->shipping_name)<strong>{{ $order->shipping_name }}</strong><br>@endif
                        @if($order->shipping_street_address){{ $order->shipping_street_address }}<br>@endif
                        @if($order->shipping_city || $order->shipping_state || $order->shipping_postal_code)
                            {{ $order->shipping_city }}@if($order->shipping_city && $order->shipping_state), @endif{{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                        @endif
                        @if($order->shipping_country){{ $order->shipping_country }}<br>@endif
                        @if($order->shipping_phone || $order->shipping_email)<br>@endif
                        @if($order->shipping_phone)<strong><i class="fa fa-phone me-1"></i> Phone:</strong> {{ $order->shipping_phone }}<br>@endif
                        @if($order->shipping_email)<strong><i class="fa fa-envelope me-1"></i> Email:</strong> {{ $order->shipping_email }}@endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="info-card">
                    <h6>
                        <i class="fa fa-credit-card me-2 text-primary"></i>
                        Payment Information
                    </h6>
                    <div class="payment-details">
                        <div class="payment-method">
                            <strong>Payment Method:</strong><br>
                            <i class="fa fa-money me-1"></i>
                            {{ $order->paymentMethod->name ?? 'N/A' }}
                        </div>
                        <div class="payment-status">
                            <strong>Payment Status:</strong><br>
                            <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                <i class="fa fa-{{ $order->payment_status == 'paid' ? 'check-circle' : 'clock-o' }} me-1"></i>
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="info-card">
                    <h6>
                        <i class="fa fa-calculator me-2 text-primary"></i>
                        Order Summary
                    </h6>
                    <div class="order-summary-details">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>
                                @if($order->shipping_cost > 0)
                                    Rs. {{ number_format($order->shipping_cost, 2) }}
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                            </span>
                        </div>
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>Rs. {{ number_format($order->tax, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="summary-row">
                                <span>Discount:</span>
                                <span class="text-success">-Rs. {{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row">
                            <span><strong>Total:</strong></span>
                            <span><strong>Rs. {{ number_format($order->total, 2) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle order cancellation
    const cancelButton = document.querySelector('.cancel-order');
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');

            Swal.fire({
                title: 'Cancel Order?',
                text: 'Are you sure you want to cancel this order? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Cancel Order',
                cancelButtonText: 'Keep Order'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Cancelling...';
                    this.disabled = true;

                    // Make the API call to cancel order
                    fetch(`/customer/order/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            cancellation_reason: 'Cancelled by customer from order detail page'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the status badge
                            const statusBadge = document.querySelector('.order-status');
                            statusBadge.className = 'order-status badge-danger';
                            statusBadge.innerHTML = '<i class="fa fa-times-circle me-1"></i> Cancelled';

                            // Remove the cancel button
                            this.remove();

                            // Update progress indicators
                            const progressSteps = document.querySelectorAll('.progress-step');
                            progressSteps.forEach(step => {
                                step.classList.remove('completed');
                            });

                            Swal.fire({
                                title: 'Order Cancelled!',
                                text: 'Your order has been cancelled successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#abcf37'
                            }).then(() => {
                                window.location.href = '{{ route('customer.orders') }}?cancelled=1';
                            });
                        } else {
                            this.innerHTML = originalText;
                            this.disabled = false;
                            Swal.fire({
                                title: 'Cancellation Failed',
                                text: data.message || 'Failed to cancel order. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#abcf37'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.innerHTML = originalText;
                        this.disabled = false;
                        Swal.fire({
                            title: 'Error',
                            text: 'An error occurred while cancelling the order. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#abcf37'
                        });
                    });
                }
            });
        });
    }

    // Handle write review buttons
    const reviewButtons = document.querySelectorAll('.write-review');
    reviewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            showReviewModal(productId, productName);
        });
    });

    // Smooth animations for sections
    const sections = document.querySelectorAll('.order-header-section, .order-progress-section, .order-items-section, .info-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(section);
    });

    // Enhanced hover effects for order items
    const orderItems = document.querySelectorAll('.item-row');
    orderItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease;';
        notification.innerHTML = `
            <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        `;

        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    // Review modal function (placeholder)
    function showReviewModal(productId = null, productName = null) {
        // This would open a review modal
        // For now, just show a placeholder message
        if (productId) {
            showNotification(`Review functionality for "${productName}" would open here`, 'info');
        } else {
            showNotification('Bulk review functionality would open here', 'info');
        }
    }

    // Make review modal function globally available
    window.showReviewModal = showReviewModal;

    // Handle successful actions from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('cancelled')) {
        showNotification('Order cancelled successfully!', 'success');
    }
});
</script>
@endpush
@endsection
