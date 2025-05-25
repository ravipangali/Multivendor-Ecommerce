<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SaasSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'display_name',
        'type',
        'validation',
        'description',
        'is_public',
        'sort_order',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Cache key prefix for settings
     */
    const CACHE_KEY = 'saas_settings';

    /**
     * Custom save method to clear cache when a setting is modified
     */
    public function save(array $options = [])
    {
        // Clear cache for this setting
        $this->clearCache();

        return parent::save($options);
    }

    /**
     * Get a setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $cacheKey = self::getCacheKey($key);

        return Cache::remember($cacheKey, 86400, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function setValue(string $key, $value, string $group = 'general')
    {
        $setting = self::firstOrNew([
            'key' => $key,
        ]);

        $setting->value = $value;

        if (!$setting->exists) {
            $setting->group = $group;
        }

        $setting->save();

        return $setting;
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group)
    {
        $cacheKey = self::getCacheKey("group_{$group}");

        return Cache::remember($cacheKey, 86400, function () use ($group) {
            return self::where('group', $group)->orderBy('sort_order')->get();
        });
    }

    /**
     * Clear setting cache
     */
    public function clearCache()
    {
        // Clear specific key cache
        Cache::forget(self::getCacheKey($this->key));

        // Clear group cache
        Cache::forget(self::getCacheKey("group_{$this->group}"));

        // Clear all settings cache
        Cache::forget(self::getCacheKey('all'));
    }

    /**
     * Clear all settings cache
     */
    public static function clearAllCache()
    {
        Cache::flush();
    }

    /**
     * Get cache key for a setting
     */
    protected static function getCacheKey(string $key)
    {
        return self::CACHE_KEY . "_{$key}";
    }
}
