@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Blog Posts')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Blog Posts</h5>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">
                    <i class="align-middle" data-feather="plus"></i> Add New Post
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
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.blog-posts.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="align-middle" data-feather="search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" action="{{ route('admin.blog-posts.index') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Published</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.blog-posts.index') }}">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-2">
                    <form method="GET" action="{{ route('admin.blog-posts.index') }}">
                        <select name="featured" class="form-select" onchange="this.form.submit()">
                            <option value="">All Posts</option>
                            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                            <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
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
                            <th>Author</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Views</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $key => $post)
                            <tr>
                                <td>{{ $posts->firstItem() + $key }}</td>
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" width="50" class="rounded">
                                    @else
                                        <span class="badge bg-secondary">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($post->title, 40) }}</strong>
                                    @if($post->excerpt)
                                        <br><small class="text-muted">{{ Str::limit($post->excerpt, 60) }}</small>
                                    @endif
                                    @if($post->tags && count($post->tags) > 0)
                                        <br>
                                        @foreach(array_slice($post->tags, 0, 3) as $tag)
                                            <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($post->author)
                                        {{ $post->author->name }}
                                    @else
                                        <span class="text-muted">No Author</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->category)
                                        <span class="badge bg-info">{{ $post->category->name }}</span>
                                    @else
                                        <span class="text-muted">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->status)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->is_featured)
                                        <span class="badge bg-primary">Featured</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $post->views_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($post->published_at)
                                        {{ $post->published_at->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">Not Published</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($post->status)
                                            <a href="{{ route('customer.blog.show', $post->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="View Post">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.blog-posts.edit', $post->id) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i class="align-middle" data-feather="edit"></i>
                                        </a>
                                        <form action="{{ route('admin.blog-posts.toggle-status', $post->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Toggle Status">
                                                <i class="align-middle" data-feather="power"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger delete-blog-post"
                                                data-id="{{ $post->id }}"
                                                data-title="{{ $post->title }}"
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
                                        <i class="align-middle" data-feather="edit-3" style="font-size: 48px;"></i>
                                        <p class="mt-2">No blog posts found.</p>
                                        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">Create First Post</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
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
    // Delete blog post confirmation with SweetAlert
    document.querySelectorAll('.delete-blog-post').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-id');
            const postTitle = this.getAttribute('data-title');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete blog post "${postTitle}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `{{ route('admin.blog-posts.index') }}/${postId}`;
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
