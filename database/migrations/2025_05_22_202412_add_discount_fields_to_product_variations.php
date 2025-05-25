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
        Schema::table('saas_product_variations', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0)->after('price');
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat')->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_product_variations', function (Blueprint $table) {
            $table->dropColumn(['discount', 'discount_type']);
        });
    }
};
