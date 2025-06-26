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
    .form-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
    }
    .form-control, .form-select {
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-medium);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border: none;
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        transform: translateY(-2px);
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
                <i class="fas fa-plus"></i>
            </div>
            <div class="breadcrumb-content">
                <h2 class="breadcrumb-title">Add New Method</h2>
        <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.payment-methods.index') }}">Payment Methods</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New</li>
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
                     <h2 class="mb-0 text-white">Add a New Payment Method</h2>
                    </div>

                        <div class="form-card">
                            <form action="{{ route('customer.payment-methods.store') }}" method="POST" id="paymentMethodForm">
                                @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="type" class="form-label">Payment Method Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="" disabled selected>-- Select Type --</option>
                                    <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="esewa" {{ old('type') == 'esewa' ? 'selected' : '' }}>eSewa</option>
                                    <option value="khalti" {{ old('type') == 'khalti' ? 'selected' : '' }}>Khalti</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                        <hr class="my-4">

                        <!-- Bank Transfer Fields -->
                        <div id="bank_fields" class="d-none">
                            <h5 class="mb-3">Bank Account Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="details[bank_name]" value="{{ old('details.bank_name') }}">
                                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="details[branch_name]" value="{{ old('details.branch_name') }}">
                                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="account_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="details[account_name]" value="{{ old('details.account_name') }}">
                                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="details[account_number]" value="{{ old('details.account_number') }}">
                                                </div>
                                            </div>
                                        </div>

                        <!-- eSewa Fields -->
                        <div id="esewa_fields" class="d-none">
                            <h5 class="mb-3">eSewa Details</h5>
                            <div class="mb-3">
                                <label for="esewa_id" class="form-label">eSewa ID (Mobile Number) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="details[esewa_id]" value="{{ old('details.esewa_id') }}">
                                            </div>
                                        </div>

                        <!-- Khalti Fields -->
                        <div id="khalti_fields" class="d-none">
                            <h5 class="mb-3">Khalti Details</h5>
                            <div class="mb-3">
                                <label for="khalti_id" class="form-label">Khalti ID (Mobile Number) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="details[khalti_id]" value="{{ old('details.khalti_id') }}">
                                            </div>
                                        </div>

                        <hr class="my-4">

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
                            <label class="form-check-label pt-1" for="is_default">Set as default payment method</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('customer.payment-methods.index') }}" class="btn btn-secondary me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom">Save Method</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function () {
        const type = this.value;
        document.getElementById('bank_fields').classList.add('d-none');
        document.getElementById('esewa_fields').classList.add('d-none');
        document.getElementById('khalti_fields').classList.add('d-none');

        if (type === 'bank_transfer') {
            document.getElementById('bank_fields').classList.remove('d-none');
        } else if (type === 'esewa') {
            document.getElementById('esewa_fields').classList.remove('d-none');
        } else if (type === 'khalti') {
            document.getElementById('khalti_fields').classList.remove('d-none');
        }
    });

    // Trigger change on page load to show fields if there's an old value
    document.getElementById('type').dispatchEvent(new Event('change'));
</script>
@endpush
