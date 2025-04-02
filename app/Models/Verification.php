<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sell_price_monthly', 
        'sell_price_yearly',
        'discount_ad',
        'priority',
        'status',
        'features',
        'ribbon', 
        'rib_text'
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
