<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalRadioConfigurationProfile extends Model
{
    use HasFactory;
    
    protected $table = 'external_radio_configuration_profiles';
    protected $fillable = [
        'radio_id',
        'logo',
        'banner',
        'frequency',
        'location',
        'description',
        'meta_keywords',
        'genres',
        'highest_peak_listeners',
        'social_media',
        'radio_locale',
    ];

    public function radio_profile()
    {
        return $this->belongsTo(ExternalRadioConfiguration::class, 'radio_id');
    }
    public function languages()
    {
        // Use a dedicated pivot table for external profiles if desired
        return $this->belongsToMany(
            \App\Models\Language::class, 
            'external_radio_profile_language', 
            'external_radio_configuration_profile_id', 
            'language_id'
        )->withTimestamps();
    }
}
