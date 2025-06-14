<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'fixed' values to 'flat' to match the code
        DB::table('saas_orders')
            ->where('coupon_discount_type', 'fixed')
            ->update(['coupon_discount_type' => 'flat']);

        // Drop the existing enum column and recreate it with correct values
        Schema::table('saas_orders', function (Blueprint $table) {
            $table->dropColumn('coupon_discount_type');
        });

        Schema::table('saas_orders', function (Blueprint $table) {
            $table->enum('coupon_discount_type', ['percentage', 'flat'])->nullable()->after('coupon_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        Schema::table('saas_orders', function (Blueprint $table) {
            $table->dropColumn('coupon_discount_type');
        });

        Schema::table('saas_orders', function (Blueprint $table) {
            $table->enum('coupon_discount_type', ['percentage', 'fixed'])->nullable()->after('coupon_discount_amount');
        });

        // Update any 'flat' values back to 'fixed'
        DB::table('saas_orders')
            ->where('coupon_discount_type', 'flat')
            ->update(['coupon_discount_type' => 'fixed']);
    }
};
