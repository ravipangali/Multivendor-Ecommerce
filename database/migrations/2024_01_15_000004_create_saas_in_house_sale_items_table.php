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
        Schema::create('saas_in_house_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('saas_in_house_sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('saas_products')->onDelete('cascade');
            $table->foreignId('variation_id')->nullable()->constrained('saas_product_variations')->onDelete('cascade');
            $table->string('product_name'); // Store name at time of sale
            $table->string('product_sku'); // Store SKU at time of sale
            $table->decimal('unit_price', 10, 2); // Store price at time of sale
            $table->integer('quantity');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_type')->nullable(); // 'percentage' or 'flat'
            $table->decimal('total_price', 10, 2); // (unit_price * quantity) - discount
            $table->timestamps();

            $table->index(['sale_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_in_house_sale_items');
    }
};
