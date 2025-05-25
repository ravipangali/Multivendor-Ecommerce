@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Flash Deal Products')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    @if($flashDeal)
                        Products in "{{ $flashDeal->title }}" Flash Deal
                    @else
                        Flash Deal Products
                    @endif
                </h5>
                <div>
                    @if($flashDeal)
                        <a href="{{ route('admin.flash-deal-products.create', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-primary">
                            <i class="align-middle" data-feather="plus"></i> Add Product to Deal
                        </a>
                        <a href="{{ route('admin.flash-deals.show', $flashDeal->id) }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to Flash Deal
                        </a>
                    @else
                        <a href="{{ route('admin.flash-deals.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to Flash Deals
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($flashDeal)
                <div class="alert alert-info mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <strong>Flash Deal:</strong> {{ $flashDeal->title }}<br>
                            <strong>Period:</strong> {{ $flashDeal->start_time->format('M d, Y H:i') }} - {{ $flashDeal->end_time->format('M d, Y H:i') }}<br>
                            <strong>Status:</strong>
                            @if($flashDeal->end_time < now())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($flashDeal->start_time > now())
                                <span class="badge bg-warning">Upcoming</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('admin.flash-deals.edit', $flashDeal->id) }}" class="btn btn-sm btn-info">
                                <i class="align-middle" data-feather="edit"></i> Edit Flash Deal
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Regular Price</th>
                            <th>Discount Type</th>
                            <th>Discount Value</th>
                            <th>Final Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flashDealProducts as $key => $flashDealProduct)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($flashDealProduct->product && $flashDealProduct->product->images && $flashDealProduct->product->images->count() > 0)
                                            <img src="{{ asset($flashDealProduct->product->images->first()->image_url) }}"
                                                alt="{{ $flashDealProduct->product->name }}" width="40" height="40" class="me-2 img-thumbnail">
                                        @endif
                                        <div>
                                            @if($flashDealProduct->product)
                                                <a href="{{ route('admin.products.show', $flashDealProduct->product->id) }}" class="text-decoration-none">
                                                    {{ $flashDealProduct->product->name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">ID: {{ $flashDealProduct->product->id }}</small>
                                            @else
                                                <span class="text-danger">Product Deleted</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>Rs {{ number_format($flashDealProduct->product ? $flashDealProduct->product->price : 0, 2) }}</td>
                                <td>{{ ucfirst($flashDealProduct->discount_type) }}</td>
                                <td>
                                    @if($flashDealProduct->discount_type == 'percentage')
                                        {{ $flashDealProduct->discount_value }}%
                                    @else
                                        Rs {{ number_format($flashDealProduct->discount_value, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if($flashDealProduct->product)
                                        @if($flashDealProduct->discount_type == 'percentage')
                                            Rs {{ number_format($flashDealProduct->product->price - ($flashDealProduct->product->price * $flashDealProduct->discount_value / 100), 2) }}
                                        @else
                                            Rs {{ number_format($flashDealProduct->product->price - $flashDealProduct->discount_value, 2) }}
                                        @endif
                                    @else
                                        <span class="text-danger">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.flash-deal-products.edit', $flashDealProduct->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.flash-deal-products.destroy', $flashDealProduct->id) }}" method="POST" class="d-inline">
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
                                <td colspan="7" class="text-center">No products in this flash deal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $flashDealProducts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
