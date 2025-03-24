<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioConfiguration extends Model
{
    use HasFactory;
    
    // Recommended to fix the typo if possible
    protected $table = 'radio_configurations'; 
    protected $fillable = [
        'subscriber_id',
        'plan_id',
        'radio_name',
        'radio_name_slug',
        'source',
        'source_password',
        'fallback_mount',
        'description',
        'status',
    ];

    protected $casts = [
        'social_media'  => 'array',
        'radio_locale'  => 'array',
        'genres'        => 'array',
    ];

    
    public function subscriber() {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }

    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function duration()
    {
        return $this->hasOne(RadioSubscription::class, 'radio_configuration_id');
    }


    public function listeners()
    {
        return $this->belongsToMany(Listener::class, 'listener_radio_configuration', 'radio_configuration_id', 'listener_id')
                    ->withTimestamps();
    }

    public function genre()
    {
        return $this->belongsTo(\App\Models\Genre::class, 'genre_id');
    }

    public function radio_configuration_profile()
    {
        return $this->hasOne(RadioConfigurationProfile::class, 'radio_id');
    }

}
