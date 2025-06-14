@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Customer Report')

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
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .customer-info {
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card date-filter mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.customers') }}" class="row g-3 align-items-center">
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
                        <h5 class="card-title mb-0">Total Customers</h5>
                    </div>
                    <h3 class="mt-1 mb-3">{{ $newCustomers->count() }}</h3>
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
                        <h5 class="card-title mb-0">Avg. Customer Spend</h5>
                    </div>
                    <h3 class="mt-1 mb-3">
                        ${{ $topCustomers->count() > 0 ? number_format($topCustomers->sum('total_spent') / $topCustomers->count(), 2) : '0.00' }}
                    </h3>
                    <div class="mb-0">
                        <span class="text-muted">Based on {{ $topCustomers->count() }} customers</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card report-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="report-icon bg-info-subtle me-3">
                            <i class="align-middle text-info" data-feather="shopping-cart"></i>
                        </div>
                        <h5 class="card-title mb-0">Avg. Orders per Customer</h5>
                    </div>
                    <h3 class="mt-1 mb-3">
                        {{ $topCustomers->count() > 0 ? number_format($topCustomers->sum('order_count') / $topCustomers->count(), 1) : '0.0' }}
                    </h3>
                    <div class="mb-0">
                        <span class="text-muted">Based on {{ $topCustomers->count() }} customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Growth</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="customerGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Customers</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse ($newCustomers->take(5) as $customer)
                        <div class="list-group-item border-start-0 border-end-0">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}" class="customer-avatar" alt="Customer">
                                <div class="customer-info">
                                    <h6 class="mb-0">{{ $customer->name }}</h6>
                                    <small class="text-muted">Joined {{ $customer->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item border-start-0 border-end-0">
                            <div class="text-center py-3">
                                <p class="mb-0 text-muted">No new customers found</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Top Spending Customers</h5>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="topCustomersExport" data-bs-toggle="dropdown" aria-expanded="false">
                    Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="topCustomersExport">
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
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Avg. Order Value</th>
                            <th>Joined Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topCustomers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('saas_admin/img/avatars/avatar.jpg') }}" class="customer-avatar" alt="Customer">
                                    <div class="customer-info">
                                        <span class="fw-bold">{{ $customer->customer ? $customer->customer->name : 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->customer ? $customer->customer->email : 'N/A' }}</td>
                            <td>{{ $customer->order_count }}</td>
                            <td>${{ number_format($customer->total_spent, 2) }}</td>
                            <td>${{ $customer->order_count > 0 ? number_format($customer->total_spent / $customer->order_count, 2) : '0.00' }}</td>
                            <td>{{ $customer->customer ? $customer->customer->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($customer->customer)
                                <a href="{{ route('admin.customers.show', $customer->customer->id) }}" class="btn btn-sm btn-primary">
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
                            <td colspan="7" class="text-center">No top customers found.</td>
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

        // Customer growth chart
        const customersData = @json($customersByDate);
        const dates = customersData.map(item => item.date);
        const counts = customersData.map(item => item.count);

        // Calculate cumulative count
        let cumulativeCounts = [];
        let runningTotal = 0;

        counts.forEach(count => {
            runningTotal += count;
            cumulativeCounts.push(runningTotal);
        });

        new Chart(document.getElementById('customerGrowthChart'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'New Customers',
                        data: counts,
                        fill: false,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        tension: 0.1
                    },
                    {
                        label: 'Cumulative Growth',
                        data: cumulativeCounts,
                        fill: true,
                        borderColor: '#3b7ddd',
                        backgroundColor: 'rgba(59, 125, 221, 0.1)',
                        borderWidth: 2,
                        yAxisID: 'y',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Total Customers'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'New Customers'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
