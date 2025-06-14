<?php

namespace App\Services;

use App\Models\SaasSetting;
use App\Models\SaasProduct;
use Illuminate\Support\Collection;

class SaasTaxService
{
    /**
     * Calculate tax for cart items
     */
    public function calculateTax(Collection $cartItems, $subtotal, $shippingAddress = null, $shippingCost = 0)
    {
        $settings = SaasSetting::first();

        // Check if tax is enabled - if not, return 0
        if (!$settings || !$settings->tax_enable) {
            return 0;
        }

        // Get tax rate from settings or use default Nepal VAT rate
        $taxRate = $settings->tax_rate ?? 13;

        // Ensure tax rate is valid
        if ($taxRate <= 0) {
            return 0;
        }

        // Calculate base tax on subtotal
        $taxableAmount = $subtotal;

        // Add shipping to taxable amount if tax_shipping is enabled
        if ($settings->tax_shipping && $shippingCost > 0) {
            $taxableAmount += $shippingCost;
        }

        // For tax-inclusive pricing, calculate the tax component
        if ($settings->tax_inclusive_pricing) {
            // If prices include tax, extract the tax component
            return round($taxableAmount * ($taxRate / (100 + $taxRate)), 2);
        }

        // For tax-exclusive pricing, add tax on top
        return round($taxableAmount * ($taxRate / 100), 2);
    }

    /**
     * Calculate tax for a specific amount
     */
    public function calculateTaxForAmount($amount, $includeTaxOnShipping = false, $shippingAmount = 0)
    {
        $settings = SaasSetting::first();

        if (!$settings || !$settings->tax_enable) {
            return 0;
        }

        $taxRate = $settings->tax_rate ?? 13;

        // Ensure tax rate is valid
        if ($taxRate <= 0) {
            return 0;
        }

        $taxableAmount = $amount;

        if ($includeTaxOnShipping && $settings->tax_shipping) {
            $taxableAmount += $shippingAmount;
        }

        if ($settings->tax_inclusive_pricing) {
            return round($taxableAmount * ($taxRate / (100 + $taxRate)), 2);
        }

        return round($taxableAmount * ($taxRate / 100), 2);
    }

    /**
     * Check if tax is enabled
     */
    public function isTaxEnabled()
    {
        $settings = SaasSetting::first();
        return $settings && $settings->tax_enable;
    }

    /**
     * Get current tax rate
     */
    public function getTaxRate()
    {
        $settings = SaasSetting::first();
        if (!$settings || !$settings->tax_enable) {
            return 0;
        }
        return $settings->tax_rate ?? 0;
    }

    /**
     * Get tax settings
     */
    public function getTaxSettings()
    {
        $settings = SaasSetting::first();

        return [
            'enabled' => $settings ? (bool)$settings->tax_enable : false,
            'rate' => $settings ? ($settings->tax_rate ?? 0) : 0,
            'tax_shipping' => $settings ? (bool)$settings->tax_shipping : false,
            'tax_inclusive' => $settings ? (bool)$settings->tax_inclusive_pricing : false,
        ];
    }

    /**
     * Calculate tax breakdown for display
     */
    public function getTaxBreakdown($subtotal, $shippingCost = 0)
    {
        $settings = SaasSetting::first();

        if (!$settings || !$settings->tax_enable) {
            return [
                'enabled' => false,
                'rate' => 0,
                'subtotal_tax' => 0,
                'shipping_tax' => 0,
                'total_tax' => 0,
                'tax_inclusive' => false,
            ];
        }

        $taxRate = $settings->tax_rate ?? 13;
        $subtotalTax = 0;
        $shippingTax = 0;

        if ($settings->tax_inclusive_pricing) {
            // Extract tax from inclusive prices
            $subtotalTax = round($subtotal * ($taxRate / (100 + $taxRate)), 2);
            if ($settings->tax_shipping) {
                $shippingTax = round($shippingCost * ($taxRate / (100 + $taxRate)), 2);
            }
        } else {
            // Add tax on top of exclusive prices
            $subtotalTax = round($subtotal * ($taxRate / 100), 2);
            if ($settings->tax_shipping) {
                $shippingTax = round($shippingCost * ($taxRate / 100), 2);
            }
        }

        return [
            'enabled' => true,
            'rate' => $taxRate,
            'subtotal_tax' => $subtotalTax,
            'shipping_tax' => $shippingTax,
            'total_tax' => $subtotalTax + $shippingTax,
            'tax_inclusive' => (bool)$settings->tax_inclusive_pricing,
        ];
    }
}
