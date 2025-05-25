<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SaasProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class);
    }

    /**
     * Get the full URL of the image.
     */
    public function getImageUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * Get the raw image path (without full URL)
     */
    public function getRawImageUrlAttribute()
    {
        return $this->attributes['image_url'];
    }
}
