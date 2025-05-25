@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Customer')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Customer: {{ $customer->name }}</h5>
                <div>
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Customer
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Customers
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

            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Basic Information</h6>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="e.g., +977-9841234567">
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
                            <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" {{ old('is_active', $customer->is_active) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_1">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0" {{ old('is_active', $customer->is_active) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active_0">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile & Address Information -->
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Profile & Address Information</h6>

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                            <small class="text-muted">Upload new photo to replace current one (max 2MB)</small>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Current Photo:</label>
                            <div class="border p-3 text-center">
                                @if($customer->profile_photo)
                                    <img src="{{ asset('storage/'.$customer->profile_photo) }}" alt="{{ $customer->name }}" class="img-fluid" style="max-height: 150px;">
                                @else
                                    <div class="text-muted">No photo uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3" id="image-preview-container" style="display: none;">
                            <label class="form-label">New Photo Preview:</label>
                            <div class="border p-3 text-center">
                                <img id="image-preview" src="#" alt="Profile Photo Preview" style="max-height: 150px; max-width: 100%;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" placeholder="Enter full shipping address">{{ old('shipping_address', $customer->customerProfile->shipping_address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" rows="3" placeholder="Enter full billing address">{{ old('billing_address', $customer->customerProfile->billing_address ?? '') }}</textarea>
                            <small class="text-muted">Leave empty to use shipping address as billing address</small>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('profile_photo');
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
