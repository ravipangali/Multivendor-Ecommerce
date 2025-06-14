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
                        <li class="breadcrumb-item"><a href="{{ route('customer.sellers') }}">Sellers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $seller->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Seller Profile Header -->
<section class="seller-profile-header">
    <div class="container">
        <div class="seller-hero-card">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="seller-avatar-container">
                        @if($seller->profile_photo)
                            <img src="{{ asset('storage/' . $seller->profile_photo) }}"
                                 alt="{{ $seller->name }}"
                                 class="seller-avatar">
                        @else
                            <div class="seller-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div class="avatar-backdrop"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="seller-info">
                        <div class="seller-badge">
                            <i class="fas fa-store me-2"></i>Seller
                        </div>
                        <h1 class="seller-name">{{ $seller->name }}</h1>
                        @if($seller->sellerProfile && $seller->sellerProfile->business_name)
                            <h2 class="business-name">{{ $seller->sellerProfile->business_name }}</h2>
                        @endif
                        @if($seller->sellerProfile && $seller->sellerProfile->business_description)
                            <p class="seller-description">{{ $seller->sellerProfile->business_description }}</p>
                        @endif
                        <div class="seller-location">
                            @if($seller->sellerProfile && $seller->sellerProfile->business_address)
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $seller->sellerProfile->business_address }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="seller-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $totalProducts }}</span>
                            <span class="stat-label">{{ Str::plural('Product', $totalProducts) }}</span>
                        </div>
                        <div class="stat-item">
                            <div class="rating-display">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $averageRating ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">{{ number_format($averageRating, 1) }} ({{ $totalReviews }})</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $totalSales }}</span>
                            <span class="stat-label">Total Sales</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seller Products Section -->
<section class="seller-products-section">
    <div class="container">
        <div class="row">
            <!-- Enhanced Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <div class="sidebar-header">
                        <h5 class="sidebar-title">
                            <i class="fas fa-filter me-2"></i>
                            Filter Products
                        </h5>
                    </div>

                    <!-- Search Filter -->
                    <div class="filter-group">
                        <h6 class="filter-title">Search Products</h6>
                        <div class="filter-content">
                            <form action="{{ route('customer.seller.profile', $seller->id) }}" method="GET" id="searchForm">
                                <div class="search-input-group">
                                    <input type="text" name="search" class="search-input"
                                           placeholder="Search products..." value="{{ request('search') }}">
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    @if($categories->count() > 0)
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
                    @endif

                    <!-- Brands Filter -->
                    @if($brands->count() > 0)
                    <div class="filter-group">
                        <h6 class="filter-title">Brands</h6>
                        <div class="filter-content">
                            @foreach($brands as $brand)
                                <div class="filter-option">
                                    <input class="filter-checkbox brand-filter"
                                           type="checkbox"
                                           value="{{ $brand->slug }}"
                                           id="brand_{{ $brand->id }}"
                                           {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                    <label class="filter-label" for="brand_{{ $brand->id }}">
                                        <span class="checkbox-custom"></span>
                                        <span class="label-text">{{ $brand->name }}</span>
                                        <span class="item-count">({{ $brand->products_count ?? 0 }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Price Range Filter -->
                    @if($priceRange && $priceRange->min_price && $priceRange->max_price)
                    <div class="filter-group">
                        <h6 class="filter-title">Price Range</h6>
                        <div class="filter-content">
                            <form action="{{ route('customer.seller.profile', $seller->id) }}" method="GET" id="priceFilterForm">
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
                    @endif

                    <!-- Clear Filters -->
                    <div class="filter-actions">
                        <a href="{{ route('customer.seller.profile', $seller->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-undo me-2"></i>Clear All Filters
                        </a>
                    </div>
                </div>

                <!-- Recent Reviews -->
                @if($recentReviews->count() > 0)
                <div class="reviews-sidebar">
                    <div class="sidebar-header">
                        <h5 class="sidebar-title">
                            <i class="fas fa-star me-2"></i>
                            Recent Reviews
                        </h5>
                    </div>
                    <div class="reviews-list">
                        @foreach($recentReviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="customer-info">
                                        <strong>{{ $review->customer->name }}</strong>
                                        <div class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="review-date">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="review-text">{{ Str::limit($review->review, 100) }}</p>
                                <div class="review-product">
                                    <a href="{{ route('customer.product.detail', $review->product->slug) }}" class="product-link">
                                        {{ $review->product->name }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Products Area -->
            <div class="col-lg-9">
                <!-- Enhanced Results Header -->
                <div class="results-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="results-info">
                                <h4 class="results-title">{{ $seller->name }}'s Products</h4>
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
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
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
                                    <h4 class="empty-title">No products found</h4>
                                    <p class="empty-text">This seller doesn't have any products matching your filters</p>
                                    <a href="{{ route('customer.seller.profile', $seller->id) }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-left me-2"></i>View All Products
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
    $('.category-filter, .brand-filter').on('change', function() {
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

    // Add to cart functionality
    $('.add-to-cart').on('click', function() {
        const productId = $(this).data('product-id');
        addToCart(productId);
    });

    // Add to wishlist functionality
    $('.add-to-wishlist').on('click', function() {
        const productId = $(this).data('product-id');
        const btn = $(this);
        toggleWishlist(productId, btn);
    });

    function addToCart(productId) {
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
                    showNotification(response.message, 'success');
                    updateCartCount(response.cart_count);
                } else {
                    showNotification(response.message || 'Failed to add to cart', 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showNotification(response?.error || 'Failed to add to cart', 'error');
            }
        });
    }

    function toggleWishlist(productId, btn) {
        $.ajax({
            url: '{{ route("customer.wishlist.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification(response.message, 'success');
                    updateWishlistCount(response.wishlist_count);

                    if (response.in_wishlist) {
                        btn.addClass('active');
                    } else {
                        btn.removeClass('active');
                    }
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showNotification(response?.error || 'Failed to update wishlist', 'error');
            }
        });
    }

    function updateCartCount(count) {
        $('.cart-count').text(count);
    }

    function updateWishlistCount(count) {
        $('.wishlist-count').text(count);
    }
});
</script>
@endpush

@push('styles')
<style>
/* Seller Profile Specific Styles */
.seller-profile-header {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    padding: 3rem 0;
}

.seller-hero-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.seller-avatar-container {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.seller-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--white);
    box-shadow: var(--shadow-md);
    position: relative;
    z-index: 2;
}

.seller-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 3rem;
    border: 4px solid var(--white);
    box-shadow: var(--shadow-md);
    position: relative;
    z-index: 2;
}

.seller-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
}

.seller-name {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.business-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-color);
    margin: 0 0 1rem 0;
}

.seller-description {
    color: var(--text-medium);
    font-size: 1rem;
    line-height: 1.6;
    margin: 0 0 1rem 0;
}

.seller-location {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

.seller-stats {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    align-items: center;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
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

.rating-display {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stars {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.stars .fa-star {
    color: var(--border-medium);
    font-size: 1rem;
    transition: color 0.2s ease;
}

.stars .fa-star.active {
    color: var(--warning);
}

.rating-text {
    font-size: 0.875rem;
    color: var(--text-medium);
    font-weight: 500;
}

/* Continue with existing styles for filters, products, etc. */
</style>
@endpush
