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
                        <li class="breadcrumb-item active" aria-current="page">Brands</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Brands Header -->
<section class="brands-header">
    <div class="container">
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="header-text">
                        <h1 class="page-title">Premium Brands</h1>
                        <p class="page-subtitle">
                            Explore our collection of trusted brands offering quality products and innovative solutions
                        </p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="header-stats">
                        <div class="stat-card">
                            <span class="stat-number">{{ $brands->total() }}</span>
                            <span class="stat-label">Available Brands</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Brands Section -->
<section class="brands-section">
    <div class="container">
        <!-- Search and Filter Header -->
        <div class="filters-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="search-section">
                        <form action="{{ route('customer.brands') }}" method="GET" class="search-form">
                            <div class="search-input-group">
                                <input type="text" name="search" class="search-input"
                                       placeholder="Search brands..." value="{{ request('search') }}">
                                <button type="submit" class="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="sorting-controls">
                        <div class="sort-wrapper">
                            <label for="sortBy" class="sort-label">Sort by:</label>
                            <select name="sort_by" id="sortBy" class="sort-select">
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                <option value="products" {{ request('sort_by') == 'products' ? 'selected' : '' }}>Most Products</option>
                                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brands Grid -->
        <div class="brands-grid">
            <div class="row g-4">
                @forelse($brands as $brand)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="brand-card">
                            <div class="brand-image-container">
                                @if($brand->image)
                                    <img src="{{ asset('storage/' . $brand->image) }}"
                                         alt="{{ $brand->name }}"
                                         class="brand-logo brand-img">
                                @else
                                    <div class="brand-logo-placeholder">
                                        <span class="brand-initial">{{ substr($brand->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="brand-overlay">
                                    <a href="{{ route('customer.brand', $brand->slug) }}" class="brand-link">
                                        <i class="fas fa-eye"></i>
                                        <span>View Products</span>
                                    </a>
                                </div>
                            </div>

                            <div class="brand-content">
                                <h5 class="brand-name">{{ $brand->name }}</h5>
                                @if($brand->description)
                                    <p class="brand-description">{{ Str::limit($brand->description, 80) }}</p>
                                @endif

                                <div class="brand-stats">
                                    <div class="stat-item">
                                        <span class="stat-number">{{ $brand->products_count }}</span>
                                        <span class="stat-label">{{ Str::plural('Product', $brand->products_count) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="brand-footer">
                                <a href="{{ route('customer.brand', $brand->slug) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-store me-2"></i>Shop Brand
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-brands">
                            <div class="empty-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h4 class="empty-title">No brands found</h4>
                            <p class="empty-text">
                                @if(request('search'))
                                    No brands match your search criteria. Try adjusting your search terms.
                                @else
                                    Currently there are no brands available.
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('customer.brands') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>View All Brands
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($brands->hasPages())
            <div class="pagination-wrapper">
                {{ $brands->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Featured Brands Section -->
@if(!request('search') && $brands->currentPage() == 1)
<section class="featured-brands-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Why Choose Our Brands?</h2>
            <p class="section-subtitle">Discover the benefits of shopping with our trusted brand partners</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h5 class="feature-title">Authentic Products</h5>
                    <p class="feature-text">All brands are verified and offer 100% authentic products with quality guarantees.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5 class="feature-title">Fast Delivery</h5>
                    <p class="feature-text">Quick and reliable delivery from trusted brand partners across the country.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h5 class="feature-title">Premium Quality</h5>
                    <p class="feature-text">Curated selection of premium brands known for their superior quality and reliability.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="feature-title">24/7 Support</h5>
                    <p class="feature-text">Round-the-clock customer support for all your brand-related queries and concerns.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
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

    // Brand card hover effects
    $('.brand-card').hover(
        function() {
            $(this).find('.brand-overlay').removeClass('opacity-0').addClass('opacity-100');
        },
        function() {
            $(this).find('.brand-overlay').removeClass('opacity-100').addClass('opacity-0');
        }
    );

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

    // Observe brand cards
    document.querySelectorAll('.brand-card, .feature-card').forEach((card, index) => {
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

/* Brands Header */
.brands-header {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    padding: 3rem 0;
}

.header-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.header-content::before {
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

.page-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.page-subtitle {
    color: var(--text-medium);
    font-size: 1.125rem;
    line-height: 1.6;
    margin: 0;
}

.header-stats {
    display: flex;
    justify-content: center;
}

.stat-card {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    text-align: center;
    box-shadow: var(--shadow-md);
    min-width: 150px;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
}

/* Brands Section */
.brands-section {
    padding: 3rem 0;
    background: var(--white);
}

/* Filters Header */
.filters-header {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 3rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
}

/* Search Section */
.search-input-group {
    position: relative;
    display: flex;
    max-width: 400px;
}

.search-input {
    width: 100%;
    padding: 0.875rem 3.5rem 0.875rem 1rem;
    border: 2px solid var(--border-medium);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
}

.search-btn {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    width: 3.5rem;
    border: none;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-btn:hover {
    background: var(--primary-dark);
}

/* Sorting Controls */
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
    white-space: nowrap;
}

.sort-select {
    min-width: 200px;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-medium);
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

/* Brand Cards */
.brand-card {
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

.brand-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
    border-color: var(--primary-color);
}

.brand-image-container {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    background: linear-gradient(135deg, var(--accent-color), #f8fafc);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.brand-logo {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.brand-card:hover .brand-logo {
    transform: scale(1.05);
}

.brand-logo-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 2rem;
    font-weight: 700;
    box-shadow: var(--shadow-md);
}

.brand-initial {
    text-transform: uppercase;
}

.brand-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.brand-link {
    color: var(--white);
    text-decoration: none;
    text-align: center;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.brand-card:hover .brand-link {
    transform: translateY(0);
}

.brand-link i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.brand-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    text-align: center;
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    line-height: 1.3;
}

.brand-description {
    color: var(--text-medium);
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0 0 1.5rem 0;
    flex: 1;
}

.brand-stats {
    display: flex;
    justify-content: center;
    margin-bottom: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-item .stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-item .stat-label {
    font-size: 0.75rem;
    color: var(--text-light);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.brand-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
    background: var(--accent-color);
}

/* Empty Brands */
.empty-brands {
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

/* Featured Brands Section */
.featured-brands-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--accent-color), #f8fafc);
}

.section-header {
    margin-bottom: 3rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
}

.section-subtitle {
    color: var(--text-medium);
    font-size: 1.125rem;
    margin: 0;
}

.feature-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem 2rem;
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    height: 100%;
    transition: all 0.3s ease;
}

.feature-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--white);
    font-size: 2rem;
    box-shadow: var(--shadow-md);
}

.feature-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
}

.feature-text {
    color: var(--text-medium);
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }

    .filters-header {
        text-align: center;
    }

    .sorting-controls {
        justify-content: center;
        margin-top: 1.5rem;
    }

    .sort-wrapper {
        flex-direction: column;
        gap: 0.5rem;
    }

    .search-input-group {
        max-width: 100%;
    }

    .brand-image-container {
        padding: 1.5rem;
    }

    .brand-content {
        padding: 1.25rem;
    }

    .brand-footer {
        padding: 1.25rem;
    }

    .feature-card {
        padding: 2rem 1.5rem;
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }

    .header-content {
        padding: 2rem;
        text-align: center;
    }

    .header-stats {
        margin-top: 2rem;
    }

    .stat-card {
        padding: 1.5rem;
        min-width: 120px;
    }

    .stat-number {
        font-size: 2rem;
    }

    .section-title {
        font-size: 1.75rem;
    }
}

/* Mobile-Responsive Brand Image Styles */
.brand-img {
    width: 100%;
    height: auto;
    max-height: 150px;
    object-fit: contain;
    object-position: center;
    padding: 10px;
}

.brand-logo {
    transition: transform 0.3s ease;
}

.brand-card:hover .brand-logo {
    transform: scale(1.05);
}

/* Mobile specific brand image optimizations */
@media (max-width: 768px) {
    .brand-img {
        max-height: 120px;
        padding: 8px;
    }

    .brand-image-container {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .brand-img {
        max-height: 100px;
        padding: 5px;
    }

    .brand-image-container {
        padding: 1rem;
    }

    .brand-logo-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

/* Mobile-Responsive Typography Styles */
@media (max-width: 768px) {
    /* Page titles */
    .page-title {
        font-size: 2rem !important;
        line-height: 1.2;
    }
    
    .page-subtitle {
        font-size: 1rem !important;
        line-height: 1.4;
    }
    
    /* Brand card typography */
    .brand-name {
        font-size: 1rem !important;
        line-height: 1.3;
    }
    
    .brand-description {
        font-size: 0.85rem !important;
        line-height: 1.4;
    }
    
    .brand-stats {
        font-size: 0.8rem !important;
    }
    
    .products-count {
        font-size: 0.75rem !important;
    }
    
    /* Statistics typography */
    .stat-number {
        font-size: 1.75rem !important;
    }
    
    .stat-label {
        font-size: 0.8rem !important;
    }
    
    /* Section headers */
    .section-title {
        font-size: 1.75rem !important;
    }
    
    .section-subtitle {
        font-size: 0.95rem !important;
    }
    
    /* Button typography */
    .btn {
        font-size: 0.85rem !important;
        padding: 0.6rem 1rem !important;
    }
    
    .view-products-btn {
        font-size: 0.8rem !important;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .page-title {
        font-size: 1.75rem !important;
    }
    
    .page-subtitle {
        font-size: 0.9rem !important;
    }
    
    .brand-name {
        font-size: 0.9rem !important;
    }
    
    .brand-description {
        font-size: 0.8rem !important;
    }
    
    .brand-stats {
        font-size: 0.75rem !important;
    }
    
    .products-count {
        font-size: 0.7rem !important;
    }
    
    .stat-number {
        font-size: 1.5rem !important;
    }
    
    .stat-label {
        font-size: 0.75rem !important;
    }
    
    .section-title {
        font-size: 1.5rem !important;
    }
    
    .btn {
        font-size: 0.8rem !important;
        padding: 0.5rem 0.8rem !important;
    }
    
    .view-products-btn {
        font-size: 0.75rem !important;
    }
}
</style>
@endpush
