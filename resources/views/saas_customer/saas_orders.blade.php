@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .orders-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 2rem 0;
  }

  .orders-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
  }

  /* Orders specific styles */

  .orders-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
  }

  .filters-card {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
  }

  .filter-form .form-select {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    background: var(--white);
    transition: all 0.2s ease;
  }

  .filter-form .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .filter-form .form-control {
    border: 1px solid var(--border-medium);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    background: var(--white);
    transition: all 0.2s ease;
  }

  .filter-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(171, 207, 55, 0.1);
    outline: none;
  }

  .order-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-light);
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .order-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-3px);
    border-color: var(--primary-color);
  }

  .order-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .order-card:hover::before {
    opacity: 1;
  }

  .order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--accent-color);
  }

  .order-info h6 {
    font-family: var(--font-display);
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
  }

  .order-date {
    color: var(--text-medium);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
  }

  .order-id {
    color: var(--text-light);
    font-size: 0.75rem;
    font-family: monospace;
    background: var(--accent-color);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    display: inline-block;
  }

  .order-status-section {
    text-align: right;
  }

  .status-badge {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    box-shadow: var(--shadow-sm);
  }

  .status-pending {
    background: linear-gradient(135deg, var(--warning), #f39c12);
    color: var(--white);
  }

  .status-processing {
    background: linear-gradient(135deg, var(--info), #3498db);
    color: var(--white);
  }

  .status-shipped {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: var(--white);
  }

  .status-delivered {
    background: linear-gradient(135deg, var(--success), #27ae60);
    color: var(--white);
  }

  .status-cancelled {
    background: linear-gradient(135deg, var(--danger), #e74c3c);
    color: var(--white);
  }

  .order-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .order-items {
    margin-bottom: 1.5rem;
  }

  .order-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: var(--accent-color);
    border-radius: var(--radius-md);
    margin-bottom: 1rem;
    transition: all 0.2s ease;
  }

  .order-item:hover {
    background: rgba(171, 207, 55, 0.1);
    transform: translateX(5px);
  }

  .item-image {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-md);
    object-fit: cover;
    margin-right: 1rem;
    box-shadow: var(--shadow-sm);
  }

  .item-details {
    flex: 1;
  }

  .item-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
  }

  .item-variation {
    color: var(--text-medium);
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
  }

  .item-quantity {
    color: var(--text-light);
    font-size: 0.75rem;
  }

  .item-price {
    font-weight: 600;
    color: var(--secondary-color);
    font-size: 0.875rem;
    text-align: center;
    min-width: 80px;
  }

  .item-total {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1rem;
    text-align: right;
    min-width: 100px;
  }

  .more-items {
    text-align: center;
    color: var(--text-medium);
    font-style: italic;
    padding: 0.5rem;
  }

  .order-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
  }

  .order-progress {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .progress-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    color: var(--white);
  }

  .progress-icon.active {
    background: var(--success);
  }

  .progress-icon.inactive {
    background: var(--text-muted);
  }

  .order-buttons {
    display: flex;
    gap: 0.75rem;
  }

  .btn-custom {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
  }

  .btn-primary-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
  }

  .btn-primary-custom:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: var(--white);
  }

  .btn-danger-custom {
    background: linear-gradient(135deg, var(--danger), #dc3545);
    color: var(--white);
  }

  .btn-danger-custom:hover {
    background: linear-gradient(135deg, #c82333, #a71e2a);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    color: var(--white);
  }

  .empty-orders {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    margin: 2rem 0;
  }

  .empty-orders-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--accent-color), #e2e8f0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: var(--text-muted);
  }

  .breadcrumb-modern {
    background: linear-gradient(135deg, var(--white), var(--accent-color));
    padding: 1.5rem 0;
    margin-bottom: 0;
    border-bottom: 1px solid var(--border-light);
  }

  .breadcrumb-modern .breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
  }

  .breadcrumb-modern .breadcrumb-item a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .breadcrumb-modern .breadcrumb-item a:hover {
    color: var(--primary-color);
  }

  .breadcrumb-modern .breadcrumb-item.active {
    color: var(--text-dark);
    font-weight: 600;
  }

  /* Modal Styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
    max-width: 500px;
  }

  .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: var(--white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
  }

  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-light);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  }

  .modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
  }

  .modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1.5rem;
  }

  .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-light);
    background: var(--accent-color);
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
  }

  .btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--white);
    cursor: pointer;
  }

  @media (max-width: 768px) {
    .orders-container {
      padding: 1rem 0;
    }

    .orders-header {
      padding: 1.5rem;
      text-align: center;
    }

    /* Enhanced sidebar handles responsive styles */

    .order-card {
      padding: 1.5rem;
    }

    .order-header {
      flex-direction: column;
      text-align: center;
      gap: 1rem;
    }

    .order-status-section {
      text-align: center;
    }

    .order-actions {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }

    .order-buttons {
      justify-content: center;
    }

    .filter-form .row > div {
      margin-bottom: 1rem;
    }

    .modal-dialog {
      margin: 1rem;
      max-width: calc(100% - 2rem);
    }
  }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="breadcrumb-modern">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">My Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Orders Content -->
