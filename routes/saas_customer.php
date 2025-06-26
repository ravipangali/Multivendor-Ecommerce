<?php

use App\Http\Controllers\SaasCustomer\SaasCustomerController;
use App\Http\Controllers\SaasCustomer\SaasCustomerCartController;
use App\Http\Controllers\SaasCustomer\SaasCustomerCheckoutController;
use App\Http\Controllers\SaasCustomer\SaasCustomerWishlistController;
use App\Http\Controllers\SaasCustomer\SaasCustomerAccountController;
use App\Http\Controllers\SaasCustomer\SaasCustomerSearchController;
use App\Http\Controllers\SaasCustomer\SaasCustomerSellerController;
use App\Http\Controllers\SaasCustomer\SaasPageController;
use App\Http\Controllers\SaasCustomer\SaasBlogController;
use App\Http\Controllers\SaasCustomer\SaasDigitalProductController;
use App\Http\Controllers\SaasCustomer\SaasRefundController;
use App\Http\Controllers\SaasCustomer\SaasPaymentMethodController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [SaasCustomerController::class, 'home'])->name('customer.home');

// CMS Pages
Route::get('/page/{slug}', [SaasPageController::class, 'show'])->name('customer.page');
Route::get('/terms-and-conditions', [SaasPageController::class, 'terms'])->name('customer.terms');
Route::get('/privacy-policy', [SaasPageController::class, 'privacy'])->name('customer.privacy');
Route::get('/about-us', [SaasPageController::class, 'about'])->name('customer.about');
Route::get('/contact-us', [SaasPageController::class, 'contact'])->name('customer.contact');

