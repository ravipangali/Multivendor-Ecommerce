@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Banner')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Banner</h5>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Banners
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

            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Banner Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="link_url" class="form-label">Link URL</label>
                            <input type="url" class="form-control" id="link_url" name="link_url" value="{{ old('link_url') }}" placeholder="https://example.com">
                            <small class="text-muted">Where users will be directed when they click on the banner</small>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                            <select class="form-select" id="position" name="position" required>
                                <option value="">Select Position</option>
                                @foreach($positions as $key => $label)
                                    <option value="{{ $key }}" {{ old('position') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_0">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <small class="text-muted">Recommended size depends on position:
                                <br>Popup Banner: 800×600px or 1000×750px
                                <br>Footer Banner: 1200×200px
                                <br>Main Section Banner: 1200×400px
                            </small>
                        </div>

                        <div class="mt-3" id="image-preview-container" style="display: none;">
                            <label class="form-label">Image Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="image-preview" src="#" alt="Banner Preview" style="max-height: 200px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Create Banner</button>
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
    });
</script>
@endsection
