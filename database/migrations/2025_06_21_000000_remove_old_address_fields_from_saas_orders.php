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
            // Remove old text address fields since we have individual fields now
            $table->dropColumn(['shipping_address', 'billing_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_orders', function (Blueprint $table) {
            // Add back the old address fields if needed
            $table->text('shipping_address')->nullable()->after('payment_method');
            $table->text('billing_address')->nullable()->after('shipping_address');
        });
    }
};
