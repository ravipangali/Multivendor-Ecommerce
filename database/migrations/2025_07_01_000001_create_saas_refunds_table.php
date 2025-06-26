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
        Schema::create('saas_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('saas_orders')->onDelete('cascade');
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('saas_payment_methods')->onDelete('restrict');
            $table->decimal('order_amount', 15, 2); // Original order amount
            $table->decimal('commission_rate', 5, 2); // Commission rate at time of order
            $table->decimal('commission_amount', 15, 2); // Commission amount to deduct
            $table->decimal('refund_amount', 15, 2); // Amount to refund to customer
            $table->decimal('seller_deduct_amount', 15, 2); // Amount to deduct from seller (order amount - commission)
            $table->string('currency', 3)->default('NPR');
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
            $table->text('customer_reason'); // Customer's reason for refund
            $table->text('admin_notes')->nullable(); // Admin's notes
            $table->string('admin_attachment')->nullable(); // Admin's proof of payment file
            $table->timestamp('processed_at')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin who processed
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('customer_id');
            $table->index('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_refunds');
    }
};