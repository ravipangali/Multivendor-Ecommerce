@extends('saas_admin.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Refund Request #{{ $refund->id }}</h1>
        <div class="float-end">
            <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Refunds
            </a>
            @if($refund->status === 'pending')
                <a href="{{ route('admin.refunds.edit', $refund) }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="edit"></i> Process Refund
                </a>
            @endif
        </div>
    </div>

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

    <div class="row">
        <!-- Refund Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Refund Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Refund ID:</dt>
                        <dd class="col-sm-9"><code>#{{ $refund->id }}</code></dd>

                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9">
                            @switch($refund->status)
                                @case('pending')
                                    <span class="badge bg-warning">Pending Review</span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-success">Approved</span>
                                    @break
                                @case('processed')
                                    <span class="badge bg-primary">Processed</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @break
                            @endswitch
                        </dd>

                        <dt class="col-sm-3">Request Date:</dt>
                        <dd class="col-sm-9">{{ $refund->created_at->format('d M Y, h:i A') }}</dd>

                        @if($refund->processed_at)
                            <dt class="col-sm-3">Processed Date:</dt>
                            <dd class="col-sm-9">{{ $refund->processed_at->format('d M Y, h:i A') }}</dd>
                        @endif

                        <dt class="col-sm-3">Customer Reason:</dt>
                        <dd class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                {{ $refund->customer_reason }}
                            </div>
                        </dd>

                        @if($refund->admin_notes)
                            <dt class="col-sm-3">Admin Notes:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-info">
                                    {{ $refund->admin_notes }}
                                </div>
                            </dd>
                        @endif

                        @if($refund->rejected_reason)
                            <dt class="col-sm-3">Rejection Reason:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-danger">
                                    {{ $refund->rejected_reason }}
                                </div>
                            </dd>
                        @endif

                        @if($refund->admin_attachment)
                            <dt class="col-sm-3">Payment Proof:</dt>
                            <dd class="col-sm-9">
                                <a href="{{ route('admin.refunds.download-attachment', $refund) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="align-middle" data-feather="download"></i> Download Attachment
                                </a>
                            </dd>
                        @endif

                        @if($refund->processedBy)
                            <dt class="col-sm-3">Processed By:</dt>
                            <dd class="col-sm-9">{{ $refund->processedBy->name }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Order Number:</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route('admin.orders.show', $refund->order->id) }}" class="text-decoration-none">
                                {{ $refund->order->order_number }}
                            </a>
                        </dd>

                        <dt class="col-sm-3">Order Date:</dt>
                        <dd class="col-sm-9">{{ $refund->order->created_at->format('d M Y, h:i A') }}</dd>

                        <dt class="col-sm-3">Order Status:</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $refund->order->order_status === 'completed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($refund->order->order_status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-3">Payment Status:</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-{{ $refund->order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($refund->order->payment_status) }}
                            </span>
                        </dd>
                    </dl>

                    <!-- Order Items -->
                    <h6 class="mt-4 mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refund->order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->thumbnail)
                                                <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product_sku)
                                                    <br><small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs {{ number_format($item->price, 2) }}</td>
                                    <td>Rs {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Financial Information -->
        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Name:</dt>
                        <dd class="col-7">{{ $refund->customer->name }}</dd>

                        <dt class="col-5">Email:</dt>
                        <dd class="col-7">{{ $refund->customer->email }}</dd>

                        <dt class="col-5">Phone:</dt>
                        <dd class="col-7">{{ $refund->customer->phone ?? 'N/A' }}</dd>
                    </dl>

                    <a href="{{ route('admin.customers.show', $refund->customer->id) }}" class="btn btn-sm btn-outline-primary">
                        View Customer Profile
                    </a>
                </div>
            </div>

            <!-- Seller Information -->
            @if($refund->seller)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Seller Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Name:</dt>
                        <dd class="col-7">{{ $refund->seller->name }}</dd>

                        <dt class="col-5">Email:</dt>
                        <dd class="col-7">{{ $refund->seller->email }}</dd>

                        <dt class="col-5">Balance:</dt>
                        <dd class="col-7">Rs {{ number_format($refund->seller->balance ?? 0, 2) }}</dd>
                    </dl>

                    <a href="{{ route('admin.sellers.show', $refund->seller->id) }}" class="btn btn-sm btn-outline-primary">
                        View Seller Profile
                    </a>
                </div>
            </div>
            @endif

            <!-- Financial Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Financial Summary</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-7">Order Amount:</dt>
                        <dd class="col-5">Rs {{ number_format($refund->order_amount, 2) }}</dd>

                        <dt class="col-7">Commission ({{ $refund->commission_rate }}%):</dt>
                        <dd class="col-5">Rs {{ number_format($refund->commission_amount, 2) }}</dd>

                        <dt class="col-7">Seller Deduction:</dt>
                        <dd class="col-5">Rs {{ number_format($refund->seller_deduct_amount, 2) }}</dd>

                        <dt class="col-7 border-top pt-2"><strong>Refund Amount:</strong></dt>
                        <dd class="col-5 border-top pt-2"><strong class="text-danger">Rs {{ number_format($refund->refund_amount, 2) }}</strong></dd>
                    </dl>

                    @if($refund->paymentMethod)
                        <h6 class="mt-3">Refund Method</h6>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $refund->paymentMethod->type)) }}</span>
                            <br><small class="text-muted">{{ $refund->paymentMethod->title }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if($refund->status === 'pending')
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve this refund?')">
                            <i class="align-middle" data-feather="check"></i> Quick Approve
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="align-middle" data-feather="x"></i> Quick Reject
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($refund->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Refund Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejected_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejected_reason" name="rejected_reason" rows="3" required placeholder="Please provide a clear reason for rejection..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2" placeholder="Any additional internal notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="align-middle" data-feather="x"></i> Reject Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
