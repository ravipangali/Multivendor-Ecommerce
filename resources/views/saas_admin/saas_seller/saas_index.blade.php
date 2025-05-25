@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Sellers')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Sellers Management</h5>
                <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Seller
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('admin.sellers.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" placeholder="Search by name, email, store..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="approval" class="form-select">
                                <option value="">All Approval</option>
                                <option value="1" {{ request('approval') == '1' ? 'selected' : '' }}>Approved</option>
                                <option value="0" {{ request('approval') == '0' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seller</th>
                            <th>Store Name</th>
                            <th>Email</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $key => $seller)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($seller->profile_photo)
                                            <img src="{{ asset('storage/'.$seller->profile_photo) }}" alt="{{ $seller->name }}" width="40" height="40" class="rounded-circle me-2">
                                        @else
                                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                {{ substr($seller->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $seller->name }}</div>
                                            <small class="text-muted">{{ $seller->phone ?? 'No phone' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($seller->sellerProfile && $seller->sellerProfile->store_logo)
                                            <img src="{{ asset('storage/'.$seller->sellerProfile->store_logo) }}" alt="Store Logo" width="30" height="30" class="rounded me-2">
                                        @endif
                                        <div>
                                            <div>{{ $seller->sellerProfile->store_name ?? 'No store name' }}</div>
                                            @if($seller->sellerProfile && $seller->sellerProfile->address)
                                                <small class="text-muted">{{ Str::limit($seller->sellerProfile->address, 20) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $seller->email }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $seller->products()->count() }} products</span>
                                </td>
                                <td>
                                    @if($seller->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($seller->sellerProfile)
                                        @if($seller->sellerProfile->is_approved)
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Profile</span>
                                    @endif
                                </td>
                                <td>{{ $seller->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn btn-sm btn-primary" title="View">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sellers.edit', $seller->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        @if($seller->sellerProfile)
                                            <form action="{{ route('admin.sellers.toggle-approval', $seller->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $seller->sellerProfile->is_approved ? 'btn-warning' : 'btn-success' }}" title="{{ $seller->sellerProfile->is_approved ? 'Disapprove' : 'Approve' }}">
                                                    <i class="align-middle" data-feather="{{ $seller->sellerProfile->is_approved ? 'x-circle' : 'check-circle' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-confirm" title="Delete">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No sellers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $sellers->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
