<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'listener_id',
        'radioable_id',
        'radioable_type',
        'comment_text',
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
