<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('saas_settings', function (Blueprint $table) {
            // Tax settings
            $table->boolean('tax_enable')->default(true)->after('shipping_policy_info');
            $table->decimal('tax_rate', 5, 2)->default(13.00)->after('tax_enable'); // Default 13% VAT for Nepal
            $table->boolean('tax_shipping')->default(false)->after('tax_rate');
            $table->boolean('tax_inclusive_pricing')->default(false)->after('tax_shipping');

            // Additional shipping settings
            $table->decimal('shipping_weight_rate', 8, 2)->nullable()->after('tax_inclusive_pricing');
            $table->decimal('shipping_min_weight', 8, 2)->default(0.5)->after('shipping_weight_rate');
            $table->decimal('shipping_max_weight', 8, 2)->default(50.0)->after('shipping_min_weight');

            // Zone-based shipping settings
            $table->boolean('shipping_zone_based_enable')->default(false)->after('shipping_max_weight');
            $table->decimal('shipping_local_rate', 8, 2)->default(50.00)->after('shipping_zone_based_enable');
            $table->decimal('shipping_regional_rate', 8, 2)->default(100.00)->after('shipping_local_rate');
            $table->decimal('shipping_remote_rate', 8, 2)->default(200.00)->after('shipping_regional_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_settings', function (Blueprint $table) {
            $table->dropColumn([
                'tax_enable',
                'tax_rate',
                'tax_shipping',
                'tax_inclusive_pricing',
                'shipping_weight_rate',
                'shipping_min_weight',
                'shipping_max_weight',
                'shipping_zone_based_enable',
                'shipping_local_rate',
                'shipping_regional_rate',
                'shipping_remote_rate'
            ]);
        });
    }
};
