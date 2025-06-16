@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Coupons')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h1 class="h3 mb-3">Coupons</h1>
        </div>

        <div class="col-auto ms-auto text-end mt-n1">
            <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary">
                <i class="align-middle" data-feather="plus"></i> Add Coupon
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
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Discount</th>
                                    <th>Valid Period</th>
                                    <th>Usage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>
                                        <strong>{{ $coupon->code }}</strong>
                                    </td>
                                    <td>
                                        {{ Str::limit($coupon->description, 50) }}
                                    </td>
                                    <td>
                                        @if($coupon->discount_type == 'percentage')
                                            {{ $coupon->discount_value }}%
                                        @else
                                            Rs {{ number_format($coupon->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $coupon->usage_count ?? 0 }}
                                            @if($coupon->usage_limit)
                                                / {{ $coupon->usage_limit }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('seller.coupons.show', $coupon) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('seller.coupons.edit', $coupon) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                            <form action="{{ route('seller.coupons.destroy', $coupon) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger delete-confirm"
                                                        data-confirm-message="Are you sure you want to delete this coupon?">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="align-middle" data-feather="tag" style="width: 48px; height: 48px;"></i>
                                            <p class="mt-2">No coupons found.</p>
                                            <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary">
                                                Create Your First Coupon
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($coupons->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $coupons->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection