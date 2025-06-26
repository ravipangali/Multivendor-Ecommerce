@extends('saas_admin.saas_layouts.saas_layout')


@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4">Admin Transactions</h1>
                <div>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-info">
                        <i class="fas fa-list"></i> All Transactions
                    </a>
                    <a href="{{ route('admin.transactions.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white card-title">Total Transactions</h6>
                            <h4 class="text-white ">{{ number_format($totalTransactions) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white card-title">Total Deposits</h6>
                            <h4 class="text-white ">${{ number_format($totalDeposits, 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white card-title">Total Withdrawals</h6>
                            <h4 class="text-white ">${{ number_format($totalWithdrawals, 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white card-title">Total Commissions</h6>
                            <h4 class="text-white ">${{ number_format($totalCommissions, 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.transactions.admin-transactions') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transaction_type">Transaction Type</label>
                            <select name="transaction_type" id="transaction_type" class="form-control">
                                <option value="">All Types</option>
                                @foreach($transactionTypes as $key => $value)
                                    <option value="{{ $key }}" {{ request('transaction_type') === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Status</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search description, user, order..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.transactions.admin-transactions') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Transactions ({{ $transactions->total() }})</h5>
        </div>
        <div class="card-body">
            @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Order</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->transaction_date->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($transaction->user)
                                            <span class="badge badge-info">{{ $transaction->user->name }}</span>
                                            <small class="d-block text-muted">{{ ucfirst($transaction->user->role) }}</small>
                                        @else
                                            <span class="badge badge-dark">Admin</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $transaction->type_badge_class }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold {{ $transaction->transaction_type === 'deposit' || $transaction->transaction_type === 'commission' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->formatted_amount }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">Before: {{ number_format($transaction->balance_before, 2) }}</small><br>
                                        <strong>After: {{ number_format($transaction->balance_after, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($transaction->order)
                                            <a href="{{ route('admin.orders.show', $transaction->order->id) }}" class="text-primary">
                                                {{ $transaction->order->order_number }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->commission_percentage)
                                            <small class="text-muted">{{ $transaction->commission_percentage }}%</small><br>
                                            <strong>{{ number_format($transaction->commission_amount, 2) }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $transaction->status_badge_class }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No transactions found</h5>
                    <p class="text-muted">There are no transactions matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.badge-success {
    background-color: #28a745;
}
.badge-info {
    background-color: #17a2b8;
}
.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
.badge-danger {
    background-color: #dc3545;
}
.badge-secondary {
    background-color: #6c757d;
}
.badge-dark {
    background-color: #343a40;
}
</style>
@endpush
