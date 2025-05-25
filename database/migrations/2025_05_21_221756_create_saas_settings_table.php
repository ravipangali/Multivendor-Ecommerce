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
        Schema::create('saas_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // Group settings: general, email, payment, analytics, etc.
            $table->string('display_name');
            $table->string('type')->default('text'); // text, textarea, file, boolean, select, etc.
            $table->string('validation')->nullable(); // Validation rules
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false); // Whether setting is publicly accessible
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Add index for faster lookups
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_settings');
    }
};
