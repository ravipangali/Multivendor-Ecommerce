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
        Schema::table('saas_in_house_sales', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('sale_number')
                  ->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_in_house_sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id');
        });
    }
};
