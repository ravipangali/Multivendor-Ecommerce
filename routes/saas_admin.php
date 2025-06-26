<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaasAdmin\SaasAdminDashboardController;
use App\Http\Controllers\SaasAdmin\SaasAttributeController;
use App\Http\Controllers\SaasAdmin\SaasAttributeValueController;
use App\Http\Controllers\SaasAdmin\SaasBannerController;
use App\Http\Controllers\SaasAdmin\SaasBrandController;
use App\Http\Controllers\SaasAdmin\SaasCategoryController;
use App\Http\Controllers\SaasAdmin\SaasSubCategoryController;
use App\Http\Controllers\SaasAdmin\SaasChildCategoryController;
use App\Http\Controllers\SaasAdmin\SaasCouponController;
use App\Http\Controllers\SaasAdmin\SaasCustomerController;
use App\Http\Controllers\SaasAdmin\SaasCustomerProfileController;
use App\Http\Controllers\SaasAdmin\SaasFlashDealController;
use App\Http\Controllers\SaasAdmin\SaasFlashDealProductController;
use App\Http\Controllers\SaasAdmin\SaasOrderController;
use App\Http\Controllers\SaasAdmin\SaasPaymentMethodController;
use App\Http\Controllers\SaasAdmin\SaasProductController;
use App\Http\Controllers\SaasAdmin\SaasProductReviewController;
use App\Http\Controllers\SaasAdmin\SaasReportController;
use App\Http\Controllers\SaasAdmin\SaasSellerController;
use App\Http\Controllers\SaasAdmin\SaasSellerProfileController;
use App\Http\Controllers\SaasAdmin\SaasSettingsController;
use App\Http\Controllers\SaasAdmin\SaasUnitController;
use App\Http\Controllers\SaasAdmin\SaasUserController;
use App\Http\Controllers\SaasAdmin\SaasPageController;
use App\Http\Controllers\SaasAdmin\SaasBlogCategoryController;
use App\Http\Controllers\SaasAdmin\SaasBlogPostController;
use App\Http\Controllers\SaasAdmin\SaasPosController;
use App\Http\Controllers\SaasAdmin\SaasInHouseSaleController;
use App\Http\Controllers\SaasAdmin\SaasInHouseProductController;
use App\Http\Controllers\SaasAdmin\SaasTransactionController;
use App\Http\Controllers\SaasAdmin\TinyMCEController;

