@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Payment Method Details</h1>
        <div class="float-end">
            <a href="{{ route('seller.payment-methods.index') }}" class="btn btn-sm btn-secondary"><i class="align-middle" data-feather="arrow-left"></i> Back</a>
            <a href="{{ route('seller.payment-methods.edit', $paymentMethod) }}" class="btn btn-sm btn-primary"><i class="align-middle" data-feather="edit"></i> Edit</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $paymentMethod->title }}</h5>
                    <div class="float-end">
                        @if($paymentMethod->is_default)
                            <span class="badge bg-success">Default</span>
                        @endif
                        @if($paymentMethod->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Type:</strong>
                                @if($paymentMethod->type == 'bank_transfer')
                                    <span class="badge bg-primary">Bank Transfer</span>
                                @elseif($paymentMethod->type == 'esewa')
                                    <span class="badge bg-success">eSewa</span>
                                @elseif($paymentMethod->type == 'khalti')
                                    <span class="badge bg-purple">Khalti</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($paymentMethod->type) }}</span>
                                @endif
                            </p>
                            <p><strong>Account Name:</strong> {{ $paymentMethod->details['account_name'] ?? 'N/A' }}</p>

                            @if($paymentMethod->type == 'bank_transfer')
                                <p><strong>Bank Name:</strong> {{ $paymentMethod->details['bank_name'] ?? 'N/A' }}</p>
                                <p><strong>Bank Branch:</strong> {{ $paymentMethod->details['bank_branch'] ?? 'N/A' }}</p>
                                <p><strong>Account Number:</strong> {{ $paymentMethod->details['account_number'] ?? 'N/A' }}</p>
                            @endif

                            @if($paymentMethod->type == 'esewa' || $paymentMethod->type == 'khalti')
                                <p><strong>Mobile Number:</strong> {{ $paymentMethod->details['mobile_number'] ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Created At:</strong> {{ $paymentMethod->created_at->format('d M Y, h:i A') }}</p>
                            <p><strong>Last Updated:</strong> {{ $paymentMethod->updated_at->format('d M Y, h:i A') }}</p>

                            @if($paymentMethod->notes)
                                <p><strong>Notes:</strong></p>
                                <div class="p-3 bg-light rounded">
                                    {{ $paymentMethod->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            @if(!$paymentMethod->is_default)
                                <form action="{{ route('seller.payment-methods.set-default', $paymentMethod) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Set as Default</button>
                                </form>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <form action="{{ route('seller.payment-methods.destroy', $paymentMethod) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger delete-payment-method"
                                    data-id="{{ $paymentMethod->id }}"
                                    data-title="{{ $paymentMethod->title }}">Delete</button>
                            </form>
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
document.addEventListener('DOMContentLoaded', function() {
    // Delete payment method confirmation with SweetAlert
    document.querySelectorAll('.delete-payment-method').forEach(button => {
        button.addEventListener('click', function() {
            const paymentMethodId = this.getAttribute('data-id');
            const paymentMethodTitle = this.getAttribute('data-title');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete payment method "${paymentMethodTitle}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('seller.payment-methods.index') }}/${paymentMethodId}`;
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
