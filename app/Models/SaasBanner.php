<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SaasBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link_url',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to get banners by position.
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Save banner image
     */
    public function saveBannerImage($image)
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }

        $filename = 'banner_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['image' => $filename]);

        return $filename;
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
