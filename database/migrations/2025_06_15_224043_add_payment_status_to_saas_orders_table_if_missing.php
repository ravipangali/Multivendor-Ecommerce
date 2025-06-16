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
        Schema::table('saas_orders', function (Blueprint $table) {
            // Check if payment_status column doesn't exist, then add it
            if (!Schema::hasColumn('saas_orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'canceled'])
                      ->default('pending')
                      ->after('total');
            } else {
                // If column exists, modify it to include all needed values
                DB::statement("ALTER TABLE saas_orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded', 'canceled') DEFAULT 'pending'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_orders', function (Blueprint $table) {
            if (Schema::hasColumn('saas_orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
