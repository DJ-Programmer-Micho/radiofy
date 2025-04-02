<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'radioable_id',   // The ID for either the internal or external radio record
        'radioable_type', // The class name (e.g., App\Models\RadioConfiguration or App\Models\ExternalRadioConfiguration)
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

    // Polymorphic relationship: radioable can be either an internal or external radio.
    public function radioable()
    {
        return $this->morphTo();
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
