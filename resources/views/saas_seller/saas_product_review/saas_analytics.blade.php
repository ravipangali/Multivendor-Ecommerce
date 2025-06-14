@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Review Analytics')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Review Analytics</h5>
                <a href="{{ route('seller.reviews.index') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="list"></i> All Reviews
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Overview Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-feather="star" class="feather-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">{{ number_format($averageRating, 1) }}</div>
                                    <div class="small">Average Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-feather="message-circle" class="feather-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">{{ $totalReviews }}</div>
                                    <div class="small">Total Reviews</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-feather="thumbs-up" class="feather-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">{{ $ratingDistribution[5] + $ratingDistribution[4] }}</div>
                                    <div class="small">Positive Reviews</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-feather="alert-triangle" class="feather-lg"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="h4 mb-0">{{ $ratingDistribution[1] + $ratingDistribution[2] }}</div>
                                    <div class="small">Needs Attention</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Rating Distribution</h6>
                        </div>
                        <div class="card-body">
                            @foreach([5, 4, 3, 2, 1] as $rating)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-2" style="width: 60px;">
                                        {{ $rating }} Star{{ $rating > 1 ? 's' : '' }}
                                    </div>
                                    <div class="flex-grow-1 me-2">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $rating >= 4 ? 'success' : ($rating == 3 ? 'warning' : 'danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ $totalReviews > 0 ? ($ratingDistribution[$rating] / $totalReviews) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="width: 40px; text-align: right;">
                                        {{ $ratingDistribution[$rating] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Review Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="h2 text-primary">{{ number_format($averageRating, 1) }}/5</div>
                                <div class="stars mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($averageRating))
                                            <i class="text-warning" data-feather="star"></i>
                                        @else
                                            <i class="text-muted" data-feather="star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="text-muted">Based on {{ $totalReviews }} review{{ $totalReviews != 1 ? 's' : '' }}</div>
                            </div>

                            @if($totalReviews > 0)
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="h5 text-success">{{ number_format((($ratingDistribution[5] + $ratingDistribution[4]) / $totalReviews) * 100, 1) }}%</div>
                                        <div class="small text-muted">Positive</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="h5 text-warning">{{ number_format(($ratingDistribution[3] / $totalReviews) * 100, 1) }}%</div>
                                        <div class="small text-muted">Neutral</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="h5 text-danger">{{ number_format((($ratingDistribution[1] + $ratingDistribution[2]) / $totalReviews) * 100, 1) }}%</div>
                                        <div class="small text-muted">Negative</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Rated Products -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Top Rated Products</h6>
                        </div>
                        <div class="card-body">
                            @forelse($topRatedProducts as $product)
                                <div class="d-flex align-items-center mb-3">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset($product->images->first()->image_url) }}"
                                             alt="{{ $product->name }}"
                                             class="rounded me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i data-feather="image" class="text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ Str::limit($product->name, 30) }}</div>
                                        <div class="small text-muted">
                                            <span class="text-warning">{{ number_format($product->average_rating, 1) }}</span>
                                            <i data-feather="star" class="text-warning" style="width: 12px; height: 12px;"></i>
                                            ({{ $product->reviews_count }} review{{ $product->reviews_count != 1 ? 's' : '' }})
                                        </div>
                                    </div>
                                    <a href="{{ route('seller.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                    </a>
                                </div>
                            @empty
                                <p class="text-center text-muted">No products with reviews yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Most Reviewed Products</h6>
                        </div>
                        <div class="card-body">
                            @forelse($mostReviewedProducts as $product)
                                <div class="d-flex align-items-center mb-3">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset($product->images->first()->image_url) }}"
                                             alt="{{ $product->name }}"
                                             class="rounded me-3"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i data-feather="image" class="text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ Str::limit($product->name, 30) }}</div>
                                        <div class="small text-muted">
                                            {{ $product->reviews_count }} review{{ $product->reviews_count != 1 ? 's' : '' }}
                                            @if($product->average_rating)
                                                - <span class="text-warning">{{ number_format($product->average_rating, 1) }}</span>
                                                <i data-feather="star" class="text-warning" style="width: 12px; height: 12px;"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('seller.reviews.product', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="message-circle" style="width: 14px; height: 14px;"></i>
                                    </a>
                                </div>
                            @empty
                                <p class="text-center text-muted">No products with reviews yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @if($totalReviews == 0)
                <div class="text-center py-5">
                    <i data-feather="message-circle" class="text-muted mb-3" style="width: 64px; height: 64px;"></i>
                    <h4>No Reviews Yet</h4>
                    <p class="text-muted">Start selling products to receive customer reviews and see analytics here.</p>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-primary">
                        <i data-feather="package" class="me-1"></i> View Products
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize feather icons
    document.addEventListener("DOMContentLoaded", function() {
        feather.replace();
    });
</script>
@endsection
