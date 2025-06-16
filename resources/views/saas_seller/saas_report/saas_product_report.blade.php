@extends('saas_seller.saas_layouts.saas_layout')

@section('styles')
<style>
    .analytics-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .analytics-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .metric-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
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
        flex-shrink: 0;
    }

    .metric-icon svg {
        width: 16px;
        height: 16px;
    }

    .metric-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.2;
    }

    .metric-subtitle {
        color: #6c757d;
        font-size: 0.75rem;
        margin: 0;
        line-height: 1.2;
    }

    .performance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .performance-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        position: relative;
        overflow: hidden;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .performance-card::before {
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

    .performance-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .performance-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .performance-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .performance-card.danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    }

    .performance-value {
        font-size: 1.6rem;
        font-weight: bold;
        margin: 8px 0 4px;
        position: relative;
        z-index: 2;
        line-height: 1.2;
    }

    .performance-label {
        font-size: 0.8rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
        line-height: 1.2;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .product-table-enhanced {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .product-table-enhanced thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .product-table-enhanced thead th {
        border: none;
        padding: 20px 15px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .product-table-enhanced tbody td {
        padding: 18px 15px;
        border: none;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }

    .product-table-enhanced tbody tr:hover {
        background-color: #f8f9fa;
    }

    .product-image {
        width: 55px;
        height: 55px;
        border-radius: 10px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-details h6 {
        margin: 0 0 5px;
        font-weight: 600;
        color: #2c3e50;
    }

    .product-details small {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .progress-enhanced {
        height: 10px;
        border-radius: 5px;
        background-color: #e9ecef;
        overflow: hidden;
    }

    .progress-enhanced .progress-bar {
        border-radius: 5px;
        transition: width 0.6s ease;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .status-inactive {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .status-low-stock {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }

    .status-out-stock {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .chart-container-enhanced {
        position: relative;
        height: 400px;
        padding: 20px;
        background: #fafbfc;
        border-radius: 10px;
        margin: 20px 0;
    }

    .inventory-alert {
        background: linear-gradient(135deg, #ff9a56 0%, #ffad56 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .category-performance {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 20px 0;
    }

    .category-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .trend-analysis {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }

    .action-button {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📦 Product Performance Analytics</h1>
            <p class="text-muted">Comprehensive insights into your product portfolio</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('seller.products.create') }}" class="btn btn-success">
                <i class="align-middle" data-feather="plus"></i> Add Product
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                       value="{{ request('start_date', date('Y-m-d', strtotime($startDate))) }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                       value="{{ request('end_date', date('Y-m-d', strtotime($endDate))) }}">
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label fw-semibold">Category</label>
                <select class="form-select" id="category" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="align-middle" data-feather="filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Performance Overview -->
    <div class="performance-grid">
        <div class="performance-card">
            <div class="performance-value">{{ $totalProducts }}</div>
            <div class="performance-label">Total Products</div>
        </div>
        <div class="performance-card success">
            <div class="performance-value">{{ $activeProducts }}</div>
            <div class="performance-label">Active Products</div>
        </div>
        <div class="performance-card warning">
            <div class="performance-value">{{ $lowStockProducts->count() }}</div>
            <div class="performance-label">Low Stock Items</div>
        </div>
        <div class="performance-card danger">
            <div class="performance-value">{{ $outOfStockProducts }}</div>
            <div class="performance-label">Out of Stock</div>
        </div>
        <div class="performance-card info">
            <div class="performance-value">Rs {{ number_format($totalRevenue, 0) }}</div>
            <div class="performance-label">Total Revenue</div>
        </div>
        <div class="performance-card">
            <div class="performance-value">{{ number_format($averageRating, 1) }} ⭐</div>
            <div class="performance-label">Average Rating</div>
        </div>
    </div>

    <!-- Inventory Alert -->
    @if($lowStockProducts->count() > 0 || $outOfStockProducts > 0)
    <div class="inventory-alert">
        <div class="d-flex align-items-center">
            <i data-feather="alert-triangle" style="width: 30px; height: 30px; margin-right: 15px;"></i>
            <div>
                <h5 class="mb-1">⚠️ Inventory Alert</h5>
                <p class="mb-0">
                    You have {{ $lowStockProducts->count() }} products with low stock and {{ $outOfStockProducts }} out of stock items that need attention.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Top Performing Products -->
        <div class="col-xl-12">
            <div class="analytics-card">
                <div class="metric-header">
                    <div class="metric-icon bg-success">
                        <i data-feather="trending-up"></i>
                    </div>
                    <div>
                        <h5 class="metric-title">Top Performing Products</h5>
                        <p class="metric-subtitle">Based on sales volume and revenue for selected period</p>
                    </div>
                </div>

                @if($topProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table product-table-enhanced">
                            <thead>
                                <tr>
                                    <th class="text-white">Product</th>
                                    <th class="text-white">Category</th>
                                    <th class="text-white">Units Sold</th>
                                    <th class="text-white">Revenue</th>
                                    <th class="text-white">Rating</th>
                                    <th class="text-white">Performance</th>
                                    <th class="text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            @if(isset($product->images) && $product->images && $product->images->count() > 0)
                                                <img src="{{ $product->images->first()->image_url }}"
                                                     alt="{{ $product->name }}" class="product-image">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i data-feather="image" class="text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="product-details">
                                                <h6>{{ Str::limit($product->name, 25) }}</h6>
                                                <small>SKU: {{ $product->SKU ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ isset($product->category) && $product->category ? $product->category->name : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $product->quantity_sold ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">Rs {{ number_format($product->total_sales ?? 0, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ number_format($product->avg_rating ?? 0, 1) }}</span>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= ($product->avg_rating ?? 0))
                                                        ⭐
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="progress-enhanced">
                                            @php
                                                $maxSales = $topProducts->max('total_sales') ?? 1;
                                                $percentage = $maxSales > 0 ? (($product->total_sales ?? 0) / $maxSales) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ number_format($percentage, 1) }}%</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.products.show', $product) }}"
                                               class="action-button btn-outline-primary">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ route('seller.products.edit', $product) }}"
                                               class="action-button btn-outline-warning">
                                                <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i data-feather="package" class="text-muted" style="width: 64px; height: 64px;"></i>
                        <p class="text-muted mt-3">No product sales data available for this period.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Category Performance & Charts -->
        <div class="col-xl-12 row gap-2">
            <!-- Category Distribution -->
            <div class="col-md-5 analytics-card">
                <div class="metric-header">
                    <div class="metric-icon bg-primary">
                        <i data-feather="pie-chart"></i>
                    </div>
                    <div>
                        <h5 class="metric-title">Category Distribution</h5>
                        <p class="metric-subtitle">Products by category</p>
                    </div>
                </div>

                @if($productsByCategory->count() > 0)
                    <div class="chart-container-enhanced">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="category-performance">
                        @foreach($productsByCategory as $category)
                        <div class="category-card">
                            <h6 class="fw-bold">{{ $category->name }}</h6>
                            <p class="text-primary fw-bold mb-1">{{ $category->product_count }}</p>
                            <small class="text-muted">Products</small>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i data-feather="grid" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <p class="text-muted mt-2">No categories found.</p>
                    </div>
                @endif
            </div>

            <!-- Stock Status Overview -->
            <div class="col-md-5 analytics-card">
                <div class="metric-header">
                    <div class="metric-icon bg-warning">
                        <i data-feather="package"></i>
                    </div>
                    <div>
                        <h5 class="metric-title">Stock Overview</h5>
                        <p class="metric-subtitle">Inventory status summary</p>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">In Stock</span>
                    <span class="badge bg-success">{{ $activeProducts - $lowStockProducts->count() - $outOfStockProducts }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Low Stock</span>
                    <span class="badge bg-warning">{{ $lowStockProducts->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold">Out of Stock</span>
                    <span class="badge bg-danger">{{ $outOfStockProducts }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Inactive</span>
                    <span class="badge bg-secondary">{{ $totalProducts - $activeProducts }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Products Alert -->
    @if($lowStockProducts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="analytics-card">
                <div class="metric-header">
                    <div class="metric-icon bg-danger">
                        <i data-feather="alert-triangle"></i>
                    </div>
                    <div>
                        <h5 class="metric-title">⚠️ Low Stock Alert</h5>
                        <p class="metric-subtitle">Products that need immediate restocking</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table product-table-enhanced">
                        <thead>
                            <tr>
                                <th class="text-white">Product</th>
                                <th class="text-white">Category</th>
                                <th class="text-white">Current Stock</th>
                                <th class="text-white">Status</th>
                                <th class="text-white">Last Sold</th>
                                <th class="text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        @if(isset($product->images) && $product->images && $product->images->count() > 0)
                                            <img src="{{ $product->images->first()->image_url }}"
                                                 alt="{{ $product->name }}" class="product-image">
                                        @else
                                            <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                <i data-feather="image" class="text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="product-details">
                                            <h6>{{ Str::limit($product->name, 30) }}</h6>
                                            <small>SKU: {{ $product->SKU ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ isset($product->category) && $product->category ? $product->category->name : 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= 5 ? 'text-warning' : 'text-info') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->stock <= 0)
                                        <span class="status-badge status-out-stock">Out of Stock</span>
                                    @elseif($product->stock <= 5)
                                        <span class="status-badge status-low-stock">Low Stock</span>
                                    @else
                                        <span class="status-badge status-active">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $product->updated_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('seller.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i data-feather="edit" style="width: 14px; height: 14px;"></i> Update Stock
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Performance Insights -->
    <div class="row">
        <div class="col-12">
            <div class="trend-analysis">
                <h5 class="fw-bold mb-4">📈 Performance Insights & Recommendations</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-primary fw-semibold">🎯 Best Performing Categories</h6>
                            @if($topCategories->count() > 0)
                                @foreach($topCategories->take(3) as $category)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $category->name }}</span>
                                    <span class="badge bg-success">{{ $category->total_revenue ? 'Rs ' . number_format($category->total_revenue, 0) : 'No sales' }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No category performance data available.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h6 class="text-warning fw-semibold">⚡ Quick Actions Needed</h6>
                            <ul class="list-unstyled">
                                @if($lowStockProducts->count() > 0)
                                    <li class="mb-2">
                                        <i data-feather="alert-circle" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                                        Restock {{ $lowStockProducts->count() }} low inventory items
                                    </li>
                                @endif
                                @if($outOfStockProducts > 0)
                                    <li class="mb-2">
                                        <i data-feather="x-circle" class="text-danger me-2" style="width: 16px; height: 16px;"></i>
                                        Address {{ $outOfStockProducts }} out-of-stock products
                                    </li>
                                @endif
                                @if($inactiveProducts > 0)
                                    <li class="mb-2">
                                        <i data-feather="pause-circle" class="text-secondary me-2" style="width: 16px; height: 16px;"></i>
                                        Review {{ $inactiveProducts }} inactive products
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="text-info fw-semibold">💡 Growth Opportunities</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i data-feather="trending-up" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                    Focus on promoting top-rated products
                                </li>
                                <li class="mb-2">
                                    <i data-feather="target" class="text-primary me-2" style="width: 16px; height: 16px;"></i>
                                    Expand inventory in best-performing categories
                                </li>
                                <li class="mb-2">
                                    <i data-feather="star" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                                    Improve products with low ratings
                                </li>
                                <li class="mb-2">
                                    <i data-feather="zap" class="text-info me-2" style="width: 16px; height: 16px;"></i>
                                    Consider bundling complementary products
                                </li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-success fw-semibold">📊 Key Metrics</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <div class="fw-bold text-primary">{{ number_format($conversionRate, 1) }}%</div>
                                        <small class="text-muted">Conversion Rate</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <div class="fw-bold text-success">Rs {{ number_format($avgOrderValue, 0) }}</div>
                                        <small class="text-muted">Avg Order Value</small>
                                    </div>
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
    // Category Distribution Chart
    @if($productsByCategory->count() > 0)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($productsByCategory->pluck('name')),
            datasets: [{
                data: @json($productsByCategory->pluck('product_count')),
                backgroundColor: [
                    '#667eea',
                    '#11998e',
                    '#f093fb',
                    '#4facfe',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796',
                    '#36b9cc',
                    '#1cc88a'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 5,
                hoverOffset: 10
            }]
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
                            size: 12,
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
            cutout: '60%',
            animation: {
                animateRotate: true,
                duration: 2000
            }
        }
    });
    @endif
});



// Initialize feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>
@endsection
