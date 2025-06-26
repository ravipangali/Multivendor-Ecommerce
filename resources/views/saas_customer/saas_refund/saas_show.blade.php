@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
    .dashboard_content {
        padding: 1.5rem;
        background-color: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .refund-header-section {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
    }

    .refund-header-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    }

    .card {
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        border-radius: var(--radius-lg);
    }

    .card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid var(--border-light);
        padding: 1rem 1.5rem;
    }

    .card-header .card-title {
        font-family: var(--font-display);
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 0;
    }

    .breadcrumb-modern-wrapper {
        padding: 0 0 1.5rem;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 1.5rem;
    }
    .breadcrumb-modern-wrapper .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
    }

    .breadcrumb-modern-wrapper .breadcrumb-item a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .breadcrumb-modern-wrapper .breadcrumb-item a:hover {
        color: var(--primary-color);
    }

    .breadcrumb-modern-wrapper .breadcrumb-item.active {
        color: var(--text-dark);
        font-weight: 600;
    }

    .step-timeline { position: relative; }
    .step { display: flex; align-items: flex-start; margin-bottom: 2rem; position: relative; }
    .step:not(:last-child)::after { content: ''; position: absolute; left: 17px; top: 40px; width: 2px; height: calc(100% - 1.5rem); background: #dee2e6; }
    .step.completed:not(:last-child)::after { background: #198754; }
    .step.rejected:not(:last-child)::after { background: #dc3545; }
    .step-icon { width: 35px; height: 35px; border-radius: 50%; background: #dee2e6; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 14px; margin-right: 1rem; flex-shrink: 0; z-index: 1; position: relative; }
    .step.completed .step-icon { background: #198754; color: white; }
    .step.rejected .step-icon { background: #dc3545; color: white; }
    .step-content { flex: 1; }
    .step-title { font-size: 0.9rem; font-weight: 600; margin-bottom: 0.25rem; color: #495057; }
    .step.completed .step-title { color: #198754; }
    .step.rejected .step-title { color: #dc3545; }
    .step-desc { font-size: 0.8rem; color: #6c757d; margin: 0; line-height: 1.4; }
</style>
@endpush

@section('content')
<section class="user-dashboard-area pt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="dashboard_content">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb-modern-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('customer.refunds.index') }}">My Refunds</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Refund #{{ $refund->id }}</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Header -->
                    <div class="refund-header-section">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="h2 text-dark fw-bold mb-2">Refund Request Details</h1>
                                {{-- <p class="text-muted fs-6 mb-0">For Order: <a href="{{ route('customer.order.detail', $refund->order->id) }}">#{{ $refund->order->order_number }}</a></p> --}}
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge {{ $refund->status_badge_class }} fs-6 px-3 py-2 rounded-pill">
                                    {{ ucfirst($refund->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Main Content -->
                        <div class="col-lg-8">
                            <!-- Refund Status Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title">Refund Status</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-info bg-opacity-10 rounded-2 me-3">
                                                    <i class="align-middle text-info" data-feather="calendar" style="width: 20px; height: 20px;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Request Date</small>
                                                    <strong>{{ $refund->created_at->format('M d, Y, g:i A') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        @if($refund->processed_at)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="p-2 bg-success bg-opacity-10 rounded-2 me-3">
                                                    <i class="align-middle text-success" data-feather="check-circle" style="width: 20px; height: 20px;"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Processed Date</small>
                                                    <strong>{{ $refund->processed_at->format('M d, Y, g:i A') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @if($refund->status === 'rejected' && $refund->rejected_reason)
                                    <div class="alert alert-danger mt-4">
                                        <h6 class="fw-bold mb-2">Rejection Reason:</h6>
                                        <p class="mb-0">{{ $refund->rejected_reason }}</p>
                                    </div>
                                    @endif
                                    @if($refund->admin_notes)
                                    <div class="alert alert-info mt-4">
                                        <h6 class="fw-bold mb-2">Admin Notes:</h6>
                                        <p class="mb-0">{{ $refund->admin_notes }}</p>
                                    </div>
                                    @endif
                                    @if($refund->admin_attachment)
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-2">Admin Attachment:</h6>
                                        <a href="{{ route('customer.refunds.attachment.download', $refund) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="align-middle me-2" data-feather="download" style="width: 16px; height: 16px;"></i>
                                            Download Attachment
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Details Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title">Order Details</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="py-2 px-3">Product</th>
                                                    <th class="py-2 px-3">Quantity</th>
                                                    <th class="py-2 px-3">Price</th>
                                                    <th class="py-2 px-3">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($refund->order->items as $item)
                                                <tr>
                                                    <td class="py-2 px-3">
                                                        <div class="d-flex align-items-center">
                                                            @if($item->product && $item->product->images->first())
                                                            <img src="{{ $item->product->images->first()->image_url }}" alt="{{ $item->product->name }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                            @endif
                                                            <div>
                                                                <strong>{{ $item->product ? $item->product->name : 'Product Deleted' }}</strong>
                                                                @if($item->productVariation)
                                                                <small class="text-muted d-block">{{ $item->productVariation->attribute->name }}: {{ $item->productVariation->attributeValue->value }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-3">{{ $item->quantity }}</td>
                                                    <td class="py-2 px-3">Rs {{ number_format($item->price, 2) }}</td>
                                                    <td class="py-2 px-3">Rs {{ number_format($item->total, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Refund Amount & Reason -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title">Refund Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Refund Amount</strong>
                                            <div class="text-center p-3 bg-success bg-opacity-10 rounded-3 mt-2">
                                                <h4 class="fw-bold text-success mb-0">Rs {{ number_format($refund->refund_amount, 2) }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Refund Reason</strong>
                                            <div class="bg-light p-3 rounded-3 mt-2">
                                                <p class="mb-0">{{ $refund->customer_reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Refund Process -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title">Refund Process</h6>
                                </div>
                                <div class="card-body">
                                    <div class="step-timeline">
                                        <div class="step completed">
                                            <div class="step-icon"><i class="fas fa-plus"></i></div>
                                            <div class="step-content">
                                                <h6 class="step-title">Request Submitted</h6>
                                                <p class="step-desc">Your refund request has been submitted</p>
                                            </div>
                                        </div>
                                        <div class="step {{ in_array($refund->status, ['approved', 'processed']) ? 'completed' : ($refund->status === 'rejected' ? 'rejected' : '') }}">
                                            <div class="step-icon"><i class="fas fa-eye"></i></div>
                                            <div class="step-content">
                                                <h6 class="step-title">Under Review</h6>
                                                <p class="step-desc">Admin is reviewing your request</p>
                                            </div>
                                        </div>
                                        <div class="step {{ in_array($refund->status, ['approved', 'processed']) ? 'completed' : '' }}">
                                            <div class="step-icon"><i class="fas fa-check"></i></div>
                                            <div class="step-content">
                                                <h6 class="step-title">@if($refund->status === 'rejected') Request Rejected @else Request Approved @endif</h6>
                                                <p class="step-desc">@if($refund->status === 'rejected') Your request has been rejected @else Refund will be processed @endif</p>
                                            </div>
                                        </div>
                                        @if($refund->status !== 'rejected')
                                        <div class="step {{ $refund->status === 'processed' ? 'completed' : '' }}">
                                            <div class="step-icon"><i class="fas fa-money-bill"></i></div>
                                            <div class="step-content">
                                                <h6 class="step-title">Refund Processed</h6>
                                                <p class="step-desc">Amount refunded to your payment method</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Refund Method Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="card-title">Refund Method</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-2 me-3">
                                            <i class="align-middle text-primary" data-feather="credit-card" style="width: 20px; height: 20px;"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $refund->paymentMethod->title }}</strong>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $refund->paymentMethod->type)) }}</small>
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
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection