@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-primary bg-opacity-10 rounded-3 me-4">
                    <i class="align-middle text-primary" data-feather="plus-circle" style="width: 32px; height: 32px;"></i>
                </div>
                <div>
                    <h1 class="h2 text-dark fw-bold mb-1">Request a Refund</h1>
                    {{-- <p class="text-muted fs-6 mb-0">Fill out the form below to initiate a refund request.</p> --}}
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('customer.refunds.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                <i class="align-middle me-2" data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
                Back to My Refunds
            </a>
        </div>
    </div>

    <div class="row g-5">
        <!-- Refund Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-lg-5 p-4">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 mb-4">
                            <h6 class="fw-bold">Please fix the following errors:</h6>
                            <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                        </div>
                    @endif

                    <form action="{{ route('customer.refunds.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Step 1: Select Order -->
                        <div class="mb-5">
                            <label for="order_id" class="form-label fs-5 fw-bold text-dark mb-3">1. Select an eligible order</label>
                            <select class="form-select form-select-lg @error('order_id') is-invalid @enderror" id="order_id" name="order_id" required onchange="loadOrderDetails(this.value)">
                                <option value="" disabled {{ !old('order_id', $selectedOrderId ?? null) ? 'selected' : '' }}>Choose an order...</option>
                                @foreach($eligibleOrders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id', $selectedOrderId ?? null) == $order->id ? 'selected' : '' }}>
                                        #{{ $order->order_number }} - Placed on {{ $order->created_at->format('d M Y') }} - Rs {{ number_format($order->total, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @if($eligibleOrders->isEmpty())
                                <div class="alert alert-warning mt-3">
                                    You have no orders eligible for a refund. Only delivered or completed orders can be refunded.
                                </div>
                            @endif
                        </div>

                        <!-- Order Details (populated via JS) -->
                        <div id="order-details" class="mb-5" style="display: none;"></div>

                        <!-- Step 2: Select Refund Method -->
                        <div class="mb-5">
                            <label class="form-label fs-5 fw-bold text-dark mb-3">2. Choose your refund method</label>
                            <div class="row g-3">
                                @foreach($paymentMethods as $paymentMethod)
                                    <div class="col-md-6">
                                        <div class="card border h-100 payment-method-card">
                                            <label class="card-body p-3 d-flex align-items-center" for="payment_method_{{ $paymentMethod->id }}">
                                                <input type="radio" name="payment_method_id" id="payment_method_{{ $paymentMethod->id }}" value="{{ $paymentMethod->id }}" class="form-check-input mt-0" required>
                                                <div class="ms-3 flex-grow-1">
                                                    <span class="fw-bold d-block">{{ $paymentMethod->title }}</span>
                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $paymentMethod->type)) }}</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($paymentMethods->isEmpty())
                                <div class="alert alert-warning mt-3">
                                    You have no payment methods saved. <a href="{{ route('customer.payment-methods.create') }}">Add a payment method</a> to proceed.
                                </div>
                            @endif
                        </div>

                        <!-- Step 3: Reason for Refund -->
                        <div class="mb-5">
                            <label for="customer_reason" class="form-label fs-5 fw-bold text-dark mb-3">3. Tell us why you're requesting a refund</label>
                            <textarea class="form-control form-control-lg @error('customer_reason') is-invalid @enderror" id="customer_reason" name="customer_reason" rows="5" required placeholder="Please provide as much detail as possible...">{{ old('customer_reason') }}</textarea>
                            <div class="form-text mt-2">
                                Providing a clear reason helps us process your request faster.
                            </div>
                        </div>

                        <!-- Submission -->
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('customer.refunds.index') }}" class="btn btn-light me-3">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow" @if($eligibleOrders->isEmpty() || $paymentMethods->isEmpty()) disabled @endif>
                                <i class="align-middle me-2" data-feather="send"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side Information -->
        <div class="col-lg-4">
            <div class="card border-0 bg-light rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="align-middle text-primary me-2" data-feather="info"></i>Refund Policy Highlights</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex mb-3">
                            <i class="align-middle text-success me-2 mt-1" data-feather="check-circle" style="width: 18px;"></i>
                            <span>Only <strong>delivered</strong> or <strong>completed</strong> orders are eligible.</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="align-middle text-success me-2 mt-1" data-feather="check-circle" style="width: 18px;"></i>
                            <span>Requests are typically reviewed within <strong>24-48 hours</strong>.</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="align-middle text-success me-2 mt-1" data-feather="check-circle" style="width: 18px;"></i>
                            <span>Refunds are processed to your selected payment method within <strong>3-5 business days</strong> after approval.</span>
                        </li>
                        <li class="d-flex">
                            <i class="align-middle text-success me-2 mt-1" data-feather="check-circle" style="width: 18px;"></i>
                            <span>One refund request per order.</span>
                        </li>
                        </ul>
                    <hr class="my-4">
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="align-middle me-2" data-feather="file-text"></i> Read Full Refund Policy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-method-card {
        cursor: pointer;
    transition: all 0.2s ease;
    }
    .payment-method-card:hover {
        transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
    }
    .payment-method-card.border-primary {
    box-shadow: 0 0 0 2px var(--bs-primary);
    background-color: var(--bs-primary-bg-subtle);
    }
