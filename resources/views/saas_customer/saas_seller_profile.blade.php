@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<!-- Enhanced Breadcrumb -->
<section class="breadcrumb-enhanced">
    <div class="container">
        <div class="breadcrumb-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb-custom">
                    <li class="breadcrumb-item">
                        <a href="{{ route('customer.home') }}" class="breadcrumb-link">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('customer.sellers') }}" class="breadcrumb-link">
                            <i class="fas fa-store"></i>
                            <span>Sellers</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="breadcrumb-current">{{ $seller->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Enhanced Seller Profile Header -->
<section class="seller-profile-hero">
    <div class="hero-background">
        <div class="background-pattern"></div>
        <div class="background-gradient"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="seller-main-info">
                        <div class="seller-avatar-section">
                            <div class="avatar-container">
                                @if($seller->profile_photo)
                                    <img src="{{ asset('storage/' . $seller->profile_photo) }}"
                                         alt="{{ $seller->name }}"
                                         class="seller-avatar seller-profile-img">
                                @else
                                    <div class="seller-avatar-placeholder">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                @endif
                                <div class="verified-badge">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="seller-details">
                            <div class="seller-badges">
                                <span class="badge-primary">
                                    <i class="fas fa-store me-2"></i>Verified Seller
                                </span>
                                <span class="badge-success">
                                    <i class="fas fa-shield-check me-2"></i>Trusted
                                </span>
                            </div>

                            <h1 class="seller-title">{{ $seller->name }}</h1>

                            @if($seller->sellerProfile && $seller->sellerProfile->business_name)
                                <h2 class="business-title">{{ $seller->sellerProfile->business_name }}</h2>
                            @endif

                            @if($seller->sellerProfile && $seller->sellerProfile->business_description)
                                <p class="seller-description">{{ $seller->sellerProfile->business_description }}</p>
                            @endif

                            <div class="seller-meta">
                                @if($seller->sellerProfile && $seller->sellerProfile->business_address)
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $seller->sellerProfile->business_address }}</span>
                                    </div>
                                @endif
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Member since {{ $seller->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="seller-stats-panel">
                        <div class="stats-container">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-number">{{ number_format($totalProducts) }}</span>
                                    <span class="stat-label">{{ Str::plural('Product', $totalProducts) }}</span>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="rating-section">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $averageRating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="rating-info">
                                            <span class="rating-score">{{ number_format($averageRating, 1) }}</span>
                                            <span class="rating-count">({{ number_format($totalReviews) }} reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-number">{{ number_format($totalSales) }}</span>
                                    <span class="stat-label">Total Sales</span>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button class="btn btn-primary btn-contact">
                                <i class="fas fa-envelope me-2"></i>Contact Seller
                            </button>
                            <button class="btn btn-outline-primary btn-follow">
                                <i class="fas fa-heart me-2"></i>Follow Store
                            </button>
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
                <div class="filters-sidebar-enhanced">
                    <div class="sidebar-header">
                        <h5 class="sidebar-title">
                            <i class="fas fa-sliders-h me-2"></i>
                            Filter Products
                        </h5>
                        <div class="filter-count">
                            <span id="activeFilters">0</span> active
                        </div>
                    </div>

                    <!-- Enhanced Search Filter -->
                    <div class="filter-group-enhanced">
                        <div class="filter-header">
                            <h6 class="filter-title">
                                <i class="fas fa-search me-2"></i>
                                Search Products
                            </h6>
                        </div>
                        <div class="filter-content">
                            <form action="{{ route('customer.seller.profile', $seller->id) }}" method="GET" id="searchForm">
                                <div class="search-box-enhanced">
                                    <input type="text" name="search" class="search-input-enhanced"
                                           placeholder="Search by product name, description..." value="{{ request('search') }}">
                                    <button type="submit" class="search-btn-enhanced">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search'))
                                        <button type="button" class="search-clear" onclick="clearSearch()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Enhanced Categories Filter -->
                    @if($categories->count() > 0)
                    <div class="filter-group-enhanced">
                        <div class="filter-header collapsible" data-toggle="categories">
                            <h6 class="filter-title">
                                <i class="fas fa-tags me-2"></i>
                                Categories
                            </h6>
                            <i class="fas fa-chevron-down filter-toggle"></i>
                        </div>
                        <div class="filter-content" id="categories">
                            <div class="filter-options-enhanced">
                                @foreach($categories as $category)
                                    <div class="filter-option-enhanced">
                                        <input class="filter-checkbox-enhanced category-filter"
                                               type="checkbox"
                                               value="{{ $category->slug }}"
                                               id="category_{{ $category->id }}"
                                               {{ request('category') == $category->slug ? 'checked' : '' }}>
                                        <label class="filter-label-enhanced" for="category_{{ $category->id }}">
                                            <span class="checkbox-indicator"></span>
                                            <span class="label-content">
                                                <span class="label-text">{{ $category->name }}</span>
                                                <span class="item-count">{{ $category->products_count ?? 0 }}</span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Enhanced Brands Filter -->
                    @if($brands->count() > 0)
                    <div class="filter-group-enhanced">
                        <div class="filter-header collapsible" data-toggle="brands">
                            <h6 class="filter-title">
                                <i class="fas fa-tags me-2"></i>
                                Brands
                            </h6>
                            <i class="fas fa-chevron-down filter-toggle"></i>
                        </div>
                        <div class="filter-content" id="brands">
                            <div class="filter-options-enhanced">
                                @foreach($brands as $brand)
                                    <div class="filter-option-enhanced">
                                        <input class="filter-checkbox-enhanced brand-filter"
                                               type="checkbox"
                                               value="{{ $brand->slug }}"
                                               id="brand_{{ $brand->id }}"
                                               {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                        <label class="filter-label-enhanced" for="brand_{{ $brand->id }}">
                                            <span class="checkbox-indicator"></span>
                                            <span class="label-content">
                                                <span class="label-text">{{ $brand->name }}</span>
                                                <span class="item-count">{{ $brand->products_count ?? 0 }}</span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Enhanced Price Range Filter -->
                    @if($priceRange && $priceRange->min_price && $priceRange->max_price)
                    <div class="filter-group-enhanced">
                        <div class="filter-header collapsible" data-toggle="priceRange">
                            <h6 class="filter-title">
                                <span class="rs-icon me-2">Rs</span>
                                Price Range
                            </h6>
                            <i class="fas fa-chevron-down filter-toggle"></i>
                        </div>
                        <div class="filter-content" id="priceRange">
                            <form action="{{ route('customer.seller.profile', $seller->id) }}" method="GET" id="priceFilterForm">
                                <div class="price-inputs-enhanced">
                                    <div class="price-input-group">
                                        <label class="price-label">Minimum</label>
                                        <div class="price-input-wrapper">
                                            <span class="price-prefix">Rs.</span>
                                            <input type="number" name="min_price" class="price-input-enhanced"
                                                   placeholder="0" value="{{ request('min_price') }}" min="0">
                                        </div>
                                    </div>
                                    <div class="price-separator-enhanced">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                    <div class="price-input-group">
                                        <label class="price-label">Maximum</label>
                                        <div class="price-input-wrapper">
                                            <span class="price-prefix">Rs.</span>
                                            <input type="number" name="max_price" class="price-input-enhanced"
                                                   placeholder="999999" value="{{ request('max_price') }}" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="price-range-info-enhanced">
                                    <div class="range-display">
                                        <span class="range-label">Available Range:</span>
                                        <span class="range-values">Rs. {{ number_format($priceRange->min_price ?? 0) }} - Rs. {{ number_format($priceRange->max_price ?? 100000) }}</span>
                                    </div>
                                </div>
                                <button type="submit" class="btn-apply-price">
                                    <i class="fas fa-filter me-2"></i>Apply Price Filter
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Enhanced Filter Actions -->
                    <div class="filter-actions-enhanced">
                        <button type="button" onclick="clearAllFilters()" class="btn-clear-filters">
                            <i class="fas fa-undo me-2"></i>Clear All Filters
                        </button>
                    </div>
                </div>

                <!-- Enhanced Recent Reviews -->
                @if($recentReviews->count() > 0)
                <div class="reviews-sidebar-enhanced">
                    <div class="reviews-header">
                        <h5 class="reviews-title">
                            <i class="fas fa-star me-2"></i>
                            Recent Reviews
                        </h5>
                        <div class="reviews-count">{{ $recentReviews->count() }}</div>
                    </div>
                    <div class="reviews-list-enhanced">
                        @foreach($recentReviews as $review)
                            <div class="review-item-enhanced">
                                <div class="review-header-enhanced">
                                    <div class="customer-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $review->customer->name }}</div>
                                        <div class="review-rating-enhanced">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="review-date-enhanced">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                                <p class="review-text-enhanced">{{ Str::limit($review->review, 100) }}</p>
                                <div class="review-product-enhanced">
                                    <i class="fas fa-box me-1"></i>
                                    <a href="{{ route('customer.product.detail', $review->product->slug) }}" class="product-link-enhanced">
                                        {{ Str::limit($review->product->name, 30) }}
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
                <div class="results-header-modern">
                    <div class="results-header-content">
                        <div class="results-info-section">
                            <div class="results-title-group">
                                <h4 class="results-title">{{ $seller->name }}'s Products</h4>
                                <div class="results-badge">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $products->total() }} {{ Str::plural('Product', $products->total()) }}
                                </div>
                            </div>
                            <p class="results-subtitle">
                                @if($products->total() > 0)
                                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }}
                                    of {{ number_format($products->total()) }} results
                                @else
                                    No products found
                                @endif
                            </p>
                        </div>

                        <div class="sorting-controls-modern">

                            <div class="sort-wrapper-modern">
                                <label for="sortBy" class="sort-label">
                                    <i class="fas fa-sort-amount-down me-2"></i>Sort by:
                                </label>
                                <select name="sort_by" id="sortBy" class="sort-select-modern">
                                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>
                                        <i class="fas fa-clock"></i> Newest First
                                    </option>
                                    <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>
                                        Price: Low to High
                                    </option>
                                    <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>
                                        Price: High to Low
                                    </option>
                                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>
                                        Name: A to Z
                                    </option>
                                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>
                                        Name: Z to A
                                    </option>
                                    <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>
                                        Most Popular
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Products Grid -->
                <div class="products-grid-modern" id="productsContainer">
                    <div class="products-row">
                        @forelse($products as $product)
                            <div class="product-item-wrapper">
                                <div class="product-card-modern">
                                    <div class="product-image-wrapper">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}" class="product-image-link">
                                            <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                 alt="{{ $product->name }}" class="product-image-modern">
                                            <div class="image-overlay">
                                                <div class="overlay-content">
                                                    <i class="fas fa-eye"></i>
                                                    <span>Quick View</span>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Product Badges -->
                                        <div class="product-badges-modern">
                                            @if($product->discount > 0)
                                                <span class="badge-discount-modern">
                                                    <i class="fas fa-percentage me-1"></i>{{ $product->discount }}%
                                                </span>
                                            @endif
                                            @if($product->stock <= 0)
                                                <span class="badge-stock-modern">
                                                    <i class="fas fa-times me-1"></i>Out of Stock
                                                </span>
                                            @endif
                                            @if($product->created_at->isCurrentMonth())
                                                <span class="badge-new-modern">
                                                    <i class="fas fa-star me-1"></i>New
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Product Actions -->
                                        <div class="product-actions-modern">
                                            <button class="action-btn-modern wishlist-btn add-to-wishlist"
                                                    data-product-id="{{ $product->id }}"
                                                    title="Add to Wishlist">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                            <button class="action-btn-modern compare-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    title="Add to Compare">
                                                <i class="fas fa-balance-scale"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="product-content-modern">
                                        <div class="product-meta-modern">
                                            <span class="product-brand-modern">{{ $product->brand->name ?? 'Brand' }}</span>
                                            <div class="product-rating-modern">
                                                @php
                                                    $avgRating = $product->reviews->avg('rating') ?? 0;
                                                @endphp
                                                <div class="stars-modern">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star {{ $i <= $avgRating ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-count-modern">({{ $product->reviews->count() }})</span>
                                            </div>
                                        </div>

                                        <h6 class="product-title-modern">
                                            <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                {{ Str::limit($product->name, 60) }}
                                            </a>
                                        </h6>

                                        <div class="product-price-modern">
                                            <span class="current-price-modern">Rs. {{ number_format($product->final_price, 2) }}</span>
                                            @if($product->discount > 0)
                                                <span class="original-price-modern">Rs. {{ number_format($product->price, 2) }}</span>
                                                <span class="savings-modern">Save Rs. {{ number_format($product->price - $product->final_price, 2) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-features-modern">
                                            @if($product->stock > 0)
                                                <span class="stock-status in-stock">
                                                    <i class="fas fa-check-circle me-1"></i>In Stock
                                                </span>
                                            @else
                                                <span class="stock-status out-of-stock">
                                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                                </span>
                                            @endif

                                            @if($product->free_delivery ?? false)
                                                <span class="delivery-info">
                                                    <i class="fas fa-shipping-fast me-1"></i>Free Delivery
                                                </span>
                                            @endif
                                        </div>

                                        <div class="product-footer-modern">
                                            @if($product->stock > 0)
                                                <button class="btn-add-cart-modern add-to-cart"
                                                        data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn-add-cart-modern disabled" disabled>
                                                    <i class="fas fa-times me-2"></i>Out of Stock
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-products-modern">
                                <div class="empty-illustration">
                                    <div class="empty-icon-modern">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <div class="empty-content">
                                        <h4 class="empty-title-modern">No products found</h4>
                                        <p class="empty-text-modern">
                                            @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price']))
                                                No products match your current filters. Try adjusting your search criteria.
                                            @else
                                                This seller hasn't added any products yet.
                                            @endif
                                        </p>
                                        <div class="empty-actions">
                                            @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price']))
                                                <a href="{{ route('customer.seller.profile', $seller->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-undo me-2"></i>Clear Filters
                                                </a>
                                            @endif
                                            <a href="{{ route('customer.products') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-search me-2"></i>Browse All Products
                                            </a>
                                        </div>
                                    </div>
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
    // Enhanced filter functionality with real-time updates
    let filterTimeout;
    let isFiltering = false;

    // Initialize filter functionality
    initializeFilters();

    function initializeFilters() {
        // Update active filter count on page load
        updateFilterCount();

        // Initialize collapsible sections
        initializeCollapsibleSections();

        // Bind all filter events
        bindFilterEvents();

        // Initialize keyboard shortcuts
        initializeKeyboardShortcuts();
    }

    function bindFilterEvents() {
        // Real-time search functionality
        $('.search-input-enhanced').on('input', debounce(function() {
            updateFilterCount();
            applyFiltersInstantly();
        }, 300));

        // Category and brand filters with instant response
        $('.category-filter, .brand-filter').on('change', function() {
            const $label = $(this).next('.filter-label-enhanced');

            // Visual feedback
            if (this.checked) {
                $label.addClass('filter-selected');
                $(this).closest('.filter-option-enhanced').addClass('selected');
            } else {
                $label.removeClass('filter-selected');
                $(this).closest('.filter-option-enhanced').removeClass('selected');
            }

            updateFilterCount();
            applyFiltersInstantly(150); // Quick response for checkboxes
        });

        // Price range filters with validation
        $('.price-input-enhanced').on('input', debounce(function() {
            validatePriceInputs();
            updateFilterCount();
            applyFiltersInstantly(600); // Longer delay for price inputs
        }, 100));

        // Price input formatting on blur
        $('.price-input-enhanced').on('blur', function() {
            formatPriceInput(this);
        });

        // Sorting with smooth transition
        $('#sortBy').on('change', function() {
            showLoadingState();
            applyFiltersInstantly(100);
        });

        // Clear search functionality
        $('.search-clear').on('click', function() {
            $('.search-input-enhanced').val('').focus();
            updateFilterCount();
            applyFiltersInstantly(100);
        });
    }

    function applyFiltersInstantly(delay = 200) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            applyFilters();
        }, delay);
    }

    function applyFilters() {
        if (isFiltering) return;

        showLoadingState();

        const filters = getCurrentFilters();
        const url = buildFilterUrl(filters);

        // Use AJAX for seamless filtering
        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                updatePageContent(response);
                updateUrlWithoutReload(url);
                hideLoadingState();
                animateProductsIn();
            },
            error: function(xhr) {
                console.error('Filter error:', xhr);
                hideLoadingState();
                showFilterError();

                // Fallback to page reload
                setTimeout(() => {
                    window.location.href = url;
                }, 1000);
            }
        });
    }

    function getCurrentFilters() {
        const filters = {
            search: $('.search-input-enhanced').val().trim(),
            categories: [],
            brands: [],
            min_price: $('input[name="min_price"]').val(),
            max_price: $('input[name="max_price"]').val(),
            sort_by: $('#sortBy').val()
        };

        // Get selected categories
        $('.category-filter:checked').each(function() {
            filters.categories.push($(this).val());
        });

        // Get selected brands
        $('.brand-filter:checked').each(function() {
            filters.brands.push($(this).val());
        });

        return filters;
    }

    function buildFilterUrl(filters) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams();

        if (filters.search) params.append('search', filters.search);
        if (filters.categories.length) params.append('category', filters.categories.join(','));
        if (filters.brands.length) params.append('brand', filters.brands.join(','));
        if (filters.min_price) params.append('min_price', filters.min_price);
        if (filters.max_price) params.append('max_price', filters.max_price);
        if (filters.sort_by) params.append('sort_by', filters.sort_by);

        return `${url.pathname}${params.toString() ? '?' + params.toString() : ''}`;
    }

    function updatePageContent(html) {
        const $newContent = $(html);

        // Update products container
        const $newProducts = $newContent.find('.products-row');
        if ($newProducts.length) {
            $('.products-row').html($newProducts.html());
        }

        // Update results info
        const $newResultsInfo = $newContent.find('.results-subtitle');
        if ($newResultsInfo.length) {
            $('.results-subtitle').html($newResultsInfo.html());
        }

        // Update results badge
        const $newResultsBadge = $newContent.find('.results-badge');
        if ($newResultsBadge.length) {
            $('.results-badge').html($newResultsBadge.html());
        }

        // Re-bind cart and wishlist events for new products
        bindProductEvents();
    }

    function updateUrlWithoutReload(url) {
        window.history.pushState({}, '', url);
    }

    function showLoadingState() {
        if (isFiltering) return;
        isFiltering = true;

        // Add loading overlay
        if (!$('.filter-loading-overlay').length) {
            const $overlay = $(`
                <div class="filter-loading-overlay">
                    <div class="loading-spinner">
                        <div class="spinner-ring"></div>
                        <div class="loading-text">Filtering products...</div>
                    </div>
                </div>
            `);
            $('.products-row').css('position', 'relative').append($overlay);
        }

        // Dim existing products
        $('.product-card-enhanced').css({
            'opacity': '0.6',
            'pointer-events': 'none'
        });

        // Show loading state in filter sidebar
        $('.filters-sidebar-enhanced').addClass('filtering');
    }

    function hideLoadingState() {
        isFiltering = false;
        $('.filter-loading-overlay').remove();
        $('.product-card-enhanced').css({
            'opacity': '1',
            'pointer-events': 'auto'
        });
        $('.filters-sidebar-enhanced').removeClass('filtering');
    }

    function animateProductsIn() {
        $('.product-card-enhanced').each(function(index) {
            const $card = $(this);
            $card.css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            });

            setTimeout(() => {
                $card.css({
                    'transition': 'all 0.3s ease',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, index * 50);
        });
    }

    function updateFilterCount() {
        const filters = getCurrentFilters();
        let count = 0;

        count += filters.categories.length;
        count += filters.brands.length;
        if (filters.search) count++;
        if (filters.min_price || filters.max_price) count++;

        const $filterCount = $('#activeFilters');
        const currentCount = parseInt($filterCount.text());

        if (currentCount !== count) {
            $filterCount.css('transform', 'scale(1.2)').text(count);
            setTimeout(() => {
                $filterCount.css('transform', 'scale(1)');
            }, 200);
        }

        // Update filter count styling
        const $filterContainer = $filterCount.parent();
        if (count > 0) {
            $filterContainer.addClass('has-active-filters');
        } else {
            $filterContainer.removeClass('has-active-filters');
        }
    }

    function validatePriceInputs() {
        const minPrice = parseFloat($('input[name="min_price"]').val()) || 0;
        const maxPrice = parseFloat($('input[name="max_price"]').val()) || Infinity;

        $('.price-input-enhanced').each(function() {
            if (minPrice > maxPrice && maxPrice > 0) {
                $(this).css('border-color', '#e53e3e');
            } else {
                $(this).css('border-color', '');
            }
        });
    }

    function formatPriceInput(input) {
        if (input.value) {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                input.value = Math.max(0, value);
            }
        }
    }

    function initializeCollapsibleSections() {
        $('.collapsible').on('click', function() {
            const targetId = $(this).data('toggle');
            const $content = $('#' + targetId);
            const $toggle = $(this).find('.filter-toggle');

            const isOpen = $content.hasClass('open');

            if (isOpen) {
                $content.removeClass('open').css('max-height', '0px');
                $toggle.css('transform', 'rotate(0deg)');
                $(this).removeClass('expanded');
            } else {
                $content.addClass('open').css('max-height', $content[0].scrollHeight + 'px');
                $toggle.css('transform', 'rotate(180deg)');
                $(this).addClass('expanded');
            }
        });

        // Initialize all sections as open
        $('.filter-content').each(function() {
            $(this).addClass('open').css({
                'max-height': this.scrollHeight + 'px',
                'transition': 'all 0.3s ease'
            });
        });

        $('.collapsible').addClass('expanded');
        $('.filter-toggle').css({
            'transform': 'rotate(180deg)',
            'transition': 'transform 0.3s ease'
        });
    }

    function initializeKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                $('.search-input-enhanced').focus().select();
            }

            // Escape to clear all filters (when not in input)
            if (e.key === 'Escape' && !$(e.target).is('input')) {
                clearAllFilters();
            }
        });

        // Escape in search input to clear search
        $('.search-input-enhanced').on('keydown', function(e) {
            if (e.key === 'Escape') {
                $(this).val('');
                updateFilterCount();
                applyFiltersInstantly(100);
            }
        });
    }

    function clearAllFilters() {
        // Clear search
        $('.search-input-enhanced').val('');

        // Uncheck all checkboxes
        $('.category-filter, .brand-filter').prop('checked', false);
        $('.filter-label-enhanced').removeClass('filter-selected');
        $('.filter-option-enhanced').removeClass('selected');

        // Clear price inputs
        $('.price-input-enhanced').val('').css('border-color', '');

        // Reset sorting
        $('#sortBy').val('');

        updateFilterCount();
        applyFiltersInstantly(100);
    }

    function showFilterError() {
        const $error = $(`
            <div class="filter-error-message">
                <div class="error-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Failed to apply filters. Refreshing page...</span>
                </div>
            </div>
        `);

        $('.results-header-modern').append($error);

        setTimeout(() => {
            $error.remove();
        }, 3000);
    }

    function bindProductEvents() {
        // Add to cart functionality
        $('.add-to-cart').off('click').on('click', function() {
            const productId = $(this).data('product-id');
            addToCart(productId);
        });

        // Add to wishlist functionality
        $('.add-to-wishlist').off('click').on('click', function() {
            const productId = $(this).data('product-id');
            const btn = $(this);
            toggleWishlist(productId, btn);
        });
    }

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

    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Global functions for external access
    window.clearAllFilters = clearAllFilters;
    window.clearSearch = function() {
        $('.search-input-enhanced').val('').focus();
        updateFilterCount();
        applyFiltersInstantly(100);
    };

    // Initialize product events on page load
    bindProductEvents();
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Breadcrumb Styles */
.breadcrumb-enhanced {
    background: var(--white);
    border-bottom: 1px solid var(--border-light);
    padding: 1rem 0;
}

