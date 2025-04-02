<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioConfigurationProfile extends Model
{
    use HasFactory;
    
    protected $table = 'radio_configuration_profiles';
    protected $fillable = [
        'radio_id',
        'logo',
        'banner',
        'frequency',
        'location',
        'description',
        'meta_keywords',
        'social_media',
        'highest_peak_listeners',
        'radio_locale',
    ];

    public function radio_profile()
    {
        return $this->belongsTo(RadioConfiguration::class, 'radio_id');
    }
    public function languages()
    {
        return $this->belongsToMany(
            \App\Models\Language::class, 
            'radio_profile_language', 
            'radio_configuration_profile_id', 
            'language_id'
        )->withTimestamps();
    }
}
