<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = ['bitrate', 'max_listeners', 'sell_price_monthly', 'sell_price_yearly', 'priority', 'support', 'status', 'ribbon', 'rib_text'];
}
