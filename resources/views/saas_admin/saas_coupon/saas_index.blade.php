@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Coupons')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Coupons</h5>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Coupon
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Validity</th>
                            <th>Used / Limit</th>
                            <th>Seller</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $key => $coupon)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $coupon->code }}</span>
                                </td>
                                <td>
                                    @if($coupon->discount_type == 'percentage')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        Rs {{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        <i class="align-middle" data-feather="calendar"></i>
                                        {{ $coupon->start_date->format('M d, Y') }} -
                                        {{ $coupon->end_date->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    {{ $coupon->used_count }} /
                                    @if($coupon->usage_limit)
                                        {{ $coupon->usage_limit }}
                                    @else
                                        <span class="text-muted">∞</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->seller)
                                        {{ $coupon->seller->name }}
                                    @else
                                        <span class="badge bg-secondary">Global</span>
                                    @endif
                                </td>
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.coupons.show', $coupon->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No coupons found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
