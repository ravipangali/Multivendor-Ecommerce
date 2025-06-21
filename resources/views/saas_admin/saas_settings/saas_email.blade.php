@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Email Settings')

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
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }
    .test-email-section {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #e1bee7;
    }
    .alert {
        border-radius: 8px;
        border: none;
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
                <h5 class="card-title mb-0">Email Settings</h5>
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

            <form action="{{ route('admin.settings.email') }}" method="POST">
                @csrf

                <!-- SMTP Configuration -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="mail"></i> SMTP Configuration
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_host" class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_host" name="mail_host"
                                    value="{{ old('mail_host', $settings->mail_host) }}"
                                    placeholder="smtp.gmail.com" required>
                                <small class="text-muted">Examples: smtp.gmail.com, smtp.mailtrap.io, mail.yourhost.com</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_port" class="form-label">SMTP Port <span class="text-danger">*</span></label>
                                <select class="form-select" id="mail_port" name="mail_port" required>
                                    <option value="">Select Port</option>
                                    <option value="25" {{ old('mail_port', $settings->mail_port) == '25' ? 'selected' : '' }}>25 (Standard SMTP)</option>
                                    <option value="465" {{ old('mail_port', $settings->mail_port) == '465' ? 'selected' : '' }}>465 (SSL)</option>
                                    <option value="587" {{ old('mail_port', $settings->mail_port) == '587' ? 'selected' : '' }}>587 (TLS/STARTTLS)</option>
                                    <option value="2525" {{ old('mail_port', $settings->mail_port) == '2525' ? 'selected' : '' }}>2525 (Alternative)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_username" class="form-label">SMTP Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_username" name="mail_username"
                                    value="{{ old('mail_username', $settings->mail_username) }}"
                                    placeholder="your-email@gmail.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_password" class="form-label">SMTP Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="mail_password" name="mail_password"
                                        value="{{ old('mail_password', $settings->mail_password) }}"
                                        placeholder="Your app password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i data-feather="eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">For Gmail, use App Password instead of regular password</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mail_encryption" class="form-label">Encryption <span class="text-danger">*</span></label>
                        <select class="form-select" id="mail_encryption" name="mail_encryption" required>
                            <option value="">Select Encryption</option>
                            <option value="TLS" {{ old('mail_encryption', $settings->mail_encryption) == 'TLS' ? 'selected' : '' }}>TLS</option>
                            <option value="SSL" {{ old('mail_encryption', $settings->mail_encryption) == 'SSL' ? 'selected' : '' }}>SSL</option>
                            <option value="STARTTLS" {{ old('mail_encryption', $settings->mail_encryption) == 'STARTTLS' ? 'selected' : '' }}>STARTTLS</option>
                        </select>
                    </div>
                </div>

                <!-- Sender Information -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="user"></i> Sender Information
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_from_address" class="form-label">From Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                    value="{{ old('mail_from_address', $settings->mail_from_address) }}"
                                    placeholder="noreply@yoursite.com" required>
                                <small class="text-muted">This email will appear as the sender for all outgoing emails</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mail_from_name" class="form-label">From Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                    value="{{ old('mail_from_name', $settings->mail_from_name) }}"
                                    placeholder="Your Site Name" required>
                                <small class="text-muted">This name will appear as the sender for all outgoing emails</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Testing -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="send"></i> Test Email Configuration
                    </h6>

                    <div class="alert alert-info">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Test your email settings:</strong> Save the settings first, then use the test feature below to verify your configuration.
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="email" class="form-control" id="test_email" name="test_email"
                                    placeholder="Enter email address to send test email" required>
                                <button type="button" class="btn btn-info" id="sendTestEmail">
                                    <i class="align-middle" data-feather="send"></i> Send Test Email
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="testResult" class="mt-3" style="display: none;"></div>
                </div>

                <!-- Common SMTP Providers -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="align-middle" data-feather="cloud"></i> Common SMTP Providers
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Gmail</h6>
                                    <small class="text-muted">
                                        <strong>Host:</strong> smtp.gmail.com<br>
                                        <strong>Port:</strong> 587 (TLS) or 465 (SSL)<br>
                                        <strong>Security:</strong> Use App Password
                                    </small>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="setGmailConfig()">
                                        Use Gmail Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Mailtrap (Testing)</h6>
                                    <small class="text-muted">
                                        <strong>Host:</strong> smtp.mailtrap.io<br>
                                        <strong>Port:</strong> 587<br>
                                        <strong>Security:</strong> TLS
                                    </small>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="setMailtrapConfig()">
                                        Use Mailtrap Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="align-middle" data-feather="save"></i> Save Email Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('mail_password');
        const icon = this.querySelector('[data-feather]');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.setAttribute('data-feather', 'eye-off');
        } else {
            passwordField.type = 'password';
            icon.setAttribute('data-feather', 'eye');
        }
        feather.replace();
    });

    // Set Gmail configuration
    function setGmailConfig() {
        document.getElementById('mail_host').value = 'smtp.gmail.com';
        document.getElementById('mail_port').value = '587';
        document.getElementById('mail_encryption').value = 'TLS';
    }

    // Set Mailtrap configuration
    function setMailtrapConfig() {
        document.getElementById('mail_host').value = 'smtp.mailtrap.io';
        document.getElementById('mail_port').value = '587';
        document.getElementById('mail_encryption').value = 'TLS';
    }

    // Auto-update encryption based on port selection
    document.getElementById('mail_port').addEventListener('change', function() {
        const encryptionField = document.getElementById('mail_encryption');

        switch(this.value) {
            case '465':
                encryptionField.value = 'SSL';
                break;
            case '587':
                encryptionField.value = 'TLS';
                break;
            case '25':
                encryptionField.value = '';
                break;
        }
    });

    // Send test email
    document.getElementById('sendTestEmail').addEventListener('click', function() {
        const emailInput = document.getElementById('test_email');
        const email = emailInput.value;
        const button = this;
        const resultDiv = document.getElementById('testResult');

        if (!email) {
            Swal.fire({
                title: 'Email Required',
                text: 'Please enter an email address',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...';

        // Send AJAX request
        fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.style.display = 'block';

            if (data.success) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="align-middle" data-feather="check-circle"></i>
                        ${data.message}
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="align-middle" data-feather="alert-circle"></i>
                        <strong>Test Failed:</strong> ${data.message}
                        ${data.error_details ? `<br><small>${data.error_details}</small>` : ''}
                    </div>
                `;
            }

            // Replace feather icons
            feather.replace();
        })
        .catch(error => {
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="align-middle" data-feather="alert-circle"></i>
                    <strong>Error:</strong> Failed to send test email. Please try again.
                </div>
            `;
            feather.replace();
        })
        .finally(() => {
            // Reset button state
            button.disabled = false;
            button.innerHTML = '<i class="align-middle" data-feather="send"></i> Send Test Email';
            feather.replace();
        });
    });
</script>
@endsection
