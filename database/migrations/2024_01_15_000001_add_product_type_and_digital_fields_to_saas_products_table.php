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
            $table->enum('product_type', ['Digital', 'Physical'])->default('Physical')->after('slug');
            $table->boolean('is_in_house_product')->default(false)->after('product_type');
            $table->string('file')->nullable()->after('is_in_house_product')->comment('Digital product file path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'is_in_house_product', 'file']);
        });
    }
};