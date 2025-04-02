<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sub_name',
        'sell',
        'duration',
        'priority',
        'status',
        'ribbon', 
        'rib_text',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
