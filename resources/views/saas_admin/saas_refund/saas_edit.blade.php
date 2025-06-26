@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Process Refund Request')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Process Refund Request #{{ $refund->id }}</h1>
        <div class="float-end">
            <a href="{{ route('admin.refunds.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back to Refunds
            </a>
            <a href="{{ route('admin.refunds.show', $refund->id) }}" class="btn btn-info">
                <i class="align-middle" data-feather="eye"></i> View Details
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Process Refund</h5>
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

                    <div class="mb-4">
                        <h4>Approval Form</h4>
                        <p>Approve the refund request. This will mark the refund as approved and it can be processed for payment.</p>
                        <form action="{{ route('admin.refunds.approve', $refund->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="approve_admin_notes" class="form-label">Admin Notes (Optional)</label>
                                <textarea class="form-control" id="approve_admin_notes" name="admin_notes" rows="3" placeholder="Add any internal notes about this approval..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="align-middle" data-feather="check-circle"></i> Approve Refund
                            </button>
                        </form>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <h4>Rejection Form</h4>
                        <p>Reject the refund request. This will permanently mark the refund as rejected.</p>
                        <form action="{{ route('admin.refunds.reject', $refund->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rejected_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejected_reason" name="rejected_reason" rows="3" required placeholder="Provide a clear reason for rejecting the request..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="reject_admin_notes" class="form-label">Admin Notes (Optional)</label>
                                <textarea class="form-control" id="reject_admin_notes" name="admin_notes" rows="3" placeholder="Add any internal notes about this rejection..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class="align-middle" data-feather="x-circle"></i> Reject Refund
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Refund Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Refund Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Refund ID:</dt>
                        <dd class="col-sm-9"><code>#{{ $refund->id }}</code></dd>

                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9">
                           <span class="badge bg-warning">Pending Review</span>
                        </dd>

                        <dt class="col-sm-3">Request Date:</dt>
                        <dd class="col-sm-9">{{ $refund->created_at->format('d M Y, h:i A') }}</dd>

                        <dt class="col-sm-3">Customer Reason:</dt>
                        <dd class="col-sm-9">
                            <div class="bg-light p-3 rounded">
                                {{ $refund->customer_reason }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Name:</dt>
                        <dd class="col-7">{{ $refund->customer->name }}</dd>

                        <dt class="col-5">Email:</dt>
                        <dd class="col-7">{{ $refund->customer->email }}</dd>

                        <dt class="col-5">Phone:</dt>
                        <dd class="col-7">{{ $refund->customer->phone ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Financial Summary</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-7">Order Amount:</dt>
                        <dd class="col-5">Rs {{ number_format($refund->order_amount, 2) }}</dd>

                        <dt class="col-7 border-top pt-2"><strong>Refund Amount:</strong></dt>
                        <dd class="col-5 border-top pt-2"><strong class="text-danger">Rs {{ number_format($refund->refund_amount, 2) }}</strong></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection