@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
/* Enhanced Product Listing Styles - Dashboard Theme */
.listing-container {
    background: #f8fafc;
    min-height: 100vh;
    padding: 2rem 0;
    position: relative;
}

.listing-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.02"><circle cx="30" cy="30" r="2"/></g></svg>') repeat;
    pointer-events: none;
}

/* Enhanced Breadcrumb - Dashboard Theme */
.breadcrumb-modern {
    background: var(--white);
    padding: 1.5rem 0;
    margin-bottom: 0;
    border-bottom: 1px solid var(--border-light);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

.simple-breadcrumb .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.simple-breadcrumb .breadcrumb-item a {
    color: var(--secondary-color);
    text-decoration: none;
}

.simple-breadcrumb .breadcrumb-item.active {
    color: var(--text-muted);
}

/* Enhanced Filters - Dashboard Theme */
.filter-sidebar {
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.filter-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
    overflow: hidden;
    transition: all 0.3s ease;
}

.filter-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--white);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #64748b, #475569);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-content {
    padding: 1.5rem;
}

.filter-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    transition: all 0.2s ease;
}

.filter-item:hover {
    padding-left: 5px;
}

.filter-item input[type="checkbox"],
.filter-item input[type="radio"] {
    margin-right: 12px;
    width: 16px;
    height: 16px;
    accent-color: var(--primary-color);
}

.filter-item label {
    font-size: 14px;
    color: var(--text-light);
    cursor: pointer;
    margin: 0;
    flex: 1;
}

.filter-item:hover label {
    color: var(--text-dark);
}

/* Category Groups */
.category-group {
    margin-bottom: 1rem;
}

.category-group:last-child {
    margin-bottom: 0;
}

.category-main {
    position: relative;
    font-weight: 600;
}

.expand-btn {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-light);
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.expand-btn:hover {
    color: var(--primary-color);
}

.expand-btn.expanded i {
    transform: rotate(180deg);
}

.subcategories {
    margin-left: 20px;
    margin-top: 8px;
    padding-left: 15px;
    border-left: 2px solid var(--border-light);
    display: none;
}

.subcategories.show {
    display: block;
}

.subcategory-item {
    font-size: 13px;
    padding: 4px 0;
}

.count {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: normal;
}

/* Color Filters */
.color-item {
    align-items: center;
}

.color-label {
    display: flex;
    align-items: center;
    gap: 8px;
}

.color-swatch {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--border-light);
    display: inline-block;
}

/* Size Filters */
.size-item {
    align-items: center;
}

.size-label {
    display: flex;
    align-items: center;
    gap: 8px;
}

.size-box {
    width: 30px;
    height: 30px;
    border: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-medium);
    transition: all 0.2s ease;
}

