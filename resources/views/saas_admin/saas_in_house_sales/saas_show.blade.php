@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Sale Details - ' . $sale->sale_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-success me-2" target="_blank">
                        <i data-feather="printer"></i> Print Receipt
                    </a>
                    <a href="{{ route('admin.in-house-sales.index') }}" class="btn btn-outline-secondary">
                        <i data-feather="arrow-left"></i> Back to Sales
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Sale Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Sale Information</h5>
                                <div>
                                    @php
                                        $statusClass = match($sale->payment_status) {
                                            'paid' => 'bg-success',
                                            'partial' => 'bg-warning',
                                            'pending' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} fs-6">
                                        {{ ucfirst($sale->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Sale Number:</td>
                                            <td>{{ $sale->sale_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Sale Date:</td>
                                            <td>{{ $sale->sale_date->format('M d, Y g:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Cashier:</td>
                                            <td>{{ $sale->cashier->name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Payment Method:</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Customer Name:</td>
                                            <td>
                                                @if($sale->customer)
                                                    <a href="{{ route('admin.customers.show', $sale->customer->id) }}" class="text-decoration-none">
                                                        {{ $sale->customer->name }}
                                                        <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.75em;"></i>
                                                    </a>
                                                @else
                                                    Walk-in Customer
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Phone:</td>
                                            <td>{{ $sale->customer->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Email:</td>
                                            <td>{{ $sale->customer->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Address:</td>
                                            <td>
                                                @if($sale->customer && $sale->customer->customerProfile)
                                                    {{ $sale->customer->customerProfile->address ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if($sale->notes)
                                <div class="mt-3">
                                    <strong>Notes:</strong>
                                    <p class="text-muted mb-0">{{ $sale->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sale Items -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sale Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Variation</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->saleItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->thumbnail_img)
                                                        <img src="{{ uploaded_asset($item->product->thumbnail_img) }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="rounded me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px;">
                                                            <i data-feather="image" class="text-muted" style="width: 20px; height: 20px;"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        @if($item->product)
                                                            <br><small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->variation_name)
                                                    <span class="badge bg-secondary">{{ $item->variation_name }}</span>
                                                @else
                                                    <span class="text-muted">No variation</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end">Rs {{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-end">
                                                <strong>Rs {{ number_format($item->total_price, 2) }}</strong>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sale Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sale Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">Rs {{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                @if($sale->discount_amount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end text-success">- Rs {{ number_format($sale->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($sale->tax_amount > 0)
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">Rs {{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                @endif
                                @if($sale->shipping_amount > 0)
                                <tr>
                                    <td>Shipping:</td>
                                    <td class="text-end">Rs {{ number_format($sale->shipping_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>Total Amount:</strong></td>
                                    <td class="text-end"><strong>Rs {{ number_format($sale->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.in-house-sales.receipt', $sale) }}" class="btn btn-success" target="_blank">
                                    <i data-feather="printer"></i> Print Receipt
                                </a>
                                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                                    <i data-feather="plus"></i> New Sale
                                </a>
                                <button type="button" class="btn btn-outline-danger w-100 delete-sale"
                                        data-id="{{ $sale->id }}"
                                        data-number="{{ $sale->sale_number }}">
                                    <i data-feather="trash-2"></i> Delete Sale
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    // Delete sale confirmation with SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-sale').forEach(button => {
            button.addEventListener('click', function() {
                const saleId = this.getAttribute('data-id');
                const saleNumber = this.getAttribute('data-number');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete sale "${saleNumber}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `{{ route('admin.in-house-sales.index') }}/${saleId}`;
                        form.submit();
                    }
                });
            });
        });

        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
