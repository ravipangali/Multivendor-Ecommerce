@extends('saas_seller.saas_layouts.saas_layout')


@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Withdrawals</h1>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary">
                <i class="align-middle" data-feather="plus"></i> Request Withdrawal
            </a>
            <a href="{{ route('seller.withdrawals.history') }}" class="btn btn-outline-secondary">
                <i class="align-middle" data-feather="clock"></i> History
            </a>
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Current Balance</p>
                            <h1 class="mt-1 mb-3">Rs {{ number_format($balance, 2) }}</h1>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i class="bx bx-wallet font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Pending Withdrawals</p>
                            <h1 class="mt-1 mb-3">Rs {{ number_format($pendingAmount, 2) }}</h1>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i class="bx bx-time-five font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Available for Withdrawal</p>
                            <h1 class="mt-1 mb-3">Rs {{ number_format($balance, 2) }}</h1>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i class="bx bx-money font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total Requests</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info">
                                <i class="align-middle" data-feather="download"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $withdrawalRequests->total() }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Withdrawal Summary</h4>
                    <div class="row text-center">
                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="text-muted mb-1">Current Balance</p>
                                <h1 class="mt-1 mb-3">Rs {{ number_format($balance, 2) }}</h1>
                                <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary btn-sm">Request Withdrawal</a>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="text-muted mb-1">Pending Withdrawals</p>
                                <h1 class="mt-1 mb-3">Rs {{ number_format($pendingAmount, 2) }}</h1>
                                <a href="#pending-requests" class="btn btn-secondary btn-sm">View Pending</a>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="text-muted mb-1">Total Withdrawn</p>
                                <h1 class="mt-1 mb-3">Rs {{ number_format($totalWithdrawn, 2) }}</h1>
                                <a href="{{ route('seller.transactions.index') }}" class="btn btn-info btn-sm">View History</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title" id="pending-requests">Withdrawal History</h4>
                        <div class="my-3">
                            @if($balance > 0)
                                <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-success"><i class="mdi mdi-plus me-1"></i> Request New Withdrawal</a>
                            @else
                                <p class="text-warning">You do not have enough balance to make a withdrawal request.</p>
                            @endif
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

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-message">{{ session('info') }}</div>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Requested Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawalRequests as $withdrawal)
                                <tr>
                                    <td>
                                        <strong>#{{ str_pad($withdrawal->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($withdrawal->amount, 2) }}</strong>
                                        @if($withdrawal->fee > 0)
                                            <br><small class="text-muted">Fee: Rs {{ number_format($withdrawal->fee, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}
                                        @if($withdrawal->payment_details)
                                            <br><small class="text-muted">{{ Str::limit($withdrawal->payment_details['details'] ?? '', 30) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $withdrawal->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Processing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">Cancelled</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('seller.withdrawals.show', $withdrawal) }}"
                                               class="btn btn-sm btn-outline-info"
                                               title="View Details">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            @if($withdrawal->status == 'pending')
                                                <form action="{{ route('seller.withdrawals.cancel', $withdrawal) }}"
                                                      method="POST"
                                                      style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Cancel Request"
                                                            class="cancel-withdrawal"
                                    data-id="{{ $withdrawal->id }}"
                                    data-amount="{{ $withdrawal->amount }}">
                                                        <i class="align-middle" data-feather="x"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="align-middle" data-feather="download" style="width: 48px; height: 48px;"></i>
                                            <p class="mt-2">No withdrawal requests found.</p>
                                            @if($balance > 0)
                                                <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary">
                                                    Request Your First Withdrawal
                                                </a>
                                            @else
                                                <p class="text-muted">You need available balance to request withdrawals.</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($withdrawalRequests->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $withdrawalRequests->links() }}
                        </div>
                    @endif
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

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
