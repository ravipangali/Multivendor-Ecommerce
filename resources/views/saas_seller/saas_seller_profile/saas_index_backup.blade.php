@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'My Profile')

@section('content')
<div class="col-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Seller Profile</h6>
                    <a href="{{ route('seller.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Store Logo and Banner -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                @if($sellerProfile->store_logo)
                                    <img src="{{ asset('storage/' . $sellerProfile->store_logo) }}"
                                         alt="Store Logo"
                                         class="img-fluid rounded-circle mb-3"
                                         style="max-width: 200px; height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 200px; height: 200px;">
                                        <i class="fas fa-store fa-4x text-white"></i>
                                    </div>
                                @endif
                                <h5 class="font-weight-bold">{{ $sellerProfile->store_name }}</h5>

                                <!-- Approval Status -->
                                <div class="mt-3">
                                    @if($sellerProfile->is_approved)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pending Approval
                                        </span>
                                    @endif
                                </div>

                                <!-- Wallet Balance -->
                                @if($user->wallet)
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <h6 class="text-muted mb-2">Wallet Balance</h6>
                                        <h4 class="text-primary mb-0">Rs {{ number_format($user->wallet->balance, 2) }}</h4>
                                        <small class="text-muted">Pending: Rs {{ number_format($user->wallet->pending_balance, 2) }}</small>
                                    </div>
                                @endif
                            </div>

                            @if($sellerProfile->store_banner)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $sellerProfile->store_banner) }}"
                                         alt="Store Banner"
                                         class="img-fluid rounded"
                                         style="width: 100%; height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <!-- Store Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Store Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Store Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $sellerProfile->store_name }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Description</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $sellerProfile->store_description ?? 'No description provided' }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Address</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $sellerProfile->address ?? 'No address provided' }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Owner Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $user->name }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $user->email }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Phone</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $user->phone ?? 'Not provided' }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Profile Created</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $sellerProfile->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Last Updated</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            {{ $sellerProfile->updated_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
                    <a href="{{ route('seller.payment-methods.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Payment Method
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($user->paymentMethods) && $user->paymentMethods->count() > 0)
                        @foreach($user->paymentMethods as $paymentMethod)
                            <div class="border rounded p-3 mb-2 {{ $paymentMethod->is_default ? 'border-primary' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ ucfirst($paymentMethod->method_type) }}
                                            @if($paymentMethod->is_default)
                                                <span class="badge badge-primary ml-2">Default</span>
                                            @endif
                                        </h6>
                                        @if($paymentMethod->method_type == 'bank')
                                            <p class="mb-0 text-muted">
                                                {{ $paymentMethod->bank_name }} - {{ $paymentMethod->account_number }}
                                            </p>
                                        @elseif(in_array($paymentMethod->method_type, ['jazzcash', 'easypaisa']))
                                            <p class="mb-0 text-muted">
                                                {{ $paymentMethod->account_number }}
                                            </p>
                                        @endif
                                    </div>
                                    <a href="{{ route('seller.payment-methods.edit', $paymentMethod->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No payment methods added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
