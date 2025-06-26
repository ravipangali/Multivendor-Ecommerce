@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Refund Requests</li>
            </ol>
        </nav>
                </div>
</section>

<!-- Refunds Dashboard -->
<section class="customer-dashboard">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="dashboard-content">
                    <!-- Header -->
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title-section">
                                <h2 class="page-title">
                                    <i class="fas fa-undo-alt me-3"></i>Refund Requests
                                </h2>
                                <p class="page-subtitle">Manage your refund requests and track their status</p>
        </div>
                            <div class="page-actions my-3">
                                <a href="{{ route('customer.refunds.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Request New Refund
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Overview -->
                    <div class="stats-overview mb-4">
                        <div class="row g-4">
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card total-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $totalRefunds }}</div>
                                        <div class="stat-label">Total Requests</div>
                </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-file-alt"></i>
            </div>
        </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card pending-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $pendingRefunds }}</div>
                                        <div class="stat-label">Pending</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card approved-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $approvedRefunds }}</div>
                                        <div class="stat-label">Approved</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-check-circle"></i>
            </div>
        </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card amount-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">Rs {{ number_format($totalRefundAmount, 2) }}</div>
                                        <div class="stat-label">Total Amount</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                    <!-- Refunds Table -->
                    <div class="refunds-section">
                        <div class="section-header">
                            <div class="section-title-area">
                                <h4 class="section-title">Refund History</h4>
                            </div>
                            <div class="section-filters">
                    <div class="row g-2">
                                    <div class="col-md-8">
                                        <div class="search-box">
                                            <i class="fas fa-search search-icon"></i>
                                            <input type="text" class="form-control search-input" placeholder="Search by Order ID or Amount..." id="refundSearch">
                            </div>
                        </div>
                                    <div class="col-md-4">
                                        <select class="form-select status-filter" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="processed">Processed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
                        <div class="refunds-table-container">
            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                                <table class="table refunds-table" id="refundsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Request ID</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Order Details</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Refund Amount</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Reason</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Status</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4">Request Date</th>
                            <th class="fw-bold text-dark border-0 py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                        <tr class="refund-row" data-status="{{ $refund->status }}" data-search-term="{{ strtolower($refund->order->order_number . ' ' . $refund->refund_amount) }}">
                            <td class="px-4 py-4">
                                    <span class="fw-bold text-primary">#{{ $refund->id }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <span class="fw-bold text-dark d-block mb-1">{{ $refund->order->order_number }}</span>
                                    <span class="text-muted small">Order Total: Rs {{ number_format($refund->order_amount, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                    <span class="fw-bold text-danger fs-6">Rs {{ number_format($refund->refund_amount, 2) }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-truncate" style="max-width: 180px;" title="{{ $refund->customer_reason }}">
                                    <span class="text-muted">{{ Str::limit($refund->customer_reason, 40) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['class' => 'bg-warning text-dark', 'icon' => 'clock', 'text' => 'Under Review'],
                                        'approved' => ['class' => 'bg-success', 'icon' => 'check', 'text' => 'Approved'],
                                        'processed' => ['class' => 'bg-primary', 'icon' => 'credit-card', 'text' => 'Refunded'],
                                        'rejected' => ['class' => 'bg-danger', 'icon' => 'x', 'text' => 'Rejected'],
                                    ];
                                    $config = $statusConfig[$refund->status] ?? ['class' => 'bg-secondary', 'icon' => 'info', 'text' => ucfirst($refund->status)];
                                @endphp
                                <span class="badge {{ $config['class'] }} bg-opacity-10 text-{{ explode(' ', $config['class'])[0] }} shadow-sm rounded-pill px-3 py-2 fw-semibold">
                                    <i class="align-middle me-1" data-feather="{{ $config['icon'] }}" style="width: 14px; height: 14px;"></i> {{ $config['text'] }}
                                        </span>
                            </td>
                            <td class="px-4 py-4">
                                        <span class="fw-bold text-dark d-block">{{ $refund->created_at->format('d M Y') }}</span>
                                        <span class="text-muted small">{{ $refund->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="text-center px-4 py-4">
                                <a href="{{ route('customer.refunds.show', $refund) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-semibold">
                                    <i class="align-middle me-1" data-feather="eye" style="width: 14px; height: 14px;"></i> Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 border-0">
                                <div class="empty-refunds">
                                    <div class="empty-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h5 class="empty-title">No Refund Requests Found</h5>
                                    <p class="empty-text">You haven't made any refund requests yet.</p>
                                    <a href="{{ route('customer.refunds.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Request Your First Refund
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($refunds->hasPages())
                                <div class="pagination-wrapper">
                        {{ $refunds->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search and Filter JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('refundSearch');
        const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#refundsTable tbody tr.refund-row');
    const emptyRow = document.querySelector('#refundsTable tbody tr:not(.refund-row)');

        function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value.toLowerCase();
        let visibleRows = 0;

            rows.forEach(row => {
                const rowStatus = row.dataset.status.toLowerCase();
            const rowSearchTerm = row.dataset.searchTerm.toLowerCase();

            const matchesSearch = searchTerm === '' || rowSearchTerm.includes(searchTerm);
                const matchesStatus = statusValue === '' || rowStatus === statusValue;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

        if (emptyRow) {
            emptyRow.style.display = visibleRows > 0 ? 'none' : '';
        }
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    });
    </script>

@endsection

@push('styles')
<style>
/* Refund Dashboard Specific Styles - Dark Theme Compatible */
.refunds-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.section-header {
    background: var(--accent-color);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-title {
    color: var(--text-dark);
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
}

.section-filters {
    flex: 1;
    max-width: 500px;
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-medium);
    z-index: 10;
}

.search-input {
    padding-left: 3rem;
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    background: var(--white);
    color: var(--text-dark);
    transition: all 0.2s ease;
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
    background: var(--white);
    color: var(--text-dark);
}

.search-input::placeholder {
    color: var(--text-light);
}

.status-filter {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    background: var(--white);
    color: var(--text-dark);
    transition: all 0.2s ease;
}

.status-filter:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
}

.refunds-table-container {
    background: var(--white);
}

.refunds-table {
    margin: 0;
    width: 100%;
}

.refunds-table thead th {
    background: var(--accent-color);
    border-bottom: 1px solid var(--border-light);
    color: var(--text-dark);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem;
}

.refunds-table tbody td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: middle;
    color: var(--text-medium);
}

.refunds-table tbody tr {
    background: var(--white);
    transition: all 0.2s ease;
}

.refunds-table tbody tr:hover {
    background: var(--accent-color);
}

/* Ensure proper text colors in table */
.refunds-table tbody td * {
    color: inherit;
}

.refunds-table tbody td strong,
.refunds-table tbody td .text-dark {
    color: var(--text-dark) !important;
}

.refunds-table tbody td .text-muted {
    color: var(--text-light) !important;
}

.empty-refunds {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-color), var(--border-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--text-light);
    font-size: 2rem;
}

.empty-title {
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    font-weight: 600;
    font-size: 1.25rem;
}

.empty-text {
    color: var(--text-medium);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.pagination-wrapper {
    padding: 1.5rem 2rem;
    background: var(--accent-color);
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: center;
}

/* Status badge updates for better visibility and dark theme */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    color: var(--white);
}

.badge-pending {
    background: var(--warning);
    color: var(--white);
}

.badge-approved {
    background: var(--success);
    color: var(--white);
}

.badge-rejected {
    background: var(--danger);
    color: var(--white);
}

.badge-processing {
    background: var(--info);
    color: var(--white);
}

/* Page Header Improvements */
.page-header-content h1 {
    color: var(--text-dark);
    font-weight: 700;
    margin: 0;
}

.page-header-content p {
    color: var(--text-medium);
    margin: 0;
    font-size: 1.1rem;
}

/* Stats Cards Enhancement */
.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card .stat-number {
    color: var(--text-dark);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-card .stat-label {
    color: var(--text-medium);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: var(--white);
    margin-bottom: 1rem;
}

/* Breadcrumb Improvements */
.breadcrumb-item {
    color: var(--text-medium);
}

.breadcrumb-item.active {
    color: var(--text-dark);
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Button Improvements */
.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white);
    font-weight: 600;
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline-primary {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        align-items: stretch;
        padding: 1rem;
    }

    .section-filters .row {
        margin: 0;
    }

    .section-filters .col-md-8,
    .section-filters .col-md-4 {
        padding: 0;
        margin-bottom: 0.5rem;
    }

    .refunds-table-container {
        padding: 0;
        overflow-x: auto;
    }

    .refunds-table {
        min-width: 600px;
    }

    .refunds-table thead th,
    .refunds-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }

    .empty-refunds {
        padding: 2rem 1rem;
    }

    .pagination-wrapper {
        padding: 1rem;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .page-header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .refunds-table {
        font-size: 0.8rem;
    }

    .refunds-table thead th,
    .refunds-table tbody td {
        padding: 0.5rem 0.25rem;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .section-header {
        padding: 0.75rem;
    }

    .section-title {
        font-size: 1.1rem;
    }
}

/* Approved stat specific color */
.approved-stat {
    background: linear-gradient(135deg, #dcfce7 0%, var(--white) 100%);
    border-left: 4px solid var(--success);
}

.approved-stat .stat-number {
    color: var(--success);
}

.approved-stat .stat-icon {
    background: linear-gradient(135deg, var(--success), #15803d);
}

/* Amount stat specific color */
.amount-stat {
    background: linear-gradient(135deg, #dbeafe 0%, var(--white) 100%);
    border-left: 4px solid var(--info);
}

.amount-stat .stat-number {
    color: var(--info);
}

.amount-stat .stat-icon {
    background: linear-gradient(135deg, var(--info), #1d4ed8);
}

/* Pending stat specific color */
.pending-stat .stat-number {
    color: var(--warning);
}

.pending-stat .stat-icon {
    background: linear-gradient(135deg, var(--warning), #d97706);
}

/* Total stat specific color */
.total-stat .stat-number {
    color: var(--primary-color);
}

.total-stat .stat-icon {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
}

/* Dark theme text compatibility */
.text-dark-compatible {
    color: var(--text-dark) !important;
}

.text-medium-compatible {
    color: var(--text-medium) !important;
}

.text-light-compatible {
    color: var(--text-light) !important;
}

/* Ensure all elements have proper contrast */
.refunds-section h1,
.refunds-section h2,
.refunds-section h3,
.refunds-section h4,
.refunds-section h5,
.refunds-section h6 {
    color: var(--text-dark) !important;
}

.refunds-section p,
.refunds-section span:not(.badge),
.refunds-section div:not(.badge):not(.btn) {
    color: var(--text-medium) !important;
}

.refunds-section .text-muted {
    color: var(--text-light) !important;
}

/* Table row selection and interaction improvements */
.refunds-table tbody tr.selected {
    background: rgba(171, 207, 55, 0.1);
    border-left: 3px solid var(--primary-color);
}

/* Loading state improvements */
.loading-state {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    color: var(--text-medium);
}

.loading-spinner {
    width: 2rem;
    height: 2rem;
    border: 2px solid var(--border-light);
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
    }
</style>
@endpush
