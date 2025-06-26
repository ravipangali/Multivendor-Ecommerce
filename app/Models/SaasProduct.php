<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'product_type',
        'is_in_house_product',
        'file',
        'description',
        'short_description',
        'category_id',
        'subcategory_id',
        'child_category_id',
        'brand_id',
        'SKU',
        'unit_id',
        'stock',
        'weight',
        'price',
        'discount',
        'discount_type',
        'tax',
        'is_active',
        'is_featured',
        'has_variations',
        'seller_publish_status',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'has_variations' => 'boolean',
        'is_in_house_product' => 'boolean',
    ];

    // Product type constants
    const PRODUCT_TYPE_DIGITAL = 'Digital';
    const PRODUCT_TYPE_PHYSICAL = 'Physical';

    // Seller publish status constants
    const SELLER_PUBLISH_STATUS_REQUEST = 'request_product';
    const SELLER_PUBLISH_STATUS_APPROVED = 'approved_product';
    const SELLER_PUBLISH_STATUS_DENIED = 'denied_product';

    public static function getProductTypes()
    {
        return [
            self::PRODUCT_TYPE_DIGITAL => 'Digital Product',
            self::PRODUCT_TYPE_PHYSICAL => 'Physical Product',
        ];
    }

    public static function getSellerPublishStatuses()
    {
        return [
            self::SELLER_PUBLISH_STATUS_REQUEST => 'Request Product',
            self::SELLER_PUBLISH_STATUS_APPROVED => 'Approved Product',
            self::SELLER_PUBLISH_STATUS_DENIED => 'Denied Product',
        ];
    }

    public function isDigital()
    {
        return $this->product_type === self::PRODUCT_TYPE_DIGITAL;
    }

    public function isPhysical()
    {
        return $this->product_type === self::PRODUCT_TYPE_PHYSICAL;
    }

    public function isRequestProduct()
    {
        return $this->seller_publish_status === self::SELLER_PUBLISH_STATUS_REQUEST;
    }

    public function isApprovedProduct()
    {
        return $this->seller_publish_status === self::SELLER_PUBLISH_STATUS_APPROVED;
    }

    public function isDeniedProduct()
    {
        return $this->seller_publish_status === self::SELLER_PUBLISH_STATUS_DENIED;
    }

    public function getFileUrlAttribute()
    {
        if ($this->file && Storage::disk('public')->exists($this->file)) {
            return asset('storage/' . $this->file);
        }
        return null;
    }

    public function saveDigitalFile($file)
    {
                Log::info('saveDigitalFile called', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);

        if ($this->file) {
            Storage::disk('public')->delete($this->file);
            Log::info('Deleted old file', ['old_file' => $this->file]);
        }

        $filename = 'digital_products/' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        Log::info('Generated filename', ['filename' => $filename]);

        $result = $file->storeAs('', $filename, 'public');
        Log::info('File storage result', ['result' => $result, 'exists' => Storage::disk('public')->exists($filename)]);

        $this->file = $filename;
        $this->save();

        Log::info('Product saved with file', ['product_id' => $this->id, 'file' => $this->file]);

        return $filename;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $slug = Str::slug($product->name);
            $product->slug = self::makeUniqueSlug($slug);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $slug = Str::slug($product->name);
                $product->slug = self::makeUniqueSlug($slug, $product->id);
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
     * Get the seller that owns the product.
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the category of the product.
     */
    public function category()
    {
        return $this->belongsTo(SaasCategory::class);
    }

    /**
     * Get the subcategory of the product.
     */
    public function subcategory()
    {
        return $this->belongsTo(SaasSubCategory::class, 'subcategory_id');
    }

    /**
     * Get the child category of the product.
     */
    public function childCategory()
    {
        return $this->belongsTo(SaasChildCategory::class, 'child_category_id');
    }

    /**
     * Get the brand of the product.
     */
    public function brand()
    {
        return $this->belongsTo(SaasBrand::class);
    }

    /**
     * Get the unit of the product.
     */
    public function unit()
    {
        return $this->belongsTo(SaasUnit::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(SaasProductImage::class, 'product_id');
    }

    /**
     * Get the variations for the product.
     */
    public function variations()
    {
        return $this->hasMany(SaasProductVariation::class, 'product_id');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(SaasProductReview::class, 'product_id');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(SaasOrderItem::class, 'product_id');
    }

    /**
     * Get the wishlists that include this product.
     */
    public function wishlists()
    {
        return $this->hasMany(SaasWishlist::class, 'product_id');
    }

    /**
     * Get the flash deals that include this product.
     */
    public function flashDeals()
    {
        return $this->belongsToMany(SaasFlashDeal::class, 'saas_flash_deal_products', 'product_id', 'flash_deal_id')
            ->withPivot('discount_type', 'discount_value')
            ->withTimestamps();
    }

    /**
     * Get the final price after discount.
     */
    public function getFinalPriceAttribute()
    {
        $finalPrice = $this->price;

        if ($this->discount > 0) {
            if ($this->discount_type === 'percentage') {
                $finalPrice = $finalPrice - (($finalPrice * $this->discount) / 100);
            } else {
                $finalPrice = $finalPrice - $this->discount;
            }
        }

        return max(0, $finalPrice);
    }

    /**
     * Save the primary image for the product.
     */
    public function saveImage($image)
    {
        $filename = 'product_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('', $filename, 'public');

        $productImage = new SaasProductImage([
            'product_id' => $this->id,
            'image_url' => $filename
        ]);
        $productImage->save();

        return $productImage;
    }

    /**
     * Delete a product image.
     */
    public function deleteImage($imageId)
    {
        $image = $this->images()->findOrFail($imageId);

        if ($image->image_url) {
            Storage::disk('public')->delete($image->image_url);
        }

        return $image->delete();
    }
}
