@extends('saas_customer.saas_layout.saas_layout')

@section('title', 'Search Results - ' . request('q'))

@push('styles')
<style>
  .search-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 3rem 0;
    position: relative;
  }

  .search-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 30% 30%, rgba(171, 207, 55, 0.08) 0%, transparent 60%),
                radial-gradient(circle at 70% 70%, rgba(9, 113, 126, 0.05) 0%, transparent 60%);
    pointer-events: none;
  }

  .breadcrumb-modern {
    background: linear-gradient(135deg, var(--white), var(--accent-color));
    padding: 1.5rem 0;
    margin-bottom: 0;
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

  .search-header {
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }

  .search-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
  }

  .search-form {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .search-input-group {
    position: relative;
    margin-bottom: 1rem;
  }

  .search-input {
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    padding: 1rem 3rem 1rem 1rem;
    font-size: 1rem;
    width: 100%;
    transition: all 0.3s ease;
    background: var(--white);
  }

  .search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    border-radius: var(--radius-md);
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .search-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-50%) scale(1.05);
  }

  .search-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
  }

  .suggestion-tag {
    background: var(--accent-color);
    color: var(--text-dark);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid var(--border-light);
  }

  .suggestion-tag:hover {
    background: var(--primary-color);
    color: var(--white);
    text-decoration: none;
    transform: translateY(-1px);
  }

  .filters-sidebar {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    position: sticky;
    top: 2rem;
  }

  .filter-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-light);
  }

  .filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
  }

  .filter-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-size: 1rem;
  }

  .filter-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    transition: all 0.2s ease;
  }

  .filter-item:hover {
    padding-left: 0.5rem;
  }

  .filter-item input[type="checkbox"],
  .filter-item input[type="radio"] {
    margin-right: 0.75rem;
    width: 18px;
    height: 18px;
    accent-color: var(--primary-color);
  }

  .filter-item label {
    font-size: 0.875rem;
    color: var(--text-medium);
    cursor: pointer;
    margin: 0;
    flex: 1;
  }

  .filter-item:hover label {
    color: var(--text-dark);
  }

  .clear-filters-btn {
    background: var(--danger);
    color: var(--white);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
  }

  .clear-filters-btn:hover {
    background: #dc3545;
    transform: translateY(-1px);
  }

  .results-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .results-count {
    color: var(--text-medium);
    font-size: 0.875rem;
  }

  .sort-dropdown {
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    background: var(--white);
    color: var(--text-dark);
    min-width: 160px;
  }

  .sort-dropdown:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(171, 207, 55, 0.1);
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
  }

  .product-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    height: 100%;
    position: relative;
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
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
    color: var(--text-medium);
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
    background: linear-gradient(135deg, var(--danger), #dc3545);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
  }

  .product-content {
    padding: 1.5rem;
  }

  .product-brand {
    color: var(--text-light);
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }

  .product-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    line-height: 1.4;
  }

  .product-title a {
    color: inherit;
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

  .product-buttons {
    display: flex;
    gap: 0.5rem;
  }

  .btn-add-cart {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-add-cart:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
  }

  .btn-wishlist {
    width: 44px;
    height: 44px;
    background: transparent;
    color: var(--text-medium);
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-wishlist:hover {
    background: var(--secondary-color);
    color: var(--white);
    border-color: var(--secondary-color);
  }

  .no-results {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
  }

  .no-results-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--text-muted), #94a3b8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: var(--white);
  }

  .no-results h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
  }

  .no-results p {
    color: var(--text-medium);
    margin-bottom: 2rem;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--white);
    text-decoration: none;
  }

  @media (max-width: 768px) {
    .search-container {
      padding: 1rem 0;
    }

    .search-header {
      padding: 2rem;
      text-align: center;
    }

    .filters-sidebar {
      position: static;
      margin-bottom: 2rem;
    }

    .results-header {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }

    .products-grid {
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .search-input-group {
      margin-bottom: 1rem;
    }

    .search-suggestions {
      justify-content: center;
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
                        <li class="breadcrumb-item active" aria-current="page">Search Results</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Search Results Content -->
<section class="search-container">
    <div class="container">
        <!-- Search Header -->
        <div class="search-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">Search Results</h2>
                    <p class="mb-0 opacity-75">
                        @if(request('q'))
                            Showing results for "{{ request('q') }}"
                        @else
                            Browse all products
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                        <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                            <i class="fa fa-search me-1"></i>
                            {{ $products->total() ?? 0 }} {{ Str::plural('Result', $products->total() ?? 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-form">
            <form action="{{ route('customer.search') }}" method="GET" id="searchForm">
                <div class="search-input-group">
                    <input type="text"
                           class="search-input"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search for products, brands, categories..."
                           autocomplete="off">
                    <button type="submit" class="search-btn">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <!-- Popular Searches -->
                <div class="search-suggestions">
                    <span class="text-muted me-2">Popular:</span>
                    <a href="{{ route('customer.search', ['q' => 'electronics']) }}" class="suggestion-tag">Electronics</a>
                    <a href="{{ route('customer.search', ['q' => 'fashion']) }}" class="suggestion-tag">Fashion</a>
                    <a href="{{ route('customer.search', ['q' => 'home']) }}" class="suggestion-tag">Home & Garden</a>
                    <a href="{{ route('customer.search', ['q' => 'books']) }}" class="suggestion-tag">Books</a>
                    <a href="{{ route('customer.search', ['q' => 'sports']) }}" class="suggestion-tag">Sports</a>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <h5 class="mb-3">
                        <i class="fa fa-filter me-2"></i>Filters
                    </h5>

                    <!-- Categories -->
                    <div class="filter-section">
                        <h6 class="filter-title">Categories</h6>
                        @forelse($categories ?? [] as $category)
                            <div class="filter-item">
                                <input type="checkbox"
                                       id="cat_{{ $category->id }}"
                                       value="{{ $category->slug }}"
                                       class="category-filter"
                                       {{ in_array($category->slug, request('categories', [])) ? 'checked' : '' }}>
                                <label for="cat_{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @empty
                            <div class="filter-item">
                                <label class="text-muted">No categories available</label>
                            </div>
                        @endforelse
                    </div>

                    <!-- Brands -->
                    <div class="filter-section">
                        <h6 class="filter-title">Brands</h6>
                        @forelse($brands ?? [] as $brand)
                            <div class="filter-item">
                                <input type="checkbox"
                                       id="brand_{{ $brand->id }}"
                                       value="{{ $brand->slug }}"
                                       class="brand-filter"
                                       {{ in_array($brand->slug, request('brands', [])) ? 'checked' : '' }}>
                                <label for="brand_{{ $brand->id }}">{{ $brand->name }}</label>
                            </div>
                        @empty
                            <div class="filter-item">
                                <label class="text-muted">No brands available</label>
                            </div>
                        @endforelse
                    </div>

                    <!-- Price Range -->
                    <div class="filter-section">
                        <h6 class="filter-title">Price Range</h6>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <input type="number"
                                           class="form-control"
                                           placeholder="Min"
                                           id="min_price"
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number"
                                           class="form-control"
                                           placeholder="Max"
                                           id="max_price"
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-primary w-100" id="applyPriceFilter">Apply Filter</button>
                    </div>

                    <!-- Clear Filters -->
                    <div class="filter-section">
                        <button type="button" class="clear-filters-btn" id="clearFilters">
                            <i class="fa fa-refresh me-1"></i>Clear All Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Area -->
            <div class="col-lg-9">
                <div class="results-section">
                    <!-- Results Header -->
                    <div class="results-header">
                        <div>
                            <h4 class="mb-1">Search Results</h4>
                            <div class="results-count">
                                Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products
                            </div>
                        </div>
                        <div>
                            <select class="sort-dropdown" id="sortProducts">
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Most Relevant</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                    </div>

                    @if(isset($products) && $products->count() > 0)
                        <!-- Products Grid -->
                        <div class="products-grid">
                            @foreach($products as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="{{ $product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                             alt="{{ $product->name }}">

                                        @if(($product->discount ?? 0) > 0)
                                            <div class="product-badge">
                                                @if($product->discount_type === 'percentage')
                                                    {{ $product->discount }}% OFF
                                                @else
                                                    Rs. {{ $product->discount }} OFF
                                                @endif
                                            </div>
                                        @endif

                                        <div class="product-actions">
                                            <button class="action-btn add-to-wishlist" data-product-id="{{ $product->id }}" title="Add to Wishlist">
                                                <i class="far fa-heart"></i>
                                            </button>
                                            <a href="{{ route('customer.product.detail', $product->slug) }}" class="action-btn" title="Quick View">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="product-content">
                                        <div class="product-brand">{{ $product->brand->name ?? 'No Brand' }}</div>
                                        <h5 class="product-title">
                                            <a href="{{ route('customer.product.detail', $product->slug) }}">
                                                {{ Str::limit($product->name, 50) }}
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
                                            <span class="current-price">Rs. {{ number_format($product->final_price ?? $product->price, 2) }}</span>
                                            @if(($product->discount ?? 0) > 0)
                                                <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-stock" style="margin-bottom: 10px; font-size: 12px;">
                                            @if($product->stock > 0)
                                                <span style="color: #28a745;">✓ In Stock ({{ $product->stock }} available)</span>
                                            @else
                                                <span style="color: #dc3545;">✗ Out of Stock</span>
                                            @endif
                                        </div>

                                        <div class="product-buttons">
                                            @if($product->stock > 0)
                                                <button class="btn-add-cart add-to-cart" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                                </button>
                                            @else
                                                <button class="btn-add-cart" disabled style="background-color: #ccc; cursor: not-allowed;">
                                                    <i class="fas fa-times me-1"></i>Out of Stock
                                                </button>
                                            @endif
                                            <button class="btn-wishlist add-to-wishlist" data-product-id="{{ $product->id }}">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <!-- No Results -->
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="fa fa-search"></i>
                            </div>
                            <h3>No products found</h3>
                            <p>
                                @if(request('q'))
                                    We couldn't find any products matching "{{ request('q') }}". Try adjusting your search terms or browse our categories.
                                @else
                                    No products available at the moment. Check back later!
                                @endif
                            </p>
                            @if(request()->hasAny(['q', 'categories', 'brands', 'min_price', 'max_price']))
                                <a href="{{ route('customer.search') }}" class="btn-primary">
                                    <i class="fa fa-refresh me-2"></i>Clear Search
                                </a>
                            @else
                                <a href="{{ route('customer.products') }}" class="btn-primary">
                                    <i class="fa fa-shopping-bag me-2"></i>Browse All Products
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    // Filter functionality
    $('.category-filter, .brand-filter').on('change', function() {
        applyFilters();
    });

    $('#applyPriceFilter').on('click', function() {
        applyFilters();
    });

    $('#clearFilters').on('click', function() {
        $('.category-filter, .brand-filter').prop('checked', false);
        $('#min_price, #max_price').val('');
        applyFilters();
    });

    $('#sortProducts').on('change', function() {
        applyFilters();
    });

    function applyFilters() {
        const searchQuery = $('input[name="q"]').val();
        let categories = [];
        $('.category-filter:checked').each(function() {
            categories.push($(this).val());
        });

        let brands = [];
        $('.brand-filter:checked').each(function() {
            brands.push($(this).val());
        });

        const minPrice = $('#min_price').val();
        const maxPrice = $('#max_price').val();
        const sortBy = $('#sortProducts').val();

        // Build URL with filters
        let url = new URL(window.location.href);
        url.search = '';

        if (searchQuery) {
            url.searchParams.set('q', searchQuery);
        }
        if (categories.length > 0) {
            url.searchParams.set('categories', categories.join(','));
        }
        if (brands.length > 0) {
            url.searchParams.set('brands', brands.join(','));
        }
        if (minPrice) {
            url.searchParams.set('min_price', minPrice);
        }
        if (maxPrice) {
            url.searchParams.set('max_price', maxPrice);
        }
        if (sortBy && sortBy !== 'relevance') {
            url.searchParams.set('sort', sortBy);
        }

        window.location.href = url.toString();
    }

    // Add to cart functionality
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const $btn = $(this);
        const originalText = $btn.html();

        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Adding...');
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
                    showNotification('Product added to cart successfully!', 'success');
                    $btn.html('<i class="fas fa-check me-1"></i>Added!');
                    $btn.removeClass('btn-add-cart').addClass('btn-success');

                    setTimeout(() => {
                        $btn.html(originalText);
                        $btn.removeClass('btn-success').addClass('btn-add-cart');
                        $btn.prop('disabled', false);
                    }, 2000);
                } else {
                    showNotification(response.message || 'Failed to add product to cart', 'error');
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showNotification('Please login to add products to cart', 'warning');
                } else {
                    showNotification('Error occurred while adding product to cart', 'error');
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
                    showNotification(response.message, 'success');
                    const $icon = $btn.find('i');
                    if (response.in_wishlist) {
                        $icon.removeClass('far').addClass('fas text-danger');
                    } else {
                        $icon.removeClass('fas text-danger').addClass('far');
                    }
                } else {
                    showNotification(response.message || 'Failed to update wishlist', 'error');
                }
            },
            error: function(xhr) {
                showNotification('Error occurred while updating wishlist', 'error');
            }
        });
        @else
        showNotification('Please login to add products to wishlist', 'warning');
        @endauth
    });

    // Show notification function
    function showNotification(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
        const iconClass = type === 'success' ? 'fa-check-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle');

        const notification = $(`
            <div class="alert ${alertClass} position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease;">
                <i class="fa ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-auto" onclick="$(this).parent().remove()"></button>
            </div>
        `);

        $('body').append(notification);

        // Trigger animation
        setTimeout(() => {
            notification.css({
                'opacity': '1',
                'transform': 'translateX(0)'
            });
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.css({
                'opacity': '0',
                'transform': 'translateX(100%)'
            });
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }

    // Search auto-complete (basic implementation)
    const searchInput = $('.search-input');
    let searchTimeout;

    searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();

        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                // Here you could implement autocomplete functionality
                // For now, we'll just show a simple suggestion
            }, 300);
        }
    });

    // Animation on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.05}s, transform 0.6s ease ${index * 0.05}s`;
        observer.observe(card);
    });
});
</script>
@endpush
@endsection
