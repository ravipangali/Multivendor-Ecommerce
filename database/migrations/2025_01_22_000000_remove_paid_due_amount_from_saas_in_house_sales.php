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
            $table->dropColumn(['paid_amount', 'due_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_in_house_sales', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_status');
            $table->decimal('due_amount', 10, 2)->default(0)->after('paid_amount');
        });
    }
};