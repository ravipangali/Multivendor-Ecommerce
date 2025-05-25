@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Flash Deal Product')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Product Discount</h5>
                <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDealProduct->flash_deal_id]) }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                </a>
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

            <div class="alert alert-info mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <strong>Flash Deal:</strong> {{ $flashDealProduct->flashDeal->title }}<br>
                        <strong>Period:</strong> {{ $flashDealProduct->flashDeal->start_time->format('M d, Y H:i') }} - {{ $flashDealProduct->flashDeal->end_time->format('M d, Y H:i') }}<br>
                        <strong>Status:</strong>
                        @if($flashDealProduct->flashDeal->end_time < now())
                            <span class="badge bg-danger">Expired</span>
                        @elseif($flashDealProduct->flashDeal->start_time > now())
                            <span class="badge bg-warning">Upcoming</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.flash-deals.show', $flashDealProduct->flash_deal_id) }}" class="btn btn-sm btn-info">
                            <i class="align-middle" data-feather="eye"></i> View Flash Deal
                        </a>
                    </div>
                </div>
            </div>

            @if(!$flashDealProduct->product)
                <div class="alert alert-danger mb-4">
                    <i class="align-middle me-2" data-feather="alert-circle"></i>
                    The product associated with this flash deal item has been deleted. You can update the discount settings, but they won't apply to any product.
                </div>
            @endif

            <form action="{{ route('admin.flash-deal-products.update', $flashDealProduct->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <div class="d-flex align-items-center border rounded p-2">
                                @if($flashDealProduct->product && $flashDealProduct->product->images && $flashDealProduct->product->images->count() > 0)
                                    <img src="{{ asset($flashDealProduct->product->images->first()->image_url) }}"
                                        alt="{{ $flashDealProduct->product->name }}" width="60" height="60" class="img-thumbnail me-3">
                                @endif
                                <div>
                                    @if($flashDealProduct->product)
                                        <h6 class="mb-0">{{ $flashDealProduct->product->name }}</h6>
                                        <p class="mb-1">Regular Price: Rs {{ number_format($flashDealProduct->product->price, 2) }}</p>
                                        <small class="text-muted">SKU: {{ $flashDealProduct->product->SKU }}</small>
                                    @else
                                        <h6 class="mb-0 text-danger">Product Deleted</h6>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                @foreach($discountTypes as $type)
                                    <option value="{{ $type }}" {{ old('discount_type', $flashDealProduct->discount_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="discount_value" name="discount_value"
                                    value="{{ old('discount_value', $flashDealProduct->discount_value) }}" step="0.01" min="0" required>
                                <span class="input-group-text" id="discount-symbol">%</span>
                            </div>
                            <small class="text-muted" id="discount-hint">
                                For percentage discount, use values from 1 to 100
                            </small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Discount Preview</h6>
                            </div>
                            <div class="card-body">
                                @if($flashDealProduct->product)
                                    <table class="table">
                                        <tr>
                                            <th>Regular Price:</th>
                                            <td id="regular-price">Rs {{ number_format($flashDealProduct->product->price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount:</th>
                                            <td id="discount-amount">
                                                @if($flashDealProduct->discount_type == 'percentage')
                                                    Rs {{ number_format(($flashDealProduct->product->price * $flashDealProduct->discount_value / 100), 2) }}
                                                @else
                                                    Rs {{ number_format($flashDealProduct->discount_value, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="table-success">
                                            <th>Final Price:</th>
                                            <td id="final-price">
                                                @if($flashDealProduct->discount_type == 'percentage')
                                                    Rs {{ number_format($flashDealProduct->product->price - ($flashDealProduct->product->price * $flashDealProduct->discount_value / 100), 2) }}
                                                @else
                                                    Rs {{ number_format($flashDealProduct->product->price - $flashDealProduct->discount_value, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="align-middle me-2" data-feather="alert-triangle"></i>
                                        Cannot calculate discount preview because the product has been deleted.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Discount</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($flashDealProduct->product)
        const productPrice = {{ $flashDealProduct->product->price }};
        const discountTypeSelect = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');
        const discountSymbol = document.getElementById('discount-symbol');
        const discountHint = document.getElementById('discount-hint');
        const discountAmountEl = document.getElementById('discount-amount');
        const finalPriceEl = document.getElementById('final-price');

        // Update the discount symbol and hint based on discount type
        discountTypeSelect.addEventListener('change', function() {
            updateDiscountUI();
            calculateDiscount();
        });

        // Calculate discount when discount value changes
        discountValueInput.addEventListener('input', calculateDiscount);

        function updateDiscountUI() {
            const discountType = discountTypeSelect.value;

            if (discountType === 'percentage') {
                discountSymbol.textContent = '%';
                discountHint.textContent = 'For percentage discount, use values from 1 to 100';
                discountValueInput.setAttribute('max', '100');
            } else {
                discountSymbol.textContent = 'Rs';
                discountHint.textContent = 'For flat discount, enter an amount less than the product price';
                discountValueInput.removeAttribute('max');
            }
        }

        function calculateDiscount() {
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;
            let discountAmount = 0;
            let finalPrice = productPrice;

            if (discountType === 'percentage') {
                discountAmount = (productPrice * discountValue / 100);
            } else {
                discountAmount = discountValue;
            }

            finalPrice = productPrice - discountAmount;

            // Handle negative final price (if discount is greater than price)
            if (finalPrice < 0) {
                finalPrice = 0;
                discountAmount = productPrice;
            }

            discountAmountEl.textContent = 'Rs ' + discountAmount.toFixed(2);
            finalPriceEl.textContent = 'Rs ' + finalPrice.toFixed(2);
        }

        // Initialize UI
        updateDiscountUI();
        @endif
    });
</script>
@endsection
