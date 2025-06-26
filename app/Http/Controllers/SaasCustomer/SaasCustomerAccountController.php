<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasCart;
use App\Models\SaasCustomerProfile;
use App\Models\SaasOrder;
use App\Models\SaasOrderItem;
use App\Models\SaasProduct;
use App\Models\SaasProductReview;
use App\Models\SaasWishlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class SaasCustomerAccountController extends Controller
{
    public function saasDashboard()
    {
        $customer = Auth::user();

        // Get order statistics
        $totalOrders = SaasOrder::where('customer_id', $customer->id)->count();
        $pendingOrders = SaasOrder::where('customer_id', $customer->id)->where('order_status', 'pending')->count();
        $completedOrders = SaasOrder::where('customer_id', $customer->id)->where('order_status', 'delivered')->count();
        $totalSpent = SaasOrder::where('customer_id', $customer->id)->where('order_status', 'delivered')->sum('total');

        // Get wishlist count
        $wishlistCount = SaasWishlist::where('customer_id', $customer->id)->count();

        // Get recent orders
        $recentOrders = SaasOrder::where('customer_id', $customer->id)
            ->with(['items.product.images'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent reviews
        $recentReviews = SaasProductReview::where('customer_id', $customer->id)
            ->with(['product.images'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('saas_customer.saas_dashboard', compact(
            'customer',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'wishlistCount',
            'recentOrders',
            'recentReviews'
        ));
    }

    public function saasProfile()
    {
        $customer = Auth::user();
        $profile = $customer->customerProfile ?? new SaasCustomerProfile();

        return view('saas_customer.saas_profile', compact('customer', 'profile'));
    }

    public function saasUpdateProfile(Request $request)
    {
        $customer = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'shipping_address' => 'nullable|string|max:1000',
            'billing_address' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'remove_photo' => 'nullable|boolean'
        ], [
            'name.required' => 'Full name is required.',
            'name.max' => 'Full name cannot be longer than 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'phone.max' => 'Phone number cannot be longer than 20 characters.',
            'shipping_address.max' => 'Shipping address cannot be longer than 1000 characters.',
            'billing_address.max' => 'Billing address cannot be longer than 1000 characters.',
            'profile_photo.image' => 'Profile photo must be an image.',
            'profile_photo.mimes' => 'Profile photo must be a JPEG, PNG, JPG, GIF, or WebP file.',
        ]);

        try {
            // Update user basic info
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Handle profile photo upload/removal
            if ($request->has('remove_photo') && $request->remove_photo) {
                // Remove existing photo
                if ($customer->profile_photo) {
                    Storage::disk('public')->delete($customer->profile_photo);
                    $customer->update(['profile_photo' => null]);
                }
            } elseif ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($customer->profile_photo) {
                    Storage::disk('public')->delete($customer->profile_photo);
                }

                $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
                $customer->update(['profile_photo' => $profilePhotoPath]);
            }

            // Update or create customer profile (only if shipping or billing address provided)
            if ($request->shipping_address || $request->billing_address) {
                $profileData = array_filter([
                    'shipping_address' => $request->shipping_address,
                    'billing_address' => $request->billing_address,
                ], function($value) {
                    return $value !== null && $value !== '';
                });

                if (!empty($profileData)) {
                    $customer->customerProfile()->updateOrCreate(
                        ['user_id' => $customer->id],
                        $profileData
                    );
                }
            }

            return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }

    public function saasUpdatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $customer = Auth::user();

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $customer->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('customer.profile')->with('success', 'Password changed successfully');
    }

    public function saasChangePassword(Request $request)
    {
        return $this->saasUpdatePassword($request);
    }

    public function saasUpdateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ], [
            'profile_photo.required' => 'Please select a profile photo.',
            'profile_photo.image' => 'Profile photo must be an image.',
            'profile_photo.mimes' => 'Profile photo must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'profile_photo.max' => 'Profile photo must not be larger than 2MB.'
        ]);

        try {
            $customer = Auth::user();

            // Delete old photo if exists
            if ($customer->profile_photo) {
                Storage::disk('public')->delete($customer->profile_photo);
            }

            // Store new photo
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
            $customer->update(['profile_photo' => $profilePhotoPath]);

            return redirect()->route('customer.profile')->with('success', 'Profile photo updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while uploading your photo. Please try again.');
        }
    }

    public function saasDeleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $customer = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['password' => 'Password is incorrect']);
        }

        try {
            // Delete profile photo if exists
            if ($customer->profile_photo) {
                Storage::disk('public')->delete($customer->profile_photo);
            }

            // Cancel all pending orders
            SaasOrder::where('customer_id', $customer->id)
                ->whereIn('order_status', ['pending', 'confirmed'])
                ->update([
                    'order_status' => 'cancelled',
                    'cancellation_reason' => 'Account deleted by customer',
                    'cancelled_at' => now()
                ]);

            // Delete customer profile
            if ($customer->customerProfile) {
                $customer->customerProfile->delete();
            }

            // Delete reviews
            SaasProductReview::where('customer_id', $customer->id)->delete();

            // Clear cart and wishlist
            SaasCart::where('user_id', $customer->id)->delete();
            SaasWishlist::where('customer_id', $customer->id)->delete();

            // Finally delete the user account
            $customer->delete();

            // Logout and redirect
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('customer.home')->with('success', 'Your account has been deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while deleting your account. Please try again.');
        }
    }

    public function saasOrders(Request $request)
    {
        $query = SaasOrder::where('customer_id', Auth::id())
            ->with(['items.product.images']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('saas_customer.saas_orders', compact('orders'));
    }

    public function saasOrderDetail($id)
    {
        $order = SaasOrder::where('id', $id)
            ->where('customer_id', Auth::id())
            ->with([
                'items.product.images',
                'items.productVariation',
                'customer.customerProfile'
            ])
            ->firstOrFail();

        return view('saas_customer.saas_order_detail', compact('order'));
    }

        public function saasCancelOrder(Request $request, $id)
    {
        try {
            $order = SaasOrder::where('id', $id)
                ->where('customer_id', Auth::id())
                ->with(['customer', 'seller', 'items.product', 'items.productVariation'])
                ->firstOrFail();

            if (!in_array($order->order_status, ['pending', 'confirmed'])) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'This order cannot be cancelled']);
                }
                return back()->with('error', 'This order cannot be cancelled');
            }

            // For AJAX requests, provide a default cancellation reason
            $cancellationReason = $request->input('cancellation_reason', 'Cancelled by customer');

            if (!$request->expectsJson()) {
                $request->validate([
                    'cancellation_reason' => 'required|string|max:500'
                ]);
                $cancellationReason = $request->cancellation_reason;
            }

            // Store previous status for email notification
            $previousStatus = $order->order_status;

            // Update order status with proper database transaction
            DB::transaction(function () use ($order, $cancellationReason) {
                $order->update([
                    'order_status' => 'cancelled',
                    'cancellation_reason' => $cancellationReason,
                    'cancelled_at' => now(),
                ]);

                // Restore product stock
                foreach ($order->items as $item) {
                    if ($item->productVariation) {
                        $item->productVariation->increment('stock', $item->quantity);
                    } else {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            });

            // Send email notifications
            try {
                // Send email to customer
                Mail::to($order->customer->email)->send(
                    new \App\Mail\SaasOrderStatusChanged($order, $previousStatus, 'customer')
                );

                // Send email to seller
                if ($order->seller && $order->seller->email) {
                    Mail::to($order->seller->email)->send(
                        new \App\Mail\SaasOrderStatusChanged($order, $previousStatus, 'seller')
                    );
                }

                // Send email to admin
                $adminEmail = config('app.admin_email', 'admin@example.com');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(
                        new \App\Mail\SaasOrderStatusChanged($order, $previousStatus, 'admin')
                    );
                }

                Log::info('Order cancellation emails sent successfully', [
                    'order_id' => $order->id,
                    'customer_id' => Auth::id(),
                    'customer_email' => $order->customer->email,
                    'seller_email' => $order->seller->email ?? 'N/A',
                    'admin_email' => $adminEmail
                ]);

            } catch (\Exception $e) {
                // Log email error but don't fail the cancellation
                Log::error('Failed to send order cancellation emails', [
                    'order_id' => $order->id,
                    'customer_id' => Auth::id(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully',
                    'order_status' => 'cancelled'
                ]);
            }

            return redirect()->route('customer.orders')->with('success', 'Order cancelled successfully');

        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to cancel order. Please try again.']);
            }

            return back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    public function saasAddReview(Request $request, SaasProduct $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if customer has purchased this product
        $hasPurchased = SaasOrderItem::whereHas('order', function($query) {
            $query->where('customer_id', Auth::id())
                  ->where('order_status', 'delivered');
        })->where('product_id', $product->id)->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased');
        }

        // Check if customer already reviewed this product
        $existingReview = SaasProductReview::where('customer_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product');
        }

        // Handle review images
        $reviewImages = [];
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $image) {
                $reviewImages[] = $image->store('review_images', 'public');
            }
        }

        SaasProductReview::create([
            'customer_id' => Auth::id(),
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'images' => $reviewImages,
            'is_approved' => true, // Auto-approve for now
        ]);

        return back()->with('success', 'Review added successfully');
    }

    public function saasUpdateReview(Request $request, SaasProductReview $review)
    {
        if ($review->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Review updated successfully');
    }

    public function saasDeleteReview(SaasProductReview $review)
    {
        if ($review->customer_id !== Auth::id()) {
            abort(403);
        }

        // Delete review images
        if ($review->images) {
            $images = json_decode($review->images, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully');
    }

    public function saasOrderReview($id)
    {
        $order = SaasOrder::where('id', $id)
            ->where('customer_id', Auth::id())
            ->where('order_status', 'delivered')
            ->with([
                'items.product.images',
                'items.productVariation.attribute',
                'items.productVariation.attributeValue',
                'items.product.reviews' => function($query) {
                    $query->where('customer_id', Auth::id());
                }
            ])
            ->firstOrFail();

        return view('saas_customer.saas_order_review', compact('order'));
    }

    public function saasSubmitOrderReview(Request $request, $id)
    {
        $order = SaasOrder::where('id', $id)
            ->where('customer_id', Auth::id())
            ->where('order_status', 'delivered')
            ->with(['items.product'])
            ->firstOrFail();

        $request->validate([
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:saas_products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.review' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $order) {
            foreach ($request->reviews as $reviewData) {
                // Check if customer already reviewed this product
                $existingReview = SaasProductReview::where('customer_id', Auth::id())
                    ->where('product_id', $reviewData['product_id'])
                    ->first();

                if (!$existingReview) {
                    // Get the product to find its seller_id
                    $product = SaasProduct::find($reviewData['product_id']);

                    SaasProductReview::create([
                        'customer_id' => Auth::id(),
                        'product_id' => $reviewData['product_id'],
                        'seller_id' => $product->seller_id,
                        'rating' => $reviewData['rating'],
                        'review' => $reviewData['review'],
                    ]);
                }
            }
        });

        return redirect()->route('customer.orders')
            ->with('success', 'Thank you for your reviews! Your feedback helps other customers.');
    }
}
