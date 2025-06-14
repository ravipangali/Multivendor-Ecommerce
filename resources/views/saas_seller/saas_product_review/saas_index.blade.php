@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Product Reviews')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Product Reviews
                    @if(isset($product))
                        for {{ $product->name }}
                    @endif
                </h5>
                <div>
                    @if(isset($product))
                        <a href="{{ route('seller.products.show', $product->id) }}" class="btn btn-info">
                            <i class="align-middle" data-feather="eye"></i> View Product
                        </a>
                    @endif
                    
                    <!-- Filter by rating -->
                    <div class="dropdown d-inline-block ms-2">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="ratingFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter by Rating
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="ratingFilterDropdown">
                            <li><a class="dropdown-item {{ !request('rating') ? 'active' : '' }}" href="{{ route('seller.reviews.index') }}">All Ratings</a></li>
                            @for($i = 5; $i >= 1; $i--)
                                <li><a class="dropdown-item {{ request('rating') == $i ? 'active' : '' }}" href="{{ route('seller.reviews.index', ['rating' => $i]) }}">
                                    {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                </a></li>
                            @endfor
                        </ul>
                    </div>
                    
                    <a href="{{ route('seller.reviews.analytics') }}" class="btn btn-primary ms-2">
                        <i class="align-middle" data-feather="bar-chart-2"></i> Review Analytics
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

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $key => $review)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('seller.products.show', $review->product_id) }}">
                                        {{ $review->product->name }}
                                    </a>
                                </td>
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
                                        <span class="ms-1">({{ $review->rating }})</span>
                                    </div>
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
                                <td colspan="7" class="text-center">No reviews found.</td>
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