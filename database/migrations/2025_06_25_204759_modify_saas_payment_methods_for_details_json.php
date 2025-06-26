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
        Schema::table('saas_payment_methods', function (Blueprint $table) {
            $table->json('details')->nullable()->after('title');
            $table->dropColumn(['account_name', 'account_number', 'bank_name', 'bank_branch', 'mobile_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_payment_methods', function (Blueprint $table) {
            $table->dropColumn('details');
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('mobile_number')->nullable();
        });
    }
};
