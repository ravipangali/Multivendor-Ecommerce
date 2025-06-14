@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Review Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Review Details</h5>
                <div>
                    <a href="{{ route('seller.products.show', $review->product_id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Product
                    </a>
                    <a href="{{ route('seller.reviews.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Reviews
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
                            <th>Created At:</th>
                            <td>{{ $review->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($review->seller_response)
                                    <span class="badge bg-success">Responded</span>
                                @elseif($review->is_reported)
                                    <span class="badge bg-warning">Reported</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <h6 class="text-muted">Review Comment</h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                <p class="mb-0">{{ $review->review }}</p>
                            </div>
                        </div>
                    </div>

                    @if($review->seller_response)
                        <div class="mb-4">
                            <h6 class="text-muted">Your Response</h6>
                            <div class="card">
                                <div class="card-body bg-light">
                                    <p class="mb-0">{{ $review->seller_response }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($review->is_reported)
                        <div class="mb-4">
                            <h6 class="text-muted">Report Reason</h6>
                            <div class="card">
                                <div class="card-body bg-light">
                                    <p class="mb-0">{{ $review->report_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$review->seller_response && !$review->is_reported)
                        <div class="mb-4">
                            <h6 class="text-muted">Respond to Review</h6>
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('seller.reviews.respond', $review->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea class="form-control" name="seller_response" rows="3" placeholder="Write your response to this review..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Response</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$review->is_reported)
                        <div class="mb-4">
                            <h6 class="text-muted">Report Review</h6>
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('seller.reviews.report', $review->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea class="form-control" name="report_reason" rows="3" placeholder="Reason for reporting this review..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-warning">Report Review</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
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