<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasCart;
use App\Models\SaasCoupon;
use App\Models\SaasProduct;
use App\Models\SaasProductVariation;
use App\Services\SaasCartCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasCustomerCartController extends Controller
{
    protected $cartCalculationService;

    public function __construct(SaasCartCalculationService $cartCalculationService)
    {
        $this->cartCalculationService = $cartCalculationService;
    }

    public function saasIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your cart.');
        }

        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product.images', 'product.brand', 'productVariation'])
            ->get();

        // Check for applied coupon in session
        $appliedCoupon = null;
        if (session('applied_coupon_code')) {
            $appliedCoupon = SaasCoupon::where('code', session('applied_coupon_code'))->first();

            // If coupon doesn't exist or is invalid, remove from session
            if (!$appliedCoupon || !$appliedCoupon->isValid()) {
                session()->forget('applied_coupon_code');
                $appliedCoupon = null;
            }
        }

        // Calculate totals using the service
        $totals = $this->cartCalculationService->calculateCartTotals($cartItems, $appliedCoupon);

        return view('saas_customer.saas_cart', compact('cartItems') + $totals);
    }

    public function saasUpdate(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'cart_id' => 'required|exists:saas_carts,id',
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        $cartItem = SaasCart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check stock availability using the model method
        $availableStock = $cartItem->getAvailableStock();

        if ($request->quantity > $availableStock) {
            return response()->json([
                'error' => 'Requested quantity exceeds available stock. Available: ' . $availableStock
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        // Recalculate totals using the service
        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product', 'productVariation'])
            ->get();

        $totals = $this->cartCalculationService->calculateCartTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_total' => $totals['formatted']['subtotal'],
            'shipping_cost' => $totals['formatted']['shipping_cost'],
            'tax' => $totals['formatted']['tax_amount'],
            'grand_total' => $totals['formatted']['grand_total'],
            'cart_count' => $totals['item_count']
        ]);
    }

    public function saasRemove($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $cartItem = SaasCart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        // Recalculate totals after removal using the service
        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product', 'productVariation'])
            ->get();

        $totals = $this->cartCalculationService->calculateCartTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart_count' => $totals['item_count'],
            'cart_total' => $totals['formatted']['subtotal'],
            'shipping_cost' => $totals['formatted']['shipping_cost'],
            'tax' => $totals['formatted']['tax_amount'],
            'grand_total' => $totals['formatted']['grand_total']
        ]);
    }

    public function saasClear()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        SaasCart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cart_count' => 0
        ]);
    }

    public function saasApplyCoupon(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product', 'productVariation'])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty'], 400);
        }

        $subtotal = $this->cartCalculationService->calculateSubtotal($cartItems);
        $couponResult = $this->cartCalculationService->applyCoupon($request->coupon_code, $subtotal);

        if (!$couponResult['success']) {
            return response()->json(['error' => $couponResult['message']], 400);
        }

        // Calculate totals with coupon applied
        $totals = $this->cartCalculationService->calculateCartTotals($cartItems, $couponResult['coupon']);

        // Store only coupon code in session for persistence
        session(['applied_coupon_code' => $couponResult['coupon']->code]);

        return response()->json([
            'success' => true,
            'message' => $couponResult['message'],
            'discount_amount' => $totals['formatted']['discount_amount'],
            'cart_total' => $totals['formatted']['subtotal'],
            'shipping_cost' => $totals['formatted']['shipping_cost'],
            'tax' => $totals['formatted']['tax_amount'],
            'grand_total' => $totals['formatted']['grand_total']
        ]);
    }

    public function saasRemoveCoupon()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        // Remove coupon from session
        session()->forget('applied_coupon_code');

        // Recalculate totals without coupon
        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product', 'productVariation'])
            ->get();

        $totals = $this->cartCalculationService->calculateCartTotals($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully',
            'cart_total' => $totals['formatted']['subtotal'],
            'shipping_cost' => $totals['formatted']['shipping_cost'],
            'tax' => $totals['formatted']['tax_amount'],
            'grand_total' => $totals['formatted']['grand_total']
        ]);
    }

    public function saasGetCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['cart_count' => 0]);
        }

        $cartCount = SaasCart::where('user_id', Auth::id())->sum('quantity');

        return response()->json(['cart_count' => $cartCount]);
    }

    public function saasAddToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login first'], 401);
        }

        $request->validate([
            'product_id' => 'required_without:product_ids|exists:saas_products,id',
            'product_ids' => 'required_without:product_id|array',
            'product_ids.*' => 'exists:saas_products,id',
            'quantity' => 'required|integer|min:1',
            'variation_id' => 'nullable|exists:saas_product_variations,id',
            'variation_ids' => 'nullable|array',
            'variation_ids.*' => 'exists:saas_product_variations,id'
        ]);

        // Handle multiple products (from wishlist "Add All")
        if ($request->has('product_ids')) {
            $addedCount = 0;
            $skippedCount = 0;

            foreach ($request->product_ids as $productId) {
                $product = SaasProduct::where('id', $productId)
                    ->where('is_active', true)
                    ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
                    ->first();
                if (!$product || $product->stock <= 0) {
                    $skippedCount++;
                    continue;
                }

                // Check if item already exists in cart
                $existingCartItem = SaasCart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->whereNull('variations_data')
                    ->first();

                if ($existingCartItem) {
                    $newQuantity = $existingCartItem->quantity + $request->quantity;
                    if ($newQuantity <= $product->stock) {
                        $existingCartItem->update(['quantity' => $newQuantity]);
                        $addedCount++;
                    } else {
                        $skippedCount++;
                    }
                } else {
                    if ($request->quantity <= $product->stock) {
                        SaasCart::create([
                            'user_id' => Auth::id(),
                            'product_id' => $productId,
                            'variation_id' => null,
                            'variations_data' => null,
                            'variation_details' => null,
                            'quantity' => $request->quantity,
                            'price' => $product->final_price
                        ]);
                        $addedCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            }

            $message = "Added $addedCount items to cart";
            if ($skippedCount > 0) {
                $message .= ", $skippedCount items skipped (product is out of stock or insufficient quantity)";
            }

            $cartCount = SaasCart::where('user_id', Auth::id())->sum('quantity');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'added_count' => $addedCount,
                    'skipped_count' => $skippedCount,
                    'cart_count' => $cartCount
                ]);
            }

            return redirect()->back()->with('success', $message);
        }

        // Handle single product
        $product = SaasProduct::where('id', $request->product_id)
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->first();

        if (!$product) {
            $message = 'Product not found or not available for purchase.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 404);
            }
            return redirect()->back()->with('error', $message);
        }

        // Determine variations to add
        $variationIds = [];
        if ($request->has('variation_ids') && is_array($request->variation_ids)) {
            $variationIds = array_filter($request->variation_ids);
        } elseif ($request->variation_id) {
            $variationIds = [$request->variation_id];
        }

        // Check if product needs variations but none selected
        if ((!$product->price || $product->price <= 0) && (!$product->discount || $product->discount <= 0) && empty($variationIds)) {
            $message = 'Please select product variations first';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        // Determine price and stock
        $finalPrice = $product->final_price;
        $availableStock = $product->stock;
        $variationDetails = null;

        if (!empty($variationIds)) {
            // Validate variations belong to this product
            $variations = SaasProductVariation::whereIn('id', $variationIds)
                ->where('product_id', $request->product_id)
                ->get();

            if ($variations->count() != count($variationIds)) {
                $message = 'One or more selected variations are invalid.';
                if ($request->expectsJson()) {
                    return response()->json(['error' => $message], 400);
                }
                return redirect()->back()->with('error', $message);
            }

            // Use highest price among selected variations
            $finalPrice = $variations->max('final_price') ?? $product->final_price;

            // Use minimum stock among selected variations
            $availableStock = $variations->min('stock') ?? 0;

            // Build variation details string
            $detailsArray = [];
            foreach ($variations as $variation) {
                $detailsArray[] = $variation->attribute->name . ': ' . $variation->attributeValue->value;
            }
            $variationDetails = implode(', ', $detailsArray);
        }

        // Prepare variation data for comparison (sorted for consistency)
        $variationDataForComparison = empty($variationIds) ? null : $variationIds;
        if ($variationDataForComparison) {
            sort($variationDataForComparison);
        }

        // Check if there's existing cart item with same product and variations
        $existingCartItem = SaasCart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->get()
            ->first(function ($item) use ($variationDataForComparison) {
                $existingVariationData = $item->variations_data;
                if ($existingVariationData) {
                    sort($existingVariationData);
                }
                return $existingVariationData == $variationDataForComparison;
            });

        // Calculate required quantity
        $existingQuantity = $existingCartItem ? $existingCartItem->quantity : 0;
        $totalRequiredQuantity = $existingQuantity + $request->quantity;

        // Check stock availability
        if ($totalRequiredQuantity > $availableStock) {
            // Special message for completely out of stock products
            if ($availableStock <= 0) {
                $message = "Product is out of stock";
            } else {
                $message = $existingQuantity > 0
                    ? "Cannot add {$request->quantity} more items. You already have {$existingQuantity} in cart. Available stock: {$availableStock}"
                    : "Requested quantity exceeds available stock. Available: {$availableStock}";
            }

            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        // Update or create cart item
        if ($existingCartItem) {
            $newQuantity = $existingQuantity + $request->quantity;
            $existingCartItem->update([
                'quantity' => $newQuantity,
                'price' => $finalPrice // Update to current price
            ]);
            $action = 'updated';
        } else {
            SaasCart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variation_id' => empty($variationIds) ? null : $variationIds[0], // Keep for backward compatibility
                'variations_data' => empty($variationIds) ? null : $variationIds,
                'variation_details' => $variationDetails,
                'quantity' => $request->quantity,
                'price' => $finalPrice
            ]);
            $action = 'added';
        }

        $cartCount = SaasCart::where('user_id', Auth::id())->sum('quantity');

        $successMessage = $action === 'updated'
            ? 'Cart updated successfully'
            : 'Product added to cart successfully';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', $successMessage . '!');
    }
}
