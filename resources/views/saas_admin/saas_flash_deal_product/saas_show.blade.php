@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Flash Deal Product Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Flash Deal Product Details</h5>
                <div>
                    <a href="{{ route('admin.flash-deal-products.edit', $flashDealProduct->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Discount
                    </a>
                    <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDealProduct->flash_deal_id]) }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <strong>Flash Deal:</strong> {{ $flashDealProduct->flashDeal->title }}<br>
                        <strong>Period:</strong> {{ $flashDealProduct->flashDeal->start_time->format('M d, Y H:i') }} - {{ $flashDealProduct->flashDeal->end_time->format('M d, Y H:i') }}<br>
                        <strong>Status:</strong>
                        @if($flashDealProduct->flashDeal->end_time < now())
                            <span class="badge bg-danger">Expired</span>
                        @elseif($flashDealProduct->flashDeal->start_time > now())
                            <span class="badge bg-warning">Upcoming</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.flash-deals.show', $flashDealProduct->flash_deal_id) }}" class="btn btn-sm btn-info">
                            <i class="align-middle" data-feather="eye"></i> View Flash Deal
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Product Information</h6>
                    @if($flashDealProduct->product)
                        <div class="d-flex mb-3">
                            @if($flashDealProduct->product->images && $flashDealProduct->product->images->count() > 0)
                                <img src="{{ $flashDealProduct->product->images->first()->image_url }}"
                                    alt="{{ $flashDealProduct->product->name }}" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <div>
                                <h5>{{ $flashDealProduct->product->name }}</h5>
                                <p class="mb-1">SKU: {{ $flashDealProduct->product->SKU }}</p>
                                <p class="mb-1">
                                    <a href="{{ route('admin.products.show', $flashDealProduct->product->id) }}" class="btn btn-sm btn-primary">
                                        <i class="align-middle" data-feather="eye"></i> View Product
                                    </a>
                                </p>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th>Regular Price</th>
                                <td>Rs {{ number_format($flashDealProduct->product->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Discount Type</th>
                                <td>{{ ucfirst($flashDealProduct->discount_type) }}</td>
                            </tr>
                            <tr>
                                <th>Discount Value</th>
                                <td>
                                    @if($flashDealProduct->discount_type == 'percentage')
                                        {{ $flashDealProduct->discount_value }}%
                                    @else
                                        Rs {{ number_format($flashDealProduct->discount_value, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Discount Amount</th>
                                <td>
                                    @if($flashDealProduct->discount_type == 'percentage')
                                        Rs {{ number_format(($flashDealProduct->product->price * $flashDealProduct->discount_value / 100), 2) }}
                                    @else
                                        Rs {{ number_format($flashDealProduct->discount_value, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="table-success">
                                <th>Final Price</th>
                                <td>
                                    @if($flashDealProduct->discount_type == 'percentage')
                                        Rs {{ number_format($flashDealProduct->product->price - ($flashDealProduct->product->price * $flashDealProduct->discount_value / 100), 2) }}
                                    @else
                                        Rs {{ number_format($flashDealProduct->product->price - $flashDealProduct->discount_value, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $flashDealProduct->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $flashDealProduct->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-danger">
                            <i class="align-middle me-2" data-feather="alert-circle"></i>
                            The product associated with this flash deal item has been deleted.
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    @if($flashDealProduct->product)
                        <h6 class="text-muted mb-3">Product Description</h6>
                        <div class="card">
                            <div class="card-body">
                                {{ $flashDealProduct->product->short_description }}
                            </div>
                        </div>

                        <h6 class="text-muted mt-4 mb-3">Discount Visualization</h6>
                        <div class="card">
                            <div class="card-body">
                                <div class="progress" style="height: 30px;">
                                    @php
                                        $discountPercentage = 0;
                                        if ($flashDealProduct->discount_type == 'percentage') {
                                            $discountPercentage = $flashDealProduct->discount_value;
                                        } else {
                                            if ($flashDealProduct->product->price > 0) {
                                                $discountPercentage = ($flashDealProduct->discount_value / $flashDealProduct->product->price) * 100;
                                            }
                                        }
                                        $discountPercentage = min(100, $discountPercentage);
                                    @endphp
                                    <div class="progress-bar bg-danger" role="progressbar"
                                        style="width: {{ $discountPercentage }}%;"
                                        aria-valuenow="{{ $discountPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ round($discountPercentage) }}% OFF
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('admin.flash-deal-products.destroy', $flashDealProduct->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Remove from Flash Deal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
