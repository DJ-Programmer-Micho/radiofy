<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'first_name',
        'last_name',
        'country',
        'city',
        'address',
        'zip_code',
        'phone_number',
        'avatar',
    ];

    public function admin() { return $this->belongsTo(Admin::class); }

}
