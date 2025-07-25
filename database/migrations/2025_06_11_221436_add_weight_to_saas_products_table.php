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
            $table->decimal('weight', 8, 2)->default(0.5)->after('stock')->comment('Product weight in kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
