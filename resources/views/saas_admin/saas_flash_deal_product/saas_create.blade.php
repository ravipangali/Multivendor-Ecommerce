@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Add Product to Flash Deal')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    @if(isset($flashDeal))
                        Add Product to "{{ $flashDeal->title }}"
                    @else
                        Add Product to Flash Deal
                    @endif
                </h5>
                <div>
                    @if(isset($flashDeal))
                        <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to Products
                        </a>
                    @else
                        <a href="{{ route('admin.flash-deal-products.index') }}" class="btn btn-secondary">
                            <i class="align-middle" data-feather="arrow-left"></i> Back to Flash Deal Products
                        </a>
                    @endif
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

            <form action="{{ route('admin.flash-deal-products.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        @if(!isset($flashDeal))
                            <div class="mb-3">
                                <label for="flash_deal_id" class="form-label">Flash Deal <span class="text-danger">*</span></label>
                                <select class="form-select" id="flash_deal_id" name="flash_deal_id" required>
                                    <option value="">Select Flash Deal</option>
                                    @foreach($flashDeals as $deal)
                                        <option value="{{ $deal->id }}" {{ old('flash_deal_id') == $deal->id ? 'selected' : '' }}>
                                            {{ $deal->title }} ({{ $deal->start_time->format('M d, Y') }} - {{ $deal->end_time->format('M d, Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="flash_deal_id" value="{{ $flashDeal->id }}">
                            <div class="mb-3">
                                <label class="form-label">Flash Deal</label>
                                <div class="form-control bg-light">{{ $flashDeal->title }}</div>
                                <div class="small text-muted mt-1">
                                    Period: {{ $flashDeal->start_time->format('M d, Y H:i') }} - {{ $flashDeal->end_time->format('M d, Y H:i') }}
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}
                                        data-price="{{ $product->price }}">
                                        {{ $product->name }} (Rs {{ number_format($product->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="discount_type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="discount_type" name="discount_type" required>
                                @foreach($discountTypes as $type)
                                    <option value="{{ $type }}" {{ old('discount_type', 'percentage') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="discount_value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="discount_value" name="discount_value"
                                    value="{{ old('discount_value') }}" step="0.01" min="0" required>
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
                                <table class="table">
                                    <tr>
                                        <th>Regular Price:</th>
                                        <td id="regular-price">Rs 0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Discount:</th>
                                        <td id="discount-amount">Rs 0.00</td>
                                    </tr>
                                    <tr class="table-success">
                                        <th>Final Price:</th>
                                        <td id="final-price">Rs 0.00</td>
                                    </tr>
                                </table>
                                <div class="alert alert-info mb-0" id="discount-info">
                                    <i class="align-middle" data-feather="info"></i>
                                    Select a product and set discount to see the calculation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Add Product to Flash Deal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const discountTypeSelect = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');
        const discountSymbol = document.getElementById('discount-symbol');
        const discountHint = document.getElementById('discount-hint');
        const regularPriceEl = document.getElementById('regular-price');
        const discountAmountEl = document.getElementById('discount-amount');
        const finalPriceEl = document.getElementById('final-price');
        const discountInfoEl = document.getElementById('discount-info');

        // Update the discount symbol and hint based on discount type
        discountTypeSelect.addEventListener('change', function() {
            updateDiscountUI();
            calculateDiscount();
        });

        // Calculate discount when product or discount value changes
        productSelect.addEventListener('change', calculateDiscount);
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
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            if (selectedOption && selectedOption.value) {
                const productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
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

                regularPriceEl.textContent = 'Rs ' + productPrice.toFixed(2);
                discountAmountEl.textContent = 'Rs ' + discountAmount.toFixed(2);
                finalPriceEl.textContent = 'Rs ' + finalPrice.toFixed(2);

                discountInfoEl.style.display = 'none';
            } else {
                regularPriceEl.textContent = 'Rs 0.00';
                discountAmountEl.textContent = 'Rs 0.00';
                finalPriceEl.textContent = 'Rs 0.00';

                discountInfoEl.style.display = 'block';
            }
        }

        // Initialize UI
        updateDiscountUI();
    });
</script>
@endsection
