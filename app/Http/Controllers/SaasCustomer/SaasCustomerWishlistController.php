<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasWishlist;
use App\Models\SaasProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasCustomerWishlistController extends Controller
{
    public function saasIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist.');
        }

        $wishlistItems = SaasWishlist::where('customer_id', Auth::id())
            ->with(['product.images', 'product.brand', 'product.reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('saas_customer.saas_wishlist', compact('wishlistItems'));
    }

    public function saasAdd(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:saas_products,id'
        ]);

        $product = SaasProduct::where('id', $request->product_id)
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->first();

        if (!$product) {
            return response()->json([
                'error' => 'Product not found or is not available'
            ], 404);
        }

        // Check if product is already in wishlist
        $existingWishlistItem = SaasWishlist::where('customer_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingWishlistItem) {
            return response()->json([
                'error' => 'Product is already in your wishlist'
            ], 400);
        }

        SaasWishlist::create([
            'customer_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        $wishlistCount = SaasWishlist::where('customer_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully',
            'wishlist_count' => $wishlistCount
        ]);
    }

    public function saasRemove($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $wishlistItem = SaasWishlist::where('id', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $wishlistItem->delete();

        $wishlistCount = SaasWishlist::where('customer_id', Auth::id())->count();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist',
                'wishlist_count' => $wishlistCount
            ]);
        }

        return redirect()->route('customer.wishlist')->with('success', 'Item removed from wishlist');
    }

    public function saasToggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:saas_products,id'
        ]);

        $existingWishlistItem = SaasWishlist::where('customer_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingWishlistItem) {
            // Remove from wishlist
            $existingWishlistItem->delete();
            $message = 'Product removed from wishlist';
            $inWishlist = false;
        } else {
            // Add to wishlist
            $product = SaasProduct::where('id', $request->product_id)
                ->where('is_active', true)
                ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
                ->first();

            if (!$product) {
                return response()->json(['error' => 'Product not found or is not available'], 404);
            }

            SaasWishlist::create([
                'customer_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            $message = 'Product added to wishlist';
            $inWishlist = true;
        }

        $wishlistCount = SaasWishlist::where('customer_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $inWishlist,
            'wishlist_count' => $wishlistCount
        ]);
    }

    public function saasGetWishlistCount()
    {
        if (!Auth::check()) {
            return response()->json(['wishlist_count' => 0]);
        }

        $wishlistCount = SaasWishlist::where('customer_id', Auth::id())->count();

        return response()->json(['wishlist_count' => $wishlistCount]);
    }

    public function saasGetCount()
    {
        return $this->saasGetWishlistCount();
    }

    public function saasClear()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        SaasWishlist::where('customer_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist cleared successfully',
            'wishlist_count' => 0
        ]);
    }

    public function saasBulkRemove(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:saas_products,id'
        ]);

        $removedCount = SaasWishlist::where('customer_id', Auth::id())
            ->whereIn('product_id', $request->product_ids)
            ->delete();

        $wishlistCount = SaasWishlist::where('customer_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => "Removed {$removedCount} items from wishlist",
            'removed_count' => $removedCount,
            'wishlist_count' => $wishlistCount
        ]);
    }
}
