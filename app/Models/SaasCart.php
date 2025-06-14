<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'variation_id',
        'variations_data',
        'variation_details',
        'quantity',
        'price',
    ];

    protected $casts = [
        'variations_data' => 'array',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product associated with this cart item.
     */
    public function product()
    {
        return $this->belongsTo(SaasProduct::class, 'product_id');
    }

    /**
     * Get the product variation if applicable.
     */
    public function productVariation()
    {
        return $this->belongsTo(SaasProductVariation::class, 'variation_id');
    }

    /**
     * Get all selected variations for this cart item.
     */
    public function getSelectedVariations()
    {
        if (empty($this->variations_data)) {
            return collect();
        }

        return SaasProductVariation::whereIn('id', $this->variations_data)->get();
    }

    /**
     * Get the highest price among selected variations.
     */
    public function getHighestVariationPrice()
    {
        $variations = $this->getSelectedVariations();

        if ($variations->isEmpty()) {
            return $this->product->final_price;
        }

        return $variations->max('final_price');
    }

    /**
     * Get formatted variation details for display.
     */
    public function getFormattedVariationDetails()
    {
        if (!empty($this->variation_details)) {
            return $this->variation_details;
        }

        $variations = $this->getSelectedVariations();
        if ($variations->isEmpty()) {
            return null;
        }

        $details = [];
        foreach ($variations as $variation) {
            $details[] = $variation->attribute->name . ': ' . $variation->attributeValue->value;
        }

        return implode(', ', $details);
    }

    /**
     * Check if this cart item has sufficient stock.
     */
    public function hasInsufficientStock()
    {
        if (empty($this->variations_data)) {
            // No variations, check product stock
            return $this->quantity > $this->product->stock;
        }

        // Check minimum stock among all selected variations
        $variations = $this->getSelectedVariations();
        $minStock = $variations->min('stock');

        return $this->quantity > $minStock;
    }

    /**
     * Get available stock for this cart item.
     */
    public function getAvailableStock()
    {
        if (empty($this->variations_data)) {
            return $this->product->stock;
        }

        $variations = $this->getSelectedVariations();
        return $variations->min('stock');
    }
}
