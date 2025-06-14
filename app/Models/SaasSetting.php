<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasSetting extends Model
{
    protected $fillable = [
        // Site Settings
        'site_name',
        'site_logo',
        'site_favicon',
        'site_description',
        'site_keywords',
        'site_footer',
        'site_email',
        'site_phone',
        'site_address',

        // Social Media Links
        'site_facebook',
        'site_twitter',
        'site_instagram',
        'site_linkedin',
        'site_youtube',

        // Currency Settings
        'site_currency_symbol',
        'site_currency_code',

        // Mail Settings
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',

        // Payment Gateway Settings
        'minimum_withdrawal_amount',
        'gateway_transaction_fee',
        'esewa_merchant_id',
        'esewa_secret_key',
        'khalti_public_key',
        'khalti_secret_key',
        'withdrawal_policy',

        // Shipping Settings
        'shipping_enable_free',
        'shipping_free_min_amount',
        'shipping_flat_rate_enable',
        'shipping_flat_rate_cost',
        'shipping_enable_local_pickup',
        'shipping_local_pickup_cost',
        'shipping_allow_seller_config',
        'shipping_seller_free_enable',
        'shipping_seller_flat_rate_enable',
        'shipping_seller_zone_based_enable',
        'shipping_policy_info',

        // Tax Settings
        'tax_enable',
        'tax_rate',
        'tax_shipping',
        'tax_inclusive_pricing',

        // Additional Shipping Settings
        'shipping_weight_rate',
        'shipping_min_weight',
        'shipping_max_weight',
        'shipping_zone_based_enable',
        'shipping_local_rate',
        'shipping_regional_rate',
        'shipping_remote_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'minimum_withdrawal_amount' => 'decimal:2',
        'gateway_transaction_fee' => 'decimal:2',
    ];
}
