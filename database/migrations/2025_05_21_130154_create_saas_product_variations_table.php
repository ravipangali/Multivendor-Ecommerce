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
        Schema::create('saas_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('saas_products')->onDelete('cascade');
            $table->string('sku')->unique();
            $table->foreignId('attribute_id')->constrained('saas_attributes')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('saas_attribute_values')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_product_variations');
    }
};
