<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperEmailLog
 */
class EmailLog extends Model
{
    protected $fillable = [
        'message_id',
        'from',
        'to',
        'subject',
        'status',
        'metadata',
        'sent_at',
        'delivered_at',
        'opened_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
    ];
}