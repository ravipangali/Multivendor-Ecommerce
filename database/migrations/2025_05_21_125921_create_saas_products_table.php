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
        Schema::create('saas_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->foreignId('category_id')->constrained('saas_categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('saas_sub_categories')->onDelete('set null');
            $table->foreignId('child_category_id')->nullable()->constrained('saas_child_categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('saas_brands')->onDelete('set null');
            $table->string('SKU')->unique();
            $table->foreignId('unit_id')->nullable()->constrained('saas_units')->onDelete('set null');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('tax', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_products');
    }
};
