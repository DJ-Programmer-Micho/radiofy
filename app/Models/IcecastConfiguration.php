<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcecastConfiguration extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'plan_id',
        'radio_name',
        'location',
        'server_admin',
        'server_password',
        'burst_size',
        'port',
        'bind_address',
        'source_password',
        'relay_password',
        'admin_password',
        'fallback_mount',
        'status',
    ];

    public function subscriber() {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }

    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
}
