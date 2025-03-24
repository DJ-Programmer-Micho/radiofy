<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListenerProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'listener_id',
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

    public function listener() { return $this->belongsTo(Listener::class); }
}
