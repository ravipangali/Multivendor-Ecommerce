@extends('saas_customer.saas_layout.saas_layout')

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
                        <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Brand Header -->
<section class="brand-header-modern">
    <div class="container">
        <div class="brand-hero-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="brand-content">
                        <div class="brand-badge">Brand</div>
                        <h1 class="brand-title">{{ $brand->name }}</h1>
                        @if($brand->description)
                            <p class="brand-description">{{ $brand->description }}</p>
                        @endif
                        <div class="brand-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $products->total() }}</span>
                                <span class="stat-label">{{ Str::plural('Product', $products->total()) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    @if($brand->logo)
                        <div class="brand-logo-container">
                            <img src="{{ asset('storage/' . $brand->logo) }}"
                                 alt="{{ $brand->name }}"
                                 class="brand-logo">
                            <div class="logo-backdrop"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brand Products -->
<section class="brand-products-section">
    <div class="container">
        <div class="row">
            <!-- Enhanced Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <div class="sidebar-header">
                        <h5 class="sidebar-title">
                            <i class="fas fa-filter me-2"></i>
                            Filters
                        </h5>
                    </div>

                    <!-- Categories Filter -->
                    <div class="filter-group">
                        <h6 class="filter-title">Categories</h6>
                        <div class="filter-content">
                            @foreach($categories as $category)
                                <div class="filter-option">
                                    <input class="filter-checkbox category-filter"
                                           type="checkbox"
                                           value="{{ $category->slug }}"
                                           id="category_{{ $category->id }}"
                                           {{ request('category') == $category->slug ? 'checked' : '' }}>
                                    <label class="filter-label" for="category_{{ $category->id }}">
                                        <span class="checkbox-custom"></span>
                                        <span class="label-text">{{ $category->name }}</span>
                                        <span class="item-count">({{ $category->products_count ?? 0 }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <h6 class="filter-title">Price Range</h6>
                        <div class="filter-content">
                            <form action="{{ route('customer.brand', $brand->slug) }}" method="GET" id="priceFilterForm">
                                <div class="price-inputs">
                                    <div class="input-group">
                                        <span class="input-prefix">Rs.</span>
                                        <input type="number" name="min_price" class="price-input"
                                               placeholder="Min" value="{{ request('min_price') }}" min="0">
                                    </div>
                                    <div class="price-separator">to</div>
                                    <div class="input-group">
                                        <span class="input-prefix">Rs.</span>
                                        <input type="number" name="max_price" class="price-input"
                                               placeholder="Max" value="{{ request('max_price') }}" min="0">
                                    </div>
                                </div>
                                <div class="price-range-info">
                                    <small>Range: Rs. {{ number_format($priceRange->min_price ?? 0) }} - Rs. {{ number_format($priceRange->max_price ?? 100000) }}</small>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-3">Apply Filter</button>
                            </form>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="filter-group">
                        <h6 class="filter-title">Customer Rating</h6>
                        <div class="filter-content">
                            @for($i = 5; $i >= 1; $i--)
                                <div class="filter-option rating-option">
                                    <input class="filter-radio rating-filter"
                                           type="radio"
                                           name="rating"
                                           value="{{ $i }}"
                                           id="rating_{{ $i }}"
                                           {{ request('rating') == $i ? 'checked' : '' }}>
                                    <label class="filter-label" for="rating_{{ $i }}">
                                        <span class="radio-custom"></span>
                                        <div class="rating-stars">
                                            @for($j = 1; $j <= 5; $j++)
                                                <i class="fa fa-star {{ $j <= $i ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-text">{{ $i }} stars & up</span>
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <div class="filter-actions">
                        <a href="{{ route('customer.brand', $brand->slug) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-undo me-2"></i>Clear All Filters
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-lg-9">
                <!-- Enhanced Results Header -->
                <div class="results-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="results-info">
                                <h4 class="results-title">{{ $brand->name }} Products</h4>
                                <p class="results-subtitle">
                                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                                    of {{ $products->total() }} results
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="sorting-controls">
                                <div class="sort-wrapper">
                                    <label for="sortBy" class="sort-label">Sort by:</label>
                                    <select name="sort_by" id="sortBy" class="sort-select">
                                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                        <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                        <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['category', 'min_price', 'max_price', 'rating']))
                    <div class="active-filters">
                        <div class="filters-header">
                            <span class="filters-label">Active Filters:</span>
                        </div>
                        <div class="filters-list">
                            @if(request('category'))
                                <div class="filter-tag">
                                    <span class="tag-icon"><i class="fas fa-tag"></i></span>
                                    <span class="tag-text">Category: {{ request('category') }}</span>
                                    <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="tag-remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <div class="filter-tag">
                                    <span class="tag-icon"><span class="rs-icon">Rs</span></span>
                                    <span class="tag-text">Price: Rs. {{ request('min_price', 0) }} - Rs. {{ request('max_price', '∞') }}</span>
                                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="tag-remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                            @if(request('rating'))
                                <div class="filter-tag">
                                    <span class="tag-icon"><i class="fas fa-star"></i></span>
                                    <span class="tag-text">Rating: {{ request('rating') }}+ stars</span>
                                    <a href="{{ request()->fullUrlWithQuery(['rating' => null]) }}" class="tag-remove">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Enhanced Products Grid -->
                <div class="products-grid">
                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6">
                                <div class="product-card">
                                    <div class="product-image-container">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}" class="product-link">
                                            <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                 alt="{{ $product->name }}" class="product-image">
                                        </a>

                                        <!-- Product Badges -->
                                        <div class="product-badges">
                                            @if($product->discount > 0)
                                                <span class="badge-discount">-{{ $product->discount }}%</span>
                                            @endif
                                            @if($product->stock <= 0)
                                                <span class="badge-stock">Out of Stock</span>
                                            @endif
                                        </div>

                                        <!-- Product Actions -->
                                        <div class="product-actions">
                                            <button class="action-btn wishlist-btn add-to-wishlist"
                                                    data-product-id="{{ $product->id }}"
                                                    title="Add to Wishlist">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <a href="{{ route('customer.product.detail', $product->slug) }}"
                                               class="action-btn view-btn"
                                               title="Quick View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-content">
                                        <div class="product-meta">
                                            <span class="product-brand">{{ $product->brand->name ?? 'Brand' }}</span>
                                            <div class="product-rating">
                                                @php
                                                    $avgRating = $product->reviews->avg('rating') ?? 0;
                                                @endphp
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star {{ $i <= $avgRating ? 'active' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-count">({{ $product->reviews->count() }})</span>
                                            </div>
                                        </div>

                                        <h6 class="product-title">
                                            <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                {{ Str::limit($product->name, 50) }}
                                            </a>
                                        </h6>

                                        <div class="product-price">
                                            <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                            @if($product->discount > 0)
                                                <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-footer">
                                            @if($product->stock > 0)
                                                <button class="btn btn-primary add-to-cart flex-grow-1"
                                                        data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn btn-secondary flex-grow-1" disabled>
                                                    <i class="fas fa-times me-2"></i>Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-products">
                                    <div class="empty-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h4 class="empty-title">No products found for {{ $brand->name }}</h4>
                                    <p class="empty-text">Try adjusting your filters or browse other brands</p>
                                    <a href="{{ route('customer.products') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i>Browse All Products
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                @if($products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->appends(request()->query())->links() }}
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
    $('.category-filter, .rating-filter').on('change', function() {
        applyFilters();
    });

    function applyFilters() {
        let currentUrl = new URL(window.location.href);

        // Category filters
        let selectedCategories = [];
        $('.category-filter:checked').each(function() {
            selectedCategories.push($(this).val());
        });

        if (selectedCategories.length > 0) {
            currentUrl.searchParams.set('category', selectedCategories[0]);
        } else {
            currentUrl.searchParams.delete('category');
        }

        // Rating filter
        let selectedRating = $('.rating-filter:checked').val();
        if (selectedRating) {
            currentUrl.searchParams.set('rating', selectedRating);
        } else {
            currentUrl.searchParams.delete('rating');
        }

        window.location.href = currentUrl.toString();
    }

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe product cards
    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endpush

@push('styles')
<style>
/* Breadcrumb Modern */
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

/* Brand Header Modern */
.brand-header-modern {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    padding: 3rem 0;
}

.brand-hero-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.brand-hero-card::before {
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

.brand-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.brand-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.brand-description {
    color: var(--text-medium);
    font-size: 1.125rem;
    line-height: 1.6;
    margin: 0 0 2rem 0;
}

.brand-stats {
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

.brand-logo-container {
    position: relative;
    display: inline-block;
}

.brand-logo {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: var(--radius-lg);
    border: 3px solid var(--white);
    box-shadow: var(--shadow-md);
    position: relative;
    z-index: 2;
}

.logo-backdrop {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140px;
    height: 140px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    opacity: 0.2;
    z-index: 1;
}

/* Brand Products Section */
.brand-products-section {
    padding: 3rem 0;
    background: var(--white);
}

/* Enhanced Filters Sidebar */
.filters-sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    position: sticky;
    top: 2rem;
}

.sidebar-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
}

.sidebar-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.filter-group {
    margin-bottom: 2rem;
}

.filter-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-option {
    position: relative;
}

.filter-checkbox,
.filter-radio {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.filter-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.filter-label:hover {
    background: var(--accent-color);
    border-color: var(--primary-color);
}

.checkbox-custom,
.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-medium);
    margin-right: 0.75rem;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.checkbox-custom {
    border-radius: var(--radius-sm);
}

.radio-custom {
    border-radius: 50%;
}

.filter-checkbox:checked ~ .filter-label .checkbox-custom,
.filter-radio:checked ~ .filter-label .radio-custom {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.filter-checkbox:checked ~ .filter-label .checkbox-custom::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: var(--white);
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.filter-radio:checked ~ .filter-label .radio-custom::after {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--white);
    display: block;
    margin: 4px;
}

.label-text {
    flex: 1;
    font-weight: 500;
    color: var(--text-dark);
}

.item-count {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Price Inputs */
.price-inputs {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.input-group {
    position: relative;
    flex: 1;
}

.input-prefix {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-medium);
    font-weight: 500;
    z-index: 1;
}

.price-input {
    width: 100%;
    padding: 0.75rem 0.75rem 0.75rem 2rem;
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.price-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
}

.price-separator {
    color: var(--text-medium);
    font-weight: 500;
    font-size: 0.875rem;
}

.price-range-info {
    color: var(--text-muted);
    font-size: 0.75rem;
}

/* Rating Options */
.rating-option .filter-label {
    padding: 1rem;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
    margin-right: 0.75rem;
}

.rating-stars .fa-star {
    color: var(--border-medium);
    font-size: 0.875rem;
    transition: color 0.2s ease;
}

.rating-stars .fa-star.active {
    color: var(--warning);
}

.rating-text {
    font-size: 0.875rem;
    color: var(--text-medium);
}

/* Filter Actions */
.filter-actions {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

/* Results Header */
.results-header {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
}

.results-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
}

.results-subtitle {
    color: var(--text-medium);
    margin: 0;
    font-size: 0.875rem;
}

.sorting-controls {
    display: flex;
    justify-content: flex-end;
}

.sort-wrapper {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sort-label {
    font-weight: 500;
    color: var(--text-dark);
    margin: 0;
    font-size: 0.875rem;
}

.sort-select {
    min-width: 200px;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    background: var(--white);
    cursor: pointer;
    transition: all 0.2s ease;
}

.sort-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
}

/* Active Filters */
.active-filters {
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
}

.filters-header {
    margin-bottom: 1rem;
}

.filters-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filters-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.filter-tag {
    background: var(--white);
    border: 1px solid var(--primary-color);
    border-radius: var(--radius-md);
    padding: 0.5rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.filter-tag:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.tag-icon {
    color: var(--primary-color);
    font-size: 0.75rem;
}

.tag-text {
    color: var(--text-dark);
    font-weight: 500;
}

.tag-remove {
    color: var(--text-muted);
    text-decoration: none;
    padding: 0.25rem;
    border-radius: 50%;
    transition: all 0.2s ease;
    margin-left: 0.25rem;
}

.tag-remove:hover {
    color: var(--danger);
    background: rgba(245, 101, 101, 0.1);
}

/* Enhanced Product Cards */
.product-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
    border-color: var(--primary-color);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
}

.product-link {
    display: block;
    width: 100%;
    height: 100%;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-badges {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 2;
}

.badge-discount {
    background: linear-gradient(135deg, var(--danger), #dc3545);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-stock {
    background: linear-gradient(135deg, var(--text-muted), #6b7280);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.product-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.9);
    color: var(--text-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    backdrop-filter: blur(10px);
}

.action-btn:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: scale(1.1);
}

.product-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.product-brand {
    color: var(--primary-color);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars {
    display: flex;
    gap: 0.125rem;
}

.stars .fa-star {
    color: var(--border-medium);
    font-size: 0.75rem;
    transition: color 0.2s ease;
}

.stars .fa-star.active {
    color: var(--warning);
}

.rating-count {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    line-height: 1.4;
    flex: 1;
}

.product-title a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-title a:hover {
    color: var(--primary-color);
}

.product-price {
    margin-bottom: 1.5rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
}

.original-price {
    font-size: 0.875rem;
    color: var(--text-muted);
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.product-footer {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
}

/* Empty Products */
.empty-products {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
}

.empty-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-color), #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: var(--text-muted);
    font-size: 2.5rem;
}

.empty-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
}

.empty-text {
    color: var(--text-medium);
    margin: 0 0 2rem 0;
    font-size: 1rem;
    line-height: 1.6;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 991px) {
    .filters-sidebar {
        position: static;
        margin-bottom: 2rem;
    }

    .brand-title {
        font-size: 2rem;
    }

    .brand-stats {
        gap: 1rem;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .brand-hero-card {
        padding: 2rem;
        text-align: center;
    }

    .brand-title {
        font-size: 1.75rem;
    }

    .results-header {
        text-align: center;
    }

    .sorting-controls {
        justify-content: center;
        margin-top: 1rem;
    }

    .sort-wrapper {
        flex-direction: column;
        gap: 0.5rem;
    }

    .filters-list {
        flex-direction: column;
    }

    .filter-tag {
        justify-content: space-between;
    }

    .price-inputs {
        flex-direction: column;
        gap: 0.75rem;
    }

    .price-separator {
        text-align: center;
    }
}
</style>
@endpush
