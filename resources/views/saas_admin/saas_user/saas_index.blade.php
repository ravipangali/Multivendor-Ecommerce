@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Users Management')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    @if(request()->role)
                        {{ ucfirst(request()->role) }}s
                    @else
                        All Users
                    @endif
                </h5>
                <div>
                    <div class="btn-group me-2" role="group">
                        <a href="{{ route('admin.users.index') }}" class="btn {{ !request()->role ? 'btn-primary' : 'btn-primary' }}">All</a>
                        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn {{ request()->role == 'admin' ? 'btn-primary' : 'btn-primary' }}">Admins</a>
                        <a href="{{ route('admin.users.index', ['role' => 'seller']) }}" class="btn {{ request()->role == 'seller' ? 'btn-primary' : 'btn-primary' }}">Sellers</a>
                        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="btn {{ request()->role == 'customer' ? 'btn-primary' : 'btn-primary' }}">Customers</a>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="plus"></i> Add New User
                    </a>
                </div>
            </div>
        </div>
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
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $key }}</td>
                                <td>
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="{{ $user->name }}" width="40" height="40" class="rounded-circle">
                                    @else
                                        <div class="avatar bg-primary text-white d-flex justify-content-center align-items-center rounded-circle">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @elseif($user->role == 'seller')
                                        <span class="badge bg-success">Seller</span>
                                    @elseif($user->role == 'customer')
                                        <span class="badge bg-info">Customer</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="align-middle" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
