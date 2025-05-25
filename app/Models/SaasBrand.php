<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasBrand extends Model
{
    use HasFactory;

    protected $fillable = [
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

        static::creating(function ($brand) {
            $slug = Str::slug($brand->name);
            $brand->slug = self::makeUniqueSlug($slug);
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && !$brand->isDirty('slug')) {
                $slug = Str::slug($brand->name);
                $brand->slug = self::makeUniqueSlug($slug, $brand->id);
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
     * Get the products for the brand.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'brand_id');
    }

    /**
     * Save brand image
     */
    public function saveBrandImage($image)
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }

        $filename = 'brand_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['image' => $filename]);

        return $filename;
    }

    /**
     * Get brand image URL
     */
    public function getBrandImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
