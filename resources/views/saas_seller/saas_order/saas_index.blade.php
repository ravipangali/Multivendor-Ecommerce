@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Orders')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Orders</h1>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="filter"></i> Filter by Status
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('seller.orders.index') }}">All Orders</a></li>
                    @foreach($orderStatuses as $statusKey => $statusLabel)
                        <li><a class="dropdown-item" href="{{ route('seller.orders.index', ['status' => $statusKey]) }}">
                            {{ $statusLabel }}
                        </a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @if($status)
        <div class="alert alert-info">
            <i class="align-middle" data-feather="filter"></i>
            Showing orders with status: <strong>{{ $orderStatuses[$status] ?? ucfirst($status) }}</strong>
            <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-outline-primary ms-2">Clear Filter</a>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
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
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        @if($order->customer)
                                            {{ $order->customer->name }}
                                            <br>
                                            <small class="text-muted">{{ $order->customer->email }}</small>
                                        @else
                                            <span class="text-muted">Guest</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->items->count() }} items</span>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($order->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusClasses[$order->order_status] ?? 'secondary' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('seller.orders.show', $order) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View Details">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            @if(in_array($order->order_status, ['pending', 'processing']))
                                                <a href="{{ route('seller.orders.edit', $order) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit Status">
                                                    <i class="align-middle" data-feather="edit"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('seller.orders.invoice', $order) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="View Invoice"
                                               target="_blank">
                                                <i class="align-middle" data-feather="printer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="align-middle" data-feather="shopping-cart" style="width: 48px; height: 48px;"></i>
                                            <p class="mt-2">
                                                @if($status)
                                                    No {{ strtolower($orderStatuses[$status] ?? $status) }} orders found.
                                                @else
                                                    No orders found.
                                                @endif
                                            </p>
                                            @if(!$status)
                                                <p class="text-muted">Orders will appear here once customers start purchasing your products.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
