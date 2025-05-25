@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Brand')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Brand: {{ $brand->name }}</h5>
                <div>
                    <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Brand
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Brands
                    </a>
                </div>
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

            <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                            <small class="text-muted">Enter the brand name (e.g., Nike, Apple, Samsung)</small>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-muted">(auto-generated)</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}">
                            <small class="text-muted">The slug will be used in URLs (e.g., nike, apple, samsung)</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Brand Logo</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Upload a new logo to replace the current one (Recommended size: 400x200px)</small>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Current Logo:</label>
                            <div class="border p-3 text-center">
                                @if($brand->image)
                                    <img src="{{ asset('storage/'.$brand->image) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 150px;">
                                @else
                                    <div class="text-muted">No logo image uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3" id="image-preview-container" style="display: none;">
                            <label class="form-label">New Logo Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="image-preview" src="#" alt="Brand Logo Preview" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreviewContainer.style.display = 'none';
            }
        });

        // Auto-generate slug from name
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        nameInput.addEventListener('keyup', function() {
            if (!slugInput.getAttribute('data-manual-input')) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '') // Remove special chars
                    .replace(/\s+/g, '-')     // Replace spaces with hyphens
                    .replace(/-+/g, '-');     // Replace multiple hyphens with single hyphen
            }
        });

        // Flag when user manually edits the slug
        slugInput.addEventListener('input', function() {
            slugInput.setAttribute('data-manual-input', '1');
        });
    });
</script>
@endsection
