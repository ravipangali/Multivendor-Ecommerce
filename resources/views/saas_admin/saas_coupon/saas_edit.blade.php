@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Coupon')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Coupon</h5>
                <div>
                    <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Coupon
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Coupons
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                            <small class="text-muted">Enter a unique code (e.g., SUMMER2023, WELCOME10)</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            <small class="text-muted">Brief description of the coupon for internal reference</small>
                        </div>

                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                @foreach($discountTypes as $type)
                                    <option value="{{ $type }}" {{ old('discount_type', $coupon->discount_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="discount_value" name="discount_value"
                                    value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01" min="0" required>
                                <span class="input-group-text" id="discount-symbol">%</span>
                            </div>
                            <small class="text-muted" id="discount-hint">
                                For percentage discount, enter a value between 1 and 100
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ old('end_date', $coupon->end_date->format('Y-m-d')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit</label>
                            <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                                value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                            <small class="text-muted">Maximum number of times this coupon can be used (leave empty for unlimited)</small>
                        </div>

                        <div class="mb-3">
                            <label for="seller_id" class="form-label">Seller</label>
                            <select class="form-select" id="seller_id" name="seller_id">
                                <option value="">Global Coupon (All Sellers)</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ old('seller_id', $coupon->seller_id) == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">If selected, the coupon will only apply to this seller's products</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usage Information</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Used Count:</strong> {{ $coupon->used_count }} times</p>
                                    @if($coupon->usage_limit)
                                        <p class="mb-1"><strong>Remaining Uses:</strong> {{ max(0, $coupon->usage_limit - $coupon->used_count) }}</p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            @php
                                                $percentUsed = ($coupon->used_count / $coupon->usage_limit) * 100;
                                            @endphp
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentUsed }}%;"
                                                aria-valuenow="{{ $percentUsed }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    @else
                                        <p class="mb-0"><strong>Remaining Uses:</strong> Unlimited</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountTypeSelect = document.getElementById('discount_type');
        const discountSymbol = document.getElementById('discount-symbol');
        const discountHint = document.getElementById('discount-hint');
        const discountValueInput = document.getElementById('discount_value');

        // Update the discount symbol and hint based on discount type
        discountTypeSelect.addEventListener('change', function() {
            updateDiscountUI();
        });

        function updateDiscountUI() {
            const discountType = discountTypeSelect.value;

            if (discountType === 'percentage') {
                discountSymbol.textContent = '%';
                discountHint.textContent = 'For percentage discount, enter a value between 1 and 100';
                discountValueInput.setAttribute('max', '100');
            } else {
                discountSymbol.textContent = 'Rs';
                discountHint.textContent = 'Enter the flat discount amount';
                discountValueInput.removeAttribute('max');
            }
        }

        // Set minimum values for dates
        const today = new Date().toISOString().split('T')[0];

        // Update end_date min value when start_date changes
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;

            // If end_date is before new start_date, update it
            if (document.getElementById('end_date').value < this.value) {
                document.getElementById('end_date').value = this.value;
            }
        });

        // Initialize UI
        updateDiscountUI();
    });
</script>
@endsection