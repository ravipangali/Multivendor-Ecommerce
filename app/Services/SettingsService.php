<?php

namespace App\Services;

use App\Models\SaasSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get all settings with caching
     */
    public static function getSettings()
    {
        return Cache::remember('saas_settings', 3600, function () {
            return SaasSetting::first() ?? new SaasSetting();
        });
    }

    /**
     * Get a specific setting value
     */
    public static function get($key, $default = null)
    {
        $settings = self::getSettings();
        return $settings->{$key} ?? $default;
    }

    /**
     * Check if tax is enabled
     */
    public static function isTaxEnabled()
    {
        return (bool) self::get('tax_enable', false);
    }

    /**
     * Get tax rate
     */
    public static function getTaxRate()
    {
        if (!self::isTaxEnabled()) {
            return 0;
        }
        return (float) self::get('tax_rate', 13.00);
    }

    /**
     * Check if tax applies to shipping
     */
    public static function isTaxOnShipping()
    {
        return self::isTaxEnabled() && (bool) self::get('tax_shipping', false);
    }

    /**
     * Check if tax-inclusive pricing is enabled
     */
    public static function isTaxInclusivePricing()
    {
        return self::isTaxEnabled() && (bool) self::get('tax_inclusive_pricing', false);
    }

    /**
     * Get tax configuration
     */
    public static function getTaxConfig()
    {
        return [
            'enabled' => self::isTaxEnabled(),
            'rate' => self::getTaxRate(),
            'tax_shipping' => self::isTaxOnShipping(),
            'tax_inclusive' => self::isTaxInclusivePricing(),
        ];
    }

    /**
     * Get currency settings
     */
    public static function getCurrencySettings()
    {
        return [
            'symbol' => self::get('site_currency_symbol', 'Rs.'),
            'code' => self::get('site_currency_code', 'NPR'),
        ];
    }

    /**
     * Get site configuration
     */
    public static function getSiteConfig()
    {
        return [
            'name' => self::get('site_name', 'Multi-Tenant E-commerce'),
            'email' => self::get('site_email', 'admin@example.com'),
            'phone' => self::get('site_phone'),
            'address' => self::get('site_address'),
            'logo' => self::get('site_logo'),
            'favicon' => self::get('site_favicon'),
        ];
    }

    /**
     * Get shipping configuration
     */
    public static function getShippingConfig()
    {
        return [
            'free_shipping_enabled' => (bool) self::get('shipping_enable_free', false),
            'free_shipping_min_amount' => (float) self::get('shipping_free_min_amount', 1000),
            'flat_rate_enabled' => (bool) self::get('shipping_flat_rate_enable', false),
            'flat_rate_cost' => (float) self::get('shipping_flat_rate_cost', 100),
            'local_pickup_enabled' => (bool) self::get('shipping_enable_local_pickup', false),
            'local_pickup_cost' => (float) self::get('shipping_local_pickup_cost', 0),
            'zone_based_enabled' => (bool) self::get('shipping_zone_based_enable', false),
            'local_rate' => (float) self::get('shipping_local_rate', 50),
            'regional_rate' => (float) self::get('shipping_regional_rate', 100),
            'remote_rate' => (float) self::get('shipping_remote_rate', 200),
        ];
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('saas_settings');
        Cache::forget('tax_config');
        Cache::forget('shipping_config');
        Cache::forget('site_config');
    }

    /**
     * Update setting value
     */
    public static function set($key, $value)
    {
        $settings = SaasSetting::first() ?? new SaasSetting();
        $settings->{$key} = $value;
        $settings->save();

        self::clearCache();

        return $settings;
    }

    /**
     * Bulk update settings
     */
    public static function update($data)
    {
        $settings = SaasSetting::first() ?? new SaasSetting();
        $settings->fill($data);
        $settings->save();

        self::clearCache();

        return $settings;
    }

    /**
     * Check if email is configured
     */
    public static function isEmailConfigured()
    {
        return !empty(self::get('mail_host')) &&
               !empty(self::get('mail_port')) &&
               !empty(self::get('mail_username')) &&
               !empty(self::get('mail_password'));
    }

    /**
     * Check if payment gateways are configured
     */
    public static function isPaymentConfigured()
    {
        return !empty(self::get('esewa_merchant_id')) ||
               !empty(self::get('khalti_public_key'));
    }

    /**
     * Get default settings for new installation
     */
    public static function getDefaults()
    {
        return [
            'site_name' => 'Multi-Tenant E-commerce',
            'site_email' => 'admin@example.com',
            'site_currency_symbol' => 'Rs.',
            'site_currency_code' => 'NPR',
            'tax_enable' => false,
            'tax_rate' => 13.00,
            'tax_shipping' => false,
            'tax_inclusive_pricing' => false,
            'shipping_enable_free' => true,
            'shipping_free_min_amount' => 1000.00,
            'shipping_flat_rate_enable' => true,
            'shipping_flat_rate_cost' => 100.00,
        ];
    }
}
