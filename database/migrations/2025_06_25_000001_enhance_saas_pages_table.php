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
        Schema::table('saas_pages', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('content');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->boolean('in_header')->default(false)->after('in_footer');
            $table->string('featured_image')->nullable()->after('in_header');
            $table->string('template')->default('default')->after('featured_image');
            $table->unsignedBigInteger('author_id')->nullable()->after('template');
            $table->timestamp('published_at')->nullable()->after('author_id');

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saas_pages', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn([
                'excerpt',
                'meta_keywords',
                'in_header',
                'featured_image',
                'template',
                'author_id',
                'published_at'
            ]);
        });
    }
};
