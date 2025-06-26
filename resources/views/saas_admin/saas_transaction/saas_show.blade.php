@extends('saas_admin.saas_layouts.saas_layout')


@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4">Transaction Details</h1>
                <div>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Transactions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transaction Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt"></i> Transaction Information
                        <span class="badge {{ $transaction->status_badge_class }} ml-2">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Transaction ID:</td>
                                    <td>{{ $transaction->id }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Date:</td>
                                    <td>{{ $transaction->transaction_date->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Type:</td>
                                    <td>
                                        <span class="badge {{ $transaction->type_badge_class }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Amount:</td>
                                    <td>
                                        <span class="h5 {{ $transaction->transaction_type === 'deposit' || $transaction->transaction_type === 'commission' ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->formatted_amount }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Reference Type:</td>
                                    <td>{{ $transaction->reference_type ? ucfirst($transaction->reference_type) : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="font-weight-bold">Balance Before:</td>
                                    <td>${{ number_format($transaction->balance_before, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Balance After:</td>
                                    <td class="font-weight-bold">${{ number_format($transaction->balance_after, 2) }}</td>
                                </tr>
                                @if($transaction->commission_percentage)
                                <tr>
                                    <td class="font-weight-bold">Commission Rate:</td>
                                    <td>{{ $transaction->commission_percentage }}%</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Commission Amount:</td>
                                    <td>${{ number_format($transaction->commission_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="font-weight-bold">Status:</td>
                                    <td>
                                        <span class="badge {{ $transaction->status_badge_class }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($transaction->description)
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h6>Description:</h6>
                            <p class="text-muted">{{ $transaction->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($transaction->meta_data)
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h6>Additional Data:</h6>
                            <pre class="bg-light p-3 rounded">{{ json_encode($transaction->meta_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Information -->
        <div class="col-md-4">
            <!-- User Information -->
            @if($transaction->user)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user"></i> User Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($transaction->user->profile_photo)
                            <img src="{{ asset('storage/' . $transaction->user->profile_photo) }}"
                                 alt="{{ $transaction->user->name }}"
                                 class="rounded-circle" width="60" height="60">
                        @else
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="font-weight-bold">Name:</td>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Email:</td>
                            <td>{{ $transaction->user->email }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Role:</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucfirst($transaction->user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Current Balance:</td>
                            <td class="font-weight-bold text-success">
                                ${{ number_format($transaction->user->balance, 2) }}
                            </td>
                        </tr>
                        @if($transaction->user->commission)
                        <tr>
                            <td class="font-weight-bold">Commission Rate:</td>
                            <td>{{ $transaction->user->commission }}%</td>
                        </tr>
                        @endif
                    </table>
                    <div class="text-center">
                        @if($transaction->user->role === 'seller')
                            <a href="{{ route('admin.sellers.show', $transaction->user->id) }}" class="btn btn-sm btn-primary">
                                View Seller Details
                            </a>
                        @else
                            <a href="{{ route('admin.customers.show', $transaction->user->id) }}" class="btn btn-sm btn-primary">
                                View Customer Details
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-cog"></i> Admin Transaction
                    </h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                    <p class="text-muted">This is an admin/system transaction.</p>
                </div>
            </div>
            @endif

            <!-- Order Information -->
            @if($transaction->order)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> Related Order
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="font-weight-bold">Order Number:</td>
                            <td>{{ $transaction->order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Order Date:</td>
                            <td>{{ $transaction->order->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Total Amount:</td>
                            <td class="font-weight-bold">${{ number_format($transaction->order->total, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Order Status:</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucfirst($transaction->order->order_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Payment Status:</td>
                            <td>
                                <span class="badge badge-{{ $transaction->order->payment_status === 'paid' ? 'success' : ($transaction->order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($transaction->order->payment_status) }}
                                </span>
                            </td>
                        </tr>
                        @if($transaction->order->customer)
                        <tr>
                            <td class="font-weight-bold">Customer:</td>
                            <td>{{ $transaction->order->customer->name }}</td>
                        </tr>
                        @endif
                        @if($transaction->order->seller)
                        <tr>
                            <td class="font-weight-bold">Seller:</td>
                            <td>{{ $transaction->order->seller->name }}</td>
                        </tr>
                        @endif
                    </table>
                    <div class="text-center">
                        <a href="{{ route('admin.orders.show', $transaction->order->id) }}" class="btn btn-sm btn-primary">
                            View Order Details
                        </a>
                    </div>
                </div>
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
