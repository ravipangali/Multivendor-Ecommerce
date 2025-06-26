@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .checkout-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .checkout-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }

  .checkout-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
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

  .checkout-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .checkout-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .checkout-section:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
    border-color: var(--primary-color);
  }

  .checkout-section:hover::before {
    opacity: 1;
  }

  .checkout-section h4 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    position: relative;
  }

  .checkout-section h4::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
  }

  .form-control {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: var(--white);
  }

  .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .form-select {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: var(--white);
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
  }

  .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .form-select option {
    padding: 0.5rem;
    background: var(--white);
    color: var(--text-dark);
  }

  .payment-methods {
    margin-top: 1rem;
  }

  .payment-method {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 1rem;
    margin-bottom: 1rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .payment-method:hover {
    border-color: var(--primary-color);
    background: rgba(171, 207, 55, 0.1);
  }

  .payment-method input:checked + .payment-label {
    color: var(--primary-color);
    font-weight: 600;
  }

  .payment-method:has(input:checked) {
    border-color: var(--primary-color);
    background: rgba(171, 207, 55, 0.1);
    box-shadow: var(--shadow-sm);
  }

  .form-check-input {
    border: 2px solid var(--border-medium);
    border-radius: 50%;
    transition: all 0.2s ease;
  }

  .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
  }

  #billing_fields {
    margin-top: 1rem;
    padding: 1.5rem;
    background: rgba(171, 207, 55, 0.05);
    border-radius: var(--radius-md);
    border: 1px solid rgba(171, 207, 55, 0.2);
  }

  .billing-field:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
  }

  .order-summary-section {
    position: sticky;
    top: 2rem;
  }

  .order-summary-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
  }

  .order-summary-card h4 {
    font-family: var(--font-display);
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .order-item {
    padding: 1rem;
    background: var(--accent-color);
    border-radius: var(--radius-md);
    margin-bottom: 1rem;
    transition: all 0.2s ease;
  }

  .order-item:hover {
    background: rgba(171, 207, 55, 0.15);
    transform: translateX(5px);
  }

  .item-info h6 {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
  }

  .item-quantity small {
    color: var(--text-medium);
    font-size: 0.75rem;
  }

  .item-price span {
    font-weight: 600;
    color: var(--secondary-color);
    font-size: 0.875rem;
  }

  .summary-line {
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: var(--text-medium);
  }

  .summary-line.total-line {
    border-top: 2px solid var(--accent-color);
    margin-top: 1rem;
    padding-top: 1rem;
    font-size: 1.125rem;
    color: var(--text-dark);
  }

  .place-order-section .btn {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    color: var(--white);
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
  }

  .place-order-section .btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: var(--white);
  }

  .place-order-section .btn:active {
    transform: translateY(0);
  }

  .security-badges {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
  }

  .security-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-medium);
    font-size: 0.75rem;
  }

  .security-badge i {
    color: var(--success);
  }

  @media (max-width: 768px) {
    .checkout-container {
      padding: 1rem 0;
    }

    .checkout-header {
      padding: 1.5rem;
      text-align: center;
    }

    .checkout-section {
      padding: 1.5rem;
    }

    .order-summary-section {
      position: static;
      margin-top: 2rem;
    }

    .order-summary-card {
      padding: 1.5rem;
    }
  }

  .checkout-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .progress-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--white);
    border-radius: var(--radius-md);
    border: 2px solid var(--border-light);
    color: var(--text-medium);
    font-size: 0.875rem;
    font-weight: 500;
  }

  .progress-step.active {
    border-color: var(--primary-color);
    background: rgba(171, 207, 55, 0.1);
    color: var(--primary-color);
  }

  .progress-step.completed {
    border-color: var(--success);
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
  }

  .progress-connector {
    width: 30px;
    height: 2px;
    background: var(--border-light);
  }

  .progress-connector.active {
    background: var(--success);
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
                        <li class="breadcrumb-item"><a href="{{ route('customer.cart') }}">Cart</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Checkout Container -->
<section class="checkout-container">
    <div class="container">
        <!-- Checkout Header -->
        <div class="checkout-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">Secure Checkout</h2>
                    <p class="mb-0 opacity-75">
                        Complete your order safely and securely
                    </p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                        <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                            <i class="fa fa-shopping-cart me-1"></i>
                            {{ count($cartItems) }} {{ Str::plural('Item', count($cartItems)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Progress -->
        <div class="checkout-progress">
            <div class="progress-step completed">
                <i class="fa fa-shopping-cart"></i>
                <span>Cart</span>
            </div>
            <div class="progress-connector active"></div>
            <div class="progress-step active">
                <i class="fa fa-credit-card"></i>
                <span>Checkout</span>
            </div>
            <div class="progress-connector"></div>
            <div class="progress-step">
                <i class="fa fa-check-circle"></i>
                <span>Confirmation</span>
            </div>
        </div>

        <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkoutForm" autocomplete="on">
            @csrf

            <!-- Display validation errors and debugging info -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4" style="border-left: 4px solid #dc3545;">
                    <h5><i class="fa fa-exclamation-triangle me-2"></i>Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success mb-4">
                    <strong>Success:</strong> {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <!-- Shipping Information -->
                    <div class="checkout-section">
                        <h4>
                            <i class="fa fa-truck me-2 text-primary"></i>
                            Shipping Information
                        </h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="shipping_name"
                                       class="form-control"
                                       value="{{ old('shipping_name', $customer->name) }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email"
                                       name="shipping_email"
                                       class="form-control"
                                       value="{{ old('shipping_email', $customer->email) }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel"
                                       name="shipping_phone"
                                       class="form-control"
                                       value="{{ old('shipping_phone', $customer->phone ?? '') }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select name="shipping_country" class="form-select" required>
                                    <option value="">Select Country</option>
                                    <option value="Nepal" {{ old('shipping_country') == 'Nepal' ? 'selected' : '' }}>Nepal</option>
                                    <option value="India" {{ old('shipping_country') == 'India' ? 'selected' : '' }}>India</option>
                                    <option value="United States" {{ old('shipping_country') == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="United Kingdom" {{ old('shipping_country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Canada" {{ old('shipping_country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="Australia" {{ old('shipping_country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="Germany" {{ old('shipping_country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="France" {{ old('shipping_country') == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Japan" {{ old('shipping_country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                    <option value="China" {{ old('shipping_country') == 'China' ? 'selected' : '' }}>China</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="shipping_address"
                                       class="form-control"
                                       placeholder="House number and street name"
                                       value="{{ old('shipping_address', $customerProfile->shipping_address ?? '') }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="shipping_city"
                                       class="form-control"
                                       value="{{ old('shipping_city') }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State/Province <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="shipping_state"
                                       class="form-control"
                                       value="{{ old('shipping_state') }}"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="shipping_postal_code"
                                       class="form-control"
                                       value="{{ old('shipping_postal_code') }}"
                                       required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Order Notes (Optional)</label>
                                <textarea name="order_notes"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('order_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="checkout-section">
                        <h4>
                            <i class="fa fa-credit-card me-2 text-primary"></i>
                            Billing Information
                        </h4>

                                                <!-- Same as shipping checkbox -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="same_as_shipping"
                                       id="same_as_shipping"
                                       value="1"
                                       {{ old('same_as_shipping', old('billing_name') || old('billing_email') || old('billing_address') ? '' : '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="same_as_shipping">
                                    <strong>Billing address is the same as shipping address</strong>
                                </label>
                            </div>
                        </div>

                        <!-- Billing form fields (hidden by default) -->
                        <div id="billing_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_name"
                                           class="form-control billing-field"
                                           value="{{ old('billing_name', $customer->name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="billing_email"
                                           class="form-control billing-field"
                                           value="{{ old('billing_email', $customer->email) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel"
                                           name="billing_phone"
                                           class="form-control billing-field"
                                           value="{{ old('billing_phone', $customer->phone ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_country"
                                           class="form-control billing-field"
                                           value="{{ old('billing_country', 'Nepal') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_address"
                                           class="form-control billing-field"
                                           placeholder="House number and street name"
                                           value="{{ old('billing_address', $customerProfile->billing_address ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_city"
                                           class="form-control billing-field"
                                           value="{{ old('billing_city') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State/Province <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_state"
                                           class="form-control billing-field"
                                           value="{{ old('billing_state') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Postal Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="billing_postal_code"
                                           class="form-control billing-field"
                                           value="{{ old('billing_postal_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="checkout-section">
                        <h4>
                            <i class="fa fa-credit-card me-2 text-primary"></i>
                            Payment Method
                        </h4>
                        <div class="payment-methods">
                            <!-- Default Payment Methods -->
                            <div class="payment-method">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           value="cash_on_delivery"
                                           id="payment_cash"
                                           checked
                                           required>
                                    <label class="form-check-label payment-label" for="payment_cash">
                                        <strong>Cash on Delivery</strong>
                                        <br><small class="text-muted">Pay when you receive your order</small>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           value="bank_transfer"
                                           id="payment_bank">
                                    <label class="form-check-label payment-label" for="payment_bank">
                                        <strong>Bank Transfer</strong>
                                        <br><small class="text-muted">Transfer to our bank account</small>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           value="esewa"
                                           id="payment_esewa">
                                    <label class="form-check-label payment-label" for="payment_esewa">
                                        <strong>eSewa</strong>
                                        <br><small class="text-muted">Pay with your eSewa wallet</small>
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           value="khalti"
                                           id="payment_khalti">
                                    <label class="form-check-label payment-label" for="payment_khalti">
                                        <strong>Khalti</strong>
                                        <br><small class="text-muted">Pay with your Khalti wallet</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="order-summary-section">
                        <div class="order-summary-card">
                            <h4>
                                <i class="fa fa-list-alt me-2 text-primary"></i>
                                Your Order
                            </h4>

                            <!-- Cart Items -->
                            <div class="order-items mb-3">
                                @foreach($cartItems as $item)
                                    <div class="order-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="item-info flex-grow-1">
                                                <h6 class="mb-1">{{ Str::limit($item->product->name, 30) }}</h6>
                                                <div class="item-quantity">
                                                    <small>Qty: {{ $item->quantity }}</small>
                                                </div>
                                                @if($item->productVariation)
                                                    <div class="item-variation">
                                                        <small class="text-muted">{{ $item->productVariation->attribute->name ?? '' }}: {{ $item->productVariation->attributeValue->value ?? '' }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="item-price ms-2">
                                                <span>Rs. {{ number_format(($item->productVariation ? $item->productVariation->price : $item->product->price) * $item->quantity, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Summary -->
                            <div class="order-totals">
                                <div class="summary-line d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>Rs. {{ number_format($cartSubtotal, 2) }}</span>
                                </div>

                                <div class="summary-line d-flex justify-content-between">
                                    <span>Shipping:</span>
                                    <span>
                                        @if($shippingFee > 0)
                                            Rs. {{ number_format($shippingFee, 2) }}
                                        @else
                                            <span class="text-success">Free</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="summary-line d-flex justify-content-between">
                                    <span>Tax (13%):</span>
                                    <span>Rs. {{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="summary-line d-flex justify-content-between total-line">
                                    <span><strong>Total:</strong></span>
                                    <span><strong>Rs. {{ number_format($grandTotal, 2) }}</strong></span>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <div class="place-order-section">
                                <button type="submit" class="btn btn-primary w-100" id="checkoutSubmitBtn">
                                    <i class="fa fa-lock me-2"></i>
                                    Place Order Securely
                                </button>

                                <!-- Security Badges -->
                                <div class="security-badges">
                                    <div class="security-badge">
                                        <i class="fa fa-shield"></i>
                                        <span>SSL Secured</span>
                                    </div>
                                    <div class="security-badge">
                                        <i class="fa fa-check-circle"></i>
                                        <span>Safe Payment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
// Improved JavaScript for form submission handling
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const form = document.getElementById('checkoutForm');
    console.log('Checkout form initialized');

    // Handle same as shipping checkbox
    const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
    const billingFields = document.getElementById('billing_fields');

    function toggleBillingFields() {
        if (sameAsShippingCheckbox && billingFields) {
            if (sameAsShippingCheckbox.checked) {
                billingFields.style.display = 'none';
                // Remove required attribute from billing fields when hidden
                const billingInputs = billingFields.querySelectorAll('.billing-field');
                billingInputs.forEach(input => {
                    input.removeAttribute('required');
                });
            } else {
                billingFields.style.display = 'block';
                // Add required attribute to billing fields when shown
                const billingInputs = billingFields.querySelectorAll('.billing-field');
                billingInputs.forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        }
    }

    // Initialize billing fields visibility
    toggleBillingFields();

    // Show billing fields if there are validation errors for billing fields
    @if($errors->hasAny(['billing_name', 'billing_email', 'billing_phone', 'billing_country', 'billing_address', 'billing_city', 'billing_state', 'billing_postal_code']))
        if (sameAsShippingCheckbox) {
            sameAsShippingCheckbox.checked = false;
            toggleBillingFields();
        }
    @endif

    // Add event listener for checkbox change
    if (sameAsShippingCheckbox) {
        sameAsShippingCheckbox.addEventListener('change', toggleBillingFields);
    }

    // Add a submit event listener
    if (form) {
        form.addEventListener('submit', function(event) {
            console.log('Form submit initiated');

            // Display a loading indicator on the submit button
            const submitBtn = document.getElementById('checkoutSubmitBtn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';
                submitBtn.disabled = true;
            }

            // Let the form submit naturally - no event.preventDefault()
            console.log('Form being submitted to: ' + form.action);
            return true;
        });
    } else {
        console.error('Checkout form not found!');
    }

    // Add visual feedback for payment methods
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('selected');
            });
            this.closest('.payment-method').classList.add('selected');
        });
    });
});
</script>
@endpush
@endsection
