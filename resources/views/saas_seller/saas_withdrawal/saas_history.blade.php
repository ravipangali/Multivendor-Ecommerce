@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Withdrawal History</h1>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary">
                <i class="align-middle" data-feather="plus"></i> New Withdrawal
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <strong>{{ $withdrawal->created_at->format('d M Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <strong>Rs {{ number_format($withdrawal->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}</span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($withdrawal->notes)
                                            {{ Str::limit($withdrawal->notes, 30) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('seller.withdrawals.show', $withdrawal) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            @if($withdrawal->status == 'pending')
                                                <form action="{{ route('seller.withdrawals.cancel', $withdrawal) }}"
                                                      method="POST"
                                                      style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            class="cancel-withdrawal" data-id="{{ $withdrawal->id }}" data-amount="{{ $withdrawal->amount }}">
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
                                            <span class="rs-icon rs-icon-xl align-middle" style="width: 48px; height: 48px; font-size: 20px;">Rs</span>
                                            <p class="mt-2">No withdrawal history found.</p>
                                            <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary">
                                                Request Your First Withdrawal
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($withdrawals->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Total Requested</h5>
                    <h3 class="text-primary">Rs {{ number_format($withdrawals->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Completed</h5>
                    <h3 class="text-success">Rs {{ number_format($withdrawals->where('status', 'completed')->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Pending</h5>
                    <h3 class="text-warning">Rs {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Total Requests</h5>
                    <h3 class="text-info">{{ $withdrawals->total() }}</h3>
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
