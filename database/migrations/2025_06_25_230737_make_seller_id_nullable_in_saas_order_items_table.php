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
        Schema::table('saas_order_items', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['seller_id']);

            // Modify the column to be nullable
            $table->unsignedBigInteger('seller_id')->nullable()->change();

            // Re-add the foreign key constraint with nullable
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_order_items', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['seller_id']);

            // Modify the column back to not nullable
            $table->unsignedBigInteger('seller_id')->nullable(false)->change();

            // Re-add the foreign key constraint with cascade delete
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
