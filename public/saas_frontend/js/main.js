/**
 * Allsewa Multi-tenant eCommerce Platform
 * Main JavaScript File
 */

(function ($) {
    "use strict";

    // Global Variables
    const $window = $(window);
    const $body = $('body');

    // Preloader
    $(window).on('load', function () {
        $('#preloader').fadeOut(500, function () {
            $(this).remove();
        });
    });

    // Back to Top Button
    $window.scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    $('.back-to-top').click(function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    // Sticky Header
    $window.scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('header').addClass('sticky-header');
        } else {
            $('header').removeClass('sticky-header');
        }
    });

    // Initialize Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Bootstrap Popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Hero Slider
    if ($('.hero-slider').length) {
        $('.hero-slider').slick({
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            dots: true,
            fade: true,
            cssEase: 'linear',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false
                    }
                }
            ]
        });
    }

    // Product Slider
    if ($('.product-slider').length) {
        $('.product-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            dots: false,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        arrows: false,
                        dots: true
                    }
                }
            ]
        });
    }

    // Initialize WOW.js for animations
    if (typeof WOW === 'function') {
        new WOW().init();
    }

    // Product Quantity Increment/Decrement
    $('.quantity-decrease').on('click', function () {
        const $input = $(this).siblings('input[type="number"]');
        let val = parseInt($input.val());
        if (val > 1) {
            $input.val(val - 1).change();
        }
    });

    $('.quantity-increase').on('click', function () {
        const $input = $(this).siblings('input[type="number"]');
        let val = parseInt($input.val());
        let max = parseInt($input.attr('max'));
        if (!max || val < max) {
            $input.val(val + 1).change();
        }
    });

    // Countdown Timer Function
    $('.countdown').each(function () {
        const $this = $(this);
        const endTime = new Date($this.data('end-time')).getTime();

        const timer = setInterval(function () {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timer);
                $this.html('<span class="expired">EXPIRED</span>');
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $this.find('.days').text(days < 10 ? '0' + days : days);
            $this.find('.hours').text(hours < 10 ? '0' + hours : hours);
            $this.find('.minutes').text(minutes < 10 ? '0' + minutes : minutes);
            $this.find('.seconds').text(seconds < 10 ? '0' + seconds : seconds);
        }, 1000);
    });

    // Product Gallery
    if ($('.product-main-image').length) {
        $('.thumbnail').on('click', function () {
            const newImage = $(this).data('image');
            $('.main-image').attr('src', newImage);
            $('.thumbnail-item').removeClass('active');
            $(this).parent().addClass('active');
        });
    }

    // Checkout Form
    if ($('#checkout-form').length) {
        // Toggle billing address form
        $('#billing_same_as_shipping').change(function () {
            if ($(this).is(':checked')) {
                $('#billing-address-form').addClass('d-none');
            } else {
                $('#billing-address-form').removeClass('d-none');
            }
        });

        // Toggle saved address
        $('#use_saved_address').change(function () {
            if ($(this).is(':checked')) {
                $('#shipping-address-form').addClass('d-none');
            } else {
                $('#shipping-address-form').removeClass('d-none');
            }
        });

        // Toggle payment method forms
        $('.payment-method').change(function () {
            const method = $(this).val();

            if (method === 'card') {
                $('#card-payment-form').removeClass('d-none');
            } else {
                $('#card-payment-form').addClass('d-none');
            }
        });

        // Update shipping cost and total when shipping method changes
        $('.shipping-method').change(function () {
            const shippingCost = parseFloat($(this).data('price'));
            const subtotal = parseFloat($('#subtotal-value').data('value'));
            const tax = subtotal * 0.1;

            $('#shipping-cost').text('$' + shippingCost.toFixed(2));

            const total = subtotal + tax + shippingCost;
            $('#order-total').text('$' + total.toFixed(2));
        });
    }

    // Shopping Cart
    if ($('.cart-quantity').length) {
        // Auto-submit form when quantity changes
        $('.quantity-input').change(function () {
            $(this).closest('form').submit();
        });
    }

    // Shop Page
    if ($('.shop-products').length) {
        // Grid and List view toggle
        $('#grid-view').click(function () {
            $(this).addClass('active');
            $('#list-view').removeClass('active');
            $('.shop-products').removeClass('list-view').addClass('grid-view');
        });

        $('#list-view').click(function () {
            $(this).addClass('active');
            $('#grid-view').removeClass('active');
            $('.shop-products').removeClass('grid-view').addClass('list-view');
        });

        // Apply price filter button
        $('.apply-price-filter').click(function () {
            // Get current URL
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);

            // Update or add min_price and max_price parameters
            const minPrice = $('#min_price').val();
            const maxPrice = $('#max_price').val();

            if (minPrice) {
                params.set('min_price', minPrice);
            } else {
                params.delete('min_price');
            }

            if (maxPrice) {
                params.set('max_price', maxPrice);
            } else {
                params.delete('max_price');
            }

            // Update URL and reload page
            url.search = params.toString();
            window.location.href = url.toString();
        });
    }

    // Add to Cart Animation
    $('.add-to-cart-btn').on('click', function (e) {
        e.preventDefault();
        const $form = $(this).closest('form');
        const $productCard = $(this).closest('.product-card');
        const productImg = $productCard.find('img').eq(0);

        if (productImg.length) {
            const imgClone = productImg.clone()
                .offset({
                    top: productImg.offset().top,
                    left: productImg.offset().left
                })
                .css({
                    'opacity': '0.8',
                    'position': 'absolute',
                    'height': productImg.height(),
                    'width': productImg.width(),
                    'z-index': '9999'
                })
                .appendTo($('body'))
                .animate({
                    'top': $('.cart-icon').offset().top + 10,
                    'left': $('.cart-icon').offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 1000, 'easeInOutExpo');

            setTimeout(function () {
                $('.cart-icon').effect("shake", {
                    times: 2
                }, 200);
            }, 1500);

            imgClone.animate({
                'width': 0,
                'height': 0
            }, function () {
                $(this).detach();
                $form.submit();
            });
        } else {
            $form.submit();
        }
    });

    // Product Review Stars
    if ($('.rating-stars-input').length) {
        $('.rating-stars-input label').hover(
            function () {
                $(this).prevAll('label').addBack().find('i').removeClass('far').addClass('fas');
                $(this).nextAll('label').find('i').removeClass('fas').addClass('far');
            },
            function () {
                const checkedStar = $('input[name="rating"]:checked');
                if (checkedStar.length) {
                    const checkedValue = checkedStar.val();

                    $('.rating-stars-input label').each(function () {
                        const starValue = $(this).prev('input').val();
                        if (starValue <= checkedValue) {
                            $(this).find('i').removeClass('far').addClass('fas');
                        } else {
                            $(this).find('i').removeClass('fas').addClass('far');
                        }
                    });
                } else {
                    $('.rating-stars-input label i').removeClass('fas').addClass('far');
                }
            }
        );

        $('.rating-stars-input input').change(function () {
            const checkedValue = $(this).val();

            $('.rating-stars-input label').each(function () {
                const starValue = $(this).prev('input').val();
                if (starValue <= checkedValue) {
                    $(this).find('i').removeClass('far').addClass('fas');
                } else {
                    $(this).find('i').removeClass('fas').addClass('far');
                }
            });
        });
    }

    // Newsletter Subscription
    $('.newsletter-form').on('submit', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $email = $form.find('input[type="email"]');
        const $button = $form.find('button[type="submit"]');
        const $alert = $form.find('.alert');

        if ($email.val() && $email.val().indexOf('@') > -1) {
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method'),
                data: $form.serialize(),
                success: function (response) {
                    $alert.removeClass('alert-danger').addClass('alert-success').html('Thank you for subscribing!').removeClass('d-none');
                    $email.val('');
                },
                error: function (error) {
                    $alert.removeClass('alert-success').addClass('alert-danger').html('An error occurred. Please try again.').removeClass('d-none');
                },
                complete: function () {
                    $button.prop('disabled', false).html('Subscribe');
                }
            });
        } else {
            $alert.removeClass('alert-success').addClass('alert-danger').html('Please enter a valid email address.').removeClass('d-none');
        }

        setTimeout(function () {
            $alert.addClass('d-none');
        }, 5000);
    });

    // Initialize Lazy Loading for Images
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        const lazyImages = document.querySelectorAll('img.lazy');
        const lazyLoad = function () {
            const scrollTop = window.pageYOffset;
            lazyImages.forEach(function (img) {
                if (img.offsetTop < (window.innerHeight + scrollTop + 500)) {
                    img.src = img.dataset.src;
                    if (img.dataset.srcset) {
                        img.srcset = img.dataset.srcset;
                    }
                    img.classList.remove('lazy');
                }
            });
            if (lazyImages.length === 0) {
                document.removeEventListener('scroll', lazyLoad);
                window.removeEventListener('resize', lazyLoad);
                window.removeEventListener('orientationChange', lazyLoad);
            }
        };

        document.addEventListener('scroll', lazyLoad);
        window.addEventListener('resize', lazyLoad);
        window.addEventListener('orientationChange', lazyLoad);
    }

    // Mobile Menu Toggle
    $('.navbar-toggler').on('click', function () {
        $body.toggleClass('mobile-menu-open');
    });

    // Close Mobile Menu When Clicking Outside
    $(document).on('click', function (e) {
        if ($body.hasClass('mobile-menu-open') && !$(e.target).closest('.navbar-collapse, .navbar-toggler').length) {
            $('.navbar-toggler').click();
        }
    });

})(jQuery);
