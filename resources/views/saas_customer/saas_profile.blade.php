@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .profile-container {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 80vh;
    padding: 3rem 0;
    position: relative;
  }

  .profile-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 25% 25%, rgba(171, 207, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(9, 113, 126, 0.05) 0%, transparent 50%);
    pointer-events: none;
  }

  .breadcrumb-modern {
    background: linear-gradient(135deg, var(--white), #f8fafc);
    padding: 1.5rem 0;
    margin-bottom: 0;
    border-bottom: 1px solid var(--border-light);
  }

  .breadcrumb-modern .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
  }

  .breadcrumb-modern .breadcrumb-item a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .breadcrumb-modern .breadcrumb-item a:hover {
    color: var(--primary-color);
  }

  .breadcrumb-modern .breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
  }

  .profile-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
  }

  .profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
  }

  /* Profile specific styles */

  .profile-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border-light);
    position: relative;
    margin-bottom: 2rem;
  }

  .profile-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .section-title {
    font-family: var(--font-display);
    color: var(--text-dark);
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
    position: relative;
  }

  .section-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  /* Enhanced form section headers */
  h6.border-bottom {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 1rem;
    padding-bottom: 0.75rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0 !important;
    position: relative;
  }

  h6.border-bottom::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 40px;
    height: 2px;
    background: var(--primary-color);
  }

  /* Enhanced form inputs */
  .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
    background: rgba(248, 250, 252, 0.8);
  }

  /* File input styling */
  .form-control[type="file"] {
    padding: 0.5rem;
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    transition: all 0.3s ease;
  }

  .form-control[type="file"]:focus {
    border-color: var(--primary-color);
    border-style: dashed;
    background: rgba(171, 207, 55, 0.05);
  }

  /* Small text styling */
  small.text-muted {
    font-size: 0.8rem;
    color: #64748b;
    display: block;
    margin-top: 0.25rem;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: block;
  }

  .form-control {
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
  }

  .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
    background: rgba(171, 207, 55, 0.02);
  }

  .btn-update {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
  }

  .btn-update:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: var(--white);
  }

  .profile-stats {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
  }

  .stat-item {
    text-align: center;
    padding: 1rem;
  }

  .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    display: block;
    margin-bottom: 0.5rem;
  }

  .stat-label {
    color: var(--text-medium);
    font-size: 0.875rem;
    font-weight: 500;
  }

  .avatar-upload {
    position: relative;
    display: inline-block;
  }

  .avatar-upload input[type="file"] {
    display: none;
  }

  .avatar-upload-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    background: var(--primary-color);
    color: var(--white);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
  }

  .avatar-upload-btn:hover {
    background: var(--primary-dark);
    transform: scale(1.1);
  }

  .password-section {
    background: #f8fafc;
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-top: 2rem;
    border: 1px solid var(--border-light);
  }

  .password-section h5 {
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .profile-container {
      padding: 1rem 0;
    }

    .profile-header {
      padding: 2rem;
      text-align: center;
    }

    /* Enhanced sidebar handles responsive styles */

    .profile-content {
      padding: 2rem;
    }

    .stat-item {
      margin-bottom: 1rem;
    }
  }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-modern">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Profile Content -->
<section class="profile-container">
    <div class="container">
        <!-- Profile Header -->


        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">My Profile</h2>
                            <p class="mb-0 opacity-75">
                                Manage your personal information and account settings
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                                <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                    <i class="fa fa-user me-1"></i>
                                    Customer Since {{ auth()->user()->created_at->format('Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Profile Stats -->
                <div class="profile-stats">
                    <div class="row">
                        @php
                            $user = auth()->user();
                            $userTotalOrders = DB::table('saas_orders')->where('customer_id', $user->id)->count();
                            $userWishlistCount = DB::table('saas_wishlists')->where('customer_id', $user->id)->count();
                            $userReviewsCount = DB::table('saas_product_reviews')->where('customer_id', $user->id)->count();
                            $userTotalSpent = DB::table('saas_orders')->where('customer_id', $user->id)->where('order_status', 'delivered')->sum('total') ?? 0;

                            // Get customer profile data
                            $customerProfile = DB::table('saas_customer_profiles')->where('user_id', $user->id)->first();
                        @endphp
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <span class="stat-number">{{ $userTotalOrders }}</span>
                                <span class="stat-label">Total Orders</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <span class="stat-number">{{ $userWishlistCount }}</span>
                                <span class="stat-label">Wishlist Items</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <span class="stat-number">{{ $userReviewsCount }}</span>
                                <span class="stat-label">Reviews Written</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="stat-item">
                                <span class="stat-number">Rs. {{ number_format($userTotalSpent, 0) }}</span>
                                <span class="stat-label">Total Spent</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="profile-content">
                    <h4 class="section-title">
                        <i class="fa fa-user me-2 text-primary"></i>
                        Personal Information
                    </h4>

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone', auth()->user()->phone) }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="border-bottom pb-2 mb-3 text-primary mt-4">
                            <i class="fas fa-map-marker-alt me-2"></i>Address Information
                        </h6>

                        <div class="form-group">
                            <label for="shipping_address" class="form-label">
                                <i class="fas fa-truck me-1"></i>Shipping Address
                            </label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3"
                                      placeholder="Enter your complete shipping address">{{ old('shipping_address', $customerProfile->shipping_address ?? '') }}</textarea>
                            @error('shipping_address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This will be your default delivery address</small>
                        </div>

                        <div class="form-group">
                            <label for="billing_address" class="form-label">
                                <i class="fas fa-credit-card me-1"></i>Billing Address
                            </label>
                            <textarea class="form-control" id="billing_address" name="billing_address" rows="3"
                                      placeholder="Enter your billing address (if different from shipping)">{{ old('billing_address', $customerProfile->billing_address ?? '') }}</textarea>
                            @error('billing_address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave blank to use shipping address for billing</small>
                        </div>



                        <h6 class="border-bottom pb-2 mb-3 text-primary mt-4">
                            <i class="fas fa-camera me-2"></i>Profile Photo
                        </h6>

                        <div class="form-group">
                            <label for="profile_photo" class="form-label">
                                <i class="fas fa-image me-1"></i>Upload Profile Photo
                            </label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                            @error('profile_photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            @if(auth()->user()->profile_photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                         alt="Current Profile Photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <p class="small text-muted mt-1">Current profile photo</p>
                                </div>
                            @endif
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-update">
                                <i class="fa fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>

                    <!-- Change Password Section -->
                    <div class="password-section">
                        <h5>
                            <i class="fa fa-lock me-2 text-primary"></i>
                            Change Password
                        </h5>

                        <form action="{{ route('customer.password.update') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn-update">
                                    <i class="fa fa-lock me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection
