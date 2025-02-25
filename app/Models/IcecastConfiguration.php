<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcecastConfiguration extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'radio_name',
        'location',
        'server_admin',
        'server_password',
        'max_listeners',    // number of clients
        'burst_size',
        'port',
        'bind_address',
        'source_password',
        'relay_password',
        'admin_password',
        'fallback_mount',
        'status',
    ];

    public function radio() { return $this->hasOne(Subscriber::class, 'subscriber_id'); }
}
