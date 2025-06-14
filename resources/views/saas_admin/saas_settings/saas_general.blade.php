@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'General Settings')

@section('styles')
<style>
    .settings-nav {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        margin-bottom: 2rem;
    }
    .settings-nav .nav-link {
        color: #6c757d;
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .settings-nav .nav-link:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .settings-nav .nav-link:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .settings-nav .nav-link.active {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        color: white;
    }
    .settings-nav .nav-link:hover:not(.active) {
        background: #f8f9fa;
        color: #495057;
    }
    .settings-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15);
        border: none;
        overflow: hidden;
    }
    .settings-card .card-header {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        color: white;
        border: none;
        padding: 1.5rem;
    }
    .form-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }
    .form-section h5 {
        color: #495057;
        margin-bottom: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #4c8bef 0%, #024dc4 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    .image-preview {
        max-height: 60px;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        padding: 5px;
        background: white;
    }
    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #e0e6ed;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">General Settings</h5>
                <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                    <i class="align-middle" data-feather="arrow-left"></i> Back to Settings
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

            <form action="{{ route('admin.settings.general') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Site Information -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="globe"></i> Site Information
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="site_name" name="site_name"
                                    value="{{ old('site_name', $settings->site_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_email" class="form-label">Site Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="site_email" name="site_email"
                                    value="{{ old('site_email', $settings->site_email) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_phone" class="form-label">Site Phone</label>
                                <input type="text" class="form-control" id="site_phone" name="site_phone"
                                    value="{{ old('site_phone', $settings->site_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_address" class="form-label">Site Address</label>
                                <input type="text" class="form-control" id="site_address" name="site_address"
                                    value="{{ old('site_address', $settings->site_address) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', $settings->site_description) }}</textarea>
                        <small class="text-muted">This will be used as meta description for SEO</small>
                    </div>

                    <div class="mb-3">
                        <label for="site_keywords" class="form-label">Site Keywords</label>
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords"
                            value="{{ old('site_keywords', $settings->site_keywords) }}"
                            placeholder="keyword1, keyword2, keyword3">
                        <small class="text-muted">Comma-separated keywords for SEO</small>
                    </div>

                    <div class="mb-3">
                        <label for="site_footer" class="form-label">Footer Text</label>
                        <textarea class="form-control" id="site_footer" name="site_footer" rows="2">{{ old('site_footer', $settings->site_footer) }}</textarea>
                    </div>
                </div>

                <!-- Site Branding -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="image"></i> Site Branding
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_logo" class="form-label">Site Logo</label>
                                <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                                <small class="text-muted">Recommended size: 200x50px</small>

                                @if($settings->site_logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$settings->site_logo) }}" alt="Current Logo"
                                            class="img-thumbnail" style="max-height: 60px;">
                                        <div class="small text-muted">Current Logo</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_favicon" class="form-label">Site Favicon</label>
                                <input type="file" class="form-control" id="site_favicon" name="site_favicon" accept="image/*">
                                <small class="text-muted">Recommended: 32x32px (.ico, .png)</small>

                                @if($settings->site_favicon)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$settings->site_favicon) }}" alt="Current Favicon"
                                            class="img-thumbnail" style="max-height: 32px;">
                                        <div class="small text-muted">Current Favicon</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currency Settings -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="dollar-sign"></i> Currency Settings
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_currency_code" class="form-label">Currency Code <span class="text-danger">*</span></label>
                                <select class="form-select" id="site_currency_code" name="site_currency_code" required>
                                    <option value="">Select Currency</option>
                                    <option value="USD" {{ old('site_currency_code', $settings->site_currency_code) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('site_currency_code', $settings->site_currency_code) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="NPR" {{ old('site_currency_code', $settings->site_currency_code) == 'NPR' ? 'selected' : '' }}>NPR - Nepalese Rupee</option>
                                    <option value="INR" {{ old('site_currency_code', $settings->site_currency_code) == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                    <option value="GBP" {{ old('site_currency_code', $settings->site_currency_code) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="JPY" {{ old('site_currency_code', $settings->site_currency_code) == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_currency_symbol" class="form-label">Currency Symbol <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="site_currency_symbol" name="site_currency_symbol"
                                    value="{{ old('site_currency_symbol', $settings->site_currency_symbol) }}"
                                    placeholder="$, €, ₹, रू, etc." required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="share-2"></i> Social Media Links
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_facebook" class="form-label">Facebook URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="facebook"></i></span>
                                    <input type="url" class="form-control" id="site_facebook" name="site_facebook"
                                        value="{{ old('site_facebook', $settings->site_facebook) }}"
                                        placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_twitter" class="form-label">Twitter URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="twitter"></i></span>
                                    <input type="url" class="form-control" id="site_twitter" name="site_twitter"
                                        value="{{ old('site_twitter', $settings->site_twitter) }}"
                                        placeholder="https://twitter.com/yourhandle">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_instagram" class="form-label">Instagram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="instagram"></i></span>
                                    <input type="url" class="form-control" id="site_instagram" name="site_instagram"
                                        value="{{ old('site_instagram', $settings->site_instagram) }}"
                                        placeholder="https://instagram.com/yourprofile">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_linkedin" class="form-label">LinkedIn URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="linkedin"></i></span>
                                    <input type="url" class="form-control" id="site_linkedin" name="site_linkedin"
                                        value="{{ old('site_linkedin', $settings->site_linkedin) }}"
                                        placeholder="https://linkedin.com/company/yourcompany">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_youtube" class="form-label">YouTube URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i data-feather="youtube"></i></span>
                                    <input type="url" class="form-control" id="site_youtube" name="site_youtube"
                                        value="{{ old('site_youtube', $settings->site_youtube) }}"
                                        placeholder="https://youtube.com/channel/yourchannel">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save General Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-fill currency symbol based on currency code selection
    document.getElementById('site_currency_code').addEventListener('change', function() {
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'NPR': 'रू',
            'INR': '₹',
            'GBP': '£',
            'JPY': '¥'
        };

        const symbolInput = document.getElementById('site_currency_symbol');
        if (symbols[this.value] && !symbolInput.value) {
            symbolInput.value = symbols[this.value];
        }
    });
</script>
@endsection
