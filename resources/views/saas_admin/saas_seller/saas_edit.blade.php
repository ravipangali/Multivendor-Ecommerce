@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Seller')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Seller: {{ $seller->name }}</h5>
                <div>
                    <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Seller
                    </a>
                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Sellers
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

            <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Basic Information</h6>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $seller->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $seller->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $seller->phone) }}" placeholder="e.g., +977-9841234567">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave empty to keep current password</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label">Account Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" {{ old('is_active', $seller->is_active) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0" {{ old('is_active', $seller->is_active) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_0">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commission" class="form-label">Commission (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="commission" name="commission"
                                    value="{{ old('commission', $seller->commission ?? $defaultCommission ?? 0) }}"
                                    min="0" max="100" step="0.01" placeholder="0.00">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Commission percentage for this seller (default: {{ $defaultCommission ?? 0 }}%)</small>
                        </div>

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                            <small class="text-muted">Upload new photo to replace current one (max 2MB)</small>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Current Profile Photo:</label>
                            <div class="border p-3 text-center">
                                @if($seller->profile_photo)
                                    <img src="{{ asset('storage/'.$seller->profile_photo) }}" alt="{{ $seller->name }}" class="img-fluid" style="max-height: 150px;">
                                @else
                                    <div class="text-muted">No photo uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3" id="profile-preview-container" style="display: none;">
                            <label class="form-label">New Profile Photo Preview:</label>
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
                            <input type="text" class="form-control" id="store_name" name="store_name" value="{{ old('store_name', $seller->sellerProfile->store_name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="store_description" class="form-label">Store Description</label>
                            <textarea class="form-control" id="store_description" name="store_description" rows="3" placeholder="Brief description about the store">{{ old('store_description', $seller->sellerProfile->store_description ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Store Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Store physical address">{{ old('address', $seller->sellerProfile->address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="is_approved" class="form-label">Seller Approval <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_approved" id="is_approved_1" value="1" {{ old('is_approved', $seller->sellerProfile->is_approved ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved_1">Approved</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_approved" id="is_approved_0" value="0" {{ old('is_approved', $seller->sellerProfile->is_approved ?? '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_approved_0">Pending</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="store_logo" class="form-label">Store Logo</label>
                            <input type="file" class="form-control" id="store_logo" name="store_logo" accept="image/*">
                            <small class="text-muted">Upload new logo to replace current one (Recommended size: 200x200px)</small>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Current Store Logo:</label>
                            <div class="border p-3 text-center">
                                @if($seller->sellerProfile && $seller->sellerProfile->store_logo)
                                    <img src="{{ asset('storage/'.$seller->sellerProfile->store_logo) }}" alt="Store Logo" class="img-fluid" style="max-height: 100px;">
                                @else
                                    <div class="text-muted">No logo uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3" id="logo-preview-container" style="display: none;">
                            <label class="form-label">New Logo Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="logo-preview" src="#" alt="Store Logo Preview" style="max-height: 100px; max-width: 100%;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="store_banner" class="form-label">Store Banner</label>
                            <input type="file" class="form-control" id="store_banner" name="store_banner" accept="image/*">
                            <small class="text-muted">Upload new banner to replace current one (Recommended size: 1200x400px)</small>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Current Store Banner:</label>
                            <div class="border p-3 text-center">
                                @if($seller->sellerProfile && $seller->sellerProfile->store_banner)
                                    <img src="{{ asset('storage/'.$seller->sellerProfile->store_banner) }}" alt="Store Banner" class="img-fluid" style="max-height: 150px;">
                                @else
                                    <div class="text-muted">No banner uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3" id="banner-preview-container" style="display: none;">
                            <label class="form-label">New Banner Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="banner-preview" src="#" alt="Store Banner Preview" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Seller</button>
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
