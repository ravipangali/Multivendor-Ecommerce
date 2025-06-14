@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
/* Modern Category Page Styles */
.breadcrumb-modern {
    background: linear-gradient(135deg, var(--white), var(--accent-color));
    padding: 1.5rem 0;
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

/* Category Header Modern */
.category-header-modern {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    padding: 3rem 0;
}

.category-hero-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.category-hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(171, 207, 55, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.category-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.category-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.category-description {
    color: var(--text-medium);
    font-size: 1.125rem;
    line-height: 1.6;
    margin: 0 0 2rem 0;
}

.category-stats {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-light);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.5rem;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--border-medium);
}

.category-image-container {
    position: relative;
    display: inline-block;
}

.category-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-md);
    position: relative;
    z-index: 2;
}

.image-backdrop {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140px;
    height: 140px;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    border-radius: 50%;
    opacity: 0.2;
    z-index: 1;
}

/* Clean Container */
.clean-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Simple Layout */
.filter-sidebar {
    padding: 20px 0;
}

.filter-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    padding: 5px 0;
}

.filter-checkbox input[type="checkbox"] {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    accent-color: var(--primary-color);
}

.filter-checkbox label {
    font-size: 13px;
    color: var(--text-light);
    cursor: pointer;
    margin: 0;
    flex: 1;
    transition: color 0.2s;
}

.filter-checkbox:hover label {
    color: var(--text-dark);
}

/* Plain Product Grid */
.product-item {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.product-item:last-child {
    border-bottom: none;
}

.product-image-container {
    position: relative;
    margin-bottom: 12px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.3s ease;
    border: none;
}

.product-image:hover {
    transform: scale(1.03);
}

.discount-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 4px 8px;
    font-size: 10px;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
}

.discount-flat {
    background: var(--primary-color);
}

.discount-percent {
    background: var(--secondary-color);
}

