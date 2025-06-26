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
        Schema::create('saas_blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->integer('reading_time')->default(1); // in minutes
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('saas_blog_categories')->onDelete('set null');

            $table->index(['status', 'published_at']);
            $table->index(['is_featured', 'status']);
            $table->index('slug');
            $table->index('category_id');
            $table->index('author_id');
            $table->fullText(['title', 'excerpt', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_blog_posts');
    }
};
