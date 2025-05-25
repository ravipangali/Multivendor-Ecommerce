@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Email Settings')

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
    .password-toggle {
        cursor: pointer;
    }
    .smtp-test-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #dee2e6;
    }
    .smtp-test-section h5 {
        margin-bottom: 1rem;
        color: #3b7ddd;
    }
    .test-result {
        margin-top: 15px;
        padding: 10px 15px;
        border-radius: 5px;
        display: none;
    }
    .test-result.success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    .test-result.error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    .email-provider-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }
    .email-provider-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .email-provider-card.selected {
        border-color: #3b7ddd;
    }
    .email-provider-card .card-body {
        padding: 1.5rem;
    }
    .provider-logo {
        height: 50px;
        object-fit: contain;
        margin-bottom: 1rem;
    }
    .provider-logo-fallback {
        height: 50px;
        width: 150px;
        margin-bottom: 1rem;
        background-color: #3b7ddd;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
    }
    .provider-card-footer {
        padding: 0.75rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    /* Add animation for test email */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 125, 221, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(59, 125, 221, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 125, 221, 0); }
    }
    .pulse {
        animation: pulse 1.5s infinite;
    }
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    .was-validated .form-control:invalid, .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
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
                    <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action">
                        <i data-feather="settings" class="feather-sm"></i> General
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="list-group-item list-group-item-action active">
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
                    <h5 class="card-title mb-0">Email Settings</h5>
                    <a href="#smtp-test" class="btn btn-sm btn-outline-primary">
                        <i data-feather="send" class="feather-sm"></i> Test SMTP
                    </a>
                </div>
                <div class="card-body">
                    <!-- Email Providers -->
                    <h5 class="form-section-title">
                        <i data-feather="mail" class="feather-sm me-1"></i> Email Provider
                    </h5>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div id="provider-smtp" class="email-provider-card card selected" data-provider="smtp">
                                <div class="card-body text-center">
                                    <div class="provider-logo-fallback">SMTP</div>
                                    <h5 class="card-title">SMTP</h5>
                                    <p class="card-text text-muted">Standard email delivery through SMTP server</p>
                                </div>
                                <div class="provider-card-footer text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mail_driver" id="mail_driver_smtp" value="smtp" checked>
                                        <label class="form-check-label" for="mail_driver_smtp">
                                            Select SMTP
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div id="provider-mailgun" class="email-provider-card card" data-provider="mailgun">
                                <div class="card-body text-center">
                                    <div class="provider-logo-fallback">Mailgun</div>
                                    <h5 class="card-title">Mailgun</h5>
                                    <p class="card-text text-muted">Email delivery through Mailgun API</p>
                                </div>
                                <div class="provider-card-footer text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mail_driver" id="mail_driver_mailgun" value="mailgun">
                                        <label class="form-check-label" for="mail_driver_mailgun">
                                            Select Mailgun
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div id="provider-ses" class="email-provider-card card" data-provider="ses">
                                <div class="card-body text-center">
                                    <div class="provider-logo-fallback">Amazon SES</div>
                                    <h5 class="card-title">Amazon SES</h5>
                                    <p class="card-text text-muted">Email delivery through Amazon SES</p>
                                </div>
                                <div class="provider-card-footer text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="mail_driver" id="mail_driver_ses" value="ses">
                                        <label class="form-check-label" for="mail_driver_ses">
                                            Select Amazon SES
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.email') }}" method="POST" id="emailSettingsForm" class="needs-validation" novalidate>
                        @csrf

                        <!-- SMTP Configuration -->
                        <div class="provider-settings" id="smtp-settings">
                            <h5 class="form-section-title">
                                <i data-feather="server" class="feather-sm me-1"></i> SMTP Configuration
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mail_host" class="form-label">Mail Host <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                                           id="mail_host" name="mail_host"
                                           value="{{ old('mail_host', $settingsArray['mail_host'] ?? '') }}" required
                                           placeholder="e.g., smtp.gmail.com">
                                    <div class="invalid-feedback">
                                        Please provide a valid mail host.
                                    </div>
                                    @error('mail_host')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mail_port" class="form-label">Mail Port <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('mail_port') is-invalid @enderror"
                                           id="mail_port" name="mail_port"
                                           value="{{ old('mail_port', $settingsArray['mail_port'] ?? '587') }}" required
                                           placeholder="e.g., 587">
                                    <div class="invalid-feedback">
                                        Please provide a valid port number.
                                    </div>
                                    @error('mail_port')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mail_username" class="form-label">Mail Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mail_username') is-invalid @enderror"
                                           id="mail_username" name="mail_username"
                                           value="{{ old('mail_username', $settingsArray['mail_username'] ?? '') }}" required
                                           placeholder="e.g., your_email@gmail.com">
                                    <div class="invalid-feedback">
                                        Please provide a valid username.
                                    </div>
                                    @error('mail_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mail_password" class="form-label">Mail Password <span class="text-danger">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="password" class="form-control @error('mail_password') is-invalid @enderror"
                                               id="mail_password" name="mail_password"
                                               value="{{ old('mail_password', $settingsArray['mail_password'] ?? '') }}" required>
                                        <span class="input-group-text password-toggle" id="toggle-password">
                                            <i data-feather="eye" class="feather-sm"></i>
                                        </span>
                                        <div class="invalid-feedback">
                                            Please provide a password.
                                        </div>
                                    </div>
                                    @error('mail_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mail_encryption" class="form-label">Mail Encryption</label>
                                    <select class="form-select @error('mail_encryption') is-invalid @enderror"
                                            id="mail_encryption" name="mail_encryption">
                                        <option value="" {{ (old('mail_encryption', $settingsArray['mail_encryption'] ?? '') == '') ? 'selected' : '' }}>None</option>
                                        <option value="tls" {{ (old('mail_encryption', $settingsArray['mail_encryption'] ?? '') == 'tls') ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ (old('mail_encryption', $settingsArray['mail_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('mail_encryption')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- From Information -->
                        <h5 class="form-section-title">
                            <i data-feather="at-sign" class="feather-sm me-1"></i> From Information
                        </h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_address" class="form-label">From Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror"
                                       id="mail_from_address" name="mail_from_address"
                                       value="{{ old('mail_from_address', $settingsArray['mail_from_address'] ?? '') }}" required
                                       placeholder="e.g., info@yourdomain.com">
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mail_from_name" class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror"
                                       id="mail_from_name" name="mail_from_name"
                                       value="{{ old('mail_from_name', $settingsArray['mail_from_name'] ?? '') }}" required
                                       placeholder="e.g., Your Company Name">
                                <div class="invalid-feedback">
                                    Please provide a sender name.
                                </div>
                                @error('mail_from_name')
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

                    <!-- SMTP Test Section -->
                    <div class="smtp-test-section" id="smtp-test">
                        <h5>
                            <i data-feather="send" class="feather-sm me-1"></i> Test SMTP Connection
                        </h5>
                        <form id="smtp-test-form" class="needs-validation" novalidate data-ajax="true">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="test_email" class="form-label">Send Test Email To</label>
                                    <input type="email" class="form-control" id="test_email" name="test_email"
                                           placeholder="Enter email address" required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email address.
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end mb-3">
                                    <button type="submit" class="btn btn-primary w-100" id="test-email-btn">
                                        <i data-feather="send" class="feather-sm me-1"></i> Send Test Email
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="test-result" class="test-result mt-3"></div>
                        <div class="small text-muted mt-3">
                            <i data-feather="info" class="feather-sm me-1"></i>
                            Make sure to save your settings before testing the connection.
                        </div>
                    </div>
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

        // Password toggle
        const togglePassword = document.getElementById('toggle-password');
        const passwordField = document.getElementById('mail_password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon
            const eyeIcon = this.querySelector('svg');
            if (type === 'text') {
                eyeIcon.setAttribute('data-feather', 'eye-off');
            } else {
                eyeIcon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        });

        // Email provider selection
        const providerCards = document.querySelectorAll('.email-provider-card');
        const providerSettings = document.querySelectorAll('.provider-settings');
        const mailDriverInputs = document.querySelectorAll('input[name="mail_driver"]');

        providerCards.forEach(card => {
            card.addEventListener('click', function() {
                // Update selected class
                providerCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');

                // Check the radio button
                const providerId = this.getAttribute('data-provider');
                document.getElementById('mail_driver_' + providerId).checked = true;

                // Show/hide provider settings
                providerSettings.forEach(settings => {
                    const settingsId = settings.getAttribute('id');
                    settings.style.display = settingsId === providerId + '-settings' ? 'block' : 'none';
                });
            });
        });

        // Initial setup based on selected driver
        mailDriverInputs.forEach(input => {
            if (input.checked) {
                const providerId = input.value;
                document.getElementById('provider-' + providerId).classList.add('selected');
                providerSettings.forEach(settings => {
                    const settingsId = settings.getAttribute('id');
                    settings.style.display = settingsId === providerId + '-settings' ? 'block' : 'none';
                });
            }
        });

        // SMTP Test Form with improved feedback
        const smtpTestForm = document.getElementById('smtp-test-form');
        const testResult = document.getElementById('test-result');
        const testEmailBtn = document.getElementById('test-email-btn');

        smtpTestForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Form validation
            if (!smtpTestForm.checkValidity()) {
                e.stopPropagation();
                smtpTestForm.classList.add('was-validated');
                return;
            }

            // Show loading state
            testEmailBtn.disabled = true;
            testEmailBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            testEmailBtn.classList.add('pulse');

            // Show loading in result area
            testResult.innerHTML = '<div class="d-flex align-items-center"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Testing SMTP connection...</div>';
            testResult.classList.remove('success', 'error');
            testResult.style.display = 'block';

            // Send AJAX request to test the SMTP connection
            fetch('{{ route("admin.settings.test-email") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    email: document.getElementById('test_email').value
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Reset button
                testEmailBtn.disabled = false;
                testEmailBtn.innerHTML = '<i data-feather="send" class="feather-sm me-1"></i> Send Test Email';
                testEmailBtn.classList.remove('pulse');
                feather.replace();

                // Show response
                if (data.success) {
                    testResult.innerHTML = '<div class="d-flex align-items-center"><i data-feather="check-circle" class="feather-sm me-2"></i> ' + data.message + '</div>';
                    testResult.classList.add('success');
                    testResult.classList.remove('error');

                    // Show toast notification
                    if (typeof showToast === 'function') {
                        showToast('Success', 'Test email sent successfully!', 'success');
                    }

                    // Clear the email field on success
                    document.getElementById('test_email').value = '';
                    smtpTestForm.classList.remove('was-validated');
                } else {
                    testResult.innerHTML = '<div class="d-flex align-items-center"><i data-feather="alert-circle" class="feather-sm me-2"></i> ' + data.message + '</div>';
                    testResult.classList.add('error');
                    testResult.classList.remove('success');

                    // Show toast notification
                    if (typeof showToast === 'function') {
                        showToast('Error', 'Failed to send test email', 'error');
                    }
                }
                feather.replace();
            })
            .catch(error => {
                // Reset button
                testEmailBtn.disabled = false;
                testEmailBtn.innerHTML = '<i data-feather="send" class="feather-sm me-1"></i> Send Test Email';
                testEmailBtn.classList.remove('pulse');
                feather.replace();

                // Show error
                testResult.innerHTML = '<div class="d-flex align-items-center"><i data-feather="alert-circle" class="feather-sm me-2"></i> An error occurred while testing the connection.</div>';
                testResult.classList.add('error');
                testResult.classList.remove('success');

                // Show toast notification
                if (typeof showToast === 'function') {
                    showToast('Error', 'An unexpected error occurred', 'error');
                }

                console.error('Error:', error);
                feather.replace();
            });
        });

        // Form validation for main settings form
        const emailSettingsForm = document.getElementById('emailSettingsForm');
        if (emailSettingsForm) {
            emailSettingsForm.addEventListener('submit', function(event) {
                if (!emailSettingsForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                emailSettingsForm.classList.add('was-validated');
            });
        }
    });
</script>
@endsection
