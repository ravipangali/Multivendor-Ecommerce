@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Add Payment Method')

@section('content')
<div class="col-12">
    <form action="{{ route('admin.payment-methods.store') }}" method="POST" id="paymentMethodForm">
        @csrf

        @if(isset($userId))
            <input type="hidden" name="user_id" value="{{ $userId }}">
        @endif

        @if(isset($userRole))
            <input type="hidden" name="user_role" value="{{ $userRole }}">
        @endif

        <!-- Header Card -->
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Add New Payment Method</h5>
                    <div>
                        @if(isset($userId) && isset($userRole))
                            @if($userRole == 'customer')
                                <a href="{{ route('admin.customers.show', $userId) }}" class="btn btn-secondary">
                                    <i class="align-middle" data-feather="arrow-left"></i> Back to Customer
                                </a>
                            @elseif($userRole == 'seller')
                                <a href="{{ route('admin.sellers.show', $userId) }}" class="btn btn-secondary">
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
                        <label for="type" class="form-label">Payment Method Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">-- Select Payment Method Type --</option>
                            <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>
                                <i class="bi bi-bank"></i> Bank Transfer
                            </option>
                            <option value="esewa" {{ old('type') == 'esewa' ? 'selected' : '' }}>
                                <i class="bi bi-phone"></i> eSewa
                            </option>
                            <option value="khalti" {{ old('type') == 'khalti' ? 'selected' : '' }}>
                                <i class="bi bi-phone"></i> Khalti
                            </option>
                            <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>
                                <i class="bi bi-cash"></i> Cash
                            </option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
                                <i class="bi bi-wallet2"></i> Other
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Choose the type of payment method you want to add</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title/Nickname <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g., My Primary Bank Account" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Give a friendly name to identify this payment method</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="account_name" class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                               id="account_name" name="account_name" value="{{ old('account_name') }}"
                               placeholder="Enter account holder's full name" required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Name as it appears on the account</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Status</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">Enable this payment method</small>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                           value="1" {{ old('is_default') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        <strong>Set as Default</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">Make this the primary payment method</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Transfer Details -->
        <div id="bank_fields" class="card mb-3 d-none">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="credit-card" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Bank Account Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger bank-required">*</span></label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                               id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                               placeholder="e.g., Nepal Investment Bank">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="bank_branch" class="form-label">Branch Name <span class="text-danger bank-required">*</span></label>
                        <input type="text" class="form-control @error('bank_branch') is-invalid @enderror"
                               id="bank_branch" name="bank_branch" value="{{ old('bank_branch') }}"
                               placeholder="e.g., Kathmandu Main Branch">
                        @error('bank_branch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="account_number" class="form-label">Account Number <span class="text-danger bank-required">*</span></label>
                        <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                               id="account_number" name="account_number" value="{{ old('account_number') }}"
                               placeholder="Enter your bank account number">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Keep this information secure</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Wallet Details -->
        <div id="mobile_fields" class="card mb-3 d-none">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="smartphone" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Mobile Wallet Details
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger mobile-required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+977</span>
                            <input type="text" class="form-control @error('mobile_number') is-invalid @enderror"
                                   id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}"
                                   placeholder="9812345678" maxlength="10">
                        </div>
                        @error('mobile_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Enter 10-digit mobile number without country code</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i data-feather="file-text" class="align-middle me-2" style="width: 18px; height: 18px;"></i>
                    Additional Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Notes / Additional Details</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="4"
                                  placeholder="Add any additional information about this payment method...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">This information will be visible only to you</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save Payment Method
                    </button>
                    @if(isset($userId) && isset($userRole))
                        @if($userRole == 'customer')
                            <a href="{{ route('admin.customers.show', $userId) }}" class="btn btn-light">
                                <i class="align-middle" data-feather="x"></i> Cancel
                            </a>
                        @elseif($userRole == 'seller')
                            <a href="{{ route('admin.sellers.show', $userId) }}" class="btn btn-light">
                                <i class="align-middle" data-feather="x"></i> Cancel
                            </a>
                        @else
                            <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-light">
                                <i class="align-middle" data-feather="x"></i> Cancel
                            </a>
                        @endif
                    @else
                        <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-light">
                            <i class="align-middle" data-feather="x"></i> Cancel
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const bankFields = document.getElementById('bank_fields');
        const mobileFields = document.getElementById('mobile_fields');
        const form = document.getElementById('paymentMethodForm');

        // Get all required fields
        const bankNameInput = document.getElementById('bank_name');
        const bankBranchInput = document.getElementById('bank_branch');
        const accountNumberInput = document.getElementById('account_number');
        const mobileNumberInput = document.getElementById('mobile_number');

        function toggleFields() {
            const selectedType = typeSelect.value;

            // Hide all field groups first
            bankFields.classList.add('d-none');
            mobileFields.classList.add('d-none');

            // Reset required attributes for hidden fields
            bankNameInput.required = false;
            bankBranchInput.required = false;
            accountNumberInput.required = false;
            mobileNumberInput.required = false;

            // Show relevant fields based on selection
            if (selectedType === 'bank_transfer') {
                bankFields.classList.remove('d-none');
                bankNameInput.required = true;
                bankBranchInput.required = true;
                accountNumberInput.required = true;
            } else if (selectedType === 'esewa' || selectedType === 'khalti') {
                mobileFields.classList.remove('d-none');
                mobileNumberInput.required = true;
            }
        }

        // Initial toggle on page load
        toggleFields();

        // Toggle fields when type changes
        typeSelect.addEventListener('change', function() {
            const newType = typeSelect.value;
            const previousType = typeSelect.dataset.lastValue || '';

            // Store the current value for next comparison
            typeSelect.dataset.lastValue = newType;

            // If type has changed, clear irrelevant fields
            if (previousType && newType !== previousType) {
                if (newType === 'bank_transfer') {
                    // Clear mobile fields
                    mobileNumberInput.value = '';
                } else if (newType === 'esewa' || newType === 'khalti') {
                    // Clear bank fields
                    bankNameInput.value = '';
                    bankBranchInput.value = '';
                    accountNumberInput.value = '';
                } else {
                    // For other types, clear all specialized fields
                    bankNameInput.value = '';
                    bankBranchInput.value = '';
                    accountNumberInput.value = '';
                    mobileNumberInput.value = '';
                }
            }

            toggleFields();
        });

        // Mobile number validation
        mobileNumberInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Limit to 10 digits
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const selectedType = typeSelect.value;

            if (!selectedType) {
                e.preventDefault();
                typeSelect.classList.add('is-invalid');
                typeSelect.focus();
                return false;
            }

            // Additional validation based on type
            if (selectedType === 'bank_transfer') {
                if (!bankNameInput.value || !bankBranchInput.value || !accountNumberInput.value) {
                    e.preventDefault();
                    alert('Please fill in all required bank details.');
                    return false;
                }
            } else if (selectedType === 'esewa' || selectedType === 'khalti') {
                if (!mobileNumberInput.value || mobileNumberInput.value.length !== 10) {
                    e.preventDefault();
                    alert('Please enter a valid 10-digit mobile number.');
                    mobileNumberInput.focus();
                    return false;
                }
            }
        });
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

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .input-group-text {
        background-color: #e9ecef;
        border: 1px solid #ced4da;
    }

    .bank-required,
    .mobile-required {
        display: inline;
    }
</style>
@endsection
