@extends('saas_admin.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Withdrawal Details</h1>
        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary float-end">
            <i class="align-middle" data-feather="arrow-left"></i> Back to Withdrawals
        </a>
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
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Withdrawal Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Withdrawal ID:</dt>
                        <dd class="col-sm-8"><code>#{{ $withdrawal->id }}</code></dd>

                        <dt class="col-sm-4">Seller:</dt>
                        <dd class="col-sm-8">
                            <a href="#">{{ $withdrawal->user->name }}</a>
                            <br>
                            <small class="text-muted">{{ $withdrawal->user->email }}</small>
                        </dd>

                        <dt class="col-sm-4">Amount:</dt>
                        <dd class="col-sm-8">
                            <strong class="text-primary">Rs {{ number_format($withdrawal->requested_amount, 2) }}</strong>
                        </dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            @include('saas_admin.saas_withdrawal.partials.status_badge', ['status' => $withdrawal->status])
                        </dd>

                        <dt class="col-sm-4">Request Date:</dt>
                        <dd class="col-sm-8">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</dd>

                        @if($withdrawal->processed_at)
                            <dt class="col-sm-4">Processed Date:</dt>
                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($withdrawal->processed_at)->format('d M Y, h:i A') }}</dd>
                        @endif

                        @if($withdrawal->processedBy)
                            <dt class="col-sm-4">Processed By:</dt>
                            <dd class="col-sm-8">{{ $withdrawal->processedBy->name }}</dd>
                        @endif

                        @if($withdrawal->notes)
                            <dt class="col-sm-4">Seller Notes:</dt>
                            <dd class="col-sm-8">{{ $withdrawal->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if($withdrawal->status == 'pending')
                <div class="card">
                    <div class="card-header"><h5 class="card-title">Actions</h5></div>
                    <div class="card-body">
                        <p>Review the withdrawal request and choose an action below.</p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="align-middle" data-feather="check"></i> Approve
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="align-middle" data-feather="x"></i> Reject
                        </button>
                    </div>
                </div>
            @endif

            @if($withdrawal->admin_notes || $withdrawal->rejected_reason)
                <div class="card">
                    <div class="card-header"><h5 class="card-title">Admin Information</h5></div>
                    <div class="card-body">
                        @if($withdrawal->rejected_reason)
                        <div class="mb-3">
                            <strong>Rejection Reason:</strong>
                            <p class="text-danger">{{ $withdrawal->rejected_reason }}</p>
                        </div>
                        @endif
                        @if($withdrawal->admin_notes)
                        <div>
                            <strong>Admin Notes:</strong>
                            <p>{{ $withdrawal->admin_notes }}</p>
                        </div>
                        @endif
                         @if ($withdrawal->admin_attachment)
                            <hr>
                            <strong>Attachment:</strong><br>
                            <a href="{{ route('admin.withdrawals.download-attachment', $withdrawal) }}" class="btn btn-info btn-sm">
                                <i class="align-middle" data-feather="download"></i> Download Attachment
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Payment Details -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    @if($withdrawal->paymentMethod)
                        @foreach($withdrawal->paymentMethod->details as $key => $value)
                            <dl class="row">
                                <dt class="col-5">{{ ucfirst(str_replace('_', ' ', $key)) }}:</dt>
                                <dd class="col-7">{{ $value }}</dd>
                            </dl>
                        @endforeach
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
                        <dd class="col-5 text-end">Rs {{ number_format($withdrawal->requested_amount, 2) }}</dd>

                        <dt class="col-7">Gateway Fee ({{ $withdrawal->gateway_fee }}%):</dt>
                        <dd class="col-5 text-end">- Rs {{ number_format($withdrawal->fee, 2) }}</dd>

                        <dt class="col-7 border-top pt-2"><strong>Net Payout:</strong></dt>
                        <dd class="col-5 border-top pt-2 text-end"><strong>Rs {{ number_format($withdrawal->net_amount, 2) }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="approveModalLabel">Approve Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <p>You are about to approve a withdrawal of <strong>Rs {{ number_format($withdrawal->requested_amount, 2) }}</strong> for <strong>{{ $withdrawal->user->name }}</strong>.</p>
            <div class="mb-3">
                <label for="admin_notes_approve" class="form-label">Admin Notes (Optional)</label>
                <textarea name="admin_notes" id="admin_notes_approve" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="admin_attachment" class="form-label">Attach Proof (Optional)</label>
                <input type="file" name="admin_attachment" id="admin_attachment" class="form-control">
                <small class="form-text text-muted">Attach a screenshot or document as proof of payment.</small>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Confirm Approval</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST">
        @csrf
        <div class="modal-body">
          <p>You are about to reject this withdrawal request.</p>
          <div class="mb-3">
            <label for="rejected_reason" class="form-label">Reason for Rejection</label>
            <textarea name="rejected_reason" id="rejected_reason" class="form-control" rows="3" required></textarea>
          </div>
           <div class="mb-3">
                <label for="admin_notes_reject" class="form-label">Admin Notes (Optional)</label>
                <textarea name="admin_notes" id="admin_notes_reject" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Confirm Rejection</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
