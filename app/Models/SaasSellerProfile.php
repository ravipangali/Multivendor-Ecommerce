<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SaasSellerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_name',
        'store_logo',
        'store_banner',
        'store_description',
        'is_approved',
        'address',
    ];

    /**
     * Get the user that owns the seller profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the seller.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'seller_id', 'user_id');
    }

    /**
     * Get the orders for the seller.
     */
    public function orders()
    {
        return $this->hasMany(SaasOrder::class, 'seller_id', 'user_id');
    }

    /**
     * Save store logo image
     */
    public function saveStoreLogo($image)
    {
        if ($this->store_logo) {
            Storage::disk('public')->delete($this->store_logo);
        }

        $filename = 'store_logos/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($filename);

        $this->update(['store_logo' => $filename]);

        return $filename;
    }

    /**
     * Save store banner image
     */
    public function saveStoreBanner($image)
    {
        if ($this->store_banner) {
            Storage::disk('public')->delete($this->store_banner);
        }

        $filename = 'store_banners/' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs( $filename);

        $this->update(['store_banner' => $filename]);

        return $filename;
    }

    /**
     * Get store logo URL
     */
    public function getStoreLogoUrlAttribute()
    {
        return $this->store_logo ? Storage::url($this->store_logo) : null;
    }

    /**
     * Get store banner URL
     */
    public function getStoreBannerUrlAttribute()
    {
        return $this->store_banner ? Storage::url($this->store_banner) : null;
    }
}
