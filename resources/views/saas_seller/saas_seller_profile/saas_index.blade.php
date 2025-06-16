@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid p-0">
    <!-- Profile Header -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Banner -->
            <div class="position-relative">
                <div class="profile-banner" style="height: 200px; background-color: #f5f7fb; background-image: url('{{ $sellerProfile->store_banner ? asset('storage/' . $sellerProfile->store_banner) : asset('assets/img/default-banner.jpg') }}'); background-size: cover; background-position: center;">
                </div>
                <div class="position-absolute top-0 end-0 p-3">
                    <a href="{{ route('seller.profile.edit') }}" class="btn btn-light btn-sm shadow-sm">
                        <i data-feather="edit-2" class="feather-sm me-1"></i> Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="px-4 pt-0 pb-4">
                <div class="d-flex align-items-end">
                    <div class="profile-avatar position-relative mt-n5 me-3">
                        @if($sellerProfile->store_logo)
                            <img src="{{ asset('storage/' . $sellerProfile->store_logo) }}"
                                alt="{{ $sellerProfile->store_name }}"
                                class="rounded-circle border border-4 border-white shadow-sm"
                                width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center border border-4 border-white shadow-sm"
                                style="width: 120px; height: 120px;">
                                <i data-feather="shopping-bag" class="text-white" style="width: 40px; height: 40px;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div>
                            <h4 class="mb-1">{{ $sellerProfile->store_name }}</h4>
                            <div class="d-flex align-items-center flex-wrap">
                                @if($sellerProfile->is_approved)
                                    <span class="badge bg-success me-2">Approved</span>
                                @else
                                    <span class="badge bg-warning me-2">Pending Approval</span>
                                @endif
                                <span class="text-muted small">Member since {{ $sellerProfile->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                        @if($user->wallet)
                            <div class="wallet-card mt-3 mt-md-0 py-2 px-3 bg-light rounded-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 p-2 rounded-circle bg-primary bg-opacity-10">
                                        <i data-feather="credit-card" class="text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="d-block text-muted small">Wallet Balance</span>
                                        <h5 class="mb-0">Rs {{ number_format($user->wallet->balance, 2) }}</h5>
                                        <small class="text-muted">Pending: Rs {{ number_format($user->wallet->pending_balance, 2) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Store Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3">
                    <h5 class="card-title mb-0">Store Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Store Description -->
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-2 small text-uppercase">Description</h6>
                            <p class="mb-0">{{ $sellerProfile->store_description ?? 'No description provided' }}</p>
                        </div>

                        <!-- Store Details -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-3 small text-uppercase">Contact Information</h6>
                                <ul class="list-unstyled">
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="user" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Owner Name</span>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="mail" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Email</span>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="phone" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Phone</span>
                                            <span>{{ $user->phone ?? 'Not provided' }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted mb-3 small text-uppercase">Store Details</h6>
                                <ul class="list-unstyled">
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="map-pin" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Address</span>
                                            <span>{{ $sellerProfile->address ?? 'No address provided' }}</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="calendar" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Profile Created</span>
                                            <span>{{ $sellerProfile->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-3">
                                        <div class="me-3">
                                            <i data-feather="refresh-cw" class="text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="text-muted small d-block">Last Updated</span>
                                            <span>{{ $sellerProfile->updated_at->format('M d, Y') }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                    <a href="{{ route('seller.payment-methods.create') }}" class="btn btn-sm btn-primary">
                        <i data-feather="plus" class="feather-sm"></i> Add New
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($paymentMethods) && $paymentMethods->count() > 0)
                        <div class="payment-methods-list">
                            @foreach($paymentMethods as $paymentMethod)
                                <div class="payment-method-card p-3 mb-3 rounded-3 {{ $paymentMethod->is_default ? 'border border-primary' : 'bg-light' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center mb-2">
                                                @php
                                                    $iconClass = 'credit-card';
                                                    if($paymentMethod->type == 'bank_transfer') {
                                                        $iconClass = 'briefcase';
                                                    } elseif(in_array($paymentMethod->type, ['esewa', 'khalti'])) {
                                                        $iconClass = 'smartphone';
                                                    } elseif($paymentMethod->type == 'cash') {
                                                        $iconClass = 'rs-icon'; // Custom Rs icon for cash payments
                                                    }
                                                @endphp
                                                <div class="me-2 p-2 rounded-circle {{ $paymentMethod->is_default ? 'bg-primary' : 'bg-secondary' }} bg-opacity-10">
                                                    @if($iconClass == 'rs-icon')
                                                        <span class="rs-icon rs-icon-sm {{ $paymentMethod->is_default ? 'text-primary' : 'text-secondary' }}">Rs</span>
                                                    @else
                                                        <i data-feather="{{ $iconClass }}" class="{{ $paymentMethod->is_default ? 'text-primary' : 'text-secondary' }} feather-sm"></i>
                                                    @endif
                                                </div>
                                                <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $paymentMethod->type)) }}</h6>
                                                @if($paymentMethod->is_default)
                                                    <span class="badge bg-primary ms-2">Default</span>
                                                @endif
                                            </div>
                                            <p class="mb-1 text-muted small">{{ $paymentMethod->title }}</p>
                                            <p class="mb-0 small">
                                                @if($paymentMethod->type == 'bank_transfer')
                                                    {{ $paymentMethod->bank_name ?? '' }} {{ $paymentMethod->account_number ? ' - ' . $paymentMethod->account_number : '' }}
                                                @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
                                                    {{ $paymentMethod->mobile_number ?? '' }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i data-feather="more-vertical" class="feather-sm"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('seller.payment-methods.show', $paymentMethod->id) }}">
                                                        <i data-feather="eye" class="feather-sm me-2"></i> View
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('seller.payment-methods.edit', $paymentMethod->id) }}">
                                                        <i data-feather="edit" class="feather-sm me-2"></i> Edit
                                                    </a>
                                                </li>
                                                @if(!$paymentMethod->is_default)
                                                <li>
                                                    <form action="{{ route('seller.payment-methods.set-default', $paymentMethod->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item border-0 bg-transparent">
                                                            <i data-feather="star" class="feather-sm me-2"></i> Set as Default
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i data-feather="credit-card" class="text-muted" style="width: 48px; height: 48px;"></i>
                            </div>
                            <p class="text-muted">No payment methods added yet.</p>
                            <a href="{{ route('seller.payment-methods.create') }}" class="btn btn-sm btn-primary">
                                <i data-feather="plus" class="feather-sm me-1"></i> Add Payment Method
                            </a>
                        </div>
                    @endif
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
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
