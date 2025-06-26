@extends('saas_admin.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Seller Withdrawal Requests</h1>
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
                            <h5 class="card-title">Total Requests</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <span class="rs-icon">Rs</span>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalWithdrawals }}</h1>
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
                    <h1 class="mt-1 mb-3">{{ $pendingWithdrawals }}</h1>
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
                    <h1 class="mt-1 mb-3">{{ $approvedWithdrawals }}</h1>
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
                    <h1 class="mt-1 mb-3">Rs {{ number_format($totalWithdrawalAmount, 2) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Withdrawals</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}">
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
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Seller name, email...">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="align-middle" data-feather="search"></i> Filter
                        </button>
                        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="x"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Withdrawal Requests</h5>
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
                            <th>Seller</th>
                            <th>Requested Amount</th>
                            <th>Gateway Fee</th>
                            <th>Final Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Request Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td><strong>#{{ $withdrawal->id }}</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $withdrawal->user->name }}</strong>
                                    <br><small class="text-muted">{{ $withdrawal->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <strong class="text-primary">Rs {{ number_format($withdrawal->requested_amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="text-danger">Rs {{ number_format($withdrawal->gateway_fee, 2) }}</span>
                            </td>
                            <td>
                                <strong class="text-success">Rs {{ number_format($withdrawal->final_amount, 2) }}</strong>
                            </td>
                            <td>
                                <div>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $withdrawal->paymentMethod->type)) }}</span>
                                    <br><small class="text-muted">{{ $withdrawal->paymentMethod->title }}</small>
                                </div>
                            </td>
                            <td>
                                @switch($withdrawal->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">Approved</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">Processing</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-primary">Completed</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div>
                                    {{ $withdrawal->created_at->format('d M Y') }}
                                    <br><small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                        <i class="align-middle" data-feather="eye"></i>
                                    </a>
                                    @if($withdrawal->status === 'pending')
                                        <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-outline-primary" title="Process">
                                            <i class="align-middle" data-feather="settings"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="align-middle" data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2">No withdrawal requests found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($withdrawals->hasPages())
                <div class="mt-4">
                    {{ $withdrawals->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
