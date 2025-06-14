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
        Schema::table('saas_orders', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->after('cancellation_reason')->comment('Internal admin notes about the order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_orders', function (Blueprint $table) {
            $table->dropColumn('admin_note');
        });
    }
};
