@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="col-12">
    <div class="row">
        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Orders</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalOrders }}</h1>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> {{ $recentOrders }} </span>
                        <span class="text-muted">New orders today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Sales</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <span class="rs-icon align-middle">Rs</span>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">Rs {{ number_format($totalSales, 2) }}</h1>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> Rs {{ number_format($recentSales, 2) }} </span>
                        <span class="text-muted">Sales today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Products</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="box"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalProducts }}</h1>
                    <div class="mb-0">
                        <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> {{ $lowStockProducts }} </span>
                        <span class="text-muted">Products in low stock</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Customers</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="align-middle" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalCustomers }}</h1>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> {{ $newCustomers }} </span>
                        <span class="text-muted">New customers today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Orders</h5>
                    <h6 class="card-subtitle text-muted">Most recent orders placed on the platform</h6>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>Rs {{ number_format($order->total, 2) }}</td>
                                <td>
                                    @if($order->order_status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->order_status == 'processing')
                                        <span class="badge bg-info">Processing</span>
                                    @elseif($order->order_status == 'shipped')
                                        <span class="badge bg-primary">Shipped</span>
                                    @elseif($order->order_status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->order_status == 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($order->order_status == 'refunded')
                                        <span class="badge bg-secondary">Refunded</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No recent orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales & Top Products -->
    <div class="row">
        <!-- Sales chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sales Overview</h5>
                    <h6 class="card-subtitle text-muted">Sales over the last 7 days</h6>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="salesChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top products -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Top Products</h5>
                    <h6 class="card-subtitle text-muted">Best selling products</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($topProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $product->name }}
                            <span class="badge bg-primary rounded-pill">{{ $product->quantity_sold }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare sales chart data
        var ctx = document.getElementById('salesChart').getContext('2d');

        var salesData = {
            labels: @json($salesChartLabels),
            datasets: [{
                label: 'Sales (Rs)',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: 'rgba(0, 123, 255, 1)',
                data: @json($salesChartValues)
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endsection


