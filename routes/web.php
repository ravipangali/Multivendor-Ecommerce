<?php

// use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;

Route::get('/clear-config', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return 'Config and cache cleared!';
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/check-env', function () {
    return app()->environment();
});

Route::get('/storage-link', function () {
    $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
    symlink($targetFolder, $linkFolder);
    echo 'Success';
});

// Test mail routes - REMOVE THESE IN PRODUCTION
Route::get('/test-product-mail', function () {
    try {
        // Create test data
        $seller = \App\Models\User::firstOrCreate(
            ['email' => 'test-seller@example.com'],
            [
                'name' => 'Test Seller',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'seller',
                'is_active' => true,
            ]
        );

        $category = \App\Models\SaasCategory::firstOrCreate(
            ['name' => 'Test Category'],
            ['status' => 'active']
        );

        $brand = \App\Models\SaasBrand::firstOrCreate(['name' => 'Test Brand']);
        $unit = \App\Models\SaasUnit::firstOrCreate(['name' => 'Piece']);

        $product = \App\Models\SaasProduct::create([
            'seller_id' => $seller->id,
            'name' => 'Test Product for Email - ' . now()->format('Y-m-d H:i:s'),
            'SKU' => 'TEST-EMAIL-' . time(),
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_id' => $unit->id,
            'price' => 99.99,
            'stock' => 10,
            'product_type' => 'Physical',
            'description' => 'This is a test product created for email testing.',
            'short_description' => 'Test product',
            'is_active' => true,
            'seller_publish_status' => \App\Models\SaasProduct::SELLER_PUBLISH_STATUS_REQUEST,
        ]);

        $product = $product->load(['seller', 'category', 'brand']);

        // Test product request email
        $adminEmail = config('app.admin_email', 'admin@example.com');
        \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\SaasProductRequestNotification($product));

        return response()->json([
            'success' => true,
            'message' => 'Product request email sent successfully!',
            'data' => [
                'product_name' => $product->name,
                'seller_name' => $product->seller->name,
                'admin_email' => $adminEmail,
                'mail_driver' => config('mail.default')
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.product.mail');

Route::get('/test-order-mail', function () {
    try {
        // Get a sample order with relationships
        $order = \App\Models\SaasOrder::with(['customer', 'seller', 'items.product'])->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No orders found in database. Create an order first.'
            ]);
        }

        // Test order status change emails
        $adminEmail = config('app.admin_email', 'admin@example.com');

        // Send to customer
        \Illuminate\Support\Facades\Mail::to($order->customer->email)->send(
            new \App\Mail\SaasOrderStatusChanged($order, 'pending', 'customer')
        );

        // Send to seller
        if ($order->seller && $order->seller->email) {
            \Illuminate\Support\Facades\Mail::to($order->seller->email)->send(
                new \App\Mail\SaasOrderStatusChanged($order, 'pending', 'seller')
            );
        }

        // Send to admin
        \Illuminate\Support\Facades\Mail::to($adminEmail)->send(
            new \App\Mail\SaasOrderStatusChanged($order, 'pending', 'admin')
        );

        return response()->json([
            'success' => true,
            'message' => 'Order status emails sent successfully!',
            'data' => [
                'order_number' => $order->order_number,
                'customer_email' => $order->customer->email,
                'seller_email' => $order->seller->email ?? 'N/A',
                'admin_email' => $adminEmail,
                'mail_driver' => config('mail.default')
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.order.mail');

Route::get('/test-approval-mails', function () {
    try {
        // Test Product Approval Email
        $seller = \App\Models\User::where('role', 'seller')->first();
        if (!$seller) {
            return response()->json([
                'success' => false,
                'message' => 'No seller found. Please create a seller first.'
            ]);
        }

        $product = \App\Models\SaasProduct::where('seller_id', $seller->id)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'No products found for seller. Please create a product first.'
            ]);
        }

        // Test product approval email
        \Illuminate\Support\Facades\Mail::to($seller->email)->send(
            new \App\Mail\SaasProductApprovalNotification($product, 'approved')
        );

        // Test product denial email
        \Illuminate\Support\Facades\Mail::to($seller->email)->send(
            new \App\Mail\SaasProductApprovalNotification($product, 'denied')
        );

        // Test seller approval email
        \Illuminate\Support\Facades\Mail::to($seller->email)->send(
            new \App\Mail\SaasSellerApprovalNotification($seller, 'approved')
        );

        // Test seller denial email
        \Illuminate\Support\Facades\Mail::to($seller->email)->send(
            new \App\Mail\SaasSellerApprovalNotification($seller, 'denied')
        );

        return response()->json([
            'success' => true,
            'message' => 'All approval test emails sent successfully!',
            'data' => [
                'seller_name' => $seller->name,
                'seller_email' => $seller->email,
                'product_name' => $product->name,
                'emails_sent' => [
                    'product_approved',
                    'product_denied',
                    'seller_approved',
                    'seller_denied'
                ]
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.approval.mails');

require __DIR__.'/auth.php';

require __DIR__.'/saas_admin.php';
require __DIR__.'/saas_seller.php';
require __DIR__.'/saas_customer.php';
