<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'country',
        'city',
        'address',
        'zip_code',
        'phone_number',
        'avatar',
        'news',
        'reg',
        'terms',
        'policy',
    ];

    public function subscriber() { return $this->belongsTo(Subscriber::class); }
}
