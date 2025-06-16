@extends('saas_seller.saas_layouts.saas_layout')

@section('content')
<div class="container-fluid p-0">
    <div class="mb-3">
        <h1 class="h3 d-inline align-middle">Coupon Details</h1>
        <div class="float-end">
            <a href="{{ route('seller.coupons.edit', $coupon) }}" class="btn btn-primary">
                <i class="align-middle" data-feather="edit"></i> Edit
            </a>
            <a href="{{ route('seller.coupons.index') }}" class="btn btn-secondary">
                <i class="align-middle" data-feather="arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $coupon->code }}</h5>
                    <div class="card-actions">
                        @php
                            $now = now();
                            $start = \Carbon\Carbon::parse($coupon->start_date);
                            $end = \Carbon\Carbon::parse($coupon->end_date);
                        @endphp

                        @if($now < $start)
                            <span class="badge bg-warning">Upcoming</span>
                        @elseif($now > $end)
                            <span class="badge bg-danger">Expired</span>
                        @elseif($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit)
                            <span class="badge bg-secondary">Used Up</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Coupon Code</h6>
                            <p class="h4 text-primary">{{ $coupon->code }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Discount</h6>
                            <p class="h4">
                                @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_value }}% OFF
                                @else
                                    Rs {{ number_format($coupon->discount_value, 2) }} OFF
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($coupon->description)
                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $coupon->description }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Valid From</h6>
                            <p>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Valid Until</h6>
                            <p>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Times Used</h6>
                            <p>
                                {{ $coupon->usage_count ?? 0 }}
                                @if($coupon->usage_limit)
                                    / {{ $coupon->usage_limit }}
                                @else
                                    / Unlimited
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Created</h6>
                            <p>{{ $coupon->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Usage Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Usage Statistics</h5>
                </div>
                <div class="card-body">
                    @if($coupon->usage_limit)
                        @php
                            $usagePercentage = $coupon->usage_limit > 0 ? (($coupon->usage_count ?? 0) / $coupon->usage_limit) * 100 : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Usage Progress</span>
                                <span class="text-muted">{{ number_format($usagePercentage, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: {{ $usagePercentage }}%"
                                     aria-valuenow="{{ $usagePercentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <p class="text-muted small">
                            {{ ($coupon->usage_limit ?? 0) - ($coupon->usage_count ?? 0) }} uses remaining
                        </p>
                    @else
                        <p class="text-muted">Unlimited usage allowed</p>
                        <p class="h5">{{ $coupon->usage_count ?? 0 }} times used</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('seller.coupons.edit', $coupon) }}" class="btn btn-primary">
                            <i class="align-middle" data-feather="edit"></i> Edit Coupon
                        </a>

                        @if($coupon->usage_count == 0)
                        <form action="{{ route('seller.coupons.destroy', $coupon) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger w-100 delete-confirm"
                                    data-confirm-message="Are you sure you want to delete this coupon?">
                                <i class="align-middle" data-feather="trash-2"></i> Delete Coupon
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
