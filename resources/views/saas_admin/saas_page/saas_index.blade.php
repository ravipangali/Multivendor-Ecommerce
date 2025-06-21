@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'CMS Pages')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">CMS Pages</h5>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Page
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
                    <form method="GET" action="{{ route('admin.pages.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search pages..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="align-middle" data-feather="search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.pages.index') }}">
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
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Footer</th>
                            <th>Header</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $key => $page)
                            <tr>
                                <td>{{ $pages->firstItem() + $key }}</td>
                                <td>
                                    @if($page->featured_image)
                                        <img src="{{ $page->featured_image_url }}" alt="{{ $page->title }}" width="50" class="rounded">
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($page->title, 30) }}</strong>
                                    @if($page->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($page->excerpt, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $page->slug }}</code></td>
                                <td>
                                    @if($page->author)
                                        {{ $page->author->name }}
                                    @else
                                        <span class="text-muted">No Author</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->in_footer)
                                        <span class="badge bg-info">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->in_header)
                                        <span class="badge bg-info">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($page->published_at)
                                        {{ $page->published_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customer.page', $page->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View Page">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.toggle-status', $page->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Toggle Status">
                                                <i class="align-middle" data-feather="power"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger delete-page"
                                                data-id="{{ $page->id }}"
                                                data-title="{{ $page->title }}"
                                                title="Delete">
                                            <i class="align-middle" data-feather="trash-2"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="align-middle" data-feather="file-text" style="font-size: 48px;"></i>
                                        <p class="mt-2">No pages found.</p>
                                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Create First Page</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pages->links() }}
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
    // Delete page confirmation with SweetAlert
    document.querySelectorAll('.delete-page').forEach(button => {
        button.addEventListener('click', function() {
            const pageId = this.getAttribute('data-id');
            const pageTitle = this.getAttribute('data-title');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete page "${pageTitle}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('admin.pages.index') }}/${pageId}`;
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
