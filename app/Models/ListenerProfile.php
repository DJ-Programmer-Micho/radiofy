<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListenerProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'first_name',
        'last_name',
        'business_module',
        'country',
        'city',
        'address',
        'zip_code',
        'phone_number',
        'avatar',
    ];

    public function listener() { return $this->belongsTo(Listener::class); }
}
