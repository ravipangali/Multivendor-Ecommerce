<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasChildCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_id',
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

        static::creating(function ($childCategory) {
            $slug = Str::slug($childCategory->name);
            $childCategory->slug = self::makeUniqueSlug($slug);
        });

        static::updating(function ($childCategory) {
            if ($childCategory->isDirty('name') && !$childCategory->isDirty('slug')) {
                $slug = Str::slug($childCategory->name);
                $childCategory->slug = self::makeUniqueSlug($slug, $childCategory->id);
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
     * Get the subcategory that owns the child category.
     */
    public function subCategory()
    {
        return $this->belongsTo(SaasSubCategory::class, 'sub_category_id');
    }

    /**
     * Get the products for the child category.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'child_category_id');
    }

    /**
     * Save child category image
     */
    public function saveChildCategoryImage($image)
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }

        $filename = 'childcategory_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['image' => $filename]);

        return $filename;
    }

    /**
     * Get child category image URL
     */
    public function getChildCategoryImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
