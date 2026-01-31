<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperNotificationTemplate
 */
class NotificationTemplate extends Model
{
    protected $fillable = [
        'code',
        'name',
        'subject',
        'content',
        'type',
        'trigger_event',
        'is_active',
        'variables',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'template_id');
    }
}