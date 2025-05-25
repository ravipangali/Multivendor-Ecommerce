@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'User Profile')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">User Profile: {{ $user->name }}</h5>
                <div>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="edit"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="{{ $user->name }}" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                    @else
                        <div class="avatar bg-primary text-white rounded-circle mb-3 mx-auto" style="width: 200px; height: 200px; font-size: 72px; line-height: 200px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ ucfirst($user->role) }}</p>
                    <div class="mt-3">
                        @if($user->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $user->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role:</strong></td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Registered On:</strong></td>
                                        <td>{{ $user->created_at->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated:</strong></td>
                                        <td>{{ $user->updated_at->format('F d, Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($user->role == 'seller')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Seller Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><strong>Shop Name:</strong></td>
                                        <td>{{ $user->seller->shop_name ?? 'Not set' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Shop Address:</strong></td>
                                        <td>{{ $user->seller->address ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Commission Rate:</strong></td>
                                        <td>{{ $user->seller->commission_rate ?? '0' }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($user->role == 'customer')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Customer Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-0 text-center">
                                <div class="col-4">
                                    <div class="p-3 border-end">
                                        <h4 class="mb-0">{{ $user->orders_count ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Orders</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 border-end">
                                        <h4 class="mb-0">Rs {{ $user->total_spent ?? '0.00' }}</h4>
                                        <p class="text-muted mb-0">Total Spent</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3">
                                        <h4 class="mb-0">{{ $user->wishlist_count ?? 0 }}</h4>
                                        <p class="text-muted mb-0">Wishlist Items</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
