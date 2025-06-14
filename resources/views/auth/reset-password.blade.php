@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="our-reset-password">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-6 mx-auto">
                <div class="log-reg-form search_area">
                    <div class="reset-password-form">
                        <div class="heading text-center">
                            <h3>Reset Your Password</h3>
                            <p>Create a new password for your AllSewa account. Make sure it's strong and secure!</p>
                        </div>

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       type="email"
                                       name="email"
                                       value="{{ old('email', $request->email) }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       placeholder="Enter your email address">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>New Password
                                </label>
                                <div class="password-input-container">
                                    <input id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           type="password"
                                           name="password"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Enter your new password">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">
                                    <i class="fas fa-lock me-2"></i>Confirm New Password
                                </label>
                                <div class="password-input-container">
                                    <input id="password_confirmation"
                                           class="form-control @error('password_confirmation') is-invalid @enderror"
                                           type="password"
                                           name="password_confirmation"
                                           required
                                           autocomplete="new-password"
                                           placeholder="Confirm your new password">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-log w-100 btn-thm" type="submit">
                                    <i class="fas fa-key me-2"></i>Reset Password
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
.our-reset-password {
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

.password-input-container {
    position: relative;
}

.form-control {
    height: 50px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0 50px 0 15px;
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

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: var(--primary-color);
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

.text {
    color: #6c757d;
    margin: 0;
}

@media (max-width: 768px) {
    .our-reset-password {
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

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');

    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endsection
