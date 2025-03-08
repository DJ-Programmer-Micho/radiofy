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
        'priority',
        'status',
    ];

    public function radioConfigurationProfiles()
    {
        return $this->belongsToMany(RadioConfigurationProfile::class, 'radio_profile_language', 'language_id', 'radio_configuration_profile_id')
                    ->withTimestamps();
    }
}
