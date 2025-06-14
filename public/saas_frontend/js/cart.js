/**
 * Allsewa Multi-tenant eCommerce Platform
 * Cart JavaScript File
 */

(function ($) {
    "use strict";

    // Cart Class
    class Cart {
        constructor() {
            this.setupEventListeners();
            this.updateCartCount();
        }

        setupEventListeners() {
            // Add to Cart
            $(document).on('submit', '.add-to-cart-form', this.handleAddToCart.bind(this));

            // Update Cart Item Quantity
            $(document).on('click', '.quantity-decrease, .quantity-increase', this.handleQuantityChange.bind(this));
            $(document).on('change', '.quantity-input', this.handleQuantityInputChange.bind(this));

            // Remove Cart Item
            $(document).on('submit', '.remove-cart-item-form', this.handleRemoveCartItem.bind(this));

            // Clear Cart
            $(document).on('submit', '.clear-cart-form', this.handleClearCart.bind(this));

            // Apply Coupon
            $(document).on('submit', '.coupon-form', this.handleApplyCoupon.bind(this));
        }

        handleAddToCart(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            const $button = $form.find('button[type="submit"]');
            const originalText = $button.html();

            // Change button text to loading
            $button.html('<i class="fas fa-spinner fa-spin"></i> Adding...').prop('disabled', true);

            // Get form data
            const formData = $form.serialize();

            // Send AJAX request
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    // Show success message
                    this.showNotification('success', '<i class="fas fa-check-circle me-2"></i> Product added to cart successfully!');

                    // Update cart count
                    this.updateCartCount(response.cartCount);

                    // Animate cart icon
                    this.animateCartIcon();
                },
                error: (error) => {
                    // Show error message
                    this.showNotification('danger', '<i class="fas fa-exclamation-circle me-2"></i> Failed to add product to cart!');
                    console.error('Error adding to cart:', error);
                },
                complete: () => {
                    // Reset button text
                    $button.html(originalText).prop('disabled', false);
                }
            });
        }

        handleQuantityChange(e) {
            const $button = $(e.currentTarget);
            const $input = $button.siblings('input[type="number"]');
            let value = parseInt($input.val());
            const min = parseInt($input.attr('min')) || 1;
            const max = parseInt($input.attr('max')) || 999;

            if ($button.hasClass('quantity-decrease')) {
                value = value > min ? value - 1 : min;
            } else {
                value = value < max ? value + 1 : max;
            }

            $input.val(value).trigger('change');
        }

        handleQuantityInputChange(e) {
            const $input = $(e.currentTarget);
            const $form = $input.closest('form');

            // If in cart page, auto-submit the form
            if ($form.hasClass('update-cart-form')) {
                this.updateCartItemQuantity($form);
            }
        }

        updateCartItemQuantity($form) {
            const $button = $form.find('button[type="submit"]');
            const originalText = $button.html();

            // Change button text to loading if it exists
            if ($button.length) {
                $button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            }

            // Get form data
            const formData = $form.serialize();

            // Send AJAX request
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    // Update cart count
                    this.updateCartCount(response.cartCount);

                    // Update cart total
                    if (response.cartTotal) {
                        $('#cart-subtotal').text('$' + response.cartSubtotal.toFixed(2));
                        $('#cart-total').text('$' + response.cartTotal.toFixed(2));
                    }

                    // Update item total
                    if (response.itemTotal) {
                        $form.closest('tr').find('.item-total').text('$' + response.itemTotal.toFixed(2));
                    }

                    // Show success message
                    this.showNotification('success', '<i class="fas fa-check-circle me-2"></i> Cart updated successfully!');
                },
                error: (error) => {
                    // Show error message
                    this.showNotification('danger', '<i class="fas fa-exclamation-circle me-2"></i> Failed to update cart!');
                    console.error('Error updating cart:', error);
                },
                complete: () => {
                    // Reset button text if it exists
                    if ($button.length) {
                        $button.html(originalText).prop('disabled', false);
                    }
                }
            });
        }

        handleRemoveCartItem(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            const $row = $form.closest('tr');

            // Confirm before removing using SweetAlert
            Swal.fire({
                title: 'Remove Item?',
                text: 'Are you sure you want to remove this item from your cart?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request
                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: $form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (response) => {
                            // Remove row with animation
                            $row.fadeOut(300, function() {
                                $(this).remove();

                                // If no items left, reload page to show empty cart message
                                if ($('.cart-item').length === 0) {
                                    window.location.reload();
                                }
                            });

                            // Update cart count
                            this.updateCartCount(response.cartCount);

                            // Update cart total
                            if (response.cartTotal) {
                                $('#cart-subtotal').text('$' + response.cartSubtotal.toFixed(2));
                                $('#cart-total').text('$' + response.cartTotal.toFixed(2));
                            }

                            // Show success message
                            this.showNotification('success', '<i class="fas fa-check-circle me-2"></i> Item removed from cart!');
                        },
                        error: (error) => {
                            // Show error message
                            this.showNotification('danger', '<i class="fas fa-exclamation-circle me-2"></i> Failed to remove item from cart!');
                            console.error('Error removing from cart:', error);
                        }
                    });
                }
            });
        }

        handleClearCart(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);

            // Confirm before clearing using SweetAlert
            Swal.fire({
                title: 'Clear Cart?',
                text: 'Are you sure you want to clear your cart? This will remove all items.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f56565',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Clear Cart',
                cancelButtonText: 'Keep Shopping'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request
                    $.ajax({
                        url: $form.attr('action'),
                        method: 'POST',
                        data: $form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (response) => {
                            // Reload page to show empty cart
                            window.location.reload();
                        },
                        error: (error) => {
                            // Show error message
                            this.showNotification('danger', '<i class="fas fa-exclamation-circle me-2"></i> Failed to clear cart!');
                            console.error('Error clearing cart:', error);
                        }
                    });
                }
            });
        }

        handleApplyCoupon(e) {
            e.preventDefault();
            const $form = $(e.currentTarget);
            const $button = $form.find('button[type="submit"]');
            const originalText = $button.html();

            // Change button text to loading
            $button.html('<i class="fas fa-spinner fa-spin"></i> Applying...').prop('disabled', true);

            // Get form data
            const formData = $form.serialize();

            // Send AJAX request
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    if (response.valid) {
                        // Show coupon discount
                        $('.coupon-discount').removeClass('d-none');
                        $('#discount-amount').text('-$' + response.discountAmount.toFixed(2));

                        // Update cart total
                        $('#cart-total').text('$' + response.cartTotal.toFixed(2));

                        // Show success message
                        this.showNotification('success', '<i class="fas fa-check-circle me-2"></i> Coupon applied successfully!');
                    } else {
                        // Show error message
                        this.showNotification('warning', '<i class="fas fa-exclamation-triangle me-2"></i> ' + response.message);
                    }
                },
                error: (error) => {
                    // Show error message
                    this.showNotification('danger', '<i class="fas fa-exclamation-circle me-2"></i> Failed to apply coupon!');
                    console.error('Error applying coupon:', error);
                },
                complete: () => {
                    // Reset button text
                    $button.html(originalText).prop('disabled', false);
                }
            });
        }

        updateCartCount(count) {
            // If count is not provided, get it from the server
            if (count === undefined) {
                $.ajax({
                    url: '/cart/count',
                    method: 'GET',
                    success: (response) => {
                        $('.cart-count').text(response.count);
                    }
                });
            } else {
                $('.cart-count').text(count);
            }
        }

        animateCartIcon() {
            $('.cart-icon').effect("shake", {
                times: 2,
                distance: 3
            }, 300);
        }

        showNotification(type, message) {
            // Create notification element
            const $notification = $('<div class="toast align-items-center text-white bg-' + type + ' border-0" role="alert" aria-live="assertive" aria-atomic="true">');
            $notification.html(`
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `);

            // Add to notification container
            const $container = $('.toast-container');
            if ($container.length === 0) {
                $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
            }
            $('.toast-container').append($notification);

            // Initialize and show toast
            const toast = new bootstrap.Toast($notification[0], {
                delay: 3000
            });
            toast.show();

            // Remove after hiding
            $notification.on('hidden.bs.toast', function () {
                $(this).remove();
            });
        }
    }

    // Initialize Cart when document is ready
    $(document).ready(function() {
        window.allsewaCart = new Cart();
    });

})(jQuery);
