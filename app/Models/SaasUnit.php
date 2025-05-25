<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the products that use this unit.
     */
    public function products()
    {
        return $this->hasMany(SaasProduct::class, 'unit_id');
    }
}
