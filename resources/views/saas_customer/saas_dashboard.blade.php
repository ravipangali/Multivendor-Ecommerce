@extends('saas_customer.saas_layout.saas_layout')

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Account</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Dashboard -->
<section class="customer-dashboard">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="dashboard-content">
                    <!-- Welcome Header -->
                    <div class="welcome-header">
                        <div class="welcome-content">
                            <h2 class="welcome-title">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="welcome-subtitle text-white">Here's what's happening with your account today.</p>
                        </div>
                        <div class="welcome-actions">
                            <a href="{{ route('customer.products') }}" class="btn btn-secondary">
                                <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Stats Overview -->
                    <div class="stats-overview">
                        <div class="row g-4">
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card orders-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $totalOrders }}</div>
                                        <div class="stat-label">Total Orders</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card pending-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $pendingOrders }}</div>
                                        <div class="stat-label">Pending Orders</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card wishlist-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">{{ $wishlistCount }}</div>
                                        <div class="stat-label">Wishlist Items</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="stat-card spending-stat">
                                    <div class="stat-content">
                                        <div class="stat-number">Rs. {{ number_format($totalSpent, 0) }}</div>
                                        <div class="stat-label">Total Spent</div>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="recent-orders-section">
                        <div class="section-header">
                            <h4 class="section-title" style="margin-right: 1rem;">Recent Orders</h4>
                            <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-sm">
                                View All Orders
                            </a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="orders-list">
                                @foreach($recentOrders as $order)
                                    <div class="order-card">
                                        <div class="order-header">
                                            <div class="order-info">
                                                <h6 class="order-number">#{{ $order->order_number }}</h6>
                                                <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="order-status">
                                                <span class="status-badge status-{{ $order->order_status }}">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                                <div class="order-total">Rs. {{ number_format($order->total, 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="order-items">
                                            @foreach($order->items->take(2) as $item)
                                                <div class="order-item">
                                                    @if($item->product && $item->product->images->count() > 0)
                                                        <img src="{{ $item->product->images->first()->image_url }}"
                                                             alt="{{ $item->product->name }}" class="item-image">
                                                    @else
                                                        <img src="{{ asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                             alt="{{ $item->product->name }}" class="item-image">
                                                    @endif
                                                    <div class="item-details">
                                                        <h6 class="item-name">{{ Str::limit($item->product->name, 30) }}</h6>
                                                        <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                                    </div>
                                                    <div class="item-price">Rs. {{ number_format($item->total, 2) }}</div>
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                <div class="more-items">
                                                    <span>and {{ $order->items->count() - 2 }} more items</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="order-actions">
                                            <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                            @if($order->order_status == 'pending')
                                                <button class="btn btn-sm btn-outline-danger cancel-order" data-order-id="{{ $order->id }}">
                                                    Cancel Order
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-orders">
                                <div class="empty-icon">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h5 class="empty-title">No orders yet</h5>
                                <p class="empty-text">Start shopping to see your orders here.</p>
                                <a href="{{ route('customer.products') }}" class="btn btn-primary">Start Shopping</a>
                            </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions-section">
                        <h4 class="section-title">Quick Actions</h4>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="action-content">
                                        <h5 class="action-title">Continue Shopping</h5>
                                        <p class="action-description">Discover new products and great deals</p>
                                        <a href="{{ route('customer.products') }}" class="btn btn-primary">Shop Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="action-content">
                                        <h5 class="action-title">My Wishlist</h5>
                                        <p class="action-description">View and manage your saved items</p>
                                        <a href="{{ route('customer.wishlist') }}" class="btn btn-secondary">View Wishlist</a>
                                    </div>
                                </div>
                            </div>
                        </div>
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

@push('styles')
<style>
/* Dashboard Layout */
.customer-dashboard {
    min-height: 80vh;
    padding: 3rem 0;
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
}

/* Dashboard specific styles */

/* Welcome Header */
.welcome-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    border-radius: var(--radius-lg);
    padding: 2.5rem 2rem;
    margin-bottom: 2rem;
    color: var(--white);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.welcome-title {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.welcome-subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
    margin: 0;
}

.welcome-actions .btn {
    background: var(--white);
    color: var(--primary-color);
    border: none;
    font-weight: 600;
}

.welcome-actions .btn:hover {
    background: var(--accent-color);
    color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Stats Overview */
.stats-overview {
    margin-bottom: 2.5rem;
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.25rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--text-light);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--white);
}

.orders-stat .stat-number { color: var(--primary-color); }
.orders-stat .stat-icon { background: var(--primary-color); }

.pending-stat .stat-number { color: var(--warning); }
.pending-stat .stat-icon { background: var(--warning); }

.wishlist-stat .stat-number { color: var(--danger); }
.wishlist-stat .stat-icon { background: var(--danger); }

.spending-stat .stat-number { color: var(--secondary-color); }
.spending-stat .stat-icon { background: var(--secondary-color); }

/* Section Headers */
.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

/* Recent Orders */
.recent-orders-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    margin-bottom: 2rem;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.order-card {
    border: 1px solid var(--border-light);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.order-card:hover {
    box-shadow: var(--shadow-sm);
    border-color: var(--primary-color);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-number {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.order-date {
    color: var(--text-light);
    font-size: 0.875rem;
}

.order-status {
    text-align: right;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: inline-block;
}

.status-pending { background: var(--warning); color: var(--white); }
.status-processing { background: var(--info); color: var(--white); }
.status-shipped { background: var(--secondary-color); color: var(--white); }
.status-delivered { background: var(--success); color: var(--white); }
.status-cancelled { background: var(--danger); color: var(--white); }

.order-total {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
}

.order-items {
    margin-bottom: 1.5rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-light);
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: var(--radius-sm);
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.item-quantity {
    font-size: 0.75rem;
    color: var(--text-light);
}

.item-price {
    font-weight: 600;
    color: var(--secondary-color);
}

.more-items {
    text-align: center;
    padding: 0.75rem 0;
    color: var(--text-light);
    font-size: 0.875rem;
    font-style: italic;
}

.order-actions {
    display: flex;
    gap: 0.75rem;
}

/* Empty States */
.empty-orders {
    text-align: center;
    padding: 3rem 1.5rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--accent-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--text-muted);
    font-size: 2rem;
}

.empty-title {
    font-size: 1.25rem;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-text {
    color: var(--text-light);
    margin-bottom: 1.5rem;
}

/* Quick Actions */
.quick-actions-section {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
}

.action-card {
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    border: 2px solid transparent;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-color);
}

.action-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: var(--white);
    font-size: 1.75rem;
}

.action-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
}

.action-description {
    color: var(--text-light);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 991px) {
    .welcome-header {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }

    .order-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .order-status {
        text-align: center;
    }

    .order-actions {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .welcome-title {
        font-size: 1.5rem;
    }

    .welcome-subtitle {
        font-size: 1rem;
    }

    .stat-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .stat-icon {
        order: -1;
    }

    .order-item {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }

    .action-card {
        margin-bottom: 1.5rem;
    }

    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Cancel order functionality
    $('.cancel-order').click(function() {
        const orderId = $(this).data('order-id');
        const $btn = $(this);

        Swal.fire({
            title: 'Cancel Order?',
            text: 'Are you sure you want to cancel this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f56565',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Cancel Order',
            cancelButtonText: 'Keep Order'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add loading state
                const originalText = $btn.text();
                $btn.text('Cancelling...').prop('disabled', true);

                // Here you would make an AJAX call to cancel the order
                // For now, just show a success message
                setTimeout(() => {
                    Swal.fire({
                        title: 'Order Cancelled!',
                        text: 'Your order has been cancelled successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#abcf37'
                    }).then(() => {
                        $btn.text('Cancelled').removeClass('btn-outline-danger').addClass('btn-secondary').prop('disabled', true);
                    });
                }, 1000);
            }
        });
    });

    // Add hover effects to stat cards
    $('.stat-card').hover(
        function() {
            $(this).find('.stat-icon').addClass('animate__animated animate__pulse');
        },
        function() {
            $(this).find('.stat-icon').removeClass('animate__animated animate__pulse');
        }
    );

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.stat-card, .order-card, .action-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endpush
