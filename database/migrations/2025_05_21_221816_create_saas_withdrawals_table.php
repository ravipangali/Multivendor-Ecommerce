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
        Schema::create('saas_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('saas_wallets')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('saas_payment_methods')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 10, 2)->default(0.00); // Transaction fee if applicable
            $table->decimal('net_amount', 15, 2); // Amount after fees
            $table->string('currency', 3)->default('NPR');
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable(); // For internal use
            $table->string('reference_id')->nullable(); // Transaction reference
            $table->timestamp('processed_at')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_withdrawals');
    }
};
