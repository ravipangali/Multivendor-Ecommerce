@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'In-House Sales Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">In-House Sales Reports & Analytics</h5>
                        <a href="{{ route('admin.in-house-sales.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.in-house-sales.reports') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', $startDate) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', $endDate) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="report_type" class="form-label">Report Type</label>
                                    <select class="form-select" id="report_type" name="report_type">
                                        <option value="daily" {{ request('report_type', 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ request('report_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i data-feather="search"></i> Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(isset($analytics) && $analytics['total_sales'] > 0)
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="text-white card-title">Total Sales</h6>
                                                <h3 class="text-white mb-0">{{ number_format($analytics['total_sales']) }}</h3>
                                            </div>
                                            <div class="align-self-center">
                                                <i data-feather="shopping-cart" class="icon-lg"></i>
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
                                                <h6 class="text-white card-title">Total Revenue</h6>
                                                <h3 class="text-white mb-0">Rs {{ number_format($analytics['total_revenue'], 2) }}</h3>
                                            </div>
                                            <div class="align-self-center">
                                                <i data-feather="dollar-sign" class="icon-lg"></i>
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
                                                <h6 class="text-white card-title">Avg. Sale Value</h6>
                                                <h3 class="text-white mb-0">Rs {{ number_format($analytics['avg_sale_value'], 2) }}</h3>
                                            </div>
                                            <div class="align-self-center">
                                                <i data-feather="trending-up" class="icon-lg"></i>
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
                                                <h6 class="text-white card-title">Items Sold</h6>
                                                <h3 class="text-white mb-0">{{ number_format($analytics['total_items_sold']) }}</h3>
                                            </div>
                                            <div class="align-self-center">
                                                <i data-feather="package" class="icon-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Sales Trend</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salesTrendChart" height="80"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Payment Methods</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="paymentMethodChart" height="160"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Top Selling Products</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($analytics['top_products']->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Quantity Sold</th>
                                                            <th>Revenue</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($analytics['top_products'] as $product)
                                                            <tr>
                                                                <td>{{ $product->product_name }}</td>
                                                                <td>{{ number_format($product->total_quantity) }}</td>
                                                                <td>Rs {{ number_format($product->total_revenue, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No products sold in this period.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Sales by Cashier</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($analytics['sales_by_cashier']->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Cashier</th>
                                                            <th>Sales Count</th>
                                                            <th>Revenue</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($analytics['sales_by_cashier'] as $cashier)
                                                            <tr>
                                                                <td>{{ $cashier->cashier_name }}</td>
                                                                <td>{{ number_format($cashier->sales_count) }}</td>
                                                                <td>Rs {{ number_format($cashier->total_revenue, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No cashier data available.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Customers Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Top Customers</h6>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($analytics['top_customers']) && $analytics['top_customers']->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Sales Count</th>
                                                            <th>Revenue</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($analytics['top_customers'] as $customer)
                                                            <tr>
                                                                <td>{{ $customer->customer_name }}</td>
                                                                <td>{{ number_format($customer->sales_count) }}</td>
                                                                <td>Rs {{ number_format($customer->total_revenue, 2) }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.customers.show', $customer->customer_id) }}" class="btn btn-sm btn-outline-info">
                                                                        <i data-feather="user"></i> View
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                                <small class="text-muted">*Only registered customers are included in this report</small>
                                        @else
                                            <p class="text-muted">No registered customer data available for this period.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Sales Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-title mb-0">Daily Sales Breakdown</h6>
                                            <div>
                                                <button class="btn btn-sm btn-outline-primary" onclick="exportToCSV()">
                                                    <i data-feather="download"></i> Export CSV
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($analytics['daily_sales']->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="dailySalesTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Sales Count</th>
                                                            <th>Items Sold</th>
                                                            <th>Revenue</th>
                                                            <th>Avg. Sale Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($analytics['daily_sales'] as $daily)
                                                            <tr>
                                                                <td>{{ date('M d, Y', strtotime($daily->date)) }}</td>
                                                                <td>{{ number_format($daily->sales_count) }}</td>
                                                                <td>{{ number_format($daily->total_items) }}</td>
                                                                <td>Rs {{ number_format($daily->total_revenue, 2) }}</td>
                                                                <td>Rs {{ number_format($daily->avg_sale_value, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">No daily sales data available for the selected period.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Data Message -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <i data-feather="bar-chart-2" class="icon-lg text-muted mb-3"></i>
                                        <h4 class="text-muted">No Sales Data Found</h4>
                                        <p class="text-muted mb-4">
                                            No in-house sales found for the selected date range
                                            ({{ date('M d, Y', strtotime($startDate)) }} - {{ date('M d, Y', strtotime($endDate)) }}).
                                        </p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                                                <i data-feather="plus"></i> Create New Sale
                                            </a>
                                            <a href="{{ route('admin.in-house-sales.index') }}" class="btn btn-outline-secondary">
                                                <i data-feather="list"></i> View All Sales
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Only initialize charts if analytics data exists
    @if(isset($analytics) && $analytics['total_sales'] > 0)
        // Sales Trend Chart
        const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
        const salesTrendChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($analytics['daily_sales']->pluck('date')) !!},
                datasets: [{
                    label: 'Revenue (Rs)',
                    data: {!! json_encode($analytics['daily_sales']->pluck('total_revenue')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1,
                    fill: true
                }, {
                    label: 'Sales Count',
                    data: {!! json_encode($analytics['daily_sales']->pluck('sales_count')) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.1,
                    yAxisID: 'y1',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Sales Trend Over Time ({{ date("M d, Y", strtotime($startDate)) }} - {{ date("M d, Y", strtotime($endDate)) }})'
                    },
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
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
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Sales Count'
                        },
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
        const paymentMethodChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($analytics['payment_methods']->pluck('payment_method')) !!},
                datasets: [{
                    data: {!! json_encode($analytics['payment_methods']->pluck('count')) !!},
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Payment Methods Distribution'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} sales (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    @endif

    // Export to CSV function
    function exportToCSV() {
        const table = document.getElementById('dailySalesTable');
        if (!table) {
            Swal.fire({
                title: 'No Data',
                text: 'No data available to export',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const rows = table.querySelectorAll('tr');
        let csv = [];

        // Add header with metadata
        csv.push(['In-House Sales Report']);
        csv.push(['Date Range:', '{{ date("M d, Y", strtotime($startDate)) }} - {{ date("M d, Y", strtotime($endDate)) }}']);
        csv.push(['Generated on:', '{{ date("M d, Y H:i:s") }}']);
        csv.push(['']); // Empty row

        for (let i = 0; i < rows.length; i++) {
            const row = [];
            const cols = rows[i].querySelectorAll('td, th');

            for (let j = 0; j < cols.length; j++) {
                row.push(cols[j].innerText.replace(/,/g, '')); // Remove commas to avoid CSV issues
            }
            csv.push(row.join(','));
        }

        const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        const downloadLink = document.createElement('a');
        downloadLink.download = `in_house_sales_report_{{ date('Y-m-d', strtotime($startDate)) }}_to_{{ date('Y-m-d', strtotime($endDate)) }}.csv`;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    // Initialize date picker with reasonable defaults
    document.addEventListener('DOMContentLoaded', function() {
        // Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Auto-submit form when dates change (optional)
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput && endDateInput) {
            [startDateInput, endDateInput].forEach(input => {
                input.addEventListener('change', function() {
                    // Optional: Auto-submit after both dates are selected
                    // if (startDateInput.value && endDateInput.value) {
                    //     this.form.submit();
                    // }
                });
            });
        }
    });
</script>
@endsection
