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
        // First, update existing data to map old positions to new ones
        DB::table('saas_banners')
            ->where('position', 'homepage')
            ->update(['position' => 'main_section']);

        DB::table('saas_banners')
            ->where('position', 'top')
            ->update(['position' => 'popup']);

        DB::table('saas_banners')
            ->where('position', 'sidebar')
            ->update(['position' => 'footer']);

        // Now update the enum column to use new values
        DB::statement("ALTER TABLE saas_banners MODIFY COLUMN position ENUM('popup', 'footer', 'main_section') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE saas_banners MODIFY COLUMN position ENUM('homepage', 'top', 'sidebar') NOT NULL");

        // Revert data mapping
        DB::table('saas_banners')
            ->where('position', 'main_section')
            ->update(['position' => 'homepage']);

        DB::table('saas_banners')
            ->where('position', 'popup')
            ->update(['position' => 'top']);

        DB::table('saas_banners')
            ->where('position', 'footer')
            ->update(['position' => 'sidebar']);
    }
};