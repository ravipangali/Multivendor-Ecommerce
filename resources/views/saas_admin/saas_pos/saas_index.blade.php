@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'POS System')

@section('content')
<style>
    .pos-container {
        background: #f0f8f0;
        min-height: 100vh;
        padding: 12px 0;
    }

    .pos-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .pos-header h2 {
        margin: 0;
        font-weight: 600;
        font-size: 1.3rem;
    }

    .pos-header p {
        margin: 3px 0 0 0;
        opacity: 0.9;
        font-size: 0.8rem;
    }

    .products-section {
        background: white;
        border-radius: 4px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 12px;
    }

    .cart-section {
        background: white;
        border-radius: 4px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 12px;
    }

    .filter-bar {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 3px;
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
    }

    .filter-bar .form-control, .filter-bar .form-select {
        border-radius: 3px;
        border: 1px solid #dee2e6;
        padding: 6px 8px;
        font-size: 12px;
    }

    .filter-bar .btn {
        border-radius: 3px;
        padding: 6px 12px;
        font-weight: 500;
        font-size: 12px;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .product-card {
        background: white;
        border-radius: 3px;
        border: 1px solid #f1f3f4;
        transition: all 0.2s ease;
        cursor: pointer;
        overflow: hidden;
        position: relative;
    }

    .product-card:hover {
        border-color: #28a745;
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(40, 167, 69, 0.15);
    }

    .product-image {
        height: 120px;
        overflow: hidden;
        position: relative;
        background: #f8f9fa;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .stock-badge {
        position: absolute;
        top: 4px;
        right: 4px;
        background: #28a745;
        color: white;
        padding: 2px 4px;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 500;
    }

    .stock-badge.low-stock {
        background: #dc3545;
    }

    .product-info {
        padding: 8px;
    }

    .product-name {
        font-weight: 500;
        font-size: 12px;
        color: #2d3748;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .product-category {
        color: #718096;
        font-size: 10px;
        margin-bottom: 6px;
    }

    .product-price {
        font-size: 14px;
        font-weight: 600;
        color: #28a745;
        margin-bottom: 8px;
    }

    .add-btn {
        width: 100%;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 6px;
        border-radius: 3px;
        font-weight: 500;
        font-size: 11px;
        transition: all 0.2s ease;
    }

    .add-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .cart-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 10px;
        margin: -12px -12px 12px -12px;
        border-radius: 4px 4px 0 0;
    }

    .cart-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
    }

    .customer-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }

    .customer-section h6 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .customer-section .form-control, .customer-section .form-select {
        border-radius: 3px;
        border: 1px solid #dee2e6;
        padding: 6px 8px;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .cart-items {
        margin-bottom: 20px;
    }

    .cart-item {
        background: #f8f9fa;
        border-radius: 3px;
        padding: 6px;
        margin-bottom: 6px;
        border: 1px solid #e9ecef;
    }

    .cart-item-name {
        font-weight: 500;
        color: #2d3748;
        font-size: 11px;
        margin-bottom: 3px;
    }

    .cart-item-price {
        color: #718096;
        font-size: 10px;
        margin-bottom: 6px;
    }

    .qty-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-btn {
        width: 20px;
        height: 20px;
        border-radius: 3px;
        border: 1px solid #dee2e6;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 10px;
        transition: all 0.2s ease;
    }

    .qty-btn:hover {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .qty-display {
        font-weight: 500;
        color: #2d3748;
        font-size: 11px;
        min-width: 20px;
        text-align: center;
    }

    .remove-btn {
        background: #dc3545;
        border: none;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        transition: all 0.2s ease;
    }

    .remove-btn:hover {
        background: #c82333;
        transform: scale(1.05);
    }

    .order-summary {
        background: #f8f9fa;
        padding: 8px;
        border-radius: 3px;
        margin-bottom: 8px;
        border: 1px solid #e9ecef;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        padding: 4px 0;
        font-size: 11px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row.total {
        border-top: 1px solid #dee2e6;
        padding-top: 6px;
        margin-top: 6px;
        font-weight: 600;
        font-size: 13px;
        color: #28a745;
    }

    .summary-input {
        width: 60px;
        padding: 3px 6px;
        border-radius: 3px;
        border: 1px solid #dee2e6;
        text-align: right;
        font-size: 11px;
    }

    .payment-section .form-select {
        padding: 6px;
        border-radius: 3px;
        border: 1px solid #e9ecef;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .action-buttons {
        display: grid;
        gap: 6px;
    }

    .btn-process {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        color: white;
        padding: 8px;
        border-radius: 3px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .btn-process:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .btn-clear {
        background: #6c757d;
        border: none;
        color: white;
        padding: 6px;
        border-radius: 3px;
        font-weight: 500;
        font-size: 11px;
        transition: all 0.2s ease;
    }

    .btn-clear:hover {
        background: #5a6268;
        color: white;
    }

    .empty-cart {
        text-align: center;
        padding: 20px 10px;
        color: #718096;
    }

    .empty-cart i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        opacity: 0.5;
    }

    .empty-cart h5 {
        font-size: 14px;
        font-weight: 500;
    }

    .empty-cart p {
        font-size: 11px;
    }

    .no-products {
        text-align: center;
        padding: 30px 10px;
        color: #718096;
    }

    .no-products i {
        font-size: 2rem;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .no-products h4 {
        font-size: 16px;
        font-weight: 500;
    }

    .no-products p {
        font-size: 12px;
    }

        /* Touch-friendly improvements */
    .add-btn, .qty-btn, .remove-btn, .btn-process, .btn-clear {
        min-height: 28px;
        min-width: 28px;
    }

    .form-control, .form-select {
        min-height: 28px;
    }

    /* Loading animations */
    .product-card.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-process.loading {
        background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

        /* Expandable card improvements */
    .expandable-card {
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
        margin-bottom: 8px;
    }

    .expandable-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-color: #28a745;
    }

    .card-collapsible {
        background: #f8f9fa;
        border-radius: 3px;
        margin-bottom: 8px;
    }

    .card-toggle {
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 8px 10px;
        border-radius: 3px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .card-toggle:hover {
        background: #e9ecef;
        border-color: #28a745;
    }

    .card-toggle.active {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-color: #28a745;
    }

    .card-toggle h6 {
        font-size: 12px;
        font-weight: 500;
    }

    /* SweetAlert2 Custom Styles */
    .swal2-border-radius {
        border-radius: 4px !important;
    }

    .swal2-popup.swal2-border-radius {
        border-radius: 4px !important;
        font-size: 12px !important;
    }

    .swal2-title {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #2d3748 !important;
    }

    .swal2-html-container {
        font-size: 12px !important;
        color: #4a5568 !important;
    }

    .swal2-confirm {
        font-size: 11px !important;
        padding: 6px 12px !important;
        border-radius: 3px !important;
        font-weight: 500 !important;
    }

    .swal2-cancel {
        font-size: 11px !important;
        padding: 6px 12px !important;
        border-radius: 3px !important;
        font-weight: 500 !important;
    }

    .swal2-icon {
        font-size: 24px !important;
        width: 50px !important;
        height: 50px !important;
        margin: 10px auto 15px !important;
    }

    .swal2-icon.swal2-success {
        border-color: #28a745 !important;
        color: #28a745 !important;
    }

    .swal2-icon.swal2-success [class^="swal2-success-line"] {
        background-color: #28a745 !important;
    }

    .swal2-icon.swal2-success .swal2-success-ring {
        border-color: #28a745 !important;
    }

    /* Toast notifications */
    .toast-notification {
        position: fixed !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 9999 !important;
        min-width: 300px;
        max-width: 400px;
        padding: 15px 20px !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2) !important;
        font-weight: 500 !important;
        backdrop-filter: blur(10px);
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease-out;
    }

    .toast-notification.show {
        opacity: 1;
        transform: translateX(0);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Cart badge */
    .cart-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 500;
        animation: bounce 0.3s ease;
    }

    @keyframes bounce {
        0%, 20%, 60%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        80% { transform: translateY(-5px); }
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .pos-header {
            padding: 15px;
            margin-bottom: 15px;
        }

        .pos-header h2 {
            font-size: 1.4rem;
        }

        .pos-header p {
            font-size: 14px;
        }

                        .products-section, .cart-section {
            padding: 8px;
        }

        .filter-bar {
            padding: 8px;
        }

        .expandable-card {
            margin-bottom: 6px;
        }

        .card-toggle {
            padding: 6px 8px;
        }

        .card-content .p-3 {
            padding: 8px !important;
        }

        .product-card {
            border-radius: 10px;
        }

        .product-image {
            height: 140px;
        }

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-size: 14px;
        }

        .product-price {
            font-size: 16px;
        }

        .add-btn {
            padding: 10px;
            font-size: 14px;
        }

        .cart-header {
            padding: 15px;
            margin: -15px -15px 20px -15px;
        }

        .cart-header h5 {
            font-size: 1.1rem;
        }

        .order-summary {
            padding: 15px;
        }

        .btn-process {
            padding: 15px;
            font-size: 15px;
        }

        .summary-row {
            font-size: 14px;
        }

        .toast-notification {
            right: 10px !important;
            left: 10px !important;
            max-width: none !important;
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr;
        }

        .filter-bar .row {
            row-gap: 12px;
        }

        .filter-bar .col-md-4,
        .filter-bar .col-md-3,
        .filter-bar .col-md-2 {
            width: 100%;
        }
    }

    /* Customer search results styling */
    #customer_search_results {
        border: 1px solid #dee2e6;
        border-radius: 3px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .customer-item {
        transition: all 0.2s ease;
    }

    .customer-item:hover {
        background-color: #f8f9fa;
        border-color: #28a745;
    }

    .customer-item:last-child {
        border-bottom: none !important;
    }

    .customer-item .fw-bold {
        font-size: 12px;
        color: #2d3748;
    }

    .customer-item .text-muted {
        font-size: 10px;
    }

    /* Customer selection styling */
    .customer-dropdown-section {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 15px;
    }

    .customer-search-section {
        background: linear-gradient(135deg, #e7f3ff 0%, #f0f8ff 100%);
        border: 1px solid #a6c8ff;
        border-radius: 6px;
        padding: 12px;
    }

    .section-divider {
        position: relative;
        text-align: center;
        margin: 15px 0;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #dee2e6;
    }

    .section-divider .badge {
        background: #6c757d !important;
        position: relative;
        z-index: 1;
        padding: 4px 8px;
    }
</style>

<div class="pos-container">
    <div class="container-fluid">

        <div class="row">
            <!-- Products Section -->
            <div class="col-lg-8">
                <div class="products-section">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px;">
                            <i class="fas fa-box me-1" style="font-size: 12px; color: #28a745;"></i>Select Products
                        </h6>
                        <span class="badge bg-success" style="font-size: 10px;">{{ $products->total() }} Available</span>
                    </div>

                    <!-- Filters -->
                    <div class="filter-bar">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <i class="fas fa-search position-absolute" style="left: 8px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 10px;"></i>
                                    <input type="text" id="search" class="form-control ps-4" placeholder="Search products..." style="font-size: 11px;" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="category_filter" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="brand_filter" class="form-select">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="clear_filters" class="btn btn-outline-secondary w-100" style="font-size: 11px; padding: 4px;">
                                    <i class="fas fa-eraser me-1" style="font-size: 9px;"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="product-grid" id="products_grid">
                            @foreach($products as $product)
                                <div class="product-item"
                                     data-category="{{ $product->category_id }}"
                                     data-brand="{{ $product->brand_id }}"
                                     data-name="{{ strtolower($product->name) }}">
                                    <div class="product-card">
                                        <div class="product-image">
                                            @if($product->images->count() > 0)
                                                <img src="{{ $product->images->first()->image_url }}"
                                                     alt="{{ $product->name }}"
                                                     onerror="this.src='/images/no-image.svg'">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="stock-badge {{ $product->stock <= 5 ? 'low-stock' : '' }}">
                                                {{ $product->stock }} left
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                            <div class="product-price">Rs {{ number_format($product->price, 2) }}</div>
                                            <button class="add-btn add-to-cart"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-stock="{{ $product->stock }}">
                                                <i class="fas fa-plus me-2"></i>Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="no-products">
                            <i class="fas fa-box-open"></i>
                            <h4>No In-House Products Found</h4>
                            <p>Please add some in-house products to use the POS system.</p>
                            <a href="{{ route('admin.in-house-products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cart Section -->
            <div class="col-lg-4">
                <div class="cart-section">
                    <div class="cart-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0" style="font-size: 12px; color: white;"><i class="fas fa-shopping-cart me-1" style="font-size: 11px;"></i>Shopping Cart</h6>
                            <div class="position-relative">
                                <i class="fas fa-shopping-bag" style="font-size: 16px;"></i>
                                <span id="cart_count" class="cart-badge" style="display: none;">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Expandable Card -->
                    <div class="expandable-card">
                        <div class="card-toggle active" onclick="toggleCard('customer-info')">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0" style="font-size: 11px; color: white;"><i class="fas fa-user me-1" style="font-size: 10px;"></i>Customer Information</h6>
                                <i class="fas fa-chevron-down" id="customer-info-icon" style="font-size: 10px;"></i>
                            </div>
                        </div>
                        <div class="card-content" id="customer-info-content">
                                                        <div class="p-3">
                                <!-- Customer Dropdown -->
                                <div class="customer-dropdown-section">
                                    <label class="form-label mb-2" style="font-size: 11px; font-weight: 600; color: #28a745;">
                                        <i class="fas fa-users me-1"></i> Select from Customer List
                                    </label>
                                    <div class="input-group">
                                        <select id="customer_dropdown" class="form-select" style="font-size: 11px;">
                                            <option value="">Choose a customer...</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-email="{{ $customer->email }}"
                                                        data-phone="{{ $customer->phone }}">
                                                    {{ $customer->name }}
                                                    @if($customer->phone)
                                                        - {{ $customer->phone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('admin.customers.create') }}" class="btn btn-success" title="Add New Customer" target="_blank">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                    <small class="text-muted" style="font-size: 10px;">
                                        <i class="fas fa-info-circle me-1"></i>{{ $customers->count() }} customers available
                                    </small>
                                </div>

                                <!-- OR Divider -->
                                <div class="section-divider">
                                    <span class="badge">OR</span>
                                </div>

                                <!-- Customer Search -->
                                <div class="customer-search-section">
                                    <label class="form-label mb-2" style="font-size: 11px; font-weight: 600; color: #007bff;">
                                        <i class="fas fa-search me-1"></i> Search Customer
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" id="customer_search" class="form-control" placeholder="Search by name, email or phone" style="font-size: 11px;">
                                        <div id="customer_search_loading" class="position-absolute end-0 top-50 translate-middle-y me-2" style="display: none;">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status" style="width: 1rem; height: 1rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customer_search_results" class="position-absolute bg-white shadow-sm rounded w-100 mt-1" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
                                </div>

                                <div id="customer_form">
                                    <input type="hidden" id="customer_id">
                                    <div class="selected-customer-info mb-2 p-2 border rounded" id="selected_customer_info" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-bold" id="selected_customer_name"></h6>
                                                <small class="text-muted" id="selected_customer_contact"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="clear_customer">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="no-customer-selected" id="no_customer_selected">
                                        <div class="alert alert-light border text-center py-2" style="font-size: 11px;">
                                            <i class="fas fa-info-circle me-1"></i> No customer selected
                                            <br>
                                            <small class="text-muted">Search for a customer or proceed as walk-in</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items Expandable Card -->
                    <div id="cart_items_wrapper">
                        <div class="empty-cart" id="empty_cart">
                            <div class="text-center p-4" style="background: #f8f9fa; border-radius: 12px; border: 2px dashed #dee2e6;">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Cart is Empty</h5>
                                <p class="text-muted mb-0">Add products to get started</p>
                            </div>
                        </div>

                        <div id="cart_items_card" style="display: none;">
                            <div class="expandable-card">
                                <div class="card-toggle active" onclick="toggleCard('cart-items')">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0" style="font-size: 11px; color: white;">
                                            <i class="fas fa-shopping-cart me-1" style="font-size: 10px;"></i>Cart Items
                                            <span class="badge bg-success ms-1" id="cart_items_count" style="font-size: 8px;">0</span>
                                        </h6>
                                        <i class="fas fa-chevron-down" id="cart-items-icon" style="font-size: 10px;"></i>
                                    </div>
                                </div>
                                <div class="card-content" id="cart-items-content">
                                    <div class="p-3">
                                        <div id="cart_items"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Expandable Card -->
                    <div id="order_summary" style="display: none;">
                        <!-- Order Totals Card -->
                        <div class="expandable-card">
                            <div class="card-toggle active" onclick="toggleCard('order-totals')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0" style="font-size: 11px; color: white;"><i class="fas fa-calculator me-1" style="font-size: 10px;"></i>Order Totals</h6>
                                    <i class="fas fa-chevron-down" id="order-totals-icon" style="font-size: 10px;"></i>
                                </div>
                            </div>
                            <div class="card-content" id="order-totals-content">
                                <div class="p-3">
                                    <div class="summary-row">
                                        <span>Subtotal:</span>
                                        <span id="subtotal" class="fw-bold">Rs 0.00</span>
                                    </div>
                                    <div class="summary-row">
                                        <span>Discount:</span>
                                        <input type="number" id="discount" class="summary-input" placeholder="0" min="0" step="0.01">
                                    </div>
                                    <div class="summary-row">
                                        <span>Tax:</span>
                                        <input type="number" id="tax" class="summary-input" placeholder="0" min="0" step="0.01">
                                    </div>
                                    <div class="summary-row total">
                                        <span><strong>Total:</strong></span>
                                        <span id="total" class="fw-bold text-primary fs-5">Rs 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Card -->
                        <div class="expandable-card">
                            <div class="card-toggle active" onclick="toggleCard('payment-info')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0" style="font-size: 11px; color: white;"><i class="fas fa-credit-card me-1" style="font-size: 10px;"></i>Payment Information</h6>
                                    <i class="fas fa-chevron-down" id="payment-info-icon" style="font-size: 10px;"></i>
                                </div>
                            </div>
                            <div class="card-content" id="payment-info-content">
                                <div class="p-3">
                                    <select id="payment_method" class="form-select mb-2" required style="font-size: 11px; padding: 4px 6px;">
                                        <option value="">💳 Payment Method</option>
                                        <option value="cash">💵 Cash</option>
                                        <option value="card">💳 Card</option>
                                        <option value="bank_transfer">🏦 Transfer</option>
                                        <option value="mobile_payment">📱 Mobile</option>
                                    </select>
                                    <textarea id="notes" class="form-control" rows="2" placeholder="📝 Notes (optional)" style="border-radius: 3px; font-size: 11px; padding: 4px 6px;"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="button" id="process_sale" class="btn-process">
                                <i class="fas fa-check-circle me-2"></i>Process Sale
                                <small class="d-block opacity-75" style="font-size: 11px;">Ctrl+Enter</small>
                            </button>
                            <button type="button" id="clear_cart" class="btn-clear">
                                <i class="fas fa-trash me-2"></i>Clear Cart
                                <small class="d-block opacity-75" style="font-size: 11px;">Esc</small>
                            </button>
                        </div>

                        <!-- Keyboard Shortcuts Info -->
                        <div class="mt-2 p-2" style="background: linear-gradient(135deg, #f0f8f0 0%, #e8f5e8 100%); border-radius: 3px; border: 1px solid #dee2e6;">
                            <div class="text-center">
                                <div class="fw-bold mb-1" style="color: #28a745; font-size: 10px;">⌨️ Shortcuts</div>
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <span class="badge bg-success" style="font-size: 8px;">F1: Search</span>
                                    <span class="badge bg-success" style="font-size: 8px;">Ctrl+Enter: Process</span>
                                    <span class="badge bg-secondary" style="font-size: 8px;">Esc: Clear</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure jQuery is loaded before running our code
(function() {
    'use strict';

    // Wait for jQuery to be available
    function waitForjQuery() {
        if (typeof $ !== 'undefined') {
            initPOS();
        } else {
            setTimeout(waitForjQuery, 100);
        }
    }

    // Initialize POS system
    function initPOS() {
        $(document).ready(function() {
            let cart = [];

    // Add to cart functionality with animation
    $(document).on('click', '.add-to-cart', function() {
        const $button = $(this);
        const productId = $button.data('product-id');
        const productName = $button.data('product-name');
        const productPrice = parseFloat($button.data('product-price'));
        const productStock = parseInt($button.data('product-stock'));

        // Prevent multiple clicks
        if ($button.hasClass('loading')) {
            return;
        }

        // Add loading state
        $button.addClass('loading').html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');

        setTimeout(() => {
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                if (existingItem.quantity < productStock) {
                    existingItem.quantity++;
                    updateCart();
                    showToast('Product quantity updated!', 'success');
                } else {
                    Swal.fire({
                        title: 'Insufficient Stock!',
                        text: 'Not enough stock available for this product.',
                        icon: 'warning',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                        allowHtml: true,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal2-border-radius'
                        }
                    });
                }
            } else {
                cart.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    stock: productStock
                });
                updateCart();
                showToast('Product added to cart!', 'success');
                focusCustomerSearch();
            }

            // Reset button state
            $button.removeClass('loading').html('<i class="fas fa-plus me-2"></i>Add to Cart');
        }, 300);
    });

    // Update cart display
    function updateCart() {
        const cartItems = $('#cart_items');
        const emptyCart = $('#empty_cart');
        const cartItemsCard = $('#cart_items_card');
        const orderSummary = $('#order_summary');
        const cartCount = $('#cart_count');
        const cartItemsCount = $('#cart_items_count');

        // Update cart counter
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (totalItems > 0) {
            cartCount.text(totalItems).show();
        } else {
            cartCount.hide();
        }

        if (cart.length === 0) {
            emptyCart.show();
            cartItemsCard.hide();
            orderSummary.hide();
            return;
        }

        emptyCart.hide();
        cartItemsCard.show();
        orderSummary.show();

        // Update cart items count badge
        cartItemsCount.text(cart.length);

        let cartHtml = '';

        cart.forEach((item, index) => {
            cartHtml += `
                <div class="cart-item mb-3 p-3" style="background: #f8f9fa; border-radius: 10px; border: 1px solid #e9ecef;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="item-info flex-grow-1">
                            <h6 class="mb-1 text-dark">${item.name}</h6>
                            <p class="mb-0 text-muted small">Rs ${parseFloat(item.price).toFixed(2)} each</p>
                        </div>
                        <button class="btn btn-sm btn-outline-danger rounded-circle p-1 remove-btn" data-index="${index}" title="Remove item">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="qty-controls d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle decrease-qty" data-index="${index}" ${item.quantity <= 1 ? 'disabled' : ''}>
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="quantity mx-3 fw-bold">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-primary rounded-circle increase-qty" data-index="${index}" ${item.quantity >= item.stock ? 'disabled' : ''}>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="item-total fw-bold text-primary">Rs ${(item.price * item.quantity).toFixed(2)}</div>
                    </div>
                </div>
            `;
        });

        cartItems.html(cartHtml);
        updateTotals();
    }

    // Update totals
    function updateTotals() {
        let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = parseFloat($('#discount').val()) || 0;
        const tax = parseFloat($('#tax').val()) || 0;
        const total = subtotal - discount + tax;

        $('#subtotal').text('Rs ' + subtotal.toFixed(2));
        $('#total').text('Rs ' + total.toFixed(2));

        // POS sales are always considered paid in full
    }

    // Cart item actions
    $(document).on('click', '.increase-qty', function() {
        const index = $(this).data('index');
        if (cart[index].quantity < cart[index].stock) {
            cart[index].quantity++;
            updateCart();
        } else {
            Swal.fire({
                title: 'Stock Limit Exceeded!',
                text: `Only ${cart[index].stock} items available in stock.`,
                icon: 'warning',
                confirmButtonColor: '#28a745',
                confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal2-border-radius'
                }
            });
        }
    });

    $(document).on('click', '.decrease-qty', function() {
        const index = $(this).data('index');
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
            updateCart();
        }
    });

    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        updateCart();
        showToast('Item removed from cart', 'info');
    });

    // Update totals on discount/tax change
    $('#discount, #tax').on('input', updateTotals);

    // Clear cart
    $('#clear_cart').click(function() {
        if (cart.length === 0) return;

        Swal.fire({
            title: 'Clear Cart?',
            text: 'Are you sure you want to clear the cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i>Yes, Clear Cart',
            cancelButtonText: '<i class="fas fa-times me-1"></i>Cancel',
            customClass: {
                popup: 'swal2-border-radius'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                updateCart();
                Swal.fire({
                    title: 'Cart Cleared!',
                    text: 'Your cart has been cleared successfully.',
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal2-border-radius'
                    }
                });
            }
        });
    });

    // Search functionality
    $('#search').on('input', function() {
        filterProducts();
    });

    // Filter functionality
    $('#category_filter, #brand_filter').change(function() {
        filterProducts();
    });

    function filterProducts() {
        const searchTerm = $('#search').val().toLowerCase();
        const categoryFilter = $('#category_filter').val();
        const brandFilter = $('#brand_filter').val();

        $('.product-item').each(function() {
            const productName = $(this).data('name');
            const productCategory = $(this).data('category').toString();
            const productBrand = $(this).data('brand').toString();

            let show = true;

            if (searchTerm && !productName.includes(searchTerm)) {
                show = false;
            }

            if (categoryFilter && productCategory !== categoryFilter) {
                show = false;
            }

            if (brandFilter && productBrand !== brandFilter) {
                show = false;
            }

            $(this).toggle(show);
        });
    }

    // Clear filters
    $('#clear_filters').click(function() {
        $('#search').val('');
        $('#category_filter').val('');
        $('#brand_filter').val('');
        $('.product-item').show();
        showToast('Filters cleared!', 'info');
    });

    // Process sale
    $('#process_sale').click(function() {
        if (cart.length === 0) {
            Swal.fire({
                title: 'Cart is Empty!',
                text: 'Please add products to your cart first.',
                icon: 'warning',
                confirmButtonColor: '#28a745',
                confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                customClass: {
                    popup: 'swal2-border-radius'
                }
            });
            return;
        }

        const paymentMethod = $('#payment_method').val();

        if (!paymentMethod) {
            Swal.fire({
                title: 'Payment Method Required!',
                text: 'Please select a payment method to proceed.',
                icon: 'warning',
                confirmButtonColor: '#28a745',
                confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                customClass: {
                    popup: 'swal2-border-radius'
                }
            }).then(() => {
                $('#payment_method').focus();
            });
            return;
        }

        const saleData = {
            customer_id: $('#customer_id').val(),
            items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                price: item.price
            })),
            payment_method: paymentMethod,
            discount: parseFloat($('#discount').val()) || 0,
            tax: parseFloat($('#tax').val()) || 0,
            notes: $('#notes').val().trim(),
            _token: '{{ csrf_token() }}'
        };

        // Show loading
        const processBtn = $(this);
        processBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing Sale...');

        $.post('{{ route("admin.pos.process-sale") }}', saleData)
            .done(function(response) {
                if (response.success) {
                    // Reset form
                    cart = [];
                    updateCart();
                    $('#notes').val('');
                    $('#discount, #tax').val('');
                    $('#payment_method').val('');

                    // Show success and option to print receipt
                    Swal.fire({
                        title: 'Sale Completed Successfully!',
                        text: `Sale #${response.sale_number} has been processed. Would you like to view/print the receipt?`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-receipt me-1"></i>View Receipt',
                        cancelButtonText: '<i class="fas fa-times me-1"></i>Close',
                        allowHtml: true,
                        customClass: {
                            popup: 'swal2-border-radius'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Open receipt in new tab - user can choose to print from there
                            window.open('{{ url("admin/pos/receipt") }}/' + response.sale_number, '_blank');
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error Processing Sale!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                        allowHtml: true,
                        customClass: {
                            popup: 'swal2-border-radius'
                        }
                    });
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'An error occurred while processing the sale';
                Swal.fire({
                    title: 'Sale Processing Failed!',
                    text: error,
                    icon: 'error',
                    confirmButtonColor: '#28a745',
                    confirmButtonText: '<i class="fas fa-check me-1"></i>Got it',
                    allowHtml: true,
                    customClass: {
                        popup: 'swal2-border-radius'
                    }
                });
            })
            .always(function() {
                processBtn.prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i>Process Sale');
            });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const colors = {
            success: '#48bb78',
            error: '#e53e3e',
            info: '#3182ce',
            warning: '#d69e2e'
        };

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle',
            warning: 'fas fa-exclamation-triangle'
        };

        const toast = $(`
            <div class="toast-notification" style="background: ${colors[type]}; color: white;">
                <div class="d-flex align-items-center">
                    <i class="${icons[type]} me-2"></i>
                    <span>${message}</span>
                </div>
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => {
            toast.addClass('show');
        }, 100);

        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Ctrl/Cmd + Enter to process sale
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
            e.preventDefault();
            $('#process_sale').click();
        }

        // Escape to clear cart
        if (e.keyCode === 27) {
            e.preventDefault();
            $('#clear_cart').click();
        }

        // F1 to focus search
        if (e.keyCode === 112) {
            e.preventDefault();
            $('#search').focus();
        }
    });

    // Auto-focus customer search when cart has items
    function focusCustomerSearch() {
        if (cart.length > 0) {
            setTimeout(() => {
                $('#customer_search').focus();
            }, 500);
        }
    }

    // Toggle expandable cards
    window.toggleCard = function(cardId) {
        const content = $('#' + cardId + '-content');
        const icon = $('#' + cardId + '-icon');
        const toggle = content.closest('.expandable-card').find('.card-toggle');

        if (content.is(':visible')) {
            content.slideUp(300);
            icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            toggle.removeClass('active');
        } else {
            content.slideDown(300);
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            toggle.addClass('active');
        }
    };

    // Initialize card states
    function initializeCards() {
        // All cards start expanded
        $('.card-content').show();
        $('.card-toggle').addClass('active');
        $('.card-toggle i[id$="-icon"]').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    }

    // Call initialize function
    initializeCards();

    // Customer search functionality
    let searchTimeout;

    $('#customer_search').on('input', function() {
        clearTimeout(searchTimeout);
        const searchQuery = $(this).val().trim();

        if (searchQuery.length < 2) {
            $('#customer_search_results').empty().hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            $('#customer_search_loading').show();

            $.ajax({
                url: "{{ route('admin.pos.search-customers') }}",
                method: 'GET',
                data: { search: searchQuery },
                success: function(response) {
                    let resultsHtml = '';

                    if (response.customers.length === 0) {
                        resultsHtml = '<div class="p-2 text-muted">No customers found</div>';
                    } else {
                        response.customers.forEach(function(customer) {
                            resultsHtml += `
                                <div class="customer-item p-2 border-bottom" style="cursor: pointer;"
                                    data-id="${customer.id}"
                                    data-name="${customer.name}"
                                    data-email="${customer.email || ''}"
                                    data-phone="${customer.phone || ''}"
                                    data-address="${customer.shipping_address || customer.billing_address || ''}">
                                    <div class="fw-bold">${customer.name}</div>
                                    <div class="small text-muted">${customer.email || ''} ${customer.phone ? ' | ' + customer.phone : ''}</div>
                                </div>
                            `;
                        });
                    }

                    $('#customer_search_results').html(resultsHtml).show();
                },
                error: function(xhr) {
                    console.error('Error searching customers:', xhr);
                    $('#customer_search_results').html('<div class="p-2 text-danger">Error searching customers</div>').show();
                },
                complete: function() {
                    $('#customer_search_loading').hide();
                }
            });
        }, 300);
    });

    // Customer dropdown functionality
    $('#customer_dropdown').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const customerId = selectedOption.val();

        if (customerId) {
            const customerName = selectedOption.data('name');
            const customerEmail = selectedOption.data('email');
            const customerPhone = selectedOption.data('phone');

            // Update customer selection
            updateCustomerSelection(customerId, customerName, customerEmail, customerPhone);

            // Clear search field
            $('#customer_search').val('');
            $('#customer_search_results').hide();

            showToast(`Customer "${customerName}" selected from dropdown`, 'success');
        } else {
            clearCustomerSelection();
        }
    });

    // Customer search click functionality
    $(document).on('click', '.customer-item', function() {
        const customerId = $(this).data('id');
        const customerName = $(this).data('name');
        const customerEmail = $(this).data('email');
        const customerPhone = $(this).data('phone');

        // Update customer selection
        updateCustomerSelection(customerId, customerName, customerEmail, customerPhone);

        // Clear search and dropdown
        $('#customer_search_results').hide();
        $('#customer_search').val('');
        $('#customer_dropdown').val('');

        showToast(`Customer "${customerName}" selected from search`, 'success');
    });

    // Function to update customer selection
    function updateCustomerSelection(customerId, customerName, customerEmail, customerPhone) {
        $('#customer_id').val(customerId);

        // Update the selected customer display
        $('#selected_customer_name').text(customerName);

        let contactInfo = [];
        if (customerEmail) contactInfo.push(customerEmail);
        if (customerPhone) contactInfo.push(customerPhone);

        $('#selected_customer_contact').text(contactInfo.join(' | '));

        // Show the selected customer info and hide the no customer message
        $('#selected_customer_info').show();
        $('#no_customer_selected').hide();
    }

    // Function to clear customer selection
    function clearCustomerSelection() {
        $('#customer_id').val('');
        $('#customer_dropdown').val('');
        $('#customer_search').val('');
        $('#customer_search_results').hide();
        $('#selected_customer_info').hide();
        $('#no_customer_selected').show();
    }

    // Clear selected customer
    $('#clear_customer').on('click', function() {
        clearCustomerSelection();
        showToast('Customer cleared', 'info');
    });

    // Hide search results when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#customer_search, #customer_search_results').length) {
            $('#customer_search_results').hide();
        }
    });
        });
    }

    // Start waiting for jQuery
    waitForjQuery();
})();
</script>
@endsection