// Blog Routes
Route::prefix('blog')->name('customer.blog.')->group(function () {
    Route::get('/', [SaasBlogController::class, 'index'])->name('index');
    Route::get('/search', [SaasBlogController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [SaasBlogController::class, 'category'])->name('category');
    Route::get('/{slug}', [SaasBlogController::class, 'show'])->name('show');
});

// Product routes
Route::get('/products', [SaasCustomerController::class, 'saasProductListing'])->name('customer.products');
Route::get('/product/{slug}', [SaasCustomerController::class, 'saasProductDetail'])->name('customer.product.detail');
Route::get('/category/{slug}', [SaasCustomerController::class, 'saasCategoryProducts'])->name('customer.category');
Route::get('/brand/{slug}', [SaasCustomerController::class, 'saasBrandProducts'])->name('customer.brand');
Route::get('/search', [SaasCustomerSearchController::class, 'saasSearch'])->name('customer.search');



// Brands and Sellers routes
Route::get('/brands', [SaasCustomerSellerController::class, 'saasBrandsListing'])->name('customer.brands');
Route::get('/sellers', [SaasCustomerSellerController::class, 'saasSellersListing'])->name('customer.sellers');
Route::get('/seller/{id}', [SaasCustomerSellerController::class, 'saasSellerProfile'])->name('customer.seller.profile');

// Cart routes (accessible to all users)
Route::post('/cart/add', [SaasCustomerCartController::class, 'saasAddToCart'])->name('customer.cart.add');
Route::post('/cart/bulk-add', [SaasCustomerCartController::class, 'saasAddToCart'])->name('customer.cart.bulk-add');
Route::get('/cart', [SaasCustomerCartController::class, 'saasIndex'])->name('customer.cart');
Route::post('/cart/update', [SaasCustomerCartController::class, 'saasUpdate'])->name('customer.cart.update');
Route::delete('/cart/remove/{id}', [SaasCustomerCartController::class, 'saasRemove'])->name('customer.cart.remove');
Route::delete('/cart/clear', [SaasCustomerCartController::class, 'saasClear'])->name('customer.cart.clear');
Route::post('/cart/apply-coupon', [SaasCustomerCartController::class, 'saasApplyCoupon'])->name('customer.cart.apply-coupon');
Route::delete('/cart/remove-coupon', [SaasCustomerCartController::class, 'saasRemoveCoupon'])->name('customer.cart.remove-coupon');

// Wishlist toggle route (accessible to all users for AJAX)
Route::post('/wishlist/toggle', [SaasCustomerWishlistController::class, 'saasToggle'])->name('customer.wishlist.toggle');

// Authenticated customer routes
Route::middleware(['auth', 'saasrolemanager:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Account/Dashboard
    Route::get('/dashboard', [SaasCustomerAccountController::class, 'saasDashboard'])->name('dashboard');
    Route::get('/profile', [SaasCustomerAccountController::class, 'saasProfile'])->name('profile');
    Route::patch('/profile/update', [SaasCustomerAccountController::class, 'saasUpdateProfile'])->name('profile.update');
    Route::put('/profile/photo', [SaasCustomerAccountController::class, 'saasUpdateProfilePhoto'])->name('profile.photo');
    Route::put('/profile/password', [SaasCustomerAccountController::class, 'saasUpdatePassword'])->name('profile.password');
    Route::delete('/profile/delete', [SaasCustomerAccountController::class, 'saasDeleteAccount'])->name('profile.delete');
    Route::put('/password/update', [SaasCustomerAccountController::class, 'saasUpdatePassword'])->name('password.update');

    // Orders
    Route::get('/orders', [SaasCustomerAccountController::class, 'saasOrders'])->name('orders');
    Route::get('/order/{id}', [SaasCustomerAccountController::class, 'saasOrderDetail'])->name('order.detail');
    Route::get('/order/{id}/review', [SaasCustomerAccountController::class, 'saasOrderReview'])->name('order.review');
    Route::post('/order/{id}/review', [SaasCustomerAccountController::class, 'saasSubmitOrderReview'])->name('order.review.submit');
    Route::put('/order/{id}/cancel', [SaasCustomerAccountController::class, 'saasCancelOrder'])->name('order.cancel');
    Route::post('/order/{id}/cancel', [SaasCustomerAccountController::class, 'saasCancelOrder'])->name('order.cancel.ajax');

    // Wishlist
    Route::get('/wishlist', [SaasCustomerWishlistController::class, 'saasIndex'])->name('wishlist');
    Route::post('/wishlist/add', [SaasCustomerWishlistController::class, 'saasAdd'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [SaasCustomerWishlistController::class, 'saasRemove'])->name('wishlist.remove');
    Route::post('/wishlist/bulk-remove', [SaasCustomerWishlistController::class, 'saasBulkRemove'])->name('wishlist.bulk-remove');
    Route::delete('/wishlist/clear', [SaasCustomerWishlistController::class, 'saasClear'])->name('wishlist.clear');

    // Checkout
    Route::get('/checkout', [SaasCustomerCheckoutController::class, 'saasIndex'])->name('checkout');
    Route::post('/checkout/process', [SaasCustomerCheckoutController::class, 'saasProcess'])->name('checkout.process');
    Route::get('/checkout/success', [SaasCustomerCheckoutController::class, 'saasSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [SaasCustomerCheckoutController::class, 'saasCancel'])->name('checkout.cancel');

    // Reviews
    Route::post('/product/{product}/review', [SaasCustomerAccountController::class, 'saasAddReview'])->name('product.review');
    Route::put('/review/{review}/update', [SaasCustomerAccountController::class, 'saasUpdateReview'])->name('review.update');
    Route::delete('/review/{review}/delete', [SaasCustomerAccountController::class, 'saasDeleteReview'])->name('review.delete');

    // Digital Product Downloads
    Route::get('/order/{orderId}/digital-product/{productId}/download', [SaasDigitalProductController::class, 'download'])->name('digital-product.download');
    Route::get('/order/{orderId}/digital-product/{productId}/preview', [SaasDigitalProductController::class, 'preview'])->name('digital-product.preview');
    Route::get('/order/{orderId}/digital-products', [SaasDigitalProductController::class, 'getDownloadableProducts'])->name('digital-products.list');

    // Refund Routes
    Route::prefix('refunds')->name('refunds.')->group(function () {
        Route::get('/', [SaasRefundController::class, 'index'])->name('index');
        Route::get('/create', [SaasRefundController::class, 'create'])->name('create');
        Route::post('/', [SaasRefundController::class, 'store'])->name('store');
        Route::get('/{refund}', [SaasRefundController::class, 'show'])->name('show');
        Route::get('/{refund}/attachment', [SaasRefundController::class, 'downloadAttachment'])->name('attachment.download');
});

// Payment Method Routes
Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
    Route::get('/', [SaasPaymentMethodController::class, 'index'])->name('index');
    Route::get('/create', [SaasPaymentMethodController::class, 'create'])->name('create');
    Route::post('/', [SaasPaymentMethodController::class, 'store'])->name('store');
    Route::get('/{paymentMethod}', [SaasPaymentMethodController::class, 'show'])->name('show');
    Route::get('/{paymentMethod}/edit', [SaasPaymentMethodController::class, 'edit'])->name('edit');
    Route::put('/{paymentMethod}', [SaasPaymentMethodController::class, 'update'])->name('update');
    Route::delete('/{paymentMethod}', [SaasPaymentMethodController::class, 'destroy'])->name('destroy');
    Route::post('/{paymentMethod}/set-default', [SaasPaymentMethodController::class, 'setDefault'])->name('set-default');
    Route::post('/{paymentMethod}/toggle-status', [SaasPaymentMethodController::class, 'toggleStatus'])->name('toggle-status');
    });
});

// AJAX routes for dynamic functionality
Route::post('/ajax/get-subcategories', [SaasCustomerSearchController::class, 'saasGetSubcategories'])->name('customer.ajax.subcategories');
Route::post('/ajax/get-product-variations', [SaasCustomerController::class, 'saasGetProductVariations'])->name('customer.ajax.product.variations');
Route::get('/ajax/cart-count', [SaasCustomerCartController::class, 'saasGetCartCount'])->name('customer.ajax.cart.count');
Route::get('/ajax/wishlist-count', [SaasCustomerWishlistController::class, 'saasGetWishlistCount'])->name('customer.wishlist.count');
