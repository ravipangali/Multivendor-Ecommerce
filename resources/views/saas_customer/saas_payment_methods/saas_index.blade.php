@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
    .payment-methods-container {
        background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
        min-height: 80vh;
        padding: 2rem 0;
    }
    .payment-methods-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: var(--white);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-lg);
    }
    .payment-method-card {
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .payment-method-card:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
        border-color: var(--primary-color);
    }
    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--accent-color);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1.5rem;
    }
    .card-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }
    .card-details {
        color: var(--text-medium);
        font-size: 0.875rem;
    }
    .default-badge {
        background-color: var(--primary-color);
        color: var(--white);
    }
    .btn-custom {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .breadcrumb-enhanced {
        padding: 2rem 0;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
    }
    .breadcrumb-inner {
        display: flex;
        align-items: center;
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: 1.5rem 2rem;
        box-shadow: var(--shadow-md);
    }
    .breadcrumb-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-right: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 15px rgba(171, 207, 55, 0.3);
    }
    .breadcrumb-content {
        flex: 1;
    }
    .breadcrumb-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0 0 0.25rem;
    }
    .breadcrumb-enhanced .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
        font-size: 0.875rem;
    }
    .breadcrumb-enhanced .breadcrumb-item a {
        color: var(--text-medium);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .breadcrumb-enhanced .breadcrumb-item a:hover {
        color: var(--primary-color);
    }
    .breadcrumb-enhanced .breadcrumb-item.active {
        color: var(--text-dark);
        font-weight: 500;
    }
    .breadcrumb-enhanced .breadcrumb-item::before {
        color: var(--text-light);
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-enhanced">
    <div class="container">
        <div class="breadcrumb-inner">
            <div class="breadcrumb-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="breadcrumb-content">
                <h2 class="breadcrumb-title">My Payment Methods</h2>
        <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment Methods</li>
            </ol>
        </nav>
            </div>
        </div>
    </div>
</section>

<section class="payment-methods-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>
            <div class="col-lg-9">
                <div class="payment-methods-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 text-white">My Payment Methods</h2>
                            <p class="mb-0 text-white opacity-75">Manage your saved payment options</p>
                        </div>
                        <a href="{{ route('customer.payment-methods.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Add New Method
                        </a>
                    </div>
                                    </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($paymentMethods->count() > 0)
                    @foreach($paymentMethods as $paymentMethod)
                        <div class="payment-method-card">
                            <div class="d-flex align-items-center">
                                <div class="card-icon">
                                    @if($paymentMethod->type == 'bank_transfer')
                                        <i class="fas fa-university"></i>
                                    @else
                                        <i class="fas fa-mobile-alt"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title">{{ $paymentMethod->title ?? $paymentMethod->getDisplayNameAttribute() }}</h6>
                                            <p class="card-details mb-0">
                                                @if($paymentMethod->type == 'bank_transfer')
                                                    {{ $paymentMethod->details['bank_name'] ?? '' }} - A/C: ...{{ substr($paymentMethod->details['account_number'] ?? '', -4) }}
                                                @elseif(in_array($paymentMethod->type, ['esewa', 'khalti']))
                                                    {{ $paymentMethod->details['esewa_id'] ?? $paymentMethod->details['khalti_id'] ?? '' }}
                                                @endif
                                            </p>
                            </div>
                                        <div>
                                            @if($paymentMethod->is_default)
                                                <span class="badge default-badge">Default</span>
                                            @endif
                                    </div>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <div class="btn-group">
                                        <a href="{{ route('customer.payment-methods.show', $paymentMethod) }}" class="btn btn-sm btn-outline-secondary btn-custom" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('customer.payment-methods.edit', $paymentMethod) }}" class="btn btn-sm btn-outline-secondary btn-custom" title="Edit"><i class="fas fa-edit"></i></a>
                                        @if(!$paymentMethod->is_default)
                                        <form action="{{ route('customer.payment-methods.destroy', $paymentMethod) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm btn-custom" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                        </div>
                                    </div>
                                @endforeach
                        @else
                    <div class="text-center p-5 bg-white rounded-3 shadow-sm">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h4>No Payment Methods Found</h4>
                        <p class="text-muted">You haven't added any payment methods yet.</p>
                        <a href="{{ route('customer.payment-methods.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Add Your First Method
                                </a>
                            </div>
                        @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-confirm').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
@endpush
