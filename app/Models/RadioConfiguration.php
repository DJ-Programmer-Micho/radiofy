<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioConfiguration extends Model
{
    use HasFactory;
    
    protected $table = 'radio_configurations';
    protected $fillable = [
        'subscriber_id',
        'plan_id',
        'radio_name',
        'radio_name_slug',
        'source',
        'source_password',
        'fallback_mount',
        'verified',
        'status',
    ];

    protected $casts = [
        'social_media' => 'array',
        'radio_locale' => 'array',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
    public function duration()
    {
        return $this->morphOne(RadioSubscription::class, 'radioable');
    }

    public function radio_configuration_profile()
    {
        return $this->hasOne(RadioConfigurationProfile::class, 'radio_id');
    }
    // Polymorphic interactions
    // App\Models\RadioConfiguration.php
    public function languages()
    {
        return $this->morphToMany(Language::class, 'languageable');
    }
    public function genres()
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }
    public function listeners()
    {
        return $this->morphToMany(Listener::class, 'radioable', 'listener_radios', 'radioable_id', 'listener_id')
                    ->withTimestamps();
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'radioable');
    }
    public function follows()
    {
        return $this->morphMany(Follow::class, 'radioable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'radioable');
    }

    // Call Methods
    public function getProfileAttribute()
    {
        return $this->radio_configuration_profile;
    }
    public function getTypeAttribute()
    {
        return 'internal';
    }
    protected static function booted()
    {
        static::deleting(function($radio) {
            // Delete all likes, follows, and comments associated with the radio.
            $radio->likes()->delete();
            $radio->follows()->delete();
            $radio->comments()->delete();
            
            // For a many-to-many relationship like listeners(), detach instead of delete.
            $radio->listeners()->detach();
        });
    }
}
