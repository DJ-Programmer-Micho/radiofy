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
        'country',
        'city',
        'address',
        'zip_code',
        'phone_number',
        'avatar',
    ];

    public function subscriber() { return $this->belongsTo(Subscriber::class); }
}
