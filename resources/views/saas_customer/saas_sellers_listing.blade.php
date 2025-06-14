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
                        <li class="breadcrumb-item active" aria-current="page">Sellers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Sellers Header -->
<section class="sellers-header">
    <div class="container">
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="header-text">
                        <h1 class="page-title">Our Verified Sellers</h1>
                        <p class="page-subtitle">
                            Discover trusted sellers offering quality products and exceptional service
                        </p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="header-stats">
                        <div class="stat-card">
                            <span class="stat-number">{{ $sellers->total() }}</span>
                            <span class="stat-label">Active Sellers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sellers Section -->
<section class="sellers-section">
    <div class="container">
        <!-- Search and Filter Header -->
        <div class="filters-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="search-section">
                        <form action="{{ route('customer.sellers') }}" method="GET" class="search-form">
                            <div class="search-input-group">
                                <input type="text" name="search" class="search-input"
                                       placeholder="Search sellers..." value="{{ request('search') }}">
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
                                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                                <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                <option value="products" {{ request('sort_by') == 'products' ? 'selected' : '' }}>Most Products</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sellers Grid -->
        <div class="sellers-grid">
            <div class="row g-4">
                @forelse($sellers as $seller)
                    <div class="col-lg-4 col-md-6">
                        <div class="seller-card">
                            <div class="seller-header">
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
                                    <div class="verified-badge">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="seller-info">
                                    <h5 class="seller-name">{{ $seller->name }}</h5>
                                    @if($seller->sellerProfile && $seller->sellerProfile->business_name)
                                        <p class="business-name">{{ $seller->sellerProfile->business_name }}</p>
                                    @endif
                                    @if($seller->sellerProfile && $seller->sellerProfile->business_address)
                                        <div class="seller-location">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($seller->sellerProfile->business_address, 30) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="seller-body">
                                @if($seller->sellerProfile && $seller->sellerProfile->business_description)
                                    <p class="seller-description">
                                        {{ Str::limit($seller->sellerProfile->business_description, 120) }}
                                    </p>
                                @endif

                                <div class="seller-stats">
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <span class="stat-number">{{ $seller->total_products }}</span>
                                            <span class="stat-label">{{ Str::plural('Product', $seller->total_products) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <div class="rating-display">
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fa fa-star {{ $i <= $seller->average_rating ? 'active' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-text">{{ number_format($seller->average_rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stat-row">
                                        <div class="stat-item">
                                            <span class="stat-number">{{ $seller->total_reviews }}</span>
                                            <span class="stat-label">{{ Str::plural('Review', $seller->total_reviews) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-text">Member since {{ $seller->created_at->format('Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="seller-footer">
                                <a href="{{ route('customer.seller.profile', $seller->id) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-store me-2"></i>Visit Store
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-sellers">
                            <div class="empty-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h4 class="empty-title">No sellers found</h4>
                            <p class="empty-text">
                                @if(request('search'))
                                    No sellers match your search criteria. Try adjusting your search terms.
                                @else
                                    Currently there are no active sellers available.
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('customer.sellers') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>View All Sellers
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($sellers->hasPages())
            <div class="pagination-wrapper">
                {{ $sellers->appends(request()->query())->links() }}
            </div>
        @endif
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

    // Observe seller cards
    document.querySelectorAll('.seller-card').forEach((card, index) => {
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

/* Sellers Header */
.sellers-header {
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

/* Sellers Section */
.sellers-section {
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

/* Seller Cards */
.seller-card {
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

.seller-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
    border-color: var(--primary-color);
}

.seller-header {
    padding: 2rem 2rem 1rem;
    text-align: center;
    background: linear-gradient(135deg, var(--accent-color), #f8fafc);
    position: relative;
}

.seller-avatar-container {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.seller-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-md);
}

.seller-avatar-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 2rem;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-md);
}

.verified-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 24px;
    height: 24px;
    background: var(--success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 0.75rem;
    border: 2px solid var(--white);
}

.seller-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}

.business-name {
    color: var(--primary-color);
    font-size: 0.875rem;
    font-weight: 500;
    margin: 0 0 0.5rem 0;
}

.seller-location {
    color: var(--text-light);
    font-size: 0.75rem;
    margin: 0;
}

.seller-body {
    padding: 1.5rem 2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.seller-description {
    color: var(--text-medium);
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0 0 1.5rem 0;
    flex: 1;
}

.seller-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-item .stat-number {
    display: block;
    font-size: 1.25rem;
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

.stat-text {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-style: italic;
}

.rating-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
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

.rating-text {
    font-size: 0.75rem;
    color: var(--text-medium);
    font-weight: 500;
}

.seller-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-light);
    background: var(--accent-color);
}

/* Empty Sellers */
.empty-sellers {
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

    .seller-header {
        padding: 1.5rem 1.5rem 1rem;
    }

    .seller-body {
        padding: 1.5rem;
    }

    .seller-footer {
        padding: 1.5rem;
    }

    .stat-row {
        flex-direction: column;
        gap: 0.75rem;
    }

    .stat-item {
        text-align: center;
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
}
</style>
@endpush