.product-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.action-btn {
    width: 32px;
    height: 32px;
    background: rgba(255,255,255,0.9);
    color: var(--text-light);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.product-info {
    text-align: left;
}

.product-meta {
    margin-bottom: 8px;
}

.product-category {
    font-size: 11px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 3px;
}

.product-brand {
    font-size: 11px;
    color: var(--primary-color);
    font-weight: 500;
    text-transform: uppercase;
}

.product-title {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 8px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: var(--secondary-color);
}

.product-price {
    margin-bottom: 12px;
}

.current-price {
    font-size: 16px;
    font-weight: 600;
    color: var(--secondary-color);
}

.original-price {
    font-size: 12px;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-left: 8px;
}

.product-actions-bottom {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-cart {
    flex: 1;
    padding: 8px 16px;
    background: var(--primary-color);
    color: white;
    border: none;
    font-size: 12px;
    cursor: pointer;
    text-transform: uppercase;
    font-weight: 500;
    transition: background 0.2s ease;
}

.btn-cart:hover {
    background: #9ab832;
}

.btn-wishlist {
    padding: 8px;
    background: transparent;
    color: var(--text-light);
    border: 1px solid var(--border-color);
    cursor: pointer;
    transition: all 0.2s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.btn-wishlist:hover {
    background: var(--secondary-color);
    color: white;
    border-color: var(--secondary-color);
}

/* Widget Styling */
.widget {
    padding: 0;
    margin-bottom: 25px;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 15px;
}

.widget:last-child {
    border-bottom: none;
}

.widget .title {
    font-size: 14px;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border-bottom: none;
    padding-bottom: 0;
}

.widget ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget li {
    margin-bottom: 8px;
}

.form-check {
    display: flex;
    align-items: center;
    padding: 5px 0;
}

.form-check-input {
    margin-right: 10px;
    width: 16px;
    height: 16px;
    accent-color: var(--primary-color);
}

.form-check-label {
    font-size: 13px;
    color: var(--text-light);
    cursor: pointer;
    transition: color 0.2s;
}

.form-check:hover .form-check-label {
    color: var(--text-dark);
}

.count {
    color: var(--text-muted);
    font-size: 11px;
}

.listing-header {
    background: transparent;
    padding: 15px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

/* Category Header */
.category-header {
    background: transparent;
    border-bottom: 1px solid var(--border-color);
}

.category-info h2 {
    color: var(--secondary-color);
    font-weight: 600;
    font-size: 24px;
}

.category-info p {
    color: var(--text-light);
    font-size: 14px;
}

.category-image img {
    border: 2px solid var(--primary-color);
}

/* Form Controls */
.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 0;
    padding: 6px 10px;
    font-size: 13px;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: none;
}

.btn-thm {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 12px;
    padding: 8px 16px;
}

.btn-thm:hover {
    background: #9ab832;
    border-color: #9ab832;
}

.btn-secondary {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
    color: white;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 12px;
    padding: 8px 16px;
}

.btn-secondary:hover {
    background: #076a75;
    border-color: #076a75;
}

/* Results Info */
.results-info h5 {
    font-size: 18px;
    color: var(--secondary-color);
    margin-bottom: 5px;
}

.results-info p {
    font-size: 13px;
    color: var(--text-light);
    margin: 0;
}

/* No Products */
.no-products {
    text-align: center;
    padding: 60px 20px;
}

.no-products h4 {
    font-size: 18px;
    color: var(--text-dark);
    margin-bottom: 10px;
}

.no-products p {
    font-size: 14px;
    color: var(--text-light);
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .filter-sidebar {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .product-item {
        margin-bottom: 25px;
        padding-bottom: 15px;
    }

    .product-image {
        height: 180px;
    }

    .product-title {
        font-size: 13px;
    }

    .current-price {
        font-size: 15px;
    }

    .category-info h2 {
        font-size: 20px;
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
                        <li class="breadcrumb-item"><a href="{{ route('customer.products') }}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Category Header -->
<section class="category-header-modern">
    <div class="container">
        <div class="category-hero-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="category-content">
                        <div class="category-badge">Category</div>
                        <h1 class="category-title">{{ $category->name }}</h1>
                        @if($category->description)
                            <p class="category-description">{{ $category->description }}</p>
                        @endif
                        <div class="category-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $products->total() }}</span>
                                <span class="stat-label">{{ Str::plural('Product', $products->total()) }}</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $category->subcategories->count() }}</span>
                                <span class="stat-label">{{ Str::plural('Subcategory', $category->subcategories->count()) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    @if($category->image)
                        <div class="category-image-container">
                            <img src="{{ $category->category_image_url }}"
                                 alt="{{ $category->name }}"
                                 class="category-image">
                            <div class="image-backdrop"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Products -->
<section class="category-products pt0 pb90">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Subcategory Filter -->
                    @if($category->subcategories->count() > 0)
                        <div class="widget">
                            <h6 class="title">Subcategories</h6>
                            <ul class="subcategory-list">
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input subcategory-filter"
                                               type="radio"
                                               name="subcategory"
                                               value=""
                                               id="subcategory_all"
                                               {{ !request('subcategory') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subcategory_all">
                                            All {{ $category->name }}
                                        </label>
                                    </div>
                                </li>
                                @foreach($category->subcategories as $subcategory)
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input subcategory-filter"
                                                   type="radio"
                                                   name="subcategory"
                                                   value="{{ $subcategory->slug }}"
                                                   id="subcategory_{{ $subcategory->id }}"
                                                   {{ request('subcategory') == $subcategory->slug ? 'checked' : '' }}>
                                            <label class="form-check-label" for="subcategory_{{ $subcategory->id }}">
                                                {{ $subcategory->name }}
                                                <span class="count">({{ $subcategory->products_count ?? 0 }})</span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Brand Filter -->
                    <div class="widget">
                        <h6 class="title">Brands</h6>
                        <ul class="brand-list">
                            @foreach($brands as $brand)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input brand-filter"
                                               type="checkbox"
                                               value="{{ $brand->slug }}"
                                               id="brand_{{ $brand->id }}"
                                               {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                        <label class="form-check-label" for="brand_{{ $brand->id }}">
                                            {{ $brand->name }}
                                            <span class="count">({{ $brand->products_count }})</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="widget">
                        <h6 class="title">Price Range</h6>
                        <form action="{{ route('customer.category', $category->slug) }}" method="GET" id="priceFilterForm">
                            @if(request('subcategory'))
                                <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
                            @endif
                            <div class="price-range mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" name="min_price" class="form-control form-control-sm"
                                               placeholder="Min" value="{{ request('min_price') }}"
                                               min="0">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" class="form-control form-control-sm"
                                               placeholder="Max" value="{{ request('max_price') }}"
                                               min="0">
                                    </div>
                                </div>
                                <div class="price-range-info mt-2">
                                    <small class="text-muted" style="font-size: 11px;">
                                        Range: Rs. {{ number_format($priceRange->min_price ?? 0) }} - Rs. {{ number_format($priceRange->max_price ?? 100000) }}
                                    </small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-thm w-100">Apply Filter</button>
                        </form>
                    </div>

                    <!-- Size Filter -->
                    @if($availableSizes->count() > 0)
                    <div class="widget">
                        <h6 class="title">Size</h6>
                        <ul class="size-list">
                            @foreach($availableSizes as $size)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input size-filter"
                                               type="checkbox"
                                               value="{{ $size }}"
                                               id="size_{{ $loop->index }}"
                                               {{ in_array($size, (array)request('size', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size_{{ $loop->index }}">
                                            {{ $size }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Color Filter -->
                    @if($availableColors->count() > 0)
                    <div class="widget">
                        <h6 class="title">Color</h6>
                        <ul class="color-list">
                            @foreach($availableColors as $color)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input color-filter"
                                               type="checkbox"
                                               value="{{ $color }}"
                                               id="color_{{ $loop->index }}"
                                               {{ in_array($color, (array)request('color', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="color_{{ $loop->index }}">
                                            {{ ucfirst($color) }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Clear Filters -->
                    <div class="widget">
                        <a href="{{ route('customer.category', $category->slug) }}" class="btn btn-secondary w-100">
                            Clear All Filters
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-lg-9">
                <!-- Results Header -->
                <div class="listing-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="results-info">
                                <h5>{{ $category->name }} Products</h5>
                                <p>Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sorting-options text-end">
                                <select name="sort_by" id="sortBy" class="form-select d-inline-block w-auto">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                    <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid">
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6">
                                <div class="product-item">
                                    <div class="product-image-container">
                                        <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                             alt="{{ $product->name }}" class="product-image">

                                        @php
                                            $originalPrice = $product->price;
                                            $discountedPrice = $originalPrice;
                                            $discountAmount = 0;
                                            $discountType = '';

                                            if ($product->discount > 0) {
                                                if ($product->discount_type == 'flat') {
                                                    $discountedPrice = $originalPrice - $product->discount;
                                                    $discountAmount = $product->discount;
                                                    $discountType = 'flat';
                                                } else {
                                                    $discountedPrice = $originalPrice - ($originalPrice * $product->discount / 100);
                                                    $discountAmount = $product->discount;
                                                    $discountType = 'percent';
                                                }
                                            }
                                        @endphp

                                        @if($product->discount > 0)
                                            @if($discountType == 'flat')
                                                <div class="discount-badge discount-flat">-Rs {{ number_format($discountAmount) }}</div>
                                            @else
                                                <div class="discount-badge discount-percent">-{{ $discountAmount }}%</div>
                                            @endif
                                        @endif

                                        <div class="product-actions">
                                            <button class="action-btn add-to-wishlist" data-product-id="{{ $product->id }}">
                                                <i class="flaticon-heart"></i>
                                            </button>
                                            <a href="{{ route('customer.product.detail', $product->slug) }}" class="action-btn">
                                                <i class="flaticon-eye"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-info">
                                        <div class="product-meta">
                                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                            <div class="product-brand">{{ $product->brand->name ?? 'No Brand' }}</div>
                                        </div>

                                        <h6 class="product-title">
                                            <a href="{{ route('customer.product.detail', $product->slug) }}">{{ $product->name }}</a>
                                        </h6>

                                        <div class="product-price">
                                            <span class="current-price">Rs {{ number_format($discountedPrice) }}</span>
                                            @if($product->discount > 0)
                                                <span class="original-price">Rs {{ number_format($originalPrice) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-stock" style="margin: 10px 0; font-size: 12px;">
                                            @if($product->stock > 0)
                                                <span style="color: #28a745;">✓ In Stock ({{ $product->stock }} available)</span>
                                            @else
                                                <span style="color: #dc3545;">✗ Out of Stock</span>
                                            @endif
                                        </div>

                                        <div class="product-actions-bottom">
                                            @if($product->stock > 0)
                                                <button class="btn-cart add-to-cart" data-product-id="{{ $product->id }}">
                                                    Add to Cart
                                                </button>
                                            @else
                                                <button class="btn-cart" disabled style="background-color: #ccc; cursor: not-allowed;">
                                                    Out of Stock
                                                </button>
                                            @endif
                                            <button class="btn-wishlist add-to-wishlist" data-product-id="{{ $product->id }}">
                                                <i class="flaticon-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="no-products">
                                    <div class="no-results-icon mb-4">
                                        <i class="fa fa-search" style="font-size: 3rem; color: var(--text-muted);"></i>
                                    </div>
                                    <h4>No products found in {{ $category->name }}</h4>
                                    <p>Try adjusting your filters or browse other categories</p>
                                    <a href="{{ route('customer.category', $category->slug) }}" class="btn btn-thm mt-3">Clear Filters</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center" style="border-top: 1px solid var(--border-color); padding-top: 20px;">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle sorting
    $('#sortBy').on('change', function() {
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('sort_by', this.value);
        window.location.href = currentUrl.toString();
    });

    // Handle filter changes
    $('.subcategory-filter, .brand-filter').on('change', function() {
        applyFilters();
    });

    function applyFilters() {
        let currentUrl = new URL(window.location.href);

        // Subcategory filter
        let selectedSubcategory = $('.subcategory-filter:checked').val();
        if (selectedSubcategory) {
            currentUrl.searchParams.set('subcategory', selectedSubcategory);
        } else {
            currentUrl.searchParams.delete('subcategory');
        }

        // Brand filters
        let selectedBrands = [];
        $('.brand-filter:checked').each(function() {
            selectedBrands.push($(this).val());
        });

        if (selectedBrands.length > 0) {
            currentUrl.searchParams.set('brand', selectedBrands[0]);
        } else {
            currentUrl.searchParams.delete('brand');
        }

        window.location.href = currentUrl.toString();
    }
});
</script>
@endpush
