<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;
    
    protected $table = 'genres';
    protected $fillable = [
        'priority',
        'status',
        'image',
        'image_sq',
    ];

    // Each genre can have one translation for the default locale.
    public function genreTranslation()
    {
        return $this->hasOne(GenreTranslater::class, 'genre_id');
    }
    
    public function internalRadios()
    {
        return $this->morphedByMany(RadioConfiguration::class, 'genreable');
    }
    
    public function externalRadios()
    {
        return $this->morphedByMany(ExternalRadioConfiguration::class, 'genreable');
    }
    
}
