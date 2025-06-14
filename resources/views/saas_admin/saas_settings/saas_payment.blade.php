@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Payment Settings')

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
    .gateway-section {
        background: linear-gradient(135deg, #cdfff8 0%, #e1edff 100%);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e1edff;
    }
    .gateway-logo {
        height: 40px;
        width: auto;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Payment Settings</h5>
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

            <form action="{{ route('admin.settings.payment') }}" method="POST">
                @csrf

                <!-- Withdrawal Settings -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="dollar-sign"></i> Withdrawal Settings
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="minimum_withdrawal_amount" class="form-label">Minimum Withdrawal Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                    <input type="number" class="form-control" id="minimum_withdrawal_amount" name="minimum_withdrawal_amount"
                                        value="{{ old('minimum_withdrawal_amount', $settings->minimum_withdrawal_amount) }}"
                                        placeholder="100" step="0.01" min="0" required>
                                </div>
                                <small class="text-muted">Minimum amount sellers can withdraw from their earnings</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gateway_transaction_fee" class="form-label">Platform Transaction Fee <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="gateway_transaction_fee" name="gateway_transaction_fee"
                                        value="{{ old('gateway_transaction_fee', $settings->gateway_transaction_fee) }}"
                                        placeholder="2.5" step="0.01" min="0" max="100" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">Percentage fee charged on each transaction</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="withdrawal_policy" class="form-label">Withdrawal Policy</label>
                        <textarea class="form-control" id="withdrawal_policy" name="withdrawal_policy" rows="4">{{ old('withdrawal_policy', $settings->withdrawal_policy) }}</textarea>
                        <small class="text-muted">Terms and conditions for seller withdrawals (displayed to sellers)</small>
                    </div>
                </div>

                <!-- eSewa Settings -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="credit-card"></i> eSewa Configuration
                        <span class="badge bg-success ms-2">Nepal</span>
                    </h6>

                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>eSewa Integration:</strong> Popular digital wallet and payment gateway in Nepal.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="esewa_merchant_id" class="form-label">eSewa Merchant ID</label>
                                <input type="text" class="form-control" id="esewa_merchant_id" name="esewa_merchant_id"
                                    value="{{ old('esewa_merchant_id', $settings->esewa_merchant_id) }}"
                                    placeholder="Your eSewa Merchant ID">
                                <small class="text-muted">Obtain from your eSewa merchant account</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="esewa_secret_key" class="form-label">eSewa Secret Key</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="esewa_secret_key" name="esewa_secret_key"
                                        value="{{ old('esewa_secret_key', $settings->esewa_secret_key) }}"
                                        placeholder="Your eSewa Secret Key">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('esewa_secret_key')">
                                        <i data-feather="eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Keep this secret and secure</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">eSewa Setup Instructions</h6>
                                    <ol class="small mb-0">
                                        <li>Register as a merchant at <a href="https://merchant.esewa.com.np" target="_blank">eSewa Merchant Portal</a></li>
                                        <li>Complete the verification process</li>
                                        <li>Obtain your Merchant ID and Secret Key</li>
                                        <li>Configure the success and failure URLs in your eSewa merchant panel</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khalti Settings -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="smartphone"></i> Khalti Configuration
                        <span class="badge bg-success ms-2">Nepal</span>
                    </h6>

                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Khalti Integration:</strong> Leading digital wallet in Nepal with wide merchant acceptance.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="khalti_public_key" class="form-label">Khalti Public Key</label>
                                <input type="text" class="form-control" id="khalti_public_key" name="khalti_public_key"
                                    value="{{ old('khalti_public_key', $settings->khalti_public_key) }}"
                                    placeholder="Your Khalti Public Key">
                                <small class="text-muted">Public key for client-side integration</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="khalti_secret_key" class="form-label">Khalti Secret Key</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="khalti_secret_key" name="khalti_secret_key"
                                        value="{{ old('khalti_secret_key', $settings->khalti_secret_key) }}"
                                        placeholder="Your Khalti Secret Key">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('khalti_secret_key')">
                                        <i data-feather="eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Server-side secret key - keep secure</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Khalti Setup Instructions</h6>
                                    <ol class="small mb-0">
                                        <li>Register at <a href="https://khalti.com" target="_blank">Khalti Merchant Dashboard</a></li>
                                        <li>Complete merchant verification</li>
                                        <li>Generate API keys from the developer section</li>
                                        <li>Test with sandbox keys before going live</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Gateway Status -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="activity"></i> Payment Gateway Status
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title">eSewa</h6>
                                    @if($settings->esewa_merchant_id && $settings->esewa_secret_key)
                                        <span class="badge bg-success fs-6">
                                            <i class="align-middle" data-feather="check-circle"></i> Configured
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6">
                                            <i class="align-middle" data-feather="x-circle"></i> Not Configured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Khalti</h6>
                                    @if($settings->khalti_public_key && $settings->khalti_secret_key)
                                        <span class="badge bg-success fs-6">
                                            <i class="align-middle" data-feather="check-circle"></i> Configured
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6">
                                            <i class="align-middle" data-feather="x-circle"></i> Not Configured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Fee Calculator -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="calculator"></i> Transaction Fee Calculator
                    </h6>

                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Sale Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $settings->site_currency_symbol ?: '$' }}</span>
                                        <input type="number" class="form-control" id="calculator_amount" placeholder="100" value="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fee Breakdown</label>
                                    <div class="text-muted">
                                        <div>Platform Fee (<span id="calc_fee_rate">{{ $settings->gateway_transaction_fee ?: 2.5 }}</span>%): <span class="fw-bold" id="calc_platform_fee">{{ $settings->site_currency_symbol ?: '$' }}{{ number_format((($settings->gateway_transaction_fee ?: 2.5) / 100) * 100, 2) }}</span></div>
                                        <div>Seller Receives: <span class="fw-bold text-success" id="calc_seller_amount">{{ $settings->site_currency_symbol ?: '$' }}{{ number_format(100 - (($settings->gateway_transaction_fee ?: 2.5) / 100) * 100, 2) }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save Payment Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.nextElementSibling;
        const icon = button.querySelector('[data-feather]');

        if (field.type === 'password') {
            field.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            field.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    }

    // Update fee calculator when transaction fee changes
    document.getElementById('gateway_transaction_fee').addEventListener('input', function() {
        updateCalculator();
    });

    // Update fee calculator when amount changes
    document.getElementById('calculator_amount').addEventListener('input', function() {
        updateCalculator();
    });

    function updateCalculator() {
        const amount = parseFloat(document.getElementById('calculator_amount').value) || 0;
        const feeRate = parseFloat(document.getElementById('gateway_transaction_fee').value) || 0;
        const currencySymbol = '{{ $settings->site_currency_symbol ?: "$" }}';

        const platformFee = (feeRate / 100) * amount;
        const sellerAmount = amount - platformFee;

        document.getElementById('calc_fee_rate').textContent = feeRate;
        document.getElementById('calc_platform_fee').textContent = currencySymbol + platformFee.toFixed(2);
        document.getElementById('calc_seller_amount').textContent = currencySymbol + sellerAmount.toFixed(2);
    }

    // Initialize calculator on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCalculator();
    });
</script>
@endsection
