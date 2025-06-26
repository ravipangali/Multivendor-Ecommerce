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
        Schema::table('saas_withdrawals', function (Blueprint $table) {
            // Add new fields for seller withdrawals
            $table->decimal('requested_amount', 15, 2)->after('amount'); // Amount requested by seller
            $table->decimal('gateway_fee', 10, 2)->default(0.00)->after('fee'); // Gateway transaction fee
            $table->decimal('final_amount', 15, 2)->after('gateway_fee'); // Final amount after gateway fee
            $table->string('admin_attachment')->nullable()->after('admin_notes'); // Admin's proof of payment file
            $table->enum('type', ['seller_withdrawal'])->default('seller_withdrawal')->after('id'); // Type of withdrawal
            $table->foreignId('processed_by')->nullable()->after('rejected_reason')->constrained('users')->onDelete('set null'); // Admin who processed

            // Update status enum to include 'approved'
            $table->dropColumn('status');
        });

        // Re-add status column with updated enum
        Schema::table('saas_withdrawals', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn([
                'requested_amount',
                'gateway_fee',
                'final_amount',
                'admin_attachment',
                'type',
                'processed_by'
            ]);

            // Restore original status enum
            $table->dropColumn('status');
        });

        Schema::table('saas_withdrawals', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending');
        });
    }
};
