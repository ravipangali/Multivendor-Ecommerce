@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .cancel-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 2rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .cancel-card {
    background: white;
    border-radius: 15px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid #e3e6f0;
    max-width: 500px;
    width: 100%;
  }

  .cancel-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #dc3545;
  }

  .cancel-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2d3436;
  }

  .cancel-message {
    font-size: 1.125rem;
    color: #636e72;
    margin-bottom: 2rem;
    line-height: 1.6;
  }

  .btn-primary {
    background: linear-gradient(135deg, #007bff, #6610f2);
    border: none;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin: 0.5rem;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    color: white;
  }

  .btn-outline {
    background: transparent;
    border: 2px solid #007bff;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    color: #007bff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    margin: 0.5rem;
  }

  .btn-outline:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }
</style>
@endpush

@section('title', 'Checkout Cancelled')

@section('content')
<div class="cancel-container">
    <div class="container">
        <div class="cancel-card">
            <div class="cancel-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1 class="cancel-title">Payment Cancelled</h1>
            <p class="cancel-message">
                Your payment was cancelled and no charges have been made to your account.
                Your items are still in your cart if you'd like to try again.
            </p>

            <div class="action-buttons">
                <a href="{{ route('customer.cart') }}" class="btn-primary">
                    <i class="fas fa-shopping-cart"></i>
                    Return to Cart
                </a>
                <a href="{{ route('customer.home') }}" class="btn-outline">
                    <i class="fas fa-home"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
