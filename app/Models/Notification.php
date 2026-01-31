<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperNotification
 */
class Notification extends Model
{
    protected $fillable = [
        'template_id',
        'notifiable_type',
        'notifiable_id',
        'subject',
        'content',
        'type',
        'status',
        'data',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}