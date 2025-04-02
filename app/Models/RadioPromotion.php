<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadioPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'radioable_id',      // new
        'radioable_type',
        'promotion_id',
        'verification_id',
        'promotion_text',
        'promotion_date',
        'expire_date',
        'price',
        'discount',
        'target_gender',       // new
        'target_age_range',
        'status',
        'new_listener_count',
    ];

    protected $casts = [
        'target_gender'    => 'array',
        'target_age_range' => 'array',
    ];

    // Optionally, you can define relationships:
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function verification()
    {
        return $this->belongsTo(RadioVerification::class);
    }

    // If your radio can be internal or external, you may define a polymorphic relationship:
    public function radioable()
    {
        return $this->morphTo();
    }

    public function getUniqueListenersCountAttribute()
    {
        if (!$this->radioable) {
            return 0;
        }
        return $this->radioable->listeners()
            ->where('listener_radios.created_at', '>=', $this->promotion_date)
            ->distinct()
            ->count('listener_id');
    }
}
