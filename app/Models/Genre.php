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
    ];

    // Assuming each genre has one translation for the default locale.
    public function genreTranslation()
    {
        return $this->hasOne(GenreTranslater::class, 'genre_id');
    }
    
    // Each genre can have many radio configurations.
    public function radioConfigurations()
    {
        return $this->hasMany(RadioConfiguration::class, 'genre_id');
    }
}
