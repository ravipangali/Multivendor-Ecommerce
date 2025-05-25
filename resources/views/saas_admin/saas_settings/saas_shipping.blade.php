@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Shipping Settings')

@section('styles')
<style>
    .settings-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .settings-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .settings-nav {
        position: sticky;
        top: 1rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .settings-nav .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
    }
    .settings-nav .list-group-item.active {
        background-color: #3b7ddd;
        border-color: #3b7ddd;
    }
    .settings-nav .list-group-item i {
        margin-right: 10px;
    }
    .form-section-title {
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
        color: #3b7ddd;
    }
    .form-section-title:first-of-type {
        margin-top: 0;
    }
    .shipping-method-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
    }
    .shipping-method-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .shipping-method-header {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .shipping-method-body {
        padding: 1.5rem;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .shipping-method-body.active {
        display: block;
        opacity: 1;
    }
    .shipping-logo {
        height: 40px;
        width: auto;
        margin-right: 15px;
        object-fit: contain;
    }
    .shipping-logo-fallback {
        height: 40px;
        width: 100px;
        margin-right: 15px;
        background-color: #3b7ddd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
    }
    .form-switch {
        padding-left: 2.5em;
    }
    .form-switch .form-check-input {
        width: 2em;
        margin-left: -2.5em;
        height: 1em;
    }
    .shipping-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .zone-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .zone-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .zone-card-header {
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .zone-card-body {
        padding: 15px;
    }
    .add-zone-btn {
        margin-bottom: 20px;
    }
    .shipping-info-box {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 2rem;
        border: 1px solid #dee2e6;
    }
    .shipping-info-box h6 {
        margin-bottom: 1rem;
        color: #3b7ddd;
    }
    #seller-shipping-options {
        transition: opacity 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="row">
        <!-- Settings navigation -->
        <div class="col-md-3 mb-4">
            <div class="settings-nav">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action">
                        <i data-feather="settings" class="feather-sm"></i> General
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="list-group-item list-group-item-action">
                        <i data-feather="mail" class="feather-sm"></i> Email
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="list-group-item list-group-item-action">
                        <i data-feather="credit-card" class="feather-sm"></i> Payment
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="list-group-item list-group-item-action active">
                        <i data-feather="truck" class="feather-sm"></i> Shipping
                    </a>
                </div>
            </div>
        </div>

        <!-- Settings content -->
        <div class="col-md-9">
            <div class="card settings-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Shipping Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.shipping') }}" method="POST" id="shippingSettingsForm">
                        @csrf

                        <!-- Shipping Methods Section -->
                        <h5 class="form-section-title">
                            <i data-feather="truck" class="feather-sm me-1"></i> Shipping Methods
                        </h5>
                        <p class="shipping-description">
                            Configure the shipping methods that will be available for customers during checkout.
                        </p>

                        <!-- Free Shipping Method -->
                        <div class="shipping-method-card">
                            <div class="shipping-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="shipping-logo-fallback">Free Shipping</div>
                                    <h6 class="mb-0">Free Shipping</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input shipping-method-toggle" type="checkbox" id="shipping_enable_free" name="shipping_enable_free" value="1"
                                            {{ old('shipping_enable_free', $settingsArray['shipping_enable_free'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping_enable_free">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="free-shipping-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="shipping-method-body" id="free-shipping-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_free_min_amount" class="form-label">Minimum Order Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('shipping_free_min_amount') is-invalid @enderror"
                                                   id="shipping_free_min_amount" name="shipping_free_min_amount"
                                                   value="{{ old('shipping_free_min_amount', $settingsArray['shipping_free_min_amount'] ?? '50') }}"
                                                   {{ old('shipping_enable_free', $settingsArray['shipping_enable_free'] ?? 0) == 1 ? 'required' : '' }}>
                                        </div>
                                        <small class="form-text text-muted">Orders above this amount will qualify for free shipping. Set to 0 for unconditional free shipping.</small>
                                        <div class="invalid-feedback">
                                            Please provide a minimum order amount for free shipping.
                                        </div>
                                        @error('shipping_free_min_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flat Rate Shipping Method -->
                        <div class="shipping-method-card">
                            <div class="shipping-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="shipping-logo-fallback">Flat Rate</div>
                                    <h6 class="mb-0">Flat Rate Shipping</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input shipping-method-toggle" type="checkbox" id="shipping_flat_rate_enable" name="shipping_flat_rate_enable" value="1"
                                            {{ old('shipping_flat_rate_enable', $settingsArray['shipping_flat_rate_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping_flat_rate_enable">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="flat-rate-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="shipping-method-body" id="flat-rate-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_flat_rate_cost" class="form-label">Shipping Cost <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('shipping_flat_rate_cost') is-invalid @enderror"
                                                   id="shipping_flat_rate_cost" name="shipping_flat_rate_cost"
                                                   value="{{ old('shipping_flat_rate_cost', $settingsArray['shipping_flat_rate_cost'] ?? '5') }}"
                                                   {{ old('shipping_flat_rate_enable', $settingsArray['shipping_flat_rate_enable'] ?? 0) == 1 ? 'required' : '' }}>
                                        </div>
                                        <small class="form-text text-muted">Fixed shipping cost applied to all orders regardless of size or weight.</small>
                                        <div class="invalid-feedback">
                                            Please provide a flat rate shipping cost.
                                        </div>
                                        @error('shipping_flat_rate_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Local Pickup Shipping Method -->
                        <div class="shipping-method-card">
                            <div class="shipping-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="shipping-logo-fallback">Local Pickup</div>
                                    <h6 class="mb-0">Local Pickup</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input shipping-method-toggle" type="checkbox" id="shipping_enable_local_pickup" name="shipping_enable_local_pickup" value="1"
                                            {{ old('shipping_enable_local_pickup', $settingsArray['shipping_enable_local_pickup'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping_enable_local_pickup">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="local-pickup-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="shipping-method-body" id="local-pickup-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_local_pickup_cost" class="form-label">Pickup Cost <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('shipping_local_pickup_cost') is-invalid @enderror"
                                                   id="shipping_local_pickup_cost" name="shipping_local_pickup_cost"
                                                   value="{{ old('shipping_local_pickup_cost', $settingsArray['shipping_local_pickup_cost'] ?? '0') }}"
                                                   {{ old('shipping_enable_local_pickup', $settingsArray['shipping_enable_local_pickup'] ?? 0) == 1 ? 'required' : '' }}>
                                        </div>
                                        <small class="form-text text-muted">Fee for local pickup. Set to 0 for free pickup.</small>
                                        <div class="invalid-feedback">
                                            Please provide a local pickup fee.
                                        </div>
                                        @error('shipping_local_pickup_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seller-Based Shipping Settings -->
                        <h5 class="form-section-title mt-4">
                            <i data-feather="layers" class="feather-sm me-1"></i> Seller Shipping Options
                        </h5>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="shipping_allow_seller_config" name="shipping_allow_seller_config" value="1"
                                {{ old('shipping_allow_seller_config', $settingsArray['shipping_allow_seller_config'] ?? 0) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="shipping_allow_seller_config">
                                Allow sellers to configure their own shipping methods and rates
                            </label>
                            <div class="form-text">If enabled, sellers can set up their own shipping rates independent of the global settings.</div>
                        </div>

                        <div class="mt-4" id="seller-shipping-options" style="{{ old('shipping_allow_seller_config', $settingsArray['shipping_allow_seller_config'] ?? 0) == 1 ? '' : 'display: none;' }}">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="shipping_seller_free_enable" name="shipping_seller_free_enable" value="1"
                                    {{ old('shipping_seller_free_enable', $settingsArray['shipping_seller_free_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="shipping_seller_free_enable">
                                    Allow sellers to offer free shipping
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="shipping_seller_flat_rate_enable" name="shipping_seller_flat_rate_enable" value="1"
                                    {{ old('shipping_seller_flat_rate_enable', $settingsArray['shipping_seller_flat_rate_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="shipping_seller_flat_rate_enable">
                                    Allow sellers to set flat rate shipping
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="shipping_seller_zone_based_enable" name="shipping_seller_zone_based_enable" value="1"
                                    {{ old('shipping_seller_zone_based_enable', $settingsArray['shipping_seller_zone_based_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="shipping_seller_zone_based_enable">
                                    Allow sellers to create zone-based shipping
                                </label>
                            </div>
                        </div>

                        <div class="shipping-info-box">
                            <h6>Shipping Policy Information</h6>
                            <div class="mb-3">
                                <label for="shipping_policy_info" class="form-label">Shipping Policy</label>
                                <textarea class="form-control" id="shipping_policy_info" name="shipping_policy_info" rows="4">{{ old('shipping_policy_info', $settingsArray['shipping_policy_info'] ?? 'Standard shipping takes 3-5 business days. Expedited shipping options may be available at checkout. International shipping may require additional time for customs clearance.') }}</textarea>
                                <small class="form-text text-muted">This information will be displayed on the checkout page and in the shipping policy section.</small>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="feather-sm me-1"></i> Save Settings
                            </button>
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
        // Initialize Feather icons
        feather.replace();

        // Toggle shipping method details
        const toggleButtons = document.querySelectorAll('.toggle-method-details');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);

                if (targetElement.classList.contains('active')) {
                    targetElement.style.opacity = '0';
                    this.querySelector('svg').style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        targetElement.classList.remove('active');
                    }, 300);
                } else {
                    targetElement.classList.add('active');
                    this.querySelector('svg').style.transform = 'rotate(180deg)';
                    setTimeout(() => {
                        targetElement.style.opacity = '1';
                    }, 10);
                }
            });
        });

        // Show active shipping method details on page load
        const activeToggles = document.querySelectorAll('.shipping-method-toggle:checked');
        activeToggles.forEach(toggle => {
            const methodId = toggle.id.replace('shipping_', '').replace('_enable', '');
            const detailsElement = document.getElementById(methodId + '-details');
            if (detailsElement) {
                detailsElement.classList.add('active');
                const button = document.querySelector(`[data-target="${methodId}-details"]`);
                if (button) {
                    button.querySelector('svg').style.transform = 'rotate(180deg)';
                }
            }
        });

        // Toggle seller shipping options with animation
        const sellerConfigToggle = document.getElementById('shipping_allow_seller_config');
        const sellerOptions = document.getElementById('seller-shipping-options');

        sellerConfigToggle.addEventListener('change', function() {
            if (this.checked) {
                sellerOptions.style.display = 'block';
                sellerOptions.style.opacity = '0';
                setTimeout(() => {
                    sellerOptions.style.opacity = '1';
                }, 10);

                // Show toast notification
                if (typeof showToast === 'function') {
                    showToast('Seller Shipping', 'Seller shipping options enabled', 'info');
                }
            } else {
                sellerOptions.style.opacity = '0';
                setTimeout(() => {
                    sellerOptions.style.display = 'none';
                }, 300);

                // Show toast notification
                if (typeof showToast === 'function') {
                    showToast('Seller Shipping', 'Seller shipping options disabled', 'info');
                }
            }
        });

        // Required attributes toggling with improved UX
        const methodToggles = document.querySelectorAll('.shipping-method-toggle');
        methodToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const methodId = this.id.replace('shipping_', '').replace('_enable', '');
                const detailsElement = document.getElementById(methodId + '-details');
                const methodCard = this.closest('.shipping-method-card');

                if (detailsElement) {
                    // Animate the details section
                    if (this.checked) {
                        // If toggle is checked but details are not visible, show them
                        if (!detailsElement.classList.contains('active')) {
                            const button = document.querySelector(`[data-target="${methodId}-details"]`);
                            if (button) {
                                button.querySelector('svg').style.transform = 'rotate(180deg)';
                                detailsElement.classList.add('active');
                            }
                        }

                        // Highlight the card
                        methodCard.style.borderColor = '#3b7ddd';
                        setTimeout(() => {
                            methodCard.style.borderColor = '';
                        }, 1000);
                    }

                    // Handle required fields
                    const requiredInputs = detailsElement.querySelectorAll('input, textarea');
                    requiredInputs.forEach(input => {
                        if (input.closest('.form-group')?.querySelector('label span.text-danger') ||
                            input.hasAttribute('required-if-enabled')) {
                            if (this.checked) {
                                input.setAttribute('required', '');
                                input.classList.add('required-field');
                            } else {
                                input.removeAttribute('required');
                                input.classList.remove('required-field');

                                // Clear validation errors when disabling
                                input.classList.remove('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.style.display = 'none';
                                }
                            }
                        }
                    });
                }

                // Show toast notification
                if (typeof showToast === 'function') {
                    const methodName = methodCard.querySelector('h6').textContent;
                    if (this.checked) {
                        showToast('Shipping Method Enabled', `${methodName} has been enabled`, 'success');
                    } else {
                        showToast('Shipping Method Disabled', `${methodName} has been disabled`, 'info');
                    }
                }
            });
        });

        // Form validation
        const shippingSettingsForm = document.getElementById('shippingSettingsForm');
        if (shippingSettingsForm) {
            shippingSettingsForm.addEventListener('submit', function(event) {
                let hasErrors = false;

                // Check required fields for enabled shipping methods
                methodToggles.forEach(toggle => {
                    if (toggle.checked) {
                        const methodId = toggle.id.replace('shipping_', '').replace('_enable', '');
                        const detailsElement = document.getElementById(methodId + '-details');

                        if (detailsElement) {
                            const requiredInputs = detailsElement.querySelectorAll('[required]');
                            requiredInputs.forEach(input => {
                                if (!input.value.trim()) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.style.display = 'block';
                                    }
                                    hasErrors = true;
                                }
                            });
                        }
                    }
                });

                if (hasErrors) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }

                    if (typeof showToast === 'function') {
                        showToast('Validation Error', 'Please fill in all required fields', 'error');
                    }
                } else {
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('Success', 'Shipping settings are being saved...', 'success');
                    }
                }
            });

            // Clear validation state when input changes
            shippingSettingsForm.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection
