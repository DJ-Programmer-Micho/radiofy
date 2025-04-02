<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'radioable_id',
        'radioable_type',
        'verification_id',
        'ad_dicount',
        'payment_frequency',
        'verified_date',
        'renew_date',
        'expire_date',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function radioable()
    {
        return $this->morphTo(); // Either internal or external radio
    }

    public function verification()
    {
        return $this->belongsTo(Verification::class);
    }
}
