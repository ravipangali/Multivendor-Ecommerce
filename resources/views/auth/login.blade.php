@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="our-login">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-6 mx-auto">
                <div class="log-reg-form search_area">
                    <div class="login-form">
                        <div class="heading">
                            <h3 class="text-center">Welcome Back</h3>
                            <p class="text-center">Sign in to your AllSewa account</p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
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
                                <label for="password">Password</label>
                                <input id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       placeholder="Enter your password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="form-group d-flex justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                    <label class="custom-control-label" for="remember_me">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="color-thm forgot-password" href="{{ route('password.request') }}">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <div class="form-group">
                                <button class="btn btn-log w-100 btn-thm" type="submit">Sign In</button>
                            </div>

                            <div class="divide-content">
                                <span>or</span>
                            </div>

                            <div class="form-group text-center">
                                <p class="text">Don't have an account?
                                    <a class="color-thm" href="{{ route('register') }}">Create Account</a>
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
.our-login {
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
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

.custom-control {
    position: relative;
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5rem;
}

.custom-control-input {
    position: absolute;
    left: 0;
    z-index: -1;
    width: 1rem;
    height: 1.25rem;
    opacity: 0;
}

.custom-control-input:checked ~ .custom-control-label::before {
    color: #fff;
    border-color: #007bff;
    background-color: #007bff;
}

.custom-control-label {
    position: relative;
    margin-bottom: 0;
    vertical-align: top;
    color: #6c757d;
    font-size: 14px;
}

.custom-control-label::before {
    position: absolute;
    top: 0.25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    pointer-events: none;
    content: "";
    background-color: #fff;
    border: 2px solid #e9ecef;
    border-radius: 3px;
}

.custom-control-label::after {
    position: absolute;
    top: 0.25rem;
    left: -1.5rem;
    display: block;
    width: 1rem;
    height: 1rem;
    content: "";
    background: no-repeat 50%/50% 50%;
}

.custom-control-input:checked ~ .custom-control-label::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='m6.564.75-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e");
}

.forgot-password {
    font-size: 14px;
    text-decoration: none;
}

.btn-log {
    height: 50px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-thm {
    background-color: #007bff;
    border: none;
    color: white;
}

.btn-thm:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
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
}

.divide-content span {
    background: #fff;
    padding: 0 15px;
    color: #6c757d;
    font-size: 14px;
    position: relative;
}

.text {
    margin: 0;
    color: #6c757d;
}

.color-thm {
    color: #007bff !important;
    text-decoration: none;
    font-weight: 500;
}

.color-thm:hover {
    color: #0056b3 !important;
}

.alert {
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

@media (max-width: 768px) {
    .our-login {
        padding: 30px 0;
    }

    .log-reg-form {
        padding: 30px 20px;
        margin: 0 15px;
    }
}
</style>
@endsection
