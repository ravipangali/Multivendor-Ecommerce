@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Flash Deal Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Flash Deal Details</h5>
                <div>
                    <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-success">
                        <i class="align-middle" data-feather="tag"></i> Manage Products
                    </a>
                    <a href="{{ route('admin.flash-deals.edit', $flashDeal->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Deal
                    </a>
                    <a href="{{ route('admin.flash-deals.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    @if($flashDeal->banner_image)
                        <div class="mb-3">
                            <h6 class="text-muted">Banner Image</h6>
                            <img src="{{ asset('storage/'.$flashDeal->banner_image) }}" alt="{{ $flashDeal->title }}" class="img-fluid" style="max-height: 300px;">
                        </div>
                    @else
                        <div class="alert alert-warning">
                            No banner image available for this flash deal.
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Flash Deal Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">Title</th>
                            <td>{{ $flashDeal->title }}</td>
                        </tr>
                        <tr>
                            <th>Start Time</th>
                            <td>{{ $flashDeal->start_time->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>End Time</th>
                            <td>{{ $flashDeal->end_time->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($flashDeal->end_time < now())
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($flashDeal->start_time > now())
                                    <span class="badge bg-warning">Upcoming</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Products</th>
                            <td>{{ $flashDeal->products->count() }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $flashDeal->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $flashDeal->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Products in Flash Deal</h6>
                    @if($flashDeal->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Discount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flashDeal->products as $flashDealProduct)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($flashDealProduct->images && $flashDealProduct->images->count() > 0)
                                                        <img src="{{ asset($flashDealProduct->images->first()->image_url) }}"
                                                            alt="{{ $flashDealProduct->name }}" width="40" height="40" class="me-2 img-thumbnail">
                                                    @endif
                                                    {{ $flashDealProduct ? $flashDealProduct->name : 'Product Deleted' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($flashDealProduct->pivot->discount_type == 'percentage')
                                                    {{ $flashDealProduct->pivot->discount_value }}%
                                                @else
                                                    Rs {{ number_format($flashDealProduct->pivot_discount_value, 2) }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.flash-deal-products.edit', $flashDealProduct->id) }}" class="btn btn-sm btn-info">
                                                    <i class="align-middle" data-feather="edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-primary">
                                View All Products
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="align-middle me-2" data-feather="info"></i>
                            No products have been added to this flash deal yet.
                            <div class="mt-2">
                                <a href="{{ route('admin.flash-deal-products.create', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-sm btn-primary">
                                    Add Products
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('admin.flash-deals.destroy', $flashDeal->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Flash Deal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
