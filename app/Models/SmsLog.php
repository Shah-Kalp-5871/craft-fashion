<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSmsLog
 */
class SmsLog extends Model
{
    protected $fillable = [
        'message_id',
        'from',
        'to',
        'message',
        'status',
        'metadata',
        'sent_at',
        'delivered_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];
}