Route::middleware(['auth', 'saasrolemanager:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [SaasAdminDashboardController::class, 'index'])->name('dashboard');

    // POS System
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [SaasPosController::class, 'index'])->name('index');
        Route::get('/search-products', [SaasPosController::class, 'searchProducts'])->name('search-products');
        Route::get('/search-customers', [SaasPosController::class, 'searchCustomers'])->name('search-customers');
        Route::get('/product/{id}', [SaasPosController::class, 'getProduct'])->name('get-product');
        Route::post('/process-sale', [SaasPosController::class, 'processSale'])->name('process-sale');
        Route::get('/receipt/{saleNumber}', [SaasPosController::class, 'printReceipt'])->name('receipt');
    });

    // In-House Sales Management
    Route::prefix('in-house-sales')->name('in-house-sales.')->group(function () {
        Route::get('/', [SaasInHouseSaleController::class, 'index'])->name('index');
        Route::get('/reports', [SaasInHouseSaleController::class, 'report'])->name('reports');
        Route::get('/{sale}', [SaasInHouseSaleController::class, 'show'])->name('show');
        Route::delete('/{sale}', [SaasInHouseSaleController::class, 'destroy'])->name('destroy');

        Route::get('/{sale}/receipt', [SaasInHouseSaleController::class, 'printReceipt'])->name('receipt');
    });

    // In-House Products Management
    Route::resource('in-house-products', SaasInHouseProductController::class);

    // Category Management
    Route::resource('categories', SaasCategoryController::class);
    Route::resource('subcategories', SaasSubCategoryController::class);
    Route::resource('childcategories', SaasChildCategoryController::class);

    // API routes for dependent dropdowns
    Route::get('/subcategories/by-category/{categoryId}', [SaasSubCategoryController::class, 'getByCategory'])->name('subcategories.by-category');
    Route::get('/childcategories/by-subcategory/{subcategoryId}', [SaasChildCategoryController::class, 'getBySubcategory'])->name('childcategories.by-subcategory');

    // Product Management
    Route::resource('brands', SaasBrandController::class);
    Route::resource('attributes', SaasAttributeController::class);
    Route::resource('attribute-values', SaasAttributeValueController::class);
    Route::resource('units', SaasUnitController::class);
    Route::resource('products', SaasProductController::class);
    // Digital Product File Access Routes
    Route::get('/products/{product}/file/preview', [SaasProductController::class, 'previewFile'])->name('products.file.preview');
    Route::get('/products/{product}/file/download', [SaasProductController::class, 'downloadFile'])->name('products.file.download');
    // Product Approval Routes
    Route::patch('/products/{product}/approve', [SaasProductController::class, 'approve'])->name('products.approve');
    Route::patch('/products/{product}/deny', [SaasProductController::class, 'deny'])->name('products.deny');
    Route::patch('/products/{product}/reset-status', [SaasProductController::class, 'resetStatus'])->name('products.reset-status');
    Route::resource('product-reviews', SaasProductReviewController::class);
    Route::patch('product-reviews/{productReview}/toggle-approval', [SaasProductReviewController::class, 'toggleApproval'])->name('product-reviews.toggle-approval');
    Route::patch('product-reviews/{productReview}/clear-report', [SaasProductReviewController::class, 'clearReport'])->name('product-reviews.clear-report');

    // Marketing
    Route::resource('banners', SaasBannerController::class);
    Route::resource('flash-deals', SaasFlashDealController::class);
    Route::post('flash-deals/{flash_deal}/duplicate', [SaasFlashDealController::class, 'duplicate'])->name('flash-deals.duplicate');
    Route::resource('flash-deal-products', SaasFlashDealProductController::class);
    Route::resource('coupons', SaasCouponController::class);

    // Order Management
    Route::resource('orders', SaasOrderController::class);

    // Payment Methods
    Route::resource('payment-methods', SaasPaymentMethodController::class);
    Route::post('payment-methods/{paymentMethod}/set-default', [SaasPaymentMethodController::class, 'setDefault'])
        ->name('payment-methods.set-default');

    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [SaasTransactionController::class, 'index'])->name('index');
        Route::get('/admin-transactions', [SaasTransactionController::class, 'adminTransactions'])->name('admin-transactions');
        Route::get('/export', [SaasTransactionController::class, 'export'])->name('export');
        Route::get('/{transaction}', [SaasTransactionController::class, 'show'])->name('show');
    });

    // User Management
    Route::resource('users', SaasUserController::class);
    Route::resource('customers', SaasCustomerController::class);
    Route::resource('sellers', SaasSellerController::class);
    Route::patch('sellers/{seller}/toggle-approval', [SaasSellerController::class, 'toggleApproval'])->name('sellers.toggle-approval');

    // CMS Management
    Route::resource('pages', SaasPageController::class);
    Route::patch('pages/{page}/toggle-status', [SaasPageController::class, 'toggleStatus'])->name('pages.toggle-status');

    // Blog Management
    Route::resource('blog-categories', SaasBlogCategoryController::class);
    Route::patch('blog-categories/{blog_category}/toggle-status', [SaasBlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
    Route::resource('blog-posts', SaasBlogPostController::class);
    Route::patch('blog-posts/{blog_post}/toggle-status', [SaasBlogPostController::class, 'toggleStatus'])->name('blog-posts.toggle-status');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [SaasReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/products', [SaasReportController::class, 'productReport'])->name('reports.products');
        Route::get('/customers', [SaasReportController::class, 'customerReport'])->name('reports.customers');
        Route::get('/sellers', [SaasReportController::class, 'sellerReport'])->name('reports.sellers');
        Route::get('/sellers/{seller}', [SaasReportController::class, 'individualSellerReport'])->name('reports.individual-seller');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SaasSettingsController::class, 'index'])->name('settings.index');
        Route::match(['get', 'post'], '/general', [SaasSettingsController::class, 'general'])->name('settings.general');
        Route::match(['get', 'post'], '/email', [SaasSettingsController::class, 'email'])->name('settings.email');
        Route::match(['get', 'post'], '/payment', [SaasSettingsController::class, 'payment'])->name('settings.payment');
        Route::match(['get', 'post'], '/shipping', [SaasSettingsController::class, 'shipping'])->name('settings.shipping');
        Route::match(['get', 'post'], '/tax', [SaasSettingsController::class, 'tax'])->name('settings.tax');
        Route::post('/test-email', [SaasSettingsController::class, 'testEmail'])->name('settings.test-email');
        Route::post('/clear-cache', [SaasSettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::get('/export', [SaasSettingsController::class, 'exportSettings'])->name('settings.export');
    });

    // TinyMCE Image Upload
    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('tinymce.upload');
    Route::get('tinymce/test', [TinyMCEController::class, 'test'])->name('tinymce.test');

    // Test page for TinyMCE
    Route::get('tinymce/test-page', function() {
        return view('saas_admin.tinymce_test');
    })->name('tinymce.test-page');

    // Refund Management
    Route::prefix('refunds')->name('refunds.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'index'])->name('index');
        Route::get('/{refund}', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'show'])->name('show');
        Route::get('/{refund}/edit', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'edit'])->name('edit');
        Route::post('/{refund}/approve', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'approve'])->name('approve');
        Route::post('/{refund}/reject', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'reject'])->name('reject');
        Route::get('/{refund}/download-attachment', [\App\Http\Controllers\SaasAdmin\SaasRefundController::class, 'downloadAttachment'])->name('download-attachment');
    });

    // Withdrawal Management
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SaasAdmin\SaasWithdrawalController::class, 'index'])->name('index');
        Route::get('/{withdrawal}', [\App\Http\Controllers\SaasAdmin\SaasWithdrawalController::class, 'show'])->name('show');
        Route::post('/{withdrawal}/approve', [\App\Http\Controllers\SaasAdmin\SaasWithdrawalController::class, 'approve'])->name('approve');
        Route::post('/{withdrawal}/reject', [\App\Http\Controllers\SaasAdmin\SaasWithdrawalController::class, 'reject'])->name('reject');
        Route::get('/{withdrawal}/download-attachment', [\App\Http\Controllers\SaasAdmin\SaasWithdrawalController::class, 'downloadAttachment'])->name('download-attachment');
    });
});
