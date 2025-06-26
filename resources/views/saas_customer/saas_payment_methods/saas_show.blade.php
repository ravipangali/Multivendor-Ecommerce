@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
    .payment-methods-container {
        background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
        min-height: 80vh;
        padding: 2rem 0;
    }
    .payment-methods-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: var(--white);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }
    .details-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
    }
    .detail-item {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    .detail-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--accent-color);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    .detail-item-label {
        color: var(--text-medium);
        display: block;
        font-size: 0.875rem;
    }
    .detail-item-value {
        font-weight: 500;
        color: var(--text-dark);
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        color: var(--white);
        padding: 0.5rem 1rem;
    }
    .btn-secondary-custom {
        background: var(--text-medium);
        border: none;
        color: var(--white);
        padding: 0.5rem 1rem;
    }
    .breadcrumb-enhanced {
        padding: 2rem 0;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
    }
    .breadcrumb-inner {
        display: flex;
        align-items: center;
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 1.5rem 2rem;
        box-shadow: var(--shadow-md);
    }
    .breadcrumb-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-right: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 15px rgba(171, 207, 55, 0.3);
    }
    .breadcrumb-content {
        flex: 1;
    }
    .breadcrumb-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.25rem;
    }
    .breadcrumb-enhanced .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
        font-size: 0.875rem;
    }
    .breadcrumb-enhanced .breadcrumb-item a {
        color: var(--text-medium);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .breadcrumb-enhanced .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    .breadcrumb-enhanced .breadcrumb-item.active {
        color: var(--text-dark);
        font-weight: 500;
    }
    .breadcrumb-enhanced .breadcrumb-item::before {
        color: var(--text-light);
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-enhanced">
    <div class="container">
        <div class="breadcrumb-inner">
            <div class="breadcrumb-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="breadcrumb-content">
                <h2 class="breadcrumb-title">Method Details</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.payment-methods.index') }}">Payment Methods</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="payment-methods-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>
            <div class="col-lg-9">
                 <div class="payment-methods-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 text-white">{{ $paymentMethod->title }}</h2>
                            <p class="mb-0 text-white opacity-75">Details for your saved payment method</p>
                        </div>
                        <div>
                            <a href="{{ route('customer.payment-methods.edit', $paymentMethod->id) }}" class="btn btn-light btn-sm"><i class="fas fa-edit me-1"></i> Edit</a>
                            <a href="{{ route('customer.payment-methods.index') }}" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to List</a>
                        </div>
                    </div>
                </div>

                <div class="details-card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-item-icon"><i class="fas fa-tag"></i></div>
                                <div>
                                    <span class="detail-item-label">Type</span>
                                    <span class="detail-item-value">{{ $paymentMethod->getDisplayNameAttribute() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-item-icon"><i class="fas fa-star"></i></div>
                                <div>
                                    <span class="detail-item-label">Default</span>
                                    <span class="detail-item-value">{!! $paymentMethod->is_default ? '<span class="badge bg-primary">Yes</span>' : 'No' !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-4">Specific Details</h5>

                    @if($paymentMethod->type == 'bank_transfer')
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Bank Name:</strong> {{ $paymentMethod->details['bank_name'] ?? 'N/A' }}</p>
                                <p><strong>Account Holder Name:</strong> {{ $paymentMethod->details['account_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Branch Name:</strong> {{ $paymentMethod->details['branch_name'] ?? 'N/A' }}</p>
                                <p><strong>Account Number:</strong> {{ $paymentMethod->details['account_number'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @elseif($paymentMethod->type == 'esewa')
                        <p><strong>eSewa ID:</strong> {{ $paymentMethod->details['esewa_id'] ?? 'N/A' }}</p>
                    @elseif($paymentMethod->type == 'khalti')
                        <p><strong>Khalti ID:</strong> {{ $paymentMethod->details['khalti_id'] ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
