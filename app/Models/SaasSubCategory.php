<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'image',
        'slug',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subCategory) {
            $slug = Str::slug($subCategory->name);
            $subCategory->slug = self::makeUniqueSlug($slug);
        });

        static::updating(function ($subCategory) {
            if ($subCategory->isDirty('name') && !$subCategory->isDirty('slug')) {
                $slug = Str::slug($subCategory->name);
                $subCategory->slug = self::makeUniqueSlug($slug, $subCategory->id);
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
     * Get the category that owns the subcategory.
     */
    public function category()
    {
        return $this->belongsTo(SaasCategory::class);
    }

    /**
     * Get the child categories for the subcategory.
     */
    public function childCategories()
    {
        return $this->hasMany(SaasChildCategory::class, 'sub_category_id');
    }

    /**
     * Get the products for the subcategory.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'subcategory_id');
    }

    /**
     * Save subcategory image
     */
    public function saveSubCategoryImage($image)
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }

        $filename = 'subcategory_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['image' => $filename]);

        return $filename;
    }

    /**
     * Get subcategory image URL
     */
    public function getSubCategoryImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