.breadcrumb-content {
    padding: 0;
}

.breadcrumb-custom {
    background: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-text);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
    font-weight: 500;
}

.breadcrumb-link:hover {
    background: var(--light-bg);
    color: var(--primary-color);
    transform: translateY(-1px);
}

.breadcrumb-current {
    color: var(--dark-text);
    font-weight: 600;
    padding: 0.5rem 1rem;
}

/* Enhanced Seller Profile Hero */
.seller-profile-hero {
    position: relative;
    background: var(--white);
    padding: 4rem 0;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.background-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        radial-gradient(circle at 25% 25%, rgba(103, 126, 234, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(244, 143, 177, 0.1) 0%, transparent 50%);
}

.background-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(103, 126, 234, 0.05) 0%, rgba(244, 143, 177, 0.05) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
}

.seller-main-info {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}

.seller-avatar-section {
    flex-shrink: 0;
}

.avatar-container {
    position: relative;
    display: inline-block;
}

.seller-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid var(--white);
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
}

.seller-avatar-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 3.5rem;
    border: 6px solid var(--white);
    box-shadow: var(--shadow-lg);
}

.verified-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--success-color);
    color: var(--white);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--white);
    box-shadow: var(--shadow);
}

.seller-details {
    flex: 1;
}

.seller-badges {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge-primary, .badge-success {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.badge-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: var(--white);
}

.badge-success {
    background: var(--success-color);
    color: var(--white);
}

.seller-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark-text);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.business-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
    margin: 0 0 1rem 0;
}

.seller-description {
    color: var(--gray-text);
    font-size: 1.1rem;
    line-height: 1.6;
    margin: 0 0 1.5rem 0;
}

.seller-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--gray-text);
    font-size: 0.95rem;
}

.meta-item i {
    color: var(--primary-color);
    width: 16px;
}

/* Enhanced Stats Panel */
.seller-stats-panel {
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    border: 1px solid var(--border-light);
    position: sticky;
    top: 2rem;
}

.stats-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--light-bg);
    border-radius: 8px;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-content {
    flex: 1;
}

.stat-number {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--dark-text);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-text);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.rating-section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
}

.rating-stars .fa-star {
    color: var(--border-color);
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.rating-stars .fa-star.filled {
    color: var(--warning-color);
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-score {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-text);
}

.rating-count {
    font-size: 0.875rem;
    color: var(--gray-text);
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-contact, .btn-follow {
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-contact {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: var(--white);
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.btn-follow {
    background: transparent;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-follow:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
}

/* Enhanced Filter Sidebar */
.filters-sidebar-enhanced {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    position: sticky;
    top: 2rem;
    overflow: hidden;
    margin-bottom: 2rem;
}

.sidebar-header {
    background: linear-gradient(135deg, var(--accent-color), #ffffff);
    padding: 1.75rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.sidebar-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark-text);
    margin: 0;
    display: flex;
    align-items: center;
}

.filter-count {
    background: var(--primary-color);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.filter-group-enhanced {
    border-bottom: 1px solid var(--border-light);
}

.filter-group-enhanced:last-child {
    border-bottom: none;
}

.filter-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-header:hover {
    background: var(--light-bg);
}

.filter-header.collapsible {
    user-select: none;
}

.filter-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark-text);
    margin: 0;
    display: flex;
    align-items: center;
}

.filter-toggle {
    color: var(--gray-text);
    transition: all 0.3s ease;
}

.filter-content {
    padding: 0 1.5rem 1.5rem;
}

.search-box-enhanced {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input-enhanced {
    width: 100%;
    padding: 0.875rem 3rem 0.875rem 1rem;
    border: 2px solid var(--border-light);
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--white);
}

.search-input-enhanced:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.search-btn-enhanced {
    position: absolute;
    right: 0.5rem;
    background: var(--primary-color);
    color: var(--white);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn-enhanced:hover {
    background: #0056b3;
    transform: scale(1.05);
}

.search-clear {
    position: absolute;
    right: 3rem;
    background: var(--danger-color);
    color: var(--white);
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
}

.filter-options-enhanced {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-option-enhanced {
    position: relative;
}

.filter-checkbox-enhanced {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.filter-label-enhanced {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: var(--white);
    border: 1px solid var(--border-light);
}

.filter-label-enhanced:hover {
    background: var(--light-bg);
    transform: translateX(4px);
}

.checkbox-indicator {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.filter-checkbox-enhanced:checked + .filter-label-enhanced .checkbox-indicator {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.filter-checkbox-enhanced:checked + .filter-label-enhanced .checkbox-indicator::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: var(--white);
    font-size: 0.875rem;
    font-weight: 700;
}

.label-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.label-text {
    font-weight: 500;
    color: var(--dark-text);
}

.item-count {
    background: var(--light-bg);
    color: var(--gray-text);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Define CSS variables - matching layout theme */
:root {
    --primary-color: #abcf37;
    --primary-light: #c4e04a;
    --primary-dark: #8fb12d;
    --secondary-color: #09717e;
    --secondary-light: #1a8d9a;
    --secondary-dark: #075a64;
    --accent-color: #f8fafc;
    --text-dark: #1a202c;
    --text-medium: #4a5568;
    --text-light: #718096;
    --text-muted: #a0aec0;
    --border-light: #e2e8f0;
    --border-medium: #cbd5e0;
    --white: #ffffff;
    --success: #48bb78;
    --warning: #ed8936;
    --danger: #f56565;
    --info: #4299e1;
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-display: 'Playfair Display', serif;
}

/* Responsive Design */
@media (max-width: 768px) {
    .seller-profile-hero {
        padding: 2rem 0;
    }

    .seller-main-info {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .seller-title {
        font-size: 2rem;
    }

    .stats-container {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .stat-card {
        flex: 1;
        min-width: 120px;
    }

    .action-buttons {
        flex-direction: row;
    }

    .filters-sidebar-enhanced {
        position: static;
        margin-bottom: 2rem;
    }
}

/* Additional JavaScript functionality */
function clearSearch() {
    document.querySelector('.search-input-enhanced').value = '';
    document.getElementById('searchForm').submit();
}

// Filter toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterHeaders = document.querySelectorAll('.filter-header.collapsible');

    filterHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const targetId = this.getAttribute('data-toggle');
            const target = document.getElementById(targetId);
            const toggle = this.querySelector('.filter-toggle');

            if (target.style.display === 'none') {
                target.style.display = 'block';
                toggle.style.transform = 'rotate(0deg)';
            } else {
                target.style.display = 'none';
                toggle.style.transform = 'rotate(-90deg)';
            }
        });
    });

    // Update active filter count
    function updateFilterCount() {
        const activeFilters = document.querySelectorAll('.filter-checkbox-enhanced:checked').length;
        document.getElementById('activeFilters').textContent = activeFilters;
    }

    // Listen for filter changes
    document.querySelectorAll('.filter-checkbox-enhanced').forEach(checkbox => {
        checkbox.addEventListener('change', updateFilterCount);
    });

    // Initial count update
    updateFilterCount();
});

/* Enhanced Results Header Styles */
.results-header-modern {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.results-header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.results-info-section {
    flex: 1;
}

.results-title-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.results-title {
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.results-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.results-subtitle {
    color: var(--text-medium);
    font-size: 1rem;
    margin: 0;
    font-weight: 500;
}

.sorting-controls-modern {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.view-toggle-group {
    display: flex;
    gap: 0.25rem;
    background: var(--accent-color);
    padding: 0.25rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
}

.view-toggle {
    padding: 0.75rem;
    border: none;
    background: transparent;
    color: var(--text-medium);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.view-toggle.active,
.view-toggle:hover {
    background: var(--white);
    color: var(--primary-color);
    box-shadow: var(--shadow-sm);
}

.sort-wrapper-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sort-label {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    margin: 0;
}

.sort-select-modern {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    background: var(--white);
    color: var(--text-dark);
    font-size: 0.875rem;
    font-weight: 500;
    min-width: 200px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.sort-select-modern:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
}

/* Enhanced Products Grid Styles */
.products-grid-modern {
    margin-bottom: 3rem;
}

.products-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.product-item-wrapper {
    height: 100%;
}

.product-card-modern {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.product-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.product-image-wrapper {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: var(--accent-color);
}

.product-image-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
}

.product-image-modern {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(171, 207, 55, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card-modern:hover .image-overlay {
    opacity: 1;
}

.overlay-content {
    color: var(--white);
    text-align: center;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.overlay-content i {
    font-size: 1.5rem;
}

.product-badges-modern {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.badge-discount-modern,
.badge-stock-modern,
.badge-new-modern {
    padding: 0.375rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    box-shadow: var(--shadow-sm);
}

.badge-discount-modern {
    background: var(--danger);
    color: var(--white);
}

.badge-stock-modern {
    background: var(--text-medium);
    color: var(--white);
}

.badge-new-modern {
    background: var(--warning);
    color: var(--white);
}

.product-actions-modern {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s ease;
}

.product-card-modern:hover .product-actions-modern {
    opacity: 1;
    transform: translateX(0);
}

.action-btn-modern {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: var(--white);
    color: var(--text-medium);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.action-btn-modern:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: scale(1.1);
}

.action-btn-modern.active {
    background: var(--danger);
    color: var(--white);
}

.product-content-modern {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-meta-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.product-brand-modern {
    background: var(--accent-color);
    color: var(--secondary-color);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-rating-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars-modern {
    display: flex;
    gap: 0.125rem;
}

.stars-modern .fa-star {
    color: var(--border-medium);
    font-size: 0.875rem;
    transition: color 0.2s ease;
}

.stars-modern .fa-star.filled {
    color: var(--warning);
}

.rating-count-modern {
    font-size: 0.75rem;
    color: var(--text-light);
    font-weight: 500;
}

.product-title-modern {
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.4;
    margin: 0 0 1rem 0;
    flex: 1;
}

.product-title-modern a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-title-modern a:hover {
    color: var(--primary-color);
}

.product-price-modern {
    margin-bottom: 1rem;
}

.current-price-modern {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
    display: block;
    margin-bottom: 0.25rem;
}

.original-price-modern {
    font-size: 0.875rem;
    color: var(--text-light);
    text-decoration: line-through;
    margin-right: 0.5rem;
}

.savings-modern {
    font-size: 0.75rem;
    color: var(--success);
    font-weight: 600;
    background: rgba(72, 187, 120, 0.1);
    padding: 0.125rem 0.5rem;
    border-radius: var(--radius-sm);
    display: inline-block;
}

.product-features-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stock-status,
.delivery-info {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
}

.stock-status.in-stock {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success);
}

.stock-status.out-of-stock {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger);
}

.delivery-info {
    background: rgba(66, 153, 225, 0.1);
    color: var(--info);
}

.product-footer-modern {
    margin-top: auto;
}

.btn-add-cart-modern {
    width: 100%;
    padding: 0.875rem 1rem;
    border: none;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-add-cart-modern:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-add-cart-modern.disabled {
    background: var(--text-light);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Empty State Styles */
.empty-products-modern {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.empty-illustration {
    text-align: center;
    max-width: 500px;
}

.empty-icon-modern {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 3rem;
}

.empty-title-modern {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-text-modern {
    color: var(--text-medium);
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Update existing styles to use theme colors */
.badge-primary {
    background: var(--primary-color) !important;
}

.badge-success {
    background: var(--success) !important;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
    border-color: var(--primary-color) !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
    border-color: var(--primary-dark) !important;
}

.btn-outline-primary {
    border-color: var(--primary-color) !important;
    color: var(--primary-color) !important;
}

.btn-outline-primary:hover {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--white) !important;
}

/* Responsive Design Updates */
@media (max-width: 768px) {
    .results-header-content {
        flex-direction: column;
        gap: 1.5rem;
    }

    .sorting-controls-modern {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }

    .view-toggle-group {
        align-self: flex-start;
    }

    .sort-wrapper-modern {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }

    .sort-select-modern {
        min-width: 100%;
    }

    .products-row {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .empty-actions {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 576px) {
    .results-title-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .results-title {
        font-size: 1.5rem;
    }

    .products-row {
        grid-template-columns: 1fr;
    }
}

/* Enhanced Price Range Styles */
.price-inputs-enhanced {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.price-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.price-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.price-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.price-prefix {
    position: absolute;
    left: 1rem;
    color: var(--text-medium);
    font-weight: 600;
    font-size: 0.875rem;
    z-index: 1;
}

.price-input-enhanced {
    width: 100%;
    padding: 0.875rem 1rem 0.875rem 3rem;
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    background: var(--white);
    color: var(--text-dark);
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.price-input-enhanced:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    background: rgba(171, 207, 55, 0.02);
}

.price-separator-enhanced {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0.5rem 0;
}

.price-range-info-enhanced {
    background: var(--accent-color);
    padding: 1rem;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-light);
    margin-bottom: 1.5rem;
}

.range-display {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    text-align: center;
}

.range-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.range-values {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--secondary-color);
}

.btn-apply-price {
    width: 100%;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-apply-price:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Enhanced Filter Actions */
.filter-actions-enhanced {
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
    background: var(--accent-color);
}

.btn-clear-filters {
    width: 100%;
    padding: 0.875rem 1.5rem;
    border: 2px solid var(--text-medium);
    border-radius: var(--radius-md);
    background: transparent;
    color: var(--text-medium);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-clear-filters:hover {
    background: var(--text-medium);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

/* Enhanced Reviews Sidebar */
.reviews-sidebar-enhanced {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    overflow: hidden;
    margin-top: 2rem;
}

.reviews-header {
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.reviews-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--white);
    margin: 0;
    display: flex;
    align-items: center;
}

.reviews-count {
    background: rgba(255, 255, 255, 0.2);
    color: var(--white);
    padding: 0.375rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.reviews-list-enhanced {
    max-height: 400px;
    overflow-y: auto;
}

.review-item-enhanced {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    transition: all 0.2s ease;
}

.review-item-enhanced:last-child {
    border-bottom: none;
}

.review-item-enhanced:hover {
    background: var(--accent-color);
}

.review-header-enhanced {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 1rem;
    flex-shrink: 0;
}

.customer-details {
    flex: 1;
}

.customer-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.review-rating-enhanced {
    display: flex;
    gap: 0.125rem;
}

.review-rating-enhanced .fa-star {
    color: var(--border-medium);
    font-size: 0.75rem;
}

.review-rating-enhanced .fa-star.filled {
    color: var(--warning);
}

.review-date-enhanced {
    font-size: 0.75rem;
    color: var(--text-light);
    font-weight: 500;
}

.review-text-enhanced {
    color: var(--text-medium);
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0 0 1rem 0;
    font-style: italic;
}

.review-product-enhanced {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: var(--accent-color);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border-light);
}

.product-link-enhanced {
    color: var(--secondary-color);
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 600;
    transition: color 0.2s ease;
}

.product-link-enhanced:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

/* Enhanced Filter Interactivity */
.filter-selected {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
    color: var(--white) !important;
    transform: translateX(2px);
}

.filter-option-enhanced.selected {
    background: rgba(171, 207, 55, 0.05);
    border-radius: var(--radius-sm);
}

.search-active {
    transform: scale(1.02);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.15);
}

.has-active-filters {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: 15px;
    padding: 0.25rem 0.75rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Loading States */
.filter-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    backdrop-filter: blur(2px);
}

.loading-spinner {
    text-align: center;
    color: var(--primary-color);
}

.spinner-ring {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(171, 207, 55, 0.2);
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-medium);
}

.filters-sidebar-enhanced.filtering {
    opacity: 0.8;
    pointer-events: none;
}

/* Error Messages */
.filter-error-message {
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: #fee;
    border: 1px solid #fcc;
    border-radius: var(--radius-md);
    padding: 1rem;
    box-shadow: var(--shadow-lg);
    z-index: 1000;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.error-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #c53030;
    font-size: 0.875rem;
    font-weight: 600;
}

/* Enhanced Collapsible Animations */
.filter-content {
    overflow: hidden;
    transition: max-height 0.3s ease, opacity 0.2s ease;
}

.filter-content.open {
    opacity: 1;
}

.filter-content:not(.open) {
    opacity: 0;
}

.collapsible.expanded .filter-title {
    color: var(--primary-color);
}

.collapsible:hover .filter-title {
    color: var(--primary-color);
}

/* Price Input Enhancements */
.price-input-enhanced:invalid {
    border-color: #e53e3e;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
}

.price-input-enhanced:valid {
    border-color: var(--primary-color);
}

/* Mobile Responsive Updates for Filter Sidebar */
@media (max-width: 768px) {
    .filters-sidebar-enhanced {
        position: static;
        margin-bottom: 2rem;
    }

    .price-inputs-enhanced {
        flex-direction: column;
    }

    .sidebar-header {
        padding: 1.25rem;
    }

    .filter-content {
        padding: 1rem 1.25rem 1.25rem;
    }

    .reviews-list-enhanced {
        max-height: 300px;
    }

    .filter-error-message {
        top: 1rem;
        right: 1rem;
        left: 1rem;
        position: fixed;
    }
}

@media (max-width: 576px) {
    .review-header-enhanced {
        flex-direction: column;
        gap: 0.75rem;
    }

    .customer-avatar {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .price-input-enhanced {
        padding: 0.75rem 1rem 0.75rem 2.75rem;
    }

    .price-prefix {
        left: 0.875rem;
        font-size: 0.75rem;
    }

    .loading-spinner {
        transform: scale(0.8);
    }
}

/* Mobile-Responsive Seller Profile Image Styles */
.seller-profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    object-position: center;
    border-radius: 50%;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-md);
}

/* Mobile specific seller profile image optimizations */
@media (max-width: 768px) {
    .seller-profile-img {
        width: 100px;
        height: 100px;
    }
    
    .seller-avatar-section {
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .seller-profile-img {
        width: 80px;
        height: 80px;
    }
    
    .avatar-container {
        margin-bottom: 1rem;
    }
    
    .seller-avatar-placeholder {
        width: 80px;
        height: 80px;
        font-size: 1.5rem;
    }
}

/* Mobile-Responsive Typography Styles */
@media (max-width: 768px) {
    /* Seller profile title */
    .seller-name {
        font-size: 1.5rem !important;
        line-height: 1.2;
    }
    
    .seller-tagline {
        font-size: 1rem !important;
        line-height: 1.4;
    }
    
    /* Seller stats */
    .stat-number {
        font-size: 1.5rem !important;
    }
    
    .stat-label {
        font-size: 0.8rem !important;
    }
    
    /* Seller description */
    .seller-description {
        font-size: 0.9rem !important;
        line-height: 1.5;
    }
    
    .seller-bio {
        font-size: 0.9rem !important;
        line-height: 1.5;
    }
    
    /* Contact information */
    .contact-info {
        font-size: 0.85rem !important;
    }
    
    .contact-label {
        font-size: 0.8rem !important;
    }
    
    /* Business information */
    .business-info-item {
        font-size: 0.85rem !important;
    }
    
    .business-info-label {
        font-size: 0.8rem !important;
    }
    
    /* Product listings */
    .product-title {
        font-size: 0.9rem !important;
        line-height: 1.3;
    }
    
    .product-price {
        font-size: 0.9rem !important;
    }
    
    .product-brand {
        font-size: 0.75rem !important;
    }
    
    /* Buttons */
    .btn {
        font-size: 0.85rem !important;
        padding: 0.6rem 1rem !important;
    }
    
    .btn-sm {
        font-size: 0.75rem !important;
        padding: 0.5rem 0.8rem !important;
    }
    
    /* Section headers */
    .section-title {
        font-size: 1.5rem !important;
    }
    
    .section-subtitle {
        font-size: 0.95rem !important;
    }
    
    /* Rating and reviews */
    .rating-score {
        font-size: 1.2rem !important;
    }
    
    .rating-label {
        font-size: 0.8rem !important;
    }
    
    .review-text {
        font-size: 0.85rem !important;
        line-height: 1.5;
    }
    
    .review-author {
        font-size: 0.8rem !important;
    }
    
    .review-date {
        font-size: 0.75rem !important;
    }
    
    /* Store information */
    .store-info-item {
        font-size: 0.85rem !important;
    }
    
    .store-since {
        font-size: 0.8rem !important;
    }
    
    /* Filter sidebar typography */
    .filter-title {
        font-size: 1rem !important;
    }
    
    .filter-option-label {
        font-size: 0.85rem !important;
    }
    
    .price-label {
        font-size: 0.8rem !important;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .seller-name {
        font-size: 1.25rem !important;
    }
    
    .seller-tagline {
        font-size: 0.9rem !important;
    }
    
    .stat-number {
        font-size: 1.3rem !important;
    }
    
    .stat-label {
        font-size: 0.75rem !important;
    }
    
    .seller-description {
        font-size: 0.85rem !important;
    }
    
    .seller-bio {
        font-size: 0.85rem !important;
    }
    
    .contact-info {
        font-size: 0.8rem !important;
    }
    
    .contact-label {
        font-size: 0.75rem !important;
    }
    
    .business-info-item {
        font-size: 0.8rem !important;
    }
    
    .business-info-label {
        font-size: 0.75rem !important;
    }
    
    .product-title {
        font-size: 0.85rem !important;
    }
    
    .product-price {
        font-size: 0.85rem !important;
    }
    
    .product-brand {
        font-size: 0.7rem !important;
    }
    
    .btn {
        font-size: 0.8rem !important;
        padding: 0.5rem 0.8rem !important;
    }
    
    .btn-sm {
        font-size: 0.7rem !important;
        padding: 0.4rem 0.6rem !important;
    }
    
    .section-title {
        font-size: 1.3rem !important;
    }
    
    .section-subtitle {
        font-size: 0.9rem !important;
    }
    
    .rating-score {
        font-size: 1.1rem !important;
    }
    
    .rating-label {
        font-size: 0.75rem !important;
    }
    
    .review-text {
        font-size: 0.8rem !important;
    }
    
    .review-author {
        font-size: 0.75rem !important;
    }
    
    .review-date {
        font-size: 0.7rem !important;
    }
    
    .store-info-item {
        font-size: 0.8rem !important;
    }
    
    .store-since {
        font-size: 0.75rem !important;
    }
    
    .filter-title {
        font-size: 0.9rem !important;
    }
    
    .filter-option-label {
        font-size: 0.8rem !important;
    }
    
    .price-label {
        font-size: 0.75rem !important;
    }
}
</style>
@endpush
