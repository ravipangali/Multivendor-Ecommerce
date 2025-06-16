@extends('saas_seller.saas_layouts.saas_layout')

@section('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .stat-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card.dark {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .chart-container {
        position: relative;
        height: 400px;
        margin: 20px 0;
    }

    .metric-card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-5px);
    }

    .progress-modern {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }

    .progress-modern .progress-bar {
        border-radius: 4px;
    }

    .table-modern {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 14px;
    }

    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .trend-up {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .trend-down {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .trend-neutral {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .period-selector {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .quick-stats {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .kpi-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
    }

    .kpi-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 14px;
    }

    .kpi-icon svg {
        width: 14px;
        height: 14px;
    }

    .kpi-value {
        font-size: 1.3rem;
        font-weight: bold;
        margin: 6px 0 4px;
        line-height: 1.2;
    }

    .kpi-label {
        color: #6c757d;
        font-size: 0.7rem;
        line-height: 1.2;
    }

    .trend-indicator svg {
        width: 10px;
        height: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📊 Sales Analytics Dashboard</h1>
            <p class="text-muted">Comprehensive sales performance insights</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="refreshData()">
                <i class="align-middle" data-feather="refresh-cw"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="period-selector">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                       value="{{ request('start_date', $startDate) }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control"
                       value="{{ request('end_date', $endDate) }}">
            </div>
            <div class="col-md-3">
                <label for="period" class="form-label fw-semibold">Quick Select</label>
                <select name="period" id="period" class="form-select" onchange="setQuickPeriod()">
                    <option value="">Custom Range</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last_7_days">Last 7 Days</option>
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_year">This Year</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="align-middle" data-feather="filter"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Key Performance Indicators -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon bg-primary text-white">
                                            <span class="rs-icon">Rs</span>
            </div>
            <div class="kpi-value text-primary">Rs {{ number_format($totalSales, 2) }}</div>
            <div class="kpi-label">Total Revenue</div>
            <div class="trend-indicator trend-up mt-2">
                <i data-feather="trending-up" style="width: 10px; height: 10px;"></i>
                +12.5%
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon bg-success text-white">
                <i data-feather="shopping-cart"></i>
            </div>
            <div class="kpi-value text-success">{{ $totalOrders }}</div>
            <div class="kpi-label">Total Orders</div>
            <div class="trend-indicator trend-up mt-2">
                <i data-feather="trending-up" style="width: 10px; height: 10px;"></i>
                +8.3%
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon bg-info text-white">
                <i data-feather="trending-up"></i>
            </div>
            <div class="kpi-value text-info">Rs {{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 2) : '0.00' }}</div>
            <div class="kpi-label">Average Order Value</div>
            <div class="trend-indicator trend-up mt-2">
                <i data-feather="trending-up" style="width: 10px; height: 10px;"></i>
                +3.7%
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon bg-warning text-white">
                <i data-feather="users"></i>
            </div>
            <div class="kpi-value text-warning">{{ $uniqueCustomers }}</div>
            <div class="kpi-label">Unique Customers</div>
            <div class="trend-indicator trend-up mt-2">
                <i data-feather="trending-up" style="width: 10px; height: 10px;"></i>
                +15.2%
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon bg-danger text-white">
                <i data-feather="percent"></i>
            </div>
            <div class="kpi-value text-danger">{{ $conversionRate }}%</div>
            <div class="kpi-label">Conversion Rate</div>
            <div class="trend-indicator trend-neutral mt-2">
                <i data-feather="minus" style="width: 10px; height: 10px;"></i>
                0%
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-icon bg-secondary text-white">
                <i data-feather="repeat"></i>
            </div>
            <div class="kpi-value text-secondary">{{ $returnCustomers }}%</div>
            <div class="kpi-label">Return Customer Rate</div>
            <div class="trend-indicator trend-up mt-2">
                <i data-feather="trending-up" style="width: 10px; height: 10px;"></i>
                +2.1%
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trend Chart -->
        <div class="col-xl-8">
            <div class="card metric-card">
                <div class="card-header bg-transparent">
                    <h5 class="section-title">
                        <i data-feather="trending-up"></i>
                        Revenue Trend Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 text-center">
                            <div class="fw-bold text-primary">{{ $dailyAverage }}</div>
                            <small class="text-muted">Daily Average</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-bold text-success">{{ $peakDay }}</div>
                            <small class="text-muted">Best Day</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-bold text-info">{{ $growth }}%</div>
                            <small class="text-muted">Growth Rate</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="fw-bold text-warning">{{ $forecast }}</div>
                            <small class="text-muted">Next Week Est.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Breakdown -->
        <div class="col-xl-4">
            <div class="card metric-card h-100">
                <div class="card-header bg-transparent">
                    <h5 class="section-title">
                        <i data-feather="pie-chart"></i>
                        Sales Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="salesBreakdownChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($salesByCategory as $category)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded" style="width: 12px; height: 12px; margin-right: 8px;"></div>
                                <span class="fw-semibold">{{ $category->name }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">Rs {{ number_format($category->total_sales, 0) }}</div>
                                <small class="text-muted">{{ $category->percentage }}%</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-4">
        <!-- Payment Methods Analysis -->
        <div class="col-xl-6">
            <div class="card metric-card">
                <div class="card-header bg-transparent">
                    <h5 class="section-title">
                        <i data-feather="credit-card"></i>
                        Payment Methods Performance
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($salesByPaymentMethod as $payment)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary me-3">
                                @if($payment->payment_method == 'esewa')
                                    E
                                @elseif($payment->payment_method == 'khalti')
                                    K
                                @elseif($payment->payment_method == 'bank_transfer')
                                    B
                                @else
                                    {{ strtoupper(substr($payment->payment_method, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</h6>
                                <small class="text-muted">{{ $payment->count }} transactions</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">Rs {{ number_format($payment->total, 2) }}</div>
                            <div class="progress-modern mt-2" style="width: 100px;">
                                <div class="progress-bar bg-primary" style="width: {{ ($payment->total / $totalSales) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="align-middle text-muted" data-feather="credit-card" style="width: 48px; height: 48px;"></i>
                        <p class="text-muted mt-2">No payment data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col-xl-6">
            <div class="card metric-card">
                <div class="card-header bg-transparent">
                    <h5 class="section-title">
                        <i data-feather="package"></i>
                        Order Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 200px;">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="row mt-3">
                        @foreach($orderStatusData as $status => $count)
                        <div class="col-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $status == 'delivered' ? 'success' : ($status == 'pending' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($status) }}
                                </span>
                                <span class="fw-bold">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row mb-4">
        <!-- Top Performing Products -->
        <div class="col-12">
            <div class="card metric-card">
                <div class="card-header bg-transparent">
                    <h5 class="section-title">
                        <i data-feather="award"></i>
                        Top Performing Products
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th class="text-white">Rank</th>
                                    <th class="text-white">Product</th>
                                    <th class="text-white">Category</th>
                                    <th class="text-white">Units Sold</th>
                                    <th class="text-white">Revenue</th>
                                    <th class="text-white">Avg. Rating</th>
                                    <th class="text-white">Profit Margin</th>
                                    <th class="text-white">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $product)
                                <tr>
                                    <td>
                                        <div class="badge bg-primary">#{{ $index + 1 }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(isset($product->images) && $product->images && $product->images->count() > 0)
                                                <img src="{{ $product->images->first()->image_url }}"
                                                     alt="{{ $product->name }}"
                                                     class="rounded me-2"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="avatar-circle bg-secondary me-2">
                                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($product->name, 30) }}</div>
                                                <small class="text-muted">SKU: {{ $product->SKU ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ isset($product->category) && $product->category ? $product->category->name : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $product->units_sold }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">Rs {{ number_format($product->total_revenue, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-1">{{ number_format($product->avg_rating, 1) }}</span>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $product->avg_rating)
                                                        ⭐
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ $product->profit_margin }}%</span>
                                    </td>
                                    <td>
                                        <div class="progress-modern">
                                            <div class="progress-bar bg-success" style="width: {{ min(($product->total_revenue / $topProducts->max('total_revenue')) * 100, 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card metric-card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="section-title">
                        <i data-feather="list"></i>
                        Recent Transactions
                    </h5>
                    <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary btn-sm">
                        View All Orders
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th class="text-white">Order ID</th>
                                    <th class="text-white">Customer</th>
                                    <th class="text-white">Date & Time</th>
                                    <th class="text-white">Items</th>
                                    <th class="text-white">Amount</th>
                                    <th class="text-white">Payment</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $order) }}" class="fw-bold text-decoration-none">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary me-2">
                                                {{ strtoupper(substr($order->customer->name ?? 'G', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $order->customer->name ?? 'Guest Customer' }}</div>
                                                <small class="text-muted">{{ $order->customer->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $order->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->items->count() }} items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">Rs {{ number_format($order->total, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->order_status === 'delivered' ? 'success' : ($order->order_status === 'pending' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                <i data-feather="eye" style="width: 12px; height: 12px;"></i>
                                            </a>
                                            <a href="{{ route('seller.orders.invoice', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                <i data-feather="printer" style="width: 12px; height: 12px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="align-middle text-muted" data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                        <p class="text-muted mt-2">No transactions found for the selected period.</p>
                                    </td>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($salesByDate->pluck('date')),
            datasets: [{
                label: 'Daily Revenue',
                data: @json($salesByDate->pluck('total')),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
            }, {
                label: 'Order Count',
                data: @json($salesByDate->pluck('orders')),
                borderColor: '#11998e',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                tension: 0.4,
                fill: false,
                yAxisID: 'y1',
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#11998e',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Revenue: Rs ' + context.parsed.y.toLocaleString();
                            } else {
                                return 'Orders: ' + context.parsed.y;
                            }
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rs)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rs ' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Sales Breakdown Chart
    const breakdownCtx = document.getElementById('salesBreakdownChart').getContext('2d');
    new Chart(breakdownCtx, {
        type: 'doughnut',
        data: {
            labels: @json($salesByCategory->pluck('name')),
            datasets: [{
                data: @json($salesByCategory->pluck('total_sales')),
                backgroundColor: [
                    '#667eea',
                    '#11998e',
                    '#f093fb',
                    '#4facfe',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': Rs ' + context.parsed.toLocaleString();
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: @json(array_keys($orderStatusData)),
            datasets: [{
                data: @json(array_values($orderStatusData)),
                backgroundColor: [
                    '#f6c23e',
                    '#4e73df',
                    '#36b9cc',
                    '#1cc88a',
                    '#e74a3b',
                    '#858796'
                ],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});

// Utility Functions
function setQuickPeriod() {
    const period = document.getElementById('period').value;
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const today = new Date();

    switch(period) {
        case 'today':
            startDate.value = today.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            startDate.value = yesterday.toISOString().split('T')[0];
            endDate.value = yesterday.toISOString().split('T')[0];
            break;
        case 'last_7_days':
            const last7Days = new Date(today);
            last7Days.setDate(last7Days.getDate() - 7);
            startDate.value = last7Days.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'last_30_days':
            const last30Days = new Date(today);
            last30Days.setDate(last30Days.getDate() - 30);
            startDate.value = last30Days.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'this_month':
            const thisMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
            startDate.value = thisMonthStart.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
        case 'last_month':
            const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
            startDate.value = lastMonthStart.toISOString().split('T')[0];
            endDate.value = lastMonthEnd.toISOString().split('T')[0];
            break;
        case 'this_year':
            const thisYearStart = new Date(today.getFullYear(), 0, 1);
            startDate.value = thisYearStart.toISOString().split('T')[0];
            endDate.value = today.toISOString().split('T')[0];
            break;
    }
}



function refreshData() {
    location.reload();
}

        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
</script>
@endsection
