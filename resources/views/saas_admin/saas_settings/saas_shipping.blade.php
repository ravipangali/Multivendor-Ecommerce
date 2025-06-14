@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Shipping Settings')

@section('styles')
<style>
    .settings-nav {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        margin-bottom: 2rem;
    }
    .settings-nav .nav-link {
        color: #6c757d;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .settings-nav .nav-link:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .settings-nav .nav-link:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .settings-nav .nav-link.active {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        color: white;
    }
    .settings-nav .nav-link:hover:not(.active) {
        background: #f8f9fa;
        color: #495057;
    }
    .settings-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        border: none;
        overflow: hidden;
    }
    .settings-card .card-header {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        color: white;
        border: none;
        padding: 1.5rem;
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }
    .form-section h5 {
        color: #495057;
        margin-bottom: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .shipping-method {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #d4edda;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #e0e6ed;
        color: #6c757d;
        font-weight: 600;
    }
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    .form-switch {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }
    .seller-options {
        background: linear-gradient(135deg, #cdfff8 0%, #e1edff 100%);
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e1edff;
        transition: all 0.3s ease;
    }
    .seller-options.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Shipping Settings</h5>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Settings
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

            <form action="{{ route('admin.settings.shipping') }}" method="POST">
                @csrf

                <!-- Free Shipping -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="gift"></i> Free Shipping
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shipping_enable_free" name="shipping_enable_free"
                                value="1" {{ old('shipping_enable_free', $settings->shipping_enable_free) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_enable_free">
                                <strong>Enable Free Shipping</strong>
                            </label>
                        </div>
                        <small class="text-muted">Offer free shipping to customers</small>
                    </div>

                    <div id="freeShippingSettings" style="{{ old('shipping_enable_free', $settings->shipping_enable_free) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_free_min_amount" class="form-label">Minimum Order Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_free_min_amount" name="shipping_free_min_amount"
                                            value="{{ old('shipping_free_min_amount', $settings->shipping_free_min_amount) }}"
                                            placeholder="50" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Minimum order value for free shipping (leave empty for no minimum)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flat Rate Shipping -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="package"></i> Flat Rate Shipping
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shipping_flat_rate_enable" name="shipping_flat_rate_enable"
                                value="1" {{ old('shipping_flat_rate_enable', $settings->shipping_flat_rate_enable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_flat_rate_enable">
                                <strong>Enable Flat Rate Shipping</strong>
                            </label>
                        </div>
                        <small class="text-muted">Charge a fixed amount for all orders</small>
                    </div>

                    <div id="flatRateSettings" style="{{ old('shipping_flat_rate_enable', $settings->shipping_flat_rate_enable) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_flat_rate_cost" class="form-label">Flat Rate Cost <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_flat_rate_cost" name="shipping_flat_rate_cost"
                                            value="{{ old('shipping_flat_rate_cost', $settings->shipping_flat_rate_cost) }}"
                                            placeholder="10" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Fixed shipping cost for all orders</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Local Pickup -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="map-pin"></i> Local Pickup
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shipping_enable_local_pickup" name="shipping_enable_local_pickup"
                                value="1" {{ old('shipping_enable_local_pickup', $settings->shipping_enable_local_pickup) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_enable_local_pickup">
                                <strong>Enable Local Pickup</strong>
                            </label>
                        </div>
                        <small class="text-muted">Allow customers to pick up orders from your location</small>
                    </div>

                    <div id="localPickupSettings" style="{{ old('shipping_enable_local_pickup', $settings->shipping_enable_local_pickup) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_local_pickup_cost" class="form-label">Local Pickup Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_local_pickup_cost" name="shipping_local_pickup_cost"
                                            value="{{ old('shipping_local_pickup_cost', $settings->shipping_local_pickup_cost) }}"
                                            placeholder="0" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Cost for local pickup (usually 0)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Zone-Based Shipping -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="map"></i> Zone-Based Shipping
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shipping_zone_based_enable" name="shipping_zone_based_enable"
                                value="1" {{ old('shipping_zone_based_enable', $settings->shipping_zone_based_enable) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_zone_based_enable">
                                <strong>Enable Zone-Based Shipping</strong>
                            </label>
                        </div>
                        <small class="text-muted">Different shipping rates based on delivery zones</small>
                    </div>

                    <div id="zoneBasedSettings" style="{{ old('shipping_zone_based_enable', $settings->shipping_zone_based_enable) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shipping_local_rate" class="form-label">Local Zone Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_local_rate" name="shipping_local_rate"
                                            value="{{ old('shipping_local_rate', $settings->shipping_local_rate) }}"
                                            placeholder="5" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Within city/local area</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shipping_regional_rate" class="form-label">Regional Zone Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_regional_rate" name="shipping_regional_rate"
                                            value="{{ old('shipping_regional_rate', $settings->shipping_regional_rate) }}"
                                            placeholder="10" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Within state/region</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="shipping_remote_rate" class="form-label">Remote Zone Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="shipping_remote_rate" name="shipping_remote_rate"
                                            value="{{ old('shipping_remote_rate', $settings->shipping_remote_rate) }}"
                                            placeholder="20" step="0.01" min="0">
                                    </div>
                                    <small class="text-muted">Remote/distant areas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weight-Based Shipping -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="archive"></i> Weight-Based Shipping
                    </h6>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="shipping_weight_rate" class="form-label">Rate per Weight Unit</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                    <input type="number" class="form-control" id="shipping_weight_rate" name="shipping_weight_rate"
                                        value="{{ old('shipping_weight_rate', $settings->shipping_weight_rate) }}"
                                        placeholder="1.5" step="0.01" min="0">
                                    <span class="input-group-text">/kg</span>
                                </div>
                                <small class="text-muted">Cost per kilogram</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="shipping_min_weight" class="form-label">Minimum Weight</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="shipping_min_weight" name="shipping_min_weight"
                                        value="{{ old('shipping_min_weight', $settings->shipping_min_weight) }}"
                                        placeholder="0.1" step="0.01" min="0">
                                    <span class="input-group-text">kg</span>
                                </div>
                                <small class="text-muted">Minimum chargeable weight</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="shipping_max_weight" class="form-label">Maximum Weight</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="shipping_max_weight" name="shipping_max_weight"
                                        value="{{ old('shipping_max_weight', $settings->shipping_max_weight) }}"
                                        placeholder="50" step="0.01" min="0">
                                    <span class="input-group-text">kg</span>
                                </div>
                                <small class="text-muted">Maximum allowed weight</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seller Shipping Settings -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="users"></i> Seller Shipping Configuration
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="shipping_allow_seller_config" name="shipping_allow_seller_config"
                                value="1" {{ old('shipping_allow_seller_config', $settings->shipping_allow_seller_config) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_allow_seller_config">
                                <strong>Allow Sellers to Configure Shipping</strong>
                            </label>
                        </div>
                        <small class="text-muted">Let individual sellers set their own shipping rates</small>
                    </div>

                    <div id="sellerShippingSettings" style="{{ old('shipping_allow_seller_config', $settings->shipping_allow_seller_config) ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="shipping_seller_free_enable" name="shipping_seller_free_enable"
                                        value="1" {{ old('shipping_seller_free_enable', $settings->shipping_seller_free_enable) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shipping_seller_free_enable">
                                        Free Shipping Option
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="shipping_seller_flat_rate_enable" name="shipping_seller_flat_rate_enable"
                                        value="1" {{ old('shipping_seller_flat_rate_enable', $settings->shipping_seller_flat_rate_enable) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shipping_seller_flat_rate_enable">
                                        Flat Rate Option
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="shipping_seller_zone_based_enable" name="shipping_seller_zone_based_enable"
                                        value="1" {{ old('shipping_seller_zone_based_enable', $settings->shipping_seller_zone_based_enable) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shipping_seller_zone_based_enable">
                                        Zone-Based Option
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Policy -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="file-text"></i> Shipping Policy
                    </h6>

                    <div class="mb-3">
                        <label for="shipping_policy_info" class="form-label">Shipping Policy Information</label>
                        <textarea class="form-control" id="shipping_policy_info" name="shipping_policy_info" rows="6">{{ old('shipping_policy_info', $settings->shipping_policy_info) }}</textarea>
                        <small class="text-muted">Detailed shipping policy and terms (displayed to customers)</small>
                    </div>
                </div>

                <!-- Shipping Calculator Preview -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="calculator"></i> Shipping Cost Calculator
                    </h6>

                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Order Value</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                            <input type="number" class="form-control" id="calc_order_value" value="25" placeholder="25">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" id="calc_weight" value="1" placeholder="1" step="0.1">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Zone</label>
                                        <select class="form-select" id="calc_zone">
                                            <option value="local">Local</option>
                                            <option value="regional">Regional</option>
                                            <option value="remote">Remote</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="shipping-calc-result">
                                        <h6>Shipping Cost Breakdown:</h6>
                                        <div id="shipping_calc_result" class="text-muted">
                                            <div>Calculating...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save Shipping Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle shipping settings sections
    document.getElementById('shipping_enable_free').addEventListener('change', function() {
        document.getElementById('freeShippingSettings').style.display = this.checked ? 'block' : 'none';
        updateShippingCalculator();
    });

    document.getElementById('shipping_flat_rate_enable').addEventListener('change', function() {
        document.getElementById('flatRateSettings').style.display = this.checked ? 'block' : 'none';
        updateShippingCalculator();
    });

    document.getElementById('shipping_enable_local_pickup').addEventListener('change', function() {
        document.getElementById('localPickupSettings').style.display = this.checked ? 'block' : 'none';
        updateShippingCalculator();
    });

    document.getElementById('shipping_zone_based_enable').addEventListener('change', function() {
        document.getElementById('zoneBasedSettings').style.display = this.checked ? 'block' : 'none';
        updateShippingCalculator();
    });

    document.getElementById('shipping_allow_seller_config').addEventListener('change', function() {
        document.getElementById('sellerShippingSettings').style.display = this.checked ? 'block' : 'none';
    });

    // Update shipping calculator
    function updateShippingCalculator() {
        const orderValue = parseFloat(document.getElementById('calc_order_value').value) || 0;
        const weight = parseFloat(document.getElementById('calc_weight').value) || 0;
        const zone = document.getElementById('calc_zone').value;
        const currencySymbol = '{{ $settings->site_currency_symbol ?: "$" }}';

        let results = [];

        // Free shipping
        if (document.getElementById('shipping_enable_free').checked) {
            const minAmount = parseFloat(document.getElementById('shipping_free_min_amount').value) || 0;
            if (minAmount === 0 || orderValue >= minAmount) {
                results.push('<div class="badge bg-success">Free Shipping: ' + currencySymbol + '0.00</div>');
            }
        }

        // Flat rate
        if (document.getElementById('shipping_flat_rate_enable').checked) {
            const flatRate = parseFloat(document.getElementById('shipping_flat_rate_cost').value) || 0;
            results.push('<div>Flat Rate: ' + currencySymbol + flatRate.toFixed(2) + '</div>');
        }

        // Zone-based
        if (document.getElementById('shipping_zone_based_enable').checked) {
            let zoneRate = 0;
            if (zone === 'local') {
                zoneRate = parseFloat(document.getElementById('shipping_local_rate').value) || 0;
            } else if (zone === 'regional') {
                zoneRate = parseFloat(document.getElementById('shipping_regional_rate').value) || 0;
            } else if (zone === 'remote') {
                zoneRate = parseFloat(document.getElementById('shipping_remote_rate').value) || 0;
            }
            results.push('<div>Zone-based (' + zone + '): ' + currencySymbol + zoneRate.toFixed(2) + '</div>');
        }

        // Weight-based
        const weightRate = parseFloat(document.getElementById('shipping_weight_rate').value) || 0;
        if (weightRate > 0) {
            const weightCost = weight * weightRate;
            results.push('<div>Weight-based (' + weight + 'kg): ' + currencySymbol + weightCost.toFixed(2) + '</div>');
        }

        // Local pickup
        if (document.getElementById('shipping_enable_local_pickup').checked) {
            const pickupCost = parseFloat(document.getElementById('shipping_local_pickup_cost').value) || 0;
            results.push('<div>Local Pickup: ' + currencySymbol + pickupCost.toFixed(2) + '</div>');
        }

        if (results.length === 0) {
            results.push('<div class="text-muted">No shipping methods configured</div>');
        }

        document.getElementById('shipping_calc_result').innerHTML = results.join('');
    }

    // Add event listeners for calculator inputs
    document.getElementById('calc_order_value').addEventListener('input', updateShippingCalculator);
    document.getElementById('calc_weight').addEventListener('input', updateShippingCalculator);
    document.getElementById('calc_zone').addEventListener('change', updateShippingCalculator);

    // Add event listeners for shipping rates
    ['shipping_free_min_amount', 'shipping_flat_rate_cost', 'shipping_local_rate',
     'shipping_regional_rate', 'shipping_remote_rate', 'shipping_weight_rate',
     'shipping_local_pickup_cost'].forEach(function(id) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updateShippingCalculator);
        }
    });

    // Initialize calculator on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateShippingCalculator();
    });
</script>
@endsection
