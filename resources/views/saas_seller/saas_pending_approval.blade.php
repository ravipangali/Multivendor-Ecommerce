@extends('saas_seller.saas_layouts.saas_layout')

@section('title', 'Pending Approval')

@section('content')
<div class="col-12">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-clock fa-5x text-warning mb-4"></i>
                    <h2 class="mb-3">Your Seller Account is Pending Approval</h2>
                    <p class="lead mb-4">
                        Thank you for registering as a seller! Your account is currently under review by our admin team.
                    </p>
                    <p class="text-muted mb-4">
                        The approval process typically takes 24-48 hours. You will receive an email notification once your account has been approved.
                    </p>

                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong>What happens next?</strong>
                        <ul class="text-left mt-2 mb-0">
                            <li>Our team will review your seller profile information</li>
                            <li>We may contact you if we need additional information</li>
                            <li>Once approved, you'll have full access to all seller features</li>
                            <li>You can start listing products and managing your store</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted">
                            If you have any questions, please contact our support team at
                            <a href="mailto:support@example.com">support@example.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
