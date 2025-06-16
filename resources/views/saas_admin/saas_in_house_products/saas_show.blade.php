@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'View In-House Product')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold text-dark">{{ $product->name }}</h3>
                    <p class="text-muted mb-0">View in-house product details and information</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.in-house-products.edit', $product) }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="edit" style="width: 16px; height: 16px;"></i>
                        Edit Product
                    </a>
                    <a href="{{ route('admin.in-house-products.index') }}" class="btn btn-outline-secondary">
                        <i class="align-middle" data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i data-feather="dollar-sign" class="text-primary"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-primary mb-1">Rs {{ number_format($product->price, 2) }}</h5>
                    <p class="text-muted mb-0 small">Regular Price</p>
                    @if($product->discount > 0)
                        <span class="badge bg-success mt-1">
                            {{ $product->discount_type === 'percentage' ? $product->discount . '% OFF' : 'Rs ' . $product->discount . ' OFF' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i data-feather="package" class="text-info"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-info mb-1">
                        {{ $product->product_type === 'Digital' ? '∞' : number_format($product->stock) }}
                    </h5>
                    <p class="text-muted mb-0 small">
                        {{ $product->product_type === 'Digital' ? 'Digital Stock' : 'Units Available' }}
                    </p>
                    @if($product->product_type === 'Physical')
                        <span class="badge {{ $product->stock <= 10 ? 'bg-danger' : ($product->stock <= 50 ? 'bg-warning' : 'bg-success') }} mt-1">
                            {{ $product->stock <= 10 ? 'Low Stock' : ($product->stock <= 50 ? 'Medium Stock' : 'Good Stock') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i data-feather="eye" class="text-success"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </h5>
                    <p class="text-muted mb-0 small">Product Status</p>
                    @if($product->is_featured)
                        <span class="badge bg-warning mt-1">Featured</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i data-feather="tag" class="text-warning"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $product->SKU }}</h5>
                    <p class="text-muted mb-0 small">Product SKU</p>
                    <span class="badge bg-info mt-1">{{ $product->product_type }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i data-feather="image" class="me-2"></i>
                        Product Gallery
                        @if($product->images->count() > 0)
                            <span class="badge bg-primary ms-2">{{ $product->images->count() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($product->images->count() > 0)
                        <!-- Main Carousel -->
                        <div id="productImageCarousel" class="carousel slide" data-bs-ride="false">
                            <div class="carousel-inner rounded-3 overflow-hidden">
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="position-relative">
                                            <img src="{{ $image->image_url }}"
                                                 class="d-block w-100"
                                                 alt="{{ $product->name }}"
                                                 style="height: 400px; object-fit: contain; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/no-image.svg') }}';this.style.backgroundColor='#f8f9fa';this.style.border='1px solid #dee2e6';">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($product->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productImageCarousel" data-bs-slide="prev">
                                    <div class="bg-dark bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i data-feather="chevron-left" class="text-white" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productImageCarousel" data-bs-slide="next">
                                    <div class="bg-dark bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i data-feather="chevron-right" class="text-white" style="width: 20px; height: 20px;"></i>
                                    </div>
                                    <span class="visually-hidden">Next</span>
                                </button>

                                <!-- Carousel Indicators -->
                                <div class="carousel-indicators">
                                    @foreach($product->images as $index => $image)
                                        <button type="button" data-bs-target="#productImageCarousel" data-bs-slide-to="{{ $index }}"
                                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnail Gallery -->
                        @if($product->images->count() > 1)
                            <div class="row g-2 mt-3">
                                @foreach($product->images as $index => $image)
                                    <div class="col-3">
                                        <div class="position-relative">
                                            <img src="{{ $image->image_url }}"
                                                 class="img-thumbnail w-100 cursor-pointer thumbnail-image"
                                                 style="height: 80px; object-fit: cover; transition: all 0.3s ease;"
                                                 data-slide-to="{{ $index }}"
                                                 onclick="changeSlide({{ $index }})"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/no-image.svg') }}';this.style.backgroundColor='#f8f9fa';">
                                            @if($index === 0)
                                                <div class="position-absolute top-0 start-0">
                                                    <span class="badge bg-primary">Main</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-3 p-4 d-inline-block">
                                <i data-feather="image" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                                <p class="text-muted mb-0">No images available for this product</p>
                                <small class="text-muted">Add images in the edit page</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i data-feather="info" class="me-2"></i>
                        Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Product Badges -->
                    <div class="mb-4">
                        <span class="badge bg-primary me-2 fs-6">In-House Product</span>
                        @if($product->product_type === 'Digital')
                            <span class="badge bg-info me-2 fs-6">Digital</span>
                        @else
                            <span class="badge bg-success me-2 fs-6">Physical</span>
                        @endif
                        @if($product->is_featured)
                            <span class="badge bg-warning me-2 fs-6">Featured</span>
                        @endif
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Product Details -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="bg-light rounded-3 p-3">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-semibold text-muted small">CATEGORY</span>
                                    </div>
                                    <div class="col-8">
                                        <span class="fw-medium">{{ $product->category->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($product->subcategory)
                            <div class="col-12">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            <span class="fw-semibold text-muted small">SUB CATEGORY</span>
                                        </div>
                                        <div class="col-8">
                                            <span class="fw-medium">{{ $product->subcategory->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($product->childCategory)
                            <div class="col-12">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="row align-items-center">
                                        <div class="col-4">
                                            <span class="fw-semibold text-muted small">CHILD CATEGORY</span>
                                        </div>
                                        <div class="col-8">
                                            <span class="fw-medium">{{ $product->childCategory->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <div class="bg-light rounded-3 p-3">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-semibold text-muted small">BRAND</span>
                                    </div>
                                    <div class="col-8">
                                        <span class="fw-medium">{{ $product->brand->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="bg-light rounded-3 p-3">
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <span class="fw-semibold text-muted small">UNIT</span>
                                    </div>
                                    <div class="col-8">
                                        <span class="fw-medium">{{ $product->unit->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3">
                                <div class="text-center">
                                    <span class="fw-semibold text-muted small d-block">CREATED</span>
                                    <span class="fw-medium small">{{ $product->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="bg-light rounded-3 p-3">
                                <div class="text-center">
                                    <span class="fw-semibold text-muted small d-block">UPDATED</span>
                                    <span class="fw-medium small">{{ $product->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Descriptions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i data-feather="file-text" class="me-2"></i>
                        Product Description
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="border-start border-primary border-4 ps-3">
                                <h6 class="fw-semibold text-primary mb-2">Short Description</h6>
                                <p class="text-muted mb-0 lh-lg">{{ $product->short_description }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="border-start border-info border-4 ps-3">
                                <h6 class="fw-semibold text-info mb-2">Full Description</h6>
                                <div class="text-muted lh-lg">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Variations -->
    @if($product->variations->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <i data-feather="layers" class="me-2"></i>
                            Product Variations
                            <span class="badge bg-primary ms-2">{{ $product->variations->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Attribute</th>
                                        <th class="border-0 fw-semibold">Value</th>
                                        <th class="border-0 fw-semibold">Price</th>
                                        <th class="border-0 fw-semibold">Stock</th>
                                        <th class="border-0 fw-semibold">SKU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->variations as $variation)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $variation->attribute->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $variation->attributeValue->value ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-success">Rs {{ number_format($variation->price, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $variation->stock <= 10 ? 'bg-danger' : ($variation->stock <= 50 ? 'bg-warning' : 'bg-success') }}">
                                                    {{ number_format($variation->stock) }}
                                                </span>
                                            </td>
                                            <td>
                                                <code class="bg-light text-dark px-2 py-1 rounded">{{ $variation->sku }}</code>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .carousel-control-prev, .carousel-control-next {
        width: auto;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    #productImageCarousel:hover .carousel-control-prev,
    #productImageCarousel:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-indicators {
        margin-bottom: -2rem;
    }

    .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 4px;
        background-color: rgba(255, 255, 255, 0.7);
        border: 2px solid transparent;
    }

    .carousel-indicators button.active {
        background-color: #fff;
        border-color: var(--bs-primary);
    }

    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }

    .fs-6 {
        font-size: 0.875rem !important;
    }

    .lh-lg {
        line-height: 1.8 !important;
    }

    .thumbnail-image:hover {
        transform: scale(1.05);
        border-color: var(--bs-primary) !important;
    }

    .thumbnail-image.active {
        border-color: var(--bs-primary) !important;
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize Bootstrap carousel
    const carousel = document.querySelector('#productImageCarousel');
    let bsCarousel = null;

    if (carousel) {
        bsCarousel = new bootstrap.Carousel(carousel, {
            interval: false, // Don't auto-advance
            wrap: true,
            keyboard: true
        });
    }

    // Handle image loading errors
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            if (!this.dataset.errorHandled) {
                this.dataset.errorHandled = 'true';
                this.src = '{{ asset("images/no-image.svg") }}';
                this.style.backgroundColor = '#f8f9fa';
                this.style.border = '1px solid #dee2e6';
            }
        });
    });

    // Update thumbnail active states when carousel changes
    if (carousel) {
        carousel.addEventListener('slide.bs.carousel', function (event) {
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail-image').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Add active class to current thumbnail
            const activeThumb = document.querySelector(`.thumbnail-image[data-slide-to="${event.to}"]`);
            if (activeThumb) {
                activeThumb.classList.add('active');
            }
        });

        // Set initial active thumbnail
        const firstThumb = document.querySelector('.thumbnail-image[data-slide-to="0"]');
        if (firstThumb) {
            firstThumb.classList.add('active');
        }
    }
});

// Global function to change carousel slide
function changeSlide(slideIndex) {
    const carousel = document.querySelector('#productImageCarousel');
    if (carousel) {
        const bsCarousel = bootstrap.Carousel.getInstance(carousel) || new bootstrap.Carousel(carousel);
        bsCarousel.to(slideIndex);
    }
}
</script>
@endpush
