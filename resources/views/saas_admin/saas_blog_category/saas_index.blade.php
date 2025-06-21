@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Blog Categories')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Blog Categories</h5>
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Category
                </a>
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

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('admin.blog-categories.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="align-middle" data-feather="search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.blog-categories.index') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th>Posts</th>
                            <th>Status</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $key => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $key }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" width="50" class="rounded">
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    @if($category->description)
                                        <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge bg-info">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="text-muted">Root Category</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->blog_posts_count ?? 0 }} posts</span>
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $category->position }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customer.blog.category', $category->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View Category">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blog-categories.toggle-status', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Toggle Status">
                                                <i class="align-middle" data-feather="power"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger delete-category"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                title="Delete">
                                            <i class="align-middle" data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="align-middle" data-feather="folder" style="font-size: 48px;"></i>
                                        <p class="mt-2">No blog categories found.</p>
                                        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">Create First Category</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
<!-- Hidden form for delete -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete category confirmation with SweetAlert
    document.querySelectorAll('.delete-category').forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-id');
            const categoryName = this.getAttribute('data-name');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete blog category "${categoryName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('admin.blog-categories.index') }}/${categoryId}`;
                    form.submit();
                }
            });
        });
    });

    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endpush
