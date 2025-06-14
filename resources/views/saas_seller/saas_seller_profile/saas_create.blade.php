@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Create Seller Profile')

@section('content')
<div class="col-12">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Your Seller Profile</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        Please provide accurate information about your store. This information will be reviewed by our admin team.
                    </div>

                    <form action="{{ route('seller.profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="store_name" class="font-weight-bold">Store Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('store_name') is-invalid @enderror"
                                   id="store_name"
                                   name="store_name"
                                   value="{{ old('store_name') }}"
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
                                      placeholder="Describe your store and what you sell">{{ old('store_description') }}</textarea>
                            @error('store_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address" class="font-weight-bold">Business Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      placeholder="Enter your business address">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_logo" class="font-weight-bold">Store Logo</label>
                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('store_logo') is-invalid @enderror"
                                               id="store_logo"
                                               name="store_logo"
                                               accept="image/*"
                                               onchange="previewImage('store_logo', 'logo_preview')">
                                        <label class="custom-file-label" for="store_logo">Choose file</label>
                                    </div>
                                    @error('store_logo')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended size: 200x200px. Max file size: 2MB
                                    </small>
                                    <div id="logo_preview" class="mt-2"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="store_banner" class="font-weight-bold">Store Banner</label>
                                    <div class="custom-file">
                                        <input type="file"
                                               class="custom-file-input @error('store_banner') is-invalid @enderror"
                                               id="store_banner"
                                               name="store_banner"
                                               accept="image/*"
                                               onchange="previewImage('store_banner', 'banner_preview')">
                                        <label class="custom-file-label" for="store_banner">Choose file</label>
                                    </div>
                                    @error('store_banner')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended size: 1200x300px. Max file size: 2MB
                                    </small>
                                    <div id="banner_preview" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Important:</strong> After submitting your profile, it will be reviewed by our admin team.
                            You will receive an email notification once your account is approved.
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Profile
                            </button>
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Update file input label with selected filename
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose file';
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

// Preview image function
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-fluid rounded';
            img.style.maxWidth = '200px';
            preview.appendChild(img);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

// Handle multiple file inputs
document.querySelectorAll('.custom-file-input').forEach(function(input) {
    input.addEventListener('change', function(e) {
        var fileName = e.target.files[0]?.name || 'Choose file';
        var label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
});
</script>
@endsection
