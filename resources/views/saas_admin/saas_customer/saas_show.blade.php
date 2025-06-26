@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Customer Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Customer Details</h5>
                <div>
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Customer
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Customers
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Customer Profile Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($customer->profile_photo)
                                <img src="{{ asset('storage/'.$customer->profile_photo) }}" alt="{{ $customer->name }}" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px; font-size: 2rem;">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $customer->name }}</h4>
                            <p class="text-muted mb-3">Customer</p>

                            @if($customer->is_active)
                                <span class="badge bg-success mb-3">Active</span>
                            @else
                                <span class="badge bg-danger mb-3">Inactive</span>
                            @endif

                            <table class="table table-sm">
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Joined:</th>
                                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $customer->updated_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Statistics & Activity -->
                <div class="col-md-8">
                    <!-- Statistics Cards -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h2 class="mb-1 fw-bold text-primary">{{ $totalOrders }}</h2>
                                            <p class="text-muted mb-0 small">Total Orders</p>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i data-feather="shopping-bag" class="text-primary" style="width: 1.5rem; height: 1.5rem;"></i>
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
                                            <h2 class="mb-1 fw-bold text-success" style="font-size: 1.2rem;"> Rs </span> {{ number_format($totalSpent, 2) }}</h2>
                                            <p class="text-muted mb-0 small">Total Spent</p>
                                        </div>
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <span class="rs-icon rs-icon-xl text-success">Rs</span>
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
                                            <h2 class="mb-1 fw-bold text-warning">{{ $pendingOrders }}</h2>
                                            <p class="text-muted mb-0 small">Pending Orders</p>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i data-feather="clock" class="text-warning" style="width: 1.5rem; height: 1.5rem;"></i>
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
                                            <h2 class="mb-1 fw-bold text-info">{{ $completedOrders }}</h2>
                                            <p class="text-muted mb-0 small">Completed</p>
                                        </div>
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i data-feather="check-circle" class="text-info" style="width: 1.5rem; height: 1.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    @if($customer->customerProfile)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Shipping Address</h6>
                                    <p class="text-muted">
                                        {{ $customer->customerProfile->shipping_address ?? 'No shipping address provided' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Billing Address</h6>
                                    <p class="text-muted">
                                        {{ $customer->customerProfile->billing_address ?? 'No billing address provided' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment Methods Section -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Payment Methods</h5>
                            <a href="{{ route('admin.payment-methods.create', ['user_id' => $customer->id, 'user_role' => 'customer']) }}" class="btn btn-sm btn-primary">
                                <i class="align-middle" data-feather="plus"></i> Add Payment Method
                            </a>
                        </div>
                        <div class="card-body">
                            @php
                            $paymentMethods = \App\Models\SaasPaymentMethod::where('user_id', $customer->id)
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
                                                                <a class="dropdown-item" href="{{ route('admin.payment-methods.edit', ['payment_method' => $paymentMethod->id, 'user_id' => $customer->id, 'user_role' => 'customer']) }}">
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
                                                                <span class="rs-icon text-secondary">Rs</span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="mb-0 text-muted small">{{ ucfirst(str_replace('_', ' ', $paymentMethod->type)) }}</p>
                                                            <p class="mb-0 fw-bold">{{ $paymentMethod->details['account_name'] ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="small text-muted mt-2">
                                                        @if($paymentMethod->type == 'bank_transfer')
                                                            <div class="mb-1"><strong>Bank:</strong> {{ $paymentMethod->details['bank_name'] ?? 'N/A' }}</div>
                                                            <div class="mb-1"><strong>Branch:</strong> {{ $paymentMethod->details['bank_branch'] ?? 'N/A' }}</div>
                                                            <div><strong>Acc #:</strong> {{ $paymentMethod->details['account_number'] ?? 'N/A' }}</div>
                                                        @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
                                                            <div><strong>Mobile:</strong> {{ $paymentMethod->details['mobile_number'] ?? 'N/A' }}</div>
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
                                    No payment methods found for this customer.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                            <a href="{{ route('admin.orders.index') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-primary">
                                View All Orders
                            </a>
                        </div>
                        <div class="card-body">
                            @if($customer->customerOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->customerOrders->take(5) as $order)
                                                <tr>
                                                    <td>#{{ $order->id }}</td>
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
                                    This customer hasn't placed any orders yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-end mt-3">
                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
