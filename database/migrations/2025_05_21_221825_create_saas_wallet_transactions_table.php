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
        Schema::create('saas_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('saas_wallets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['credit', 'debit'])->index();
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->decimal('balance_after', 15, 2); // Wallet balance after transaction
            $table->string('currency', 3)->default('NPR');
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed'])->default('completed');
            $table->morphs('transactionable', 'trans_morph_idx'); // Using shorter custom index name
            $table->enum('source', ['order', 'withdrawal', 'refund', 'adjustment', 'manual', 'commission', 'other'])->index();
            $table->string('reference_id')->nullable(); // External reference if applicable
            $table->text('description')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // If transaction was initiated by admin
            $table->json('meta_data')->nullable(); // Additional transaction data
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_wallet_transactions');
    }
};
