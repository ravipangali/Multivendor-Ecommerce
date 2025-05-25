@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Create Seller')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Create New Seller</h5>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Sellers
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

            <form action="{{ route('admin.sellers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Basic Information</h6>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., +977-9841234567">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Account Status <span class="text-danger">*</span></label>
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

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                            <small class="text-muted">Upload profile photo (max 2MB)</small>
                        </div>

                        <div class="mt-3" id="profile-preview-container" style="display: none;">
                            <label class="form-label">Profile Photo Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="profile-preview" src="#" alt="Profile Photo Preview" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>

                    <!-- Store Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Store Information</h6>

                        <div class="mb-3">
                            <label for="store_name" class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="store_name" name="store_name" value="{{ old('store_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="store_description" class="form-label">Store Description</label>
                            <textarea class="form-control" id="store_description" name="store_description" rows="3" placeholder="Brief description about the store">{{ old('store_description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Store Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Store physical address">{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="is_approved" class="form-label">Seller Approval <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_approved" id="is_approved_1" value="1" {{ old('is_approved', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved_1">Approved</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_approved" id="is_approved_0" value="0" {{ old('is_approved') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved_0">Pending</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="store_logo" class="form-label">Store Logo</label>
                            <input type="file" class="form-control" id="store_logo" name="store_logo" accept="image/*">
                            <small class="text-muted">Upload store logo (Recommended size: 200x200px)</small>
                        </div>

                        <div class="mt-3" id="logo-preview-container" style="display: none;">
                            <label class="form-label">Logo Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="logo-preview" src="#" alt="Store Logo Preview" style="max-height: 100px; max-width: 100%;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="store_banner" class="form-label">Store Banner</label>
                            <input type="file" class="form-control" id="store_banner" name="store_banner" accept="image/*">
                            <small class="text-muted">Upload store banner (Recommended size: 1200x400px)</small>
                        </div>

                        <div class="mt-3" id="banner-preview-container" style="display: none;">
                            <label class="form-label">Banner Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="banner-preview" src="#" alt="Store Banner Preview" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Create Seller</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Profile photo preview
        const profileInput = document.getElementById('profile_photo');
        const profilePreviewContainer = document.getElementById('profile-preview-container');
        const profilePreview = document.getElementById('profile-preview');

        profileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.src = e.target.result;
                    profilePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                profilePreviewContainer.style.display = 'none';
            }
        });

        // Store logo preview
        const logoInput = document.getElementById('store_logo');
        const logoPreviewContainer = document.getElementById('logo-preview-container');
        const logoPreview = document.getElementById('logo-preview');

        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                logoPreviewContainer.style.display = 'none';
            }
        });

        // Store banner preview
        const bannerInput = document.getElementById('store_banner');
        const bannerPreviewContainer = document.getElementById('banner-preview-container');
        const bannerPreview = document.getElementById('banner-preview');

        bannerInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    bannerPreview.src = e.target.result;
                    bannerPreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                bannerPreviewContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection
