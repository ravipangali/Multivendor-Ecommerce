<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SaasFlashDeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'banner_image',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the products for the flash deal.
     */
    public function products()
    {
        return $this->belongsToMany(SaasProduct::class, 'saas_flash_deal_products', 'flash_deal_id', 'product_id')
            ->withPivot('discount_type', 'discount_value')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active flash deals.
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('start_time', '<=', $now)
                     ->where('end_time', '>=', $now);
    }

    /**
     * Check if the flash deal is active.
     */
    public function isActive()
    {
        $now = now();
        return $this->start_time <= $now && $this->end_time >= $now;
    }

    /**
     * Save flash deal banner image
     */
    public function saveBannerImage($image)
    {
        if ($this->banner_image) {
            Storage::disk('public')->delete($this->banner_image);
        }

        $filename = 'flash_deal_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['banner_image' => $filename]);

        return $filename;
    }

    /**
     * Get flash deal banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? Storage::url($this->banner_image) : null;
    }
}
