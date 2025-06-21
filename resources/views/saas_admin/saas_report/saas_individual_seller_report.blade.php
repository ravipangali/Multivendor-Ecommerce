@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Individual Seller Report - ' . $seller->name)

@section('content')
<div class="col-12">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Individual Seller Report - {{ $seller->name }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('admin.reports.sellers') }}" class="btn btn-secondary btn-sm">
                            <i data-feather="arrow-left"></i> Back to Seller Reports
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Date Filter -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ request('start_date', date('Y-m-d', strtotime('-30 days'))) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-white">Total Sales</h6>
                                            <h4 class="text-white">Rs {{ number_format($totalSales, 2) }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="dollar-sign" class="feather-32"></i>
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
                                            <h6 class="card-title text-white">Orders</h6>
                                            <h4 class="text-white">{{ $orderCount }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="shopping-cart" class="feather-32"></i>
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
                                            <h6 class="card-title text-white">Products</h6>
                                            <h4 class="text-white">{{ $productCount }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="box" class="feather-32"></i>
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
                                            <h6 class="card-title text-white">Avg Order Value</h6>
                                            <h4 class="text-white">Rs {{ $orderCount > 0 ? number_format($totalSales / $orderCount, 2) : '0.00' }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i data-feather="trending-up" class="feather-32"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts and Tables Row -->
                    <div class="row">
                        <!-- Sales Chart -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Sales Over Time</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart" style="height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Top Selling Products</h5>
                                </div>
                                <div class="card-body">
                                    @if($topProducts->count() > 0)
                                        <div class="list-group">
                                            @foreach($topProducts as $product)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">Rs {{ number_format($product->total_sales, 2) }}</small>
                                                </div>
                                                <span class="badge bg-primary rounded-pill">{{ $product->quantity_sold }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted text-center">No products sold in this period</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Orders</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Customer</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
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
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                                        <i data-feather="eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No orders found in this period</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesByDate);

    const labels = salesData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });

    const data = salesData.map(item => parseFloat(item.total));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales (Rs)',
                data: data,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: Rs ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
