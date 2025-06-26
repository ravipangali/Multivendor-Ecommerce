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
        Schema::create('saas_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('transaction_type')->comment('deposit, withdrawal, commission, refund');
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->foreignId('order_id')->nullable()->constrained('saas_orders')->onDelete('set null');
            $table->string('reference_type')->nullable()->comment('order, withdrawal, manual, commission');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->decimal('commission_amount', 15, 2)->nullable();
            $table->string('status')->default('completed')->comment('pending, completed, failed, cancelled');
            $table->json('meta_data')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'transaction_type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_transactions');
    }
};
