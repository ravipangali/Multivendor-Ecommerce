@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="our-seller-register">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="seller-reg-form">
                    <div class="form-header text-center">
                        <div class="seller-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h2 class="form-title">Become a Seller</h2>
                        <p class="form-subtitle">Join AllSewa and start selling your products & services</p>
                    </div>

                    <form method="POST" action="{{ route('seller.register') }}" class="seller-registration-form">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-sections">
                            <!-- Personal Information Section -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Full Name <span class="required">*</span></label>
                                            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input id="phone" class="form-control @error('phone') is-invalid @enderror" type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g., +977-9841234567">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address <span class="required">*</span></label>
                                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email address">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password <span class="required">*</span></label>
                                            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required placeholder="Create a strong password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                                            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required placeholder="Confirm your password">
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Store Information Section -->
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="fas fa-store me-2"></i>Store Information
                                </h4>

                                <div class="form-group">
                                    <label for="store_name">Store Name <span class="required">*</span></label>
                                    <input id="store_name" class="form-control @error('store_name') is-invalid @enderror" type="text" name="store_name" value="{{ old('store_name') }}" required placeholder="Enter your store name">
                                    @error('store_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="store_description">Store Description</label>
                                    <textarea id="store_description" class="form-control @error('store_description') is-invalid @enderror" name="store_description" rows="4" placeholder="Describe your store and what you sell...">{{ old('store_description') }}</textarea>
                                    @error('store_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Business Address</label>
                                    <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3" placeholder="Enter your business address...">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-section">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        <input type="checkbox" id="terms" name="terms" required class="@error('terms') is-invalid @enderror">
                                        <label for="terms">
                                            I agree to the <a href="{{ route('customer.terms') }}" class="terms-link" target="_blank">Terms and Conditions</a> and <a href="{{ route('customer.privacy') }}" class="terms-link" target="_blank">Privacy Policy</a>
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-seller-register">
                                    <i class="fas fa-store me-2"></i>Create Seller Account
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="form-footer text-center">
                                <p class="login-text">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="login-link">Sign In</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.our-seller-register {
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--accent-color), #f1f5f9);
    min-height: calc(100vh - 200px);
}

.seller-reg-form {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: 3rem;
    box-shadow: var(--shadow-xl);
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
}

.seller-reg-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.form-header {
    margin-bottom: 2.5rem;
}

.seller-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--white);
    font-size: 2rem;
    box-shadow: var(--shadow-lg);
}

.form-title {
    font-family: var(--font-display);
    color: var(--text-dark);
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: var(--text-light);
    font-size: 1.125rem;
    margin: 0;
}

.form-sections {
    margin-top: 2rem;
}

.form-section {
    margin-bottom: 2.5rem;
    padding: 2rem;
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
}

.section-title {
    color: var(--text-dark);
    font-family: var(--font-display);
    font-size: 1.375rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--primary-color);
    display: flex;
    align-items: center;
}

.section-title i {
    color: var(--primary-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    color: var(--text-dark);
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.required {
    color: var(--danger);
    font-weight: 700;
}

.form-control {
    height: 50px;
    border: 2px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
    color: var(--text-dark);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
    background: var(--white);
}

.form-control.is-invalid {
    border-color: var(--danger);
}

.form-control::placeholder {
    color: var(--text-muted);
    opacity: 0.8;
}

textarea.form-control {
    height: auto;
    resize: vertical;
    min-height: 100px;
    padding: 0.75rem 1rem;
    line-height: 1.6;
}

.invalid-feedback {
    display: block;
    color: var(--danger);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.custom-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.custom-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin: 0;
    accent-color: var(--primary-color);
    flex-shrink: 0;
    margin-top: 2px;
}

.custom-checkbox label {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.6;
    color: var(--text-medium);
    text-transform: none;
    letter-spacing: normal;
}

.terms-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.terms-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.btn-seller-register {
    width: 100%;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border: none;
    border-radius: var(--radius-md);
    color: var(--white);
    font-size: 1.125rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-seller-register:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-seller-register:active {
    transform: translateY(0);
}

.form-footer {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
}

.login-text {
    color: var(--text-medium);
    margin: 0;
    font-size: 1rem;
}

.login-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.login-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.alert {
    border-radius: var(--radius-md);
    border: none;
    margin-bottom: 2rem;
    padding: 1rem 1.5rem;
}

.alert-danger {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger);
    border-left: 4px solid var(--danger);
}

.alert ul {
    list-style: none;
    padding: 0;
}

.alert li {
    margin-bottom: 0.5rem;
    position: relative;
    padding-left: 1.5rem;
}

.alert li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--danger);
    font-weight: bold;
}

.alert li:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .our-seller-register {
        padding: 2rem 0;
    }

    .seller-reg-form {
        padding: 2rem 1.5rem;
        margin: 0 1rem;
    }

    .form-title {
        font-size: 2rem;
    }

    .form-subtitle {
        font-size: 1rem;
    }

    .form-section {
        padding: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
    }

    .btn-seller-register {
        height: 50px;
        font-size: 1rem;
    }

    .seller-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>
@endpush
