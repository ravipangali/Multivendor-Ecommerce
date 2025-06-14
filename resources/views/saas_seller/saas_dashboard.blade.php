@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="col-12">
    <!-- Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-muted">Here's what's happening with your store today.</p>
        </div>
    </div>

    <!-- Statistics Cards Row 1 -->
    <div class="row">
        <!-- Wallet Balance -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Wallet Balance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($wallet->balance, 2) }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Pending: Rs {{ number_format($wallet->pending_balance, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Sales (All Time)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($totalSales, 2) }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Today: Rs {{ number_format($todaySales, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                This Month's Sales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($thisMonthSales, 2) }}
                            </div>
                            <div class="text-xs mt-1">
                                @if($growthPercentage > 0)
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> {{ $growthPercentage }}%
                                    </span>
                                @elseif($growthPercentage < 0)
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down"></i> {{ abs($growthPercentage) }}%
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-minus"></i> 0%
                                    </span>
                                @endif
                                vs last month
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                            <div class="text-xs text-muted mt-1">
                                Total Orders: {{ $totalOrders }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 -->
    <div class="row">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Products
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                            <div class="text-xs text-muted mt-1">
                                Active: {{ $activeProducts }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Stock Alert
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $outOfStockProducts + $lowStockProducts }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Out: {{ $outOfStockProducts }} | Low: {{ $lowStockProducts }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Customer Reviews
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalReviews }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                Avg Rating: {{ number_format($averageRating, 1) }} ⭐
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pending Withdrawals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rs {{ number_format($pendingWithdrawals, 2) }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <a href="{{ route('seller.withdrawals.index') }}" class="text-info">View All</a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Overview (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @php
                            $statusColors = [
                                'pending' => ['color' => '#f6c23e', 'label' => 'Pending'],
                                'processing' => ['color' => '#4e73df', 'label' => 'Processing'],
                                'shipped' => ['color' => '#36b9cc', 'label' => 'Shipped'],
                                'delivered' => ['color' => '#1cc88a', 'label' => 'Delivered'],
                                'cancelled' => ['color' => '#e74a3b', 'label' => 'Cancelled'],
                                'refunded' => ['color' => '#858796', 'label' => 'Refunded']
                            ];
                        @endphp
                        @foreach($statusColors as $status => $config)
                            @if(isset($orderStatusData[$status]) && $orderStatusData[$status] > 0)
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: {{ $config['color'] }}"></i>
                                    {{ $config['label'] }} ({{ $orderStatusData[$status] }})
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Top Products Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>Rs {{ number_format($order->total, 2) }}</td>
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
                                        <span class="badge badge-{{ $statusClasses[$order->order_status] ?? 'secondary' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $order->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                    @if($recentOrders->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('seller.orders.index') }}" class="btn btn-primary btn-sm">
                                View All Orders
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @forelse($topProducts as $product)
                    <div class="mb-3">
                        <h6 class="small font-weight-bold">
                            {{ Str::limit($product->name, 30) }}
                            <span class="float-right">{{ $product->quantity_sold }} sold</span>
                        </h6>
                        <div class="progress mb-2">
                            @php
                                $maxSold = $topProducts->max('quantity_sold');
                                $percentage = $maxSold > 0 ? ($product->quantity_sold / $maxSold) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">
                            Rs {{ number_format($product->price, 2) }} |
                            Revenue: Rs {{ number_format($product->total_sales, 2) }}
                        </small>
                    </div>
                    @empty
                    <p class="text-center text-muted">No sales data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Customer Reviews</h6>
                </div>
                <div class="card-body">
                    @forelse($recentReviews as $review)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="font-weight-bold mb-1">
                                    {{ $review->product->name }}
                                </h6>
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-muted">by {{ $review->customer->name }}</span>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0 text-gray-800">{{ $review->comment }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                <br>
                                <a href="{{ route('seller.reviews.show', $review->id) }}"
                                   class="btn btn-sm btn-primary mt-1">
                                    Respond
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-muted">No reviews yet</p>
                    @endforelse

                    @if($recentReviews->count() > 0)
                        <div class="text-center">
                            <a href="{{ route('seller.reviews.index') }}" class="btn btn-primary btn-sm">
                                View All Reviews
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Add New Product
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('seller.orders.pending') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-clock"></i> View Pending Orders
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill-wave"></i> Request Withdrawal
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('seller.reports.sales') }}" class="btn btn-info btn-block">
                                <i class="fas fa-chart-bar"></i> View Reports
                            </a>
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
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesChartLabels),
            datasets: [{
                label: 'Sales (Rs)',
                data: @json($salesChartValues),
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                lineTension: 0.3
            }, {
                label: 'Orders',
                data: @json($ordersChartValues),
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderColor: 'rgba(28, 200, 138, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                pointBorderColor: 'rgba(28, 200, 138, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(28, 200, 138, 1)',
                pointHoverBorderColor: 'rgba(28, 200, 138, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                lineTension: 0.3,
                yAxisID: 'y-axis-2'
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    id: 'y-axis-1',
                    position: 'left',
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
                            return 'Rs ' + value.toLocaleString();
                        }
                    },
                    gridLines: {
                        color: 'rgb(234, 236, 244)',
                        zeroLineColor: 'rgb(234, 236, 244)',
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }, {
                    id: 'y-axis-2',
                    position: 'right',
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value, index, values) {
                            return value + ' orders';
                        }
                    },
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: true
            },
            tooltips: {
                backgroundColor: 'rgb(255,255,255)',
                bodyFontColor: '#858796',
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        if (tooltipItem.datasetIndex === 0) {
                            return datasetLabel + ': Rs ' + tooltipItem.yLabel.toLocaleString();
                        } else {
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        }
    });

    // Order Status Pie Chart
    var statusData = @json($orderStatusData);
    var labels = [];
    var data = [];
    var backgroundColor = [];

    var statusConfig = {
        'pending': { label: 'Pending', color: '#f6c23e' },
        'processing': { label: 'Processing', color: '#4e73df' },
        'shipped': { label: 'Shipped', color: '#36b9cc' },
        'delivered': { label: 'Delivered', color: '#1cc88a' },
        'cancelled': { label: 'Cancelled', color: '#e74a3b' },
        'refunded': { label: 'Refunded', color: '#858796' }
    };

    for (var status in statusConfig) {
        if (statusData[status] && statusData[status] > 0) {
            labels.push(statusConfig[status].label);
            data.push(statusData[status]);
            backgroundColor.push(statusConfig[status].color);
        }
    }

    if (data.length > 0) {
        var ctx2 = document.getElementById('orderStatusChart').getContext('2d');
        var orderStatusChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColor,
                    hoverBorderColor: 'rgba(234, 236, 244, 1)',
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyFontColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    }
</script>
@endsection


