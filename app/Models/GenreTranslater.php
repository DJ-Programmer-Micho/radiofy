<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenreTranslater extends Model
{
    use HasFactory;
    
    protected $table = 'genre_translations';
    protected $fillable = [
        'genre_id',
        'locale',
        'name',
        'slug',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
