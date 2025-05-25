<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the attribute values for the attribute.
     */
    public function values()
    {
        return $this->hasMany(SaasAttributeValue::class, 'attribute_id');
    }

    /**
     * Get the product variations for the attribute.
     */
    public function productVariations()
    {
        return $this->hasMany(SaasProductVariation::class, 'attribute_id');
    }
}