</style>
@endsection

@section('scripts')
<script>
// Global function for order details loading
window.loadOrderDetails = function(orderId) {
    const orderDetailsData = @json($eligibleOrders->keyBy('id'));
    const orderDetailsDiv = document.getElementById('order-details');

    if (!orderId) {
        orderDetailsDiv.style.display = 'none';
        return;
    }

    const order = orderDetailsData[orderId];
    if (!order) {
        orderDetailsDiv.style.display = 'none';
        return;
    }

    const orderHtml = `
        <div class="card border-primary border-opacity-25 bg-primary bg-opacity-5">
            <div class="card-header bg-primary bg-opacity-10 border-0">
                <h6 class="fw-bold mb-0">Order Summary for #${order.order_number}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <strong>Total Amount:</strong>
                        <p class="mb-0 fs-5 text-success fw-bold">Rs ${parseFloat(order.total).toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
                    </div>
            <div class="col-md-6">
                        <strong>Items Count:</strong>
                        <p class="mb-0">${order.items ? order.items.length : order.items_count || 0} item(s)</p>
                </div>
                </div>
                ${order.items && order.items.length > 0 ? `
                <hr>
                <h6 class="fw-bold mb-3">Order Items:</h6>
                <div class="row g-3">
                    ${order.items.map(item => `
                        <div class="col-12">
                            <div class="d-flex align-items-center p-2 bg-white rounded border">
                                <div class="flex-grow-1">
                                    <strong>${item.product ? item.product.name : 'Product'}</strong>
                                    <div class="small text-muted">
                                        Qty: ${item.quantity} × Rs ${parseFloat(item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})} = Rs ${parseFloat(item.total).toLocaleString('en-IN', {minimumFractionDigits: 2})}
                </div>
            </div>
                </div>
                </div>
                    `).join('')}
                </div>
                ` : ''}
            </div>
        </div>
    `;

    orderDetailsDiv.innerHTML = orderHtml;
    orderDetailsDiv.style.display = 'block';
};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Load order details if order is pre-selected
    const orderSelect = document.getElementById('order_id');
    if (orderSelect && orderSelect.value) {
        loadOrderDetails(orderSelect.value);
    }

    // Payment method selection
    const paymentRadios = document.querySelectorAll('input[name="payment_method_id"]');
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-primary');
            });
            if (this.checked) {
                this.closest('.payment-method-card').classList.add('border-primary');
            }
        });
    });

    // Pre-select and highlight payment method if one is selected
    const checkedRadio = document.querySelector('input[name="payment_method_id"]:checked');
    if (checkedRadio) {
        checkedRadio.closest('.payment-method-card').classList.add('border-primary');
    }

    // Form validation
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                // Show first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            form.classList.add('was-validated');
        });
    }
});
</script>
@endsection
