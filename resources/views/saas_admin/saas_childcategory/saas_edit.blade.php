@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Child Category')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Child Category: {{ $childcategory->name }}</h5>
                <a href="{{ route('admin.childcategories.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Child Categories
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

            <form action="{{ route('admin.childcategories.update', $childcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @livewire('saas-category-subcategory-dropdown', ['categoryId' => old('category_id', $parentCategoryId), 'subcategoryId' => old('subcategory_id', $childcategory->sub_category_id)])

                <div class="mb-3">
                    <label class="form-label">Child Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $childcategory->name) }}" placeholder="Enter child category name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" id="slug"
                           value="{{ old('slug', $childcategory->slug) }}"
                           placeholder="Enter slug">
                    <small class="text-muted">Leave empty to auto-generate from name. Use lowercase letters, numbers, and hyphens only.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    @if($childcategory->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $childcategory->image) }}" alt="{{ $childcategory->name }}" width="100" class="img-thumbnail">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="text-muted">Recommended size: 100x100 pixels. Leave empty to keep the current image.</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Child Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
