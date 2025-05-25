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

Route::middleware(['auth', 'saasrolemanager:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [SaasAdminDashboardController::class, 'index'])->name('dashboard');

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
    Route::resource('product-reviews', SaasProductReviewController::class);

    // Marketing
    Route::resource('banners', SaasBannerController::class);
    Route::resource('flash-deals', SaasFlashDealController::class);
    Route::resource('flash-deal-products', SaasFlashDealProductController::class);
    Route::resource('coupons', SaasCouponController::class);

    // Order Management
    Route::resource('orders', SaasOrderController::class);

    // Payment Methods
    Route::resource('payment-methods', SaasPaymentMethodController::class);
    Route::post('payment-methods/{paymentMethod}/set-default', [SaasPaymentMethodController::class, 'setDefault'])
        ->name('payment-methods.set-default');

    // User Management
    Route::resource('users', SaasUserController::class);
    Route::resource('customers', SaasCustomerController::class);
    Route::resource('sellers', SaasSellerController::class);
    Route::patch('sellers/{seller}/toggle-approval', [SaasSellerController::class, 'toggleApproval'])->name('sellers.toggle-approval');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [SaasReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/products', [SaasReportController::class, 'productReport'])->name('reports.products');
        Route::get('/customers', [SaasReportController::class, 'customerReport'])->name('reports.customers');
        Route::get('/sellers', [SaasReportController::class, 'sellerReport'])->name('reports.sellers');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::match(['get', 'post'], '/general', [SaasSettingsController::class, 'general'])->name('settings.general');
        Route::match(['get', 'post'], '/email', [SaasSettingsController::class, 'email'])->name('settings.email');
        Route::match(['get', 'post'], '/payment', [SaasSettingsController::class, 'payment'])->name('settings.payment');
        Route::match(['get', 'post'], '/shipping', [SaasSettingsController::class, 'shipping'])->name('settings.shipping');
        Route::post('/test-email', [SaasSettingsController::class, 'testEmail'])->name('settings.test-email');
    });
});
