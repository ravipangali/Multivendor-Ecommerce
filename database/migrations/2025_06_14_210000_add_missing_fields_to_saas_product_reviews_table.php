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
        Schema::table('saas_product_reviews', function (Blueprint $table) {
            $table->text('seller_response')->nullable()->after('review');
            $table->boolean('is_reported')->default(false)->after('seller_response');
            $table->text('report_reason')->nullable()->after('is_reported');
            $table->boolean('is_approved')->default(true)->after('report_reason');
            $table->json('images')->nullable()->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_product_reviews', function (Blueprint $table) {
            $table->dropColumn([
                'seller_response',
                'is_reported',
                'report_reason',
                'is_approved',
                'images'
            ]);
        });
    }
};
