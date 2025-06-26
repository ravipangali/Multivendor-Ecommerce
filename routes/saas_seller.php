<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaasSeller\SaasCouponController;
use App\Http\Controllers\SaasSeller\SaasOrderController;
use App\Http\Controllers\SaasSeller\SaasOrderItemController;
use App\Http\Controllers\SaasSeller\SaasPaymentMethodController;
use App\Http\Controllers\SaasSeller\SaasProductController;
use App\Http\Controllers\SaasSeller\SaasProductReviewController;
use App\Http\Controllers\SaasSeller\SaasReportController;
use App\Http\Controllers\SaasSeller\SaasSellerDashboardController;
use App\Http\Controllers\SaasSeller\SaasSellerProfileController;
use App\Http\Controllers\SaasSeller\SaasWithdrawalController;
use App\Http\Controllers\SaasSeller\SaasSellerTransactionController;


Route::middleware(['auth', 'saasrolemanager:seller'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SaasSellerDashboardController::class, 'index'])->name('dashboard');

    // Payment Methods
    Route::resource('payment-methods', SaasPaymentMethodController::class)->names([
        'index' => 'payment-methods.index',
        'create' => 'payment-methods.create',
        'store' => 'payment-methods.store',
        'show' => 'payment-methods.show',
        'edit' => 'payment-methods.edit',
        'update' => 'payment-methods.update',
        'destroy' => 'payment-methods.destroy',
    ]);
    Route::post('payment-methods/{paymentMethod}/set-default', [SaasPaymentMethodController::class, 'setDefault'])
        ->name('payment-methods.set-default');

    // Seller Profile
    Route::get('/profile', [SaasSellerProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [SaasSellerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [SaasSellerProfileController::class, 'update'])->name('profile.update');

    // Products Management
    Route::resource('products', SaasProductController::class);
    // Digital Product File Access Routes
    Route::get('/products/{product}/file/preview', [SaasProductController::class, 'previewFile'])->name('products.file.preview');
    Route::get('/products/{product}/file/download', [SaasProductController::class, 'downloadFile'])->name('products.file.download');
    Route::get('/get-subcategories/{categoryId}', [SaasProductController::class, 'getSubcategories']);
    Route::get('/get-child-categories/{subcategoryId}', [SaasProductController::class, 'getChildCategories']);

    // Orders
    Route::resource('orders', SaasOrderController::class);
    Route::get('/orders/status/pending', [SaasOrderController::class, 'pendingOrders'])->name('orders.pending');
    Route::get('/orders/status/processing', [SaasOrderController::class, 'processingOrders'])->name('orders.processing');
    Route::get('/orders/status/shipped', [SaasOrderController::class, 'shippedOrders'])->name('orders.shipped');
    Route::get('/orders/status/delivered', [SaasOrderController::class, 'deliveredOrders'])->name('orders.delivered');
    Route::get('/orders/status/cancelled', [SaasOrderController::class, 'cancelledOrders'])->name('orders.cancelled');
    Route::get('/orders/status/refunded', [SaasOrderController::class, 'refundedOrders'])->name('orders.refunded');
    Route::get('/orders/{order}/invoice', [SaasOrderController::class, 'invoice'])->name('orders.invoice');

    // Order Items
    Route::resource('orders.items', SaasOrderItemController::class)->except(['create', 'store', 'destroy']);
    Route::post('/orders/{order}/items/update-status', [SaasOrderItemController::class, 'updateStatus'])
        ->name('orders.items.update-status');

    // Product Reviews
Route::get('/reviews', [SaasProductReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/analytics', [SaasProductReviewController::class, 'analytics'])->name('reviews.analytics');
Route::get('/reviews/{review}', [SaasProductReviewController::class, 'show'])->name('reviews.show');
Route::post('/reviews/{review}/respond', [SaasProductReviewController::class, 'respond'])->name('reviews.respond');
Route::post('/reviews/{review}/report', [SaasProductReviewController::class, 'report'])->name('reviews.report');
Route::get('/reviews/product/{product}', [SaasProductReviewController::class, 'productReviews'])->name('reviews.product');

    // Coupons
    Route::resource('coupons', SaasCouponController::class);

    // Transactions
    Route::resource('transactions', SaasSellerTransactionController::class)->only(['index', 'show']);
    Route::get('transactions/export', [SaasSellerTransactionController::class, 'export'])->name('transactions.export');

    // Reports
    Route::get('/reports/sales', [SaasReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/products', [SaasReportController::class, 'productReport'])->name('reports.product');
    Route::get('/reports/customers', [SaasReportController::class, 'customerReport'])->name('reports.customer');

    // Withdrawals
    Route::get('/withdrawals', [SaasWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [SaasWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [SaasWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/history', [SaasWithdrawalController::class, 'history'])->name('withdrawals.history');
    Route::get('/withdrawals/{withdrawal}', [SaasWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/cancel', [SaasWithdrawalController::class, 'cancel'])->name('withdrawals.cancel');
});
