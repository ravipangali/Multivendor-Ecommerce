@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="our-forgot-password">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-6 mx-auto">
                <div class="log-reg-form search_area">
                    <div class="forgot-password-form">
                        <div class="heading text-center">
                            <h3>Forgot Your Password?</h3>
                            <p>Don't worry! Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="Enter your email address">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-log w-100 btn-thm" type="submit">
                                    <i class="fas fa-paper-plane me-2"></i>Email Password Reset Link
                                </button>
                            </div>

                            <div class="divide-content">
                                <span>or</span>
                            </div>

                            <div class="form-group text-center">
                                <p class="text">Remember your password?
                                    <a class="color-thm" href="{{ route('login') }}">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Sign In
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.our-forgot-password {
    padding: 50px 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
    margin-top: 0;
}

.log-reg-form {
    background: #fff;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
}

.heading h3 {
    color: #1f4b5f;
    font-weight: 600;
    margin-bottom: 10px;
}

.heading p {
    color: #6c757d;
    margin-bottom: 30px;
    line-height: 1.6;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    color: #1f4b5f;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    height: 50px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0 15px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(171, 207, 55, 0.25);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

.btn-log {
    height: 50px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-thm {
    background-color: var(--primary-color);
    border: none;
    color: white;
}

.btn-thm:hover {
    background-color: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(171, 207, 55, 0.3);
}

.divide-content {
    text-align: center;
    margin: 25px 0;
    position: relative;
}

.divide-content:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e9ecef;
    z-index: 1;
}

.divide-content span {
    background: #fff;
    padding: 0 20px;
    color: #6c757d;
    position: relative;
    z-index: 2;
}

.color-thm {
    color: var(--primary-color) !important;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.color-thm:hover {
    color: var(--primary-dark) !important;
    text-decoration: underline;
}

.alert {
    border-radius: 8px;
    border: none;
    margin-bottom: 25px;
}

.alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #155724;
    border-left: 4px solid #28a745;
}

.text {
    color: #6c757d;
    margin: 0;
}

@media (max-width: 768px) {
    .our-forgot-password {
        padding: 30px 0;
    }

    .log-reg-form {
        padding: 30px 20px;
    }

    .heading h3 {
        font-size: 1.5rem;
    }

    .heading p {
        font-size: 0.9rem;
    }
}
</style>
@endsection