.size-filter:checked + .size-label .size-box {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* Price Range */
.price-inputs {
    margin-bottom: 1rem;
}

.price-range-info {
    text-align: center;
    margin-top: 0.5rem;
}

/* Enhanced Buttons */
.btn-apply-filter,
.btn-clear-filter {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-apply-filter {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: white;
    box-shadow: 0 4px 12px rgba(171, 207, 55, 0.3);
}

.btn-apply-filter:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(171, 207, 55, 0.4);
}

.btn-clear-filter {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-clear-filter:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

/* Enhanced Product Grid - Dashboard Theme */
.products-section {
    padding: 0;
}

.products-header {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.products-header h2 {
    color: var(--secondary-color);
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.results-count {
    color: var(--text-muted);
    font-size: 14px;
    margin-top: 5px;
}

.sort-dropdown {
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    font-size: 14px;
    background: white;
    color: var(--text-dark);
}

.sort-dropdown:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(171, 207, 55, 0.2);
}

/* Simple Product Cards */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
}

.product-card {
    transition: transform 0.2s ease;
}

.product-card:hover {
    transform: translateY(-2px);
}

.product-image {
    position: relative;
    margin-bottom: 15px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 36px;
    height: 36px;
    background: white;
    border: 1px solid var(--border-color);
    color: var(--text-light);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: var(--secondary-color);
    color: white;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
}

.product-info h3 {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.product-info h3 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-info h3 a:hover {
    color: var(--secondary-color);
}

.product-brand {
    font-size: 12px;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.product-price {
    margin-bottom: 15px;
}

.current-price {
    font-size: 18px;
    font-weight: 600;
    color: var(--secondary-color);
}

.original-price {
    font-size: 14px;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-left: 8px;
}

.product-buttons {
    display: flex;
    gap: 8px;
}

.btn-add-cart {
    flex: 1;
    padding: 10px 16px;
    background: var(--primary-color);
    color: white;
    border: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-add-cart:hover {
    background: #9ab832;
}

.btn-wishlist {
    width: 40px;
    height: 40px;
    background: transparent;
    color: var(--text-light);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-wishlist:hover {
    background: var(--secondary-color);
    color: white;
    border-color: var(--secondary-color);
}

/* Clean Pagination */
.pagination-wrapper {
    padding-top: 30px;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

/* No Products State */
.no-products {
    text-align: center;
    padding: 60px 20px;
}

.no-products-icon {
    font-size: 48px;
    color: var(--text-muted);
    margin-bottom: 20px;
}

.no-products h3 {
    color: var(--text-dark);
    margin-bottom: 10px;
}

.no-products p {
    color: var(--text-muted);
    margin-bottom: 20px;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-primary:hover {
    background: #9ab832;
}

.btn-secondary {
    background: var(--secondary-color);
    color: white;
    border: none;
    padding: 12px 24px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-secondary:hover {
    background: #076a75;
}

/* Clean Form Controls */
.form-control, .form-select {
    border: 1px solid var(--border-color);
    padding: 8px 12px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(171, 207, 55, 0.2);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .filter-sidebar {
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 30px;
        padding-bottom: 20px;
    }

    .products-header {
        text-align: center;
    }

    .products-header .row > div:last-child {
        margin-top: 15px;
    }
}
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-modern">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Products Section -->
<section class="listing-container">
    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Categories with Subcategories -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-tags"></i>
                            Categories
                        </h6>
                        <div class="filter-content">
                            @forelse($categories ?? [] as $category)
                                <div class="category-group">
                                    <div class="filter-item category-main">
                                        <input type="checkbox" id="cat_{{ $category->id }}" value="{{ $category->slug }}" class="category-filter">
                                        <label for="cat_{{ $category->id }}">
                                            {{ $category->name }}
                                            <span class="count">({{ $category->products_count }})</span>
                                        </label>
                                        @if($category->subcategories && $category->subcategories->count() > 0)
                                            <button type="button" class="expand-btn" data-target="#subcat_{{ $category->id }}">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if($category->subcategories && $category->subcategories->count() > 0)
                                        <div class="subcategories" id="subcat_{{ $category->id }}">
                                            @foreach($category->subcategories as $subcategory)
                                                <div class="filter-item subcategory-item">
                                                    <input type="checkbox" id="subcat_{{ $subcategory->id }}" value="{{ $subcategory->slug }}" class="subcategory-filter" data-category="{{ $category->slug }}">
                                                    <label for="subcat_{{ $subcategory->id }}">
                                                        {{ $subcategory->name }}
                                                        <span class="count">({{ $subcategory->products_count }})</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="filter-item">
                                    <label>No categories available</label>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-star"></i>
                            Brands
                        </h6>
                        <div class="filter-content">
                            @forelse($brands ?? [] as $brand)
                                <div class="filter-item">
                                    <input type="checkbox" id="brand_{{ $brand->id }}" value="{{ $brand->slug }}" class="brand-filter">
                                    <label for="brand_{{ $brand->id }}">
                                        {{ $brand->name }}
                                        <span class="count">({{ $brand->products_count }})</span>
                                    </label>
                                </div>
                            @empty
                                <div class="filter-item">
                                    <label>No brands available</label>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-palette"></i>
                            Colors
                        </h6>
                        <div class="filter-content">
                            @forelse($availableColors ?? [] as $color)
                                <div class="filter-item color-item">
                                    <input type="checkbox" id="color_{{ $loop->index }}" value="{{ $color }}" class="color-filter">
                                    <label for="color_{{ $loop->index }}" class="color-label">
                                        @php
                                            $colorMap = [
                                                'red' => '#ff0000', 'blue' => '#0000ff', 'green' => '#008000',
                                                'black' => '#000000', 'white' => '#ffffff', 'yellow' => '#ffff00',
                                                'orange' => '#ffa500', 'purple' => '#800080', 'pink' => '#ffc0cb',
                                                'brown' => '#8b4513', 'gray' => '#808080', 'grey' => '#808080',
                                                'navy' => '#000080', 'maroon' => '#800000', 'lime' => '#00ff00',
                                                'olive' => '#808000', 'aqua' => '#00ffff', 'teal' => '#008080',
                                                'silver' => '#c0c0c0', 'gold' => '#ffd700'
                                            ];
                                            $colorCode = $colorMap[strtolower($color)] ?? strtolower($color);
                                            $borderColor = strtolower($color) === 'white' ? '#ddd' : 'transparent';
                                        @endphp
                                        <span class="color-swatch" style="background-color: {{ $colorCode }}; border: 2px solid {{ $borderColor }};"></span>
                                        {{ $color }}
                                    </label>
                                </div>
                            @empty
                                <div class="filter-item">
                                    <label>No colors available</label>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Sizes -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-expand-arrows-alt"></i>
                            Sizes
                        </h6>
                        <div class="filter-content">
                            @forelse($availableSizes ?? [] as $size)
                                <div class="filter-item size-item">
                                    <input type="checkbox" id="size_{{ $loop->index }}" value="{{ $size }}" class="size-filter">
                                    <label for="size_{{ $loop->index }}" class="size-label">
                                        <span class="size-box">{{ $size }}</span>
                                    </label>
                                </div>
                            @empty
                                <div class="filter-item">
                                    <label>No sizes available</label>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-dollar-sign"></i>
                            Price Range
                        </h6>
                        <div class="filter-content">
                            <div class="price-inputs">
                                <div class="row">
                                    <div class="col-6">
                                        <input type="number" class="form-control" placeholder="Min" id="min_price"
                                               min="{{ $priceRange->min_price ?? 0 }}"
                                               max="{{ $priceRange->max_price ?? 10000 }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" placeholder="Max" id="max_price"
                                               min="{{ $priceRange->min_price ?? 0 }}"
                                               max="{{ $priceRange->max_price ?? 10000 }}">
                                    </div>
                                </div>
                                <div class="price-range-info">
                                    <small class="text-muted">
                                        Range: Rs {{ number_format($priceRange->min_price ?? 0) }} - Rs {{ number_format($priceRange->max_price ?? 10000) }}
                                    </small>
                                </div>
                            </div>
                            <button type="button" class="btn-apply-filter w-100" id="applyPriceFilter">
                                <i class="fas fa-filter"></i> Apply Filter
                            </button>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <div class="filter-section">
                        <h6 class="filter-title">
                            <i class="fas fa-eraser"></i>
                            Reset Filters
                        </h6>
                        <div class="filter-content">
                            <button type="button" class="btn-clear-filter w-100" id="clearFilters">
                                <i class="fas fa-undo"></i> Clear All Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-lg-9">
                <!-- Products Header -->
                <div class="products-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2>All Products</h2>
                            <div class="results-count">
                                @if(isset($products) && $products->total() > 0)
                                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                                @else
                                    No products found
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <select class="sort-dropdown" id="sortProducts">
                                <option value="newest">Newest First</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="name_asc">Name: A to Z</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="product-grid" id="productsGrid">
                    @forelse($products ?? [] as $product)
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                     alt="{{ $product->name }}">

                                @if($product->discount ?? 0 > 0)
                                    <div class="discount-badge">-{{ $product->discount }}%</div>
                                @endif

                                <div class="product-actions">
                                    <button class="action-btn add-to-wishlist" data-product-id="{{ $product->id }}">
                                        <i class="flaticon-heart"></i>
                                    </button>
                                    <a href="{{ route('customer.product.detail', $product->slug) }}" class="action-btn">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="product-info">
                                <div class="product-brand">{{ $product->brand->name ?? 'No Brand' }}</div>
                                <h3><a href="{{ route('customer.product.detail', $product->slug) }}">{{ $product->name }}</a></h3>

                                <div class="product-price">
                                    <span class="current-price">Rs {{ number_format($product->final_price ?? $product->price, 2) }}</span>
                                    @if(($product->discount ?? 0) > 0)
                                        <span class="original-price">Rs {{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>

                                <div class="product-stock" style="margin-top: 5px; font-size: 12px;">
                                    @if($product->stock > 0)
                                        <span style="color: #28a745;">✓ In Stock ({{ $product->stock }} available)</span>
                                    @else
                                        <span style="color: #dc3545;">✗ Out of Stock</span>
                                    @endif
                                </div>

                                <div class="product-buttons">
                                    @if($product->stock > 0)
                                        <button class="btn-add-cart add-to-cart" data-product-id="{{ $product->id }}">
                                            Add to Cart
                                        </button>
                                    @else
                                        <button class="btn-add-cart" disabled style="background-color: #ccc; cursor: not-allowed;">
                                            Out of Stock
                                        </button>
                                    @endif
                                    <button class="btn-wishlist add-to-wishlist" data-product-id="{{ $product->id }}">
                                        <i class="flaticon-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="no-products">
                                <div class="no-products-icon">
                                    <i class="fa fa-search"></i>
                                </div>
                                <h3>No products found</h3>
                                <p>Try adjusting your filters or search terms</p>
                                <button class="btn-primary" id="clearAllFilters">Clear All Filters</button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if(isset($products) && $products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->links() }}
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
    // Category expand/collapse functionality
    $('.expand-btn').on('click', function() {
        const target = $(this).data('target');
        const subcategories = $(target);
        const icon = $(this).find('i');

        subcategories.toggleClass('show');
        $(this).toggleClass('expanded');

        if (subcategories.hasClass('show')) {
            subcategories.slideDown(200);
        } else {
            subcategories.slideUp(200);
        }
    });

    // Filter functionality
    $('.category-filter, .subcategory-filter, .brand-filter, .color-filter, .size-filter').on('change', function() {
        applyFilters();
    });

    $('#applyPriceFilter').on('click', function() {
        applyFilters();
    });

    $('#clearFilters, #clearAllFilters').on('click', function() {
        // Clear all filter inputs
        $('.category-filter, .subcategory-filter, .brand-filter, .color-filter, .size-filter').prop('checked', false);
        $('#min_price, #max_price').val('');

        // Hide all subcategories
        $('.subcategories').removeClass('show').hide();
        $('.expand-btn').removeClass('expanded');

        applyFilters();
    });

    $('#sortProducts').on('change', function() {
        applyFilters();
    });

        // Add to cart functionality
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('product-id');
        const button = $(this);

        @guest
        showNotification('Please login to add items to cart', 'warning');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 1500);
        return;
        @endguest

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Adding...');

        $.ajax({
            url: '{{ route("customer.cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    button.html('<i class="fas fa-check"></i> Added');

                    // Show success notification
                    showNotification('Product added to cart!', 'success');

                    // Update cart count if exists
                    updateCartCount();

                    setTimeout(() => {
                        button.prop('disabled', false).html('Add to Cart');
                    }, 2000);
                } else {
                    button.prop('disabled', false).html('Add to Cart');
                    showNotification(response.message || 'Error adding to cart', 'error');
                }
            },
            error: function(xhr) {
                button.prop('disabled', false).html('Add to Cart');
                if (xhr.status === 401) {
                    showNotification('Please login to add items to cart', 'warning');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 1500);
                } else {
                    showNotification('Error adding to cart', 'error');
                }
            }
        });
    });

        // Add to wishlist functionality
    $('.add-to-wishlist').on('click', function() {
        const productId = $(this).data('product-id');
        const button = $(this);

        @guest
        showNotification('Please login to manage your wishlist', 'warning');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 1500);
        return;
        @endguest

        $.ajax({
            url: '{{ route("customer.wishlist.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    if (response.in_wishlist) {
                        button.addClass('active').html('<i class="fas fa-heart"></i>');
                        showNotification('Added to wishlist!', 'success');
                    } else {
                        button.removeClass('active').html('<i class="far fa-heart"></i>');
                        showNotification('Removed from wishlist!', 'info');
                    }
                } else {
                    showNotification(response.message || 'Error updating wishlist', 'error');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showNotification('Please login to manage your wishlist', 'warning');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 1500);
                } else {
                    showNotification('Error updating wishlist', 'error');
                }
            }
        });
    });

    function applyFilters() {
        // Collect filter data
        let categories = [];
        $('.category-filter:checked').each(function() {
            categories.push($(this).val());
        });

        let subcategories = [];
        $('.subcategory-filter:checked').each(function() {
            subcategories.push($(this).val());
        });

        let brands = [];
        $('.brand-filter:checked').each(function() {
            brands.push($(this).val());
        });

        let colors = [];
        $('.color-filter:checked').each(function() {
            colors.push($(this).val());
        });

        let sizes = [];
        $('.size-filter:checked').each(function() {
            sizes.push($(this).val());
        });

        let minPrice = $('#min_price').val();
        let maxPrice = $('#max_price').val();
        let sortBy = $('#sortProducts').val();

        // Build URL with filters
        let url = new URL(window.location.href);
        url.search = '';

        if (categories.length > 0) {
            url.searchParams.set('category', categories.join(','));
        }
        if (subcategories.length > 0) {
            url.searchParams.set('subcategory', subcategories.join(','));
        }
        if (brands.length > 0) {
            url.searchParams.set('brand', brands.join(','));
        }
        if (colors.length > 0) {
            url.searchParams.set('colors', colors.join(','));
        }
        if (sizes.length > 0) {
            url.searchParams.set('sizes', sizes.join(','));
        }
        if (minPrice) {
            url.searchParams.set('min_price', minPrice);
        }
        if (maxPrice) {
            url.searchParams.set('max_price', maxPrice);
        }
        if (sortBy) {
            url.searchParams.set('sort_by', sortBy);
        }

        // Redirect to filtered URL
        window.location.href = url.toString();
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = $(`
            <div class="notification notification-${type}">
                <div class="notification-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            </div>
        `);

        // Add to page
        $('body').append(notification);

        // Show notification
        notification.addClass('show');

        // Remove after 3 seconds
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    function updateCartCount() {
        // Update cart count if cart counter exists
        @auth
        $.get('{{ route("customer.ajax.cart.count") }}', function(response) {
            if (response.cart_count !== undefined) {
                $('.cart-count').text(response.cart_count);
            }
        });
        @endauth
    }

    // Initialize filters based on URL parameters
    function initializeFilters() {
        const urlParams = new URLSearchParams(window.location.search);

        // Set category filters
        if (urlParams.has('category')) {
            const categories = urlParams.get('category').split(',');
            categories.forEach(category => {
                $(`.category-filter[value="${category}"]`).prop('checked', true);
            });
        }

        // Set subcategory filters
        if (urlParams.has('subcategory')) {
            const subcategories = urlParams.get('subcategory').split(',');
            subcategories.forEach(subcategory => {
                $(`.subcategory-filter[value="${subcategory}"]`).prop('checked', true);
                // Show parent category
                const categorySlug = $(`.subcategory-filter[value="${subcategory}"]`).data('category');
                $(`#subcat_${categorySlug.replace('-', '_')}`).addClass('show').show();
                $(`.expand-btn[data-target="#subcat_${categorySlug.replace('-', '_')}"]`).addClass('expanded');
            });
        }

        // Set brand filters
        if (urlParams.has('brand')) {
            const brands = urlParams.get('brand').split(',');
            brands.forEach(brand => {
                $(`.brand-filter[value="${brand}"]`).prop('checked', true);
            });
        }

        // Set color filters
        if (urlParams.has('colors')) {
            const colors = urlParams.get('colors').split(',');
            colors.forEach(color => {
                $(`.color-filter[value="${color}"]`).prop('checked', true);
            });
        }

        // Set size filters
        if (urlParams.has('sizes')) {
            const sizes = urlParams.get('sizes').split(',');
            sizes.forEach(size => {
                $(`.size-filter[value="${size}"]`).prop('checked', true);
            });
        }

        // Set price range
        if (urlParams.has('min_price')) {
            $('#min_price').val(urlParams.get('min_price'));
        }
        if (urlParams.has('max_price')) {
            $('#max_price').val(urlParams.get('max_price'));
        }

        // Set sort
        if (urlParams.has('sort_by')) {
            $('#sortProducts').val(urlParams.get('sort_by'));
        }
    }

    // Initialize filters on page load
    initializeFilters();
});
</script>

<!-- Notification Styles -->
<style>
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.notification.show {
    opacity: 1;
    transform: translateX(0);
}

.notification-content {
    background: var(--white);
    border-radius: var(--radius-md);
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-left: 4px solid;
}

.notification-success .notification-content {
    border-left-color: var(--success);
}

.notification-error .notification-content {
    border-left-color: var(--danger);
}

.notification-info .notification-content {
    border-left-color: var(--primary-color);
}

.notification i {
    font-size: 1.25rem;
}

.notification-success i {
    color: var(--success);
}

.notification-error i {
    color: var(--danger);
}

.notification-info i {
    color: var(--primary-color);
}
</style>
@endpush
