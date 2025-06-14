<?php

namespace App\Services;

use App\Models\SaasCoupon;
use App\Models\SaasOrder;
use Carbon\Carbon;

class SaasCouponService
{
    /**
     * Get coupon details for an order
     */
    public function getCouponDetailsForOrder(SaasOrder $order)
    {
        if (!$order->coupon_code) {
            return null;
        }

        $coupon = SaasCoupon::where('code', $order->coupon_code)->first();

        if (!$coupon) {
            // Return basic information from order if coupon no longer exists
            return [
                'code' => $order->coupon_code,
                'discount_type' => $order->coupon_discount_type,
                'discount_amount' => $order->coupon_discount_amount,
                'savings' => $order->coupon_discount_amount,
                'exists' => false,
                'formatted_discount' => $this->formatDiscount($order->coupon_discount_amount, $order->coupon_discount_type),
                'formatted_savings' => 'Rs. ' . number_format($order->coupon_discount_amount, 2),
            ];
        }

        return [
            'code' => $coupon->code,
            'description' => $coupon->description,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'discount_amount' => $order->coupon_discount_amount,
            'savings' => $order->coupon_discount_amount,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
            'usage_limit' => $coupon->usage_limit,
            'used_count' => $coupon->used_count,
            'seller_id' => $coupon->seller_id,
            'exists' => true,
            'status' => $this->getCouponStatus($coupon),
            'formatted_discount' => $this->formatDiscount($order->coupon_discount_amount, $coupon->discount_type),
            'formatted_original_discount' => $this->formatOriginalDiscount($coupon),
            'formatted_savings' => 'Rs. ' . number_format($order->coupon_discount_amount, 2),
            'seller' => $coupon->seller,
        ];
    }

    /**
     * Format discount amount for display
     */
    protected function formatDiscount($amount, $type)
    {
        if ($type === 'percentage') {
            return number_format($amount, 2) . '% discount';
        }

        return 'Rs. ' . number_format($amount, 2) . ' discount';
    }

    /**
     * Format original coupon discount for display
     */
    protected function formatOriginalDiscount(SaasCoupon $coupon)
    {
        if ($coupon->discount_type === 'percentage') {
            return $coupon->discount_value . '% off';
        }

        return 'Rs. ' . number_format($coupon->discount_value, 2) . ' off';
    }

    /**
     * Get coupon status
     */
    protected function getCouponStatus(SaasCoupon $coupon)
    {
        $now = Carbon::now();

        if ($coupon->end_date < $now) {
            return 'expired';
        }

        if ($coupon->start_date > $now) {
            return 'upcoming';
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return 'limit_reached';
        }

        return 'active';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'active':
                return 'bg-success';
            case 'expired':
                return 'bg-danger';
            case 'upcoming':
                return 'bg-warning';
            case 'limit_reached':
                return 'bg-secondary';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Get status display text
     */
    public function getStatusDisplayText($status)
    {
        switch ($status) {
            case 'active':
                return 'Active';
            case 'expired':
                return 'Expired';
            case 'upcoming':
                return 'Upcoming';
            case 'limit_reached':
                return 'Limit Reached';
            default:
                return 'Unknown';
        }
    }

    /**
     * Validate coupon before applying to order
     */
    public function validateCoupon($couponCode, $subtotal = 0, $sellerId = null)
    {
        $coupon = SaasCoupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Coupon code not found',
                'coupon' => null
            ];
        }

        if (!$coupon->isValid()) {
            return [
                'valid' => false,
                'message' => 'Coupon is expired or inactive',
                'coupon' => $coupon
            ];
        }

        // Check seller restriction
        if ($sellerId && $coupon->seller_id && $coupon->seller_id !== $sellerId) {
            return [
                'valid' => false,
                'message' => 'Coupon is not valid for this seller',
                'coupon' => $coupon
            ];
        }

        // Check minimum amount (if field exists)
        if (isset($coupon->minimum_amount) && $subtotal < $coupon->minimum_amount) {
            return [
                'valid' => false,
                'message' => "Minimum order amount of Rs. " . number_format($coupon->minimum_amount, 2) . " required",
                'coupon' => $coupon
            ];
        }

        return [
            'valid' => true,
            'message' => 'Coupon is valid',
            'coupon' => $coupon
        ];
    }

    /**
     * Calculate percentage from discount amount and subtotal
     */
    public function calculateDiscountPercentage($discountAmount, $subtotal)
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return round(($discountAmount / $subtotal) * 100, 2);
    }

    /**
     * Get coupon usage statistics
     */
    public function getCouponUsageStats(SaasCoupon $coupon)
    {
        $usagePercentage = 0;
        if ($coupon->usage_limit > 0) {
            $usagePercentage = round(($coupon->used_count / $coupon->usage_limit) * 100, 2);
        }

        return [
            'used_count' => $coupon->used_count,
            'usage_limit' => $coupon->usage_limit,
            'usage_percentage' => $usagePercentage,
            'remaining_uses' => $coupon->usage_limit ? max(0, $coupon->usage_limit - $coupon->used_count) : null,
        ];
    }

    /**
     * Get orders that used a specific coupon
     */
    public function getOrdersUsingCoupon($couponCode, $limit = 10)
    {
        return SaasOrder::where('coupon_code', $couponCode)
            ->with(['customer', 'seller'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
