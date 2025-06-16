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
        Schema::table('saas_products', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['seller_id']);

            // Make seller_id nullable
            $table->foreignId('seller_id')->nullable()->change();

            // Add the foreign key constraint back with nullable
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_products', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['seller_id']);

            // Make seller_id not nullable again
            $table->foreignId('seller_id')->nullable(false)->change();

            // Add the foreign key constraint back as required
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
