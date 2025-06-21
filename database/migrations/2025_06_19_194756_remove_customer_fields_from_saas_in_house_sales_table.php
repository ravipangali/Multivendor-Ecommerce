<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the columns exist before trying to drop them
        $columns = ['customer_name', 'customer_phone', 'customer_email', 'customer_address'];
        $existingColumns = [];

        foreach ($columns as $column) {
            if (Schema::hasColumn('saas_in_house_sales', $column)) {
                $existingColumns[] = $column;
            }
        }

        // Only proceed if there are columns to drop
        if (count($existingColumns) > 0) {
            // Check if the index exists before trying to drop it
            $indexExists = collect(DB::select("SHOW INDEXES FROM saas_in_house_sales"))
                ->where('Key_name', 'saas_in_house_sales_customer_phone_customer_email_index')
                ->count() > 0;

            Schema::table('saas_in_house_sales', function (Blueprint $table) use ($existingColumns, $indexExists) {
                if ($indexExists) {
                    $table->dropIndex(['customer_phone', 'customer_email']);
                }

                foreach ($existingColumns as $column) {
                    $table->dropColumn($column);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_in_house_sales', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('sale_number');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_phone');
            $table->text('customer_address')->nullable()->after('customer_email');

            // Recreate the index
            $table->index(['customer_phone', 'customer_email']);
        });
    }
};
