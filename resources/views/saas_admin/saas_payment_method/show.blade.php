@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Payment Method Details')

@section('content')
<div class="col-12">
    <!-- Header Card -->
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Payment Method Details</h5>
                <div>
                    <a href="{{ route('admin.payment-methods.edit', ['payment_method' => $paymentMethod->id, 'user_id' => request('user_id'), 'user_role' => request('user_role')]) }}"
                       class="btn btn-warning">
                        <i class="align-middle" data-feather="edit"></i> Edit
                    </a>
                    @if(request('user_id') && request('user_role'))
                        @if(request('user_role') == 'customer')
                            <a href="{{ route('admin.customers.show', request('user_id')) }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="arrow-left"></i> Back to Customer
                            </a>
                        @elseif(request('user_role') == 'seller')
                            <a href="{{ route('admin.sellers.show', request('user_id')) }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="arrow-left"></i> Back to Seller
                            </a>
                        @else
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="arrow-left"></i> Back to List
                            </a>
                        @endif
                    @else
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to List
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status and Type Overview -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Payment Type</h6>
                    @if($paymentMethod->type == 'bank_transfer')
                        <div class="mb-2">
                            <i data-feather="credit-card" class="text-primary" style="width: 32px; height: 32px;"></i>
                        </div>
                        <span class="badge bg-primary fs-6">Bank Transfer</span>
                    @elseif($paymentMethod->type == 'esewa')
                        <div class="mb-2">
                            <i data-feather="smartphone" class="text-success" style="width: 32px; height: 32px;"></i>
                        </div>
                        <span class="badge bg-success fs-6">eSewa</span>
                    @elseif($paymentMethod->type == 'khalti')
                        <div class="mb-2">
                            <i data-feather="smartphone" class="text-purple" style="width: 32px; height: 32px;"></i>
                        </div>
                        <span class="badge bg-purple fs-6">Khalti</span>
                    @elseif($paymentMethod->type == 'cash')
                        <div class="mb-2">
                            <i data-feather="dollar-sign" class="text-warning" style="width: 32px; height: 32px;"></i>
                        </div>
                        <span class="badge bg-warning fs-6">Cash</span>
                    @else
                        <div class="mb-2">
                            <i data-feather="credit-card" class="text-secondary" style="width: 32px; height: 32px;"></i>
                        </div>
                        <span class="badge bg-secondary fs-6">Other</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Status</h6>
                    <div class="mb-2">
                        @if($paymentMethod->is_active)
                            <i data-feather="check-circle" class="text-success" style="width: 32px; height: 32px;"></i>
                        @else
                            <i data-feather="x-circle" class="text-danger" style="width: 32px; height: 32px;"></i>
                        @endif
                    </div>
                    @if($paymentMethod->is_active)
                        <span class="badge bg-success fs-6">Active</span>
                    @else
                        <span class="badge bg-danger fs-6">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Default Method</h6>
                    <div class="mb-2">
                        @if($paymentMethod->is_default)
                            <i data-feather="star" class="text-warning" style="width: 32px; height: 32px;"></i>
                        @else
                            <i data-feather="circle" class="text-muted" style="width: 32px; height: 32px;"></i>
                        @endif
                    </div>
                    @if($paymentMethod->is_default)
                        <span class="badge bg-primary fs-6">Default</span>
                    @else
                        <span class="badge bg-secondary fs-6">Not Default</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Created</h6>
                    <div class="mb-2">
                        <i data-feather="calendar" class="text-info" style="width: 32px; height: 32px;"></i>
                    </div>
                    <span class="fs-6">{{ $paymentMethod->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0">
                <i data-feather="info" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                Basic Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Title/Nickname</label>
                    <p class="fw-bold mb-0">{{ $paymentMethod->title }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-muted small">Account Holder Name</label>
                    <p class="fw-bold mb-0">{{ $paymentMethod->account_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Type-specific Details -->
    @if($paymentMethod->type == 'bank_transfer')
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="credit-card" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Bank Account Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Bank Name</label>
                        <p class="fw-bold mb-0">{{ $paymentMethod->bank_name ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Branch Name</label>
                        <p class="fw-bold mb-0">{{ $paymentMethod->bank_branch ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Account Number</label>
                        <p class="fw-bold mb-0">{{ $paymentMethod->account_number ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="smartphone" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Mobile Wallet Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Mobile Number</label>
                        <p class="fw-bold mb-0">{{ $paymentMethod->mobile_number ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Wallet Type</label>
                        <p class="fw-bold mb-0">{{ ucfirst($paymentMethod->type) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Additional Information -->
    @if($paymentMethod->notes)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="file-text" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Additional Information
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $paymentMethod->notes }}</p>
            </div>
        </div>
    @endif

    <!-- Audit Information -->
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0">
                <i data-feather="clock" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                Audit Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="text-muted small">Created At</label>
                    <p class="mb-0">{{ $paymentMethod->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">Last Updated</label>
                    <p class="mb-0">{{ $paymentMethod->updated_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-3 d-flex justify-content-end gap-2">
        @if(!$paymentMethod->is_default)
            <form action="{{ route('admin.payment-methods.set-default', $paymentMethod->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info">
                    <i class="align-middle" data-feather="star"></i> Set as Default
                </button>
            </form>
        @endif

        <a href="{{ route('admin.payment-methods.edit', ['payment_method' => $paymentMethod->id, 'user_id' => request('user_id'), 'user_role' => request('user_role')]) }}"
           class="btn btn-warning">
            <i class="align-middle" data-feather="edit"></i> Edit Payment Method
        </a>

        @if(!$paymentMethod->is_default)
            <form action="{{ route('admin.payment-methods.destroy', $paymentMethod->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger delete-confirm">
                    <i class="align-middle" data-feather="trash-2"></i> Delete
                </button>
            </form>
        @endif
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize delete confirmation
        const deleteButton = document.querySelector('.delete-confirm');
        if (deleteButton) {
            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This payment method will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    label.text-muted {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection
