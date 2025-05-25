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
        Schema::create('saas_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['bank_transfer', 'esewa', 'khalti', 'cash', 'other'])->default('bank_transfer');
            $table->string('title')->nullable(); // e.g. "My NIC Asia Account"
            $table->string('account_name')->nullable(); // Name on account
            $table->string('account_number')->nullable(); // Bank account number or mobile for esewa/khalti
            $table->string('bank_name')->nullable(); // For bank transfers
            $table->string('bank_branch')->nullable(); // Branch name/location
            $table->string('mobile_number')->nullable(); // Mobile number for eSewa/Khalti
            $table->boolean('is_default')->default(false); // Default payment method for user
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Additional information
            $table->timestamps();

            // Create an index for faster lookups
            $table->index(['user_id', 'type']);
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_payment_methods');
    }
};
