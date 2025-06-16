@extends('saas_seller.saas_layouts.saas_layout')

@section('styles')
<style>
    /* Modern Dashboard Styling */
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 25px 20px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-hero::before {
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

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .hero-stat {
        text-align: center;
        padding: 15px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .hero-stat-value {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .hero-stat-label {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    /* Compact Dashboard Cards */
    .dashboard-card {
        background: white;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 12px 12px 0 0;
    }

    .metric-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .metric-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
        margin-bottom: 12px;
        flex-shrink: 0;
    }

    .metric-value {
        font-size: 1.6rem;
        font-weight: bold;
        margin-bottom: 6px;
        color: #2c3e50;
        line-height: 1.2;
    }

    .metric-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        line-height: 1.2;
    }

    .metric-change {
        font-size: 0.72rem;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .metric-change.positive {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    .metric-change.negative {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }

    .metric-change.neutral {
        background: linear-gradient(135deg, #e2e3e5, #d6d8db);
        color: #495057;
    }

    .metric-change i {
        font-size: 10px;
    }

    /* Color Themes for Metric Cards */
    .metric-card.primary { border-left: 3px solid #667eea; }
    .metric-card.success { border-left: 3px solid #1cc88a; }
    .metric-card.info { border-left: 3px solid #36b9cc; }
    .metric-card.warning { border-left: 3px solid #f6c23e; }
    .metric-card.danger { border-left: 3px solid #e74a3b; }
    .metric-card.secondary { border-left: 3px solid #858796; }

    .metric-card.primary .metric-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
    .metric-card.success .metric-icon { background: linear-gradient(135deg, #1cc88a, #17a2b8); }
    .metric-card.info .metric-icon { background: linear-gradient(135deg, #36b9cc, #17a2b8); }
    .metric-card.warning .metric-icon { background: linear-gradient(135deg, #f6c23e, #f39c12); }
    .metric-card.danger .metric-icon { background: linear-gradient(135deg, #e74a3b, #dc3545); }
    .metric-card.secondary .metric-icon { background: linear-gradient(135deg, #858796, #6c757d); }

    /* Enhanced Chart Containers */
    .chart-container-modern {
        background: #fafbfc;
        border-radius: 12px;
        padding: 15px;
        margin: 15px 0;
        position: relative;
        height: 300px;
    }

    .chart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f3f4;
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .chart-subtitle {
        color: #6c757d;
        font-size: 0.75rem;
        margin: 0;
    }

    /* Enhanced Tables */
    .modern-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        background: white;
        font-size: 0.85rem;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .modern-table th,
    .modern-table td {
        padding: 10px 12px;
        vertical-align: middle;
    }

    .modern-table th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .modern-table td {
        border-bottom: 1px solid #f1f3f4;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Quick Actions */
    .quick-actions {
        margin-bottom: 25px;
    }

    .quick-action-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
    }

    .action-btn {
        background: white;
        border: none;
        border-radius: 12px;
        padding: 15px;
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        color: #667eea;
        text-decoration: none;
    }

    .action-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }

    .action-content h6 {
        margin: 0 0 2px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .action-content small {
        color: #6c757d;
        font-size: 0.72rem;
    }

    /* Responsive Grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 992px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .metrics-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    /* Product Performance Cards */
    .product-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .product-stats {
        font-size: 0.72rem;
        color: #6c757d;
    }

    .product-performance {
        text-align: right;
        min-width: 80px;
    }

    .performance-bar {
        width: 60px;
        height: 4px;
        background: #f1f3f4;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .performance-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }

    .performance-value {
        font-size: 0.7rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Recent Reviews */
    .review-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f4;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-info {
        flex: 1;
    }

    .review-rating {
        color: #ffc107;
        font-size: 0.75rem;
        margin-bottom: 4px;
    }

    .review-text {
        font-size: 0.8rem;
        color: #2c3e50;
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .review-meta {
        font-size: 0.7rem;
        color: #6c757d;
    }

    /* Utilities */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    .slide-up {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Compact badges and buttons */
    .btn-sm {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .badge {
        font-size: 0.65rem;
        padding: 3px 6px;
        border-radius: 6px;
    }

    /* Small text utilities */
    small {
        font-size: 0.7rem;
    }

    .text-xs {
        font-size: 0.65rem;
    }

    /* Compact table status badges */
    .status-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 6px;
        font-weight: 500;
    }

        /* Compact metric card bottom section */
    .metric-card .d-flex {
        align-items: center;
        margin-top: 8px;
    }

    .metric-card .d-flex small {
        font-size: 0.68rem;
    }

    .metric-card .btn-sm {
        width: 24px;
        height: 24px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-card .btn-sm svg {
        width: 12px;
        height: 12px;
    }

    /* Feather icon sizing */
    .metric-icon svg {
        width: 16px;
        height: 16px;
    }

    .action-icon svg {
        width: 14px;
        height: 14px;
    }

    .metric-change svg {
        width: 10px;
        height: 10px;
    }

    /* Perfect alignment for metric cards */
    .metric-card {
        min-height: 140px;
        display: flex;
        flex-direction: column;
    }

    .metric-card .metric-icon {
        margin-bottom: 8px;
    }

    .metric-card .metric-value {
        flex-grow: 1;
        display: flex;
        align-items: flex-start;
    }

    .metric-card .d-flex:last-child {
        margin-top: auto;
    }

    /* Quick action alignment */
    .action-btn {
        min-height: 70px;
        align-items: center;
    }

    .action-content {
        flex-grow: 1;
    }

    /* Table responsive improvements */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    /* Status badge improvements */
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Hero Section -->
    <div class="dashboard-hero fade-in">
        <div class="hero-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h1 class="hero-title text-white">🎉 Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="hero-subtitle">Here's what's happening with your store today. Let's make it a great day!</p>
                </div>
                <div class="text-end">
                    <div class="text-white-50 mb-1 text-xs">{{ date('l, F j, Y') }}</div>
                    <div class="fs-6 fw-bold">{{ date('g:i A') }}</div>
                </div>
            </div>

            <div class="quick-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value">Rs {{ number_format($wallet->balance, 0) }}</div>
                    <div class="hero-stat-label">💰 Available Balance</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">{{ $pendingOrders }}</div>
                    <div class="hero-stat-label">⏳ Pending Orders</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">Rs {{ number_format($todaySales, 0) }}</div>
                    <div class="hero-stat-label">📈 Today's Sales</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value">{{ number_format($averageRating, 1) }} ⭐</div>
                    <div class="hero-stat-label">🌟 Store Rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="metrics-grid slide-up">
        <!-- Wallet Balance -->
        <div class="metric-card primary">
            <div class="metric-icon">
                <i data-feather="credit-card"></i>
            </div>
            <div class="metric-value">Rs {{ number_format($wallet->balance, 2) }}</div>
            <div class="metric-label">Wallet Balance</div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Pending: Rs {{ number_format($wallet->pending_balance, 2) }}</small>
                <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-sm btn-outline-primary">
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="metric-card success">
            <div class="metric-icon">
                <i data-feather="trending-up"></i>
            </div>
            <div class="metric-value">Rs {{ number_format($totalSales, 2) }}</div>
            <div class="metric-label">Total Sales (All Time)</div>
            <div class="metric-change positive">
                <i data-feather="arrow-up"></i> Today: Rs {{ number_format($todaySales, 2) }}
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="metric-card info">
            <div class="metric-icon">
                <i data-feather="calendar"></i>
            </div>
            <div class="metric-value">Rs {{ number_format($thisMonthSales, 2) }}</div>
            <div class="metric-label">This Month's Sales</div>
            <div class="metric-change {{ $growthPercentage > 0 ? 'positive' : ($growthPercentage < 0 ? 'negative' : 'neutral') }}">
                @if($growthPercentage > 0)
                    <i data-feather="arrow-up"></i> +{{ $growthPercentage }}%
                @elseif($growthPercentage < 0)
                    <i data-feather="arrow-down"></i> {{ $growthPercentage }}%
                @else
                    <i data-feather="minus"></i> 0%
                @endif
                vs last month
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="metric-card warning">
            <div class="metric-icon">
                <i data-feather="shopping-cart"></i>
            </div>
            <div class="metric-value">{{ $pendingOrders }}</div>
            <div class="metric-label">Pending Orders</div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Total: {{ $totalOrders }}</small>
                <a href="{{ route('seller.orders.index') }}" class="btn btn-sm btn-outline-warning">
                    <i data-feather="eye"></i>
                </a>
            </div>
        </div>

        <!-- Total Products -->
        <div class="metric-card secondary">
            <div class="metric-icon">
                <i data-feather="package"></i>
            </div>
            <div class="metric-value">{{ $totalProducts }}</div>
            <div class="metric-label">Total Products</div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-success">Active: {{ $activeProducts }}</small>
                <a href="{{ route('seller.products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i data-feather="plus"></i>
                </a>
            </div>
        </div>

        <!-- Stock Alert -->
        <div class="metric-card danger">
            <div class="metric-icon">
                <i data-feather="alert-triangle"></i>
            </div>
            <div class="metric-value">{{ $outOfStockProducts + $lowStockProducts }}</div>
            <div class="metric-label">Stock Alerts</div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-danger">Out: {{ $outOfStockProducts }} | Low: {{ $lowStockProducts }}</small>
                <button class="btn btn-sm btn-outline-danger" onclick="alert('Check inventory management')">
                    <i data-feather="bell"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="quick-actions slide-up">
        <h5 class="quick-action-title">🚀 Quick Actions</h5>
        <div class="action-grid">
            <a href="{{ route('seller.products.create') }}" class="action-btn">
                <div class="action-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i data-feather="plus"></i>
                </div>
                <div class="action-content">
                    <h6>Add New Product</h6>
                    <small>Expand your inventory</small>
                </div>
            </a>

            <a href="{{ route('seller.orders.index') }}" class="action-btn">
                <div class="action-icon" style="background: linear-gradient(135deg, #f6c23e, #f39c12);">
                    <i data-feather="shopping-bag"></i>
                </div>
                <div class="action-content">
                    <h6>Manage Orders</h6>
                    <small>Process customer orders</small>
                </div>
            </a>

            <a href="{{ route('seller.reports.sales') }}" class="action-btn">
                <div class="action-icon" style="background: linear-gradient(135deg, #1cc88a, #17a2b8);">
                    <i data-feather="bar-chart-2"></i>
                </div>
                <div class="action-content">
                    <h6>View Reports</h6>
                    <small>Analyze your performance</small>
                </div>
            </a>

            <a href="{{ route('seller.withdrawals.create') }}" class="action-btn">
                <div class="action-icon" style="background: linear-gradient(135deg, #36b9cc, #17a2b8);">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="action-content">
                    <h6>Request Withdrawal</h6>
                    <small>Transfer your earnings</small>
                </div>
            </a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-grid slide-up">
        <!-- Sales Chart -->
        <div class="dashboard-card">
            <div class="chart-header">
                <div>
                    <h5 class="chart-title">📊 Sales & Orders Overview</h5>
                    <p class="chart-subtitle">Performance trends over the last 12 months</p>
                </div>
            </div>
            <div class="chart-container-modern">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="dashboard-card">
            <div class="chart-header">
                <div>
                    <h5 class="chart-title">🎯 Order Status</h5>
                    <p class="chart-subtitle">Current order distribution</p>
                </div>
            </div>
            <div class="chart-container-modern" style="height: 240px;">
                <canvas id="orderStatusChart"></canvas>
            </div>
            <div class="mt-2 text-center">
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
                        <span class="badge me-1 mb-1" style="background: {{ $config['color'] }};">
                            {{ $config['label'] }} ({{ $orderStatusData[$status] }})
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-8">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="chart-title">📋 Recent Orders</h5>
                        <p class="chart-subtitle">Latest customer orders requiring attention</p>
                    </div>
                    <a href="{{ route('seller.orders.index') }}" class="btn btn-primary btn-sm">
                        <i data-feather="arrow-right"></i> View All
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="modern-table table">
                        <thead>
                            <tr>
                                <th class="text-white">Order #</th>
                                <th class="text-white">Customer</th>
                                <th class="text-white">Date</th>
                                <th class="text-white">Amount</th>
                                <th class="text-white">Status</th>
                                <th class="text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                <i data-feather="user" style="width: 14px; height: 14px;" class="text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->customer ? $order->customer->name : 'Deleted User' }}</div>
                                            <small class="text-muted">{{ $order->customer ? $order->customer->email : 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <strong class="text-success">Rs {{ number_format($order->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning text-dark',
                                            'processing' => 'bg-info text-white',
                                            'shipped' => 'bg-primary text-white',
                                            'delivered' => 'bg-success text-white',
                                            'cancelled' => 'bg-danger text-white',
                                            'refunded' => 'bg-secondary text-white'
                                        ];
                                    @endphp
                                    <span class="status-badge {{ $statusClasses[$order->status] ?? 'bg-secondary text-white' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i data-feather="package" style="width: 24px; height: 24px;" class="mb-2"></i>
                                        <p class="mb-0">No recent orders found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Top Products -->
            <div class="dashboard-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="chart-title">🏆 Top Products</h5>
                        <p class="chart-subtitle">Best performing items</p>
                    </div>
                </div>

                @forelse($topProducts as $product)
                <div class="product-item">
                    <div class="product-info">
                        <div class="product-name">{{ Str::limit($product->name, 25) }}</div>
                        <div class="product-stats">
                            Rs {{ number_format($product->price, 2) }} •
                            Revenue: Rs {{ number_format($product->total_sales, 2) }}
                        </div>
                    </div>
                    <div class="product-performance">
                        @php
                            $maxSold = $topProducts->max('quantity_sold');
                            $percentage = $maxSold > 0 ? ($product->quantity_sold / $maxSold) * 100 : 0;
                        @endphp
                        <div class="performance-bar">
                            <div class="performance-fill" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="performance-value">{{ $product->quantity_sold }} sold</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3">
                    <i data-feather="package" style="width: 24px; height: 24px;" class="text-muted mb-2"></i>
                    <p class="text-muted mb-0 small">No sales data available</p>
                </div>
                @endforelse
            </div>

            <!-- Recent Reviews -->
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="chart-title">⭐ Recent Reviews</h5>
                        <p class="chart-subtitle">Latest customer feedback</p>
                    </div>
                    <a href="{{ route('seller.reviews.index') }}" class="btn btn-sm btn-outline-primary">
                        <i data-feather="arrow-right"></i>
                    </a>
                </div>

                @forelse($recentReviews as $review)
                <div class="review-item">
                    <div class="review-info">
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i data-feather="star" style="width: 12px; height: 12px; fill: currentColor;"></i>
                                @else
                                    <i data-feather="star" style="width: 12px; height: 12px;"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="review-text">{{ Str::limit($review->review ?? $review->comment ?? 'No comment', 50) }}</div>
                                                                        <div class="review-meta">
                            <strong>{{ $review->product ? $review->product->name : 'Deleted Product' }}</strong> •
                            {{ $review->customer ? $review->customer->name : 'Deleted User' }} •
                            {{ $review->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3">
                    <i data-feather="message-circle" style="width: 24px; height: 24px;" class="text-muted mb-2"></i>
                    <p class="text-muted mb-0 small">No reviews yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesChartLabels),
            datasets: [{
                label: 'Sales (Rs)',
                data: @json($salesChartValues),
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderColor: '#667eea',
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#667eea',
                pointHoverBorderColor: '#ffffff',
                pointHitRadius: 10,
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }, {
                label: 'Orders',
                data: @json($ordersChartValues),
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                borderColor: '#1cc88a',
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#1cc88a',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#1cc88a',
                pointHoverBorderColor: '#ffffff',
                pointHitRadius: 10,
                borderWidth: 3,
                tension: 0.4,
                yAxisID: 'y-axis-2'
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
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            if (context.datasetIndex === 0) {
                                return 'Sales: Rs ' + context.parsed.y.toLocaleString();
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
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    id: 'y-axis-1',
                    position: 'left',
                    display: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rs ' + value.toLocaleString();
                        },
                        font: {
                            size: 11
                        }
                    }
                },
                'y-axis-2': {
                    id: 'y-axis-2',
                    position: 'right',
                    display: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' orders';
                        },
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Enhanced Order Status Pie Chart
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
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 6,
                    hoverOffset: 8
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: {
                    animateRotate: true,
                    duration: 1500
                }
            }
        });
    }
});

// Add smooth scrolling animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all metric cards for animation
document.querySelectorAll('.metric-card, .dashboard-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'all 0.6s ease';
    observer.observe(card);
});

// Initialize Feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>
@endsection


