@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Payment Settings')

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
    .payment-method-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
    }
    .payment-method-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .payment-method-header {
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .payment-method-body {
        padding: 1.5rem;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .payment-method-body.active {
        display: block;
        opacity: 1;
    }
    .toggle-method-details {
        background: transparent;
        border: none;
        color: #3b7ddd;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .toggle-method-details.collapsed {
        transform: rotate(180deg);
    }
    .payment-logo {
        height: 40px;
        width: auto;
        margin-right: 15px;
        object-fit: contain;
    }
    .payment-logo-fallback {
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
    .password-toggle {
        cursor: pointer;
    }
    .payment-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .withdrawal-rules {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-top: 2rem;
        border: 1px solid #dee2e6;
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
                    <a href="{{ route('admin.settings.payment') }}" class="list-group-item list-group-item-action active">
                        <i data-feather="credit-card" class="feather-sm"></i> Payment
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="list-group-item list-group-item-action">
                        <i data-feather="truck" class="feather-sm"></i> Shipping
                    </a>
                </div>
            </div>
        </div>

        <!-- Settings content -->
        <div class="col-md-9">
            <div class="card settings-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Payment Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.payment') }}" method="POST" id="paymentSettingsForm">
                        @csrf

                        <!-- Payment Methods Section -->
                        <h5 class="form-section-title">
                            <i data-feather="credit-card" class="feather-sm me-1"></i> Payment Methods
                        </h5>
                        <p class="payment-description">
                            Configure the payment methods that will be available for customers during checkout.
                        </p>

                        <!-- Bank Transfer Payment Method -->
                        <div class="payment-method-card">
                            <div class="payment-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="payment-logo-fallback">Bank Transfer</div>
                                    <h6 class="mb-0">Bank Transfer</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input payment-method-toggle" type="checkbox" id="payment_bank_enable" name="payment_bank_enable" value="1"
                                            {{ old('payment_bank_enable', $settingsArray['payment_bank_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_bank_enable">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="bank-transfer-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="payment-method-body" id="bank-transfer-details">
                                <div class="mb-3">
                                    <label for="payment_bank_details" class="form-label">Bank Account Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('payment_bank_details') is-invalid @enderror"
                                            id="payment_bank_details" name="payment_bank_details" rows="4"
                                            {{ old('payment_bank_enable', $settingsArray['payment_bank_enable'] ?? 0) == 1 ? 'required' : '' }}>{{ old('payment_bank_details', $settingsArray['payment_bank_details'] ?? '') }}</textarea>
                                    <small class="form-text text-muted">Enter the bank account details that will be shown to customers for bank transfers. Include account number, bank name, branch, etc.</small>
                                    <div class="invalid-feedback">
                                        Please provide bank account details.
                                    </div>
                                    @error('payment_bank_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- eSewa Payment Method -->
                        <div class="payment-method-card">
                            <div class="payment-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="payment-logo-fallback">eSewa</div>
                                    <h6 class="mb-0">eSewa</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input payment-method-toggle" type="checkbox" id="payment_esewa_enable" name="payment_esewa_enable" value="1"
                                            {{ old('payment_esewa_enable', $settingsArray['payment_esewa_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_esewa_enable">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="esewa-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="payment-method-body" id="esewa-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_esewa_merchant_id" class="form-label">Merchant ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('payment_esewa_merchant_id') is-invalid @enderror"
                                               id="payment_esewa_merchant_id" name="payment_esewa_merchant_id"
                                               value="{{ old('payment_esewa_merchant_id', $settingsArray['payment_esewa_merchant_id'] ?? '') }}"
                                               {{ old('payment_esewa_enable', $settingsArray['payment_esewa_enable'] ?? 0) == 1 ? 'required' : '' }}>
                                        <div class="invalid-feedback">
                                            Please provide the eSewa merchant ID.
                                        </div>
                                        @error('payment_esewa_merchant_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_esewa_secret_key" class="form-label">Secret Key <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('payment_esewa_secret_key') is-invalid @enderror"
                                                   id="payment_esewa_secret_key" name="payment_esewa_secret_key"
                                                   value="{{ old('payment_esewa_secret_key', $settingsArray['payment_esewa_secret_key'] ?? '') }}"
                                                   {{ old('payment_esewa_enable', $settingsArray['payment_esewa_enable'] ?? 0) == 1 ? 'required' : '' }}>
                                            <span class="input-group-text password-toggle" data-target="payment_esewa_secret_key">
                                                <i data-feather="eye" class="feather-sm"></i>
                                            </span>
                                            <div class="invalid-feedback">
                                                Please provide the eSewa secret key.
                                            </div>
                                        </div>
                                        @error('payment_esewa_secret_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-text text-muted mb-3">
                                    Sign up for a eSewa account at <a href="https://esewa.com.np/" target="_blank">esewa.com.np</a> to get your API credentials.
                                </div>
                            </div>
                        </div>

                        <!-- Khalti Payment Method -->
                        <div class="payment-method-card">
                            <div class="payment-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="payment-logo-fallback">Khalti</div>
                                    <h6 class="mb-0">Khalti</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input payment-method-toggle" type="checkbox" id="payment_khalti_enable" name="payment_khalti_enable" value="1"
                                            {{ old('payment_khalti_enable', $settingsArray['payment_khalti_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_khalti_enable">Enable</label>
                                    </div>
                                    <button type="button" class="toggle-method-details" data-target="khalti-details">
                                        <i data-feather="chevron-down" class="feather-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="payment-method-body" id="khalti-details">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_khalti_public_key" class="form-label">Public Key <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('payment_khalti_public_key') is-invalid @enderror"
                                               id="payment_khalti_public_key" name="payment_khalti_public_key"
                                               value="{{ old('payment_khalti_public_key', $settingsArray['payment_khalti_public_key'] ?? '') }}"
                                               {{ old('payment_khalti_enable', $settingsArray['payment_khalti_enable'] ?? 0) == 1 ? 'required' : '' }}>
                                        <div class="invalid-feedback">
                                            Please provide the Khalti public key.
                                        </div>
                                        @error('payment_khalti_public_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_khalti_secret_key" class="form-label">Secret Key <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('payment_khalti_secret_key') is-invalid @enderror"
                                                   id="payment_khalti_secret_key" name="payment_khalti_secret_key"
                                                   value="{{ old('payment_khalti_secret_key', $settingsArray['payment_khalti_secret_key'] ?? '') }}"
                                                   {{ old('payment_khalti_enable', $settingsArray['payment_khalti_enable'] ?? 0) == 1 ? 'required' : '' }}>
                                            <span class="input-group-text password-toggle" data-target="payment_khalti_secret_key">
                                                <i data-feather="eye" class="feather-sm"></i>
                                            </span>
                                            <div class="invalid-feedback">
                                                Please provide the Khalti secret key.
                                            </div>
                                        </div>
                                        @error('payment_khalti_secret_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-text text-muted mb-3">
                                    Sign up for a Khalti account at <a href="https://khalti.com/" target="_blank">khalti.com</a> to get your API credentials.
                                </div>
                            </div>
                        </div>

                        <!-- Cash on Delivery Payment Method -->
                        <div class="payment-method-card">
                            <div class="payment-method-header">
                                <div class="d-flex align-items-center">
                                    <div class="payment-logo-fallback">COD</div>
                                    <h6 class="mb-0">Cash on Delivery</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" type="checkbox" id="payment_cod_enable" name="payment_cod_enable" value="1"
                                            {{ old('payment_cod_enable', $settingsArray['payment_cod_enable'] ?? 0) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="payment_cod_enable">Enable</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Rules Section -->
                        <h5 class="form-section-title mt-4">
                            <i data-feather="sliders" class="feather-sm me-1"></i> Payment Rules
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payment_gateway_transaction_fee" class="form-label">Gateway Transaction Fee (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100" class="form-control @error('payment_gateway_transaction_fee') is-invalid @enderror"
                                           id="payment_gateway_transaction_fee" name="payment_gateway_transaction_fee"
                                           value="{{ old('payment_gateway_transaction_fee', $settingsArray['payment_gateway_transaction_fee'] ?? '0') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="form-text text-muted">Transaction fee charged by payment gateways (will be deducted from seller's earnings)</small>
                                @error('payment_gateway_transaction_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="payment_min_withdrawal_amount" class="form-label">Minimum Withdrawal Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" class="form-control @error('payment_min_withdrawal_amount') is-invalid @enderror"
                                           id="payment_min_withdrawal_amount" name="payment_min_withdrawal_amount"
                                           value="{{ old('payment_min_withdrawal_amount', $settingsArray['payment_min_withdrawal_amount'] ?? '50') }}">
                                </div>
                                <small class="form-text text-muted">Minimum amount sellers must have in their balance to request a withdrawal</small>
                                @error('payment_min_withdrawal_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="withdrawal-rules">
                            <h6 class="mb-3">Withdrawal Rules</h6>
                            <ul class="mb-0">
                                <li>Seller balance must meet or exceed the minimum withdrawal amount</li>
                                <li>Payment processing may take 2-3 business days</li>
                                <li>Platform fees and transaction fees will be automatically deducted</li>
                                <li>Sellers must provide valid bank account information</li>
                                <li>All withdrawal requests are subject to review</li>
                            </ul>
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

        // Toggle payment method details
        const toggleButtons = document.querySelectorAll('.toggle-method-details');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);

                if (targetElement.classList.contains('active')) {
                    targetElement.style.opacity = '0';
                    this.classList.add('collapsed');
                    this.querySelector('svg').style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        targetElement.classList.remove('active');
                    }, 300);
                } else {
                    targetElement.classList.add('active');
                    this.classList.remove('collapsed');
                    this.querySelector('svg').style.transform = 'rotate(180deg)';
                    setTimeout(() => {
                        targetElement.style.opacity = '1';
                    }, 10);
                }
            });
        });

        // Show active payment method details on page load
        const activeToggles = document.querySelectorAll('.payment-method-toggle:checked');
        activeToggles.forEach(toggle => {
            const methodId = toggle.id.replace('payment_', '').replace('_enable', '');
            const detailsElement = document.getElementById(methodId + '-details');
            if (detailsElement) {
                detailsElement.classList.add('active');
                const button = document.querySelector(`[data-target="${methodId}-details"]`);
                if (button) {
                    button.classList.remove('collapsed');
                    button.querySelector('svg').style.transform = 'rotate(180deg)';
                }
            }
        });

        // Password toggle functionality
        const passwordToggles = document.querySelectorAll('.password-toggle');
        passwordToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordField = document.getElementById(targetId);

                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                // Toggle the eye icon
                const eyeIcon = this.querySelector('svg');
                if (type === 'text') {
                    eyeIcon.setAttribute('data-feather', 'eye-off');
                } else {
                    eyeIcon.setAttribute('data-feather', 'eye');
                }
                feather.replace();
            });
        });

        // Required attributes toggling with improved UX
        const methodToggles = document.querySelectorAll('.payment-method-toggle');
        methodToggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const methodId = this.id.replace('payment_', '').replace('_enable', '');
                const detailsElement = document.getElementById(methodId + '-details');
                const methodCard = this.closest('.payment-method-card');

                if (detailsElement) {
                    // Animate the details section
                    if (this.checked) {
                        // If toggle is checked but details are not visible, show them
                        if (!detailsElement.classList.contains('active')) {
                            const button = document.querySelector(`[data-target="${methodId}-details"]`);
                            if (button) {
                                button.classList.remove('collapsed');
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
                        if (input.hasAttribute('required-if-enabled') || input.closest('.form-group').querySelector('label span.text-danger')) {
                            if (this.checked) {
                                input.setAttribute('required', '');
                                input.classList.add('required-field');
                            } else {
                                input.removeAttribute('required');
                                input.classList.remove('required-field');

                                // Clear validation errors when disabling
                                input.classList.remove('is-invalid');
                                const feedback = input.closest('.form-group').querySelector('.invalid-feedback');
                                if (feedback) {
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
                        showToast('Payment Method Enabled', `${methodName} has been enabled`, 'success');
                    } else {
                        showToast('Payment Method Disabled', `${methodName} has been disabled`, 'info');
                    }
                }
            });
        });

        // Form validation
        const paymentSettingsForm = document.getElementById('paymentSettingsForm');
        if (paymentSettingsForm) {
            paymentSettingsForm.addEventListener('submit', function(event) {
                let hasErrors = false;

                // Check required fields for enabled payment methods
                methodToggles.forEach(toggle => {
                    if (toggle.checked) {
                        const methodId = toggle.id.replace('payment_', '').replace('_enable', '');
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
                }
            });

            // Clear validation state when input changes
            paymentSettingsForm.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const feedback = this.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'none';
                    }
                });
            });
        }

        // Save settings with AJAX (optional)
        const saveWithAjax = false; // Set to true to enable AJAX saving
        if (saveWithAjax && paymentSettingsForm) {
            paymentSettingsForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(paymentSettingsForm);
                const submitBtn = paymentSettingsForm.querySelector('[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                fetch(paymentSettingsForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    // Show response
                    if (data.success) {
                        if (typeof showToast === 'function') {
                            showToast('Success', 'Payment settings saved successfully', 'success');
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('Error', data.message || 'Failed to save settings', 'error');
                        }
                    }
                })
                .catch(error => {
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    // Show error
                    if (typeof showToast === 'function') {
                        showToast('Error', 'An unexpected error occurred', 'error');
                    }
                    console.error('Error:', error);
                });
            });
        }
    });
</script>
@endsection
