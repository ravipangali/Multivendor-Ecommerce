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
                                 class="brand-logo brand-header-img">
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
                                            <span class="count">({{ $category->products_count ?? 0 }})</span>
                                        </label>
                                        @if(isset($category->subcategories) && $category->subcategories->count() > 0)
                                            <button type="button" class="expand-btn" data-target="#subcat_{{ $category->id }}">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if(isset($category->subcategories) && $category->subcategories->count() > 0)
                                        <div class="subcategories" id="subcat_{{ $category->id }}">
                                            @foreach($category->subcategories as $subcategory)
                                                <div class="filter-item subcategory-item">
                                                    <input type="checkbox" id="subcat_{{ $subcategory->id }}" value="{{ $subcategory->slug }}" class="subcategory-filter" data-category="{{ $category->slug }}">
                                                    <label for="subcat_{{ $subcategory->id }}">
                                                        {{ $subcategory->name }}
                                                        <span class="count">({{ $subcategory->products_count ?? 0 }})</span>
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
                            <span class="rs-icon">Rs</span>
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
    $('.category-filter, .subcategory-filter, .color-filter, .size-filter').on('change', function() {
        applyFilters();
    });

    $('#applyPriceFilter').on('click', function() {
        applyFilters();
    });

    $('#clearFilters, #clearAllFilters').on('click', function() {
        // Clear all filter inputs
        $('.category-filter, .subcategory-filter, .color-filter, .size-filter').prop('checked', false);
        $('#min_price, #max_price').val('');

        // Hide all subcategories
        $('.subcategories').removeClass('show').hide();
        $('.expand-btn').removeClass('expanded');

        applyFilters();
    });

    $('#sortBy').on('change', function() {
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
        let sortBy = $('#sortBy').val();

        // Build URL with filters
        let url = new URL(window.location.href);
        url.search = '';

        if (categories.length > 0) {
            url.searchParams.set('category', categories.join(','));
        }
        if (subcategories.length > 0) {
            url.searchParams.set('subcategory', subcategories.join(','));
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
            $('#sortBy').val(urlParams.get('sort_by'));
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

/* Results Header */
.results-header {
    background: white;
    border-radius: 8px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.results-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin: 0 0 0.5rem 0;
}

.results-subtitle {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
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
    color: #212529;
    margin: 0;
    font-size: 0.9rem;
}

.sort-select {
    min-width: 200px;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.sort-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
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

/* Modern Product Cards */
.product-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
    border-color: #007bff;
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
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
    top: 0.75rem;
    left: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    z-index: 2;
}

.badge-discount {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-stock {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.product-actions {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(10px);
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
    background: rgba(255, 255, 255, 0.95);
    color: #495057;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    backdrop-filter: blur(10px);
    border: 1px solid #e9ecef;
}

.action-btn:hover {
    background: #007bff;
    color: white;
    transform: scale(1.1);
    border-color: #007bff;
}

.product-content {
    padding: 1.25rem;
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
    color: #007bff;
    font-size: 0.8rem;
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
    color: #e9ecef;
    font-size: 0.75rem;
    transition: color 0.2s ease;
}

.stars .fa-star.active {
    color: #ffc107;
}

.rating-count {
    font-size: 0.75rem;
    color: #6c757d;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    line-height: 1.4;
    flex: 1;
}

.product-title a {
    color: #212529;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-title a:hover {
    color: #007bff;
}

.product-price {
    margin-bottom: 1.5rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #28a745;
}

.original-price {
    font-size: 0.9rem;
    color: #6c757d;
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

/* Mobile-Responsive Brand Header Image Styles */
.brand-header-img {
    width: 100%;
    max-width: 200px;
    height: auto;
    max-height: 150px;
    object-fit: contain;
    object-position: center;
    padding: 10px;
}

/* Mobile specific brand header image optimizations */
@media (max-width: 768px) {
    .brand-header-img {
        max-width: 150px;
        max-height: 120px;
    }
    
    .brand-logo-container {
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 480px) {
    .brand-header-img {
        max-width: 120px;
        max-height: 100px;
    }
    
    .brand-logo-container {
        margin-bottom: 1rem;
    }
}

/* Mobile-Responsive Typography Styles */
@media (max-width: 768px) {
    /* Brand page title */
    .brand-name {
        font-size: 2rem !important;
        line-height: 1.2;
    }
    
    .brand-title {
        font-size: 1.75rem !important;
        line-height: 1.2;
    }
    
    .brand-description {
        font-size: 1rem !important;
        line-height: 1.5;
    }
    
    .products-count {
        font-size: 0.9rem !important;
    }
    
    /* Product listings */
    .product-title {
        font-size: 0.9rem !important;
        line-height: 1.3;
    }
    
    .product-brand {
        font-size: 0.75rem !important;
    }
    
    .product-price {
        font-size: 0.9rem !important;
    }
    
    .current-price {
        font-size: 1.1rem !important;
    }
    
    .original-price {
        font-size: 0.8rem !important;
    }
    
    .discount-badge {
        font-size: 0.75rem !important;
        padding: 0.3rem 0.6rem !important;
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
    
    /* Filter and sort typography */
    .filter-label {
        font-size: 0.85rem !important;
    }
    
    .sort-label {
        font-size: 0.85rem !important;
    }
    
    .filter-option {
        font-size: 0.8rem !important;
    }
    
    .filter-tag {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
    
    /* Section headers */
    .section-title {
        font-size: 1.75rem !important;
    }
    
    .section-subtitle {
        font-size: 0.95rem !important;
    }
    
    /* Rating */
    .rating-score {
        font-size: 0.9rem !important;
    }
    
    .rating-count {
        font-size: 0.75rem !important;
    }
    
    /* Breadcrumb */
    .breadcrumb-item a {
        font-size: 0.85rem !important;
    }
    
    /* Stock status */
    .in-stock,
    .out-of-stock {
        font-size: 0.8rem !important;
    }
    
    /* Brand stats */
    .brand-stats-item {
        font-size: 0.85rem !important;
    }
    
    .brand-stats-label {
        font-size: 0.8rem !important;
    }
    
    /* Empty state */
    .empty-title {
        font-size: 1.3rem !important;
    }
    
    .empty-text {
        font-size: 0.9rem !important;
    }
    
    /* Results header */
    .results-count {
        font-size: 0.9rem !important;
    }
    
    .sort-select {
        font-size: 0.85rem !important;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .brand-name {
        font-size: 1.75rem !important;
    }
    
    .brand-title {
        font-size: 1.5rem !important;
    }
    
    .brand-description {
        font-size: 0.9rem !important;
    }
    
    .products-count {
        font-size: 0.85rem !important;
    }
    
    .product-title {
        font-size: 0.85rem !important;
    }
    
    .product-brand {
        font-size: 0.7rem !important;
    }
    
    .product-price {
        font-size: 0.85rem !important;
    }
    
    .current-price {
        font-size: 1rem !important;
    }
    
    .original-price {
        font-size: 0.75rem !important;
    }
    
    .discount-badge {
        font-size: 0.7rem !important;
        padding: 0.25rem 0.5rem !important;
    }
    
    .btn {
        font-size: 0.8rem !important;
        padding: 0.5rem 0.8rem !important;
    }
    
    .btn-sm {
        font-size: 0.7rem !important;
        padding: 0.4rem 0.6rem !important;
    }
    
    .filter-label {
        font-size: 0.8rem !important;
    }
    
    .sort-label {
        font-size: 0.8rem !important;
    }
    
    .filter-option {
        font-size: 0.75rem !important;
    }
    
    .filter-tag {
        font-size: 0.75rem !important;
        padding: 0.3rem 0.6rem !important;
    }
    
    .section-title {
        font-size: 1.5rem !important;
    }
    
    .section-subtitle {
        font-size: 0.9rem !important;
    }
    
    .rating-score {
        font-size: 0.85rem !important;
    }
    
    .rating-count {
        font-size: 0.7rem !important;
    }
    
    .breadcrumb-item a {
        font-size: 0.8rem !important;
    }
    
    .in-stock,
    .out-of-stock {
        font-size: 0.75rem !important;
    }
    
    .brand-stats-item {
        font-size: 0.8rem !important;
    }
    
    .brand-stats-label {
        font-size: 0.75rem !important;
    }
    
    .empty-title {
        font-size: 1.2rem !important;
    }
    
    .empty-text {
        font-size: 0.85rem !important;
    }
    
    .results-count {
        font-size: 0.85rem !important;
    }
    
    .sort-select {
        font-size: 0.8rem !important;
    }
}
</style>
@endpush
