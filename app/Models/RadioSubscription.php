<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'radio_configuration_id',
        'plan_id',
        'payment_frequency',
        'subscribed_date',
        'renew_date',
        'expire_date',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function radioConfiguration()
    {
        return $this->belongsTo(RadioConfiguration::class, 'radio_configuration_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
