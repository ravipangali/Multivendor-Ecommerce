@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Subcategory')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Subcategory: {{ $subcategory->name }}</h5>
                <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Subcategories
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

            <form action="{{ route('admin.subcategories.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                    <select class="form-select" name="category_id" required>
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $subcategory->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $subcategory->name) }}" placeholder="Enter subcategory name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" name="slug" id="slug"
                           value="{{ old('slug', $subcategory->slug) }}"
                           placeholder="Enter slug">
                    <small class="text-muted">Leave empty to auto-generate from name. Use lowercase letters, numbers, and hyphens only.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image</label>
                    @if($subcategory->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $subcategory->image) }}" alt="{{ $subcategory->name }}" width="100" class="img-thumbnail">
                        </div>
                    @endif
                    <input type="file" class="form-control" name="image" accept="image/*">
                    <small class="text-muted">Recommended size: 100x100 pixels. Leave empty to keep the current image.</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Subcategory</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
