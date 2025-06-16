@extends('saas_seller.saas_layouts.saas_layout')

@section('title', $title)

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            <div>
                <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="list"></i> All Orders
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Status Filter Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="nav nav-pills justify-content-center" role="tablist">
                        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.pending') }}">
                            <i class="align-middle" data-feather="clock"></i> Pending
                        </a>
                        <a class="nav-link {{ $status === 'processing' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.processing') }}">
                            <i class="align-middle" data-feather="package"></i> Processing
                        </a>
                        <a class="nav-link {{ $status === 'shipped' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.shipped') }}">
                            <i class="align-middle" data-feather="truck"></i> Shipped
                        </a>
                        <a class="nav-link {{ $status === 'delivered' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.delivered') }}">
                            <i class="align-middle" data-feather="check-circle"></i> Delivered
                        </a>
                        <a class="nav-link {{ $status === 'cancelled' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.cancelled') }}">
                            <i class="align-middle" data-feather="x-circle"></i> Cancelled
                        </a>
                        <a class="nav-link {{ $status === 'refunded' ? 'active' : '' }}" 
                           href="{{ route('seller.orders.refunded') }}">
                            <i class="align-middle" data-feather="rotate-ccw"></i> Refunded
                        </a>
                    </div>
                </div>
            </div>

            @if($orders->count() > 0)
                <!-- Orders Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                    <br><small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $order->customer->name }}</strong>
                                        <br><small class="text-muted">{{ $order->customer->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    {{ $order->placed_at ? \Carbon\Carbon::parse($order->placed_at)->format('M d, Y') : $order->created_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $order->placed_at ? \Carbon\Carbon::parse($order->placed_at)->format('h:i A') : $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</span>
                                    @if($order->items->count() > 0)
                                        <br><small class="text-muted">{{ $order->items->first()->product->name }}{{ $order->items->count() > 1 ? ' +' . ($order->items->count() - 1) . ' more' : '' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : ($order->order_status === 'pending' ? 'warning' : 'primary')) }}">
                                        {{ ucwords($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucwords($order->payment_status) }}
                                    </span>
                                    <br><small class="text-muted">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</small>
                                </td>
                                <td class="text-end">
                                    <strong>Rs {{ number_format($order->total, 2) }}</strong>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('seller.orders.show', $order->id) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="View Details">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('seller.orders.edit', $order->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit Order">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <a href="{{ route('seller.orders.invoice', $order->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Print Invoice">
                                            <i class="align-middle" data-feather="printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif

                <!-- Summary Stats -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Orders</h5>
                                <h3 class="text-primary">{{ $orders->total() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total Value</h5>
                                <h3 class="text-success">Rs {{ number_format($orders->sum('total'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">Average Order</h5>
                                <h3 class="text-info">Rs {{ $orders->count() > 0 ? number_format($orders->sum('total') / $orders->count(), 2) : '0.00' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        @if($status === 'pending')
                            <i data-feather="clock" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @elseif($status === 'processing')
                            <i data-feather="package" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @elseif($status === 'shipped')
                            <i data-feather="truck" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @elseif($status === 'delivered')
                            <i data-feather="check-circle" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @elseif($status === 'cancelled')
                            <i data-feather="x-circle" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @elseif($status === 'refunded')
                            <i data-feather="rotate-ccw" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @else
                            <i data-feather="inbox" class="text-muted" style="width: 64px; height: 64px;"></i>
                        @endif
                    </div>
                    <h4 class="text-muted">No {{ ucwords($status) }} Orders</h4>
                    <p class="text-muted">
                        @if($status === 'pending')
                            You don't have any pending orders at the moment.
                        @elseif($status === 'processing')
                            No orders are currently being processed.
                        @elseif($status === 'shipped')
                            No orders have been shipped yet.
                        @elseif($status === 'delivered')
                            No orders have been delivered yet.
                        @elseif($status === 'cancelled')
                            No orders have been cancelled.
                        @elseif($status === 'refunded')
                            No orders have been refunded.
                        @else
                            No orders found with this status.
                        @endif
                    </p>
                    <a href="{{ route('seller.orders.index') }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="list"></i> View All Orders
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .nav-pills .nav-link {
        margin: 0 5px;
        border-radius: 20px;
    }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        margin: 0 1px;
    }
    
    .card.bg-light {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>
@endsection 