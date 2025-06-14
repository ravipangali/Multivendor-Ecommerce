@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Review Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Review Details</h5>
                <div>
                    <a href="{{ route('admin.products.show', $review->product_id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Product
                    </a>
                    <a href="{{ route('admin.product-reviews.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Reviews
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Review Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="150">Product:</th>
                            <td>{{ $review->product->name }}</td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $review->customer->name }}</td>
                        </tr>
                        <tr>
                            <th>Rating:</th>
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
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($review->is_reported)
                                    <span class="badge bg-danger">Reported</span>
                                @elseif(!$review->is_approved)
                                    <span class="badge bg-warning">Pending Approval</span>
                                @else
                                    <span class="badge bg-success">Approved</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At:</th>
                            <td>{{ $review->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-muted">Customer Review</h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                <p class="mb-0">{{ $review->review }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Images -->
                    @if($review->hasImages())
                        <div class="mb-3">
                            <h6 class="text-muted">Review Images</h6>
                            <div class="row">
                                @foreach($review->getImageUrls() as $imageUrl)
                                    <div class="col-md-3 mb-2">
                                        <img src="{{ $imageUrl }}" alt="Review Image" class="img-fluid rounded">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Seller Response -->
                    @if($review->seller_response)
                        <div class="mb-3">
                            <h6 class="text-muted">Seller Response</h6>
                            <div class="card">
                                <div class="card-body bg-info bg-opacity-10">
                                    <p class="mb-0">{{ $review->seller_response }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Report Information -->
                    @if($review->is_reported && $review->report_reason)
                        <div class="mb-3">
                            <h6 class="text-muted text-danger">Report Reason</h6>
                            <div class="card border-danger">
                                <div class="card-body bg-danger bg-opacity-10">
                                    <p class="mb-0">{{ $review->report_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-end">
                        <!-- Toggle Approval -->
                        <form action="{{ route('admin.product-reviews.toggle-approval', $review->id) }}" method="POST" class="d-inline me-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $review->is_approved ? 'btn-warning' : 'btn-success' }}">
                                <i class="align-middle" data-feather="{{ $review->is_approved ? 'x-circle' : 'check-circle' }}"></i>
                                {{ $review->is_approved ? 'Disapprove' : 'Approve' }}
                            </button>
                        </form>

                        <!-- Clear Report -->
                        @if($review->is_reported)
                            <form action="{{ route('admin.product-reviews.clear-report', $review->id) }}" method="POST" class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info">
                                    <i class="align-middle" data-feather="shield"></i> Clear Report
                                </button>
                            </form>
                        @endif

                        <!-- Delete -->
                        <form action="{{ route('admin.product-reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger delete-confirm">
                                <i class="align-middle" data-feather="trash-2"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

