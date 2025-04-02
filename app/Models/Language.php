<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'image',
        'image_sq',
        'priority',
        'status',
    ];

    public function internalRadios()
    {
        return $this->morphedByMany(RadioConfiguration::class, 'languageable');
    }

    public function externalRadios()
    {
        return $this->morphedByMany(ExternalRadioConfiguration::class, 'languageable');
    }

}
