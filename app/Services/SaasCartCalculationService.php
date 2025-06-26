<?php

namespace App\Services;

use App\Models\SaasCart;
use App\Models\SaasCoupon;
use App\Models\SaasSetting;
use Illuminate\Support\Collection;

class SaasCartCalculationService
{
    protected $shippingService;
    protected $taxService;

    public function __construct(
        SaasShippingService $shippingService,
        SaasTaxService $taxService
    ) {
        $this->shippingService = $shippingService;
        $this->taxService = $taxService;
    }

    /**
     * Calculate cart totals for given cart items
     */
    public function calculateCartTotals(Collection $cartItems, $appliedCoupon = null, $shippingAddress = null)
    {
        $subtotal = $this->calculateSubtotal($cartItems);
        $shippingCost = $this->shippingService->calculateShippingCost($cartItems, $subtotal, $shippingAddress);
        $taxAmount = $this->taxService->calculateTax($cartItems, $subtotal, $shippingAddress, $shippingCost);

        $discountAmount = 0;
        $couponCode = null;

        if ($appliedCoupon) {
            $discountAmount = $this->calculateCouponDiscount($appliedCoupon, $subtotal);
            $couponCode = $appliedCoupon->code;
        }

        $grandTotal = $subtotal + $shippingCost + $taxAmount - $discountAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'shipping_cost' => round($shippingCost, 2),
            'tax_amount' => round($taxAmount, 2),
            'discount_amount' => round($discountAmount, 2),
            'grand_total' => round(max(0, $grandTotal), 2), // Ensure no negative totals
            'coupon_code' => $couponCode,
            'item_count' => $cartItems->sum(function($item) {
                return is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
            }),
            'formatted' => [
                'subtotal' => number_format($subtotal, 2),
                'shipping_cost' => number_format($shippingCost, 2),
                'tax_amount' => number_format($taxAmount, 2),
                'discount_amount' => number_format($discountAmount, 2),
                'grand_total' => number_format(max(0, $grandTotal), 2),
            ]
        ];
    }

    /**
     * Calculate subtotal from cart items
     */
    public function calculateSubtotal(Collection $cartItems)
    {
        return $cartItems->sum(function($item) {
            // Handle array items (for testing)
            if (is_array($item)) {
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;
                return $price * $quantity;
            }

            // Handle actual cart item objects
            if (is_object($item)) {
                $price = 0;
                $quantity = $item->quantity ?? 1;

                if ($item->productVariation && method_exists($item->productVariation, 'getFinalPriceAttribute')) {
                    $price = $item->productVariation->final_price;
                } elseif ($item->product && method_exists($item->product, 'getFinalPriceAttribute')) {
                    $price = $item->product->final_price;
                } elseif (isset($item->price)) {
                    $price = $item->price;
                }

                return $price * $quantity;
            }

            return 0;
        });
    }

    /**
     * Calculate item total for a specific cart item
     */
    public function calculateItemTotal($cartItem)
    {
        $price = $cartItem->productVariation ? $cartItem->productVariation->final_price : $cartItem->product->final_price;
        return $price * $cartItem->quantity;
    }

    /**
     * Apply and validate coupon
     */
    public function applyCoupon($couponCode, $subtotal)
    {
        $coupon = SaasCoupon::where('code', $couponCode)->first();

        if (!$coupon || !$coupon->isValid()) {
            return ['success' => false, 'message' => 'Invalid or expired coupon code'];
        }

        // Check minimum amount requirement
        if (isset($coupon->minimum_amount) && $subtotal < $coupon->minimum_amount) {
            return [
                'success' => false,
                'message' => "Coupon requires minimum order amount of Rs. " . number_format($coupon->minimum_amount, 2)
            ];
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'Coupon usage limit exceeded'];
        }

        $discountAmount = $coupon->calculateDiscount($subtotal);

        return [
            'success' => true,
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'message' => 'Coupon applied successfully'
        ];
    }

    /**
     * Calculate coupon discount amount
     */
    protected function calculateCouponDiscount($coupon, $subtotal)
    {
        return $coupon->calculateDiscount($subtotal);
    }

    /**
     * Get cart totals for a specific user/session
     */
    public function getCartTotals($userId = null, $sessionId = null)
    {
        $cartItems = $this->getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return [
                'subtotal' => 0,
                'shipping_fee' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
                'coupon_code' => null,
                'coupon_discount_amount' => 0,
                'coupon_discount_type' => null,
            ];
        }

        // Get applied coupon from session
        $appliedCoupon = null;
        if (session('applied_coupon_code')) {
            $appliedCoupon = SaasCoupon::where('code', session('applied_coupon_code'))->first();
            if (!$appliedCoupon || !$appliedCoupon->isValid()) {
                session()->forget('applied_coupon_code');
                $appliedCoupon = null;
            }
        }

        // Calculate using the main calculation method
        $totals = $this->calculateCartTotals($cartItems, $appliedCoupon);

        // Map to the expected format for backward compatibility
        return [
            'subtotal' => $totals['subtotal'],
            'shipping_fee' => $totals['shipping_cost'],
            'tax' => $totals['tax_amount'],
            'discount' => $totals['discount_amount'],
            'total' => $totals['grand_total'],
            'coupon_code' => $totals['coupon_code'],
            'coupon_discount_amount' => $totals['discount_amount'],
            'coupon_discount_type' => $appliedCoupon ? $appliedCoupon->discount_type : null,
        ];
    }

    /**
     * Get cart items for a specific user/session
     */
    public function getCartItems($userId = null, $sessionId = null)
    {
        $query = SaasCart::with([
            'product.images',
            'product.brand',
            'productVariation.attribute',
            'productVariation.attributeValue',
            'product.variations' // Add this to ensure variations are loaded
        ]);

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Clear cart items for a user/session
     */
    public function clearCart($userId = null, $sessionId = null)
    {
        $query = SaasCart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->delete();
    }
}
