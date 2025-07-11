@extends('saas_customer.saas_layout.saas_layout')

@section('content')
    <!-- Hero Banner -->
    <section style="padding: 0 !important; border-radius: 0 !important;" class="hero-banner">
        <div class="main-banner-wrapper">
            <div class="banner-carousel owl-theme owl-carousel">
                @forelse ($sliderBanners as $item)
                    <div class="banner-slide">
                        <a href="{{ $item->link_url ?? '#' }}">
                            <div class="banner-image">
                                <img src="{{ asset('storage/'.$item->image) ?? asset('saas_frontend/img/slider-1.jpg') }}"
                                     alt="Banner {{ $loop->iteration }}"
                                     class="img-fluid banner-img">
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="banner-slide">
                        <div class="banner-image">
                            <img src="{{ asset('saas_frontend/img/slider-1.jpg') }}" alt="Default Banner" class="img-fluid banner-img">
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn prev-btn" type="button">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn next-btn" type="button">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Action Cards -->
    <section class="action-cards py-5">
        <div class="container">
            <div class="row justify-content-center g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="action-card vendor-card">
                        <div class="card-icon">
                            <img src="{{ asset('saas_frontend/img/vendor.png') }}" alt="Become Vendor" class="action-card-img">
                        </div>
                        <div class="card-content">
                            <span class="card-subtitle">Sign Up For Free</span>
                            <h3 class="card-title">Sell your products & Services</h3>
                            <a href="{{ route('seller.register') }}" class="btn btn-primary">
                                <i class="fas fa-store me-2"></i>Become A Vendor
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="action-card shop-card">
                        <div class="card-icon">
                            <img src="{{ asset('saas_frontend/img/shop.png') }}" alt="Shop Now" class="action-card-img">
                        </div>
                        <div class="card-content">
                            <span class="card-subtitle">Get Anything You Want</span>
                            <h3 class="card-title">Explore New Arrivals Now</h3>
                            <a href="{{ route('customer.products') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Shop Now
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="action-card shop-card">
                        <div class="card-icon">
                            <img src="{{ asset('saas_frontend/img/request.png') }}" alt="Shop Now" class="action-card-img">
                        </div>
                        <div class="card-content">
                            <span class="card-subtitle">Sign Up For Free</span>
                            <h3 class="card-title">Make a demand for a product</h3>
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user me-2"></i>Become A Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Featured Categories -->
    <section class="featured-categories py-5 bg-light">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Discover products across different categories</p>
            </div>

            <div class="row g-4">
                @if ($featuredCategories->count() > 0)
                    @foreach ($featuredCategories->take(6) as $category)
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="category-card">
                                <a href="{{ route('customer.category', $category->slug) }}" class="category-link">
                                    <div class="category-image">
                                        <img src="{{ $category->category_image_url ?? asset('saas_frontend/images/about/12.jpg') }}"
                                             alt="{{ $category->name }}" class="img-fluid category-img">
                                    </div>
                                    <div class="category-content">
                                        <h5 class="category-name">{{ $category->name }}</h5>
                                        <span class="category-count">{{ $category->products_count ?? 0 }} items</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Default categories -->
                    @foreach(['Electronics', 'Fashion', 'Home & Garden', 'Sports', 'Books', 'Beauty'] as $categoryName)
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="category-card">
                                <a href="{{ route('customer.products') }}" class="category-link">
                                    <div class="category-image">
                                        <img src="{{ asset('saas_frontend/images/about/12.jpg') }}" alt="{{ $categoryName }}" class="img-fluid category-img">
                                    </div>
                                    <div class="category-content">
                                        <h5 class="category-name">{{ $categoryName }}</h5>
                                        <span class="category-count">0 items</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products py-5">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="section-title mb-2">Featured Products</h2>
                    <p class="section-subtitle mb-0">Handpicked products just for you</p>
                </div>
                <a href="{{ route('customer.products') }}" class="btn btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="product-grid">
                <div class="row g-4">
                    @forelse($featuredProducts->take(8) as $product)
                        <div class="col-lg-3 col-md-6">
                            <div class="product-card">
                                <div class="product-image">
                                    <a href="{{ route('customer.product.detail', $product->slug) }}">
                                        <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                             alt="{{ $product->name }}" class="img-fluid product-img">
                                    </a>
                                    <div class="product-actions">
                                        <button class="action-btn wishlist-btn add-to-wishlist" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <a href="{{ route('customer.product.detail', $product->slug) }}" class="action-btn view-btn" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </div>
                                    @if($product->discount > 0)
                                        <div class="product-badge">
                                            <span class="badge badge-danger">
                                                @if($product->discount_type === 'percentage')
                                                    {{ $product->discount }}% OFF
                                                @else
                                                    Rs. {{ $product->discount }} OFF
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-content">
                                    <div class="product-meta">
                                        <span class="product-brand">{{ $product->brand->name ?? 'Brand' }}</span>
                                    </div>
                                    <h5 class="product-title">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}">
                                            {{ Str::limit($product->name, 45) }}
                                        </a>
                                    </h5>
                                    <div class="product-rating">
                                        @php
                                            $avgRating = $product->reviews->avg('rating') ?? 0;
                                        @endphp
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa{{ $i <= $avgRating ? 's' : 'r' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-count">({{ $product->reviews->count() }})</span>
                                    </div>
                                    <div class="product-price">
                                        <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                        @if($product->discount > 0)
                                            <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="product-actions-bottom">
                                        <button class="btn btn-primary btn-sm add-to-cart w-100" data-product-id="{{ $product->id }}">
                                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5>No Featured Products</h5>
                                <p class="text-muted">Check back later for featured products</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals & Popular Products -->
    <section class="product-sections py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <!-- New Arrivals -->
                <div class="col-lg-8">
                    <div class="section-header mb-4">
                        <h3 class="section-title">New Arrivals</h3>
                        <p class="section-subtitle">Latest products in our store</p>
                    </div>

                    <div class="row g-3">
                        @forelse($newArrivals->take(6) as $product)
                            <div class="col-md-6">
                                <div class="product-card-horizontal">
                                    <div class="product-image">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}">
                                            <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item89.png') }}"
                                                 alt="{{ $product->name }}" class="img-fluid horizontal-product-img">
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <span class="product-brand">{{ $product->brand->name ?? 'Brand' }}</span>
                                        <h6 class="product-title">
                                            <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                {{ Str::limit($product->name, 40) }}
                                            </a>
                                        </h6>
                                        <div class="product-price">
                                            <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                            @if($product->discount > 0)
                                                <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary add-to-cart" data-product-id="{{ $product->id }}">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted text-center">No new arrivals yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Popular Products -->
                <div class="col-lg-4">
                    <div class="section-header mb-4">
                        <h3 class="section-title">Popular Products</h3>
                        <p class="section-subtitle">Customer favorites</p>
                    </div>

                    <div class="popular-products-list">
                        @forelse($topSellingProducts->take(5) as $product)
                            <div class="product-list-item">
                                <div class="product-image">
                                    <a href="{{ route('customer.product.detail', $product->slug) }}">
                                        <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/hnap1.png') }}"
                                             alt="{{ $product->name }}" class="img-fluid">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h6 class="product-title">
                                        <a href="{{ route('customer.product.detail', $product->slug) }}">
                                            {{ Str::limit($product->name, 35) }}
                                        </a>
                                    </h6>
                                    <div class="product-price">
                                        <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                    </div>
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-sm btn-primary add-to-cart" data-product-id="{{ $product->id }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">No popular products yet</p>
                        @endforelse

                        <div class="mt-3">
                            <a href="{{ route('customer.products') }}" class="btn btn-primary w-100">
                                View All Products <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Brands -->
    <section class="popular-brands py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">Popular Brands</h2>
                <p class="section-subtitle">Shop from your favorite brands</p>
            </div>

            <div class="brands-grid">
                <div class="row g-4">
                    @forelse($popularBrands->take(8) as $brand)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="brand-card">
                                <a href="{{ route('customer.brand', $brand->slug) }}" class="brand-link">
                                    <div class="brand-image">
                                        <img src="{{ $brand->brand_image_url ?? asset('saas_frontend/images/shop-items/epb1.png') }}"
                                             alt="{{ $brand->name }}" class="img-fluid">
                                    </div>
                                    <div class="brand-content">
                                        <h5 class="brand-name">{{ $brand->name }}</h5>
                                        <span class="brand-count">{{ $brand->products_count ?? 0 }} products</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <h5>No Brands Available</h5>
                                <p class="text-muted">Brands will appear here once added</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

        <!-- Category-wise Products -->
    <section class="category-wise-products py-5">
        <div class="container-fluid">
            @if($categoriesWithProducts->count() > 0)
                @foreach($categoriesWithProducts as $category)
                    <div class="category-section mb-5">
                        <!-- Category Header -->
                        <div class="container">
                            <div class="category-header d-flex justify-content-between align-items-center mb-4">
                                <div class="category-title-section">
                                    <h3 class="category-title text-uppercase">{{ $category->name }}</h3>
                                    <div class="category-subtitle">
                                        <small class="text-muted">
                                            {{ $category->allProducts->count() }} {{ Str::plural('product', $category->allProducts->count()) }}
                                            @if($category->subcategories->count() > 0)
                                                from {{ $category->subcategories->count() }} {{ Str::plural('subcategory', $category->subcategories->count()) }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="view-all-section">
                                    <a href="{{ route('customer.category', $category->slug) }}" class="view-all-link">
                                        View All
                                        <i class="fas fa-chevron-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Products Horizontal Scroll -->
                        <div class="products-scroll-container">
                            <div class="products-scroll-wrapper">
                                @foreach($category->allProducts->take(8) as $product)
                                    <div class="product-scroll-item">
                                        <div class="product-card-modern">
                                            <div class="product-image-wrapper">
                                                <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                    <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                         alt="{{ $product->name }}" class="product-image">
                                                </a>

                                                <!-- Discount Badge -->
                                                @if($product->discount > 0)
                                                    <div class="discount-badge">
                                                        @if($product->discount_type === 'percentage')
                                                            Rs {{ $product->discount }} OFF
                                                        @else
                                                            Rs {{ $product->discount }} OFF
                                                        @endif
                                                    </div>
                                                @endif

                                                <!-- Stock Status -->
                                                @if($product->stock <= 0)
                                                    <div class="stock-badge out-of-stock">
                                                        SOLD OUT
                                                    </div>
                                                @elseif($product->stock <= 5)
                                                    <div class="stock-badge low-stock">
                                                        ONLY {{ $product->stock }} LEFT
                                                    </div>
                                                @endif

                                                <!-- Quick Actions -->
                                                <div class="quick-actions">
                                                    <button class="action-btn add-to-wishlist" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                                        <i class="far fa-heart"></i>
                                                    </button>
                                                    <button class="action-btn add-to-cart" data-product-id="{{ $product->id }}" title="Add to Cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="product-info">
                                                <h6 class="product-name">
                                                    <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                        {{ Str::limit($product->name, 40) }}
                                                    </a>
                                                </h6>

                                                <div class="product-rating-small">
                                                    @php
                                                        $avgRating = $product->reviews->avg('rating') ?? 0;
                                                    @endphp
                                                    <div class="stars-small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa{{ $i <= $avgRating ? 's' : 'r' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="rating-text">({{ $product->reviews->count() }})</span>
                                                </div>

                                                <div class="product-price-section">
                                                    <div class="current-price">Rs {{ number_format($product->final_price, 2) }}</div>
                                                    @if($product->discount > 0)
                                                        <div class="original-price">Rs {{ number_format($product->price, 2) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="container">
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5>No Categories Available</h5>
                        <p class="text-muted">Categories with products will appear here</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Features -->
    <section class="features py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h5 class="feature-title">Free Shipping</h5>
                        <p class="feature-text">Free shipping for orders over Rs. 1000</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="feature-title">Money Guarantee</h5>
                        <p class="feature-text">Within 30 days for an exchange</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="feature-title">Online Support</h5>
                        <p class="feature-text">24 hours a day, 7 days a week</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h5 class="feature-title">Flexible Payment</h5>
                        <p class="feature-text">Pay with multiple credit cards</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
/* Category-wise Products */
.category-wise-products {
    padding: 4rem 0;
    background: var(--white);
}

.category-section {
    margin-bottom: 4rem;
}

.category-header {
    padding: 0 15px;
    margin-bottom: 2rem;
}

.category-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    letter-spacing: 0.5px;
}

.category-subtitle {
    margin-top: 0.5rem;
}

.category-subtitle small {
    color: #666;
    font-size: 0.875rem;
}

.view-all-link {
    color: #ff6b35;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.view-all-link:hover {
    color: #e55a30;
    text-decoration: none;
}

.view-all-link i {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.view-all-link:hover i {
    transform: translateX(3px);
}

/* Products Horizontal Scroll */
.products-scroll-container {
    position: relative;
    overflow: hidden;
}

.products-scroll-wrapper {
    display: flex;
    gap: 1rem;
    padding: 0 15px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.products-scroll-wrapper::-webkit-scrollbar {
    display: none;
}

.product-scroll-item {
    flex: 0 0 280px;
    min-width: 280px;
}

/* Modern Product Card */
.product-card-modern {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 1px solid #e8e8e8;
}

.product-card-modern:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 12px 12px 0 0;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card-modern:hover .product-image {
    transform: scale(1.05);
}

/* Badges */
.discount-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 2;
}

.stock-badge.out-of-stock {
    background: linear-gradient(135deg, #636363, #434343);
    color: white;
}

.stock-badge.low-stock {
    background: linear-gradient(135deg, #ffa726, #fb8c00);
    color: white;
}

/* Quick Actions */
.quick-actions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.product-card-modern:hover .quick-actions {
    opacity: 1;
}

.quick-actions .action-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    color: #333;
    backdrop-filter: blur(10px);
}

.quick-actions .action-btn:hover {
    background: #ff6b35;
    color: white;
    transform: scale(1.1);
}

.quick-actions .action-btn:disabled {
    background: rgba(200, 200, 200, 0.9);
    color: #666;
    cursor: not-allowed;
}

.quick-actions .action-btn:disabled:hover {
    transform: none;
    background: rgba(200, 200, 200, 0.9);
}

/* Product Info */
.product-info {
    padding: 1.25rem;
}

.product-name {
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    font-weight: 600;
    line-height: 1.4;
}

.product-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #ff6b35;
}

.product-rating-small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars-small {
    display: flex;
    gap: 2px;
}

.stars-small i {
    font-size: 0.8rem;
    color: #ffc107;
}

.stars-small i.far {
    color: #ddd;
}

.rating-text {
    font-size: 0.8rem;
    color: #666;
}

.product-price-section {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.current-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: #ff6b35;
}

.original-price {
    font-size: 0.95rem;
    color: #666;
    text-decoration: line-through;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .category-wise-products {
        padding: 3rem 0;
    }

    .category-title {
        font-size: 1.25rem;
    }

    .product-scroll-item {
        flex: 0 0 240px;
        min-width: 240px;
    }

    .product-image {
        height: 160px;
    }

    .product-info {
        padding: 1rem;
    }

    .category-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .product-scroll-item {
        flex: 0 0 200px;
        min-width: 200px;
    }

    .product-image {
        height: 140px;
    }

    .discount-badge, .stock-badge {
        font-size: 0.65rem;
        padding: 0.3rem 0.6rem;
    }

    .quick-actions .action-btn {
        width: 40px;
        height: 40px;
    }
}
</style>

@push('styles')
<style>
/* Enhanced Hero Banner */
.hero-banner {
    position: relative;
    margin-bottom: 0;
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    padding: 2rem 0;
}

.main-banner-wrapper {
    position: relative;
    border-radius: var(--radius-xl);
    overflow: hidden;
    height: 500px;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--border-light);
}

.banner-slide {
    height: 500px;
    position: relative;
    background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.2));
}

.banner-image {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.banner-image img {
    width: 100% !important;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 20px;
    pointer-events: none;
}

.carousel-btn {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.3s ease;
    pointer-events: all;
    box-shadow: var(--shadow-md);
}

.carousel-btn:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: scale(1.1);
}

/* Enhanced Action Cards */
.action-cards {
    padding: 5rem 0;
    background: var(--white);
}

.action-card {
    background: linear-gradient(135deg, var(--white), #fafbfc);
    border-radius: var(--radius-xl);
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: var(--shadow-lg);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    position: relative;
    overflow: hidden;
    border: 2px solid transparent;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.action-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-color);
}

.action-card .card-icon {
    margin-bottom: 1.5rem;
}

.action-card .card-icon img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.action-card .card-subtitle {
    color: var(--text-light);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: block;
}

.action-card .card-title {
    font-size: 1.375rem;
    margin-bottom: 1.5rem;
    line-height: 1.4;
    color: var(--text-dark);
}

/* Section Headers */
.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.25rem;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.section-subtitle {
    color: var(--text-light);
    font-size: 1.125rem;
    margin: 0;
}

/* Category Cards */
.category-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    height: 100%;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.category-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.category-image {
    height: 120px;
    overflow: hidden;
    position: relative;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-content {
    padding: 1.25rem;
    text-align: center;
}

.category-name {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-dark);
}

.category-count {
    color: var(--text-light);
    font-size: 0.875rem;
}

/* Product Cards */
.product-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    height: 100%;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.product-image {
    position: relative;
    height: 220px;
    overflow: hidden;
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

/* Category-wise Products */
.category-wise-products {
    padding: 5rem 0;
    background: var(--white);
}

.category-tabs-wrapper {
    margin-bottom: 3rem;
}

.category-tabs {
    border-bottom: none;
    gap: 1rem;
    flex-wrap: wrap;
}

.category-tabs .nav-link {
    background: var(--light-bg);
    border: 2px solid transparent;
    border-radius: var(--radius-xl);
    color: var(--text-muted);
    font-weight: 600;
    padding: 1rem 2rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
}

.category-tabs .nav-link:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.category-tabs .nav-link.active {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--white);
    border-color: var(--primary-color);
    box-shadow: var(--shadow-lg);
}

.category-tabs .nav-link .category-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-lg);
    font-size: 0.75rem;
    margin-left: 0.5rem;
    font-weight: 700;
}

