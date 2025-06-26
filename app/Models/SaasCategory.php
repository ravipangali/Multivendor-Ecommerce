<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'slug',
        'status',
        'featured',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $slug = Str::slug($category->name);
            $category->slug = self::makeUniqueSlug($slug);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $slug = Str::slug($category->name);
                $category->slug = self::makeUniqueSlug($slug, $category->id);
            }
        });
    }

    /**
     * Create a unique slug.
     *
     * @param string $slug
     * @param int|null $id
     * @return string
     */
    protected static function makeUniqueSlug($slug, $id = null)
    {
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug exists
        $query = static::whereSlug($slug);

        // Exclude the current model when updating
        if ($id) {
            $query->where('id', '!=', $id);
        }

        // If we already have this slug in the database
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
     * Get the subcategories for the category.
     */
    public function subcategories()
    {
        return $this->hasMany(SaasSubCategory::class, 'category_id');
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'category_id');
    }

    /**
     * Save category image
     */
    public function saveCategoryImage($image)
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }

        $filename = 'category_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['image' => $filename]);

        return $filename;
    }

    /**
     * Get category image URL
     */
    public function getCategoryImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    /**
     * Get all products from this category including subcategories and child categories
     */
    public function getAllHierarchyProducts($limit = null)
    {
        $allProducts = collect();

        // 1. Get products directly from this category
        $directProducts = $this->products()
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->with(['images', 'brand', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();
        $allProducts = $allProducts->merge($directProducts);

        // 2. Get products from subcategories
        $subcategories = $this->subcategories;
        foreach ($subcategories as $subcategory) {
            $subcategoryProducts = $subcategory->products()
                ->where('is_active', true)
                ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
                ->with(['images', 'brand', 'reviews'])
                ->orderBy('created_at', 'desc')
                ->get();
            $allProducts = $allProducts->merge($subcategoryProducts);

            // 3. Get products from child categories under this subcategory
            $childCategories = $subcategory->childCategories;
            foreach ($childCategories as $childCategory) {
                $childProducts = $childCategory->products()
                    ->where('is_active', true)
                    ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
                    ->with(['images', 'brand', 'reviews'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $allProducts = $allProducts->merge($childProducts);
            }
        }

        // Remove duplicates and apply limit if specified
        $uniqueProducts = $allProducts->unique('id');

        return $limit ? $uniqueProducts->take($limit) : $uniqueProducts;
    }

    /**
     * Check if category has any products in its hierarchy
     */
    public function hasAnyProducts()
    {
        // Check direct products
        if ($this->products()->where('is_active', true)->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)->exists()) {
            return true;
        }

        // Check subcategory products
        foreach ($this->subcategories as $subcategory) {
            if ($subcategory->products()->where('is_active', true)->exists()) {
                return true;
            }

            // Check child category products
            foreach ($subcategory->childCategories as $childCategory) {
                if ($childCategory->products()->where('is_active', true)->exists()) {
                    return true;
                }
            }
        }

        return false;
    }
}
