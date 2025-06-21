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
                            <h5 class="card-title">Online Orders</h5>
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

        <!-- In House Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">POS Sales</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="align-middle" data-feather="monitor"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $totalInHouseSales }}</h1>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> {{ $todayInHouseSales }} </span>
                        <span class="text-muted">POS sales today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Revenue</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <span class="rs-icon align-middle">Rs</span>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">Rs {{ number_format($totalCombinedRevenue, 2) }}</h1>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> Rs {{ number_format($todayCombinedRevenue, 2) }} </span>
                        <span class="text-muted">Revenue today</span>
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

    <!-- Revenue Breakdown -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Revenue Breakdown</h5>
                    <h6 class="card-subtitle text-muted">Online vs POS sales today</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat text-primary me-3">
                                    <i class="align-middle" data-feather="globe"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Online Sales</h6>
                                    <h4 class="mb-0 text-primary">Rs {{ number_format($recentSales, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat text-success me-3">
                                    <i class="align-middle" data-feather="monitor"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">POS Sales</h6>
                                    <h4 class="mb-0 text-success">Rs {{ number_format($todayInHouseRevenue, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Inventory Status</h5>
                    <h6 class="card-subtitle text-muted">Product stock overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat text-info me-3">
                                    <i class="align-middle" data-feather="box"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Products</h6>
                                    <h4 class="mb-0 text-info">{{ $totalProducts }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat text-warning me-3">
                                    <i class="align-middle" data-feather="alert-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Low Stock</h6>
                                    <h4 class="mb-0 text-warning">{{ $lowStockProducts }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and POS Sales -->
    <div class="row">
        <!-- Recent Online Orders -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Online Orders</h5>
                    <h6 class="card-subtitle text-muted">Latest orders from customers</h6>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders->take(5) as $order)
                            <tr>
                                <td><small>{{ $order->order_number }}</small></td>
                                <td><small>{{ $order->customer ? $order->customer->name : 'N/A' }}</small></td>
                                <td><small>Rs {{ number_format($order->total, 2) }}</small></td>
                                <td>
                                    <small>
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
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center"><small>No recent orders found</small></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent POS Sales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent POS Sales</h5>
                    <h6 class="card-subtitle text-muted">Latest in-house sales transactions</h6>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Sale ID</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInHouseSales as $sale)
                            <tr>
                                <td><small>{{ $sale->sale_number }}</small></td>
                                <td><small>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</small></td>
                                <td><small>Rs {{ number_format($sale->total_amount, 2) }}</small></td>
                                <td>
                                    <small>
                                        @if($sale->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">{{ ucfirst($sale->payment_status) }}</span>
                                        @endif
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center"><small>No recent POS sales found</small></td>
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
                    <h6 class="card-subtitle text-muted">Combined sales over the last 7 days</h6>
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
                    <h6 class="card-subtitle text-muted">Best selling products (Combined)</h6>
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
// Sales Chart with combined data
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($salesChartLabels),
        datasets: [{
            label: 'Online Sales',
            data: @json($salesChartOnlineValues),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'POS Sales',
            data: @json($salesChartInHouseValues),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Daily Sales Comparison'
            }
        },
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rs ' + value.toLocaleString();
                    }
                }
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ': Rs ' + tooltipItem.value.toLocaleString();
                }
            }
        }
    }
});
</script>
@endsection


