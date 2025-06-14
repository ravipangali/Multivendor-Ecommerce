<?php

namespace App\Services;

use App\Models\SaasSetting;
use App\Models\SaasProduct;
use Illuminate\Support\Collection;

class SaasShippingService
{
        /**
     * Calculate shipping cost for cart items
     */
    public function calculateShippingCost(Collection $cartItems, $subtotal, $shippingAddress = null)
    {
        $settings = SaasSetting::first();

        // Check for free shipping eligibility first
        if ($this->qualifiesForFreeShipping($subtotal, $settings)) {
            return 0;
        }

        // Calculate weight if weight-based shipping is enabled
        $totalWeight = $this->calculateTotalWeight($cartItems);

        // Use weight-based calculation if enabled and configured
        if ($settings && $settings->shipping_weight_rate && $totalWeight > 0) {
            return round($totalWeight * $settings->shipping_weight_rate, 2);
        }

        // Use zone-based calculation if enabled and address provided
        if ($settings && $settings->shipping_zone_based_enable && $shippingAddress) {
            $zoneRate = $this->getZoneBasedRate($shippingAddress, $settings);
            if ($zoneRate > 0) {
                return $zoneRate;
            }
        }

        // Use flat rate if enabled
        if ($settings && $settings->shipping_flat_rate_enable && $settings->shipping_flat_rate_cost) {
            return round($settings->shipping_flat_rate_cost, 2);
        }

        // Default fallback - free shipping for orders over threshold
        $freeShippingThreshold = $settings->shipping_free_min_amount ?? 1000;
        return $subtotal >= $freeShippingThreshold ? 0 : 100;
    }

        /**
     * Check if order qualifies for free shipping
     */
    protected function qualifiesForFreeShipping($subtotal, $settings)
    {
        if (!$settings || !$settings->shipping_enable_free) {
            return false;
        }

        $freeShippingMinAmount = $settings->shipping_free_min_amount ?? 1000;
        return $subtotal >= $freeShippingMinAmount;
    }

    /**
     * Calculate total weight of cart items
     */
    protected function calculateTotalWeight(Collection $cartItems)
    {
        return $cartItems->sum(function($item) {
            // Handle array items (for testing)
            if (is_array($item)) {
                $weight = $item['weight'] ?? 0.5; // Default weight
                $quantity = $item['quantity'] ?? 1;
                return $weight * $quantity;
            }

            // Handle actual cart item objects
            if (is_object($item) && isset($item->product)) {
                $weight = $item->product->weight ?? 0.5;
                $quantity = $item->quantity ?? 1;
                return $weight * $quantity;
            }

            return 0.5; // Default weight for unknown items
        });
    }

    /**
     * Get zone-based shipping rate
     */
    protected function getZoneBasedRate($shippingAddress, $settings)
    {
        // Simple zone detection based on address (can be enhanced)
        if (is_array($shippingAddress)) {
            $state = $shippingAddress['shipping_state'] ?? '';
            $city = $shippingAddress['shipping_city'] ?? '';

            // Simple zone logic - can be enhanced with proper zone mapping
            if (in_array(strtolower($state), ['bagmati', 'kathmandu'])) {
                return $settings->shipping_local_rate ?? 50;
            } elseif (in_array(strtolower($state), ['gandaki', 'lumbini', 'madhesh'])) {
                return $settings->shipping_regional_rate ?? 100;
            } else {
                return $settings->shipping_remote_rate ?? 200;
            }
        }

        // Default to regional rate if can't determine zone
        return $settings->shipping_regional_rate ?? 100;
    }
}
