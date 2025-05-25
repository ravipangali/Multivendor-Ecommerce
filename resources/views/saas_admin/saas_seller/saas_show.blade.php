@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Seller Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Seller Details</h5>
                <div>
                    <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Seller
                    </a>
                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Sellers
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Seller Profile Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($seller->profile_photo)
                                <img src="{{ asset('storage/'.$seller->profile_photo) }}" alt="{{ $seller->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 2rem;">
                                    {{ substr($seller->name, 0, 1) }}
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $seller->name }}</h4>
                            <p class="text-muted mb-3">Seller</p>

                            <div class="mb-3">
                                @if($seller->is_active)
                                    <span class="badge bg-success me-2">Active</span>
                                @else
                                    <span class="badge bg-danger me-2">Inactive</span>
                                @endif

                                @if($seller->sellerProfile)
                                    @if($seller->sellerProfile->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending Approval</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">No Profile</span>
                                @endif
                            </div>

                            <table class="table table-sm">
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $seller->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $seller->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Joined:</th>
                                    <td>{{ $seller->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $seller->updated_at->format('M d, Y') }}</td>
                                </tr>
                            </table>

                            @if($seller->sellerProfile && !$seller->sellerProfile->is_approved)
                                <form action="{{ route('admin.sellers.toggle-approval', $seller->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="align-middle" data-feather="check-circle"></i> Approve Seller
                                    </button>
                                </form>
                            @elseif($seller->sellerProfile && $seller->sellerProfile->is_approved)
                                <form action="{{ route('admin.sellers.toggle-approval', $seller->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="align-middle" data-feather="x-circle"></i> Disapprove Seller
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Store & Business Information -->
                <div class="col-md-8">
                    <!-- Statistics Cards -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h2 class="mb-1 fw-bold text-primary">{{ $totalProducts }}</h2>
                                            <p class="text-muted mb-0 small">Total Products</p>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i data-feather="package" class="text-primary" style="width: 1.5rem; height: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h2 class="mb-1 fw-bold text-success">{{ $activeProducts }}</h2>
                                            <p class="text-muted mb-0 small">Active Products</p>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i data-feather="check-circle" class="text-success" style="width: 1.5rem; height: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h2 class="mb-1 fw-bold text-warning">{{ $totalOrders }}</h2>
                                            <p class="text-muted mb-0 small">Total Orders</p>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i data-feather="shopping-bag" class="text-warning" style="width: 1.5rem; height: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h2 class="mb-1 fw-bold text-info" style="font-size: 1.2rem;">Rs {{ number_format($totalRevenue, 2) }}</h2>
                                            <p class="text-muted mb-0 small">Total Revenue</p>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i data-feather="dollar-sign" class="text-info" style="width: 1.5rem; height: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Information -->
                    @if($seller->sellerProfile)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Store Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Store Banner -->
                            @if($seller->sellerProfile->store_banner)
                                <div class="mb-3 text-center">
                                    <img src="{{ asset('storage/'.$seller->sellerProfile->store_banner) }}" alt="Store Banner" class="img-fluid" style="max-height: 200px;">
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-3 text-center">
                                    @if($seller->sellerProfile->store_logo)
                                        <img src="{{ asset('storage/'.$seller->sellerProfile->store_logo) }}" alt="Store Logo" class="img-fluid mb-2" style="max-height: 100px;">
                                    @else
                                        <div class="bg-light p-4 mb-2">
                                            <i data-feather="image" style="width: 3rem; height: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="30%">Store Name:</th>
                                            <td>{{ $seller->sellerProfile->store_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $seller->sellerProfile->store_description ?? 'No description provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $seller->sellerProfile->address ?? 'No address provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Profile Created:</th>
                                            <td>{{ $seller->sellerProfile->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Methods Section -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Payment Methods</h5>
                            <a href="{{ route('admin.payment-methods.create', ['user_id' => $seller->id, 'user_role' => 'seller']) }}" class="btn btn-sm btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Add Payment Method
                            </a>
                        </div>
                        <div class="card-body">
                            @php
                            $paymentMethods = \App\Models\SaasPaymentMethod::where('user_id', $seller->id)
                                ->orderBy('is_default', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->get();
                            @endphp

                            @if($paymentMethods->count() > 0)
                                <div class="row g-3">
                                    @foreach($paymentMethods as $paymentMethod)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 {{ $paymentMethod->is_default ? 'border-primary' : 'border-light' }} shadow-sm">
                                                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2">
                                                    <h6 class="mb-0">
                                                        {{ $paymentMethod->title }}
                                                        @if($paymentMethod->is_default)
                                                            <span class="badge bg-primary ms-1">Default</span>
                                                        @endif
                                                    </h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm p-0" type="button" data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.payment-methods.edit', ['payment_method' => $paymentMethod->id, 'user_id' => $seller->id, 'user_role' => 'seller']) }}">
                                                                    <i data-feather="edit" class="feather-sm me-1"></i> Edit
                                                                </a>
                                                            </li>
                                                            @if(!$paymentMethod->is_default)
                                                                <li>
                                                                    <form action="{{ route('admin.payment-methods.set-default', $paymentMethod->id) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i data-feather="check-circle" class="feather-sm me-1"></i> Set as Default
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body py-2">
                                                    <div class="d-flex align-items-center mb-2">
                                                        @if($paymentMethod->type == 'bank_transfer')
                                                            <div class="rounded-circle bg-light p-2 me-2">
                                                                <i data-feather="credit-card" class="text-primary"></i>
                                                            </div>
                                                        @elseif($paymentMethod->type == 'esewa')
                                                            <div class="rounded-circle bg-light p-2 me-2">
                                                                <i data-feather="smartphone" class="text-success"></i>
                                                            </div>
                                                        @elseif($paymentMethod->type == 'khalti')
                                                            <div class="rounded-circle bg-light p-2 me-2">
                                                                <i data-feather="smartphone" class="text-purple"></i>
                                                            </div>
                                                        @else
                                                            <div class="rounded-circle bg-light p-2 me-2">
                                                                <i data-feather="dollar-sign" class="text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="mb-0 text-muted small">{{ ucfirst(str_replace('_', ' ', $paymentMethod->type)) }}</p>
                                                            <p class="mb-0 fw-bold">{{ $paymentMethod->account_name }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="small text-muted mt-2">
                                                        @if($paymentMethod->type == 'bank_transfer')
                                                            <div class="mb-1"><strong>Bank:</strong> {{ $paymentMethod->bank_name }}</div>
                                                            <div class="mb-1"><strong>Branch:</strong> {{ $paymentMethod->bank_branch }}</div>
                                                            <div><strong>Acc #:</strong> {{ $paymentMethod->account_number }}</div>
                                                        @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
                                                            <div><strong>Mobile:</strong> {{ $paymentMethod->mobile_number }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-transparent d-flex justify-content-between py-2">
                                                    <span class="badge {{ $paymentMethod->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <small class="text-muted">Added: {{ $paymentMethod->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    No payment methods found for this seller.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Products</h5>
                            <a href="{{ route('admin.products.index') }}?seller_id={{ $seller->id }}" class="btn btn-sm btn-primary">
                                View All Products
                            </a>
                        </div>
                        <div class="card-body">
                            @if($seller->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($seller->products->take(5) as $product)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if($product->images && $product->images->count() > 0)
                                                                <img src="{{ asset($product->images->first()->image_url) }}"
                                                                     alt="{{ $product->name }}"
                                                                     class="rounded me-3"
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                                     style="width: 40px; height: 40px;">
                                                                    <i data-feather="box" class="text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold">{{ $product->name }}</div>
                                                                <small class="text-muted">{{ $product->category->name ?? 'No Category' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><code>{{ $product->SKU }}</code></td>
                                                    <td>Rs {{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        @if($product->stock > 0)
                                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                                        @else
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($product->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="align-middle" data-feather="eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    This seller hasn't added any products yet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                            <a href="{{ route('admin.orders.index') }}?seller_id={{ $seller->id }}" class="btn btn-sm btn-primary">
                                View All Orders
                            </a>
                        </div>
                        <div class="card-body">
                            @if($seller->sellerOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($seller->sellerOrders->take(5) as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
                                                    <td>{{ $order->customer->name ?? 'Guest' }}</td>
                                                    <td>{{ $order->items->count() }} items</td>
                                                    <td>Rs {{ number_format($order->total, 2) }}</td>
                                                    <td>
                                                        @switch($order->order_status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                                @break
                                                            @case('processing')
                                                                <span class="badge bg-info">Processing</span>
                                                                @break
                                                            @case('shipped')
                                                                <span class="badge bg-primary">Shipped</span>
                                                                @break
                                                            @case('delivered')
                                                                <span class="badge bg-success">Delivered</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span class="badge bg-danger">Cancelled</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="align-middle" data-feather="eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    This seller hasn't received any orders yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-end mt-3">
                <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Seller
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
