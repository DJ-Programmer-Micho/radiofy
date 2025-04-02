<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\ListenerProfile;

class Listener extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'email_verify',
        'phone_verify',
        'email_otp_number',
        'phone_otp_number',
        'uid',
        'recent_radios',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'phone_verified_at'  => 'datetime',
        'recent_radios'      => 'array',
    ];

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function listener_profile()
    {
        return $this->hasOne(ListenerProfile::class, 'listener_id');
    }
    public function radioConfigurations()
    {
        return $this->belongsToMany(RadioConfiguration::class, 'listener_radio_configuration', 'listener_id', 'radio_configuration_id')
                    ->withTimestamps();
    }
    // Polymorphic interactions from the listener side
    public function followedInternalRadios()
    {
        return $this->morphedByMany(
            \App\Models\RadioConfiguration::class,
            'radioable',
            'listener_radios',
            'listener_id',
            'radioable_id'
        )->withTimestamps();
    }

    public function followedExternalRadios()
    {
        return $this->morphedByMany(
            \App\Models\ExternalRadioConfiguration::class,
            'radioable',
            'listener_radios',
            'listener_id',
            'radioable_id'
        )->withTimestamps();
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function follows()
    {
        return $this->hasMany(Follow::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
