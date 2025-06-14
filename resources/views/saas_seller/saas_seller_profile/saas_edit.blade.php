@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Edit Seller Profile')

@section('content')
<div class="col-12">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Seller Profile</h6>
                </div>
                <div class="card-body">
                    @if(!$sellerProfile->is_approved)
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            Your profile is currently pending approval. You can still update your information.
                        </div>
                    @endif

                    <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="store_name" class="font-weight-bold">Store Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('store_name') is-invalid @enderror"
                                   id="store_name"
                                   name="store_name"
                                   value="{{ old('store_name', $sellerProfile->store_name) }}"
                                   placeholder="Enter your store name"
                                   required>
                            @error('store_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="store_description" class="font-weight-bold">Store Description</label>
                            <textarea class="form-control @error('store_description') is-invalid @enderror"
                                      id="store_description"
                                      name="store_description"
                                      rows="4"
                                      placeholder="Describe your store">{{ old('store_description', $sellerProfile->store_description) }}</textarea>
                            @error('store_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address" class="font-weight-bold">Store Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Enter your store address">{{ old('address', $sellerProfile->address) }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="store_logo" class="font-weight-bold">Store Logo</label>
                            <input type="file"
                                   class="form-control @error('store_logo') is-invalid @enderror"
                                   id="store_logo"
                                   name="store_logo"
                                   accept="image/*">
                            <small class="text-muted">Upload a square logo (Recommended size: 200x200px)</small>
                            @error('store_logo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @if($sellerProfile->store_logo)
                                <div class="mt-2">
                                    <label class="font-weight-bold">Current Logo:</label>
                                    <div class="border p-2 text-center">
                                        <img src="{{ Storage::url($sellerProfile->store_logo) }}"
                                             alt="Store Logo"
                                             class="img-fluid"
                                             style="max-height: 100px;">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="store_banner" class="font-weight-bold">Store Banner</label>
                            <input type="file"
                                   class="form-control @error('store_banner') is-invalid @enderror"
                                   id="store_banner"
                                   name="store_banner"
                                   accept="image/*">
                            <small class="text-muted">Upload a banner image (Recommended size: 1200x300px)</small>
                            @error('store_banner')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            @if($sellerProfile->store_banner)
                                <div class="mt-2">
                                    <label class="font-weight-bold">Current Banner:</label>
                                    <div class="border p-2 text-center">
                                        <img src="{{ Storage::url($sellerProfile->store_banner) }}"
                                             alt="Store Banner"
                                             class="img-fluid"
                                             style="max-height: 150px;">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('seller.profile') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#store_logo').change(function() {
        previewImage(this, 'store_logo_preview');
    });

    $('#store_banner').change(function() {
        previewImage(this, 'store_banner_preview');
    });
</script>
@endpush
@endsection
