@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Add New Blog Category')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Add New Blog Category</h5>
                <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('admin.blog-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Main Content -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Category Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{ old('name') }}" placeholder="Enter category name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control" name="slug" id="slug"
                                           value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                    <small class="text-muted">Leave empty to auto-generate from name</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4"
                                              placeholder="Brief description of the category">{{ old('description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Parent Category</label>
                                    <select class="form-select" name="parent_id">
                                        <option value="">Select Parent Category (Optional)</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Leave empty to create a root category</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">SEO Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title"
                                           value="{{ old('meta_title') }}" placeholder="SEO title for search engines">
                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="3"
                                              placeholder="SEO description for search engines">{{ old('meta_description') }}</textarea>
                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keywords"
                                           value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                                    <small class="text-muted">Separate keywords with commas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="number" class="form-control" name="position" min="0"
                                           value="{{ old('position', '0') }}" placeholder="0">
                                    <small class="text-muted">Higher numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <!-- Category Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Category Image</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted">Recommended size: 300x200 pixels</small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="align-middle" data-feather="save"></i> Save Category
                                    </button>
                                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                                        <i class="align-middle" data-feather="x"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');

    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });
});
</script>
@endpush
