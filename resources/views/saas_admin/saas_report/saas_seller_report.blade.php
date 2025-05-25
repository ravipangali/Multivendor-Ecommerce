@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Seller Report')

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
        height: 8px;
        border-radius: 5px;
    }
    .seller-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .seller-info {
        margin-left: 10px;
    }
    .rating-stars {
        color: #ffc107;
    }
    .top-seller-card {
        transition: all 0.3s ease;
    }
    .top-seller-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .seller-rank {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #3b7ddd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card date-filter mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.sellers') }}" class="row g-3 align-items-center">
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

    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card report-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="report-icon bg-primary-subtle me-3">
                            <i class="align-middle text-primary" data-feather="users"></i>
                        </div>
                        <h5 class="card-title mb-0">Total Sellers</h5>
                    </div>
                    <h3 class="mt-1 mb-3">{{ $newSellers->count() }}</h3>
                    <div class="mb-0">
                        <span class="text-muted">From {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card report-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="report-icon bg-success-subtle me-3">
                            <i class="align-middle text-success" data-feather="dollar-sign"></i>
                        </div>
                        <h5 class="card-title mb-0">Total Sales by Sellers</h5>
                    </div>
                    <h3 class="mt-1 mb-3">
                        ${{ number_format($topSellers->sum('total_sales'), 2) }}
                    </h3>
                    <div class="mb-0">
                        <span class="text-muted">From {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card report-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="report-icon bg-info-subtle me-3">
                            <i class="align-middle text-info" data-feather="box"></i>
                        </div>
                        <h5 class="card-title mb-0">Avg. Products per Seller</h5>
                    </div>
                    <h3 class="mt-1 mb-3">
                        {{ $topSellersByProducts->count() > 0 ? number_format($topSellersByProducts->sum('products_count') / $topSellersByProducts->count(), 1) : '0.0' }}
                    </h3>
                    <div class="mb-0">
                        <span class="text-muted">Based on {{ $topSellersByProducts->count() }} sellers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 3 Sellers</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($topSellers->take(3) as $index => $seller)
                            <div class="col-md-4">
                                <div class="card border top-seller-card position-relative">
                                    <span class="seller-rank">{{ $index + 1 }}</span>
                                    <div class="card-body text-center">
                                        <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}" class="mb-3" alt="Seller" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                                        <h5 class="card-title mb-1">{{ $seller->seller ? $seller->seller->name : 'N/A' }}</h5>
                                        <div class="rating-stars mb-2">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="align-middle" data-feather="star"></i>
                                            @endfor
                                        </div>
                                        <p class="card-text mb-1">Total Sales: <strong>${{ number_format($seller->total_sales, 2) }}</strong></p>
                                        <p class="card-text mb-3">Orders: <strong>{{ $seller->order_count }}</strong></p>
                                        <a href="{{ $seller->seller ? route('admin.sellers.show', $seller->seller->id) : '#' }}" class="btn btn-sm btn-primary">View Profile</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">No top sellers found for the selected period.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Selling Sellers</h5>
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
                                    <th>Seller</th>
                                    <th>Orders</th>
                                    <th>Total Sales</th>
                                    <th>Avg. Order Value</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topSellers as $seller)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}" class="seller-avatar" alt="Seller">
                                            <div class="seller-info">
                                                <span class="fw-bold">{{ $seller->seller ? $seller->seller->name : 'N/A' }}</span>
                                                <br>
                                                <small class="text-muted">{{ $seller->seller ? $seller->seller->email : 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $seller->order_count }}</td>
                                    <td>${{ number_format($seller->total_sales, 2) }}</td>
                                    <td>${{ $seller->order_count > 0 ? number_format($seller->total_sales / $seller->order_count, 2) : '0.00' }}</td>
                                    <td>
                                        @if($seller->seller && $seller->seller->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($seller->seller)
                                        <a href="{{ route('admin.sellers.show', $seller->seller->id) }}" class="btn btn-sm btn-primary">
                                            <i data-feather="eye" class="feather-sm"></i>
                                        </a>
                                        @else
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i data-feather="eye-off" class="feather-sm"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No sellers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Sellers by Product Count</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topSellersByProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">New Sellers</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Seller</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Store Name</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newSellers as $seller)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}" class="seller-avatar" alt="Seller">
                                    <div class="seller-info">
                                        <span class="fw-bold">{{ $seller->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $seller->email }}</td>
                            <td>{{ $seller->phone ?? 'N/A' }}</td>
                            <td>{{ $seller->store_name ?? 'N/A' }}</td>
                            <td>{{ $seller->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($seller->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn btn-sm btn-primary">
                                    <i data-feather="eye" class="feather-sm"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No new sellers found.</td>
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

        // Top sellers by products chart
        const sellerProductData = @json($topSellersByProducts);
        const sellerNames = sellerProductData.map(seller => seller.name);
        const productCounts = sellerProductData.map(seller => seller.products_count);

        // Truncate seller names for better display
        const truncatedNames = sellerNames.map(name => {
            return name.length > 15 ? name.substring(0, 15) + '...' : name;
        });

        new Chart(document.getElementById('topSellersByProductsChart'), {
            type: 'bar',
            data: {
                labels: truncatedNames,
                datasets: [{
                    label: 'Number of Products',
                    data: productCounts,
                    backgroundColor: [
                        'rgba(59, 125, 221, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)',
                        'rgba(253, 126, 20, 0.8)',
                        'rgba(32, 201, 151, 0.8)',
                        'rgba(232, 62, 140, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 125, 221)',
                        'rgb(40, 167, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)',
                        'rgb(111, 66, 193)',
                        'rgb(253, 126, 20)',
                        'rgb(32, 201, 151)',
                        'rgb(232, 62, 140)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Products'
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                // Return the full seller name in tooltip
                                return sellerNames[context[0].dataIndex];
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
