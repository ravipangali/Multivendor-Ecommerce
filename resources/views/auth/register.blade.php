@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<div class="our-register">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-6 mx-auto">
                <div class="log-reg-form search_area">
                    <div class="signup-form">
                        <div class="heading">
                            <h3 class="text-center">Create Account</h3>
                            <p class="text-center">Join AllSewa and start your journey</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       autofocus
                                       autocomplete="name"
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
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
                                       autocomplete="new-password"
                                       placeholder="Create a strong password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       type="password"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Confirm your password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-log w-100 btn-thm" type="submit">Create Account</button>
                            </div>

                            <div class="divide-content">
                                <span>or</span>
                            </div>

                            <div class="form-group text-center">
                                <p class="text">Already have an account?
                                    <a class="color-thm" href="{{ route('login') }}">Sign In</a>
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
.our-register {
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

@media (max-width: 768px) {
    .our-register {
        padding: 30px 0;
    }

    .log-reg-form {
        padding: 30px 20px;
        margin: 0 15px;
    }
}
</style>
@endsection
