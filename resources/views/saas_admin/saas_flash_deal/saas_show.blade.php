@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Flash Deal Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Flash Deal Details: {{ $flashDeal->title }}</h5>
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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif

            <div class="row">
                <!-- Flash Deal Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Flash Deal Information</h6>
                        </div>
                        <div class="card-body">
                            <!-- Banner Image -->
                            @if($flashDeal->banner_image)
                                <div class="mb-4 text-center">
                                    <h6 class="text-muted">Banner Image</h6>
                                    <img src="{{ asset('storage/'.$flashDeal->banner_image) }}" alt="{{ $flashDeal->title }}" class="img-fluid rounded" style="max-height: 250px;">
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Title</th>
                                    <td>{{ $flashDeal->title }}</td>
                                </tr>

                                <tr>
                                    <th>Start Time</th>
                                    <td>
                                        <span class="fw-bold">{{ $flashDeal->start_time->format('M d, Y') }}</span>
                                        <br><small class="text-muted">{{ $flashDeal->start_time->format('H:i A') }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>End Time</th>
                                    <td>
                                        <span class="fw-bold">{{ $flashDeal->end_time->format('M d, Y') }}</span>
                                        <br><small class="text-muted">{{ $flashDeal->end_time->format('H:i A') }}</small>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Time Status</th>
                                    <td>
                                        @if($flashDeal->end_time < now())
                                            <span class="badge bg-danger fs-6">Expired</span>
                                        @elseif($flashDeal->start_time > now())
                                            <span class="badge bg-warning fs-6">Upcoming</span>
                                        @else
                                            <span class="badge bg-success fs-6">Active</span>
                                        @endif
                                    </td>
                                </tr>


                                <tr>
                                    <th>Products Count</th>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $flashDeal->products->count() }} products</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $flashDeal->created_at->format('M d, Y H:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $flashDeal->updated_at->format('M d, Y H:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.flash-deals.edit', $flashDeal->id) }}" class="btn btn-primary w-100">
                                    <i class="align-middle" data-feather="edit"></i> Edit Deal
                                </a>

                                <form action="{{ route('admin.flash-deals.duplicate', $flashDeal->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-info w-100">
                                        <i class="align-middle" data-feather="copy"></i> Duplicate Deal
                                    </button>
                                </form>

                                <form action="{{ route('admin.flash-deals.destroy', $flashDeal->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100 delete-confirm">
                                        <i class="align-middle" data-feather="trash-2"></i> Delete Deal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    @if($flashDeal->products->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h4 class="stat-number text-primary">{{ $flashDeal->products->count() }}</h4>
                                        <p class="stat-label">Products</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h4 class="stat-number text-success">Rs {{ number_format($flashDeal->products->sum('price')) }}</h4>
                                        <p class="stat-label">Total Value</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Products Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Products in Flash Deal</h6>
                            <a href="{{ route('admin.flash-deal-products.create', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-sm btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Add Products
                            </a>
                        </div>
                        <div class="card-body">
                            @if($flashDeal->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="50%">Product</th>
                                                <th width="15%">Original Price</th>
                                                <th width="15%">Discount</th>
                                                <th width="15%">Sale Price</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($flashDeal->products->take(5) as $flashDealProduct)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($flashDealProduct->images && $flashDealProduct->images->count() > 0)
                                                                <img src="{{ asset($flashDealProduct->images->first()->image_url) }}"
                                                                    alt="{{ $flashDealProduct->name }}" width="50" height="50" class="me-3 img-thumbnail">
                                                            @else
                                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                    <i data-feather="image" class="text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-1">{{ $flashDealProduct ? $flashDealProduct->name : 'Product Deleted' }}</h6>
                                                                <small class="text-muted">SKU: {{ $flashDealProduct->SKU ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong>Rs {{ number_format($flashDealProduct->price ?? 0, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        @if($flashDealProduct->pivot->discount_type == 'percentage')
                                                            <span class="badge bg-warning">{{ $flashDealProduct->pivot->discount_value }}%</span>
                                                        @else
                                                            <span class="badge bg-warning">Rs {{ number_format($flashDealProduct->pivot->discount_value, 2) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $originalPrice = $flashDealProduct->price ?? 0;
                                                            $discountValue = $flashDealProduct->pivot->discount_value ?? 0;
                                                            $salePrice = $flashDealProduct->pivot->discount_type == 'percentage' 
                                                                ? $originalPrice - ($originalPrice * $discountValue / 100)
                                                                : $originalPrice - $discountValue;
                                                        @endphp
                                                        <strong class="text-success">Rs {{ number_format($salePrice, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.flash-deal-products.edit', $flashDealProduct->pivot->id ?? $flashDealProduct->id) }}" class="btn btn-sm btn-info">
                                                            <i class="align-middle" data-feather="edit"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($flashDeal->products->count() > 5)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-outline-primary">
                                            View All {{ $flashDeal->products->count() }} Products
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="align-middle" data-feather="tag" style="font-size: 48px;" class="text-muted"></i>
                                    <p class="mt-2 text-muted">No products have been added to this flash deal yet.</p>
                                    <a href="{{ route('admin.flash-deal-products.create', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-primary">
                                        <i class="align-middle" data-feather="plus"></i> Add First Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-confirm').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this flash deal? This action cannot be undone and will also remove all associated products.')) {
                this.closest('form').submit();
            }
        });
    });

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
