@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Tax Settings')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Tax Settings</h5>
                <div>
                    @if($settings->tax_enable)
                        <span class="badge bg-success">Tax System Enabled</span>
                    @else
                        <span class="badge bg-secondary">Tax System Disabled</span>
                    @endif
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary ms-2">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Settings
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

            @if(session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-message">{{ session('success') }}</div>
                </div>
            @endif

            <form action="{{ route('admin.settings.tax') }}" method="POST">
                @csrf

                <!-- Tax Configuration -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="percent"></i> Tax Configuration
                    </h6>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="tax_enable" name="tax_enable"
                                value="1" {{ old('tax_enable', $settings->tax_enable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tax_enable">
                                <strong>Enable Tax System</strong>
                            </label>
                        </div>
                        <small class="text-muted">Enable or disable tax calculations for all products and orders.</small>

                        <div class="mt-2">
                            <div id="taxStatusMessage" class="small">
                                @if($settings->tax_enable)
                                    <span class="text-success"><i class="align-middle" data-feather="check-circle" style="width: 16px; height: 16px;"></i> Tax system is currently enabled</span>
                                @else
                                    <span class="text-muted"><i class="align-middle" data-feather="x-circle" style="width: 16px; height: 16px;"></i> Tax system is currently disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div id="taxSettings" style="{{ old('tax_enable', $settings->tax_enable) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tax_rate" class="form-label">Default Tax Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                            value="{{ old('tax_rate', $settings->tax_rate ?? 13.00) }}"
                                            placeholder="13.00" step="0.01" min="0" max="100" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter the default tax percentage (e.g., 13 for 13% VAT)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tax Type Examples</label>
                                    <div class="text-muted small">
                                        <div><strong>Nepal:</strong> 13% VAT</div>
                                        <div><strong>India:</strong> 5-28% GST</div>
                                        <div><strong>US:</strong> 0-10% Sales Tax</div>
                                        <div><strong>UK:</strong> 20% VAT</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tax_shipping" name="tax_shipping"
                                            value="1" {{ old('tax_shipping', $settings->tax_shipping) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tax_shipping">
                                            Apply tax to shipping charges
                                        </label>
                                    </div>
                                    <small class="text-muted">When enabled, tax will be calculated on shipping costs as well.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tax_inclusive_pricing" name="tax_inclusive_pricing"
                                            value="1" {{ old('tax_inclusive_pricing', $settings->tax_inclusive_pricing) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tax_inclusive_pricing">
                                            Tax-inclusive pricing
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        When enabled, product prices include tax. When disabled, tax is added on top of product prices.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Calculation Examples -->
                <div class="mb-4" id="taxExamples" style="{{ old('tax_enable', $settings->tax_enable) ? '' : 'display: none;' }}">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="calculator"></i> Tax Calculation Examples
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Tax-Exclusive Pricing</h6>
                                    <div class="small text-muted">
                                        <div>Product Price: Rs. 100.00</div>
                                        <div>Tax (<span id="example-tax-rate-1">{{ $settings->tax_rate ?: 13 }}</span>%): Rs. <span id="example-tax-amount-1">{{ number_format((($settings->tax_rate ?: 13) / 100) * 100, 2) }}</span></div>
                                        <div class="fw-bold">Total: Rs. <span id="example-total-1">{{ number_format(100 + (($settings->tax_rate ?: 13) / 100) * 100, 2) }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Tax-Inclusive Pricing</h6>
                                    <div class="small text-muted">
                                        <div>Display Price: Rs. 100.00</div>
                                        <div>Product Price: Rs. <span id="example-product-price-2">{{ number_format(100 / (1 + (($settings->tax_rate ?: 13) / 100)), 2) }}</span></div>
                                        <div>Tax (<span id="example-tax-rate-2">{{ $settings->tax_rate ?: 13 }}</span>%): Rs. <span id="example-tax-amount-2">{{ number_format(100 - (100 / (1 + (($settings->tax_rate ?: 13) / 100))), 2) }}</span></div>
                                        <div class="fw-bold">Total: Rs. 100.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Tax Status -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="info"></i> Current Tax Status
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Tax Configuration Summary</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Tax System:</td>
                                            <td>
                                                @if($settings->tax_enable)
                                                    <span class="badge bg-success">Enabled</span>
                                                @else
                                                    <span class="badge bg-secondary">Disabled</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Current Rate:</td>
                                            <td>{{ $settings->tax_rate ?? 13 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Tax on Shipping:</td>
                                            <td>
                                                @if($settings->tax_shipping)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-muted">No</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pricing Type:</td>
                                            <td>
                                                @if($settings->tax_inclusive_pricing)
                                                    <span class="text-info">Tax-Inclusive</span>
                                                @else
                                                    <span class="text-info">Tax-Exclusive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Impact on Orders</h6>
                                    <div class="small text-muted">
                                        @if($settings->tax_enable)
                                            <div class="mb-2">
                                                <i class="align-middle text-success" data-feather="check"></i>
                                                Tax will be calculated on all new orders
                                            </div>
                                            <div class="mb-2">
                                                <i class="align-middle text-success" data-feather="check"></i>
                                                Cart totals will include tax calculations
                                            </div>
                                            <div class="mb-2">
                                                <i class="align-middle text-success" data-feather="check"></i>
                                                Order summaries will display tax breakdown
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                <i class="align-middle text-muted" data-feather="x"></i>
                                                No tax will be applied to new orders
                                            </div>
                                            <div class="mb-2">
                                                <i class="align-middle text-muted" data-feather="x"></i>
                                                Cart calculations will exclude tax
                                            </div>
                                            <div class="mb-2">
                                                <i class="align-middle text-muted" data-feather="x"></i>
                                                Tax amount will show as Rs. 0.00
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Information -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="help-circle"></i> Important Information
                    </h6>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="align-middle" data-feather="info"></i> Important Notes:</h6>
                        <ul class="mb-0">
                            <li><strong>Tax Rate:</strong> This is the default tax rate applied to all products. Individual products can have their own tax rates if needed.</li>
                            <li><strong>Tax-Inclusive vs Tax-Exclusive:</strong> Choose based on your local business practices and legal requirements.</li>
                            <li><strong>Shipping Tax:</strong> Some jurisdictions require tax on shipping, while others don't.</li>
                            <li><strong>Compliance:</strong> Ensure your tax settings comply with local tax laws and regulations.</li>
                            <li><strong>Disabling Tax:</strong> When disabled, all tax calculations will return Rs. 0.00 but your settings will be preserved.</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="align-middle" data-feather="alert-triangle"></i> Legal Disclaimer:</h6>
                        <p class="mb-0">
                            These tax settings are for basic tax calculations only. For complex tax requirements,
                            consult with a tax professional or integrate with specialized tax calculation services.
                        </p>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save Tax Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle tax settings visibility
    document.getElementById('tax_enable').addEventListener('change', function() {
        const taxSettings = document.getElementById('taxSettings');
        const taxExamples = document.getElementById('taxExamples');
        const statusMessage = document.getElementById('taxStatusMessage');
        const taxRateInput = document.getElementById('tax_rate');

        if (this.checked) {
            taxSettings.style.display = 'block';
            taxExamples.style.display = 'block';
            statusMessage.innerHTML = '<span class="text-success"><i class="align-middle" data-feather="check-circle" style="width: 16px; height: 16px;"></i> Tax system will be enabled</span>';
            taxRateInput.setAttribute('required', 'required');
        } else {
            taxSettings.style.display = 'none';
            taxExamples.style.display = 'none';
            statusMessage.innerHTML = '<span class="text-muted"><i class="align-middle" data-feather="x-circle" style="width: 16px; height: 16px;"></i> Tax system will be disabled</span>';
            taxRateInput.removeAttribute('required');
        }

        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Update tax calculation examples when tax rate changes
    document.getElementById('tax_rate').addEventListener('input', function() {
        const taxRate = parseFloat(this.value) || 0;

        // Tax-exclusive example
        const taxAmount1 = (taxRate / 100) * 100;
        const total1 = 100 + taxAmount1;

        document.getElementById('example-tax-rate-1').textContent = taxRate;
        document.getElementById('example-tax-amount-1').textContent = taxAmount1.toFixed(2);
        document.getElementById('example-total-1').textContent = total1.toFixed(2);

        // Tax-inclusive example
        const productPrice2 = 100 / (1 + (taxRate / 100));
        const taxAmount2 = 100 - productPrice2;

        document.getElementById('example-tax-rate-2').textContent = taxRate;
        document.getElementById('example-product-price-2').textContent = productPrice2.toFixed(2);
        document.getElementById('example-tax-amount-2').textContent = taxAmount2.toFixed(2);
    });
</script>
@endsection
