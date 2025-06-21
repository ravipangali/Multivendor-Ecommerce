@extends('saas_customer.saas_layout.saas_layout')

@section('title', $product->name . ' - Product Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css"
    integrity="sha512-rd0qOHVMOcez6pLWPVFIv7EfSdGKLt+eafXh4RO/12Fgr41hDQxfGvoi1Vy55QIVcQEujUE1LQrATCLl2Fs+ag=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .product-detail-page {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .breadcrumb-section {
        background: white;
        padding: 1rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-item a {
        color: #64748b;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: #0f172a;
    }

    .breadcrumb-item.active {
        color: #0f172a;
        font-weight: 500;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .product-gallery {
        position: sticky;
        top: 2rem;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .thumbnail-container {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding: 0.5rem 0;
    }

    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #abcf37;
    }

    .product-info {
        padding: 2rem;
    }

    .product-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .product-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .meta-item {
        background: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        color: #475569;
    }

    .price-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .current-price {
        font-size: 2rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 0.5rem;
    }

    .original-price {
        font-size: 1.125rem;
        color: #6b7280;
        text-decoration: line-through;
        margin-right: 1rem;
    }

    .discount-badge {
        background: #dc2626;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .stock-status {
        margin-bottom: 1.5rem;
    }

    .in-stock {
        color: #059669;
        background: #ecfdf5;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid #bbf7d0;
    }

    .out-of-stock {
        color: #dc2626;
        background: #fef2f2;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid #fecaca;
    }

    .variations-section {
        margin-bottom: 1.5rem;
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .variation-group {
        margin-bottom: 1.5rem;
    }

    .variation-group:last-child {
        margin-bottom: 0;
    }

    .variation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .variation-label {
        font-weight: 600;
        color: #374151;
        margin: 0;
        font-size: 1rem;
    }

    .variation-clear {
        background: none;
        border: none;
        color: #6b7280;
        font-size: 0.875rem;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
        transition: color 0.2s;
    }

    .variation-clear:hover {
        color: #374151;
    }

    .variation-clear:disabled {
        color: #d1d5db;
        cursor: not-allowed;
        text-decoration: none;
    }

    .variation-options {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .variation-option {
        position: relative;
    }

    .variation-option input[type="radio"] {
        display: none;
    }

    .variation-option label {
        display: inline-block;
        padding: 0.75rem 1.25rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 0.875rem;
        position: relative;
        min-width: 60px;
        text-align: center;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .variation-option label:hover {
        border-color: #abcf37;
        background: rgba(171, 207, 55, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    .variation-option input[type="radio"]:checked + label {
        background: #abcf37;
        border-color: #abcf37;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px 0 rgba(171, 207, 55, 0.3);
    }

    .variation-option input[type="radio"]:checked + label::after {
        content: '✓';
        position: absolute;
        top: -8px;
        right: -8px;
        background: #059669;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
        border: 2px solid white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .selected-variations {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(171, 207, 55, 0.1);
        border-radius: 6px;
        border: 1px solid rgba(171, 207, 55, 0.2);
        display: none;
    }

    .selected-variations.show {
        display: block;
    }

    .selected-variations h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .selected-variation-item {
        display: inline-flex;
        align-items: center;
        background: #abcf37;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .price-variation-info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        padding: 0.75rem;
        border-radius: 6px;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #1e40af;
        display: none;
    }

    .price-variation-info.show {
        display: block;
    }

    /* Variation Animation */
    .variation-option label {
        animation: none;
    }

    .variation-option input[type="radio"]:checked + label {
        animation: variationSelected 0.3s ease;
    }

    @keyframes variationSelected {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Better mobile responsiveness for variations */
    @media (max-width: 576px) {
        .variations-section {
            padding: 1rem;
        }

        .variation-options {
            gap: 0.5rem;
        }

        .variation-option label {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            min-width: 50px;
        }

        .variation-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .clear-all-variations {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem !important;
        }
    }

    .quantity-section {
        margin-bottom: 1.5rem;
    }

    .quantity-controls {
        display: inline-flex;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        overflow: hidden;
    }

    .quantity-btn {
        background: #f9fafb;
        border: none;
        padding: 0.75rem;
        cursor: pointer;
        color: #374151;
        width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover {
        background: #e5e7eb;
    }

    .quantity-input {
        border: none;
        padding: 0.75rem;
        width: 80px;
        text-align: center;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        background: #abcf37;
        border-color: #abcf37;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        flex: 1;
    }

    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    .btn-outline-primary {
        color: #abcf37;
        border-color: #abcf37;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        flex: 1;
    }

    .btn-outline-primary:hover {
        background: #abcf37;
        border-color: #abcf37;
    }

    .secondary-actions {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 6px;
        margin-bottom: 1.5rem;
    }

    .action-link {
        color: #6b7280;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .action-link:hover {
        color: #374151;
    }

    .action-link.active {
        color: #dc2626;
    }

    .seller-info {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .seller-features {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: #abcf37;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .feature-text h6 {
        margin: 0;
        font-weight: 600;
        color: #374151;
    }

    .feature-text p {
        margin: 0;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .product-tabs {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .tab-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        overflow-x: auto;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 1rem 1.5rem;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        white-space: nowrap;
    }

    .tab-btn:hover {
        color: #374151;
    }

    .tab-btn.active {
        color: #abcf37;
        border-bottom-color: #abcf37;
        background: rgba(171, 207, 55, 0.05);
    }

    .tab-content {
        padding: 2rem;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .spec-table {
        width: 100%;
        border-collapse: collapse;
    }

    .spec-table th,
    .spec-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .spec-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        width: 30%;
    }

    .spec-table td {
        color: #6b7280;
    }

    .review-item {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .review-rating {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .star {
        color: #fbbf24;
    }

    .star.empty {
        color: #d1d5db;
    }

    .related-products {
        margin-top: 3rem;
    }

    .related-products h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .product-item {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }

    .product-item:hover {
        transform: translateY(-2px);
    }

    .product-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-item-content {
        padding: 1rem;
    }

    .product-item h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .product-item .price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #059669;
    }

    @media (max-width: 768px) {
        .product-detail-page {
            padding: 1rem 0;
        }

        .product-info {
            padding: 1rem;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .current-price {
            font-size: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .tab-header {
            flex-direction: column;
        }

        .product-gallery {
            position: static;
        }
    }

    /* Mobile-Responsive Product Image Styles */
    .product-main-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        border-radius: var(--radius-md);
    }

    .product-thumbnail-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        object-position: center;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    /* Mobile specific product image optimizations */
    @media (max-width: 768px) {
        .product-main-img {
            height: 300px;
        }
        
        .product-thumbnail-img {
            width: 60px;
            height: 60px;
        }
        
        .thumbnail-container {
            gap: 0.3rem;
        }
    }

    @media (max-width: 480px) {
        .product-main-img {
            height: 250px;
        }
        
        .product-thumbnail-img {
            width: 50px;
            height: 50px;
        }
        
        .thumbnail-container {
            gap: 0.25rem;
            padding: 0.25rem 0;
        }
    }

    /* Mobile-Responsive Typography Styles */
    @media (max-width: 768px) {
        /* Product title and meta */
        .product-title {
            font-size: 1.5rem !important;
            line-height: 1.2;
        }
        
        .product-meta .meta-item {
            font-size: 0.8rem !important;
        }
        
        /* Price typography */
        .current-price {
            font-size: 1.5rem !important;
        }
        
        .original-price {
            font-size: 1rem !important;
        }
        
        .discount-badge {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.8rem !important;
        }
        
        /* Stock status */
        .in-stock,
        .out-of-stock {
            font-size: 0.85rem !important;
        }
        
        /* Variations */
        .variation-label {
            font-size: 0.9rem !important;
        }
        
        .variation-option label {
            font-size: 0.8rem !important;
            padding: 0.6rem 1rem !important;
        }
        
        /* Buttons */
        .btn {
            font-size: 0.85rem !important;
            padding: 0.7rem 1.2rem !important;
        }
        
        .btn-sm {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.8rem !important;
        }
        
        /* Tabs */
        .nav-link {
            font-size: 0.85rem !important;
            padding: 0.6rem 1rem !important;
        }
        
        /* Product description */
        .product-description p {
            font-size: 0.95rem !important;
            line-height: 1.6;
        }
        
        .product-description h3 {
            font-size: 1.25rem !important;
        }
        
        .product-description h4 {
            font-size: 1.1rem !important;
        }
        
        /* Specifications */
        .specification-item {
            font-size: 0.85rem !important;
        }
        
        /* Reviews */
        .review-author {
            font-size: 0.9rem !important;
        }
        
        .review-date {
            font-size: 0.75rem !important;
        }
        
        .review-text {
            font-size: 0.85rem !important;
            line-height: 1.5;
        }
        
        /* Related products */
        .related-product-title {
            font-size: 0.85rem !important;
            line-height: 1.3;
        }
        
        .related-product-price {
            font-size: 0.9rem !important;
        }
    }

    @media (max-width: 480px) {
        /* Extra small screens */
        .product-title {
            font-size: 1.25rem !important;
        }
        
        .product-meta .meta-item {
            font-size: 0.75rem !important;
        }
        
        .current-price {
            font-size: 1.3rem !important;
        }
        
        .original-price {
            font-size: 0.9rem !important;
        }
        
        .discount-badge {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.6rem !important;
        }
        
        .in-stock,
        .out-of-stock {
            font-size: 0.8rem !important;
        }
        
        .variation-label {
            font-size: 0.85rem !important;
        }
        
        .variation-option label {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.8rem !important;
        }
        
        .btn {
            font-size: 0.8rem !important;
            padding: 0.6rem 1rem !important;
        }
        
        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.4rem 0.6rem !important;
        }
        
        .nav-link {
            font-size: 0.8rem !important;
            padding: 0.5rem 0.8rem !important;
        }
        
        .product-description p {
            font-size: 0.9rem !important;
        }
        
        .product-description h3 {
            font-size: 1.1rem !important;
        }
        
        .product-description h4 {
            font-size: 1rem !important;
        }
        
        .specification-item {
            font-size: 0.8rem !important;
        }
        
        .review-author {
            font-size: 0.85rem !important;
        }
        
        .review-date {
            font-size: 0.7rem !important;
        }
        
        .review-text {
            font-size: 0.8rem !important;
        }
        
        .related-product-title {
            font-size: 0.8rem !important;
        }
        
        .related-product-price {
            font-size: 0.85rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="product-detail-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.products') }}">Products</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('customer.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    @endif
                    @if($product->subcategory)
                        <li class="breadcrumb-item"><a href="#">{{ $product->subcategory->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 50) }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Product Gallery -->
            <div class="col-lg-5">
                <div class="product-card">
                    <div class="product-gallery p-3">
                        <div class="main-image-container mb-3">
                            @if($product->images->count() > 0)
                                <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="main-image product-main-img" id="mainImage">
                            @else
                                <img src="{{ asset('storage/product_images/default-product.jpg') }}" alt="{{ $product->name }}" class="main-image product-main-img" id="mainImage">
                            @endif
                        </div>

                        @if($product->images->count() > 1)
                            <div class="thumbnail-container">
                                @foreach($product->images as $index => $image)
                                    <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="thumbnail product-thumbnail-img {{ $index === 0 ? 'active' : '' }}" onclick="changeMainImage('{{ $image->image_url }}', this)">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-lg-7">
                <div class="product-card">
                    <div class="product-info">
                        <!-- Product Title -->
                        <h1 class="product-title">{{ $product->name }}</h1>

                        <!-- Product Meta -->
                        <div class="product-meta">
                            @if($product->brand)
                                <span class="meta-item"><strong>Brand:</strong> {{ $product->brand->name }}</span>
                            @endif
                            @if($product->sku)
                                <span class="meta-item"><strong>SKU:</strong> {{ $product->sku }}</span>
                            @endif
                            @if($product->unit)
                                <span class="meta-item"><strong>Unit:</strong> {{ $product->unit->name }}</span>
                            @endif
                        </div>

                        <!-- Short Description -->
                        @if($product->short_description)
                            <p class="text-muted mb-3">{{ $product->short_description }}</p>
                        @endif

                        <!-- Price Section -->
                        <div class="price-section">
                            <div class="d-flex align-items-center gap-3">
                                <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                @if($product->discount > 0)
                                    <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                    <span class="discount-badge">
                                        @if($product->discount_type === 'percentage')
                                            {{ $product->discount }}% OFF
                                        @else
                                            Rs. {{ number_format($product->discount, 2) }} OFF
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if($product->tax > 0)
                                <small class="text-muted">*Tax: Rs. {{ number_format($product->tax, 2) }}</small>
                            @endif
                        </div>

                        <!-- Stock Status -->
                        <div class="stock-status">
                            @if($product->stock > 0)
                                <div class="in-stock">
                                    <i class="fas fa-check-circle me-2"></i>
                                    In Stock ({{ $product->stock }} available)
                                </div>
                            @else
                                <div class="out-of-stock">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Out of Stock
                                </div>
                            @endif
                        </div>

                                                <!-- Product Variations -->
                        @if($product->has_variations && $product->variations->count() > 0)
                            <div class="variations-section">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold">Product Options</h6>
                                    <button type="button" class="btn btn-sm btn-outline-secondary clear-all-variations" onclick="clearAllVariations()" style="display: none;">
                                        <i class="fas fa-times me-1"></i> Clear All
                                    </button>
                                </div>

                                @foreach($product->variations->groupBy('attribute.name') as $attributeName => $variations)
                                    <div class="variation-group" data-attribute="{{ $attributeName }}">
                                        <div class="variation-header">
                                            <label class="variation-label">{{ $attributeName }}</label>
                                            <button type="button" class="variation-clear"
                                                    onclick="clearVariation('{{ $attributeName }}')"
                                                    data-attribute="{{ $attributeName }}" disabled>
                                                Clear Selection
                                            </button>
                                        </div>
                                        <div class="variation-options">
                                            @foreach($variations as $variation)
                                                <div class="variation-option">
                                                    <input type="radio" name="variation_{{ $variation->attribute_id }}"
                                                           id="variation_{{ $variation->id }}" value="{{ $variation->id }}"
                                                           data-price="{{ $variation->final_price }}"
                                                           data-stock="{{ $variation->stock }}"
                                                           data-attribute="{{ $attributeName }}"
                                                           data-value="{{ $variation->attributeValue->value }}"
                                                           onchange="handleVariationChange(this)">
                                                    <label for="variation_{{ $variation->id }}" onclick="toggleVariation('{{ $variation->id }}')">
                                                        {{ $variation->attributeValue->value }}
                                                        @if($variation->final_price != $product->final_price)
                                                            <small class="d-block text-muted" style="font-size: 0.7rem;">
                                                                @if($variation->final_price > $product->final_price)
                                                                    +Rs. {{ number_format($variation->final_price - $product->final_price, 2) }}
                                                                @else
                                                                    -Rs. {{ number_format($product->final_price - $variation->final_price, 2) }}
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Selected Variations Summary -->
                                <div class="selected-variations" id="selectedVariations">
                                    <h6><i class="fas fa-check-circle me-2"></i>Selected Options:</h6>
                                    <div id="selectedVariationsList"></div>
                                </div>

                                <!-- Price Variation Info -->
                                <div class="price-variation-info" id="priceVariationInfo">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="priceVariationText"></span>
                                </div>
                            </div>
                        @endif

                        <!-- Quantity Section -->
                        @if($product->stock > 0)
                            <div class="quantity-section">
                                <label class="form-label fw-bold">Quantity:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        @if($product->stock > 0)
                            <div class="action-buttons">
                                <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                                <button type="button" class="btn btn-outline-primary buy-now-btn" data-product-id="{{ $product->id }}">
                                    <i class="fas fa-bolt me-2"></i>Buy Now
                                </button>
                            </div>
                        @endif

                        <!-- Secondary Actions -->
                        <div class="secondary-actions">
                            <a href="#" class="action-link add-to-wishlist" data-product-id="{{ $product->id }}">
                                <i class="fas fa-heart {{ isset($isInWishlist) && $isInWishlist ? 'text-danger' : '' }}"></i>
                                {{ isset($isInWishlist) && $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                            </a>
                            <a href="#" class="action-link" onclick="shareProduct()">
                                <i class="fas fa-share-alt"></i>
                                Share Product
                            </a>
                        </div>

                        <!-- Seller Information -->
                        @if($product->seller)
                            <div class="seller-info">
                                <h6 class="mb-3">Seller Information</h6>
                                <div class="seller-features">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-store"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h6>{{ $product->seller->sellerProfile->store_name ?? $product->seller->name }}</h6>
                                            <p>Sold and shipped by this seller</p>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h6>Free Shipping</h6>
                                            <p>Arrives within 1-2 business days</p>
                                        </div>
                                    </div>
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-undo"></i>
                                        </div>
                                        <div class="feature-text">
                                            <h6>Return Policy</h6>
                                            <p>15-day return policy available</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-tabs">
            <div class="tab-header">
                <button class="tab-btn active" onclick="showTab('description', this)">Description</button>
                <button class="tab-btn" onclick="showTab('specifications', this)">Specifications</button>
                <button class="tab-btn" onclick="showTab('reviews', this)">Reviews ({{ $product->reviews->count() }})</button>
            </div>

            <!-- Description Tab -->
            <div id="description" class="tab-content active">
                <h5 class="mb-3">Product Description</h5>
                <div class="product-description">
                    {!! nl2br(e($product->description)) !!}
                </div>

                @if($product->features)
                    <h6 class="mt-4 mb-3">Features</h6>
                    <ul class="list-unstyled">
                        @foreach(explode("\n", $product->features) as $feature)
                            @if(trim($feature))
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ trim($feature) }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                @if($product->benefits)
                    <h6 class="mt-4 mb-3">Benefits</h6>
                    <ul class="list-unstyled">
                        @foreach(explode("\n", $product->benefits) as $benefit)
                            @if(trim($benefit))
                                <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>{{ trim($benefit) }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Specifications Tab -->
            <div id="specifications" class="tab-content">
                <h5 class="mb-3">Product Specifications</h5>
                <table class="spec-table">
                    <tbody>
                        @if($product->sku)
                            <tr>
                                <th>SKU</th>
                                <td>{{ $product->sku }}</td>
                            </tr>
                        @endif
                        @if($product->category)
                            <tr>
                                <th>Category</th>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                        @endif
                        @if($product->subcategory)
                            <tr>
                                <th>Subcategory</th>
                                <td>{{ $product->subcategory->name }}</td>
                            </tr>
                        @endif
                        @if($product->childCategory)
                            <tr>
                                <th>Child Category</th>
                                <td>{{ $product->childCategory->name }}</td>
                            </tr>
                        @endif
                        @if($product->brand)
                            <tr>
                                <th>Brand</th>
                                <td>{{ $product->brand->name }}</td>
                            </tr>
                        @endif
                        @if($product->unit)
                            <tr>
                                <th>Unit</th>
                                <td>{{ $product->unit->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Stock Quantity</th>
                            <td>{{ $product->stock }}</td>
                        </tr>
                        @if($product->weight)
                            <tr>
                                <th>Weight</th>
                                <td>{{ $product->weight }}</td>
                            </tr>
                        @endif
                        @if($product->dimensions)
                            <tr>
                                <th>Dimensions</th>
                                <td>{{ $product->dimensions }}</td>
                            </tr>
                        @endif
                        @if($product->tax > 0)
                            <tr>
                                <th>Tax</th>
                                <td>Rs. {{ number_format($product->tax, 2) }}</td>
                            </tr>
                        @endif
                        {{-- <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr> --}}
                        {{-- @if($product->is_featured)
                            <tr>
                                <th>Featured</th>
                                <td><span class="badge bg-warning">Featured Product</span></td>
                            </tr>
                        @endif --}}
                    </tbody>
                </table>
            </div>

            <!-- Reviews Tab -->
            <div id="reviews" class="tab-content">
                <h5 class="mb-3">Customer Reviews</h5>
                @if($product->reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($product->reviews->take(10) as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div>
                                        <h6 class="mb-2">{{ $review->customer->name }}</h6>
                                        <div class="review-rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'star' : 'star empty' }}"></i>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->review)
                                    <p class="mb-0">{{ $review->review }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-star-o fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="related-products">
                <h3>Related Products</h3>
                <div class="product-grid">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="product-item">
                            <a href="{{ route('customer.product.detail', $relatedProduct->slug) }}">
                                <img src="{{ $relatedProduct->images->first()->image_url ?? asset('storage/product_images/default-product.jpg') }}"
                                     alt="{{ $relatedProduct->name }}">
                            </a>
                            <div class="product-item-content">
                                @if($relatedProduct->brand)
                                    <small class="text-muted">{{ $relatedProduct->brand->name }}</small>
                                @endif
                                <h5>
                                    <a href="{{ route('customer.product.detail', $relatedProduct->slug) }}" class="text-decoration-none">
                                        {{ Str::limit($relatedProduct->name, 50) }}
                                    </a>
                                </h5>
                                <div class="price">Rs. {{ number_format($relatedProduct->final_price, 2) }}</div>
                                @if($relatedProduct->discount > 0)
                                    <small class="text-muted"><del>Rs. {{ number_format($relatedProduct->price, 2) }}</del></small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image Gallery Functions
    window.changeMainImage = function(imageSrc, thumbnail) {
        $('#mainImage').attr('src', imageSrc);
        $('.thumbnail').removeClass('active');
        $(thumbnail).addClass('active');
    }

    // Quantity Controls
    window.decreaseQuantity = function() {
        const input = document.getElementById('quantity');
        const currentVal = parseInt(input.value);
        if (currentVal > 1) {
            input.value = currentVal - 1;
        }
    }

    window.increaseQuantity = function() {
        const input = document.getElementById('quantity');
        const currentVal = parseInt(input.value);
        const maxVal = parseInt(input.max);
        if (currentVal < maxVal) {
            input.value = currentVal + 1;
        }
    }

    // Tab Functions
    window.showTab = function(tabId, button) {
        // Hide all tab contents
        $('.tab-content').removeClass('active');
        // Remove active class from all buttons
        $('.tab-btn').removeClass('active');
        // Show selected tab
        $('#' + tabId).addClass('active');
        // Add active class to clicked button
        $(button).addClass('active');
    }

    // Share Product Function
    window.shareProduct = function() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: '{{ $product->short_description ?? $product->name }}',
                url: window.location.href,
            });
        } else {
            // Fallback - copy URL to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                toastr.success('Product URL copied to clipboard!');
            });
        }
    }

    // Variation Management Functions
    window.toggleVariation = function(variationId) {
        const input = document.getElementById('variation_' + variationId);
        const isCurrentlyChecked = input.checked;

        // If already checked, uncheck it
        if (isCurrentlyChecked) {
            input.checked = false;
            handleVariationChange(input);
        }
        // If not checked, the normal radio behavior will handle it
    };

    window.handleVariationChange = function(input) {
        const attributeName = input.dataset.attribute;
        const attributeValue = input.dataset.value;
        const price = parseFloat(input.dataset.price);
        const stock = parseInt(input.dataset.stock);
        const isChecked = input.checked;

        // Update clear button for this attribute
        const clearBtn = document.querySelector(`.variation-clear[data-attribute="${attributeName}"]`);
        clearBtn.disabled = !isChecked;

        // Update selected variations display
        updateSelectedVariations();

        // Update price and stock if any variation is selected
        updatePriceAndStock();

        // Update overall clear all button
        updateClearAllButton();
    };

    window.clearVariation = function(attributeName) {
        const inputs = document.querySelectorAll(`input[data-attribute="${attributeName}"]`);
        inputs.forEach(input => {
            input.checked = false;
        });

        // Update clear button
        const clearBtn = document.querySelector(`.variation-clear[data-attribute="${attributeName}"]`);
        clearBtn.disabled = true;

        // Update displays
        updateSelectedVariations();
        updatePriceAndStock();
        updateClearAllButton();
    };

    window.clearAllVariations = function() {
        const inputs = document.querySelectorAll('input[name^="variation_"]');
        inputs.forEach(input => {
            input.checked = false;
        });

        // Update all clear buttons
        document.querySelectorAll('.variation-clear').forEach(btn => {
            btn.disabled = true;
        });

        // Update displays
        updateSelectedVariations();
        updatePriceAndStock();
        updateClearAllButton();
    };

    function updateSelectedVariations() {
        const selectedContainer = document.getElementById('selectedVariations');
        const selectedList = document.getElementById('selectedVariationsList');
        const checkedInputs = document.querySelectorAll('input[name^="variation_"]:checked');

        if (checkedInputs.length > 0) {
            selectedContainer.classList.add('show');
            selectedList.innerHTML = '';

            checkedInputs.forEach(input => {
                const attributeName = input.dataset.attribute;
                const attributeValue = input.dataset.value;
                const item = document.createElement('span');
                item.className = 'selected-variation-item';
                item.innerHTML = `${attributeName}: ${attributeValue}`;
                selectedList.appendChild(item);
            });
        } else {
            selectedContainer.classList.remove('show');
        }
    }

    function updatePriceAndStock() {
        const checkedInputs = document.querySelectorAll('input[name^="variation_"]:checked');
        const originalPrice = {{ $product->final_price }};
        const originalStock = {{ $product->stock }};

        if (checkedInputs.length > 0) {
            // Use the highest price among selected variations
            let highestPrice = 0;
            let lowestStock = Infinity;

            checkedInputs.forEach(input => {
                const price = parseFloat(input.dataset.price);
                const stock = parseInt(input.dataset.stock);

                if (price > highestPrice) {
                    highestPrice = price;
                }

                if (stock < lowestStock) {
                    lowestStock = stock;
                }
            });

            // Update price display
            $('.current-price').text('Rs. ' + highestPrice.toLocaleString('en-IN', { minimumFractionDigits: 2 }));

            // Update stock (use minimum stock among selected variations)
            $('#quantity').attr('max', lowestStock);
            if ($('#quantity').val() > lowestStock) {
                $('#quantity').val(Math.min(1, lowestStock));
            }

            // Update stock status
            if (lowestStock > 0) {
                $('.stock-status').html('<div class="in-stock"><i class="fas fa-check-circle me-2"></i>In Stock (' + lowestStock + ' available)</div>');
            } else {
                $('.stock-status').html('<div class="out-of-stock"><i class="fas fa-times-circle me-2"></i>Out of Stock</div>');
            }

            // Show price variation info
            const priceInfo = document.getElementById('priceVariationInfo');
            const priceText = document.getElementById('priceVariationText');

            if (highestPrice !== originalPrice) {
                const difference = highestPrice - originalPrice;
                if (checkedInputs.length > 1) {
                    priceText.textContent = `Price set to Rs. ${highestPrice.toFixed(2)} (highest among selected options)`;
                } else {
                    priceText.textContent = difference > 0
                        ? `Price increased by Rs. ${difference.toFixed(2)} due to selected option`
                        : `Price decreased by Rs. ${Math.abs(difference).toFixed(2)} due to selected option`;
                }
                priceInfo.classList.add('show');
            } else {
                priceInfo.classList.remove('show');
            }
        } else {
            // Reset to original values
            $('.current-price').text('Rs. ' + originalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
            $('#quantity').attr('max', originalStock);

            // Reset stock status
            if (originalStock > 0) {
                $('.stock-status').html('<div class="in-stock"><i class="fas fa-check-circle me-2"></i>In Stock (' + originalStock + ' available)</div>');
            } else {
                $('.stock-status').html('<div class="out-of-stock"><i class="fas fa-times-circle me-2"></i>Out of Stock</div>');
            }

            // Hide price variation info
            document.getElementById('priceVariationInfo').classList.remove('show');
        }
    }

    function updateClearAllButton() {
        const clearAllBtn = document.querySelector('.clear-all-variations');
        const hasSelections = document.querySelectorAll('input[name^="variation_"]:checked').length > 0;

        if (hasSelections) {
            clearAllBtn.style.display = 'inline-block';
        } else {
            clearAllBtn.style.display = 'none';
        }
    }

    // Legacy variation change handler (keeping for backward compatibility)
    $('input[name^="variation_"]').change(function() {
        handleVariationChange(this);
    });

    // Add to Cart
    $('.add-to-cart-btn').click(function(e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const quantity = $('#quantity').val() || 1;

        // Collect all selected variations
        const selectedVariations = [];
        $('input[name^="variation_"]:checked').each(function() {
            selectedVariations.push($(this).val());
        });

        const requestData = {
            _token: '{{ csrf_token() }}',
            product_id: productId,
            quantity: quantity
        };

        // Send multiple variations if any are selected
        if (selectedVariations.length > 0) {
            if (selectedVariations.length === 1) {
                requestData.variation_id = selectedVariations[0];
            } else {
                requestData.variation_ids = selectedVariations;
            }
        }

        $.ajax({
            url: '{{ route("customer.cart.add") }}',
            method: 'POST',
            data: requestData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Product added to cart!');
                    // Update cart count if you have one
                    updateCartCount();
                } else {
                    toastr.error(response.message || 'Failed to add product to cart');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Please login to add products to cart';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                toastr.error(errorMessage);

                // Redirect to login if not authenticated
                if (xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                }
            }
        });
    });

    // Buy Now
    $('.buy-now-btn').click(function(e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const quantity = $('#quantity').val() || 1;

        // Collect all selected variations (same logic as add to cart)
        const selectedVariations = [];
        $('input[name^="variation_"]:checked').each(function() {
            selectedVariations.push($(this).val());
        });

        const requestData = {
            _token: '{{ csrf_token() }}',
            product_id: productId,
            quantity: quantity
        };

        // Send multiple variations if any are selected
        if (selectedVariations.length > 0) {
            if (selectedVariations.length === 1) {
                requestData.variation_id = selectedVariations[0];
            } else {
                requestData.variation_ids = selectedVariations;
            }
        }

        // Add to cart first, then redirect to checkout
        $.ajax({
            url: '{{ route("customer.cart.add") }}',
            method: 'POST',
            data: requestData,
            success: function(response) {
                if (response.success) {
                    // Redirect to checkout
                    window.location.href = '{{ route("customer.checkout") }}';
                } else {
                    toastr.error(response.message || 'Failed to add product to cart');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Please login to proceed';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                toastr.error(errorMessage);

                // Redirect to login if not authenticated
                if (xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                }
            }
        });
    });

    // Add to Wishlist
    $('.add-to-wishlist').click(function(e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const $link = $(this);
        const $heart = $link.find('.fas');

        $.ajax({
            url: '{{ route("customer.wishlist.toggle") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    if (response.added) {
                        $heart.addClass('text-danger');
                        $link.html('<i class="fas fa-heart text-danger"></i> Remove from Wishlist');
                        toastr.success('Product added to wishlist!');
                    } else {
                        $heart.removeClass('text-danger');
                        $link.html('<i class="fas fa-heart"></i> Add to Wishlist');
                        toastr.success('Product removed from wishlist!');
                    }
                }
            },
            error: function(xhr) {
                toastr.error('Please login to manage wishlist');

                // Redirect to login if not authenticated
                if (xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                }
            }
        });
    });

    function updateCartCount() {
        // Update cart count in header if you have one
        $.get('{{ route("customer.ajax.cart.count") }}', function(data) {
            if (data.count !== undefined) {
                $('.cart-count').text(data.count);
            }
        }).catch(function() {
            // Handle error silently
            console.log('Cart count update failed');
        });
    }
});
</script>
@endpush
