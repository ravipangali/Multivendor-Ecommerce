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
        Schema::table('saas_orders', function (Blueprint $table) {
            // Shipping address fields
            $table->string('shipping_name')->nullable()->after('payment_status');
            $table->string('shipping_email')->nullable()->after('shipping_name');
            $table->string('shipping_phone')->nullable()->after('shipping_email');
            $table->string('shipping_country')->nullable()->after('shipping_phone');
            $table->string('shipping_street_address')->nullable()->after('shipping_country');
            $table->string('shipping_city')->nullable()->after('shipping_street_address');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_state');

            // Billing address fields
            $table->string('billing_name')->nullable()->after('shipping_postal_code');
            $table->string('billing_email')->nullable()->after('billing_name');
            $table->string('billing_phone')->nullable()->after('billing_email');
            $table->string('billing_country')->nullable()->after('billing_phone');
            $table->string('billing_street_address')->nullable()->after('billing_country');
            $table->string('billing_city')->nullable()->after('billing_street_address');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_postal_code')->nullable()->after('billing_state');

            // Coupon tracking
            $table->string('coupon_code')->nullable()->after('billing_postal_code');
            $table->decimal('coupon_discount_amount', 10, 2)->default(0)->after('coupon_code');
            $table->enum('coupon_discount_type', ['percentage', 'fixed'])->nullable()->after('coupon_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_email',
                'shipping_phone',
                'shipping_country',
                'shipping_street_address',
                'shipping_city',
                'shipping_state',
                'shipping_postal_code',
                'billing_name',
                'billing_email',
                'billing_phone',
                'billing_country',
                'billing_street_address',
                'billing_city',
                'billing_state',
                'billing_postal_code',
                'coupon_code',
                'coupon_discount_amount',
                'coupon_discount_type'
            ]);
        });
    }
};