<section class="orders-container">
    <div class="container">
        <!-- Orders Header -->


        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="orders-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">My Orders</h2>
                            <p class="mb-0 text-white opacity-75">
                                Track and manage your orders
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                                <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                    <i class="fa fa-shopping-bag me-1"></i>
                                    {{ $orders->total() }} Total Orders
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="orders-content">
                    <!-- Filters -->
                    <div class="filters-card">
                        <h6 class="mb-3">
                            <i class="fa fa-filter me-2"></i>Filter Orders
                        </h6>
                        <form action="{{ route('customer.orders') }}" method="GET" class="filter-form">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Orders</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="date" name="from_date" class="form-control"
                                           placeholder="From Date" value="{{ request('from_date') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input type="date" name="to_date" class="form-control"
                                           placeholder="To Date" value="{{ request('to_date') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary-custom flex-fill">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('customer.orders') }}" class="btn btn-secondary" style="background: var(--text-medium); color: white; border: none;">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if($orders->count() > 0)
                        <!-- Orders List -->
                        <div class="orders-list">
                            @foreach($orders as $order)
                                <div class="order-card">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h6>Order #{{ $order->order_number }}</h6>
                                            <div class="order-date">
                                                <i class="fa fa-calendar me-1"></i>
                                                {{ $order->created_at->format('M d, Y h:i A') }}
                                            </div>
                                            <div class="order-id">ID: {{ $order->id }}</div>
                                        </div>
                                        <div class="order-status-section">
                                            <div class="status-badge status-{{ $order->order_status }}">
                                                @switch($order->order_status)
                                                    @case('pending')
                                                        <i class="fa fa-clock-o"></i>
                                                        @break
                                                    @case('processing')
                                                        <i class="fa fa-cog"></i>
                                                        @break
                                                    @case('shipped')
                                                        <i class="fa fa-truck"></i>
                                                        @break
                                                    @case('delivered')
                                                        <i class="fa fa-check-circle"></i>
                                                        @break
                                                    @case('cancelled')
                                                        <i class="fa fa-times-circle"></i>
                                                        @break
                                                @endswitch
                                                {{ ucfirst($order->order_status) }}
                                            </div>
                                            <div class="order-total">
                                                <i class="fa fa-money"></i>
                                                Rs. {{ number_format($order->total, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-items">
                                        @foreach($order->items->take(3) as $item)
                                            <div class="order-item">
                                                <img src="{{ $item->product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="item-image">
                                                <div class="item-details">
                                                    <div class="item-name">{{ $item->product->name }}</div>
                                                    @if($item->variation)
                                                        <div class="item-variation">
                                                            {{ $item->variation->attribute->name }}: {{ $item->variation->attributeValue->value }}
                                                        </div>
                                                    @endif
                                                    <div class="item-quantity">Quantity: {{ $item->quantity }}</div>
                                                </div>
                                                <div class="item-price">Rs. {{ number_format($item->price, 2) }}</div>
                                                <div class="item-total">Rs. {{ number_format($item->total, 2) }}</div>
                                            </div>
                                        @endforeach

                                        @if($order->items->count() > 3)
                                            <div class="more-items">
                                                <i class="fa fa-ellipsis-h me-1"></i>
                                                and {{ $order->items->count() - 3 }} more items
                                            </div>
                                        @endif
                                    </div>

                                    <div class="order-actions">
                                        <div class="order-progress">
                                            <div class="progress-icon {{ in_array($order->order_status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : 'inactive' }}">
                                                <i class="fa fa-check"></i>
                                            </div>
                                            <small class="text-muted">Order Placed</small>

                                            <div class="progress-icon {{ in_array($order->order_status, ['processing', 'shipped', 'delivered']) ? 'active' : 'inactive' }}">
                                                <i class="fa fa-cog"></i>
                                            </div>
                                            <small class="text-muted">Processing</small>

                                            <div class="progress-icon {{ in_array($order->order_status, ['shipped', 'delivered']) ? 'active' : 'inactive' }}">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <small class="text-muted">Shipped</small>

                                            <div class="progress-icon {{ $order->order_status == 'delivered' ? 'active' : 'inactive' }}">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <small class="text-muted">Delivered</small>
                                        </div>

                                        <div class="order-buttons">
                                            <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-primary-custom">
                                                <i class="fa fa-eye"></i> View Details
                                            </a>
                                            @if($order->order_status == 'pending')
                                                <button class="btn btn-danger-custom cancel-order" data-order-id="{{ $order->id }}">
                                                    <i class="fa fa-times"></i> Cancel Order
                                                </button>
                                            @endif
                                            @if($order->order_status == 'delivered')
                                                <a href="{{ route('customer.order.review', $order->id) }}" class="btn btn-primary-custom">
                                                    <i class="fa fa-star"></i> Review
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif

                    @else
                        <!-- Empty Orders -->
                        <div class="empty-orders">
                            <div class="empty-orders-icon">
                                <i class="fa fa-shopping-bag"></i>
                            </div>
                            <h3>No orders found</h3>
                            <p class="text-muted mb-4">
                                @if(request()->hasAny(['status', 'from_date', 'to_date']))
                                    No orders match your current filters. Try adjusting your search criteria.
                                @else
                                    You haven't placed any orders yet. Start shopping to see your orders here.
                                @endif
                            </p>
                            @if(request()->hasAny(['status', 'from_date', 'to_date']))
                                <a href="{{ route('customer.orders') }}" class="btn btn-primary-custom btn-lg">
                                    <i class="fa fa-list me-2"></i>View All Orders
                                </a>
                            @else
                                <a href="{{ route('customer.products') }}" class="btn btn-primary-custom btn-lg">
                                    <i class="fa fa-shopping-bag me-2"></i>Start Shopping
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Cancellation Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                <div class="mb-3">
                    <label for="cancellationReason" class="form-label">Reason for cancellation (optional):</label>
                    <textarea class="form-control" id="cancellationReason" rows="3"
                              placeholder="Please provide a reason for cancelling this order"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelOrder">
                    <i class="fa fa-times"></i> Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations for order cards
    const orderCards = document.querySelectorAll('.order-card');

    // Intersection Observer for fade-in animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    orderCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Handle order cancellation
    let currentOrderId = null;
    let currentCancelButton = null;
    let currentOrderCard = null;

    const cancelButtons = document.querySelectorAll('.cancel-order');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentOrderId = this.getAttribute('data-order-id');
            currentCancelButton = this;
            currentOrderCard = this.closest('.order-card');

            // Show the modal
            const modal = document.getElementById('cancelOrderModal');
            if (typeof bootstrap !== 'undefined') {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            } else {
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        });
    });

    // Handle modal confirmation
    document.getElementById('confirmCancelOrder').addEventListener('click', function() {
        if (!currentOrderId || !currentCancelButton) return;

        const reason = document.getElementById('cancellationReason').value.trim() || 'Cancelled by customer from dashboard';

        // Show loading state
        const originalText = currentCancelButton.innerHTML;
        currentCancelButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Cancelling...';
        currentCancelButton.disabled = true;

        // Hide modal
        const modal = document.getElementById('cancelOrderModal');
        if (typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        } else {
            modal.style.display = 'none';
            modal.classList.remove('show');
        }

        // Make the API call to cancel order
        fetch(`/customer/order/${currentOrderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                cancellation_reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status badge
                const statusBadge = currentOrderCard.querySelector('.status-badge');
                statusBadge.className = 'status-badge status-cancelled';
                statusBadge.innerHTML = '<i class="fa fa-times-circle"></i> Cancelled';

                // Remove the cancel button
                currentCancelButton.remove();

                // Update progress indicators
                const progressIcons = currentOrderCard.querySelectorAll('.progress-icon');
                progressIcons.forEach(icon => {
                    icon.className = 'progress-icon inactive';
                });

                showNotification('Order cancelled successfully', 'success');
            } else {
                currentCancelButton.innerHTML = originalText;
                currentCancelButton.disabled = false;
                showNotification(data.message || 'Failed to cancel order', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            currentCancelButton.innerHTML = originalText;
            currentCancelButton.disabled = false;
            showNotification('An error occurred while cancelling the order', 'error');
        })
        .finally(() => {
            // Reset variables
            currentOrderId = null;
            currentCancelButton = null;
            currentOrderCard = null;
            document.getElementById('cancellationReason').value = '';
        });
    });

    // Enhanced hover effects for order items
    const orderItems = document.querySelectorAll('.order-item');
    orderItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(10px)';
            this.style.backgroundColor = 'rgba(171, 207, 55, 0.15)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.backgroundColor = '';
        });
    });

    // Auto-refresh order statuses (optional)
    let autoRefreshInterval;

    function startAutoRefresh() {
        autoRefreshInterval = setInterval(() => {
            // Check for pending/processing orders
            const activeOrders = document.querySelectorAll('.status-pending, .status-processing');
            if (activeOrders.length > 0) {
                // You could implement a silent refresh here
                console.log('Checking for order updates...');
            }
        }, 30000); // Check every 30 seconds
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }

    // Start auto-refresh when page is visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });

    // Start auto-refresh on load
    startAutoRefresh();

    // Enhanced filter form handling
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        // Auto-submit on select change (optional)
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Could auto-submit here if desired
                // filterForm.submit();
            });
        });

        // Form validation
        filterForm.addEventListener('submit', function(e) {
            const fromDate = this.querySelector('input[name="from_date"]').value;
            const toDate = this.querySelector('input[name="to_date"]').value;

            if (fromDate && toDate && new Date(fromDate) > new Date(toDate)) {
                e.preventDefault();
                showNotification('From date cannot be later than to date', 'error');
                return false;
            }
        });
    }

    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease;';
        notification.innerHTML = `
            <i class="fa fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        `;

        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    // Handle successful actions from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        showNotification('Action completed successfully!', 'success');
    }
    if (urlParams.get('cancelled')) {
        showNotification('Order cancelled successfully!', 'success');
    }

    // Modal close functionality
    document.querySelectorAll('[data-bs-dismiss="modal"], .btn-close').forEach(button => {
        button.addEventListener('click', function() {
            const modal = document.getElementById('cancelOrderModal');
            if (typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            } else {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
            // Reset form
            document.getElementById('cancellationReason').value = '';
        });
    });

    // Close modal on backdrop click
    document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            if (typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(this);
                if (bsModal) bsModal.hide();
            } else {
                this.style.display = 'none';
                this.classList.remove('show');
            }
            // Reset form
            document.getElementById('cancellationReason').value = '';
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoRefresh();
    });
});
</script>
@endpush
@endsection
