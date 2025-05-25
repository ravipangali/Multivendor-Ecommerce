@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Sales Report')

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
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card date-filter mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3 align-items-center">
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
                            <i class="align-middle text-primary" data-feather="dollar-sign"></i>
                        </div>
                        <h5 class="card-title mb-0">Total Sales</h5>
                    </div>
                    <h3 class="mt-1 mb-3">${{ number_format($totalSales, 2) }}</h3>
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
                            <i class="align-middle text-success" data-feather="shopping-cart"></i>
                        </div>
                        <h5 class="card-title mb-0">Total Orders</h5>
                    </div>
                    <h3 class="mt-1 mb-3">{{ $recentOrders->count() }}</h3>
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
                        <div class="report-icon bg-warning-subtle me-3">
                            <i class="align-middle text-warning" data-feather="trending-up"></i>
                        </div>
                        <h5 class="card-title mb-0">Avg. Order Value</h5>
                    </div>
                    <h3 class="mt-1 mb-3">
                        ${{ $recentOrders->count() > 0 ? number_format($totalSales / $recentOrders->count(), 2) : '0.00' }}
                    </h3>
                    <div class="mb-0">
                        <span class="text-muted">From {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Trend</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Orders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Seller</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold">#{{ $order->id }}</a></td>
                            <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                            <td>{{ $order->seller ? $order->seller->name : 'N/A' }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($order->payment_method) }}</span>
                            </td>
                            <td>
                                @if($order->order_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->order_status == 'processing')
                                    <span class="badge bg-primary">Processing</span>
                                @elseif($order->order_status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->order_status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->order_status) }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No orders found.</td>
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

        // Sales by date chart
        const salesData = @json($salesByDate);
        const dates = salesData.map(item => item.date);
        const totals = salesData.map(item => item.total);

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Sales Amount',
                    data: totals,
                    fill: false,
                    borderColor: '#3b7ddd',
                    tension: 0.2,
                    backgroundColor: 'rgba(59, 125, 221, 0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#3b7ddd'
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
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.raw;
                            }
                        }
                    }
                }
            }
        });

        // Payment method chart
        const paymentData = @json($salesByPaymentMethod);
        const methods = paymentData.map(item => item.payment_method.toUpperCase());
        const amounts = paymentData.map(item => item.total);
        const counts = paymentData.map(item => item.count);

        new Chart(document.getElementById('paymentMethodChart'), {
            type: 'doughnut',
            data: {
                labels: methods,
                datasets: [{
                    data: amounts,
                    backgroundColor: [
                        '#3b7ddd',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#fd7e14'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const count = counts[context.dataIndex];
                                return `$${value} (${count} orders)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection