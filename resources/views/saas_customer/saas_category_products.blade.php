@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
/* Modern Category Page Styles */
.breadcrumb-modern {
    background: #f8f9fa;
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-modern .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-modern .breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.breadcrumb-modern .breadcrumb-item a:hover {
    color: #var(--primary-color);
}

.breadcrumb-modern .breadcrumb-item.active {
    color: #212529;
    font-weight: 600;
}

/* Category Header Modern */
.category-header-modern {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem 0;
}

.category-hero-card {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-item:hover label {
    color: var(--text-dark);
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

/* Modern Product Grid */
.product-item {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-item:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
    border-color: #var(--primary-color);
}

.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-item:hover .product-image {
    transform: scale(1.05);
}

.discount-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    border-radius: 4px;
    text-transform: uppercase;
    z-index: 2;
}

.discount-flat {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.discount-percent {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
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
}

.product-item:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.95);
    color: #495057;
    border: 1px solid #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    text-decoration: none;
    backdrop-filter: blur(10px);
}

.action-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.product-info {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-meta {
    margin-bottom: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
}

.product-category {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.product-brand {
    font-size: 0.8rem;
    color: #var(--primary-color);
    font-weight: 600;
    text-transform: uppercase;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1;
}

.product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: var(--primary-color);
}

.product-price {
    margin-bottom: 1rem;
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

.product-stock {
    margin: 0.75rem 0;
    font-size: 0.8rem;
}

.product-actions-bottom {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    margin-top: auto;
}

.btn-cart {
    flex: 1;
    padding: 0.75rem 1rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    text-transform: none;
}

.btn-cart:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

.btn-cart:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
}

.btn-wishlist {
    padding: 0.75rem;
    background: transparent;
    color: #6c757d;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.btn-wishlist:hover {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
    transform: scale(1.05);
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

/* Modern Headers */
.listing-header {
    background: white;
    border-radius: 8px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.results-info h5 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
}

.results-info p {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Form Controls */
.form-control, .form-select {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    outline: none;
}

.btn-thm {
    background: #var(--primary-color);
    border-color: #var(--primary-color);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-thm:hover {
    background: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    border-color: #5a6268;
    transform: translateY(-1px);
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
    padding: 4rem 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.no-results-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: #6c757d;
}

.no-products h4 {
    font-size: 1.5rem;
    color: #212529;
    margin-bottom: 1rem;
    font-weight: 600;
}

.no-products p {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 991px) {
    .filter-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .category-hero-card {
        padding: 1.5rem;
        text-align: center;
    }

    .category-title {
        font-size: 2rem;
    }

    .listing-header {
        padding: 1rem;
        text-align: center;
    }

    .product-info {
        padding: 1rem;
    }

    .product-title {
        font-size: 0.9rem;
    }

    .current-price {
        font-size: 1.1rem;
    }

    .btn-cart {
        font-size: 0.8rem;
        padding: 0.6rem 0.8rem;
    }

    .btn-wishlist {
        width: 40px;
        height: 40px;
        font-size: 14px;
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
            <!-- Enhanced Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <!-- Subcategories -->
                    @if($category->subcategories->count() > 0)
                        <div class="filter-section">
                            <h6 class="filter-title">
                                <i class="fas fa-sitemap"></i>
                                Subcategories
                            </h6>
                            <div class="filter-content">
                                <div class="filter-item">
                                    <input type="radio" id="subcat_all" value="" class="subcategory-filter" name="subcategory" {{ !request('subcategory') ? 'checked' : '' }}>
                                    <label for="subcat_all">
                                        All {{ $category->name }}
                                    </label>
                                </div>
                                @foreach($category->subcategories as $subcategory)
                                    <div class="filter-item">
                                        <input type="radio" id="subcat_{{ $subcategory->id }}" value="{{ $subcategory->slug }}" class="subcategory-filter" name="subcategory" {{ request('subcategory') == $subcategory->slug ? 'checked' : '' }}>
                                        <label for="subcat_{{ $subcategory->id }}">
                                            {{ $subcategory->name }}
                                            <span class="count">({{ $subcategory->products_count ?? 0 }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
                    @if(isset($availableColors) && $availableColors->count() > 0)
                        <div class="filter-section">
                            <h6 class="filter-title">
                                <i class="fas fa-palette"></i>
                                Colors
                            </h6>
                            <div class="filter-content">
                                @foreach($availableColors as $color)
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
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Sizes -->
                    @if(isset($availableSizes) && $availableSizes->count() > 0)
                        <div class="filter-section">
                            <h6 class="filter-title">
                                <i class="fas fa-expand-arrows-alt"></i>
                                Sizes
                            </h6>
                            <div class="filter-content">
                                @foreach($availableSizes as $size)
                                    <div class="filter-item size-item">
                                        <input type="checkbox" id="size_{{ $loop->index }}" value="{{ $size }}" class="size-filter">
                                        <label for="size_{{ $loop->index }}" class="size-label">
                                            <span class="size-box">{{ $size }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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
                                                <i class="fas fa-eye"></i>
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
    // Filter functionality
    $('.subcategory-filter, .brand-filter, .color-filter, .size-filter').on('change', function() {
        applyFilters();
    });

    $('#applyPriceFilter').on('click', function() {
        applyFilters();
    });

    $('#clearFilters, #clearAllFilters').on('click', function() {
        // Clear all filter inputs
        $('.subcategory-filter, .brand-filter, .color-filter, .size-filter').prop('checked', false);
        $('#min_price, #max_price').val('');

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
        let sortBy = $('#sortBy').val();

        // Build URL with filters
        let url = new URL(window.location.href);
        url.search = '';

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

        // Set subcategory filters
        if (urlParams.has('subcategory')) {
            const subcategories = urlParams.get('subcategory').split(',');
            subcategories.forEach(subcategory => {
                $(`.subcategory-filter[value="${subcategory}"]`).prop('checked', true);
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
