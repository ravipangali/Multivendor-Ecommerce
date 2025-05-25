@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Coupon Details')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Coupon Details</h5>
                <div>
                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Edit Coupon
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Coupons
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Coupon Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">Code</th>
                            <td>
                                <span class="badge bg-primary">{{ $coupon->code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $coupon->description ?? 'No description provided' }}</td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_value }}% off
                                @else
                                    Rs {{ number_format($coupon->discount_value, 2) }} off
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Period</th>
                            <td>
                                {{ $coupon->start_date->format('M d, Y') }} - {{ $coupon->end_date->format('M d, Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $now = now();
                                    $isActive = $coupon->start_date <= $now && $coupon->end_date >= $now;
                                    $isExpired = $coupon->end_date < $now;
                                    $isUpcoming = $coupon->start_date > $now;
                                    $isLimitReached = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                                @endphp

                                @if($isLimitReached)
                                    <span class="badge bg-danger">Limit Reached</span>
                                @elseif($isExpired)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($isUpcoming)
                                    <span class="badge bg-warning">Upcoming</span>
                                @elseif($isActive)
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Seller</th>
                            <td>
                                @if($coupon->seller)
                                    {{ $coupon->seller->name }}
                                @else
                                    <span class="badge bg-secondary">Global Coupon (All Sellers)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $coupon->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $coupon->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Usage Information</h6>
                    <div class="card border mb-4">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h3>{{ $coupon->used_count }}</h3>
                                    <p class="text-muted mb-0">Times Used</p>
                                </div>
                                <div class="col-6">
                                    <h3>
                                        @if($coupon->usage_limit)
                                            {{ max(0, $coupon->usage_limit - $coupon->used_count) }}
                                        @else
                                            <i class="align-middle" data-feather="infinity"></i>
                                        @endif
                                    </h3>
                                    <p class="text-muted mb-0">Remaining</p>
                                </div>
                            </div>

                            @if($coupon->usage_limit)
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Usage Limit: {{ $coupon->usage_limit }}</span>
                                        <span>{{ round(($coupon->used_count / $coupon->usage_limit) * 100) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $percentUsed = ($coupon->used_count / $coupon->usage_limit) * 100;
                                        @endphp
                                        <div class="progress-bar {{ $percentUsed > 80 ? 'bg-danger' : 'bg-success' }}"
                                            role="progressbar" style="width: {{ $percentUsed }}%;"
                                            aria-valuenow="{{ $percentUsed }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    This coupon has no usage limit.
                                </div>
                            @endif
                        </div>
                    </div>

                    <h6 class="text-muted">Validity</h6>
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <i class="align-middle me-1" data-feather="calendar"></i> Start Date
                                    <h5>{{ $coupon->start_date->format('M d, Y') }}</h5>
                                </div>
                                <div class="text-end">
                                    <i class="align-middle me-1" data-feather="calendar"></i> End Date
                                    <h5>{{ $coupon->end_date->format('M d, Y') }}</h5>
                                </div>
                            </div>

                            @php
                                $totalDays = $coupon->start_date->diffInDays($coupon->end_date);
                                $daysLeft = now()->diffInDays($coupon->end_date, false);
                                $percentLeft = $totalDays > 0 ? max(0, min(100, ($daysLeft / $totalDays) * 100)) : 0;
                            @endphp

                            @if($coupon->end_date >= now() && $coupon->start_date <= now())
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Time Remaining</span>
                                    <span>{{ $daysLeft }} days left</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $percentLeft }}%;" aria-valuenow="{{ $percentLeft }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            @elseif($coupon->start_date > now())
                                <div class="alert alert-warning mb-0">
                                    <i class="align-middle me-2" data-feather="clock"></i>
                                    This coupon will become active in {{ now()->diffInDays($coupon->start_date) }} days.
                                </div>
                            @else
                                <div class="alert alert-danger mb-0">
                                    <i class="align-middle me-2" data-feather="alert-circle"></i>
                                    This coupon has expired.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-confirm">
                        <i class="align-middle me-1" data-feather="trash-2"></i> Delete Coupon
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
