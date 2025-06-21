<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
     * Ensure storage directories exist
     */
    private function ensureStorageDirectoryExists()
    {
        $directory = storage_path('app/public/banner_images');

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Save banner image
     */
    public function saveBannerImage($image)
    {
        try {
            // Ensure directory exists
            $this->ensureStorageDirectoryExists();

            // Delete old image if exists
            if ($this->image) {
                Storage::disk('public')->delete($this->image);
            }

            // Generate unique filename
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the banner_images directory
            $path = $image->storeAs('banner_images', $filename, 'public');

            // Update the banner record with the file path
            $this->update(['image' => $path]);

            return $path;

        } catch (\Exception $e) {
            Log::error('Banner image save failed: ' . $e->getMessage());
            throw new \Exception('Failed to save banner image: ' . $e->getMessage());
        }
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    /**
     * Get position display name
     */
    public function getPositionDisplayNameAttribute()
    {
        $positions = [
            'popup' => 'Popup Banner',
            'footer' => 'Footer Banner',
            'main_section' => 'Main Section Banner'
        ];

        return $positions[$this->position] ?? ucfirst($this->position);
    }
}
