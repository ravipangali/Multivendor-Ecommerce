<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
