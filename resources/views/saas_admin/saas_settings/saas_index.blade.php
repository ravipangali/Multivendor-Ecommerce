@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Settings Dashboard')

@section('content')
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Settings Dashboard</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.export') }}" class="btn btn-outline-success">
                <i class="align-middle" data-feather="download"></i> Export Settings
            </a>
            <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="align-middle" data-feather="refresh-cw"></i> Clear Cache
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- General Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i data-feather="settings" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">General Settings</h5>
                    <p class="card-text text-muted">Configure site name, logo, contact information, and social media links.</p>
                    <a href="{{ route('admin.settings.general') }}" class="btn btn-primary">
                        <i class="align-middle" data-feather="edit"></i> Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i data-feather="mail" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">Email Settings</h5>
                    <p class="card-text text-muted">Configure SMTP settings for sending emails from the platform.</p>
                    <a href="{{ route('admin.settings.email') }}" class="btn btn-info">
                        <i class="align-middle" data-feather="edit"></i> Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i data-feather="credit-card" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">Payment Settings</h5>
                    <p class="card-text text-muted">Configure payment gateways, withdrawal policies, and transaction fees.</p>
                    <a href="{{ route('admin.settings.payment') }}" class="btn btn-success">
                        <i class="align-middle" data-feather="edit"></i> Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i data-feather="truck" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">Shipping Settings</h5>
                    <p class="card-text text-muted">Configure shipping options, rates, and delivery methods.</p>
                    <a href="{{ route('admin.settings.shipping') }}" class="btn btn-warning">
                        <i class="align-middle" data-feather="edit"></i> Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- Tax Settings -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-danger mb-3">
                        <i data-feather="percent" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">Tax Settings</h5>
                    <p class="card-text text-muted">Configure tax rates and tax calculation settings.</p>
                    <a href="{{ route('admin.settings.tax') }}" class="btn btn-danger">
                        <i class="align-middle" data-feather="edit"></i> Manage
                    </a>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="text-secondary mb-3">
                        <i data-feather="info" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h5 class="card-title">System Info</h5>
                    <p class="card-text text-muted">View system information and platform details.</p>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#systemInfoModal">
                        <i class="align-middle" data-feather="eye"></i> View
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Settings Overview -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Settings Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Site Name:</strong>
                                <div class="text-muted">{{ $settings->site_name ?: 'Not Set' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Site Email:</strong>
                                <div class="text-muted">{{ $settings->site_email ?: 'Not Set' }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Currency:</strong>
                                <div class="text-muted">{{ $settings->site_currency_code ?: 'Not Set' }} ({{ $settings->site_currency_symbol ?: 'Not Set' }})</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>SMTP Configured:</strong>
                                <div class="text-muted">
                                    @if($settings->mail_host && $settings->mail_port && $settings->mail_username)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Tax System:</strong>
                                <div class="text-muted">
                                    @if($settings->tax_enable)
                                        <span class="badge bg-success">Enabled ({{ $settings->tax_rate ?? 0 }}%)</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Free Shipping:</strong>
                                <div class="text-muted">
                                    @if($settings->shipping_enable_free)
                                        <span class="badge bg-success">Enabled ({{ $settings->site_currency_symbol ?? '' }}{{ $settings->shipping_free_min_amount ?? 0 }}+)</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Payment Gateways:</strong>
                                <div class="text-muted">
                                    @php
                                        $gateways = 0;
                                        if($settings->esewa_merchant_id) $gateways++;
                                        if($settings->khalti_public_key) $gateways++;
                                    @endphp
                                    @if($gateways > 0)
                                        <span class="badge bg-success">{{ $gateways }} Configured</span>
                                    @else
                                        <span class="badge bg-warning">None Configured</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <strong>Site Logo:</strong>
                                <div class="text-muted">
                                    @if($settings->site_logo)
                                        <span class="badge bg-success">Uploaded</span>
                                    @else
                                        <span class="badge bg-warning">Not Set</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Info Modal -->
<div class="modal fade" id="systemInfoModal" tabindex="-1" aria-labelledby="systemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="systemInfoModalLabel">System Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Environment Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Laravel Version:</strong></td>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td>{{ PHP_VERSION }}</td>
                            </tr>
                            <tr>
                                <td><strong>Environment:</strong></td>
                                <td>{{ app()->environment() }}</td>
                            </tr>
                            <tr>
                                <td><strong>Debug Mode:</strong></td>
                                <td>{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Platform Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Platform:</strong></td>
                                <td>Multi-Tenant E-commerce</td>
                            </tr>
                            <tr>
                                <td><strong>Version:</strong></td>
                                <td>1.0.0</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $settings->updated_at ? $settings->updated_at->format('d M Y H:i:s') : 'Never' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Server Time:</strong></td>
                                <td>{{ now()->format('d M Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
