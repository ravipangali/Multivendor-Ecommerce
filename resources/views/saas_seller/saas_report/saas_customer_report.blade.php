@extends('saas_seller.saas_layouts.saas_layout')

@section('styles')
<style>
    .customer-analytics-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .customer-analytics-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .analytics-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #f8f9fa;
    }

    .analytics-icon {
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

    .analytics-icon svg {
        width: 16px;
        height: 16px;
    }

    .analytics-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.2;
    }

    .analytics-subtitle {
        color: #6c757d;
        font-size: 0.75rem;
        margin: 0;
        line-height: 1.2;
    }

        .customer-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .customer-kpi-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .customer-kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .customer-kpi-card::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -60%;
        width: 120%;
        height: 120%;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .customer-kpi-card.premium {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .customer-kpi-card.growth {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .customer-kpi-card.engagement {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .customer-kpi-card.retention {
        background: linear-gradient(135deg, #ff9a56 0%, #ffad56 100%);
    }

        .kpi-value-large {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 8px 0 6px;
        position: relative;
        z-index: 2;
        line-height: 1.2;
    }

    .kpi-label-large {
        font-size: 0.85rem;
        opacity: 0.95;
        position: relative;
        z-index: 2;
        font-weight: 500;
        line-height: 1.2;
    }

    .kpi-change {
        font-size: 0.75rem;
        margin-top: 6px;
        position: relative;
        z-index: 2;
    }

    .customer-table-premium {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .customer-table-premium thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .customer-table-premium thead th {
        border: none;
        padding: 22px 18px;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .customer-table-premium tbody td {
        padding: 20px 18px;
        border: none;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
    }

    .customer-table-premium tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .customer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 18px;
        margin-right: 15px;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .customer-info-enhanced {
        display: flex;
        align-items: center;
    }

    .customer-details-enhanced h6 {
        margin: 0 0 5px;
        font-weight: 700;
        color: #2c3e50;
        font-size: 1rem;
    }

    .customer-details-enhanced small {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .loyalty-badge {
        padding: 10px 18px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: 2px solid;
    }

    .loyalty-vip {
        background: linear-gradient(135deg, #f6c23e 0%, #f39c12 100%);
        color: white;
        border-color: #f39c12;
    }

    .loyalty-loyal {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-color: #138496;
    }

    .loyalty-returning {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-color: #20c997;
    }

    .loyalty-new {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        border-color: #495057;
    }

    .filter-section-enhanced {
        background: white;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .segment-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .segment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .segment-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 16px;
        color: white;
        flex-shrink: 0;
    }

    .segment-icon svg {
        width: 16px;
        height: 16px;
    }

    .segment-value {
        font-size: 1.4rem;
        font-weight: bold;
        margin: 8px 0 6px;
        line-height: 1.2;
    }

    .segment-label {
        color: #6c757d;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .insights-panel {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 25px;
    }

    .insight-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 0;
        border-bottom: 1px solid #dee2e6;
    }

    .insight-item:last-child {
        border-bottom: none;
    }

    .insight-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }

    .insight-icon svg {
        width: 14px;
        height: 14px;
    }

    .chart-container-customer {
        position: relative;
        height: 350px;
        padding: 25px;
        background: #fafbfc;
        border-radius: 15px;
        margin: 25px 0;
    }

    /* Action button styling */
    .btn-sm {
        padding: 6px 10px;
        font-size: 0.8rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        border-width: 1px;
        transition: all 0.2s ease;
    }

    .btn-sm svg {
        width: 14px !important;
        height: 14px !important;
        stroke-width: 2;
    }

    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-outline-primary:hover svg {
        color: white !important;
        stroke: white;
    }

    .btn-outline-success {
        border-color: #198754;
        color: #198754;
    }

    .btn-outline-success:hover {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }

    .btn-outline-success:hover svg {
        color: white !important;
        stroke: white;
    }

    /* Table action column */
    .customer-table-premium tbody td:last-child {
        width: 120px;
    }

    .d-flex.gap-2 {
        gap: 8px !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">👥 Customer Analytics Dashboard</h1>
            <p class="text-muted">Deep insights into customer behavior and preferences</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="refreshCustomerData()">
                <i class="align-middle" data-feather="refresh-cw"></i> Refresh Data
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section-enhanced">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label fw-bold">From Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                       value="{{ request('start_date', date('Y-m-d', strtotime($startDate))) }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label fw-bold">To Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                       value="{{ request('end_date', date('Y-m-d', strtotime($endDate))) }}">
            </div>
            <div class="col-md-3">
                <label for="segment" class="form-label fw-bold">Customer Segment</label>
                <select class="form-select" id="segment" name="segment">
                    <option value="">All Customers</option>
                    <option value="new" {{ request('segment') == 'new' ? 'selected' : '' }}>New Customers</option>
                    <option value="returning" {{ request('segment') == 'returning' ? 'selected' : '' }}>Returning Customers</option>
                    <option value="vip" {{ request('segment') == 'vip' ? 'selected' : '' }}>VIP Customers</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="align-middle" data-feather="filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Key Metrics Overview -->
    <div class="customer-kpi-grid">
        <div class="customer-kpi-card">
            <div class="kpi-value-large">{{ $totalCustomers }}</div>
            <div class="kpi-label-large">Total Customers</div>
            <div class="kpi-change">
                <i data-feather="trending-up" style="width: 16px; height: 16px;"></i>
                +{{ $newCustomersThisMonth }} new this month
            </div>
        </div>

        <div class="customer-kpi-card premium">
            <div class="kpi-value-large">{{ $repeatCustomers->count() }}</div>
            <div class="kpi-label-large">Repeat Customers</div>
            <div class="kpi-change">
                <i data-feather="repeat" style="width: 16px; height: 16px;"></i>
                {{ number_format($customerRetentionRate, 1) }}% retention rate
            </div>
        </div>

        <div class="customer-kpi-card growth">
            <div class="kpi-value-large">Rs {{ number_format($avgCustomerValue, 0) }}</div>
            <div class="kpi-label-large">Avg Customer Value</div>
            <div class="kpi-change">
                                        <span class="rs-icon rs-icon-sm">Rs</span>
                Lifetime value estimate
            </div>
        </div>

        <div class="customer-kpi-card engagement">
            <div class="kpi-value-large">{{ number_format($avgOrdersPerCustomer, 1) }}</div>
            <div class="kpi-label-large">Avg Orders per Customer</div>
            <div class="kpi-change">
                <i data-feather="shopping-bag" style="width: 16px; height: 16px;"></i>
                Purchase frequency
            </div>
        </div>

        <div class="customer-kpi-card retention">
            <div class="kpi-value-large">{{ number_format($daysSinceLastOrder, 1) }}</div>
            <div class="kpi-label-large">Days Since Last Order</div>
            <div class="kpi-change">
                <i data-feather="clock" style="width: 16px; height: 16px;"></i>
                Average recency
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Customers by Value -->
        <div class="col-xl-12">
            <div class="customer-analytics-card">
                <div class="analytics-header">
                    <div class="analytics-icon bg-success">
                        <i data-feather="crown"></i>
                    </div>
                    <div>
                        <h5 class="analytics-title">🏆 Top Value Customers</h5>
                        <p class="analytics-subtitle">Customers ranked by total purchase value and engagement</p>
                    </div>
                </div>

                @if($topCustomers->count() > 0)
                    <div class="table-responsive">
                        <table class="table customer-table-premium">
                            <thead>
                                <tr>
                                    <th style="font-size: .75rem;" class="text-white">Customer</th>
                                    <th style="font-size: .75rem;" class="text-white">Total Orders</th>
                                    <th style="font-size: .75rem;" class="text-white">Total Spent</th>
                                    <th style="font-size: .75rem;" class="text-white">Avg Order Value</th>
                                    <th style="font-size: .75rem;" class="text-white">Last Order</th>
                                    <th style="font-size: .75rem;" class="text-white">Loyalty Status</th>
                                    <th style="font-size: .75rem;" class="text-white">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $customerData)
                                <tr>
                                    <td>
                                        <div class="customer-info-enhanced">
                                            <div class="customer-avatar bg-primary">
                                                {{ strtoupper(substr($customerData->customer->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div class="customer-details-enhanced">
                                                <h6>{{ $customerData->customer->name ?? 'Unknown Customer' }}</h6>
                                                <small>{{ $customerData->customer->email ?? 'N/A' }}</small>
                                                <br>
                                                <small class="text-success">Customer since {{ $customerData->customer->created_at->format('M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $customerData->order_count }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">Rs {{ number_format($customerData->total_spent, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">Rs {{ number_format($customerData->total_spent / $customerData->order_count, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $customerData->last_order_date ? \Carbon\Carbon::parse($customerData->last_order_date)->diffForHumans() : 'Never' }}</span>
                                    </td>
                                    <td>
                                        @if($customerData->order_count >= 10)
                                            <span class="loyalty-badge loyalty-vip">VIP</span>
                                        @elseif($customerData->order_count >= 5)
                                            <span class="loyalty-badge loyalty-loyal">Loyal</span>
                                        @elseif($customerData->order_count > 1)
                                            <span class="loyalty-badge loyalty-returning">Returning</span>
                                        @else
                                            <span class="loyalty-badge loyalty-new">New</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewCustomerProfile('{{ $customerData->customer->id }}')">
                                                <i data-feather="user"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="sendPromotion('{{ $customerData->customer->id }}')">
                                                <i data-feather="mail"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i data-feather="users" class="text-muted" style="width: 64px; height: 64px;"></i>
                        <p class="text-muted mt-3">No customer data available for this period.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Segmentation & Charts -->
        <div class="col-xl-12 row gap-2">
            <!-- Customer Segmentation -->
            <div class="customer-analytics-card col-md-5">
                <div class="analytics-header">
                    <div class="analytics-icon bg-info">
                        <i data-feather="pie-chart"></i>
                    </div>
                    <div>
                        <h5 class="analytics-title">Customer Segments</h5>
                        <p class="analytics-subtitle">Distribution by loyalty level</p>
                    </div>
                </div>

                <div class="chart-container-customer">
                    <canvas id="customerSegmentChart"></canvas>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="segment-card">
                            <div class="segment-icon bg-warning">
                                <i data-feather="star"></i>
                            </div>
                            <div class="segment-value text-warning">{{ $vipCustomers }}</div>
                            <div class="segment-label">VIP Customers</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="segment-card">
                            <div class="segment-icon bg-info">
                                <i data-feather="heart"></i>
                            </div>
                            <div class="segment-value text-info">{{ $loyalCustomers }}</div>
                            <div class="segment-label">Loyal Customers</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geographic Distribution -->
            <div class="customer-analytics-card col-md-5">
                <div class="analytics-header">
                    <div class="analytics-icon bg-success">
                        <i data-feather="map-pin"></i>
                    </div>
                    <div>
                        <h5 class="analytics-title">Geographic Reach</h5>
                        <p class="analytics-subtitle">Customer distribution by location</p>
                    </div>
                </div>

                @if($topLocations->count() > 0)
                    @foreach($topLocations->take(5) as $location)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="customer-avatar bg-success me-3">
                                {{ strtoupper(substr($location->city ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $location->city ?? 'Unknown' }}</h6>
                                <small class="text-muted">{{ $location->state ?? 'N/A' }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $location->customer_count }}</div>
                            <small class="text-muted">customers</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i data-feather="map" class="text-muted" style="width: 48px; height: 48px;"></i>
                        <p class="text-muted mt-2">No location data available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Customer Behavior Insights -->
    <div class="row">
        <div class="col-12">
            <div class="insights-panel">
                <h5 class="fw-bold mb-4">🧠 Customer Behavior Insights & Recommendations</h5>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary fw-bold mb-3">📊 Key Behavioral Patterns</h6>

                        <div class="insight-item">
                            <div class="insight-icon bg-success">
                                <i data-feather="trending-up" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Peak Purchase Times</div>
                                <small class="text-muted">Most orders placed on {{ $peakDay ?? 'Weekends' }} at {{ $peakHour ?? '7-9 PM' }}</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-info">
                                <i data-feather="repeat" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Purchase Frequency</div>
                                <small class="text-muted">Average {{ $avgDaysBetweenOrders ?? '30' }} days between repeat purchases</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-warning">
                                <i data-feather="heart" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Customer Satisfaction</div>
                                <small class="text-muted">{{ $avgCustomerRating ?? '4.2' }}/5 average rating across all orders</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-primary">
                                <i data-feather="shopping-cart" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Cart Abandonment</div>
                                <small class="text-muted">{{ $cartAbandonmentRate ?? '25' }}% cart abandonment rate (industry avg: 70%)</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-success fw-bold mb-3">🎯 Growth Opportunities</h6>

                        <div class="insight-item">
                            <div class="insight-icon bg-success">
                                <i data-feather="target" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Upselling Potential</div>
                                <small class="text-muted">{{ $upsellOpportunities ?? '45' }}% of customers could benefit from premium products</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-info">
                                <i data-feather="mail" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Win-Back Campaign</div>
                                <small class="text-muted">{{ $dormantCustomers ?? '67' }} customers haven't ordered in 90+ days</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-warning">
                                <i data-feather="users" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Referral Program</div>
                                <small class="text-muted">Top {{ $referralCandidates ?? '20' }}% customers are ideal referral candidates</small>
                            </div>
                        </div>

                        <div class="insight-item">
                            <div class="insight-icon bg-danger">
                                <i data-feather="gift" style="width: 18px; height: 18px;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Loyalty Program</div>
                                <small class="text-muted">Implement tiered rewards to increase retention by {{ $loyaltyImpact ?? '25' }}%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Items -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-danger fw-bold mb-3">⚡ Immediate Action Items</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                        Create targeted email campaign for dormant customers
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                        Implement VIP customer exclusive offers
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-success me-2" style="width: 16px; height: 16px;"></i>
                                        Launch referral incentive program
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                                        Optimize checkout process to reduce cart abandonment
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                                        Personalize product recommendations
                                    </li>
                                    <li class="mb-2">
                                        <i data-feather="check-circle" class="text-warning me-2" style="width: 16px; height: 16px;"></i>
                                        Set up automated follow-up sequences
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Journey Timeline -->
    <div class="row">
        <div class="col-12">
            <div class="customer-analytics-card">
                <div class="analytics-header">
                    <div class="analytics-icon bg-primary">
                        <i data-feather="trending-up"></i>
                    </div>
                    <div>
                        <h5 class="analytics-title">📈 Customer Acquisition Trend</h5>
                        <p class="analytics-subtitle">New customer acquisition over time</p>
                    </div>
                </div>

                <div class="chart-container-customer">
                    <canvas id="customerAcquisitionChart"></canvas>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3 text-center">
                        <div class="fw-bold text-primary fs-4">{{ $newCustomersToday }}</div>
                        <small class="text-muted">New Today</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="fw-bold text-success fs-4">{{ $newCustomersThisWeek }}</div>
                        <small class="text-muted">New This Week</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="fw-bold text-info fs-4">{{ $newCustomersThisMonth }}</div>
                        <small class="text-muted">New This Month</small>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="fw-bold text-warning fs-4">{{ number_format($customerGrowthRate, 1) }}%</div>
                        <small class="text-muted">Growth Rate</small>
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
    // Customer Segment Chart
    const segmentCtx = document.getElementById('customerSegmentChart').getContext('2d');
    new Chart(segmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['VIP Customers', 'Loyal Customers', 'Returning Customers', 'New Customers'],
            datasets: [{
                data: [
                    {{ $vipCustomers ?? 0 }},
                    {{ $loyalCustomers ?? 0 }},
                    {{ $returningCustomers ?? 0 }},
                    {{ $newCustomers ?? 0 }}
                ],
                backgroundColor: [
                    '#f6c23e',
                    '#17a2b8',
                    '#28a745',
                    '#6c757d'
                ],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverBorderWidth: 6,
                hoverOffset: 12
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
                duration: 2000
            }
        }
    });

    // Customer Acquisition Chart
    const acquisitionCtx = document.getElementById('customerAcquisitionChart').getContext('2d');
    new Chart(acquisitionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($customerAcquisitionLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'New Customers',
                data: {!! json_encode($customerAcquisitionData ?? [10, 15, 22, 18, 30, 25]) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    display: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });
});



function refreshCustomerData() {
    location.reload();
}

function viewCustomerProfile(customerId) {
    alert('Customer profile view for ID: ' + customerId);
}

function sendPromotion(customerId) {
    alert('Send promotion to customer ID: ' + customerId);
}

// Initialize feather icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
        console.log('Feather icons initialized');
    } else {
        console.warn('Feather icons library not loaded');
    }
});

// Re-initialize feather icons after any dynamic content changes
setTimeout(function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
        console.log('Feather icons re-initialized');
    }
}, 500);

// Function to reinitialize icons after AJAX calls or dynamic content
window.reinitializeFeatherIcons = function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
};
</script>
@endsection
