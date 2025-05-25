@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'General Settings')

@section('styles')
<style>
    .settings-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .settings-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .settings-nav {
        position: sticky;
        top: 1rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .settings-nav .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
    }
    .settings-nav .list-group-item.active {
        background-color: #3b7ddd;
        border-color: #3b7ddd;
    }
    .settings-nav .list-group-item i {
        margin-right: 10px;
    }
    .preview-image {
        width: 120px;
        height: 120px;
        object-fit: contain;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        padding: 5px;
        background-color: #f8f9fa;
    }
    .image-preview-container {
        position: relative;
        margin-bottom: 15px;
    }
    .image-preview-container .remove-image {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .dropzone:hover {
        border-color: #3b7ddd;
    }
    .custom-switch .custom-control-label::before {
        height: 1.5rem;
        width: 2.75rem;
        border-radius: 0.75rem;
    }
    .custom-switch .custom-control-label::after {
        height: calc(1.5rem - 4px);
        width: calc(1.5rem - 4px);
        border-radius: 0.75rem;
    }
    .form-section-title {
        margin-top: 2rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
        color: #3b7ddd;
    }
    .form-section-title:first-of-type {
        margin-top: 0;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="row">
        <!-- Settings navigation -->
        <div class="col-md-3 mb-4">
            <div class="settings-nav">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action active">
                        <i data-feather="settings" class="feather-sm"></i> General
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="list-group-item list-group-item-action">
                        <i data-feather="mail" class="feather-sm"></i> Email
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="list-group-item list-group-item-action">
                        <i data-feather="credit-card" class="feather-sm"></i> Payment
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="list-group-item list-group-item-action">
                        <i data-feather="truck" class="feather-sm"></i> Shipping
                    </a>
                </div>
            </div>
        </div>

        <!-- Settings content -->
        <div class="col-md-9">
            <div class="card settings-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">General Settings</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="resetForm">
                        <i data-feather="refresh-cw" class="feather-sm"></i> Reset
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.general') }}" method="POST" enctype="multipart/form-data" id="generalSettingsForm">
                        @csrf

                        <!-- Site Information -->
                        <h5 class="form-section-title">
                            <i data-feather="info" class="feather-sm me-1"></i> Site Information
                        </h5>

                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                   id="site_name" name="site_name"
                                   value="{{ old('site_name', $settingsArray['site_name'] ?? '') }}" required>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="site_logo" class="form-label">Site Logo</label>
                                <div class="image-preview-container mb-2">
                                    @if(isset($settingsArray['site_logo']))
                                        <img src="{{ Storage::url($settingsArray['site_logo']) }}" alt="Site Logo" class="preview-image" id="logoPreview">
                                        <div class="remove-image" data-input="site_logo">
                                            <i data-feather="x" class="feather-sm"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset('saas_admin/img/placeholder-image.jpg') }}" alt="Site Logo" class="preview-image" id="logoPreview">
                                    @endif
                                </div>
                                <div class="dropzone mb-3" id="logoDropzone">
                                    <i data-feather="upload" class="feather mb-2"></i>
                                    <p class="mb-0">Drag & drop or click to upload</p>
                                    <small class="text-muted">PNG, JPG, GIF up to 2MB</small>
                                    <input type="file" class="d-none" id="site_logo" name="site_logo" accept="image/*">
                                </div>
                                @error('site_logo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="favicon" class="form-label">Favicon</label>
                                <div class="image-preview-container mb-2">
                                    @if(isset($settingsArray['favicon']))
                                        <img src="{{ Storage::url($settingsArray['favicon']) }}" alt="Favicon" class="preview-image" id="faviconPreview">
                                        <div class="remove-image" data-input="favicon">
                                            <i data-feather="x" class="feather-sm"></i>
                                        </div>
                                    @else
                                        <img src="{{ asset('saas_admin/img/placeholder-image.jpg') }}" alt="Favicon" class="preview-image" id="faviconPreview">
                                    @endif
                                </div>
                                <div class="dropzone mb-3" id="faviconDropzone">
                                    <i data-feather="upload" class="feather mb-2"></i>
                                    <p class="mb-0">Drag & drop or click to upload</p>
                                    <small class="text-muted">PNG, ICO up to 1MB</small>
                                    <input type="file" class="d-none" id="favicon" name="favicon" accept="image/x-icon,image/png">
                                </div>
                                @error('favicon')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror"
                                      id="site_description" name="site_description" rows="3">{{ old('site_description', $settingsArray['site_description'] ?? '') }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="site_keywords" class="form-label">Site Keywords</label>
                            <input type="text" class="form-control @error('site_keywords') is-invalid @enderror"
                                   id="site_keywords" name="site_keywords"
                                   value="{{ old('site_keywords', $settingsArray['site_keywords'] ?? '') }}"
                                   placeholder="e.g., ecommerce, shop, online">
                            <small class="text-muted">Separate keywords with commas</small>
                            @error('site_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="footer_text" class="form-label">Footer Text</label>
                            <textarea class="form-control @error('footer_text') is-invalid @enderror"
                                      id="footer_text" name="footer_text" rows="2">{{ old('footer_text', $settingsArray['footer_text'] ?? '© ' . date('Y') . ' ' . ($settingsArray['site_name'] ?? 'Multi Tenant E-commerce') . '. All rights reserved.') }}</textarea>
                            @error('footer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <h5 class="form-section-title">
                            <i data-feather="phone" class="feather-sm me-1"></i> Contact Information
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_email" class="form-label">Site Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('site_email') is-invalid @enderror"
                                       id="site_email" name="site_email"
                                       value="{{ old('site_email', $settingsArray['site_email'] ?? '') }}" required>
                                @error('site_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="site_phone" class="form-label">Site Phone</label>
                                <input type="text" class="form-control @error('site_phone') is-invalid @enderror"
                                       id="site_phone" name="site_phone"
                                       value="{{ old('site_phone', $settingsArray['site_phone'] ?? '') }}">
                                @error('site_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="site_address" class="form-label">Site Address</label>
                            <textarea class="form-control @error('site_address') is-invalid @enderror"
                                      id="site_address" name="site_address" rows="2">{{ old('site_address', $settingsArray['site_address'] ?? '') }}</textarea>
                            @error('site_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Social Media -->
                        <h5 class="form-section-title">
                            <i data-feather="share-2" class="feather-sm me-1"></i> Social Media
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="social_facebook" class="form-label">Facebook URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="facebook" class="feather-sm"></i></span>
                                    <input type="url" class="form-control @error('social_facebook') is-invalid @enderror"
                                           id="social_facebook" name="social_facebook"
                                           value="{{ old('social_facebook', $settingsArray['social_facebook'] ?? '') }}">
                                </div>
                                @error('social_facebook')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="social_twitter" class="form-label">Twitter URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="twitter" class="feather-sm"></i></span>
                                    <input type="url" class="form-control @error('social_twitter') is-invalid @enderror"
                                           id="social_twitter" name="social_twitter"
                                           value="{{ old('social_twitter', $settingsArray['social_twitter'] ?? '') }}">
                                </div>
                                @error('social_twitter')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="social_instagram" class="form-label">Instagram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="instagram" class="feather-sm"></i></span>
                                    <input type="url" class="form-control @error('social_instagram') is-invalid @enderror"
                                           id="social_instagram" name="social_instagram"
                                           value="{{ old('social_instagram', $settingsArray['social_instagram'] ?? '') }}">
                                </div>
                                @error('social_instagram')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="social_linkedin" class="form-label">LinkedIn URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="linkedin" class="feather-sm"></i></span>
                                    <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror"
                                           id="social_linkedin" name="social_linkedin"
                                           value="{{ old('social_linkedin', $settingsArray['social_linkedin'] ?? '') }}">
                                </div>
                                @error('social_linkedin')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Currency Settings -->
                        <h5 class="form-section-title">
                            <i data-feather="dollar-sign" class="feather-sm me-1"></i> Currency Settings
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="currency_symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror"
                                       id="currency_symbol" name="currency_symbol"
                                       value="{{ old('currency_symbol', $settingsArray['currency_symbol'] ?? '$') }}" required>
                                @error('currency_symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="currency_code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('currency_code') is-invalid @enderror"
                                       id="currency_code" name="currency_code"
                                       value="{{ old('currency_code', $settingsArray['currency_code'] ?? 'USD') }}" required>
                                @error('currency_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="feather-sm me-1"></i> Save Settings
                            </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Feather icons
        feather.replace();

        // Logo dropzone functionality
        const logoDropzone = document.getElementById('logoDropzone');
        const logoInput = document.getElementById('site_logo');
        const logoPreview = document.getElementById('logoPreview');

        logoDropzone.addEventListener('click', function() {
            logoInput.click();
        });

        logoDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            logoDropzone.classList.add('border-primary');
        });

        logoDropzone.addEventListener('dragleave', function() {
            logoDropzone.classList.remove('border-primary');
        });

        logoDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            logoDropzone.classList.remove('border-primary');
            if (e.dataTransfer.files.length) {
                logoInput.files = e.dataTransfer.files;
                updateImagePreview(logoInput, logoPreview);
            }
        });

        logoInput.addEventListener('change', function() {
            updateImagePreview(logoInput, logoPreview);
        });

        // Favicon dropzone functionality
        const faviconDropzone = document.getElementById('faviconDropzone');
        const faviconInput = document.getElementById('favicon');
        const faviconPreview = document.getElementById('faviconPreview');

        faviconDropzone.addEventListener('click', function() {
            faviconInput.click();
        });

        faviconDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            faviconDropzone.classList.add('border-primary');
        });

        faviconDropzone.addEventListener('dragleave', function() {
            faviconDropzone.classList.remove('border-primary');
        });

        faviconDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            faviconDropzone.classList.remove('border-primary');
            if (e.dataTransfer.files.length) {
                faviconInput.files = e.dataTransfer.files;
                updateImagePreview(faviconInput, faviconPreview);
            }
        });

        faviconInput.addEventListener('change', function() {
            updateImagePreview(faviconInput, faviconPreview);
        });

        // Function to update image preview
        function updateImagePreview(input, preview) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.parentElement.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove image functionality
        document.querySelectorAll('.remove-image').forEach(function(button) {
            button.addEventListener('click', function() {
                const inputName = this.dataset.input;
                const input = document.getElementById(inputName);
                const preview = document.getElementById(inputName + 'Preview');

                // Reset file input
                input.value = '';

                // Reset preview
                preview.src = '{{ asset("saas_admin/img/placeholder-image.jpg") }}';

                // Hide the remove button
                this.style.display = 'none';
            });
        });

        // Reset form button
        document.getElementById('resetForm').addEventListener('click', function() {
            if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
                document.getElementById('generalSettingsForm').reset();
                document.getElementById('logoPreview').src = '{{ asset("saas_admin/img/placeholder-image.jpg") }}';
                document.getElementById('faviconPreview').src = '{{ asset("saas_admin/img/placeholder-image.jpg") }}';
                document.querySelectorAll('.remove-image').forEach(element => {
                    element.style.display = 'none';
                });
            }
        });
    });
</script>
@endsection
