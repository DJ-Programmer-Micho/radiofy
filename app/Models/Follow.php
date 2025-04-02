<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'listener_id',
        'radioable_id',
        'radioable_type',
    ];

    public function radioable()
    {
        return $this->morphTo();
    }
    public function listener()
    {
        return $this->belongsTo(\App\Models\Listener::class);
    }
}
