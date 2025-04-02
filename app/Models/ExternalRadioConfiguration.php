<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalRadioConfiguration extends Model
{
    use HasFactory;
    
    protected $table = 'external_radio_configurations';
    protected $fillable = [
        'subscriber_id',
        'radio_name',
        'radio_name_slug',
        'stream_url',   
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
    public function external_radio_configuration_profile()
    {
        return $this->hasOne(ExternalRadioConfigurationProfile::class, 'radio_id');
    }
    // Polymorphic interactions
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
        return $this->external_radio_configuration_profile;
    }
    public function getTypeAttribute()
    {
        return 'external';
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
