@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Product Reviews - ' . $product->name)

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Reviews for {{ $product->name }}</h5>
                <div>
                    <a href="{{ route('seller.products.show', $product->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Product
                    </a>
                    <a href="{{ route('seller.reviews.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> All Reviews
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('error') }}</div>
                </div>
            @endif

            <!-- Product Rating Summary -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Rating Summary</h6>
                            <div class="d-flex align-items-center mb-3">
                                <h1 class="display-4 me-3 mb-0">{{ number_format($product->reviews->avg('rating') ?? 0, 1) }}</h1>
                                <div>
                                    <div class="stars mb-1">
                                        @php $avgRating = $product->reviews->avg('rating') ?? 0; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                                <i class="text-warning" data-feather="star"></i>
                                            @else
                                                <i class="text-muted" data-feather="star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted mb-0">{{ $product->reviews->count() }} reviews</p>
                                </div>
                            </div>
                            
                            <!-- Rating Distribution -->
                            @for($i = 5; $i >= 1; $i--)
                                @php 
                                    $count = $product->reviews->where('rating', $i)->count();
                                    $percentage = $product->reviews->count() > 0 ? ($count / $product->reviews->count()) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-1">
                                    <div class="stars me-2">
                                        @for($j = 1; $j <= 5; $j++)
                                            @if($j <= $i)
                                                <i class="text-warning" data-feather="star" style="width: 14px; height: 14px;"></i>
                                            @else
                                                <i class="text-muted" data-feather="star" style="width: 14px; height: 14px;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="ms-2 text-muted small">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $key => $review)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $review->customer->name }}</td>
                                <td>
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="text-warning" data-feather="star"></i>
                                            @else
                                                <i class="text-muted" data-feather="star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $review->review }}">
                                        {{ Str::limit($review->review, 50) }}
                                    </span>
                                </td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($review->seller_response)
                                        <span class="badge bg-success">Responded</span>
                                    @elseif($review->is_reported)
                                        <span class="badge bg-warning">Reported</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('seller.reviews.show', $review->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No reviews found for this product.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
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