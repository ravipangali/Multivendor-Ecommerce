@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Category')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Category: {{ $category->name }}</h5>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Categories
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ old('name', $category->name) }}"
                           placeholder="Enter category name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" id="slug"
                           value="{{ old('slug', $category->slug) }}"
                           placeholder="Enter slug">
                    <small class="text-muted">Leave empty to auto-generate from name. Use lowercase letters, numbers, and hyphens only.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    @if($category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="100" class="img-thumbnail">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="text-muted">Recommended size: 100x100 pixels. Leave empty to keep the current image.</small>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="1" {{ (old('status', $category->status) == '1') ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (old('status', $category->status) == '0') ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured</label>
                        <select class="form-select" name="featured" required>
                            <option value="1" {{ (old('featured', $category->featured) == '1') ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ (old('featured', $category->featured) == '0') ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
