@extends('saas_admin.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Customer Refund Requests</h1>
        </div>
        <div class="col-auto ms-auto text-end mt-n1">
            <button class="btn btn-light" onclick="location.reload()">
                <i class="align-middle" data-feather="refresh-cw"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Refunds</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="rotate-ccw"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalRefunds }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Pending</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning">
                                <i class="align-middle" data-feather="clock"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $pendingRefunds }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Approved</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $approvedRefunds }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Amount</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info">
                                <span class="rs-icon">Rs</span>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">Rs {{ number_format($totalRefundAmount, 2) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Refunds</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.refunds.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Customer, Order, Reason...">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="search"></i> Filter
                        </button>
                        <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Refund Requests</h5>
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

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Order</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Request Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                        <tr>
                            <td><strong>#{{ $refund->id }}</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $refund->customer->name }}</strong>
                                    <br><small class="text-muted">{{ $refund->customer->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $refund->order->order_number }}</strong>
                                    <br><small class="text-muted">Rs {{ number_format($refund->order_amount, 2) }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="text-danger">Rs {{ number_format($refund->refund_amount, 2) }}</strong>
                                    <br><small class="text-muted">Commission: Rs {{ number_format($refund->commission_amount, 2) }}</small>
                                </div>
                            </td>
                            <td>
                                <span title="{{ $refund->customer_reason }}">
                                    {{ Str::limit($refund->customer_reason, 30) }}
                                </span>
                            </td>
                            <td>
                                @switch($refund->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
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
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($refund->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div>
                                    {{ $refund->created_at->format('d M Y') }}
                                    <br><small class="text-muted">{{ $refund->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="align-middle" data-feather="eye"></i>
                                    </a>
                                    @if($refund->status === 'pending')
                                        <a href="{{ route('admin.refunds.edit', $refund) }}" class="btn btn-sm btn-outline-primary" title="Process">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="align-middle" data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2">No refund requests found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($refunds->hasPages())
                <div class="mt-4">
                    {{ $refunds->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
