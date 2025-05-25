@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Edit Payment Method')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Edit Payment Method</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('seller.payment-methods.update', $paymentMethod) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Payment Method Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Payment Method Type</option>
                                    <option value="bank_transfer" {{ old('type', $paymentMethod->type) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="esewa" {{ old('type', $paymentMethod->type) == 'esewa' ? 'selected' : '' }}>eSewa</option>
                                    <option value="khalti" {{ old('type', $paymentMethod->type) == 'khalti' ? 'selected' : '' }}>Khalti</option>
                                    <option value="cash" {{ old('type', $paymentMethod->type) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="other" {{ old('type', $paymentMethod->type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $paymentMethod->title) }}" required placeholder="e.g. My NIC Asia Account">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">Account Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name" name="account_name" value="{{ old('account_name', $paymentMethod->account_name) }}" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bank_fields" class="d-none">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $paymentMethod->bank_name) }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="bank_branch" class="form-label">Bank Branch <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bank_branch') is-invalid @enderror" id="bank_branch" name="bank_branch" value="{{ old('bank_branch', $paymentMethod->bank_branch) }}">
                                    @error('bank_branch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number', $paymentMethod->account_number) }}">
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- eSewa/Khalti Fields -->
                        <div id="mobile_fields" class="d-none">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="mobile_number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $paymentMethod->mobile_number) }}" placeholder="e.g. 9876543210">
                                    @error('mobile_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $paymentMethod->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $paymentMethod->is_default) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">Set as Default Payment Method</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Payment Method</button>
                            <a href="{{ route('seller.payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const bankFields = document.getElementById('bank_fields');
        const mobileFields = document.getElementById('mobile_fields');

        function toggleFields() {
            const selectedType = typeSelect.value;

            // Hide all field groups first
            bankFields.classList.add('d-none');
            mobileFields.classList.add('d-none');

            // Show relevant fields based on selection
            if (selectedType === 'bank_transfer') {
                bankFields.classList.remove('d-none');
            } else if (selectedType === 'esewa' || selectedType === 'khalti') {
                mobileFields.classList.remove('d-none');
            }
        }

        // Initial toggle on page load
        toggleFields();

        // Toggle fields when type changes
        typeSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection
@endsection