.category-tabs .nav-link:not(.active) .category-count {
    background: var(--primary-color);
    color: var(--white);
}

.category-products-content {
    min-height: 500px;
}

.category-products-header {
    padding: 2rem 0;
    border-bottom: 2px solid var(--border-light);
    margin-bottom: 2rem;
}

.category-section-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.category-section-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
    margin: 0 0 0.75rem 0;
}

.category-hierarchy-info {
    margin-top: 0.5rem;
}

.category-hierarchy-info small {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
    display: inline-block;
}

.tab-pane {
    animation: fadeInUp 0.5s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-actions {
    position: absolute;
    top: 15px;
    right: 15px;
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
    width: 40px;
    height: 40px;
    background: var(--white);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.action-btn:hover {
    background: var(--primary-color);
    color: var(--white);
    transform: scale(1.1);
}

.product-badge {
    position: absolute;
    top: 15px;
    left: 15px;
}

.product-content {
    padding: 1.5rem;
}

.product-meta {
    margin-bottom: 0.5rem;
}

.product-brand {
    color: var(--text-light);
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-title {
    margin-bottom: 0.75rem;
    font-size: 1rem;
    line-height: 1.4;
}

.product-title a {
    color: var(--text-dark);
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: var(--primary-color);
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.stars {
    color: #ffd700;
    font-size: 0.875rem;
}

.rating-count {
    color: var(--text-light);
    font-size: 0.75rem;
}

.product-price {
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
}

.original-price {
    font-size: 0.875rem;
    color: var(--text-light);
    text-decoration: line-through;
    margin-left: 0.5rem;
}

/* Horizontal Product Cards */
.product-card-horizontal {
    display: flex;
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.product-card-horizontal:hover {
    box-shadow: var(--shadow-md);
    transform: translateX(5px);
}

.product-card-horizontal .product-image {
    width: 100px;
    height: 100px;
    flex-shrink: 0;
}

.product-card-horizontal .product-content {
    padding: 1rem;
    flex: 1;
}

.product-card-horizontal .product-title {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.product-card-horizontal .product-brand {
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    display: block;
}

.product-card-horizontal .product-price {
    margin-bottom: 0.75rem;
}

.product-card-horizontal .current-price {
    font-size: 1rem;
}

/* Product List Items */
.popular-products-list {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.product-list-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-light);
}

.product-list-item:last-child {
    border-bottom: none;
}

.product-list-item .product-image {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-md);
    overflow: hidden;
    flex-shrink: 0;
}

.product-list-item .product-content {
    flex: 1;
}

.product-list-item .product-title {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.product-list-item .product-actions {
    flex-shrink: 0;
}

/* Brand Cards */
.brand-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    height: 100%;
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.brand-link {
    text-decoration: none;
    color: inherit;
}

.brand-image {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.brand-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.brand-name {
    font-size: 1.125rem;
    margin-bottom: 0.25rem;
    color: var(--text-dark);
}

.brand-count {
    color: var(--text-light);
    font-size: 0.875rem;
}

/* Feature Cards */
.feature-card {
    padding: 2rem 1.5rem;
    height: 100%;
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--white);
    font-size: 2rem;
}

.feature-title {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.feature-text {
    color: var(--text-light);
    margin: 0;
    line-height: 1.6;
}

/* Empty States */
.empty-state {
    padding: 3rem 1.5rem;
}

.empty-state i {
    opacity: 0.5;
}

.empty-state h5 {
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

/* Responsive Design */
@media (max-width: 768px) {
    .main-banner-wrapper {
        height: 250px;
    }

    .banner-slide {
        height: 250px;
    }

    .section-title {
        font-size: 1.75rem;
    }

    .section-subtitle {
        font-size: 1rem;
    }

    .action-card {
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .action-card .card-title {
        font-size: 1.125rem;
    }

    .product-card-horizontal {
        flex-direction: column;
    }

    .product-card-horizontal .product-image {
        width: 100%;
        height: 150px;
    }

    .carousel-btn {
        width: 40px;
        height: 40px;
    }

    .carousel-controls {
        padding: 0 15px;
    }

    /* Category-wise Products Mobile */
    .category-wise-products {
        padding: 3rem 0;
    }

    .category-tabs {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }

    .category-tabs::-webkit-scrollbar {
        height: 4px;
    }

    .category-tabs::-webkit-scrollbar-track {
        background: var(--light-bg);
        border-radius: 2px;
    }

    .category-tabs::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 2px;
    }

    .category-tabs .nav-link {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .category-products-header {
        padding: 1rem 0;
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .category-section-title {
        font-size: 1.5rem;
    }

    .category-hierarchy-info small {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
}

@media (max-width: 576px) {
    .action-cards {
        padding: 2rem 0;
    }

    .section-header {
        margin-bottom: 2rem;
    }

    .product-sections {
        padding: 3rem 0;
    }

    .featured-categories {
        padding: 3rem 0;
    }
}

/* Mobile-Responsive Image Styles */
.banner-img {
    width: 100%;
    height: auto;
    min-height: 200px;
    max-height: 500px;
    object-fit: cover;
    object-position: center;
}

.action-card-img {
    width: 100%;
    max-width: 80px;
    height: auto;
    object-fit: contain;
}

.category-img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    object-position: center;
    border-radius: var(--radius-md);
}

.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    object-position: center;
    border-radius: var(--radius-md);
    transition: transform 0.3s ease;
}

.horizontal-product-img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    object-position: center;
    border-radius: var(--radius-sm);
}

/* Mobile specific image optimizations */
@media (max-width: 768px) {
    .banner-img {
        min-height: 150px;
        max-height: 300px;
    }

    .action-card-img {
        max-width: 60px;
    }

    .category-img {
        height: 100px;
    }

    .product-img {
        height: 160px;
    }

    .horizontal-product-img {
        height: 70px;
    }
}

@media (max-width: 480px) {
    .banner-img {
        min-height: 120px;
        max-height: 250px;
    }

    .action-card-img {
        max-width: 50px;
    }

    .category-img {
        height: 80px;
    }

    .product-img {
        height: 140px;
    }

    .horizontal-product-img {
        height: 60px;
    }
}

/* Mobile-Responsive Typography Styles */
@media (max-width: 768px) {
    /* Section titles and headings */
    .section-title {
        font-size: 1.75rem !important;
        line-height: 1.3;
    }

    .section-subtitle {
        font-size: 0.95rem !important;
        line-height: 1.5;
    }

    /* Hero banner text */
    .banner-content h1 {
        font-size: 2rem !important;
    }

    .banner-content p {
        font-size: 1rem !important;
    }

    /* Action card typography */
    .card-title {
        font-size: 1.1rem !important;
        line-height: 1.3;
    }

    .card-subtitle {
        font-size: 0.8rem !important;
    }

    /* Category names */
    .category-name {
        font-size: 0.9rem !important;
        line-height: 1.2;
    }

    .category-count {
        font-size: 0.75rem !important;
    }

    /* Product typography */
    .product-title {
        font-size: 0.9rem !important;
        line-height: 1.3;
    }

    .product-brand {
        font-size: 0.7rem !important;
    }

    .current-price {
        font-size: 1rem !important;
    }

    .original-price {
        font-size: 0.8rem !important;
    }

    /* Button text */
    .btn {
        font-size: 0.85rem !important;
        padding: 0.6rem 1rem !important;
    }

    .btn-sm {
        font-size: 0.75rem !important;
        padding: 0.5rem 0.8rem !important;
    }
}

@media (max-width: 480px) {
    /* Extra small screens - further font size reduction */
    .section-title {
        font-size: 1.5rem !important;
    }

    .section-subtitle {
        font-size: 0.9rem !important;
    }

    .banner-content h1 {
        font-size: 1.75rem !important;
    }

    .card-title {
        font-size: 1rem !important;
    }

    .card-subtitle {
        font-size: 0.75rem !important;
    }

    .category-name {
        font-size: 0.8rem !important;
    }

    .category-count {
        font-size: 0.7rem !important;
    }

    .product-title {
        font-size: 0.85rem !important;
    }

    .product-brand {
        font-size: 0.65rem !important;
    }

    .current-price {
        font-size: 0.95rem !important;
    }

    .btn {
        font-size: 0.8rem !important;
        padding: 0.5rem 0.8rem !important;
    }

    .btn-sm {
        font-size: 0.7rem !important;
        padding: 0.4rem 0.6rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize carousel
    $('.banner-carousel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        nav: false,
        dots: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn'
    });

    // Custom carousel controls
    $('.next-btn').click(function() {
        $('.banner-carousel').trigger('next.owl.carousel');
    });

    $('.prev-btn').click(function() {
        $('.banner-carousel').trigger('prev.owl.carousel');
    });

    // Add to cart functionality
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');

        // Add loading state
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route("customer.cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', 'Success!', 'Product added to cart successfully!');
                    $('.cart-filter-btn .badge').text(response.cart_count);

                    // Reset button
                    $btn.html('<i class="fas fa-check me-2"></i>Added!');
                    $btn.removeClass('btn-primary').addClass('btn-success');

                    setTimeout(() => {
                        $btn.html(originalText);
                        $btn.removeClass('btn-success').addClass('btn-primary');
                        $btn.prop('disabled', false);
                    }, 2000);
                } else {
                    showNotification('error', 'Error!', response.message || 'Failed to add product to cart');
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showNotification('warning', 'Login Required', 'Please login to add products to cart');
                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    showNotification('error', 'Error!', 'Error occurred while adding product to cart');
                }
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });
    });

    // Add to wishlist functionality
    $('.add-to-wishlist').on('click', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const $btn = $(this);

        @auth
        $.ajax({
            url: '{{ route("customer.wishlist.toggle") }}',
            method: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', 'Wishlist Updated!', response.message);

                    const $icon = $btn.find('i');
                    if (response.in_wishlist) {
                        $icon.removeClass('far').addClass('fas text-danger');
                        $btn.attr('title', 'Remove from Wishlist');
                    } else {
                        $icon.removeClass('fas text-danger').addClass('far');
                        $btn.attr('title', 'Add to Wishlist');
                    }
                } else {
                    showNotification('error', 'Error!', response.message || 'Failed to update wishlist');
                }
            },
            error: function(xhr) {
                showNotification('error', 'Error!', 'Error occurred while updating wishlist');
            }
        });
        @else
        showNotification('warning', 'Login Required', 'Please login to add products to wishlist');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 2000);
        @endauth
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.action-card, .category-card, .product-card, .brand-card, .feature-card').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
