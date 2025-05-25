<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
    ];

    /**
     * Get the attribute that owns the attribute value.
     */
    public function attribute()
    {
        return $this->belongsTo(SaasAttribute::class);
    }

    /**
     * Get the product variations for the attribute value.
     */
    public function productVariations()
    {
        return $this->hasMany(SaasProductVariation::class, 'attribute_value_id');
    }
}
