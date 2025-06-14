@extends('saas_customer.saas_layout.saas_layout')

@push('styles')
<style>
  .wishlist-container {
    background: linear-gradient(135deg, var(--accent-color) 0%, #f1f5f9 100%);
    min-height: 80vh;
    padding: 3rem 0;
    position: relative;
  }

  .wishlist-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 25% 25%, rgba(171, 207, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(9, 113, 126, 0.05) 0%, transparent 50%);
    pointer-events: none;
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

  .wishlist-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
  }

  .wishlist-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(50%, -50%);
  }

  /* Wishlist specific styles */

  .wishlist-content {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-light);
    position: relative;
  }

  .wishlist-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
  }

  .wishlist-actions {
    background: var(--accent-color);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .select-all-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .select-all-checkbox {
    width: 18px;
    height: 18px;
    accent-color: var(--primary-color);
  }

  .bulk-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
  }

  .btn-bulk {
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-bulk-cart {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
  }

  .btn-bulk-cart:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
  }

  .btn-bulk-remove {
    background: linear-gradient(135deg, var(--danger), #dc3545);
    color: var(--white);
  }

  .btn-bulk-remove:hover {
    background: linear-gradient(135deg, #c82333, #a71e2a);
    transform: translateY(-1px);
  }

  .wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
  }

  .wishlist-item {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-light);
    position: relative;
  }

  .wishlist-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
  }

  .wishlist-checkbox {
    position: absolute;
    top: 15px;
    left: 15px;
    width: 20px;
    height: 20px;
    accent-color: var(--primary-color);
    z-index: 5;
    cursor: pointer;
  }

  .product-image {
    position: relative;
    height: 220px;
    overflow: hidden;
  }

  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .wishlist-item:hover .product-image img {
    transform: scale(1.05);
  }

  .remove-wishlist {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    background: var(--white);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    color: var(--danger);
    z-index: 5;
  }

  .remove-wishlist:hover {
    background: var(--danger);
    color: var(--white);
    transform: scale(1.1);
  }

  .stock-status {
    position: absolute;
    bottom: 15px;
    left: 15px;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 5;
  }

  .stock-in {
    background: linear-gradient(135deg, var(--success), #27ae60);
    color: var(--white);
  }

  .stock-out {
    background: linear-gradient(135deg, var(--danger), #e74c3c);
    color: var(--white);
  }

  .product-content {
    padding: 1.5rem;
  }

  .product-brand {
    color: var(--text-light);
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }

  .product-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    line-height: 1.4;
  }

  .product-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .product-title a:hover {
    color: var(--primary-color);
  }

  .product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
  }

  .stars {
    color: #ffd700;
    font-size: 0.875rem;
  }

  .rating-count {
    color: var(--text-light);
    font-size: 0.75rem;
  }

  .product-price {
    margin-bottom: 1rem;
  }

  .current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary-color);
  }

  .original-price {
    font-size: 0.875rem;
    color: var(--text-light);
    text-decoration: line-through;
    margin-left: 0.5rem;
  }

  .product-actions {
    display: flex;
    gap: 0.75rem;
  }

  .btn-add-cart {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-add-cart:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-1px);
  }

  .btn-add-cart:disabled {
    background: var(--text-muted);
    cursor: not-allowed;
    transform: none;
  }

  .btn-view {
    width: 44px;
    height: 44px;
    background: transparent;
    color: var(--text-medium);
    border: 2px solid var(--border-light);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
  }

  .btn-view:hover {
    background: var(--secondary-color);
    color: var(--white);
    border-color: var(--secondary-color);
    text-decoration: none;
  }

  .empty-wishlist {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
  }

  .empty-wishlist-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--text-muted), #94a3b8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: var(--white);
  }

  .empty-wishlist h3 {
    color: var(--text-dark);
    margin-bottom: 1rem;
  }

  .empty-wishlist p {
    color: var(--text-medium);
    margin-bottom: 2rem;
  }

  .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--white);
    text-decoration: none;
  }

  .wishlist-stats {
    background: var(--accent-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
    display: flex;
    justify-content: space-around;
    text-align: center;
  }

  .stat-item {
    flex: 1;
  }

  .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    display: block;
    margin-bottom: 0.25rem;
  }

  .stat-label {
    color: var(--text-medium);
    font-size: 0.875rem;
  }

  @media (max-width: 768px) {
    .wishlist-container {
      padding: 1rem 0;
    }

    .wishlist-header {
      padding: 2rem;
      text-align: center;
    }

    /* Enhanced sidebar handles responsive styles */

    .wishlist-content {
      padding: 1.5rem;
    }

    .wishlist-grid {
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .wishlist-actions {
      flex-direction: column;
      text-align: center;
    }

    .bulk-actions {
      justify-content: center;
    }

    .wishlist-stats {
      flex-direction: column;
      gap: 1rem;
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
                        <li class="breadcrumb-item active" aria-current="page">My Wishlist</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Wishlist Content -->
<section class="wishlist-container">
    <div class="container">

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('saas_customer.saas_layout.saas_partials.saas_dashboard_sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="wishlist-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">My Wishlist</h2>
                            <p class="mb-0 text-white opacity-75">
                                Save your favorite products for later
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-end">
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                                <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                                    <i class="fa fa-heart me-1"></i>
                                    {{ $wishlistItems->count() ?? 0 }} {{ Str::plural('Item', $wishlistItems->count() ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                    <!-- Wishlist Stats -->
                    <div class="wishlist-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $wishlistItems->count() }}</span>
                            <span class="stat-label">Total Items</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $wishlistItems->where('product.stock', '>', 0)->count() }}</span>
                            <span class="stat-label">In Stock</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">Rs. {{ number_format($wishlistItems->sum('product.final_price'), 0) }}</span>
                            <span class="stat-label">Total Value</span>
                        </div>
                    </div>

                    <div class="wishlist-content">
                        <!-- Bulk Actions -->
                        <div class="wishlist-actions">
                            <div class="select-all-wrapper">
                                <input type="checkbox" id="selectAll" class="select-all-checkbox">
                                <label for="selectAll" class="text-dark font-weight-bold">Select All</label>
                            </div>
                            <div class="bulk-actions">
                                <button class="btn-bulk btn-bulk-cart" id="addSelectedToCart">
                                    <i class="fa fa-shopping-cart"></i> Add Selected to Cart
                                </button>
                                <button class="btn-bulk btn-bulk-remove" id="removeSelected">
                                    <i class="fa fa-trash"></i> Remove Selected
                                </button>
                            </div>
                        </div>

                        <!-- Wishlist Items Grid -->
                        <div class="wishlist-grid">
                            @foreach($wishlistItems as $item)
                                <div class="wishlist-item" data-product-id="{{ $item->product->id }}" data-wishlist-id="{{ $item->id }}">
                                    <input type="checkbox" class="wishlist-checkbox item-checkbox" value="{{ $item->product->id }}">

                                    <div class="product-image">
                                        <img src="{{ $item->product->images->first()->image_url ?? asset('saas_frontend/images/shop-items/shop-item27.png') }}"
                                             alt="{{ $item->product->name }}">

                                        <button class="remove-wishlist" data-product-id="{{ $item->product->id }}" title="Remove from Wishlist">
                                            <i class="fa fa-times"></i>
                                        </button>

                                        <div class="stock-status {{ $item->product->stock > 0 ? 'stock-in' : 'stock-out' }}">
                                            {{ $item->product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                        </div>
                                    </div>

                                    <div class="product-content">
                                        <div class="product-brand">{{ $item->product->brand->name ?? 'No Brand' }}</div>
                                        <h5 class="product-title">
                                            <a href="{{ route('customer.product.detail', $item->product->slug) }}">
                                                {{ Str::limit($item->product->name, 45) }}
                                            </a>
                                        </h5>

                                        <div class="product-rating">
                                            @php
                                                $avgRating = $item->product->reviews->avg('rating') ?? 0;
                                            @endphp
                                            <div class="stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa{{ $i <= $avgRating ? 's' : 'r' }} fa-star"></i>
                                                @endfor
                                            </div>
                                            <span class="rating-count">({{ $item->product->reviews->count() }})</span>
                                        </div>

                                        <div class="product-price">
                                            <span class="current-price">Rs. {{ number_format($item->product->final_price, 2) }}</span>
                                            @if($item->product->discount > 0)
                                                <span class="original-price">Rs. {{ number_format($item->product->price, 2) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-actions">
                                            <button class="btn-add-cart add-to-cart"
                                                    data-product-id="{{ $item->product->id }}"
                                                    {{ $item->product->stock <= 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-shopping-cart me-1"></i>
                                                {{ $item->product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                                            </button>
                                            <a href="{{ route('customer.product.detail', $item->product->slug) }}" class="btn-view" title="View Product">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Empty Wishlist -->
                    <div class="wishlist-content">
                        <div class="empty-wishlist">
                            <div class="empty-wishlist-icon">
                                <i class="fa fa-heart"></i>
                            </div>
                            <h3>Your wishlist is empty</h3>
                            <p>Save your favorite products to your wishlist and easily find them later.</p>
                            <a href="{{ route('customer.products') }}" class="btn-primary">
                                <i class="fa fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButtons();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkActionButtons();
        });
    });

    function updateSelectAllState() {
        if (selectAllCheckbox) {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            const totalCount = itemCheckboxes.length;

            selectAllCheckbox.checked = checkedCount === totalCount;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }
    }

    function updateBulkActionButtons() {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked');
        const bulkButtons = document.querySelectorAll('.btn-bulk');

        bulkButtons.forEach(button => {
            button.disabled = checkedItems.length === 0;
            button.style.opacity = checkedItems.length === 0 ? '0.5' : '1';
        });
    }

    // Remove from wishlist
    document.querySelectorAll('.remove-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const wishlistItem = this.closest('.wishlist-item');

            Swal.fire({
                title: 'Remove from Wishlist?',
                text: 'Are you sure you want to remove this item from your wishlist?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeFromWishlist(productId, wishlistItem);
                }
            });
        });
    });

    // Add to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;

            const productId = this.getAttribute('data-product-id');
            const originalText = this.innerHTML;

            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            this.disabled = true;

            addToCart(productId, this, originalText);
        });
    });

    // Bulk add to cart
    const addSelectedBtn = document.getElementById('addSelectedToCart');
    if (addSelectedBtn) {
        addSelectedBtn.addEventListener('click', function() {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            const productIds = Array.from(selectedItems).map(checkbox => checkbox.value);

            if (productIds.length === 0) {
                showNotification('Please select items to add to cart', 'warning');
                return;
            }

            bulkAddToCart(productIds);
        });
    }

    // Bulk remove
    const removeSelectedBtn = document.getElementById('removeSelected');
    if (removeSelectedBtn) {
        removeSelectedBtn.addEventListener('click', function() {
            const selectedItems = document.querySelectorAll('.item-checkbox:checked');
            const productIds = Array.from(selectedItems).map(checkbox => checkbox.value);

            if (productIds.length === 0) {
                showNotification('Please select items to remove', 'warning');
                return;
            }

            Swal.fire({
                title: 'Remove Multiple Items?',
                text: `Are you sure you want to remove ${productIds.length} item(s) from your wishlist?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Remove All',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkRemoveFromWishlist(productIds);
                }
            });
        });
    }

        function removeFromWishlist(productId, wishlistItem) {
        // Find the wishlist item ID from the data attribute
        const wishlistId = wishlistItem.getAttribute('data-wishlist-id');

        fetch(`{{ route("customer.wishlist.remove", "PLACEHOLDER") }}`.replace('PLACEHOLDER', wishlistId), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            if (data.success) {
                wishlistItem.style.transition = 'all 0.3s ease';
                wishlistItem.style.opacity = '0';
                wishlistItem.style.transform = 'scale(0.8)';

                setTimeout(() => {
                    wishlistItem.remove();
                    updateStats();

                    // Check if wishlist is empty
                    if (document.querySelectorAll('.wishlist-item').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 300);

                showNotification('Item removed from wishlist', 'success');
            } else {
                showNotification('Failed to remove item from wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item from wishlist', 'error');
        });
    }

    function addToCart(productId, button, originalText) {
        fetch('{{ route("customer.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                button.style.background = 'linear-gradient(135deg, var(--success), #27ae60)';

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.background = '';
                    button.disabled = false;
                }, 2000);

                showNotification('Product added to cart successfully!', 'success');
            } else {
                button.innerHTML = originalText;
                button.disabled = false;
                showNotification(data.message || 'Failed to add product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification('Error adding product to cart', 'error');
        });
    }

    function bulkAddToCart(productIds) {
        const addSelectedBtn = document.getElementById('addSelectedToCart');
        const originalText = addSelectedBtn.innerHTML;

        addSelectedBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
        addSelectedBtn.disabled = true;

        fetch('{{ route("customer.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_ids: productIds,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`${data.added_count} item(s) added to cart successfully!`, 'success');

                // Uncheck all items
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectAllState();
            } else {
                showNotification(data.message || 'Failed to add items to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding items to cart', 'error');
        })
        .finally(() => {
            addSelectedBtn.innerHTML = originalText;
            addSelectedBtn.disabled = false;
            updateBulkActionButtons();
        });
    }

    function bulkRemoveFromWishlist(productIds) {
        const removeSelectedBtn = document.getElementById('removeSelected');
        const originalText = removeSelectedBtn.innerHTML;

        removeSelectedBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Removing...';
        removeSelectedBtn.disabled = true;

        fetch('{{ route("customer.wishlist.bulk-remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_ids: productIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove selected items with animation
                productIds.forEach(productId => {
                    const wishlistItem = document.querySelector(`[data-product-id="${productId}"]`);
                    if (wishlistItem) {
                        wishlistItem.style.transition = 'all 0.3s ease';
                        wishlistItem.style.opacity = '0';
                        wishlistItem.style.transform = 'scale(0.8)';

                        setTimeout(() => {
                            wishlistItem.remove();
                        }, 300);
                    }
                });

                setTimeout(() => {
                    updateStats();
                    if (document.querySelectorAll('.wishlist-item').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 350);

                showNotification(`${data.removed_count} item(s) removed from wishlist`, 'success');
            } else {
                showNotification(data.message || 'Failed to remove items from wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing items from wishlist', 'error');
        })
        .finally(() => {
            removeSelectedBtn.innerHTML = originalText;
            removeSelectedBtn.disabled = false;
            updateBulkActionButtons();
        });
    }

    function updateStats() {
        const remainingItems = document.querySelectorAll('.wishlist-item').length;

        // Update header badge
        const headerBadge = document.querySelector('.wishlist-header .badge');
        if (headerBadge) {
            headerBadge.innerHTML = `<i class="fa fa-heart me-1"></i>${remainingItems} ${remainingItems === 1 ? 'Item' : 'Items'}`;
        }

        // Update stats section
        const statNumbers = document.querySelectorAll('.stat-number');
        if (statNumbers.length >= 1) {
            statNumbers[0].textContent = remainingItems;
        }
    }

    // Animation on load
    const wishlistItems = document.querySelectorAll('.wishlist-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    wishlistItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(item);
    });

    // Initialize bulk action buttons
    updateBulkActionButtons();

    // Show notification function
    function showNotification(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
        const iconClass = type === 'success' ? 'fa-check-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle');

        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease;';
        notification.innerHTML = `
            <i class="fa ${iconClass} me-2"></i>
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
});
</script>
@endpush
@endsection
