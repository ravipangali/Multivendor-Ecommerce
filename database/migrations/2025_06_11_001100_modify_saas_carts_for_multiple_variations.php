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
        Schema::table('saas_carts', function (Blueprint $table) {
            // Add JSON column to store multiple variations
            $table->json('variations_data')->nullable()->after('variation_id');
            // Add column to store selected attributes as text for display
            $table->text('variation_details')->nullable()->after('variations_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_carts', function (Blueprint $table) {
            $table->dropColumn(['variations_data', 'variation_details']);
        });
    }
};
