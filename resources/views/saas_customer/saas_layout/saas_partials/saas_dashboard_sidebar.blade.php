@php
    $user = auth()->user();
    $currentRoute = request()->route()->getName();

    // Get user statistics using direct database queries
    $totalOrders = DB::table('saas_orders')->where('customer_id', $user->id)->count();
    $pendingOrders = DB::table('saas_orders')->where('customer_id', $user->id)->where('order_status', 'pending')->count();
    $wishlistCount = DB::table('saas_wishlists')->where('customer_id', $user->id)->count();
    $totalSpent = DB::table('saas_orders')->where('customer_id', $user->id)->where('order_status', 'delivered')->sum('total') ?? 0;
    $reviewsCount = DB::table('saas_product_reviews')->where('customer_id', $user->id)->count();
@endphp

<div class="dashboard-sidebar-enhanced">
    <!-- User Profile Card -->
    <div class="user-profile-card-enhanced">
        <div class="profile-background">
            <div class="profile-pattern"></div>
        </div>

        <div class="user-avatar-section">
            <div class="avatar-container">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                         alt="{{ $user->name }}"
                         class="profile-image-enhanced">
                @else
                    <div class="profile-placeholder-enhanced">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <div class="online-indicator"></div>
            </div>
        </div>

        <div class="user-info-enhanced">
            <h4 class="user-name-enhanced">{{ $user->name }}</h4>
            <p class="user-email-enhanced">{{ $user->email }}</p>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-item-mini">
                <i class="fas fa-shopping-bag"></i>
                <span class="stat-number">{{ $totalOrders }}</span>
                <span class="stat-label">Orders</span>
            </div>
            <div class="stat-item-mini">
                <i class="fas fa-heart"></i>
                <span class="stat-number">{{ $wishlistCount }}</span>
                <span class="stat-label">Saved</span>
            </div>
            <div class="stat-item-mini">
                <i class="fas fa-star"></i>
                <span class="stat-number">{{ $reviewsCount }}</span>
                <span class="stat-label">Reviews</span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="dashboard-menu-enhanced">
        <ul class="menu-list-enhanced">
            <li class="menu-item-enhanced {{ $currentRoute == 'customer.dashboard' ? 'active' : '' }}">
                <a href="{{ route('customer.dashboard') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">Dashboard</span>
                        <span class="menu-subtitle">Overview & Stats</span>
                    </div>
                    <div class="menu-indicator"></div>
                </a>
            </li>

            <li class="menu-item-enhanced {{ $currentRoute == 'customer.orders' ? 'active' : '' }}">
                <a href="{{ route('customer.orders') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">My Orders</span>
                        <span class="menu-subtitle">Track your purchases</span>
                    </div>
                    @if($pendingOrders > 0)
                        <span class="badge-notification">{{ $pendingOrders }}</span>
                    @endif
                    <div class="menu-indicator"></div>
                </a>
            </li>

            <li class="menu-item-enhanced {{ $currentRoute == 'customer.wishlist' ? 'active' : '' }}">
                <a href="{{ route('customer.wishlist') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">Wishlist</span>
                        <span class="menu-subtitle">Saved favorites</span>
                    </div>
                    @if($wishlistCount > 0)
                        <span class="badge-notification wishlist-badge">{{ $wishlistCount }}</span>
                    @endif
                    <div class="menu-indicator"></div>
                </a>
            </li>

            <li class="menu-item-enhanced {{ strpos($currentRoute, 'customer.refunds') !== false ? 'active' : '' }}">
                <a href="{{ route('customer.refunds.index') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-undo-alt"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">Refund Requests</span>
                        <span class="menu-subtitle">Return & refunds</span>
                    </div>
                    @php
                        $pendingRefunds = DB::table('saas_refunds')->where('customer_id', $user->id)->where('status', 'pending')->count();
                    @endphp
                    @if($pendingRefunds > 0)
                        <span class="badge-notification">{{ $pendingRefunds }}</span>
                    @endif
                    <div class="menu-indicator"></div>
                </a>
            </li>

            <li class="menu-item-enhanced {{ strpos($currentRoute, 'customer.payment-methods') !== false ? 'active' : '' }}">
                <a href="{{ route('customer.payment-methods.index') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">Payment Methods</span>
                        <span class="menu-subtitle">Manage payments</span>
                    </div>
                    @php
                        $activePaymentMethods = DB::table('saas_payment_methods')->where('user_id', $user->id)->where('is_active', true)->count();
                    @endphp
                    @if($activePaymentMethods > 0)
                        <span class="badge-notification success-badge">{{ $activePaymentMethods }}</span>
                    @endif
                    <div class="menu-indicator"></div>
                </a>
            </li>

            <li class="menu-item-enhanced {{ $currentRoute == 'customer.profile' ? 'active' : '' }}">
                <a href="{{ route('customer.profile') }}" class="menu-link-enhanced">
                    <div class="menu-icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="menu-content">
                        <span class="menu-title">Profile Settings</span>
                        <span class="menu-subtitle">Manage account</span>
                    </div>
                    <div class="menu-indicator"></div>
                </a>
            </li>
        </ul>

        <!-- Logout Section -->
        <div class="logout-section">
            <a href="{{ route('logout') }}" class="logout-link-enhanced"
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <div class="menu-icon logout-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="menu-content">
                    <span class="menu-title">Logout</span>
                    <span class="menu-subtitle">Sign out safely</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Account Summary -->
    <div class="account-summary">
        <div class="summary-header">
            <h6 class="text-white"><i class="fas fa-wallet me-2"></i>Account Summary</h6>
        </div>
        <div class="summary-content">
            <div class="summary-item">
                <span class="summary-label">Total Spent</span>
                <span class="summary-value">Rs. {{ number_format($totalSpent, 0) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Active Orders</span>
                <span class="summary-value">{{ $pendingOrders }}</span>
            </div>
        </div>
        <div class="summary-footer">
            <a href="{{ route('customer.products') }}" class="btn-continue-shopping">
                <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('styles')
<style>
/* Enhanced Dashboard Sidebar Styles */
.dashboard-sidebar-enhanced {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* User Profile Card Enhanced */
.user-profile-card-enhanced {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
    position: relative;
    transform: translateY(0);
    transition: all 0.3s ease;
}

.user-profile-card-enhanced:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.profile-background {
    height: 80px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid var(--border-light);
}

.profile-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>') repeat;
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.user-avatar-section {
    display: flex;
    justify-content: center;
    margin-top: -40px;
    margin-bottom: 1rem;
    position: relative;
}

.avatar-container {
    position: relative;
}

.profile-image-enhanced {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--white);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.profile-image-enhanced:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
}

.profile-placeholder-enhanced {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 2rem;
    border: 4px solid var(--white);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.online-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 16px;
    height: 16px;
    background: var(--success);
    border-radius: 50%;
    border: 3px solid var(--white);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

.user-info-enhanced {
    text-align: center;
    padding: 0 1.5rem 1.5rem;
}

.user-name-enhanced {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-family: var(--font-display);
}

.user-email-enhanced {
    color: var(--text-medium);
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.user-badges {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.user-badge-enhanced {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #7c2d12;
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

.member-since {
    color: var(--text-light);
    font-size: 0.75rem;
}

.quick-stats {
    display: flex;
    justify-content: space-around;
    padding: 1rem;
    background: #f8fafc;
    border-top: 1px solid var(--border-light);
}

.stat-item-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.stat-item-mini i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.stat-item-mini .stat-number {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 0.875rem;
}

.stat-item-mini .stat-label {
    color: var(--text-light);
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Dashboard Menu Enhanced */
.dashboard-menu-enhanced {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.menu-list-enhanced {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-item-enhanced {
    position: relative;
    border-bottom: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.menu-item-enhanced:last-child {
    border-bottom: none;
}

.menu-item-enhanced:hover {
    background: #f8fafc;
}

.menu-item-enhanced.active {
    background: linear-gradient(135deg, #f1f5f9, #f8fafc);
    border-left: 4px solid var(--primary-color);
}

.menu-item-enhanced.active .menu-indicator {
    background: var(--primary-color);
    transform: scaleY(1);
}

.menu-link-enhanced {
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    color: var(--text-medium);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    gap: 1rem;
}

.menu-link-enhanced:hover {
    color: var(--primary-color);
    text-decoration: none;
}

.menu-item-enhanced.active .menu-link-enhanced {
    color: var(--primary-color);
}

.menu-icon {
    width: 45px;
    height: 45px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    font-size: 1.125rem;
    transition: all 0.3s ease;
    flex-shrink: 0;
    color: #64748b;
}

.menu-item-enhanced.active .menu-icon {
    background: var(--primary-color);
    color: var(--white);
    transform: scale(1.1);
}

.menu-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.menu-title {
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 1.2;
}

.menu-subtitle {
    font-size: 0.75rem;
    color: var(--text-light);
    line-height: 1.2;
}

.menu-indicator {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: transparent;
    transform: scaleY(0);
    transition: all 0.3s ease;
}

.badge-notification {
    background: var(--danger);
    color: var(--white);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-full);
    font-size: 0.625rem;
    font-weight: 700;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bounce 2s infinite;
}

.badge-notification.wishlist-badge {
    background: var(--danger);
}

@keyframes bounce {
    0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
    40%, 43% { transform: translate3d(0,-10px,0); }
    70% { transform: translate3d(0,-5px,0); }
    90% { transform: translate3d(0,-2px,0); }
}

.coming-soon-badge {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: var(--white);
    padding: 0.125rem 0.375rem;
    border-radius: var(--radius-full);
    font-size: 0.625rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        box-shadow: 0 0 5px rgba(99, 102, 241, 0.4);
    }
    to {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.8);
    }
}

/* Logout Section */
.logout-section {
    border-top: 2px solid #f1f5f9;
    padding: 1rem;
    background: #fef2f2;
}

.logout-link-enhanced {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    color: var(--danger);
    text-decoration: none;
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
    gap: 1rem;
}

.logout-link-enhanced:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    text-decoration: none;
    transform: translateX(5px);
}

.logout-icon {
    background: rgba(239, 68, 68, 0.1) !important;
    color: var(--danger) !important;
}

.logout-link-enhanced:hover .logout-icon {
    background: var(--danger) !important;
    color: var(--white) !important;
}

/* Account Summary */
.account-summary {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--border-light);
    overflow: hidden;
}

.summary-header {
    background: linear-gradient(135deg, #64748b, #475569);
    color: var(--white);
    padding: 1rem;
    text-align: center;
}

.summary-header h6 {
    margin: 0;
    font-weight: 600;
    font-size: 0.875rem;
}

.summary-content {
    padding: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-light);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: var(--text-medium);
    font-size: 0.75rem;
}

.summary-value {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 0.875rem;
}

.summary-footer {
    padding: 1rem;
    background: #f8fafc;
    text-align: center;
}

.btn-continue-shopping {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(171, 207, 55, 0.3);
}

.btn-continue-shopping:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(171, 207, 55, 0.4);
    color: var(--white);
    text-decoration: none;
}

/* Responsive Design */
@media (max-width: 991px) {
    .dashboard-sidebar-enhanced {
        position: static;
        margin-bottom: 2rem;
    }

    .quick-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }

    .menu-link-enhanced {
        padding: 0.75rem 1rem;
    }

    .menu-icon {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 768px) {
    .user-profile-card-enhanced {
        margin-bottom: 1rem;
    }

    .profile-background {
        height: 60px;
    }

    .user-avatar-section {
        margin-top: -30px;
    }

    .profile-image-enhanced,
    .profile-placeholder-enhanced {
        width: 60px;
        height: 60px;
    }

    .menu-content {
        gap: 0;
    }

    .menu-subtitle {
        display: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
function showComingSoon(featureName) {
    // Create modal-like notification
    const notification = document.createElement('div');
    notification.className = 'coming-soon-notification';
    notification.innerHTML = `
        <div class="coming-soon-content">
            <div class="coming-soon-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4>${featureName} Coming Soon!</h4>
            <p>This feature is currently under development and will be available soon.</p>
            <button onclick="this.parentElement.parentElement.remove()" class="btn-close-notification">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Add styles dynamically
    const style = document.createElement('style');
    style.textContent = `
        .coming-soon-notification {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .coming-soon-content {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            max-width: 400px;
            margin: 1rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        .coming-soon-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 2rem;
        }

        .coming-soon-content h4 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .coming-soon-content p {
            color: var(--text-medium);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .btn-close-notification {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: var(--white);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close-notification:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;

    if (!document.getElementById('coming-soon-styles')) {
        style.id = 'coming-soon-styles';
        document.head.appendChild(style);
    }

    // Auto close after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
    }, 5000);
}
</script>
@endpush
