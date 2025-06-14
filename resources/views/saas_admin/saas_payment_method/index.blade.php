@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Payment Methods')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    @if(isset($userId) && isset($userRole))
                        @php
                            $user = App\Models\User::find($userId);
                        @endphp
                        Payment Methods for {{ $user ? $user->name : 'User' }} ({{ ucfirst($userRole) }})
                    @else
                        My Payment Methods
                    @endif
                </h5>
                <div>
                    <a href="{{ route('admin.payment-methods.create', ['user_id' => $userId ?? null, 'user_role' => $userRole ?? null]) }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="plus"></i> Add Payment Method
                    </a>
                    @if(isset($userId) && isset($userRole))
                        @if($userRole == 'customer')
                            <a href="{{ route('admin.customers.show', $userId) }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="arrow-left"></i> Back to Customer
                            </a>
                        @elseif($userRole == 'seller')
                            <a href="{{ route('admin.sellers.show', $userId) }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="arrow-left"></i> Back to Seller
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($paymentMethods->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Title</th>
                                <th width="15%">Type</th>
                                <th width="20%">Account Details</th>
                                <th width="15%">Bank/Mobile</th>
                                <th width="10%" class="text-center">Default</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentMethods as $key => $paymentMethod)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $paymentMethod->title }}</strong>
                                        @if($paymentMethod->notes)
                                            <br><small class="text-muted">{{ Str::limit($paymentMethod->notes, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($paymentMethod->type == 'bank_transfer')
                                                <i data-feather="credit-card" class="text-primary me-2" style="width: 18px; height: 18px;"></i>
                                                <span class="badge bg-primary">Bank Transfer</span>
                                            @elseif($paymentMethod->type == 'esewa')
                                                <i data-feather="smartphone" class="text-success me-2" style="width: 18px; height: 18px;"></i>
                                                <span class="badge bg-success">eSewa</span>
                                            @elseif($paymentMethod->type == 'khalti')
                                                <i data-feather="smartphone" class="text-purple me-2" style="width: 18px; height: 18px;"></i>
                                                <span class="badge bg-purple">Khalti</span>
                                            @elseif($paymentMethod->type == 'cash')
                                                <i data-feather="dollar-sign" class="text-warning me-2" style="width: 18px; height: 18px;"></i>
                                                <span class="badge bg-warning">Cash</span>
                                            @else
                                                <i data-feather="credit-card" class="text-secondary me-2" style="width: 18px; height: 18px;"></i>
                                                <span class="badge bg-secondary">Other</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $paymentMethod->account_name }}</strong>
                                        @if($paymentMethod->account_number)
                                            <br><small class="text-muted">A/C: {{ $paymentMethod->account_number }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($paymentMethod->type == 'bank_transfer')
                                            <strong>{{ $paymentMethod->bank_name }}</strong>
                                            @if($paymentMethod->bank_branch)
                                                <br><small class="text-muted">{{ $paymentMethod->bank_branch }}</small>
                                            @endif
                                        @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
                                            <strong>{{ $paymentMethod->mobile_number }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($paymentMethod->is_default)
                                            <span class="badge bg-primary">
                                                <i data-feather="check-circle" style="width: 14px; height: 14px;"></i> Default
                                            </span>
                                        @else
                                            <form action="{{ route('admin.payment-methods.set-default', $paymentMethod->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Set as default">
                                                    <i data-feather="circle" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($paymentMethod->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.payment-methods.show', ['payment_method' => $paymentMethod->id, 'user_id' => $userId ?? null, 'user_role' => $userRole ?? null]) }}"
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('admin.payment-methods.edit', ['payment_method' => $paymentMethod->id, 'user_id' => $userId ?? null, 'user_role' => $userRole ?? null]) }}"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                            @if(!$paymentMethod->is_default)
                                                <form action="{{ route('admin.payment-methods.destroy', $paymentMethod->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Delete">
                                                        <i class="align-middle" data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info mb-0">
                                <i class="align-middle me-2" data-feather="info"></i>
                                <strong>Note:</strong> You must have at least one payment method set as default. The default payment method will be pre-selected for withdrawals and payments.
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="align-middle me-2" data-feather="alert-triangle"></i>
                    No payment methods found. Please add at least one payment method to receive payments.
                </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize delete confirmations
        const deleteButtons = document.querySelectorAll('.delete-confirm');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This payment method will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .badge.bg-purple {
        background-color: #6f42c1 !important;
    }
    .text-purple {
        color: #6f42c1 !important;
    }
</style>
@endsection
