@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Withdrawal Details</h1>
        <div class="float-end">
            <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-info">
                <i class="align-middle" data-feather="history"></i> View History
            </a>
            <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Withdrawals
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-message">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="alert-message">{{ session('error') }}</div>
        </div>
    @endif

    <div class="row">
        <!-- Withdrawal Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Withdrawal Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Withdrawal ID:</dt>
                        <dd class="col-sm-9"><code>#{{ $withdrawal->id }}</code></dd>

                        <dt class="col-sm-3">Amount:</dt>
                        <dd class="col-sm-9">
                            <strong class="text-primary">Rs {{ number_format($withdrawal->amount, 2) }}</strong>
                        </dd>

                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9">
                            @if($withdrawal->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($withdrawal->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($withdrawal->status == 'processing')
                                <span class="badge bg-info">Processing</span>
                            @elseif($withdrawal->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($withdrawal->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($withdrawal->status == 'cancelled')
                                <span class="badge bg-secondary">Cancelled</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Payment Method:</dt>
                        <dd class="col-sm-9">
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</span>
                        </dd>

                        <dt class="col-sm-3">Request Date:</dt>
                        <dd class="col-sm-9">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</dd>

                        @if($withdrawal->processed_at)
                            <dt class="col-sm-3">Processed Date:</dt>
                            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, h:i A') }}</dd>
                        @endif

                        @if($withdrawal->notes)
                            <dt class="col-sm-3">Your Notes:</dt>
                            <dd class="col-sm-9">{{ $withdrawal->notes }}</dd>
                        @endif

                        @if($withdrawal->admin_notes)
                            <dt class="col-sm-3">Admin Notes:</dt>
                            <dd class="col-sm-9">
                                <div class="alert alert-info">
                                    {{ $withdrawal->admin_notes }}
                                </div>
                            </dd>
                        @endif
                    </dl>

                    @if($withdrawal->status == 'pending')
                        <div class="alert alert-warning">
                            <i class="align-middle" data-feather="clock"></i>
                            <strong>Status:</strong> Your withdrawal request is pending review. We will process it within 3-5 business days.
                        </div>

                        <form action="{{ route('seller.withdrawals.cancel', $withdrawal) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-outline-danger"
                                    class="cancel-withdrawal" data-id="{{ $withdrawal->id }}" data-amount="{{ $withdrawal->amount }}">
                                <i class="align-middle" data-feather="x"></i> Cancel Request
                            </button>
                        </form>
                    @elseif($withdrawal->status == 'approved')
                        <div class="alert alert-success">
                            <i class="align-middle" data-feather="check-circle"></i>
                            <strong>Great!</strong> Your withdrawal request has been approved and is being processed.
                        </div>
                    @elseif($withdrawal->status == 'processing')
                        <div class="alert alert-info">
                            <i class="align-middle" data-feather="loader"></i>
                            <strong>Processing:</strong> Your withdrawal is currently being processed. You will receive the funds shortly.
                        </div>
                    @elseif($withdrawal->status == 'completed')
                        <div class="alert alert-success">
                            <i class="align-middle" data-feather="check-circle"></i>
                            <strong>Completed!</strong> Your withdrawal has been successfully processed and the funds have been transferred.
                        </div>
                    @elseif($withdrawal->status == 'rejected')
                        <div class="alert alert-danger">
                            <i class="align-middle" data-feather="x-circle"></i>
                            <strong>Rejected:</strong> Your withdrawal request has been rejected. Please contact support if you have any questions.
                        </div>
                    @elseif($withdrawal->status == 'cancelled')
                        <div class="alert alert-secondary">
                            <i class="align-middle" data-feather="x"></i>
                            <strong>Cancelled:</strong> This withdrawal request has been cancelled.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    @if($withdrawal->payment_details)
                        @php
                            $details = is_string($withdrawal->payment_details) ? json_decode($withdrawal->payment_details, true) : $withdrawal->payment_details;
                        @endphp

                        @if(is_array($details))
                            @foreach($details as $key => $value)
                                <dl class="row">
                                    <dt class="col-5">{{ ucfirst(str_replace('_', ' ', $key)) }}:</dt>
                                    <dd class="col-7">{{ $value }}</dd>
                                </dl>
                            @endforeach
                        @else
                            <p>{{ $withdrawal->payment_details }}</p>
                        @endif
                    @else
                        <p class="text-muted">No payment details available.</p>
                    @endif
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Transaction Summary</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-7">Withdrawal Amount:</dt>
                        <dd class="col-5">Rs {{ number_format($withdrawal->amount, 2) }}</dd>

                        @if(isset($withdrawal->fee) && $withdrawal->fee > 0)
                            <dt class="col-7">Processing Fee:</dt>
                            <dd class="col-5">Rs {{ number_format($withdrawal->fee, 2) }}</dd>

                            <dt class="col-7 border-top pt-2"><strong>Net Amount:</strong></dt>
                            <dd class="col-5 border-top pt-2"><strong>Rs {{ number_format($withdrawal->amount - $withdrawal->fee, 2) }}</strong></dd>
                        @else
                            <dt class="col-7 border-top pt-2"><strong>Net Amount:</strong></dt>
                            <dd class="col-5 border-top pt-2"><strong>Rs {{ number_format($withdrawal->amount, 2) }}</strong></dd>
                        @endif
                    </dl>
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
                        <div class="col-md-3">
                            <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-outline-primary w-100">
                                <i class="align-middle" data-feather="plus"></i>
                                New Withdrawal
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-outline-info w-100">
                                <i class="align-middle" data-feather="history"></i>
                                View History
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-outline-success w-100">
                                <i class="align-middle" data-feather="activity"></i>
                                Dashboard
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('seller.payment-methods.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="align-middle" data-feather="credit-card"></i>
                                Payment Methods
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hidden form for cancel -->
<form id="cancel-form" action="" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel withdrawal confirmation with SweetAlert
    document.querySelectorAll('.cancel-withdrawal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const withdrawalId = this.getAttribute('data-id');
            const amount = this.getAttribute('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to cancel withdrawal request for Rs ${amount}. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'Keep Request'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('cancel-form');
                    form.action = `{{ route('seller.withdrawals.index') }}/${withdrawalId}/cancel`;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
