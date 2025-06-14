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

            // Site Settings
            $table->string('site_name')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->text('site_description')->nullable();
            $table->text('site_keywords')->nullable();
            $table->text('site_footer')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->text('site_address')->nullable();

            // Social Media Links
            $table->string('site_facebook')->nullable();
            $table->string('site_twitter')->nullable();
            $table->string('site_instagram')->nullable();
            $table->string('site_linkedin')->nullable();
            $table->string('site_youtube')->nullable();

            // Currency Settings
            $table->string('site_currency_symbol')->nullable();
            $table->string('site_currency_code')->nullable();

            // Mail Settings
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->enum('mail_encryption', ['TLS', 'SSL'])->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            // Payment Gateway Settings
            $table->decimal('minimum_withdrawal_amount', 10, 2)->nullable();
            $table->decimal('gateway_transaction_fee', 10, 2)->nullable();
            $table->string('esewa_merchant_id')->nullable();
            $table->string('esewa_secret_key')->nullable();
            $table->string('khalti_public_key')->nullable();
            $table->string('khalti_secret_key')->nullable();
            $table->text('withdrawal_policy')->nullable();

            // Shipping Settings
            $table->boolean('shipping_enable_free')->default(false);
            $table->decimal('shipping_free_min_amount', 10, 2)->nullable();
            $table->boolean('shipping_flat_rate_enable')->default(false);
            $table->decimal('shipping_flat_rate_cost', 10, 2)->nullable();
            $table->boolean('shipping_enable_local_pickup')->default(false);
            $table->decimal('shipping_local_pickup_cost', 10, 2)->nullable();
            $table->boolean('shipping_allow_seller_config')->default(false);
            $table->boolean('shipping_seller_free_enable')->default(false);
            $table->boolean('shipping_seller_flat_rate_enable')->default(false);
            $table->boolean('shipping_seller_zone_based_enable')->default(false);
            $table->text('shipping_policy_info')->nullable();

            $table->timestamps();
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
