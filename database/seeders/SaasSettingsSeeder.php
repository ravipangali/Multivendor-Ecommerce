<?php

namespace Database\Seeders;

use App\Models\SaasSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaasSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        SaasSetting::updateOrCreate(['id' => 1], [
            // Shipping settings
            'shipping_enable_free' => true,
            'shipping_free_min_amount' => 1000.00,
            'shipping_flat_rate_enable' => true,
            'shipping_flat_rate_cost' => 100.00,
            'shipping_enable_local_pickup' => false,
            'shipping_local_pickup_cost' => 0.00,
            'shipping_allow_seller_config' => false,
            'shipping_seller_free_enable' => false,
            'shipping_seller_flat_rate_enable' => false,
            'shipping_seller_zone_based_enable' => false,
            'shipping_policy_info' => 'Standard shipping takes 3-5 business days. Free shipping on orders over Rs. 1,000.',

            // Tax settings
            'tax_enable' => true,
            'tax_rate' => 13.00, // Nepal VAT
            'tax_shipping' => false,
            'tax_inclusive_pricing' => false,

            // Additional shipping settings
            'shipping_weight_rate' => null,
            'shipping_min_weight' => 0.5,
            'shipping_max_weight' => 50.0,
            'shipping_zone_based_enable' => false,
            'shipping_local_rate' => 50.00,
            'shipping_regional_rate' => 100.00,
            'shipping_remote_rate' => 200.00,

            'updated_at' => now(),
        ]);
    }
}
