@extends('saas_admin.saas_layouts.saas_layout')

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
                    <!-- Filter Dropdown -->
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter by Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                            <li><a class="dropdown-item {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.product-reviews.index', request()->except('status')) }}">All Reviews</a></li>
                            <li><a class="dropdown-item {{ request('status') == 'approved' ? 'active' : '' }}" href="{{ route('admin.product-reviews.index', array_merge(request()->all(), ['status' => 'approved'])) }}">Approved</a></li>
                            <li><a class="dropdown-item {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.product-reviews.index', array_merge(request()->all(), ['status' => 'pending'])) }}">Pending Approval</a></li>
                            <li><a class="dropdown-item {{ request('status') == 'reported' ? 'active' : '' }}" href="{{ route('admin.product-reviews.index', array_merge(request()->all(), ['status' => 'reported'])) }}">Reported</a></li>
                        </ul>
                    </div>

                    @if(isset($product))
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info">
                            <i class="align-middle" data-feather="eye"></i> View Product
                        </a>
                    @endif
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

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $key => $review)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.products.show', $review->product_id) }}">
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
                                <td>
                                    @if($review->is_reported)
                                        <span class="badge bg-danger">Reported</span>
                                    @elseif(!$review->is_approved)
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.product-reviews.show', $review->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>

                                        <!-- Toggle Approval -->
                                        <form action="{{ route('admin.product-reviews.toggle-approval', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $review->is_approved ? 'btn-warning' : 'btn-success' }}"
                                                    title="{{ $review->is_approved ? 'Disapprove' : 'Approve' }}">
                                                <i class="align-middle" data-feather="{{ $review->is_approved ? 'x-circle' : 'check-circle' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Clear Report (if reported) -->
                                        @if($review->is_reported)
                                            <form action="{{ route('admin.product-reviews.clear-report', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-info" title="Clear Report">
                                                    <i class="align-middle" data-feather="shield"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete -->
                                        <form action="{{ route('admin.product-reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
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
