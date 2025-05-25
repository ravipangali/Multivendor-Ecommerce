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
                            <th>Created At:</th>
                            <td>{{ $review->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-muted">Review Comment</h6>
                        <div class="card">
                            <div class="card-body bg-light">
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
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

