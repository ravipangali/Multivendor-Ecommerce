@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Create Coupon')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Create New Coupon</h1>
        <div class="float-end">
            <a href="{{ route('seller.coupons.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Coupons
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('seller.coupons.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('code') is-invalid @enderror"
                                           id="code"
                                           name="code"
                                           value="{{ old('code') }}"
                                           placeholder="e.g., SAVE20"
                                           style="text-transform: uppercase;"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Use uppercase letters and numbers only</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('discount_type') is-invalid @enderror"
                                            id="discount_type"
                                            name="discount_type"
                                            required>
                                        <option value="">Select discount type</option>
                                        @foreach($discountTypes as $type)
                                            <option value="{{ $type }}" {{ old('discount_type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error('discount_value') is-invalid @enderror"
                                               id="discount_value"
                                               name="discount_value"
                                               value="{{ old('discount_value') }}"
                                               step="0.01"
                                               min="0"
                                               required>
                                        <span class="input-group-text" id="discount-unit">
                                            <span id="discount-symbol">Rs</span>
                                        </span>
                                        @error('discount_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">Usage Limit</label>
                                    <input type="number"
                                           class="form-control @error('usage_limit') is-invalid @enderror"
                                           id="usage_limit"
                                           name="usage_limit"
                                           value="{{ old('usage_limit') }}"
                                           min="1"
                                           placeholder="Leave empty for unlimited">
                                    @error('usage_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">How many times this coupon can be used (leave empty for unlimited)</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date"
                                           name="start_date"
                                           value="{{ old('start_date') }}"
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date"
                                           name="end_date"
                                           value="{{ old('end_date') }}"
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Describe what this coupon is for...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="align-middle" data-feather="save"></i> Create Coupon
                            </button>
                            <a href="{{ route('seller.coupons.index') }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="x"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeSelect = document.getElementById('discount_type');
    const discountSymbol = document.getElementById('discount-symbol');
    const codeInput = document.getElementById('code');

    // Update discount symbol based on type
    function updateDiscountSymbol() {
        if (discountTypeSelect.value === 'percentage') {
            discountSymbol.textContent = '%';
        } else {
            discountSymbol.textContent = 'Rs';
        }
    }

    // Convert code to uppercase
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    discountTypeSelect.addEventListener('change', updateDiscountSymbol);

    // Initial symbol update
    updateDiscountSymbol();
});
</script>
@endsection
