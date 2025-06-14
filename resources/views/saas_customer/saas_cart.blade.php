@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .cart-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 3rem 0;
  }

  .cart-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }

  .cart-header::before {
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

  .cart-item-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .cart-item-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .cart-item-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
    border-color: var(--primary-color);
  }

  .cart-item-card:hover::before {
    opacity: 1;
  }

  .product-image-wrapper {
    position: relative;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
  }

  .product-image {
    width: 100%;
    height: 120px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .product-image:hover {
    transform: scale(1.05);
  }

  .product-info h6 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
  }

  .product-info h6 a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .product-info h6 a:hover {
    color: var(--primary-color);
  }

  .product-meta {
    font-size: 0.875rem;
    color: var(--text-medium);
    margin-bottom: 0.25rem;
  }

  .product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
  }

  .quantity-controls {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 0.5rem;
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--border-light);
  }

  .quantity-btn {
    background: var(--white);
    border: 1px solid var(--border-medium);
    color: var(--text-dark);
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .quantity-btn:hover {
    background: var(--primary-color);
    color: var(--white);
    border-color: var(--primary-color);
  }

  .quantity-input {
    border: none;
    background: transparent;
    text-align: center;
    width: 60px;
    font-weight: 600;
    color: var(--text-dark);
  }

  .remove-btn {
    background: linear-gradient(135deg, var(--danger), #dc3545);
    color: var(--white);
    border: none;
    border-radius: var(--radius-md);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .remove-btn:hover {
    background: linear-gradient(135deg, #c82333, #a71e2a);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
  }

  .cart-summary-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    position: sticky;
    top: 2rem;
  }

  .summary-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .summary-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
  }

  .summary-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
  }

  .summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
  }

  .summary-line:last-of-type {
    border-bottom: none;
    font-weight: 600;
    font-size: 1.125rem;
    color: var(--secondary-color);
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid var(--primary-color);
  }

  .coupon-section {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin: 1.5rem 0;
    border: 1px solid var(--border-light);
  }

  .coupon-section h6 {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 1rem;
  }

  .coupon-input-group {
    display: flex;
    gap: 0.75rem;
  }

  .coupon-input {
    flex: 1;
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
  }

  .coupon-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .checkout-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: var(--radius-md);
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    width: 100%;
    margin-top: 1.5rem;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .checkout-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .checkout-btn:disabled {
    background: var(--text-muted);
    cursor: not-allowed;
    transform: none;
  }

  .payment-methods {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .payment-methods img {
    height: 30px;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
    padding: 0.25rem;
    background: var(--white);
  }

  .cart-actions {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
  }

  .empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin: 2rem 0;
  }

  .empty-cart-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--accent-color), #e2e8f0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: var(--text-muted);
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

  .related-products {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-top: 3rem;
    box-shadow: var(--shadow-md);
  }

  .section-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
  }

  .section-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
  }

  @media (max-width: 768px) {
    .cart-container {
      padding: 1rem 0;
    }

    .cart-header {
      padding: 1.5rem;
      text-align: center;
    }

    .cart-item-card {
      padding: 1rem;
    }

    .product-image {
      height: 80px;
    }

    .cart-summary-card {
      position: static;
      margin-top: 2rem;
    }

    .coupon-input-group {
      flex-direction: column;
    }
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
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="cart-container">
    <div class="container">
        <!-- Cart Header -->
        <div class="cart-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">Shopping Cart</h2>
                    <p class="mb-0 opacity-75 text-white">
                        {{ $cartItems->count() }} {{ Str::plural('item', $cartItems->count()) }} in your cart
                    </p>
                </div>
                {{-- <div class="col-md-4 text-center text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                        <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                            <i class="fa fa-shopping-cart me-1"></i>
                            Cart Total: Rs. {{ $formatted['grand_total'] ?? '0.00' }}
                        </span>
                    </div>
                </div> --}}
            </div>
        </div>

        @if($cartItems->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <!-- Cart Actions -->
                    <div class="cart-actions">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customer.products') }}" class="btn btn-outline-primary">
                                        <i class="fa fa-arrow-left me-2"></i>Continue Shopping
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button class="btn btn-secondary" id="updateCart">
                                        <i class="fa fa-refresh me-2"></i>Update Cart
                                    </button>
                                    <button class="btn btn-danger" id="clearCart">
                                        <i class="fa fa-trash me-2"></i>Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items">
                        @foreach($cartItems as $item)
                            <div class="cart-item-card" data-cart-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-3 col-sm-4 mb-3 mb-sm-0">
                                        <div class="product-image-wrapper">
                                            <img src="{{ $item->product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="product-image">
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="col-md-4 col-sm-8 mb-3 mb-md-0">
                                        <div class="product-info">
                                            <h6>
                                                <a href="{{ route('customer.product.detail', $item->product->slug) }}">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h6>
                                            @if($item->product->brand)
                                                <div class="product-meta">
                                                    <i class="fa fa-tag me-1"></i>{{ $item->product->brand->name }}
                                                </div>
                                            @endif
                                            @if($item->variation_details)
                                                <div class="product-meta">
                                                    <i class="fa fa-cog me-1"></i>
                                                    {{ $item->variation_details }}
                                                </div>
                                            @elseif($item->productVariation)
                                                <div class="product-meta">
                                                    <i class="fa fa-cog me-1"></i>
                                                    {{ $item->productVariation->attribute->name }}: {{ $item->productVariation->attributeValue->value }}
                                                </div>
                                            @endif
                                            <div class="product-price">
                                                Rs. {{ number_format($item->price, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn decrease-qty" type="button" tabindex="-1">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="number"
                                                   class="quantity-input"
                                                   value="{{ $item->quantity }}"
                                                   min="1"
                                                   max="{{ $item->getAvailableStock() }}"
                                                   data-cart-id="{{ $item->id }}"
                                                   data-max="{{ $item->getAvailableStock() }}"
                                                   autocomplete="off">
                                            <button class="quantity-btn increase-qty" type="button" tabindex="-1">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <small class="text-muted">
                                                Stock: {{ $item->getAvailableStock() }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Item Total & Actions -->
                                    <div class="col-md-2 col-6 text-end">
                                        <div class="item-total mb-2">
                                            <strong>Rs. {{ number_format($item->price * $item->quantity, 2) }}</strong>
                                        </div>
                                        <button class="remove-btn remove-item" data-cart-id="{{ $item->id }}">
                                            <i class="fa fa-trash"></i>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary-card">
                        <div class="summary-header">
                            <div class="summary-icon">
                                <i class="fa fa-calculator"></i>
                            </div>
                            <h3 class="summary-title">Order Summary</h3>
                        </div>

                        <div class="summary-details">
                            <div class="summary-line">
                                <span>Subtotal ({{ $cartItems->count() }} items):</span>
                                <span id="cartSubtotal">Rs. {{ $formatted['subtotal'] ?? '0.00' }}</span>
                            </div>

                            <div class="summary-line">
                                <span>Shipping Fee:</span>
                                <span id="shippingCost">
                                    @if(($shipping_cost ?? 0) > 0)
                                        Rs. {{ $formatted['shipping_cost'] ?? '0.00' }}
                                    @else
                                        <span class="text-success">Free</span>
                                    @endif
                                </span>
                            </div>

                            <div class="summary-line">
                                <span>Tax:</span>
                                <span id="taxAmount">Rs. {{ $formatted['tax_amount'] ?? '0.00' }}</span>
                            </div>

                            @if(($discount_amount ?? 0) > 0)
                            <div class="summary-line">
                                <span>Discount
                                    @if(session('applied_coupon_code'))
                                        <small class="text-muted">({{ session('applied_coupon_code') }})</small>
                                    @endif
                                </span>
                                <span>
                                    <span id="discountAmount" class="text-success">-Rs. {{ $formatted['discount_amount'] ?? '0.00' }}</span>
                                    @if(session('applied_coupon_code'))
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" id="removeCoupon" title="Remove coupon">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </span>
                            </div>
                            @endif

                            <div class="summary-line">
                                <span><strong>Total:</strong></span>
                                <span id="grandTotal"><strong>Rs. {{ $formatted['grand_total'] ?? '0.00' }}</strong></span>
                            </div>
                        </div>

                        <!-- Coupon Section -->
                        <div class="coupon-section">
                            <h6><i class="fa fa-ticket me-2"></i>Have a coupon?</h6>
                            <div class="coupon-input-group">
                                <input type="text" class="coupon-input" id="couponCode" placeholder="Enter coupon code">
                                <button class="btn btn-primary" type="button" id="applyCoupon">Apply</button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        @auth
                            <a href="{{ route('customer.checkout') }}" class="checkout-btn">
                                <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                            </a>
                        @else
                            <p class="text-center text-muted mt-3">Please login to checkout</p>
                            <a href="{{ route('login') }}" class="checkout-btn">
                                <i class="fa fa-sign-in me-2"></i>Login to Checkout
                            </a>
                        @endauth


                        <!-- Security Badge -->
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fa fa-shield text-success me-1"></i>
                                Your payment information is secure
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            {{-- <div class="related-products">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fa fa-lightbulb-o"></i>
                    </div>
                    <h3 class="section-title">You might also like</h3>
                </div>

                <div class="row">
                    @foreach($relatedProducts ?? [] as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="shop_item">
                                <div class="shop_item_image">
                                    <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                         alt="{{ $product->name }}" class="img-fluid">
                                </div>
                                <div class="details">
                                    <div class="title">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}">
                                            {{ Str::limit($product->name, 30) }}
                                        </a>
                                    </div>
                                    <div class="price">Rs. {{ number_format($product->final_price, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> --}}

        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('customer.products') }}" class="btn btn-primary btn-lg">
                    <i class="fa fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global throttling mechanism
    let isUpdating = false;
    let updateQueue = [];
    // Quantity controls
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateCartItem(input);
            }
        });
    });

    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.getAttribute('data-max'));
            if (currentValue < maxValue) {
                input.value = currentValue + 1;
                updateCartItem(input);
            }
        });
    });

    // Set initial values for comparison
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.setAttribute('data-original-value', input.value);
    });

        // Direct input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        // Set initial value for comparison
        input.setAttribute('data-original-value', input.value);
        input.addEventListener('change', function(e) {
            e.preventDefault();

            const maxValue = parseInt(this.getAttribute('data-max'));
            const minValue = 1;
            let value = parseInt(this.value);

            if (isNaN(value) || value < minValue) value = minValue;
            if (value > maxValue) value = maxValue;

            this.value = value;

            // Add a small delay to prevent rapid fire requests
            clearTimeout(this.updateTimeout);
            this.updateTimeout = setTimeout(() => {
                // Only update if value actually changed
                const originalValue = this.getAttribute('data-original-value') || this.defaultValue;
                if (parseInt(this.value) !== parseInt(originalValue)) {
                    updateCartItem(this);
                    this.setAttribute('data-original-value', this.value);
                }
            }, 500);
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const cartId = this.getAttribute('data-cart-id');
            removeCartItem(cartId, this);
        });
    });

    // Update cart
    document.getElementById('updateCart')?.addEventListener('click', function() {
        updateEntireCart();
    });

    // Clear cart
    document.getElementById('clearCart')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Clear Cart?',
            text: 'Are you sure you want to clear your cart? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f56565',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Clear Cart',
            cancelButtonText: 'Keep Shopping'
        }).then((result) => {
            if (result.isConfirmed) {
                clearCart();
            }
        });
    });

    // Apply coupon
    document.getElementById('applyCoupon')?.addEventListener('click', function() {
        const couponCode = document.getElementById('couponCode').value;
        if (couponCode.trim()) {
            applyCoupon(couponCode);
        }
    });

    // Remove coupon
    document.getElementById('removeCoupon')?.addEventListener('click', function() {
        removeCoupon();
    });

    function updateCartItem(input) {
        const cartId = input.getAttribute('data-cart-id');
        const quantity = parseInt(input.value);
        const cartItem = input.closest('.cart-item-card');

        // Prevent multiple simultaneous requests
        if (cartItem.isUpdating) {
            return;
        }
        cartItem.isUpdating = true;

        // Show loading state on quantity buttons
        const decreaseBtn = cartItem.querySelector('.decrease-qty');
        const increaseBtn = cartItem.querySelector('.increase-qty');
        const removeBtn = cartItem.querySelector('.remove-btn');

        decreaseBtn.disabled = true;
        increaseBtn.disabled = true;
        removeBtn.disabled = true;

        input.disabled = true;

        fetch(`{{ route('customer.cart.update') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ cart_id: cartId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Calculate and update item total
                const price = parseFloat(cartItem.querySelector('.product-price').textContent.replace('Rs. ', '').replace(/,/g, ''));
                const itemTotal = (price * quantity).toFixed(2);
                const itemTotalElement = cartItem.querySelector('.item-total strong');
                itemTotalElement.textContent = `Rs. ${Number(itemTotal).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                // Update cart totals
                document.getElementById('cartSubtotal').textContent = `Rs. ${data.cart_total}`;
                document.getElementById('taxAmount').textContent = `Rs. ${data.tax}`;
                document.getElementById('grandTotal').textContent = `Rs. ${data.grand_total}`;

                // Update cart header
                updateCartHeader(data.cart_count);

                // Show success message only if quantity actually changed
                showNotification('Cart updated successfully', 'success');
            } else {
                showNotification(data.message || 'Failed to update cart', 'error');
                // Revert the input value on error
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating cart', 'error');
            // Revert the input value on error
            location.reload();
        })
        .finally(() => {
            // Re-enable all controls
            decreaseBtn.disabled = false;
            increaseBtn.disabled = false;
            removeBtn.disabled = false;
            input.disabled = false;
            cartItem.isUpdating = false;
        });
    }

    function removeCartItem(cartId, button) {
        const cartItem = button.closest('.cart-item-card');

        // Prevent multiple clicks
        if (cartItem.isRemoving) {
            return;
        }
        cartItem.isRemoving = true;

        // Show loading state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch(`{{ route('customer.cart.remove', ':id') }}`.replace(':id', cartId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate removal
                cartItem.style.transition = 'all 0.3s ease';
                cartItem.style.transform = 'translateX(-100%)';
                cartItem.style.opacity = '0';

                setTimeout(() => {
                    cartItem.remove();

                    // Update cart header
                    updateCartHeader(data.cart_count);

                    // Update totals or show empty cart
                    if (data.cart_count > 0) {
                        document.getElementById('cartSubtotal').textContent = `Rs. ${data.cart_total}`;
                        document.getElementById('taxAmount').textContent = `Rs. ${data.tax}`;
                        document.getElementById('grandTotal').textContent = `Rs. ${data.grand_total}`;
                    } else {
                        // Show empty cart message instead of reload
                        setTimeout(() => {
                            window.location.href = '{{ route("customer.cart") }}';
                        }, 1000);
                    }
                }, 300);

                showNotification('Item removed from cart', 'success');
            } else {
                showNotification(data.message || 'Failed to remove item', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
                cartItem.isRemoving = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while removing item', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
            cartItem.isRemoving = false;
        });
    }

    function updateCartHeader(itemCount) {
        const cartHeader = document.querySelector('.cart-header p');
        if (cartHeader) {
            cartHeader.textContent = `${itemCount} ${itemCount === 1 ? 'item' : 'items'} in your cart`;
        }

        // Also update cart badge in header if it exists
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = itemCount;
        }
    }

    function updateEntireCart() {
        // Trigger update for all cart items
        showNotification('Updating entire cart...', 'info');

        // Just refresh to get latest calculations
        setTimeout(() => {
            window.location.href = '{{ route("customer.cart") }}';
        }, 500);
    }

    function clearCart() {
        fetch('{{ route('customer.cart.clear') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Cart cleared successfully', 'success');
                location.reload();
            } else {
                showNotification(data.message || 'Failed to clear cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while clearing cart', 'error');
        });
    }

    function applyCoupon(couponCode) {
        const button = document.getElementById('applyCoupon');
        const originalText = button.textContent;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch('{{ route('customer.cart.apply-coupon') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ coupon_code: couponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update totals with discount
                document.getElementById('cartSubtotal').textContent = `Rs. ${data.cart_total}`;
                document.getElementById('taxAmount').textContent = `Rs. ${data.tax}`;
                document.getElementById('grandTotal').textContent = `Rs. ${data.grand_total}`;

                // Show/update discount line
                updateDiscountDisplay(data.discount_amount, couponCode);

                showNotification('Coupon applied successfully!', 'success');
                document.getElementById('couponCode').value = '';
            } else {
                showNotification(data.error || 'Invalid coupon code', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while applying coupon', 'error');
        })
        .finally(() => {
            button.textContent = originalText;
            button.disabled = false;
        });
    }

    function removeCoupon() {
        const button = document.getElementById('removeCoupon');
        if (!button) return;

        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        button.disabled = true;

        fetch('{{ route('customer.cart.remove-coupon') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update totals
                document.getElementById('cartSubtotal').textContent = `Rs. ${data.cart_total}`;
                document.getElementById('taxAmount').textContent = `Rs. ${data.tax}`;
                document.getElementById('grandTotal').textContent = `Rs. ${data.grand_total}`;

                // Hide discount line
                const discountLine = document.getElementById('discountAmount')?.closest('.summary-line');
                if (discountLine) {
                    discountLine.style.display = 'none';
                }

                showNotification('Coupon removed successfully!', 'success');

                // Refresh page to update server-side rendered content
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.error || 'Failed to remove coupon', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while removing coupon', 'error');
        })
        .finally(() => {
            if (button.parentElement) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    }

    function updateDiscountDisplay(discountAmount, couponCode = '') {
        let discountElement = document.getElementById('discountAmount');
        let discountLine = discountElement?.closest('.summary-line');

        if (parseFloat(discountAmount) > 0) {
            if (!discountLine) {
                // Create discount line if it doesn't exist
                const taxLine = document.getElementById('taxAmount').closest('.summary-line');
                const newDiscountLine = document.createElement('div');
                newDiscountLine.className = 'summary-line';
                newDiscountLine.innerHTML = `
                    <span>Discount ${couponCode ? `<small class="text-muted">(${couponCode})</small>` : ''}</span>
                    <span>
                        <span id="discountAmount" class="text-success">-Rs. ${discountAmount}</span>
                        ${couponCode ? '<button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2" id="removeCoupon" title="Remove coupon"><i class="fa fa-times"></i></button>' : ''}
                    </span>
                `;
                taxLine.insertAdjacentElement('afterend', newDiscountLine);

                // Add event listener to new remove button
                if (couponCode) {
                    document.getElementById('removeCoupon')?.addEventListener('click', function() {
                        removeCoupon();
                    });
                }
            } else {
                // Update existing discount line
                discountElement.textContent = `-Rs. ${discountAmount}`;
                discountLine.style.display = 'flex';
            }
        } else if (discountLine) {
            // Hide discount line if no discount
            discountLine.style.display = 'none';
        }
    }

    function showNotification(message, type) {
        // Use SweetAlert for important messages
        if (type === 'success' && (message.includes('cleared') || message.includes('updated'))) {
            Swal.fire({
                title: type === 'success' ? 'Success!' : 'Notice',
                text: message,
                icon: type === 'success' ? 'success' : 'info',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        // Create toast notification for other messages
        const toast = document.createElement('div');
        let alertClass, iconClass;

        switch(type) {
            case 'success':
                alertClass = 'alert-success';
                iconClass = 'fa-check-circle';
                break;
            case 'info':
                alertClass = 'alert-info';
                iconClass = 'fa-info-circle';
                break;
            default:
                alertClass = 'alert-danger';
                iconClass = 'fa-exclamation-circle';
        }

        toast.className = `alert ${alertClass} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="fa ${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }
});
</script>
@endpush
@endsection
