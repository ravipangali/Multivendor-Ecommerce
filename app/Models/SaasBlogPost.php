<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SaasBlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_featured',
        'views',
        'reading_time',
        'author_id',
        'category_id',
        'published_at',
        'tags',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = self::makeUniqueSlug(Str::slug($post->title));
            }

            // Calculate reading time based on content
            if ($post->content) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->isDirty('slug')) {
                $post->slug = self::makeUniqueSlug(Str::slug($post->title), $post->id);
            }

            // Recalculate reading time if content changed
            if ($post->isDirty('content') && $post->content) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time = max(1, ceil($wordCount / 200));
            }
        });
    }

    /**
     * Create a unique slug.
     */
    protected static function makeUniqueSlug($slug, $id = null)
    {
        $originalSlug = $slug;
        $count = 1;

        $query = static::whereSlug($slug);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::whereSlug($slug);

            if ($id) {
                $query->where('id', '!=', $id);
            }
        }

        return $slug;
    }

    /**
     * Get the author that owns the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category that owns the blog post.
     */
    public function category()
    {
        return $this->belongsTo(SaasBlogCategory::class, 'category_id');
    }

    /**
     * Scope a query to only include active posts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', true)
                    ->where(function ($query) {
                        $query->whereNull('published_at')
                              ->orWhere('published_at', '<=', now());
                    });
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($query) use ($categorySlug) {
            $query->where('slug', $categorySlug);
        });
    }

    /**
     * Scope a query to search posts.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('excerpt', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%')
                  ->orWhere('tags', 'like', '%' . $search . '%');
        });
    }

    /**
     * Get the post's featured image URL.
     */
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    /**
     * Get the post's excerpt or truncated content.
     */
    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 200);
    }

    /**
     * Get next post.
     */
    public function getNextPostAttribute()
    {
        return static::published()
                    ->where('id', '>', $this->id)
                    ->orderBy('id', 'asc')
                    ->first();
    }

    /**
     * Get previous post.
     */
    public function getPreviousPostAttribute()
    {
        return static::published()
                    ->where('id', '<', $this->id)
                    ->orderBy('id', 'desc')
                    ->first();
    }

    /**
     * Get related posts.
     */
    public function getRelatedPostsAttribute()
    {
        return static::published()
                    ->where('id', '!=', $this->id)
                    ->where('category_id', $this->category_id)
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
