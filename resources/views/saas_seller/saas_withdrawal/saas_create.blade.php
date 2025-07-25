@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Request Withdrawal</h1>
        <div class="float-end">
            <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Withdrawals
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Wallet Balance -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Your Balance</h5>
                    <p class="card-text">This is your current available balance for withdrawal.</p>
                    <h2 class="text-success">Rs {{ number_format($availableBalance ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Withdrawal Request Form</h5>
                </div>
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

                    <form action="{{ route('seller.withdrawals.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="requested_amount" class="form-label">Withdrawal Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs</span>
                                <input type="number"
                                       class="form-control @error('requested_amount') is-invalid @enderror"
                                       id="requested_amount"
                                       name="requested_amount"
                                       value="{{ old('requested_amount') }}"
                                       step="0.01"
                                       min="{{ $minimumWithdrawal ?? 0 }}"
                                       max="{{ $availableBalance ?? 0 }}"
                                       placeholder="Enter withdrawal amount"
                                       required>
                                @error('requested_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Available balance: Rs {{ number_format($availableBalance ?? 0, 2) }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method_id" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method_id') is-invalid @enderror"
                                    id="payment_method_id"
                                    name="payment_method_id"
                                    required>
                                <option value="">Select payment method...</option>
                                @foreach($paymentMethods ?? [] as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                        {{ ucfirst($paymentMethod->type) }} -
                                        @if($paymentMethod->type == 'bank_account')
                                            {{ $paymentMethod->bank_name }} ({{ substr($paymentMethod->account_number, -4) }})
                                        @elseif($paymentMethod->type == 'paypal')
                                            {{ $paymentMethod->paypal_email }}
                                        @else
                                            @if(is_array($paymentMethod->details))
                                                {{ implode(', ', array_filter($paymentMethod->details)) }}
                                            @else
                                                {{ $paymentMethod->details }}
                                            @endif
                                        @endif
                                        @if($paymentMethod->is_default)
                                            <span class="badge bg-success">Default</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(count($paymentMethods ?? []) == 0)
                                <div class="form-text text-danger">
                                    You need to add a payment method first.
                                    <a href="{{ route('seller.payment-methods.create') }}" class="text-decoration-none">
                                        Add payment method
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="Add any additional notes for this withdrawal request">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="align-middle" data-feather="info"></i>
                            <strong>Processing Information:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Withdrawal requests are processed within 3-5 business days</li>
                                <li>You will receive an email confirmation once the withdrawal is processed</li>
                                <li>Processing fees may apply depending on the payment method</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <button type="submit"
                                    class="btn btn-primary"
                                    @if(count($paymentMethods ?? []) == 0 || ($availableBalance ?? 0) < ($minimumWithdrawal ?? 0)) disabled @endif>
                                <i class="align-middle" data-feather="send"></i> Submit Withdrawal Request
                            </button>
                            <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="x"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('seller.payment-methods.index') }}" class="btn btn-outline-primary w-100">
                                <i class="align-middle" data-feather="credit-card"></i>
                                Manage Payment Methods
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-outline-info w-100">
                                <i class="align-middle" data-feather="history"></i>
                                Withdrawal History
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-success w-100">
                                <i class="align-middle" data-feather="activity"></i>
                                View Earnings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('requested_amount');
    const availableBalance = {{ $availableBalance ?? 0 }};
    const minimumWithdrawal = {{ $minimumWithdrawal ?? 0 }};
    const gatewayFee = {{ $gatewayFee ?? 0 }};

    // Quick amount buttons
    function addQuickAmountButtons() {
        const container = amountInput.parentNode.parentNode;
        const quickAmountsDiv = document.createElement('div');
        quickAmountsDiv.className = 'mt-2';
        quickAmountsDiv.innerHTML = '<small class="text-muted">Quick amounts:</small>';

        const amounts = [
            Math.min(1000, availableBalance),
            Math.min(5000, availableBalance),
            Math.min(10000, availableBalance),
            availableBalance
        ].filter((amount, index, arr) => amount >= minimumWithdrawal && arr.indexOf(amount) === index);

        amounts.forEach(amount => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-outline-secondary me-1 mt-1';
            btn.textContent = 'Rs ' + amount.toLocaleString();
            btn.onclick = () => amountInput.value = amount;
            quickAmountsDiv.appendChild(btn);
        });

        container.appendChild(quickAmountsDiv);
    }

    if (availableBalance >= minimumWithdrawal) {
        addQuickAmountButtons();
    }

    // Validate amount on input
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        const submitBtn = document.querySelector('button[type="submit"]');
        const finalAmount = value - gatewayFee;

        if (value > availableBalance) {
            this.setCustomValidity('Amount exceeds available balance');
            submitBtn.disabled = true;
        } else if (value < minimumWithdrawal) {
            this.setCustomValidity('Amount is below minimum withdrawal limit');
            submitBtn.disabled = true;
        } else if (finalAmount <= 0) {
            this.setCustomValidity('Amount too small after gateway fees');
            submitBtn.disabled = true;
        } else {
            this.setCustomValidity('');
            submitBtn.disabled = false;
        }
    });
});
</script>
@endsection
