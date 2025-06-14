@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .review-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .review-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
  }

  .review-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .order-info-card {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
  }

  .product-review-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
  }

  .product-review-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .product-info {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .product-image {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-md);
    object-fit: cover;
    margin-right: 1rem;
    box-shadow: var(--shadow-sm);
  }

  .product-details h6 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
  }

  .product-variation {
    color: var(--text-medium);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
  }

  .product-quantity {
    color: var(--text-light);
    font-size: 0.75rem;
  }

  .star-rating {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }

  .star {
    font-size: 2rem;
    color: #dee2e6;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .star.active {
    color: #ffc107;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: block;
  }

  .form-control {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    background: var(--white);
    transition: all 0.2s ease;
    width: 100%;
  }

  .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .btn-custom {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    font-size: 1rem;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
  }

  .btn-primary-custom:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: var(--white);
  }

  .btn-secondary-custom {
    background: var(--text-medium);
    color: var(--white);
  }

  .btn-secondary-custom:hover {
    background: var(--text-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: var(--white);
  }

  .already-reviewed {
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 1rem;
    border-radius: var(--radius-md);
    text-align: center;
    font-weight: 500;
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

  @media (max-width: 768px) {
    .review-container {
      padding: 1rem 0;
    }

    .review-header {
      padding: 1.5rem;
      text-align: center;
    }

    .product-review-card {
      padding: 1.5rem;
    }

    .product-info {
      flex-direction: column;
      text-align: center;
      gap: 1rem;
    }

    .product-image {
      margin: 0 auto;
    }

    .star-rating {
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
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.orders') }}">My Orders</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Review Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Review Content -->
<section class="review-container">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="review-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Review Your Order</h2>
                            <p class="mb-0 text-white opacity-75">
                                Order #{{ $order->order_number }}
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                                <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                    <i class="fa fa-star me-1"></i>
                                    {{ $order->items->count() }} Items to Review
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="review-content">
                    <!-- Order Info -->
                    <div class="order-info-card">
                        <h6 class="mb-3">
                            <i class="fa fa-info-circle me-2"></i>Order Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                <p class="mb-1"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Delivery Date:</strong> {{ $order->updated_at->format('M d, Y') }}</p>
                                <p class="mb-1"><strong>Total Amount:</strong> Rs. {{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <form action="{{ route('customer.order.review.submit', $order->id) }}" method="POST" id="reviewForm">
                        @csrf
                        <div class="products-to-review">
                            @foreach($order->items as $item)
                                <div class="product-review-card">
                                    <div class="product-info">
                                        <img src="{{ $item->product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                             alt="{{ $item->product->name }}"
                                             class="product-image">
                                        <div class="product-details">
                                            <h6>{{ $item->product->name }}</h6>
                                            @if($item->productVariation)
                                                <div class="product-variation">
                                                    {{ $item->productVariation->attribute->name }}: {{ $item->productVariation->attributeValue->value }}
                                                </div>
                                            @endif
                                            <div class="product-quantity">Quantity: {{ $item->quantity }}</div>
                                        </div>
                                    </div>

                                    @if($item->product->reviews->where('customer_id', Auth::id())->count() > 0)
                                        <div class="already-reviewed">
                                            <i class="fa fa-check-circle me-2"></i>
                                            You have already reviewed this product
                                        </div>
                                    @else
                                        <input type="hidden" name="reviews[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">

                                        <div class="form-group">
                                            <label class="form-label">Rating *</label>
                                            <div class="star-rating" data-product="{{ $item->product_id }}">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="star" data-rating="{{ $i }}">★</span>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="reviews[{{ $loop->index }}][rating]" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="review_{{ $item->product_id }}">Your Review *</label>
                                            <textarea class="form-control"
                                                      id="review_{{ $item->product_id }}"
                                                      name="reviews[{{ $loop->index }}][review]"
                                                      rows="4"
                                                      placeholder="Share your experience with this product. What did you like or dislike about it?"
                                                      required></textarea>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <a href="{{ route('customer.orders') }}" class="btn btn-secondary-custom me-3">
                                <i class="fa fa-arrow-left"></i> Back to Orders
                            </a>
                            <button type="submit" class="btn btn-primary-custom" id="submitReviews">
                                <i class="fa fa-star"></i> Submit Reviews
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star ratings
    const starRatings = document.querySelectorAll('.star-rating');

    starRatings.forEach(rating => {
        const stars = rating.querySelectorAll('.star');
        const productId = rating.getAttribute('data-product');
        const hiddenInput = rating.parentElement.querySelector('input[type="hidden"]');

        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                const ratingValue = parseInt(this.getAttribute('data-rating'));

                // Update hidden input
                hiddenInput.value = ratingValue;

                // Update star display
                stars.forEach((s, i) => {
                    if (i < ratingValue) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });

            star.addEventListener('mouseover', function() {
                const hoverValue = parseInt(this.getAttribute('data-rating'));

                stars.forEach((s, i) => {
                    if (i < hoverValue) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#dee2e6';
                    }
                });
            });
        });

        rating.addEventListener('mouseleave', function() {
            const currentRating = parseInt(hiddenInput.value) || 0;

            stars.forEach((s, i) => {
                if (i < currentRating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#dee2e6';
                }
            });
        });
    });

    // Form validation
    const reviewForm = document.getElementById('reviewForm');
    const submitButton = document.getElementById('submitReviews');

    reviewForm.addEventListener('submit', function(e) {
        const ratings = document.querySelectorAll('input[name*="[rating]"]');
        const reviews = document.querySelectorAll('textarea[name*="[review]"]');
        let isValid = true;

        // Check if at least one review is being submitted
        let hasReviews = false;
        ratings.forEach((rating, index) => {
            if (rating.value && reviews[index].value.trim()) {
                hasReviews = true;
            }
        });

        if (!hasReviews) {
            e.preventDefault();
            Swal.fire({
                title: 'No Reviews Selected',
                text: 'Please rate and review at least one product.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#abcf37'
            });
            return false;
        }

        // Validate each review
        ratings.forEach((rating, index) => {
            const review = reviews[index];

            if (rating.value && !review.value.trim()) {
                isValid = false;
                review.focus();
                Swal.fire({
                    title: 'Review Required',
                    text: 'Please write a review for the product you rated.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#abcf37'
                });
            } else if (!rating.value && review.value.trim()) {
                isValid = false;
                rating.parentElement.querySelector('.star-rating').style.border = '2px solid red';
                Swal.fire({
                    title: 'Rating Required',
                    text: 'Please rate the product you reviewed.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#abcf37'
                });
            }
        });

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting Reviews...';
        submitButton.disabled = true;
    });

    // Character counter for textareas
    const textareas = document.querySelectorAll('textarea[name*="[review]"]');
    textareas.forEach(textarea => {
        const maxLength = 1000;
        const counter = document.createElement('div');
        counter.className = 'text-muted small mt-1';
        counter.style.textAlign = 'right';
        textarea.parentElement.appendChild(counter);

        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} characters remaining`;

            if (remaining < 100) {
                counter.style.color = '#dc3545';
            } else if (remaining < 200) {
                counter.style.color = '#ffc107';
            } else {
                counter.style.color = '#6c757d';
            }
        }

        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });

    // Smooth animations
    const productCards = document.querySelectorAll('.product-review-card');

    // Intersection Observer for fade-in animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    productCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endpush
@endsection
