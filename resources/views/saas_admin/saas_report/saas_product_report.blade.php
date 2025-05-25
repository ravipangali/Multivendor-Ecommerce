@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Product Report')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
<style>
    .report-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-5px);
    }
    .report-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .date-filter {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .chart-container {
        height: 350px;
        margin-bottom: 20px;
    }
    .progress {
        height: 10px;
        border-radius: 5px;
    }
    .product-name {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stock-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .stock-low {
        background-color: #dc3545;
    }
    .stock-medium {
        background-color: #ffc107;
    }
    .stock-high {
        background-color: #28a745;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card date-filter mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.products') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" class="form-control flatpickr-date" id="start_date" name="start_date"
                           value="{{ request('start_date', date('Y-m-d', strtotime('-30 days'))) }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" class="form-control flatpickr-date" id="end_date" name="end_date"
                           value="{{ request('end_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Selling Products</h5>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Export
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">CSV</a></li>
                            <li><a class="dropdown-item" href="#">Excel</a></li>
                            <li><a class="dropdown-item" href="#">PDF</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Sold</th>
                                    <th>Sales Amount</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-name">
                                                <span class="fw-bold">{{ $product->name }}</span>
                                                <br>
                                                <small class="text-muted">ID: #{{ $product->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->quantity_sold }}</td>
                                    <td>${{ number_format($product->total_sales, 2) }}</td>
                                    <td>
                                        <div class="progress">
                                            @php
                                                $max = $topProducts->max('quantity_sold');
                                                $percentage = ($max > 0) ? ($product->quantity_sold / $max) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No products found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Products by Category</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="productsByCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Low Stock Products</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Seller</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="product-name">
                                        <span class="fw-bold">{{ $product->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                            <td>{{ $product->seller ? $product->seller->name : 'N/A' }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock < 5)
                                    <span class="stock-indicator stock-low"></span>
                                @elseif($product->stock < 10)
                                    <span class="stock-indicator stock-medium"></span>
                                @else
                                    <span class="stock-indicator stock-high"></span>
                                @endif
                                {{ $product->stock }}
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                    <i data-feather="edit-2" class="feather-sm"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No low stock products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize date pickers
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d",
        });

        // Initialize feather icons
        feather.replace();

        // Products by category chart
        const categoryData = @json($productsByCategory);
        const categories = categoryData.map(item => item.name);
        const productCounts = categoryData.map(item => item.product_count);

        new Chart(document.getElementById('productsByCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: productCounts,
                    backgroundColor: [
                        '#3b7ddd',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#e83e8c'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${value} products (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
